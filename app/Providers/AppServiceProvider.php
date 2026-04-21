<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Pagination\Paginator; 
use Illuminate\Support\Facades\View; 
use App\Models\Category;
use Illuminate\Support\Facades\Schema; // Thêm dòng này để kiểm tra bảng

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

        // 2. Chia sẻ biến $categories cho tất cả các view để không bị lỗi "Undefined variable"
        View::composer('*', function ($view) {
            // Kiểm tra nếu bảng categories đã tồn tại trong DB thì mới lấy dữ liệu
            if (Schema::hasTable('categories')) {
                $view->with('categories', Category::all());
            }
        });
    }
}