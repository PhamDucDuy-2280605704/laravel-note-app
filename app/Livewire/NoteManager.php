<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Note;
use App\Models\Category;
use Illuminate\Support\Facades\Auth;

class NoteManager extends Component
{
    use WithPagination;

    // Các biến dùng chung
    public $search = '';
    public $categoryId = '';
    public $noidung = ''; // Biến để thêm ghi chú mới

    // Tự động reset về trang 1 khi lọc hoặc tìm kiếm
    public function updatingSearch() { $this->resetPage(); }
    public function updatingCategoryId() { $this->resetPage(); }

    /**
     * THÊM GHI CHÚ MỚI
     */
    public function store() {
        $this->validate([
            'noidung' => 'required|min:5|max:255',
            'categoryId' => 'required|exists:categories,id',
        ]);

        Auth::user()->notes()->create([
            'content' => $this->noidung,
            'category_id' => $this->categoryId,
        ]);

        $this->noidung = ''; // Xóa sạch ô nhập sau khi lưu
        session()->flash('success', 'Đã thêm ghi chú mới! ✨');
    }

    /**
     * GHIM GHI CHÚ
     */
    public function pinNote($id) {
        $note = Note::findOrFail($id);
        if ($note->user_id === Auth::id()) {
            $note->update(['is_pinned' => !$note->is_pinned]);
        }
    }

    /**
     * XÓA TẠM THỜI
     */
    public function deleteNote($id) {
        $note = Note::findOrFail($id);
        if ($note->user_id === Auth::id()) {
            $note->delete();
            session()->flash('success', 'Đã xóa tạm thời!');
        }
    }

    public function render() {
        $query = Auth::user()->notes()->with('category');

        if ($this->search) {
            $query->where('content', 'LIKE', "%{$this->search}%");
        }

        if ($this->categoryId) {
            $query->where('category_id', $this->categoryId);
        }

        return view('livewire.note-manager', [
            'notes' => $query->orderBy('is_pinned', 'desc')->latest()->paginate(6),
            'categories' => Category::all()
        ]);
    }
}