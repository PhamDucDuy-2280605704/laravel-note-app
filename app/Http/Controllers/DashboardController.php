<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Note;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $data = [];

        if ($user->hasRole('admin')) {
            // DỮ LIỆU CHO ADMIN
            $data['totalUsers'] = User::count();
            $data['totalNotes'] = Note::count();
            $data['totalTrash'] = Note::onlyTrashed()->count();
            // Lấy 5 ghi chú mới nhất toàn hệ thống kèm tên người tạo
            $data['recentNotes'] = Note::with('user')->latest()->take(5)->get();
        } else {
            // DỮ LIỆU CHO USER THƯỜNG
            $data['myNotesCount'] = $user->notes()->count();
            $data['myPinnedCount'] = $user->notes()->where('is_pinned', true)->count();
            $data['myTrashCount'] = $user->notes()->onlyTrashed()->count();
            // Lấy 5 ghi chú mới nhất của riêng họ
            $data['recentNotes'] = $user->notes()->latest()->take(5)->get();
        }

        return view('dashboard', compact('data'));
    }
}