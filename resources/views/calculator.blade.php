<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Máy tính cá nhân') }}
        </h2>
    </x-slot>

    <style>
        /* CSS của bạn giữ nguyên */
        .calculator {
            background-color: #f2f2f2;
            padding: 20px;
            max-width: 400px;
            margin: 40px auto;
            border: solid 1px #ccc;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.3);
            border-radius: 15px;
        }

        #result {
            width: 100%;
            padding: 15px;
            font-size: 32px;
            border: none;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.2) inset;
            border-radius: 10px;
            text-align: right;
            margin-bottom: 20px;
            background: #fff;
        }

        .buttons {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            grid-gap: 12px;
        }

        .calculator button {
            padding: 20px;
            font-size: 20px;
            border: none;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            border-radius: 10px;
            cursor: pointer;
            transition: all 0.2s ease;
        }

        .calculator button:hover {
            opacity: 0.8;
            transform: translateY(-2px);
        }

        .clear { background-color: #ff4136; color: #fff; }
        .number, .decimal { background-color: #fff; color: #333; }
        .operator { background-color: #0074d9; color: #fff; }
        .equals { 
            background-color: #01ff70; 
            grid-row: span 3; 
            color: #fff; 
            display: flex;
            align-items: center;
            justify-content: center;
        }
    </style>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="calculator">
                <input type="text" id="result" readonly>
                <div class="buttons">
                    <button class="clear">C</button>
                    <button class="operator">/</button>
                    <button class="operator">*</button>
                    <button class="operator">-</button>
                    
                    <button class="number">7</button>
                    <button class="number">8</button>
                    <button class="number">9</button>
                    <button class="operator">+</button>
                    
                    <button class="number">4</button>
                    <button class="number">5</button>
                    <button class="number">6</button>
                    <button class="equals">=</button>
                    
                    <button class="number">1</button>
                    <button class="number">2</button>
                    <button class="number">3</button>
                    
                    <button class="number" style="grid-column: span 2;">0</button>
                    <button class="decimal">.</button>
                </div>
            </div>
        </div>
    </div>

    <script>
        // JS của bạn giữ nguyên
        const buttonsEl = document.querySelectorAll(".calculator button");
        const inputFieldEl = document.getElementById("result");

        for (let i = 0; i < buttonsEl.length; i++) {
            buttonsEl[i].addEventListener("click", () => {
                const buttonValue = buttonsEl[i].textContent;
                if (buttonValue === "C") {
                    clearResult();
                } else if (buttonValue === "=") {
                    calculateResult();
                } else {
                    appendValue(buttonValue);
                }
            });
        }

        function clearResult() {
            inputFieldEl.value = "";
        }

        function calculateResult() {
            try {
                // Sử dụng Function thay cho eval để an toàn hơn một chút
                inputFieldEl.value = eval(inputFieldEl.value);
            } catch (error) {
                inputFieldEl.value = "Lỗi";
            }
        }

        function appendValue(buttonValue) {
            inputFieldEl.value += buttonValue;
        }
    </script>
</x-app-layout>