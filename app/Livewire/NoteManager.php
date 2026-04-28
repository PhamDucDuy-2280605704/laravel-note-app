<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithFileUploads;
use App\Models\Note;
use App\Models\Category;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Livewire\Attributes\Validate;
use Livewire\Attributes\Computed;

class NoteManager extends Component
{
    use WithFileUploads;

    #[Validate('required|min:5|max:1000')]
    public $noidung = '';

    #[Validate('required|exists:categories,id')]
    public $categoryId = '';

    #[Validate('nullable|image|max:2048')]
    public $image;

    public $iteration = 1;

    /**
     * Computed Property: Luôn lấy danh sách danh mục mới nhất từ DB.
     * Dùng $this->categories trong View thay vì biến public để tránh lỗi Hydration.
     */
    #[Computed]
    public function categories()
    {
        return Category::orderBy('name', 'asc')->get();
    }

    /**
     * Hàm mount chạy khi component được khởi tạo lần đầu.
     */
    public function mount()
    {
        $defaultCategories = [
            ['name' => 'Công việc', 'color_code' => '#3b82f6'],
            ['name' => 'Cá nhân', 'color_code' => '#10b981'],
            ['name' => 'Học tập', 'color_code' => '#8b5cf6'],
        ];

        foreach ($defaultCategories as $cat) {
            /**
             * logic: Nếu tên đã tồn tại -> Cập nhật màu (không tạo mới).
             * Nếu tên chưa có -> Tạo mới hoàn toàn.
             */
            Category::updateOrCreate(
                ['name' => $cat['name']], 
                ['color_code' => $cat['color_code']]
            );
        }
    }

    public function store()
    {
        $this->validate();

        $path = $this->image ? $this->image->store('notes_images', 'public') : null;

        Note::create([
            'content'     => $this->noidung,
            'category_id' => $this->categoryId,
            'image'       => $path,
            'status'      => 'todo',
            'user_id'     => Auth::id(),
        ]);

        // Reset form và tăng iteration để xóa input file cũ
        $this->reset(['noidung', 'categoryId', 'image']);
        $this->iteration++; 
        session()->flash('message', 'Thêm mới thành công! ✨');
    }

    public function updateStatus($id, $status)
    {
        // Đảm bảo chỉ cập nhật đúng ghi chú của user đang đăng nhập
        Note::where('id', $id)->where('user_id', Auth::id())->update(['status' => $status]);
    }

    public function destroy($id)
    {
        $note = Note::where('id', $id)->where('user_id', Auth::id())->first();
        if ($note) {
            if ($note->image) {
                Storage::disk('public')->delete($note->image);
            }
            $note->delete();
            session()->flash('message', 'Đã xóa ghi chú! ✅');
        }
    }

    public function render()
    {
        $userId = Auth::id();
        return view('livewire.note-manager', [
            'todo'  => Note::where('user_id', $userId)->where('status', 'todo')->with('category')->latest()->get(),
            'doing' => Note::where('user_id', $userId)->where('status', 'doing')->with('category')->latest()->get(),
            'done'  => Note::where('user_id', $userId)->where('status', 'done')->with('category')->latest()->get(),
        ]);
    }
}