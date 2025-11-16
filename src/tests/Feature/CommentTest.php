<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\User;
use App\Models\Item;
use App\Models\Comment;

class CommentTest extends TestCase
{
    use RefreshDatabase;


    public function test_ログイン済みユーザーはコメントを送信できる()
    {
        $user = User::factory()->create([
            'email_verified_at' => now(),
            'profile_completed' => true,
        ]);
        $item = Item::factory()->create();

        $response = $this->actingAs($user)
            ->post("/item/{$item->id}/comments", [
                'content' => 'これはテストコメントです。',
            ]);

        $response->assertStatus(302);


        $this->assertDatabaseHas('comments', [
            'user_id' => $user->id,
            'item_id' => $item->id,
            'content' => 'これはテストコメントです。',
        ]);

       
        $this->assertEquals(1, Comment::count());
    }


    public function test_ログイン前ユーザーはコメントを送信できない()
    {
        $item = Item::factory()->create();

        $response = $this->post("/item/{$item->id}/comments", [
            'content' => '未ログインコメント',
        ]);

        
        $response->assertRedirect('/login');

        $this->assertDatabaseMissing('comments', [
            'content' => '未ログインコメント',
        ]);
    }

    public function test_コメントが空の場合バリデーションエラーとなる()
    {
        $user = User::factory()->create([
            'email_verified_at' => now(),
            'profile_completed' => true,
        ]);
        $item = Item::factory()->create();

        $response = $this->actingAs($user)
            ->from("/item/{$item->id}")
            ->post("/item/{$item->id}/comments", [
                'content' => '',
            ]);

     
        $response->assertSessionHasErrors([
            'content' => 'コメントを入力してください',
        ]);
    }

 
    public function test_コメントが255文字以上の場合バリデーションエラーとなる()
    {
        $user = User::factory()->create([
            'email_verified_at' => now(),
            'profile_completed' => true,
        ]);
        $item = Item::factory()->create();

        $longComment = str_repeat('あ', 256);

        $response = $this->actingAs($user)
            ->from("/item/{$item->id}")
            ->post("/item/{$item->id}/comments", [
                'content' => $longComment,
            ]);

        
        $response->assertSessionHasErrors([
            'content' => 'コメントは255文字以内で入力してください',
        ]);
    }
}
