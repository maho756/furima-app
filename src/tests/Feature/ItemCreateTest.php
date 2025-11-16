<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\User;
use App\Models\Category;
use App\Models\Item;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

class ItemCreateTest extends TestCase
{
    use RefreshDatabase;

  
    public function test_商品出品画面から必要な情報が保存される()
    {
        Storage::fake('public');
    
        $user = User::factory()->create([
            'email_verified_at' => now(),
            'profile_completed' => true,
        ]);

        $categories = Category::factory()->count(3)->create();

        $response = $this->actingAs($user)->post(route('items.store'), [
            'image'       => UploadedFile::fake()->image('test.jpg'),
            'name'  => 'テスト商品',
            'brand'       => 'テストブランド',
            'description' => 'これはテスト用の商品説明です。',
            'categories'  => $categories->pluck('id')->toArray(),
            'condition'   => '良好',
            'price'       => 5000,
        ]);


        $response->assertStatus(302);

        $item = Item::first();

    
        $this->assertDatabaseHas('items', [
            'name'  => 'テスト商品',
            'brand'       => 'テストブランド',
            'description' => 'これはテスト用の商品説明です。',
            'condition'   => '良好',
            'price'       => 5000,
        ]);

        foreach ($categories as $category) {
            $this->assertDatabaseHas('category_item', [
                'item_id'     => $item->id,
                'category_id' => $category->id,
            ]);
        }
    }
}
