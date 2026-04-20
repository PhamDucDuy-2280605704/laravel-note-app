<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
   public function run(): void
{
    \App\Models\Category::create(['name' => 'Học tập']);
    \App\Models\Category::create(['name' => 'Công việc']);
    \App\Models\Category::create(['name' => 'Cá nhân']);
    \App\Models\Category::create(['name' => 'Quan trọng']);
}
}
