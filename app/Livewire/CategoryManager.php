<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Category;
use Livewire\Attributes\Validate;

class CategoryManager extends Component
{
    #[Validate('required|string|max:50')]
    public $name = '';

    #[Validate('nullable|string|max:20')]
    public $color = '';

    public function store()
    {
        $this->validate();

        Category::create([
            'name' => $this->name,
            'color' => $this->color ?: 'gray'
        ]);

        session()->flash('message', '✅ Tạo danh mục "' . $this->name . '" thành công!');
        $this->reset(['name', 'color']);
        $this->dispatch('refreshComponent'); // 🔄 Refresh list
    }

    public function delete($id)
    {
        $category = Category::find($id);
        
        if (!$category) {
            session()->flash('error', '❌ Danh mục không tồn tại!');
            return;
        }

        if ($category->notes()->count() > 0) {
            session()->flash('error', '❌ Không thể xóa! Có ' . $category->notes()->count() . ' ghi chú.');
            return;
        }

        $category->delete();
        session()->flash('message', '🗑️ Đã xóa danh mục!');
        $this->dispatch('refreshComponent'); // 🔄 Refresh list
    }

    public function render()
    {
        return view('livewire.category-manager', [
            'categories' => Category::withCount('notes')
                               ->orderBy('name')
                               ->get()
        ]);
    }
}