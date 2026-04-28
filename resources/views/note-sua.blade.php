<x-app-layout>
    <div class="py-12 bg-gray-50 min-h-screen">
        <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white shadow-2xl rounded-[32px] overflow-hidden border border-gray-100">
                
                {{-- Header --}}
                <div class="bg-orange-500 p-8 text-center">
                    <h2 class="text-2xl font-black text-white uppercase tracking-wider">
                        Chỉnh sửa ghi chú ✏️
                    </h2>
                </div>

                <form action="{{ route('notes.update', $note->id) }}" method="POST" enctype="multipart/form-data" class="p-10 space-y-8">
                    @csrf
                    @method('PUT')
                    
                    {{-- Phân loại: Dùng h-14 và appearance-none để chống bung --}}
                    <div>
                        <label class="block text-gray-700 text-sm font-black mb-3 ml-1 uppercase">Phân loại:</label>
                        <div class="relative h-14">
                            <select name="category_id" 
                                    class="w-full h-full px-6 rounded-2xl border-2 border-gray-100 bg-gray-50 focus:border-orange-500 focus:ring-0 text-gray-700 font-bold transition-all cursor-pointer appearance-none"
                                    style="display: block !important; -webkit-appearance: none; appearance: none;"
                                    required>
                                <option value="">-- Chọn danh mục --</option>
                                @foreach($categories as $cat)
                                    {{-- Ép kiểu và kiểm tra object để tránh lỗi id on string --}}
                                    @if(is_object($cat))
                                        <option value="{{ (int)$cat->id }}" {{ (old('category_id', $note->category_id) == $cat->id) ? 'selected' : '' }}>
                                            {{ $cat->name }}
                                        </option>
                                    @endif
                                @endforeach
                            </select>
                            <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-5 text-orange-500">
                                <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M19 9l-7 7-7-7" /></svg>
                            </div>
                        </div>
                    </div>

                    {{-- Nội dung --}}
                    <div>
                        <label class="block text-gray-700 text-sm font-black mb-3 ml-1 uppercase">Nội dung ghi chú:</label>
                        <textarea name="noidung" rows="5" 
                                  class="w-full px-6 py-4 rounded-2xl border-2 border-gray-100 bg-gray-50 focus:border-orange-500 focus:ring-0 transition-all resize-none font-medium text-gray-600"
                                  placeholder="Ghi chú điều gì đó..."
                                  required>{{ old('noidung', $note->content) }}</textarea>
                        @error('noidung')
                            <p class="text-red-500 text-xs mt-1 font-bold">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Hình ảnh --}}
                    <div class="p-6 bg-orange-50/50 rounded-3xl border-2 border-dashed border-orange-200">
                        <label class="block text-orange-700 text-xs font-black mb-4 uppercase">Hình ảnh minh họa</label>
                        <div class="flex flex-col md:flex-row gap-6 items-center">
                            @if($note->image)
                                <div class="relative shrink-0">
                                    <img src="{{ asset('storage/' . $note->image) }}" class="w-24 h-24 object-cover rounded-2xl border-4 border-white shadow-md">
                                    <span class="absolute -top-2 -left-2 bg-orange-500 text-white text-[10px] px-2 py-1 rounded-lg font-bold">ẢNH CŨ</span>
                                </div>
                            @endif
                            <div class="w-full">
                                <input type="file" name="image" accept="image/*"
                                       class="block w-full text-sm text-gray-500 file:mr-4 file:py-3 file:px-6 file:rounded-2xl file:border-0 file:text-sm file:font-bold file:bg-orange-500 file:text-white hover:file:bg-orange-600 cursor-pointer transition-all">
                                <p class="text-[10px] text-gray-400 mt-2 italic font-medium">* Để trống nếu giữ nguyên ảnh cũ</p>
                            </div>
                        </div>
                        @error('image')
                            <p class="text-red-500 text-xs mt-1 font-bold">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Điều hướng --}}
                    <div class="flex items-center justify-between gap-4 pt-10 border-t border-gray-100">
                        <a href="{{ route('notes.index') }}" class="text-gray-400 hover:text-gray-600 font-bold text-sm transition-colors uppercase tracking-widest">
                            ← Hủy bỏ
                        </a>
                        <button type="submit" class="bg-orange-500 hover:bg-orange-600 text-white font-black py-4 px-12 rounded-2xl shadow-xl shadow-orange-200 transition-all transform active:scale-95">
                            CẬP NHẬT GHI CHÚ
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>