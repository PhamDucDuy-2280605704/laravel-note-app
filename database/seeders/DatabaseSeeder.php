<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Category;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Tạo Roles
        $adminRole = Role::firstOrCreate(['name' => 'admin']);
        $userRole = Role::firstOrCreate(['name' => 'user']);

        // 2. Tạo Admin (Dùng email thật của bạn ở đây)
        $admin = User::factory()->create([
            'name' => 'Admin Duy',
            'email' => 'pduy14102004@gmail.com',
            'password' => bcrypt('12345678'),
            'email_verified_at' => now(),
        ]);
        $admin->assignRole($adminRole);

        // 3. Tạo User ảo để test
        $testUser = User::factory()->create([
            'name' => 'Người dùng ảo',
            'email' => 'user-test@gmail.com',
            'password' => bcrypt('12345678'),
            'email_verified_at' => now(), // Đã xác thực sẵn
        ]);
        $testUser->assignRole($userRole);

        // 4. Tạo Category mẫu
        Category::create(['name' => 'Công việc']);
        Category::create(['name' => 'Cá nhân']);
    }
}