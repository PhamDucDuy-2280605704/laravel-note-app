<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-red-600 leading-tight">
                {{ __('Thùng rác của tớ 🗑️') }}
            </h2>
            <a href="/danh-sach" class="bg-gray-600 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded-lg transition duration-300 shadow-md">
                ← Quay lại danh sách
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            @if($notes->isEmpty())
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-10 text-center border border-dashed border-gray-300">
                    <p class="text-gray-500 text-lg">Thùng rác trống trơn! Không có gì để khôi phục cả.</p>
                </div>
            @else
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    @foreach($notes as $item)
                        {{-- Dùng tông màu xám để thể hiện đây là đồ cũ đã xóa --}}
                        <div class="bg-gray-50 overflow-hidden shadow-sm sm:rounded-lg border border-gray-200 opacity-80 hover:opacity-100 transition-opacity duration-300">
                            <div class="p-6">
                                <p class="text-gray-600 text-lg mb-6 leading-relaxed min-h-[100px]">
                                    {{ $item->content }}
                                </p>
                                
                                <div class="flex justify-between items-center border-t border-gray-300 pt-4">
                                    <span class="text-xs text-gray-400">
                                        Đã xóa lúc: {{ $item->deleted_at->format('d/m/Y H:i') }}
                                    </span>
                                    <div class="flex space-x-4">
                                        {{-- Nút khôi phục --}}
                                        <a href="{{ route('notes.restore', $item->id) }}" class="text-green-600 hover:text-green-800 font-bold text-sm">
                                            Khôi phục
                                        </a>
                                        
                                        {{-- Nút xóa vĩnh viễn --}}
                                        <a href="{{ route('notes.forceDelete', $item->id) }}" 
                                           onclick="return confirm('Cảnh báo: Hành động này không thể hoàn tác. Cậu chắc chắn muốn xóa vĩnh viễn chứ?')"
                                           class="text-red-600 hover:text-red-800 font-bold text-sm">
                                            Xóa hẳn
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif

        </div>
    </div>
</x-app-layout>