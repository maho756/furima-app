<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\User;
use App\Models\Item;
use App\Models\Order;

class AddressChangeTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function test_住所変更画面で登録した住所が購入画面に反映される()
    {
      
        $user = User::factory()->create([
            'email_verified_at' => now(),
            'profile_completed' => true,
            'postal_code' => '000-0000',
            'address' => '初期市0-0-0',
            'building' => '初期ビル0',
        ]);

        $item = Item::factory()->create();

       
        $changedAddress = [
            'postal_code' => '123-4567',
            'address' => '変更市1-2-3',
            'building' => '変更ビル101',
        ];

    
        $response = $this->actingAs($user)
            ->get(route('purchase.index', array_merge(['item_id' => $item->id], $changedAddress)));

    
        $response->assertSee('123-4567');
        $response->assertSee('変更市1-2-3');
        $response->assertSee('変更ビル101');
    }

    public function test_購入した商品に変更後の住所が紐づいて保存される()
    {
        $user = User::factory()->create([
            'email_verified_at' => now(),
            'profile_completed' => true,
        ]);

        $item = Item::factory()->create([
            'sold_out' => false,
        ]);

        $changedAddress = [
            'postal_code' => '987-6543',
            'address' => '新市9-8-7',
            'building' => '新ビル202',
        ];

    
        $this->actingAs($user)
            ->post(route('purchase.store', $item->id), array_merge([
                'payment_method' => 'コンビニ払い',
            ], $changedAddress));

       
        $this->assertDatabaseHas('orders', [
            'user_id' => $user->id,
            'item_id' => $item->id,
            'shipping_postal_code' => '987-6543',
            'shipping_address' => '新市9-8-7',
            'shipping_building' => '新ビル202',
        ]);

    }
}
