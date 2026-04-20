<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Note;
use App\Models\Category; 
use Illuminate\Support\Facades\Auth;

class NoteController extends Controller
{
    /**
     * 1. Xem danh sách (Có phân trang 6 cái/trang và ưu tiên Ghim)
     */
    public function index(Request $request) {
        $search = $request->query('search');
        $categoryId = $request->query('category_id');

        $query = Note::with('category')->where('user_id', Auth::id());

        if ($search) {
            $query->where('content', 'LIKE', "%{$search}%");
        }

        if ($categoryId) {
            $query->where('category_id', $categoryId);
        }

        // Thay .get() bằng .paginate(6) để chia trang
        $notes = $query->orderBy('is_pinned', 'desc')->latest()->paginate(6);
        
        $categories = Category::all(); 

        return view('note-danh-sach', compact('notes', 'categories'));
    }

    /**
     * Ghim hoặc Bỏ ghim ghi chú
     */
    public function pin($id) {
        $note = Note::where('id', $id)->where('user_id', Auth::id())->firstOrFail();
        
        $note->update([
            'is_pinned' => !$note->is_pinned
        ]);

        return redirect()->back();
    }

    /**
     * 2. Hiện form thêm
     */
    public function create() {
        $categories = Category::all(); 
        return view('note-them', compact('categories'));
    }

    /**
     * 3. Lưu ghi chú
     */
    public function store(Request $request) {
        $request->validate([
            'noidung' => 'required|min:5|max:255',
            'category_id' => 'required|exists:categories,id'
        ]);

        Note::create([
            'content' => $request->noidung,
            'user_id' => Auth::id(),
            'category_id' => $request->category_id,
        ]);

        return redirect()->route('notes.index');
    }

    /**
     * 4. Hiện form sửa
     */
    public function edit($id) {
        $note = Note::where('id', $id)->where('user_id', Auth::id())->firstOrFail();
        $categories = Category::all();
        return view('note-sua', compact('note', 'categories'));
    }

    /**
     * 5. Cập nhật
     */
    public function update(Request $request, $id) {
        $note = Note::where('id', $id)->where('user_id', Auth::id())->firstOrFail();
        
        $note->update([
            'content' => $request->noidung,
            'category_id' => $request->category_id
        ]);

        return redirect()->route('notes.index');
    }

    /**
     * 6. Xóa vào thùng rác
     */
    public function destroy($id) {
        $note = Note::where('id', $id)->where('user_id', Auth::id())->firstOrFail();
        $note->delete();
        return redirect()->route('notes.index');
    }

    /**
     * --- THÙNG RÁC ---
     */
    public function trash() {
        $notes = Note::onlyTrashed()->where('user_id', Auth::id())->get();
        return view('note-trash', compact('notes'));
    }

    public function restore($id) {
        $note = Note::withTrashed()->where('id', $id)->where('user_id', Auth::id())->firstOrFail();
        $note->restore();
        return redirect()->route('notes.trash');
    }

    public function forceDelete($id) {
        $note = Note::withTrashed()->where('id', $id)->where('user_id', Auth::id())->firstOrFail();
        $note->forceDelete();
        return redirect()->route('notes.trash');
    }
}