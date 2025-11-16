<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\Item;
use App\Models\User;

class ItemIndexTest extends TestCase
{
    use RefreshDatabase;
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_ログインしていなくても全商品が表示される()
    {
      
        $items = Item::factory()->count(3)->create();

       
        $response = $this->get('/');

      
        $response->assertStatus(200);

       
        foreach ($items as $item) {
            $response->assertSee($item->name);
        }
    }

    public function test_購入済み商品にはSOLD_OUT画像が表示される()
    {
     
        $item = Item::factory()->create([
            'sold_out' => true,
        ]);

       
        $response = $this->get('/');

       
        $response->assertStatus(200);

      
        $response->assertSee('SOLD OUT');
    }

    public function test_自分が出品した商品は一覧に表示されない()
    {

        $user = User::factory()->create([
            'email_verified_at' => now(),
            'profile_completed' => true,
        ]);

     
        $myItem = Item::factory()->create([
            'user_id' => $user->id,
        ]);

     
        $otherItem = Item::factory()->create();

      
        $response = $this->actingAs($user)->get('/');

       
        $response->assertStatus(200);

      
        $response->assertDontSeeText($myItem->name);

        
        $response->assertSee($otherItem->name);
    }


}
