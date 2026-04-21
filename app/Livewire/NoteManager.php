<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Note;
use App\Models\Category;
use Livewire\WithFileUploads; // Bắt buộc phải có để upload ảnh
use Illuminate\Support\Facades\Auth;

class NoteManager extends Component
{
    use WithFileUploads;

    public $noidung = '';
    public $categoryId = '';
    public $image; // Biến tạm lưu file ảnh
    
    // Hàm lưu Task
    public function store()
    {
        $this->validate([
            'noidung' => 'required|min:3',
            'categoryId' => 'required',
            'image' => 'nullable|image|max:1024', // Ảnh tối đa 1MB
        ]);

        $imagePath = null;
        if ($this->image) {
            // Lưu ảnh vào thư mục storage/app/public/notes
            $imagePath = $this->image->store('notes', 'public');
        }

        Auth::user()->notes()->create([
            'content' => $this->noidung,
            'category_id' => $this->categoryId,
            'image' => $imagePath,
            'status' => 'todo',
        ]);

        // Reset form sau khi lưu
        $this->reset(['noidung', 'categoryId', 'image']);
        session()->flash('success', 'Đã thêm task mới thành công!');
    }

    public function render()
    {
        $notes = Auth::user()->notes();
        return view('livewire.note-manager', [
            'categories' => Category::all(),
            'todo' => (clone $notes)->where('status', 'todo')->latest()->get(),
            'doing' => (clone $notes)->where('status', 'doing')->latest()->get(),
            'done' => (clone $notes)->where('status', 'done')->latest()->get(),
        ]);
    }
}