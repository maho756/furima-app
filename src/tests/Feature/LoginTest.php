<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;


class LoginTest extends TestCase
{
    use RefreshDatabase;
    protected $middleware = true;

    public function test_メールアドレスが入力されていない場合、バリデーションメッセージが表示される()
    {
        $response = $this->from('/login')->post('/login',[
            'email' => '',
            'password' => 'password',
        ]);

        $response->assertSessionHasErrors([
            'email' => 'メールアドレスを入力してください',
        ]);

        $response->assertRedirect('/login');
    }

    public function test_パスワードが入力されていない場合、バリデーションメッセージが表示される()
    {
        $response = $this->from('/login')->post('/login',[
            'email' => 'test@example.com',
            'password' => '',
        ]);

        $response->assertSessionHasErrors([
            'password' => 'パスワードを入力してください',
        ]);

        $response->assertRedirect('/login');
    }
    
    public function test_入力情報が間違っている場合、バリデーションメッセージが表示される()
    {
        $response = $this->from('/login')->post('/login', [
            'email' => 'notfound@example.com',
            'password' => 'wrongpassword',
        ]);

        $response->assertSessionHasErrors([
            'login' => 'ログイン情報が登録されていません',
        ]);

        $response->assertRedirect('/login');
    }

    public function test_認証済みでプロフィール設定済みユーザーはマイリストにリダイレクトされる()
    {
        $user = User::factory()->create([
            'email_verified_at' => now(), 
            'profile_completed' => true, 
            'password' => bcrypt('password'),
        ]);

        $response = $this->post('/login', [
            'email' => $user->email,
            'password' => 'password',
        ]);

        $response->assertRedirect('/?tab=mylist');

        $this->assertAuthenticatedAs($user);
    }
    
}
