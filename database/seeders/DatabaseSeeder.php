<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Hapus data lama agar tidak duplikat saat re-seed
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        User::truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        // Seed Admin
        User::create([
            'name'     => 'Administrator',
            'email'    => 'admin@example.com',
            'password' => Hash::make('pandora123'),
            'role'     => 'admin',
            'status'   => 'bekerja',
        ]);

        // Seed User contoh
        User::create([
            'name'     => 'User Contoh',
            'email'    => 'user@example.com',
            'password' => Hash::make('password'),
            'role'     => 'user',
            'status'   => 'tidak_bekerja',
        ]);

        $this->command->info('✅ Seeder selesai!');
        $this->command->info('   Admin  : admin@example.com / pandora123');
        $this->command->info('   User   : user@example.com / password');
    }
}
