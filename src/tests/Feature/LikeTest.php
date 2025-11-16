<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\User;
use App\Models\Item;
use App\Models\Like;

class LikeTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function test_いいねを登録できる()
    {
        $user = User::factory()->create([
            'email_verified_at' => now(),
            'profile_completed' => true,
        ]);
        $item = Item::factory()->create();

        $this->actingAs($user)
            ->get(route('item.show', $item->id))
            ->assertSee('like.png');

        $response = $this->actingAs($user)
            ->post("/item/{$item->id}/like");

        $response->assertStatus(200)
            ->assertJson([
                'liked' => true,
                'count' => 1,
            ]);

        $this->assertDatabaseHas('likes', [
            'user_id' => $user->id,
            'item_id' => $item->id,
        ]);

        $this->actingAs($user)
            ->get(route('item.show', $item->id))
            ->assertSee('liked.png');
    }


    public function test_いいね済みアイコンの画像が変化する()
    {
        $user = User::factory()->create([
            'email_verified_at' => now(),
            'profile_completed' => true,
        ]);
        $item = Item::factory()->create();


        Like::factory()->create([
            'user_id' => $user->id,
            'item_id' => $item->id,
        ]);


        $this->actingAs($user)
            ->get(route('item.show', $item->id))
            ->assertSee('liked.png');
    }

    public function test_いいね解除できる()
    {
        $user = User::factory()->create([
            'email_verified_at' => now(),
            'profile_completed' => true,
        ]);
        $item = Item::factory()->create();

        Like::factory()->create([
            'user_id' => $user->id,
            'item_id' => $item->id,
        ]);

        $response = $this->actingAs($user)
            ->delete("/item/{$item->id}/like");

        $response->assertStatus(200)
            ->assertJson([
                'liked' => false,
                'count' => 0,
            ]);

 
        $this->assertDatabaseMissing('likes', [
            'user_id' => $user->id,
            'item_id' => $item->id,
        ]);


        $this->actingAs($user)
            ->get(route('item.show', $item->id))
            ->assertSee('like.png');
    }
}
