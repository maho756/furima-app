<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;
use App\Models\Item;


class MylistTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_いいねした商品だけがマイリストに表示される()
    {
        
        $user = User::factory()->create([
            'email_verified_at' => now(),
            'profile_completed' => true,
        ]);

   
        $likedItem = Item::factory()->create();

    
        $unlikedItem = Item::factory()->create();

   
        $user->likes()->create([
            'item_id' => $likedItem->id,
        ]);

   
        $response = $this->actingAs($user)->get('/?tab=mylist');

        $response->assertStatus(200);


        $response->assertSee($likedItem->name);

  
        $response->assertDontSee($unlikedItem->name);
    } 

    public function test_マイリスト内の購入済み商品にはSoldと表示される()
    {
      
        $user = User::factory()->create([
            'email_verified_at' => now(),
            'profile_completed' => true,
        ]);

  
        $soldItem = Item::factory()->create([
            'sold_out' => true,
        ]);

 
        $user->likes()->create([
            'item_id' => $soldItem->id,
        ]);

        $response = $this->actingAs($user)->get('/?tab=mylist');

        $response->assertStatus(200);

        $response->assertSee('SOLD OUT');
    }

    public function test_未ログインの場合はマイリストは表示されずログイン画面へリダイレクトされる()
    {
       
        $response = $this->get('/?tab=mylist');

      
        $response->assertStatus(302);

        $response->assertRedirect('/login');
    }


}
