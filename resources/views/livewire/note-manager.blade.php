<div class="max-w-7xl mx-auto px-4 py-8">
    {{-- Thông báo thành công --}}
    @if (session()->has('message'))
        <div x-data="{ show: true }" 
             x-show="show" 
             x-init="setTimeout(() => show = false, 3000)"
             class="mb-4 p-4 bg-green-500 text-white rounded-2xl shadow-lg transition-all">
            {{ session('message') }}
        </div>
    @endif

    <div class="mb-10 bg-white p-8 rounded-3xl shadow-xl border border-gray-100">
        <h2 class="text-2xl font-bold text-gray-800 mb-6">Ghi chú mới 📝</h2>
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div class="space-y-6">
                {{-- SELECT CATEGORY (Sử dụng wire:ignore để Alpine hoạt động độc lập) --}}
                <div class="relative" x-data="{ open: false }" wire:ignore>
                    <label class="block text-sm font-bold text-gray-700 mb-2">Phân loại:</label>
                    <div @click="open = !open" 
                         class="w-full h-12 px-4 flex items-center justify-between bg-gray-50 border border-gray-200 rounded-xl cursor-pointer hover:border-orange-400 transition">
                        <span class="text-gray-700">
                            {{ $this->categories->firstWhere('id', (int)$categoryId)->name ?? '-- Chọn danh mục --' }}
                        </span>
                        <svg class="w-4 h-4 text-gray-400 transition-transform" :class="open ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                        </svg>
                    </div>

                    {{-- Dropdown Menu --}}
                    <div x-show="open" 
                         x-cloak
                         @click.outside="open = false" 
                         x-transition:enter="transition ease-out duration-100"
                         x-transition:enter-start="opacity-0 transform scale-95"
                         x-transition:enter-end="opacity-100 transform scale-100"
                         class="absolute z-[100] mt-2 w-full bg-white border border-gray-200 rounded-xl shadow-2xl max-h-60 overflow-y-auto">
                        
                        @foreach($this->categories as $cat)
                            <div wire:key="cat-list-{{ $cat->id }}" 
                                 wire:click="$set('categoryId', {{ $cat->id }})"
                                 @click="open = false" 
                                 class="flex items-center gap-3 px-4 py-3 hover:bg-orange-50 cursor-pointer transition-colors">
                                <span class="w-3 h-3 rounded-full" style="background-color: {{ $cat->color_code }}"></span>
                                <span class="text-gray-800 font-medium">{{ $cat->name }}</span>
                            </div>
                        @endforeach
                    </div>
                </div>
                @error('categoryId') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror

                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-2">Nội dung:</label>
                    <textarea wire:model="noidung" rows="3" 
                              class="w-full px-4 py-3 rounded-xl border border-gray-200 outline-none focus:ring-2 focus:ring-orange-500 transition-all"
                              placeholder="Nhập nội dung ghi chú..."></textarea>
                    @error('noidung') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                </div>
            </div>

            <div class="space-y-6 flex flex-col">
                <label class="block text-sm font-bold text-gray-700 mb-2">Hình ảnh kèm theo:</label>
                <div class="flex-1 flex flex-col justify-center border-2 border-dashed border-gray-200 rounded-2xl p-4 bg-gray-50 hover:bg-gray-100 transition-colors relative">
                    <input type="file" wire:model="image" id="upload{{ $iteration }}" class="absolute inset-0 opacity-0 cursor-pointer">
                    <div class="text-center">
                        <span class="text-gray-500 text-sm">
                            @if($image) 
                                ✅ Đã chọn: {{ $image->getClientOriginalName() }} 
                            @else 
                                📁 Nhấn để chọn ảnh
                            @endif
                        </span>
                    </div>
                </div>
                
                <button wire:click="store" wire:loading.attr="disabled" 
                        class="w-full py-4 bg-orange-500 text-white font-bold rounded-2xl shadow-lg hover:bg-orange-600 transition-all disabled:bg-gray-400">
                    <span wire:loading.remove wire:target="store">LƯU CÔNG VIỆC</span>
                    <span wire:loading wire:target="store">ĐANG LƯU...</span>
                </button>
            </div>
        </div>
    </div>

    {{-- KANBAN BOARD --}}
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        @foreach(['todo' => 'Cần làm', 'doing' => 'Đang làm', 'done' => 'Hoàn thành'] as $status => $label)
            @php 
                $data = ${$status}; 
                $color = $status == 'todo' ? 'gray' : ($status == 'doing' ? 'blue' : 'green');
                $nextStatus = $status == 'todo' ? 'doing' : ($status == 'doing' ? 'done' : 'todo');
            @endphp
            <div class="flex flex-col">
                <h3 class="font-black uppercase mb-4 text-{{ $color }}-600 flex justify-between items-center px-4">
                    {{ $label }} 
                    <span class="bg-{{ $color }}-200 text-{{ $color }}-700 text-xs py-1 px-2.5 rounded-full">{{ count($data) }}</span>
                </h3>
                
                <div class="bg-{{ $color }}-50 p-6 rounded-[2.5rem] border-2 border-dashed border-{{ $color }}-200 min-h-[400px] space-y-4">
                    @foreach($data as $item)
                        <div wire:key="note-card-{{ $item->id }}" 
                             class="bg-white p-6 rounded-2xl shadow-sm relative border border-gray-100 hover:shadow-md transition-shadow">
                            
                            {{-- Delete Button --}}
                            <button wire:click="destroy({{ $item->id }})" 
                                    wire:confirm="Bạn có chắc chắn muốn xóa ghi chú này?" 
                                    class="absolute top-3 right-3 text-gray-300 hover:text-red-500 transition-colors">
                                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path></svg>
                            </button>
                            
                            @if($item->category)
                                <span class="text-[10px] font-bold px-2 py-0.5 rounded-full" 
                                      style="background-color: {{ $item->category->color_code }}22; color: {{ $item->category->color_code }}">
                                    {{ $item->category->name }}
                                </span>
                            @endif

                            <p class="text-gray-800 mt-3 text-sm font-medium leading-relaxed">{{ $item->content }}</p>

                            @if($item->image)
                                <div class="mt-3 rounded-xl overflow-hidden border border-gray-100">
                                    <img src="{{ asset('storage/' . $item->image) }}" class="w-full h-32 object-cover">
                                </div>
                            @endif

                            <button wire:click="updateStatus({{ $item->id }}, '{{ $nextStatus }}')" 
                                    class="mt-4 w-full text-xs font-bold py-2.5 rounded-xl bg-orange-500 text-white hover:bg-orange-600 transition-all shadow-sm active:scale-95">
                                TIẾP THEO ➔
                            </button>
                        </div>
                    @endforeach
                </div>
            </div>
        @endforeach
    </div>
</div>