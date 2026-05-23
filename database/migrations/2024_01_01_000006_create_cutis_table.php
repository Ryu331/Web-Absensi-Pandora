<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('cutis', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->date('tanggal_mulai');
            $table->date('tanggal_selesai');
            $table->string('jenis_cuti'); // contoh: tahunan, sakit, melahirkan, dll
            $table->text('alasan')->nullable();
            $table->enum('status', ['pending', 'disetujui', 'ditolak'])->default('pending');
            $table->foreignId('approved_by')->nullable()->constrained('users')->onDelete('set null'); // Admin yang menyetujui
            $table->timestamp('approved_at')->nullable();
            $table->text('catatan_admin')->nullable(); // Catatan dari admin saat menyetujui/menolak
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('cutis');
    }
};
