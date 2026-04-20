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
    // Tạo 3 danh mục mẫu
    \App\Models\Category::create(['name' => 'Học tập', 'color' => 'blue']);
    \App\Models\Category::create(['name' => 'Công việc', 'color' => 'red']);
    \App\Models\Category::create(['name' => 'Cá nhân', 'color' => 'green']);
}
}
