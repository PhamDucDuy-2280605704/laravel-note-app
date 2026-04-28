<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ Auth::user()->hasRole('admin') ? 'Bảng điều khiển Quản trị' : 'Bảng điều khiển Cá nhân' }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-10">
            
            {{-- 1. Thẻ thống kê --}}
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                @role('admin')
                    <div class="bg-white p-6 rounded-2xl shadow-sm border-l-4 border-blue-500">
                        <div class="text-gray-500 text-sm uppercase font-bold">Tổng Người Dùng</div>
                        <div class="text-3xl font-bold text-gray-800">{{ $data['totalUsers'] }}</div>
                    </div>
                    <div class="bg-white p-6 rounded-2xl shadow-sm border-l-4 border-green-500">
                        <div class="text-gray-500 text-sm uppercase font-bold">Tổng Ghi Chú</div>
                        <div class="text-3xl font-bold text-gray-800">{{ $data['totalNotes'] }}</div>
                    </div>
                    <div class="bg-white p-6 rounded-2xl shadow-sm border-l-4 border-red-500">
                        <div class="text-gray-500 text-sm uppercase font-bold">Ghi chú đã xóa</div>
                        <div class="text-3xl font-bold text-gray-800">{{ $data['totalTrash'] }}</div>
                    </div>
                @else
                    <div class="bg-white p-6 rounded-2xl shadow-sm border-l-4 border-indigo-500">
                        <div class="text-gray-500 text-sm uppercase font-bold">Ghi chú của tôi</div>
                        <div class="text-3xl font-bold text-gray-800">{{ $data['myNotesCount'] }}</div>
                    </div>
                    <div class="bg-white p-6 rounded-2xl shadow-sm border-l-4 border-yellow-500">
                        <div class="text-gray-500 text-sm uppercase font-bold">Đã ghim</div>
                        <div class="text-3xl font-bold text-gray-800">{{ $data['myPinnedCount'] }}</div>
                    </div>
                    <div class="bg-white p-6 rounded-2xl shadow-sm border-l-4 border-gray-500">
                        <div class="text-gray-500 text-sm uppercase font-bold">Trong thùng rác</div>
                        <div class="text-3xl font-bold text-gray-800">{{ $data['myTrashCount'] }}</div>
                    </div>
                @endrole
            </div>

            {{-- 2. FORM THÊM CÔNG VIỆC MỚI (ĐÃ FIX) --}}
            <div class="bg-white rounded-[32px] shadow-xl p-8 border border-gray-100">
                <h2 class="text-xl font-bold mb-8 flex items-center gap-2 text-gray-800">
                    Thêm công việc mới 📝
                </h2>
                
                <form action="{{ route('notes.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-10 items-start">
                        <div class="flex flex-col space-y-3">
                            <label class="text-sm font-bold text-gray-700 ml-1">Phân loại:</label>
                            <div class="relative">
                                <select name="category_id" required class="w-full h-[56px] rounded-2xl border-2 border-gray-100 bg-gray-50 px-5 focus:ring-2 focus:ring-orange-500 outline-none text-gray-700 font-semibold appearance-none">
                                    <option value="">-- Chọn danh mục --</option>
                                    @foreach(\App\Models\Category::all() as $cat)
                                        <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="flex flex-col space-y-3">
                            <label class="text-sm font-bold text-gray-700 ml-1">Hình ảnh minh họa:</label>
                            <input type="file" name="image" class="block w-full text-sm text-gray-500 border-2 border-dashed border-gray-200 rounded-2xl file:mr-4 file:py-4 file:px-6 file:border-0 file:bg-orange-100 file:text-orange-700"/>
                        </div>
                    </div>

                    <div class="mt-6 flex flex-col space-y-3">
                        <label class="text-sm font-bold text-gray-700 ml-1">Nội dung chi tiết:</label>
                        <textarea name="content" required rows="3" class="w-full rounded-2xl border-2 border-gray-100 bg-gray-50 p-4 focus:ring-2 focus:ring-orange-500 outline-none" placeholder="Nhập nội dung công việc..."></textarea>
                    </div>

                    <div class="mt-8 flex justify-center">
                        <button type="submit" class="w-full md:w-1/2 bg-orange-600 hover:bg-orange-700 text-white font-black py-4 px-8 rounded-2xl shadow-lg transition-all active:scale-95">
                            LƯU CÔNG VIỆC NGAY
                        </button>
                    </div>
                </form>
            </div>

            {{-- 3. DANH SÁCH HOẠT ĐỘNG --}}
            <div class="bg-white shadow-sm rounded-[32px] border border-gray-100 overflow-hidden">
                <div class="p-8">
                    <h3 class="font-bold text-lg mb-6 text-gray-700 flex items-center gap-2">
                         <span class="p-2 bg-orange-50 rounded-lg"><svg class="w-5 h-5 text-orange-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" /></svg></span>
                         Hoạt động mới nhất
                    </h3>
                    <div class="space-y-4">
                        @forelse($data['recentNotes'] as $note)
                            <div class="flex justify-between items-center border-b border-gray-50 pb-5 last:border-0 hover:bg-gray-50/50 transition-colors rounded-xl p-2 -mx-2">
                                <div class="pr-4">
                                    <p class="text-gray-800 font-semibold leading-relaxed">{{ Str::limit($note->content, 60) }}</p>
                                    <span class="text-xs text-gray-400 block mt-2 flex items-center gap-1">
                                        {{ $note->created_at->diffForHumans() }} 
                                        @role('admin') • <b class="text-gray-600">User: {{ $note->user->name }}</b> @endrole
                                    </span>
                                </div>
                                <a href="{{ route('notes.edit', $note->id) }}" class="shrink-0 bg-blue-50 text-blue-600 hover:bg-blue-600 hover:text-white px-4 py-2 rounded-xl font-bold text-sm transition-all">Xem chi tiết</a>
                            </div>
                        @empty
                            <div class="text-center py-12 bg-gray-50 rounded-2xl">
                                <p class="text-gray-400 italic font-medium">Chưa có hoạt động nào gần đây.</p>
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>

        </div>
    </div>
</x-app-layout>