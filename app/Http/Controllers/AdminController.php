<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Note;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function index()
    {
        // Lấy danh sách user và vai trò của họ
        $users = User::with('roles')->paginate(10);
        return view('admin.users.index', compact('users'));
    }

    public function allNotes()
    {
        // Admin xem được tất cả ghi chú của mọi người
        $notes = Note::with('user', 'category')->latest()->paginate(20);
        return view('admin.notes.all', compact('notes'));
    }

    public function changeRole(Request $request, User $user)
    {
        // Không cho phép tự đổi quyền chính mình
        if ($user->id === auth()->id()) {
            return back()->with('error', 'Không thể tự đổi quyền bản thân!');
        }
        $user->syncRoles($request->role);
        return back()->with('success', 'Cập nhật quyền thành công!');
    }
}