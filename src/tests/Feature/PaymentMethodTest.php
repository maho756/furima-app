<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\User;
use App\Models\Item;

class PaymentMethodTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function test_支払い方法を選択すると小計画面に反映される()
    {
        
        $user = User::factory()->create([
            'email_verified_at' => now(),
            'profile_completed' => true,
            'postal_code' => '111-1111',
            'address' => 'テスト市1-2-3',
        ]);

 
        $item = Item::factory()->create();

   
        $response = $this->actingAs($user)
            ->get(route('purchase.index', [
                'item_id' => $item->id,
                'payment_method' => 'クレジットカード'
            ]));

      
        $response->assertStatus(200)
                 ->assertSee('クレジットカード');
    }
}
