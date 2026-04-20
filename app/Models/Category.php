<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory; // Công cụ giúp tạo dữ liệu mẫu nhanh (thường dùng khi test app)

    /**
     * 1. BIẾN $fillable
     * Cậu chỉ cho phép lưu 2 thông tin:
     * - 'name': Tên danh mục (ví dụ: Quan trọng, Học tập...)
     * - 'color': Màu sắc đại diện (ví dụ: blue, red, green...) để hiển thị nhãn màu ngoài giao diện.
     */
    protected $fillable = ['name', 'color'];

    /**
     * 2. MỐI QUAN HỆ (RELATIONSHIP) - Ngược với Note
     * Nếu bên Note dùng 'belongsTo' (thuộc về), thì bên này dùng 'hasMany' (có nhiều).
     */
    public function notes()
    {
        // Nó nói rằng: "Một danh mục này có thể chứa NHIỀU ghi chú khác nhau"
        // Ví dụ: Danh mục "Học tập" có thể có 10 cái ghi chú bài tập bên trong.
        return $this->hasMany(Note::class);
    }
}