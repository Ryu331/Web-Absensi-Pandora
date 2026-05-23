<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('absens', function (Blueprint $table) {
            $table->time('jam_keluar')->nullable()->after('jam');
        });

        Schema::table('absen_requests', function (Blueprint $table) {
            $table->enum('request_type', ['masuk', 'keluar'])->default('masuk')->after('status');
        });
    }

    public function down(): void
    {
        Schema::table('absen_requests', function (Blueprint $table) {
            $table->dropColumn('request_type');
        });

        Schema::table('absens', function (Blueprint $table) {
            $table->dropColumn('jam_keluar');
        });
    }
};
