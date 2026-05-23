<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class AbsenRequest extends Model
{
    protected $fillable = [
        'absen_id',
        'user_id',
        'catatan',
        'status',
        'request_type',
        'validated_by',
        'validated_at',
    ];

    protected $casts = [
        'validated_at' => 'datetime',
    ];

    // Relasi ke absen
    public function absen()
    {
        return $this->belongsTo(Absen::class);
    }

    // Relasi ke user pemohon
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Relasi ke admin yang memvalidasi
    public function validator()
    {
        return $this->belongsTo(User::class, 'validated_by');
    }

    /**
     * Konfirmasi request → set status user menjadi "bekerja" atau "tidak_bekerja" untuk pulang cepat
     */
    public function konfirmasi(User $admin): void
    {
        $this->update([
            'status'       => 'dikonfirmasi',
            'validated_by' => $admin->id,
            'validated_at' => Carbon::now(config('app.timezone')),
        ]);

        if ($this->request_type === 'keluar') {
            $this->absen->update([
                'jam_keluar' => Carbon::now(config('app.timezone'))->format('H:i:s'),
                'status'     => 'dikonfirmasi',
            ]);

            $this->user->update(['status' => 'tidak_bekerja']);

            return;
        }

        $this->absen->update(['status' => 'dikonfirmasi']);
        $this->user->update(['status' => 'bekerja']);
    }

    /**
     * Tidak konfirmasi → set status user menjadi "tidak_bekerja" untuk absen masuk
     */
    public function tidakKonfirmasi(User $admin): void
    {
        $this->update([
            'status'       => 'tidak_dikonfirmasi',
            'validated_by' => $admin->id,
            'validated_at' => Carbon::now(config('app.timezone')),
        ]);

        if ($this->request_type === 'keluar') {
            return;
        }

        $this->absen->update(['status' => 'tidak_dikonfirmasi']);
        $this->user->update(['status' => 'tidak_bekerja']);
    }
}
