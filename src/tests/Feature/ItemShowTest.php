<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;
use App\Models\Item;
use App\Models\Category;
use App\Models\Comment;
use App\Models\Like;

class ItemShowTest extends TestCase
{
    use RefreshDatabase;

    public function test_商品詳細ページに必要な情報が表示される()
    {
       
        $user = User::factory()->create();

      
        $category = Category::factory()->create([
            'name' => 'バッグ',
        ]);

      
        $item = Item::factory()->create([
            'name'        => 'テストバッグ',
            'brand'       => 'テストブランド',
            'price'       => 9999,
            'description' => 'これはテスト用の商品説明です。',
            'condition'   => '良好',
            'user_id'     => $user->id,
        ]);


        $item->categories()->attach($category->id);

   
        $commentUser = User::factory()->create();
        Comment::factory()->create([
            'item_id' => $item->id,
            'user_id' => $commentUser->id,
            'content' => 'とても良い商品ですね！',
        ]);


        Like::factory()->create([
            'item_id' => $item->id,
            'user_id' => $commentUser->id,
        ]);


        $response = $this->get(route('item.show', $item->id));

      
        $response->assertStatus(200);

        $response->assertSee('テストバッグ');                       
        $response->assertSee('テストブランド');                 
        $response->assertSee('¥9,999');
    
        $response->assertSee('良好');                             
        $response->assertSee('バッグ');                           
        $response->assertSee('これはテスト用の商品説明です。');   

        
        $response->assertSee('1');

       
        $response->assertSee('1');

       
        $response->assertSee('とても良い商品ですね！');

        
        $response->assertSee($commentUser->name);
    }

    public function test_商品詳細ページで複数カテゴリが表示される()
    {
      
        $user = User::factory()->create();

     
        $category1 = Category::factory()->create(['name' => 'バッグ']);
        $category2 = Category::factory()->create(['name' => '靴']);
        $category3 = Category::factory()->create(['name' => 'アクセサリー']);

   
        $item = Item::factory()->create([
            'name'     => 'テストバッグ',
            'brand'    => 'テストブランド',
            'price'    => 5000,
            'condition'=> '良好',
            'user_id'  => $user->id,
        ]);

  
        $item->categories()->attach([$category1->id, $category2->id, $category3->id]);

        $response = $this->get(route('item.show', $item->id));

 
        $response->assertStatus(200);

  
        $response->assertSee('バッグ');
        $response->assertSee('靴');
        $response->assertSee('アクセサリー');
    }
}
