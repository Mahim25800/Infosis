<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Url;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $user = User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
        ]);

        Url::create([
            'user_id' => $user->id,
            'original_url' => 'https://laravel.com/docs',
            'short_code' => 'laravel-docs',
            'click_count' => 0,
        ]);
    }
}
