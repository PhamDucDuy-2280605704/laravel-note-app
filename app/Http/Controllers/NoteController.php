<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Note;
use App\Models\Category;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class NoteController extends Controller
{
    /**
     * 1. Xem danh sách ghi chú (Có phân trang và tìm kiếm)
     */
    public function index(Request $request) {
        $search = $request->query('search');
        $categoryId = $request->query('category_id');

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
        
        $categories = Category::select('id', 'name')->get()->unique('name'); 

        return view('note-danh-sach', compact('notes', 'categories'));
    }

    /**
     * 2. Hiện form thêm mới
     */
    public function create() {
        $categories = Category::select('id', 'name')->get()->unique('name'); 
        return view('note-them', compact('categories'));
    }

    /**
     * 3. Lưu ghi chú mới
     */
    public function store(Request $request) {
        $request->validate([
            'noidung' => 'required|min:5|max:255',
            'category_id' => 'required|exists:categories,id',
            'image' => 'nullable|image|mimes:jpg,jpeg,png|max:2048'
        ]);

        $data = [
            'content' => $request->noidung,
            'category_id' => $request->category_id,
        ];

        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('notes_images', 'public');
            $data['image'] = $path;
        }

        Auth::user()->notes()->create($data);

        return redirect()->route('notes.index')->with('success', 'Đã thêm ghi chú thành công! ✨');
    }

    /**
     * 4. Hiện form sửa (Lưu lại URL cũ vào session)
     */
    public function edit($id) {
        $note = Note::findOrFail($id);

        if ($note->user_id !== Auth::id() && !Auth::user()->hasRole('admin')) {
            abort(403, 'Bạn không có quyền sửa ghi chú này!');
        }

        // Lưu lại trang trước đó để sau khi Update xong thì quay về đúng chỗ này
        if (!session()->has('url.intended')) {
            session(['url.intended' => url()->previous()]);
        }

        $categories = Category::select('id', 'name')->get()->unique('name');

        return view('note-sua', compact('note', 'categories'));
    }

    /**
     * 5. Cập nhật dữ liệu (Nắn lại luồng để không bị nhảy trang)
     */
    public function update(Request $request, $id) {
        $note = Note::findOrFail($id);

        if ($note->user_id !== Auth::id() && !Auth::user()->hasRole('admin')) {
            abort(403);
        }
        
        $request->validate([
            'noidung' => 'required|min:5|max:255',
            'category_id' => 'required|exists:categories,id',
            'image' => 'nullable|image|mimes:jpg,jpeg,png|max:2048'
        ]);

        $data = [
            'content' => $request->noidung,
            'category_id' => $request->category_id
        ];

        if ($request->hasFile('image')) {
            if ($note->image) {
                Storage::disk('public')->delete($note->image);
            }
            $path = $request->file('image')->store('notes_images', 'public');
            $data['image'] = $path;
        }

        $note->update($data);

        // Lấy URL cũ từ session, nếu không có thì mới về index
        $url = session()->pull('url.intended', route('notes.index'));

        return redirect($url)->with('success', 'Cập nhật thành công! ✅');
    }

    /**
     * 6. Ghim/Bỏ ghim (Dùng back để đứng im tại chỗ)
     */
    public function pin($id) {
        $note = Note::findOrFail($id);
        if ($note->user_id !== Auth::id() && !Auth::user()->hasRole('admin')) abort(403);
        
        $note->update(['is_pinned' => !$note->is_pinned]);
        
        return back()->with('success', 'Đã thay đổi trạng thái ghim!');
    }

    /**
     * 7. Xóa tạm thời
     */
    public function destroy($id) {
        $note = Note::findOrFail($id);
        if ($note->user_id !== Auth::id() && !Auth::user()->hasRole('admin')) abort(403);
        
        $note->delete();
        
        return back()->with('success', 'Đã chuyển vào thùng rác.');
    }

    /**
     * 8. Xem thùng rác
     */
    public function trash() {
        $notes = Auth::user()->notes()->onlyTrashed()->get();
        return view('note-trash', compact('notes'));
    }

    /**
     * 9. Khôi phục
     */
    public function restore($id) {
        $note = Auth::user()->notes()->onlyTrashed()->findOrFail($id);
        $note->restore();
        
        return redirect()->route('notes.trash')->with('success', 'Đã khôi phục!');
    }

    /**
     * 10. Xóa vĩnh viễn
     */
    public function forceDelete($id) {
        $note = Auth::user()->notes()->onlyTrashed()->findOrFail($id);
        
        if ($note->image) {
            Storage::disk('public')->delete($note->image);
        }

        $note->forceDelete();
        
        return redirect()->route('notes.trash')->with('success', 'Đã xóa vĩnh viễn và dọn dẹp ảnh.');
    }
}