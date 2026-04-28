<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel Ghi Chú') }}</title>

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
    
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        [x-cloak] { display: none !important; }
        .shadow-orange { box-shadow: 0 10px 25px -5px rgba(249, 115, 22, 0.4); }
        /* Hiệu ứng bấm nút */
        .calc-btn:active { transform: scale(0.95); transition: 0.1s; }
    </style>
</head>
<body class="font-sans antialiased bg-gray-50 text-gray-900">
    <div class="min-h-screen">
        @include('layouts.navigation')

        @isset($header)
            <header class="bg-white shadow-sm border-b border-gray-200">
                <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                    {{ $header }}
                </div>
            </header>
        @endisset

        <main>
            {{ $slot }}
        </main>
    </div>

    {{-- PHẦN MÁY TÍNH --}}
    {{-- Thêm listener @open-calc.window để nhận lệnh từ Navigation --}}
    <div x-data="{ openCalc: false }" @open-calc.window="openCalc = true">
        
        {{-- NÚT BẤM NỔI (FAB) --}}
        <button @click="openCalc = true" 
                type="button"
                class="fixed bottom-6 right-6 bg-orange-500 text-white p-4 rounded-full shadow-orange z-[100] hover:bg-orange-600 transition-all transform hover:scale-110 active:scale-95 focus:outline-none ring-4 ring-white">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-7 w-7" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z" />
            </svg>
        </button>

        {{-- MODAL MÁY TÍNH --}}
        <div x-show="openCalc" 
             x-cloak 
             class="fixed inset-0 z-[9999] flex items-center justify-center p-4"
             @keydown.escape.window="openCalc = false"> {{-- Nhấn ESC để đóng --}}
            
            <div x-show="openCalc" x-transition.opacity class="fixed inset-0 bg-gray-900/60 backdrop-blur-sm" @click="openCalc = false"></div>
            
            <div x-show="openCalc" 
                 x-transition:enter="transition ease-out duration-300" 
                 x-transition:enter-start="opacity-0 scale-95 translate-y-10" 
                 x-transition:enter-end="opacity-100 scale-100 translate-y-0"
                 class="relative bg-white rounded-3xl shadow-2xl w-full max-w-md overflow-hidden z-10 border border-gray-100">
                
                {{-- Header Modal --}}
                <div class="p-5 flex items-center justify-between bg-gray-50/50 border-b border-gray-100">
                    <div class="flex items-center gap-2">
                        <div class="w-2 h-2 bg-orange-500 rounded-full animate-pulse"></div>
                        <h3 class="text-sm font-bold uppercase tracking-wider text-gray-500">Máy tính nhanh</h3>
                    </div>
                    <button @click="openCalc = false" class="p-2 rounded-full hover:bg-gray-200 text-gray-400 hover:text-gray-600 transition-colors">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                    </button>
                </div>

                <div class="p-6">
                    {{-- Màn hình hiển thị --}}
                    <input type="text" id="calc-result" readonly value="0" 
                           class="w-full text-right text-5xl font-mono bg-gray-50 border-none rounded-2xl p-6 mb-6 text-gray-800 focus:ring-0 shadow-inner overflow-x-auto">
                    
                    {{-- Bàn phím --}}
                    <div class="grid grid-cols-4 gap-4">
                        <button onclick="calcClear()" class="calc-btn col-span-2 bg-red-50 text-red-500 p-5 rounded-2xl font-bold hover:bg-red-500 hover:text-white transition-all">AC</button>
                        <button onclick="calcAppend('/')" class="calc-btn bg-orange-100 text-orange-600 p-5 rounded-2xl font-bold hover:bg-orange-500 hover:text-white transition-all">÷</button>
                        <button onclick="calcAppend('*')" class="calc-btn bg-orange-100 text-orange-600 p-5 rounded-2xl font-bold hover:bg-orange-500 hover:text-white transition-all">×</button>
                        
                        <button onclick="calcAppend('7')" class="calc-btn bg-gray-50 p-5 rounded-2xl font-semibold text-xl hover:bg-gray-200 transition-all">7</button>
                        <button onclick="calcAppend('8')" class="calc-btn bg-gray-50 p-5 rounded-2xl font-semibold text-xl hover:bg-gray-200 transition-all">8</button>
                        <button onclick="calcAppend('9')" class="calc-btn bg-gray-50 p-5 rounded-2xl font-semibold text-xl hover:bg-gray-200 transition-all">9</button>
                        <button onclick="calcAppend('-')" class="calc-btn bg-orange-100 text-orange-600 p-5 rounded-2xl font-bold hover:bg-orange-500 hover:text-white transition-all">-</button>
                        
                        <button onclick="calcAppend('4')" class="calc-btn bg-gray-50 p-5 rounded-2xl font-semibold text-xl hover:bg-gray-200 transition-all">4</button>
                        <button onclick="calcAppend('5')" class="calc-btn bg-gray-50 p-5 rounded-2xl font-semibold text-xl hover:bg-gray-200 transition-all">5</button>
                        <button onclick="calcAppend('6')" class="calc-btn bg-gray-50 p-5 rounded-2xl font-semibold text-xl hover:bg-gray-200 transition-all">6</button>
                        <button onclick="calcAppend('+')" class="calc-btn bg-orange-100 text-orange-600 p-5 rounded-2xl font-bold hover:bg-orange-500 hover:text-white transition-all">+</button>
                        
                        <button onclick="calcAppend('1')" class="calc-btn bg-gray-50 p-5 rounded-2xl font-semibold text-xl hover:bg-gray-200 transition-all">1</button>
                        <button onclick="calcAppend('2')" class="calc-btn bg-gray-50 p-5 rounded-2xl font-semibold text-xl hover:bg-gray-200 transition-all">2</button>
                        <button onclick="calcAppend('3')" class="calc-btn bg-gray-50 p-5 rounded-2xl font-semibold text-xl hover:bg-gray-200 transition-all">3</button>
                        <button onclick="calcEqual()" class="calc-btn row-span-2 bg-emerald-500 text-white p-5 rounded-2xl font-bold text-3xl shadow-lg shadow-emerald-200 hover:bg-emerald-600 transition-all">=</button>
                        
                        <button onclick="calcAppend('0')" class="calc-btn col-span-2 bg-gray-50 p-5 rounded-2xl font-semibold text-xl hover:bg-gray-200 transition-all">0</button>
                        <button onclick="calcAppend('.')" class="calc-btn bg-gray-50 p-5 rounded-2xl font-semibold text-xl hover:bg-gray-200 transition-all">.</button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- JAVASCRIPT --}}
    <script>
        function calcAppend(v) { 
            const r = document.getElementById("calc-result");
            if(!r) return;
            // Giới hạn độ dài để không tràn màn hình
            if(r.value.length > 12 && r.value !== "Error") return;
            r.value = (r.value === "0" || r.value === "Error") ? v : r.value + v;
        }

        function calcClear() { 
            const r = document.getElementById("calc-result");
            if(r) r.value = "0"; 
        }

        function calcEqual() {
            const r = document.getElementById("calc-result");
            if(!r || r.value === "0") return;
            try { 
                let expr = r.value.replace(/×/g, '*').replace(/÷/g, '/');
                // Tính toán và làm tròn 4 chữ số thập phân
                let result = new Function('return ' + expr)();
                r.value = Number.isInteger(result) ? result : parseFloat(result.toFixed(4));
            } catch { 
                r.value = "Error"; 
                setTimeout(calcClear, 1000); 
            }
        }

        // Thêm tính năng gõ phím
        window.addEventListener('keydown', function(e) {
            // Chỉ bắt phím khi Modal đang mở
            const modal = document.querySelector('[x-data]').__x.$data.openCalc;
            if(!modal) return;

            if(e.key >= 0 && e.key <= 9) calcAppend(e.key);
            if(e.key === '.') calcAppend('.');
            if(e.key === '+') calcAppend('+');
            if(e.key === '-') calcAppend('-');
            if(e.key === '*') calcAppend('*');
            if(e.key === '/') calcAppend('/');
            if(e.key === 'Enter') calcEqual();
            if(e.key === 'Escape' || e.key === 'Delete') calcClear();
            if(e.key === 'Backspace') {
                const r = document.getElementById("calc-result");
                r.value = r.value.slice(0, -1) || "0";
            }
        });
    </script>
</body>
</html>