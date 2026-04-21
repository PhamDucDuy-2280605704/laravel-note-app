<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Note;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminController extends Controller
{
    /**
     * Hiển thị danh sách người dùng và vai trò của họ
     */
    public function index()
    {
        // Sử dụng with('roles') để lấy dữ liệu quan hệ từ thư viện Spatie
        $users = User::with('roles')->paginate(10); 
        
        return view('admin.users.index', compact('users'));
    }

    /**
     * Xem tất cả ghi chú của mọi người trong hệ thống
     */
    public function allNotes()
    {
        // Eager load 'user' để tránh lỗi N+1 khi hiển thị tên người tạo ghi chú
        $notes = Note::with('user')->latest()->paginate(20);
        
        return view('admin.notes.all', compact('notes'));
    }

    /**
     * Thay đổi quyền của người dùng (Admin/User)
     */
    public function changeRole(Request $request, User $user)
    {
        // Bảo vệ: Không cho phép tự đổi quyền của chính mình để tránh mất quyền truy cập Admin
        if ($user->id === Auth::id()) {
            return back()->with('error', 'Bạn không thể tự thay đổi quyền của chính mình!');
        }

        $request->validate([
            'role' => 'required|exists:roles,name',
        ]);

        // Đồng bộ vai trò mới (Xóa vai trò cũ, thêm vai trò mới)
        $user->syncRoles($request->role);

        return back()->with('success', "Đã cập nhật quyền cho {$user->name} thành công!");
    }
}