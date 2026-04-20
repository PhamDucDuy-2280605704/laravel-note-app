<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Ghi chú của tớ 📝') }}
            </h2>
            <a href="{{ route('notes.create') }}" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded-lg transition duration-300 shadow-lg">
                + Thêm ghi chú
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            {{-- THÔNG BÁO THÀNH CÔNG --}}
            @if(session('success'))
                <div class="mb-6 p-4 bg-green-100 border-l-4 border-green-500 text-green-700 shadow-sm">
                    {{ session('success') }}
                </div>
            @endif

            <div class="mb-8 flex flex-col md:flex-row justify-between items-center gap-4">
                {{-- FORM TÌM KIẾM VÀ LỌC --}}
                <form action="{{ route('notes.index') }}" method="GET" class="flex flex-wrap gap-2 w-full md:w-auto">
                    <input type="text" name="search" value="{{ request('search') }}" 
                           placeholder="Tìm kiếm ghi chú..." 
                           class="w-full md:w-80 rounded-lg border-gray-300 focus:border-blue-500 focus:ring focus:ring-blue-200 shadow-sm">
                    
                    <select name="category_id" onchange="this.form.submit()" 
                            class="rounded-lg border-gray-300 focus:border-blue-500 focus:ring focus:ring-blue-200 shadow-sm">
                        <option value="">Tất cả danh mục</option>
                        @foreach($categories as $cat)
                            <option value="{{ $cat->id }}" {{ request('category_id') == $cat->id ? 'selected' : '' }}>
                                {{ $cat->name }}
                            </option>
                        @endforeach
                    </select>

                    <button type="submit" class="bg-gray-800 text-white px-6 py-2 rounded-lg hover:bg-gray-700 transition shadow-sm">
                        Tìm
                    </button>

                    @if(request('search') || request('category_id'))
                        <a href="{{ route('notes.index') }}" class="bg-gray-200 text-gray-700 px-4 py-2 rounded-lg hover:bg-gray-300 flex items-center shadow-sm">
                            Xóa lọc
                        </a>
                    @endif
                </form>

                <a href="{{ route('notes.trash') }}" class="text-sm text-gray-500 hover:text-red-500 flex items-center gap-1 transition-colors group">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 group-hover:animate-bounce" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                    </svg>
                    <span class="font-medium">Xem thùng rác</span>
                </a>
            </div>

            @if($notes->isEmpty())
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-10 text-center border border-dashed border-gray-300">
                    <p class="text-gray-500 text-lg italic">
                        {{ request('search') || request('category_id') ? 'Không tìm thấy kết quả nào phù hợp.' : 'Chưa có ghi chú nào ở đây cả. Viết gì đó đi cậu!' }}
                    </p>
                </div>
            @else
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    @foreach($notes as $item)
                        @php
                            $colors = ['bg-yellow-50', 'bg-blue-50', 'bg-green-50', 'bg-pink-50', 'bg-purple-50', 'bg-orange-50'];
                            $currentColor = $colors[$loop->index % count($colors)];
                        @endphp

                        <div class="{{ $currentColor }} overflow-hidden shadow-sm sm:rounded-lg border-2 {{ $item->is_pinned ? 'border-blue-400 shadow-md' : 'border-gray-200' }} hover:shadow-lg transition-all duration-300 relative">
                            @if($item->is_pinned)
                                <div class="absolute top-2 right-2 text-xl animate-pulse">📌</div>
                            @endif

                            <div class="p-6">
                                @if($item->category)
                                    <span class="inline-block px-2 py-0.5 mb-3 text-[10px] font-bold uppercase tracking-wider rounded-full border
                                        {{ $item->category->color == 'blue' ? 'bg-blue-100 text-blue-700 border-blue-200' : '' }}
                                        {{ $item->category->color == 'red' ? 'bg-red-100 text-red-700 border-red-200' : '' }}
                                        {{ $item->category->color == 'green' ? 'bg-green-100 text-green-700 border-green-200' : '' }}
                                        {{ $item->category->color == 'orange' ? 'bg-orange-100 text-orange-700 border-orange-200' : '' }}">
                                        {{ $item->category->name }}
                                    </span>
                                @endif

                                <p class="text-gray-800 text-lg mb-6 leading-relaxed min-h-[100px] break-words">
                                    {{ $item->content }}
                                </p>
                                
                                <div class="flex justify-between items-center border-t border-gray-200 pt-4">
                                    <span class="text-xs text-gray-500 font-medium">
                                        {{ $item->created_at->diffForHumans() }} {{-- Hiển thị kiểu "2 phút trước" cho xịn --}}
                                    </span>
                                    <div class="flex space-x-3">
                                        <a href="{{ route('notes.pin', $item->id) }}" class="{{ $item->is_pinned ? 'text-blue-600' : 'text-gray-400' }} hover:text-blue-800 font-semibold text-sm">
                                            {{ $item->is_pinned ? 'Bỏ ghim' : 'Ghim' }}
                                        </a>
                                        <a href="{{ route('notes.edit', $item->id) }}" class="text-amber-600 hover:text-amber-800 font-semibold text-sm">Sửa</a>
                                        <a href="{{ route('notes.destroy', $item->id) }}" onclick="return confirm('Xóa vào thùng rác nhé?')" class="text-red-600 hover:text-red-800 font-semibold text-sm">Xóa</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                <div class="mt-8">
                    {{ $notes->links() }}
                </div>
            @endif
        </div>
    </div>
</x-app-layout>