<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Laporan extends Model
{
    protected $fillable = [
        'user_id',
        'judul',
        'link',
        'deskripsi',
        'role_pembuat',
    ];

    // Relasi: laporan milik satu user
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
