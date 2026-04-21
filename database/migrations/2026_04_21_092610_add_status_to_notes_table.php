<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
public function up(): void
{
    Schema::table('notes', function (Blueprint $table) {
        // Đây mới là lệnh tạo cột thật sự này
        $table->string('status')->default('todo')->after('content');
    });
}

public function down(): void
{
    Schema::table('notes', function (Blueprint $table) {
        // Giờ mới viết lại lệnh xóa để sau này có rollback thì không lỗi
        $table->dropColumn('status');
    });
}
};
