<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Pagination\Paginator; // DÒNG NÀY CỰC KỲ QUAN TRỌNG, PHẢI NẰM Ở ĐÂY

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
        // Sử dụng giao diện Tailwind cho thanh phân trang
        Paginator::useTailwind(); 
    }
}