<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800">Ghi chú (Livewire Mode)</h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            {{-- ĐÂY LÀ NƠI PHÉP THUẬT XẢY RA --}}
            @livewire('note-manager')
        </div>
    </div>
</x-app-layout>