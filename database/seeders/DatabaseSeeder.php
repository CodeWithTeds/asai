<?php

namespace Database\Seeders;

use App\Models\User;
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
        User::factory()->create([
            'name' => 'User One',
            'email' => 'user1@example.com',
        ]);

        User::factory()->create([
            'name' => 'User Two',
            'email' => 'user2@example.com',
        ]);

        User::factory()->create([
            'name' => 'User Three',
            'email' => 'user3@example.com',
        ]);

        $this->call([
            EventAndJobPostingSeeder::class,
        ]);
    }
}
