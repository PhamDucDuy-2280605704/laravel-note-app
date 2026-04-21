<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ Auth::user()->hasRole('admin') ? 'Bảng điều khiển Quản trị' : 'Bảng điều khiển Cá nhân' }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
                @role('admin')
                    <div class="bg-white p-6 rounded-lg shadow border-l-4 border-blue-500">
                        <div class="text-gray-500 text-sm uppercase font-bold">Tổng Người Dùng</div>
                        <div class="text-3xl font-bold">{{ $data['totalUsers'] }}</div>
                    </div>
                    <div class="bg-white p-6 rounded-lg shadow border-l-4 border-green-500">
                        <div class="text-gray-500 text-sm uppercase font-bold">Tổng Ghi Chú</div>
                        <div class="text-3xl font-bold">{{ $data['totalNotes'] }}</div>
                    </div>
                    <div class="bg-white p-6 rounded-lg shadow border-l-4 border-red-500">
                        <div class="text-gray-500 text-sm uppercase font-bold">Ghi chú đã xóa</div>
                        <div class="text-3xl font-bold">{{ $data['totalTrash'] }}</div>
                    </div>
                @else
                    <div class="bg-white p-6 rounded-lg shadow border-l-4 border-indigo-500">
                        <div class="text-gray-500 text-sm uppercase font-bold">Ghi chú của tôi</div>
                        <div class="text-3xl font-bold">{{ $data['myNotesCount'] }}</div>
                    </div>
                    <div class="bg-white p-6 rounded-lg shadow border-l-4 border-yellow-500">
                        <div class="text-gray-500 text-sm uppercase font-bold">Đã ghim</div>
                        <div class="text-3xl font-bold">{{ $data['myPinnedCount'] }}</div>
                    </div>
                    <div class="bg-white p-6 rounded-lg shadow border-l-4 border-gray-500">
                        <div class="text-gray-500 text-sm uppercase font-bold">Trong thùng rác</div>
                        <div class="text-3xl font-bold">{{ $data['myTrashCount'] }}</div>
                    </div>
                @endrole
            </div>

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <h3 class="font-bold text-lg mb-4 text-gray-700">Ghi chú mới nhất</h3>
                    <div class="space-y-4">
                        @forelse($data['recentNotes'] as $note)
                            <div class="flex justify-between items-center border-b pb-2">
                                <div>
                                    <p class="text-gray-800 font-medium">{{ Str::limit($note->content, 50) }}</p>
                                    <span class="text-xs text-gray-500">
                                        {{ $note->created_at->diffForHumans() }} 
                                        @role('admin') • Tạo bởi: <b>{{ $note->user->name }}</b> @endrole
                                    </span>
                                </div>
                                <a href="{{ route('notes.edit', $note->id) }}" class="text-blue-600 hover:underline text-sm">Xem chi tiết</a>
                            </div>
                        @empty
                            <p class="text-gray-500 italic">Chưa có hoạt động nào gần đây.</p>
                        @endforelse
                    </div>
                </div>
            </div>

        </div>
    </div>
</x-app-layout>