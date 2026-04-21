<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Note extends Model
{
    use SoftDeletes; 

    // CẬP NHẬT: Thêm 'image' vào đây để Laravel cho phép lưu đường dẫn ảnh
    protected $fillable = [
        'content', 
        'user_id', 
        'category_id', 
        'is_pinned', 
        'image'
    ];

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}