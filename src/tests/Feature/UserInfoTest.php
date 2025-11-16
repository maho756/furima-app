<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\User;
use App\Models\Item;
use App\Models\Order;

class UserInfoTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function test_プロフィールページで必要な情報が取得できる()
    {
     
        $user = User::factory()->create([
            'email_verified_at' => now(),
            'profile_completed' => true,
            'name' => 'テストユーザー',
            'avatar' => null, 
        ]);

    
        $sellItem = Item::factory()->create([
            'user_id' => $user->id,
            'name' => '出品した商品A',
        ]);


        $buyItem = Item::factory()->create([
            'name' => '購入した商品B',
        ]);

        Order::factory()->create([
            'user_id' => $user->id,
            'item_id' => $buyItem->id,
        ]);


        $response = $this->actingAs($user)
            ->get(route('mypage.index'));


        $response->assertSee('default-avatar.png');


        $response->assertSee('テストユーザー');


        $response->assertSee('出品した商品A');

        $responseBuy = $this->actingAs($user)
            ->get(route('mypage.index', ['page' => 'buy']));

        $responseBuy->assertSee('購入した商品B');
    }
}
