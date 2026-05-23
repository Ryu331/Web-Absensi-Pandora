<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Absen extends Model
{
    protected $fillable = [
        'user_id',
        'tanggal',
        'jam',
        'jam_keluar',
        'lokasi',
        'latitude',
        'longitude',
        'foto_wajah',
        'status',
    ];

    protected $casts = [
        'tanggal'   => 'date',
        'latitude'  => 'decimal:8',
        'longitude' => 'decimal:8',
    ];

    public function hasCheckedOut(): bool
    {
        return !empty($this->jam_keluar);
    }

    public function getJamPulangAttribute(): string
    {
        return $this->jam_keluar ?? '-';
    }

    // Relasi: absen milik satu user
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Relasi: absen memiliki banyak request
    public function absenRequests()
    {
        return $this->hasMany(AbsenRequest::class);
    }

    // Helper: ambil nama user secara otomatis
    public function getNamaAttribute(): string
    {
        return $this->user->name ?? '-';
    }
}
