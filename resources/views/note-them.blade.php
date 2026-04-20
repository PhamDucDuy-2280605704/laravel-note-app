<x-app-layout>
    <div class="py-12">
        <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-8">
                <h2 class="text-2xl font-bold text-gray-800 mb-6">Viết ghi chú mới ✨</h2>
                
                <form action="/luu-ghi-chu" method="POST">
                    @csrf
                    
                    <div class="mb-6">
                        <label class="block text-gray-700 text-sm font-bold mb-2">Phân loại:</label>
                        <select name="category_id" 
                                class="w-full px-4 py-2 rounded-lg border-gray-300 focus:border-blue-500 focus:ring focus:ring-blue-200 transition duration-200"
                                required>
                            <option value="">-- Chọn một danh mục --</option>
                            @foreach($categories as $cat)
                                <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                            @endforeach
                        </select>
                        @error('category_id')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="mb-6">
                        <label class="block text-gray-700 text-sm font-bold mb-2">Nội dung:</label>
                        <textarea name="noidung" rows="5" 
                                  class="w-full px-4 py-3 rounded-lg border-gray-300 focus:border-blue-500 focus:ring focus:ring-blue-200 transition duration-200"
                                  placeholder="Cậu đang nghĩ gì thế..." required>{{ old('noidung') }}</textarea>
                        @error('noidung')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="flex items-center justify-end space-x-4">
                        <a href="/danh-sach" class="text-gray-500 hover:text-gray-700">Hủy bỏ</a>
                        <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-6 rounded-lg shadow-md transition duration-300">
                            Lưu Ghi Chú
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>