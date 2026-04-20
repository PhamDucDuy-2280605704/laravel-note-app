<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail; // Import interface này
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Relations\HasMany;

class User extends Authenticatable implements MustVerifyEmail // Thêm implements vào đây
{
    use HasFactory, Notifiable;

    /**
     * Các cột được phép lưu dữ liệu.
     */
    protected $fillable = [
        'name', 
        'email', 
        'password', 
        'google_id', 
        'email_verified_at',
    ];

    /**
     * Các cột cần ẩn khi trả về dữ liệu.
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Ép kiểu dữ liệu cho các cột.
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    /**
     * Liên kết: 1 User có nhiều Ghi chú.
     */
    public function notes(): HasMany
    {
        return $this->hasMany(Note::class);
    }
}