<?php

namespace App\Http\Controllers;

use Laravel\Socialite\Facades\Socialite;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Exception;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class GoogleController extends Controller
{
    public function redirectToGoogle()
    {
        return Socialite::driver('google')->redirect();
    }

    public function handleGoogleCallback()
    {
        try {
            $user = Socialite::driver('google')->user();
            $finduser = User::where('email', $user->email)->first();

            if($finduser){
                // Nếu User đã có, cập nhật ID Google nếu chưa có và đăng nhập
                if(!$finduser->google_id){
                    $finduser->update(['google_id' => $user->id]);
                }
                Auth::login($finduser);
                return redirect()->intended('dashboard');
            } else {
                // Tạo User mới và tự động xác thực email
                $newUser = User::create([
                    'name' => $user->name,
                    'email' => $user->email,
                    'google_id'=> $user->id,
                    'password' => Hash::make(Str::random(16)), // Pass ngẫu nhiên bảo mật
                    'email_verified_at' => now(), 
                ]);

                Auth::login($newUser);
                return redirect()->intended('dashboard');
            }
        } catch (Exception $e) {
            return redirect('login')->with('error', 'Lỗi đăng nhập Google: ' . $e->getMessage());
        }
    }
}