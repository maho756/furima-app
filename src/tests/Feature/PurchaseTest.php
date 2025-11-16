<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\User;
use App\Models\Item;
use App\Models\Order;

class PurchaseTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function test_購入ボタンを押すと購入が完了する()
    {
        $user = User::factory()->create([
            'email_verified_at' => now(),
            'profile_completed' => true,
            'postal_code' => '111-1111',
            'address' => 'テスト市1-2-3',
            'building' => 'テストビル101',
        ]);

        $item = Item::factory()->create([
            'sold_out' => false,
        ]);


        $this->actingAs($user)
            ->get(route('purchase.index', $item->id))
            ->assertStatus(200);

        $response = $this->actingAs($user)
            ->post(route('purchase.store', $item->id), [
                'payment_method' => 'コンビニ払い', 
            ]);

        $response->assertStatus(302); 


        $this->assertDatabaseHas('orders', [
            'user_id' => $user->id,
            'item_id' => $item->id,
        ]);


        $this->assertDatabaseHas('items', [
            'id'       => $item->id,
            'sold_out' => true,
        ]);


    }

    /** @test */
    public function test_購入した商品は商品一覧で_sold_として表示される()
    {
        $user = User::factory()->create([
            'email_verified_at' => now(),
            'profile_completed' => true,
            'postal_code' => '111-1111',
            'address' => 'テスト市1-2-3',
            'building' => 'テストビル101',
        ]);

        $item = Item::factory()->create([
            'sold_out' => false,
        ]);

        $this->actingAs($user)
            ->post(route('purchase.store', $item->id), [
                'payment_method' => 'クレジットカード',
            ]);

        $item->refresh();

        $response = $this->actingAs($user)
            ->get('/');

        
        $response->assertSee('sold_out.png');
    }

    /** @test */
    public function test_購入した商品はプロフィールの購入一覧に表示される()
    {
        $user = User::factory()->create([
            'email_verified_at' => now(),
            'profile_completed' => true,
            'postal_code' => '111-1111',
            'address' => 'テスト市1-2-3',
            'building' => 'テストビル101',
        ]);

        $item = Item::factory()->create([
            'sold_out' => false,
        ]);

  
        $this->actingAs($user)
            ->post(route('purchase.store', $item->id), [
                'payment_method' => 'コンビニ払い',
            ]);


        $response = $this->actingAs($user)
            ->get(route('mypage.index', ['page' => 'buy']));


        $response->assertSee($item->name);
    }
}
