<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Tất cả ghi chú hệ thống (Admin)') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="border-b">
                            <th class="p-3">Người tạo</th>
                            <th class="p-3">Tiêu đề</th>
                            <th class="p-3">Ngày tạo</th>
                            <th class="p-3">Thao tác</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($notes as $note)
                            <tr class="border-b hover:bg-gray-50">
                                <td class="p-3 font-bold text-blue-600">{{ $note->user->name }}</td>
                                <td class="p-3">{{ $note->title }}</td>
                                <td class="p-3 text-sm text-gray-500">{{ $note->created_at->format('d/m/Y H:i') }}</td>
                                <td class="p-3">
                                    <a href="{{ route('notes.edit', $note->id) }}" class="text-indigo-600 hover:unline">Xem/Sửa</a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="p-3 text-center">Không có ghi chú nào.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>

                <div class="mt-4">
                    {{ $notes->links() }}
                </div>
            </div>
        </div>
    </div>
</x-app-layout>