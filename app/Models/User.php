<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'status',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'password' => 'hashed',
    ];

    // Relasi: satu user memiliki banyak absen
    public function absens()
    {
        return $this->hasMany(Absen::class);
    }

    // Relasi: satu user memiliki banyak absen request
    public function absenRequests()
    {
        return $this->hasMany(AbsenRequest::class);
    }

    // Relasi: satu user memiliki banyak laporan
    public function laporans()
    {
        return $this->hasMany(Laporan::class);
    }

    // Relasi: satu user memiliki banyak cuti
    public function cutis()
    {
        return $this->hasMany(Cuti::class);
    }

    // Helper: apakah user adalah admin?
    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }
}