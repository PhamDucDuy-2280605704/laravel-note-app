import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';

export default defineConfig({
    server: {
        host: 'localhost', // Ép Vite chạy IPv4 để tránh lỗi [::1]
        cors: true,        // Cho phép truy cập từ các domain khác (sửa lỗi CORS)
        hmr: {
            host: 'localhost',
        },
    },
    plugins: [
        laravel({
            input: ['resources/css/app.css', 'resources/js/app.js'],
            refresh: true,
        }),
    ],
});