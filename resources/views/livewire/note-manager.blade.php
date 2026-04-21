<div class="max-w-7xl mx-auto px-4 py-8">
    
    {{-- PHẦN FORM NHẬP --}}
    <div class="mb-10 bg-white p-8 rounded-3xl shadow-xl border border-gray-100">
        <h2 class="text-2xl font-bold text-gray-800 mb-6 flex items-center gap-2">
            Thêm công việc mới 📝
        </h2>

        <div class="grid grid-cols-1 gap-6">
            {{-- Chọn danh mục --}}
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

            {{-- Nội dung --}}
            <div>
                <label class="block text-sm font-bold text-gray-700 mb-2">Nội dung:</label>
                <textarea wire:model="noidung" rows="3" placeholder="Nhập nội dung công việc..." 
                    class="w-full rounded-xl border-gray-200 focus:ring-orange-500 focus:border-orange-500"></textarea>
                @error('noidung') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
            </div>

            {{-- Hình ảnh --}}
            <div>
                <label class="block text-sm font-bold text-gray-700 mb-2">Hình ảnh:</label>
                <input type="file" wire:model="image" id="upload-{{ $iteration }}" class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-orange-50 file:text-orange-700 hover:file:bg-orange-100">
                
                @if ($image)
                    <div class="mt-3">
                        <img src="{{ $image->temporaryUrl() }}" class="w-32 h-32 object-cover rounded-xl shadow-md border border-orange-200">
                    </div>
                @endif
            </div>

            <div class="flex justify-end gap-3 mt-4 border-t pt-6">
                <button wire:click="store" wire:loading.attr="disabled" class="px-8 py-3 bg-orange-500 hover:bg-orange-600 text-white font-bold rounded-2xl shadow-lg transition disabled:opacity-50">
                    <span wire:loading.remove>Cập nhật ngay</span>
                    <span wire:loading>Đang lưu...</span>
                </button>
            </div>
        </div>
    </div>

    {{-- BẢNG KANBAN 3 CỘT --}}
    <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
        @php
            $columns = [
                ['title' => 'Cần làm', 'color' => 'gray', 'data' => $todo, 'next' => 'doing', 'btn' => 'BẮT ĐẦU →'],
                ['title' => 'Đang thực hiện', 'color' => 'blue', 'data' => $doing, 'next' => 'done', 'btn' => 'XONG ✓'],
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
                    {{-- CỰC KỲ QUAN TRỌNG: wire:key phải duy nhất --}}
                    <div wire:key="note-{{ $item->id }}" class="bg-white p-5 rounded-2xl shadow-sm mb-4 border border-{{ $col['color'] }}-100 relative group hover:shadow-md transition">
                        
                        {{-- Nút Xóa --}}
                        <button onclick="confirm('Chắc chắn xóa không?') || event.stopImmediatePropagation()" 
                                wire:click="destroy({{ $item->id }})" 
                                class="absolute top-3 right-3 text-gray-300 hover:text-red-500 transition">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" /></svg>
                        </button>

                        @if($item->category)
                            <span class="text-[10px] font-bold px-2 py-1 rounded-md bg-{{ $col['color'] }}-100 text-{{ $col['color'] }}-700 mb-2 inline-block uppercase">
                                {{ $item->category->name }}
                            </span>
                        @endif

                        @if($item->image)
                            {{-- Lưu ý đường dẫn asset --}}
                            <img src="{{ asset('storage/' . $item->image) }}" class="w-full h-32 object-cover rounded-xl mb-3 border">
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