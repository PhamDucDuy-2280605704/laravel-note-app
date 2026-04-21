<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Laravel\Socialite\Facades\Socialite; // Quan trọng
use Illuminate\Support\Facades\Auth;    // Quan trọng
use Illuminate\Support\Str;             // Để dùng Str::random
use Exception;

class GoogleController extends Controller
{
    /**
     * Điều hướng người dùng sang Google
     */
    public function redirectToGoogle()
    {
        return Socialite::driver('google')->redirect();
    }

    /**
     * Xử lý dữ liệu Google trả về
     */
    public function handleGoogleCallback()
    {
        try {
            $googleUser = Socialite::driver('google')->user();
            
            // 1. Tìm xem email này đã có trong hệ thống chưa?
            $user = User::where('email', $googleUser->email)->first();

            if ($user) {
                // Nếu đã có User (tạo bằng tay trước đó), cập nhật google_id để lần sau đồng bộ
                $user->update([
                    'google_id' => $googleUser->id,
                ]);
            } else {
                // Nếu chưa có thì mới tạo mới hoàn toàn
                $user = User::create([
                    'name' => $googleUser->name,
                    'email' => $googleUser->email,
                    'google_id' => $googleUser->id,
                    'password' => bcrypt(Str::random(16)), // Mật khẩu ngẫu nhiên để bảo mật
                    'email_verified_at' => now(),           // Google xác thực rồi, không bắt user check mail nữa
                ]);
                
                // Gán quyền mặc định là 'user'
                $user->assignRole('user');
            }

            // Đăng nhập vào hệ thống
            Auth::login($user);

            // Chuyển hướng về Dashboard
            return redirect()->route('dashboard');

        } catch (Exception $e) {
            // Log lỗi nếu cần: \Log::error($e->getMessage());
            return redirect('/login')->with('error', 'Đăng nhập Google thất bại. Vui lòng thử lại!');
        }
    }
}