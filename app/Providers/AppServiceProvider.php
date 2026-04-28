<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Pagination\Paginator; 
use Illuminate\Support\Facades\View; 
use App\Models\Category;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Schema;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // 1. Ép Laravel dùng CSS của Tailwind cho các nút phân trang
        Paginator::useTailwind();

        // 2. Chia sẻ biến $categories cho tất cả các view một cách tối ưu
        View::composer('*', function ($view) {
            // Sử dụng Cache để tránh truy vấn Database ở mỗi lần tải trang
            // 'global_categories' là khóa của cache, lưu trong 3600 giây (1 giờ)
            $categories = Cache::remember('global_categories', 3600, function () {
                // Kiểm tra bảng tồn tại (chỉ chạy 1 lần khi cache hết hạn)
                if (Schema::hasTable('categories')) {
                    return Category::all();
                }
                return collect(); // Trả về collection rỗng nếu bảng chưa có
            });

            $view->with('categories', $categories);
        });
    }
}