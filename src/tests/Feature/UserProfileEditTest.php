<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\User;

class UserProfileEditTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function プロフィール編集画面に初期値が正しく表示される()
    {
      
        $user = User::factory()->create([
            'name' => 'テスト太郎',
            'postal_code' => '123-4567',
            'address' => 'テスト県テスト市1-2-3',
            'building' => 'テストマンション101',
            'avatar' => 'avatars/test.png',
            'email_verified_at' => now(),
            'profile_completed' => true,
        ]);


        $response = $this->actingAs($user)
            ->get(route('mypage.profile'));


        $response->assertStatus(200);


        $response->assertSee('テスト太郎');
        $response->assertSee('123-4567');
        $response->assertSee('テスト県テスト市1-2-3');
        $response->assertSee('テストマンション101');

    
        $response->assertSee('avatars/test.png');
    }
}
