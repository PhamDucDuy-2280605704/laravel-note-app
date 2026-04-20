<?php

namespace App\Models; // Địa chỉ nhà của file này trong hệ thống Laravel

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes; // Nạp công cụ "Xóa mềm"

class Note extends Model
{
    /**
     * 1. SỬ DỤNG SOFT DELETES (XÓA MỀM)
     * Khi cậu gọi lệnh xóa, Laravel sẽ không xóa vĩnh viễn dòng này trong DB
     * mà chỉ điền ngày giờ vào cột 'deleted_at'.
     * Điều này giúp cậu có tính năng "Thùng rác" để khôi phục lại khi cần.
     */
    use SoftDeletes; 

    /**
     * 2. BIẾN $fillable (CHO PHÉP NHẬP LIỆU)
     * Đây là một "màng lọc" bảo mật. 
     * Cậu khai báo những cột nào được phép dùng lệnh Note::create() hoặc $note->update().
     * Nếu cậu không thêm 'is_pinned' vào đây, khi cậu lưu, Laravel sẽ lờ nó đi.
     */
    protected $fillable = [
        'content',     // Nội dung ghi chú
        'user_id',     // ID của người tạo (để biết ghi chú này của ai)
        'category_id', // ID danh mục (để phân loại: Học tập, Làm việc...)
        'is_pinned'    // Trạng thái ghim (1 là ghim, 0 là không)
    ];

    /**
     * 3. MỐI QUAN HỆ (RELATIONSHIP)
     * Hàm này cực kỳ quan trọng để cậu "nối" hai bảng lại với nhau.
     */
    public function category() 
    {
        // Nó nói rằng: "Một ghi chú này chỉ thuộc về DUY NHẤT một danh mục"
        // Nhờ dòng này, ở ngoài View cậu mới gọi được: $item->category->name
        return $this->belongsTo(Category::class);
    }
}