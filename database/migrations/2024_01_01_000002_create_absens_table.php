<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('absens', function (Blueprint $table) {
            $table->id(); // Auto-increment
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade'); // id otomatis dari id akun
            // nama otomatis dari id akun (diambil dari relasi ke users)
            $table->date('tanggal'); // Input hari, tanggal, bulan, tahun
            $table->time('jam');     // Input jam (otomatis deteksi jam)
            $table->string('lokasi')->nullable(); // Aktifkan lokasi → otomatis deteksi lokasi
            $table->decimal('latitude', 10, 8)->nullable();
            $table->decimal('longitude', 11, 8)->nullable();
            $table->string('foto_wajah')->nullable(); // Scan wajah
            $table->enum('status', ['dikonfirmasi', 'pending', 'tidak_dikonfirmasi'])->default('pending');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('absens');
    }
};
