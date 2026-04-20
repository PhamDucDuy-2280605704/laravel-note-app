<x-app-layout>
    <div class="py-12">
        <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-8">
                <h2 class="text-2xl font-bold text-gray-800 mb-6">Chỉnh sửa ghi chú ✏️</h2>
                
                <form action="/cap-nhat-ghi-chu/{{ $note->id }}" method="POST">
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
                                  required>{{ $note->content }}</textarea>
                    </div>

                    <div class="flex items-center justify-end space-x-4">
                        <a href="/danh-sach" class="text-gray-500 hover:text-gray-700">Hủy bỏ</a>
                        <button type="submit" class="bg-orange-500 hover:bg-orange-600 text-white font-bold py-2 px-6 rounded-lg shadow-md transition duration-300">
                            Cập nhật ngay
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>