<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Post;
use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        User::factory()->create([
            'name' => 'Jose Garcia',
            'email' => 'pepe@wedo.com',
            'password' => bcrypt('12345678'),
            'is_admin' => '1',
        ]);

        User::factory(5)->create();

        Category::factory(10)->create();
        Post::factory(100)->create();

        $this->call(
            PermissionSeeder::class, 
            RoleSeeder::class,
        );
    }
}
