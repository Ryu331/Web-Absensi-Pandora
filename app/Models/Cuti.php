<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cuti extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'tanggal_mulai',
        'tanggal_selesai',
        'jenis_cuti',
        'alasan',
        'status',
        'approved_by',
        'approved_at',
        'catatan_admin',
    ];

    protected $casts = [
        'tanggal_mulai' => 'date',
        'tanggal_selesai' => 'date',
        'approved_at' => 'datetime',
    ];

    // Relasi: cuti milik user
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Relasi: cuti disetujui oleh admin
    public function approvedBy()
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    // Scope: hanya cuti pending
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    // Scope: hanya cuti disetujui
    public function scopeDisetujui($query)
    {
        return $query->where('status', 'disetujui');
    }

    // Scope: hanya cuti ditolak
    public function scopeDitolak($query)
    {
        return $query->where('status', 'ditolak');
    }
}
