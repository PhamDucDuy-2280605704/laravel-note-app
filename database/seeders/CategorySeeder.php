<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    public function run()
    {
        $categories = [
            ['name' => 'Công việc', 'color' => 'blue'],
            ['name' => 'Cá nhân', 'color' => 'green'],
            ['name' => 'Học tập', 'color' => 'purple'],
            ['name' => 'Gia đình', 'color' => 'pink'],
            ['name' => 'Tài chính', 'color' => 'yellow'],
            ['name' => 'Sức khỏe', 'color' => 'red'],
            ['name' => 'Giải trí', 'color' => 'indigo'],
        ];

        foreach ($categories as $cat) {
            Category::firstOrCreate(
                ['name' => $cat['name']],
                $cat
            );
        }
    }
}