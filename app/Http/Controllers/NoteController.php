<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Note;
use App\Models\Category; 
use Illuminate\Support\Facades\Auth;

class NoteController extends Controller
{
    /**
     * 1. Xem danh sách ghi chú
     */
    public function index(Request $request) {
        $search = $request->query('search');
        $categoryId = $request->query('category_id');

        // Chỉ lấy ghi chú của người đang đăng nhập
        $query = Auth::user()->notes()->with('category');

        if ($search) {
            $query->where('content', 'LIKE', "%{$search}%");
        }

        if ($categoryId) {
            $query->where('category_id', $categoryId);
        }

        $notes = $query->orderBy('is_pinned', 'desc')
                       ->latest()
                       ->paginate(6)
                       ->withQueryString();
        
        $categories = Category::all(); 

        return view('note-danh-sach', compact('notes', 'categories'));
    }

    /**
     * 2. Hiện form thêm mới (Cái Duy đang bị thiếu đây nè!)
     */
    public function create() {
        $categories = Category::all(); 
        return view('note-them', compact('categories'));
    }

    /**
     * 3. Lưu ghi chú vào database
     */
    public function store(Request $request) {
        $request->validate([
            'noidung' => 'required|min:5|max:255',
            'category_id' => 'required|exists:categories,id'
        ]);

        Auth::user()->notes()->create([
            'content' => $request->noidung,
            'category_id' => $request->category_id,
        ]);

        return redirect()->route('notes.index')->with('success', 'Đã thêm ghi chú thành công!');
    }

    /**
     * 4. Hiện form sửa ghi chú
     */
    public function edit($id) {
        $note = Auth::user()->notes()->findOrFail($id);
        $categories = Category::all();
        return view('note-sua', compact('note', 'categories'));
    }

    /**
     * 5. Cập nhật dữ liệu sau khi sửa
     */
    public function update(Request $request, $id) {
        $note = Auth::user()->notes()->findOrFail($id);
        
        $request->validate([
            'noidung' => 'required|min:5|max:255',
            'category_id' => 'required|exists:categories,id'
        ]);

        $note->update([
            'content' => $request->noidung,
            'category_id' => $request->category_id
        ]);

        return redirect()->route('notes.index')->with('success', 'Đã cập nhật ghi chú!');
    }

    /**
     * 6. Ghim hoặc bỏ ghim
     */
    public function pin($id) {
        $note = Auth::user()->notes()->findOrFail($id);
        $note->update(['is_pinned' => !$note->is_pinned]);
        return redirect()->back();
    }

    /**
     * 7. Xóa tạm thời (Vào thùng rác)
     */
    public function destroy($id) {
        $note = Auth::user()->notes()->findOrFail($id);
        $note->delete();
        return redirect()->route('notes.index')->with('success', 'Đã chuyển vào thùng rác.');
    }

    /**
     * 8. Xem danh sách thùng rác
     */
    public function trash() {
        $notes = Auth::user()->notes()->onlyTrashed()->get();
        return view('note-trash', compact('notes'));
    }

    /**
     * 9. Khôi phục ghi chú
     */
    public function restore($id) {
        $note = Auth::user()->notes()->onlyTrashed()->findOrFail($id);
        $note->restore();
        return redirect()->route('notes.trash')->with('success', 'Đã khôi phục ghi chú!');
    }

    /**
     * 10. Xóa vĩnh viễn
     */
    public function forceDelete($id) {
        $note = Auth::user()->notes()->onlyTrashed()->findOrFail($id);
        $note->forceDelete();
        return redirect()->route('notes.trash')->with('success', 'Đã xóa vĩnh viễn.');
    }
}