<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\Item;
use App\Models\User;


class SearchTest extends TestCase
{
    use RefreshDatabase;

    public function test_商品名で部分一致検索ができる()
    {
   
        $matchingItem = Item::factory()->create([
            'name' => '赤いバッグ'
        ]);

     
        $notMatchingItem = Item::factory()->create([
            'name' => '青い靴'
        ]);

        $response = $this->get('/?query=バッグ');


        $response->assertStatus(200);


        $response->assertSee($matchingItem->name);


        $response->assertDontSee($notMatchingItem->name);
    }

    public function test_検索状態がマイリストでも保持されている()
    {
      
        $user = User::factory()->create([
            'email_verified_at' => now(),
            'profile_completed' => true,
        ]);


        $item = Item::factory()->create([
            'name' => '赤いバッグ',
        ]);


        $user->likes()->create([
            'item_id' => $item->id,
        ]);

        $responseHome = $this
            ->actingAs($user)
            ->get('/?query=バッグ&tab=recommend');


        $responseHome->assertSee('value="バッグ"', false);



        $responseMylist = $this
            ->actingAs($user)
            ->get('/?tab=mylist&query=バッグ');


        $responseMylist->assertSee('value="バッグ"', false);
    }

}
