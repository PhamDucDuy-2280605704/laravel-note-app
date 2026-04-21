<x-app-layout>
    <div class="py-12">
        <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-8">
                <h2 class="text-2xl font-bold text-gray-800 mb-6">Chỉnh sửa ghi chú ✏️</h2>
                
                <form action="{{ route('notes.update', $note->id) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    
                    <div class="mb-6">
                        <label class="block text-gray-700 text-sm font-bold mb-2">Phân loại:</label>
                        <select name="category_id" 
                                class="w-full px-4 py-2 rounded-lg border-gray-300 focus:border-orange-500 focus:ring focus:ring-orange-200 transition duration-200"
                                required>
                            @foreach($categories as $cat)
                                <option value="{{ $cat->id }}" {{ $note->category_id == $cat->id ? 'selected' : '' }}>
                                    {{ $cat->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="mb-6">
                        <label class="block text-gray-700 text-sm font-bold mb-2">Nội dung:</label>
                        <textarea name="noidung" rows="5" 
                                  class="w-full px-4 py-3 rounded-lg border-gray-300 focus:border-orange-500 focus:ring focus:ring-orange-200 transition duration-200"
                                  required>{{ old('noidung', $note->content) }}</textarea>
                        @error('noidung')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="mb-6">
                        <label class="block text-gray-700 text-sm font-bold mb-2">Hình ảnh:</label>
                        
                        @if($note->image)
                            <div class="mb-3">
                                <p class="text-xs text-gray-500 mb-1">Ảnh hiện tại:</p>
                                <img src="{{ asset('storage/' . $note->image) }}" class="w-32 h-32 object-cover rounded-lg border">
                            </div>
                        @endif

                        <input type="file" name="image" accept="image/*"
                               class="w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-orange-50 file:text-orange-700 hover:file:bg-orange-100">
                        <p class="text-xs text-gray-400 mt-1">Để trống nếu không muốn thay đổi ảnh.</p>
                        @error('image')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="flex items-center justify-end space-x-4">
                        <a href="{{ route('notes.index') }}" class="text-gray-500 hover:text-gray-700">Hủy bỏ</a>
                        <button type="submit" class="bg-orange-500 hover:bg-orange-600 text-white font-bold py-2 px-6 rounded-lg shadow-md transition duration-300">
                            Cập nhật ngay
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>