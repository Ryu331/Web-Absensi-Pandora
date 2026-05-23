<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Tabel ini merepresentasikan "Request ke Admin" dari fitur Absen user
        Schema::create('absen_requests', function (Blueprint $table) {
            $table->id();
            $table->foreignId('absen_id')->constrained('absens')->onDelete('cascade');
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->text('catatan')->nullable(); // Catatan/alasan request
            $table->enum('status', ['pending', 'dikonfirmasi', 'tidak_dikonfirmasi'])->default('pending');
            $table->foreignId('validated_by')->nullable()->constrained('users')->onDelete('set null'); // Admin yang memvalidasi
            $table->timestamp('validated_at')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('absen_requests');
    }
};
