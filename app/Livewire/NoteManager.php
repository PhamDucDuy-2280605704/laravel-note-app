<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithFileUploads;
use App\Models\Note;
use App\Models\Category;

class NoteManager extends Component
{
    use WithFileUploads;

    public $categoryId, $noidung, $image, $iteration = 1;

    public function render()
    {
        return view('livewire.note-manager', [
            'todo' => Note::where('status', 'todo')->get(),
            'doing' => Note::where('status', 'doing')->get(),
            'done' => Note::where('status', 'done')->get(),
            'categories' => Category::all(),
        ]);
    }

    public function store()
    {
        $this->validate([
            'noidung' => 'required|min:5',
            'categoryId' => 'required',
            'image' => 'nullable|image|max:2048',
        ]);

        $path = $this->image ? $this->image->store('notes_images', 'public') : null;

        Note::create([
            'content' => $this->noidung,
            'category_id' => $this->categoryId,
            'image' => $path,
            'status' => 'todo',
            'user_id' => auth()->id(),
        ]);

        $this->reset(['noidung', 'categoryId', 'image']);
        $this->iteration++; // Để reset input file
    }

    public function updateStatus($id, $status)
    {
        Note::find($id)->update(['status' => $status]);
    }

    public function destroy($id)
    {
        Note::find($id)->delete();
    }
}