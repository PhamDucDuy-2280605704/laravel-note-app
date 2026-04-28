<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Note;
use App\Models\Category;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;

class NoteController extends Controller
{
    /**
     * Lấy danh sách categories duy nhất để tránh lỗi id on string
     */
    private function getUniqueCategories() {
        return Category::select('id', 'name')
            ->whereIn('id', function($query) {
                $query->select(DB::raw('MIN(id)'))
                      ->from('categories')
                      ->groupBy('name');
            })->get();
    }

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
        
        $categories = $this->getUniqueCategories();

        return view('note-danh-sach', compact('notes', 'categories'));
    }

    public function create() {
        $categories = $this->getUniqueCategories();
        return view('note-them', compact('categories'));
    }

    public function edit($id) {
        $note = Note::findOrFail($id);

        if ($note->user_id !== Auth::id() && !Auth::user()->hasRole('admin')) {
            abort(403, 'Bạn không có quyền sửa ghi chú này!');
        }

        if (!session()->has('url.intended')) {
            session(['url.intended' => url()->previous()]);
        }

        $categories = $this->getUniqueCategories();

        return view('note-sua', compact('note', 'categories'));
    }

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

    public function update(Request $request, $id) {
        $note = Note::findOrFail($id);
        if ($note->user_id !== Auth::id() && !Auth::user()->hasRole('admin')) abort(403);
        
        $request->validate([
            'noidung' => 'required|min:5|max:255',
            'category_id' => 'required|exists:categories,id',
            'image' => 'nullable|image|mimes:jpg,jpeg,png|max:2048'
        ]);

        $data = ['content' => $request->noidung, 'category_id' => $request->category_id];

        if ($request->hasFile('image')) {
            if ($note->image) Storage::disk('public')->delete($note->image);
            $data['image'] = $request->file('image')->store('notes_images', 'public');
        }

        $note->update($data);
        $url = session()->pull('url.intended', route('notes.index'));
        return redirect($url)->with('success', 'Cập nhật thành công! ✅');
    }

    public function pin($id) {
        $note = Note::findOrFail($id);
        if ($note->user_id !== Auth::id() && !Auth::user()->hasRole('admin')) abort(403);
        $note->update(['is_pinned' => !$note->is_pinned]);
        return back()->with('success', 'Đã thay đổi trạng thái ghim!');
    }

    public function destroy($id) {
        $note = Note::findOrFail($id);
        if ($note->user_id !== Auth::id() && !Auth::user()->hasRole('admin')) abort(403);
        $note->delete();
        return back()->with('success', 'Đã chuyển vào thùng rác.');
    }

    public function trash() {
        $notes = Auth::user()->notes()->onlyTrashed()->get();
        return view('note-trash', compact('notes'));
    }

    public function restore($id) {
        $note = Auth::user()->notes()->onlyTrashed()->findOrFail($id);
        $note->restore();
        return redirect()->route('notes.trash')->with('success', 'Đã khôi phục!');
    }

    public function forceDelete($id) {
        $note = Auth::user()->notes()->onlyTrashed()->findOrFail($id);
        if ($note->image) Storage::disk('public')->delete($note->image);
        $note->forceDelete();
        return redirect()->route('notes.trash')->with('success', 'Đã xóa vĩnh viễn.');
    }
}