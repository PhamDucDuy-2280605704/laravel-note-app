<div>
    {{-- Thông báo thành công --}}
    @if (session()->has('success'))
        <div class="mb-4 p-4 bg-green-100 text-green-700 rounded-xl border border-green-200">
            {{ session('success') }}
        </div>
    @endif

    {{-- 1. FORM THÊM NHANH --}}
    <div class="mb-8 p-6 bg-white rounded-2xl shadow-sm border border-blue-100">
        <h3 class="text-sm font-bold text-blue-600 mb-4 uppercase">Thêm ghi chú mới</h3>
        <div class="flex flex-col md:flex-row gap-3">
            <input wire:model="noidung" wire:keydown.enter="store" type="text" placeholder="Cậu đang nghĩ gì..." class="flex-1 rounded-xl border-gray-200 focus:ring-blue-500">
            
            <select wire:model="categoryId" class="rounded-xl border-gray-200">
                <option value="">Chọn danh mục</option>
                @foreach($categories as $cat)
                    <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                @endforeach
            </select>

            <button wire:click="store" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-xl transition">Lưu</button>
        </div>
        @error('noidung') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
    </div>

    {{-- 2. THANH TÌM KIẾM & BỘ LỌC --}}
    <div class="mb-6 flex flex-col md:flex-row gap-4 border-b pb-6">
        <div class="relative flex-1">
            <span class="absolute inset-y-0 left-0 pl-3 flex items-center text-gray-400">🔍</span>
            <input wire:model.live="search" type="text" placeholder="Tìm nhanh theo nội dung..." class="pl-10 rounded-xl border-gray-200 w-full focus:ring-blue-500">
        </div>
        
        <select wire:model.live="categoryId" class="rounded-xl border-gray-200">
            <option value="">Tất cả danh mục</option>
            @foreach($categories as $cat)
                <option value="{{ $cat->id }}">{{ $cat->name }}</option>
            @endforeach
        </select>
    </div>

    {{-- 3. DANH SÁCH GHI CHÚ --}}
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        @forelse($notes as $item)
            <div class="p-6 bg-white rounded-2xl shadow-sm border relative hover:shadow-md transition group">
                {{-- Icon Ghim --}}
                @if($item->is_pinned)
                    <span class="absolute top-2 right-2 text-xl" title="Đã ghim">📌</span>
                @endif
                
                {{-- Tag Danh mục --}}
                <span class="text-xs font-medium text-blue-500 bg-blue-50 px-2 py-1 rounded-lg mb-2 inline-block">
                    {{ $item->category->name ?? 'Không phân loại' }}
                </span>

                <p class="text-gray-700 leading-relaxed">{{ $item->content }}</p>
                
                {{-- Nút thao tác (Hiện khi di chuột vào) --}}
                <div class="mt-4 flex gap-3 border-t pt-4 opacity-0 group-hover:opacity-100 transition">
                    <button wire:click="pinNote({{ $item->id }})" class="text-sm font-medium text-gray-600 hover:text-blue-600">
                        {{ $item->is_pinned ? 'Bỏ ghim' : 'Ghim' }}
                    </button>
                    <button wire:click="deleteNote({{ $item->id }})" class="text-sm font-medium text-red-500 hover:text-red-700">
                        Xóa
                    </button>
                </div>
            </div>
        @empty
            <div class="col-span-3 text-center py-12 text-gray-400 bg-gray-50 rounded-2xl border-2 border-dashed">
                Không tìm thấy ghi chú nào cả... 
            </div>
        @endforelse
    </div>

    {{-- Phân trang --}}
    <div class="mt-8">
        {{ $notes->links() }}
    </div>
</div>