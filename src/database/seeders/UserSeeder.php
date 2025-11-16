<?php
namespace Database\Seeders;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        User::insert([
            [
                'id' => 1,
                'name' => 'ユーザー1',
                'email' => 'user1@example.com',
                'password' => Hash::make('password'),
                'email_verified_at' => now(),
                'postal_code' => '123-4567',
                'address' => '東京都新宿区○○',
                'building' => 'ビル301',
                'profile_completed' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => 2,
                'name' => 'ユーザー2',
                'email' => 'user2@example.com',
                'password' => Hash::make('password'),
                'email_verified_at' => now(),
                'postal_code' => '333-3456',
                'address' => '大阪府大阪市',
                'building' => 'ビル41',
                'profile_completed' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => 3,
                'name' => 'ユーザー3',
                'email' => 'user3@example.com',
                'password' => Hash::make('password'),
                'email_verified_at' => now(),
                'postal_code' => '239-7856',
                'address' => '鹿児島県鹿児島市',
                'building' => 'ビル563',
                'profile_completed' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
