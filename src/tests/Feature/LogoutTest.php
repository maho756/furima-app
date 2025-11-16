<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;

class LogoutTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_ログアウトができる()
    {
       
        $user = User::factory()->create([
            'email_verified_at' => now(),
            'profile_completed' => true,
            'password' => bcrypt('password'),
        ]);

        $this->actingAs($user);

   
        $response = $this->post('/logout');

 
        $response->assertRedirect('/');


        $this->assertGuest();
    }

}
