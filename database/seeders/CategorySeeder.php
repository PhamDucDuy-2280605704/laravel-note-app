<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Category;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            'Học tập',
            'Công việc',
            'Cá nhân',
            'Ý tưởng',
            'Quan trọng'
        ];

        foreach ($categories as $name) {
            // updateOrCreate sẽ kiểm tra cột 'name'
            // Nếu đã có 'Học tập' trong DB, nó sẽ không tạo dòng mới nữa.
            Category::updateOrCreate(
                ['name' => $name], // Điều kiện tìm kiếm
                ['name' => $name]  // Dữ liệu cập nhật/tạo mới
            );
        }
    }
}