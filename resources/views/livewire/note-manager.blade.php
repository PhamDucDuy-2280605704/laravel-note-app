<div class="max-w-7xl mx-auto px-4 py-8">
    {{-- 1. FORM THÊM TASK "FULL OPTION" --}}
    <div class="mb-10 bg-white p-8 rounded-3xl shadow-xl border border-gray-100">
        <h2 class="text-2xl font-bold text-gray-800 mb-6 flex items-center gap-2">
            Thêm công việc mới 📝
        </h2>

        <div class="grid grid-cols-1 gap-6">
            {{-- Hàng 1: Chọn phân loại --}}
            <div>
                <label class="block text-sm font-bold text-gray-700 mb-2">Phân loại:</label>
                <select wire:model="categoryId" class="w-full rounded-xl border-gray-200 focus:ring-orange-500 focus:border-orange-500">
                    <option value="">-- Chọn danh mục --</option>
                    @foreach($categories as $cat)
                        <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                    @endforeach
                </select>
                @error('categoryId') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
            </div>

            {{-- Hàng 2: Nội dung --}}
            <div>
                <label class="block text-sm font-bold text-gray-700 mb-2">Nội dung:</label>
                <textarea wire:model="noidung" rows="3" placeholder="Nhập nội dung công việc..." 
                    class="w-full rounded-xl border-gray-200 focus:ring-orange-500 focus:border-orange-500"></textarea>
                @error('noidung') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
            </div>

            {{-- Hàng 3: Hình ảnh --}}
            <div>
                <label class="block text-sm font-bold text-gray-700 mb-2">Hình ảnh:</label>
                <div class="flex items-center gap-4">
                    <input type="file" wire:model="image" id="upload-{{ $iteration ?? 1 }}" class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-orange-50 file:text-orange-700 hover:file:bg-orange-100">
                </div>
                
                {{-- Xem trước ảnh khi đang chọn --}}
                @if ($image)
                    <div class="mt-3 relative inline-block">
                        <img src="{{ $image->temporaryUrl() }}" class="w-32 h-32 object-cover rounded-xl shadow-md border border-orange-200">
                        <button wire:click="$set('image', null)" class="absolute -top-2 -right-2 bg-red-500 text-white rounded-full w-6 h-6 text-xs shadow-lg">✕</button>
                    </div>
                @endif
            </div>

            {{-- Nút bấm --}}
            <div class="flex justify-end gap-3 mt-4 border-t pt-6">
                <button wire:click="$set('noidung', '')" class="px-6 py-2 text-gray-500 font-bold hover:bg-gray-100 rounded-xl transition">Hủy bỏ</button>
                <button wire:click="store" wire:loading.attr="disabled" class="px-8 py-3 bg-orange-500 hover:bg-orange-600 text-white font-bold rounded-2xl shadow-lg shadow-orange-200 transition disabled:opacity-50">
                    <span wire:loading.remove>Cập nhật ngay</span>
                    <span wire:loading>Đang lưu...</span>
                </button>
            </div>
        </div>
    </div>

    {{-- 2. BẢNG TIẾN ĐỘ 3 CỘT --}}
    <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
        
        {{-- Mảng các cột để render cho gọn --}}
        @php
            $columns = [
                ['title' => 'Cần làm', 'color' => 'gray', 'data' => $todo, 'next' => 'doing', 'btn' => 'BẮT ĐẦU NGAY →'],
                ['title' => 'Đang thực hiện', 'color' => 'blue', 'data' => $doing, 'next' => 'done', 'btn' => 'HOÀN THÀNH ✓'],
                ['title' => 'Đã xong', 'color' => 'green', 'data' => $done, 'next' => 'todo', 'btn' => 'LÀM LẠI'],
            ];
        @endphp

        @foreach($columns as $col)
            <div class="bg-{{ $col['color'] }}-50/50 p-6 rounded-3xl border-2 border-dashed border-{{ $col['color'] }}-200">
                <div class="flex items-center gap-2 mb-6 font-bold text-{{ $col['color'] }}-600 uppercase tracking-wider text-sm">
                    <span class="w-3 h-3 bg-{{ $col['color'] }}-500 rounded-full shadow-lg"></span>
                    {{ $col['title'] }}
                </div>

                @foreach($col['data'] as $item)
                    <div class="bg-white p-5 rounded-2xl shadow-sm mb-4 border border-{{ $col['color'] }}-100 group hover:shadow-md transition">
                        {{-- Hiển thị danh mục --}}
                        @if($item->category)
                            <span class="text-[10px] font-bold px-2 py-1 rounded-md bg-{{ $col['color'] }}-100 text-{{ $col['color'] }}-700 mb-2 inline-block uppercase">
                                {{ $item->category->name }}
                            </span>
                        @endif

                        {{-- Hiển thị ảnh nếu có --}}
                        @if($item->image)
                            <img src="{{ asset('storage/' . $item->image) }}" class="w-full h-32 object-cover rounded-xl mb-3 border border-gray-100">
                        @endif

                        <p class="text-gray-700 font-medium mb-3 {{ $col['title'] == 'Đã xong' ? 'line-through opacity-50' : '' }}">
                            {{ $item->content }}
                        </p>

                        <button wire:click="updateStatus({{ $item->id }}, '{{ $col['next'] }}')" class="text-xs font-bold text-{{ $col['color'] }}-500 hover:underline transition">
                            {{ $col['btn'] }}
                        </button>
                    </div>
                @endforeach
            </div>
        @endforeach

    </div>
</div>