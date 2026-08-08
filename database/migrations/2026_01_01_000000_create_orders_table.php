<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

// Catatan: saat menyalin file ini, ganti nama file agar prefix tanggalnya
// lebih baru dari migration bawaan Laravel (users, dst), contoh:
// 2026_01_01_000000_create_orders_table.php sudah cukup aman untuk latihan.

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->string('order_id')->unique(); // dikirim ke Midtrans sebagai transaction id
            $table->string('product_name');
            $table->unsignedInteger('amount'); // dalam Rupiah, tanpa desimal
            $table->string('customer_name');
            $table->string('customer_email');
            $table->string('snap_token')->nullable();
            $table->string('status')->default('pending'); // pending | paid | failed | expired
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
