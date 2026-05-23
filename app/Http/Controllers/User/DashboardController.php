<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Absen;
use App\Models\AbsenRequest;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    /**
     * Tampilkan dashboard user dengan statistik absensi pribadinya.
     */
    public function index()
    {
        $userId = Auth::id();

        $todayAbsen = Absen::where('user_id', $userId)
            ->whereDate('tanggal', Carbon::today(config('app.timezone')))
            ->with('absenRequests')
            ->first();

        $pendingMasuk = $todayAbsen?->absenRequests
            ->where('status', 'pending')
            ->where('request_type', 'masuk')
            ->first();

        $pendingKeluar = $todayAbsen?->absenRequests
            ->where('status', 'pending')
            ->where('request_type', 'keluar')
            ->first();

        $currentStatus = 'Tidak Bekerja';

        if ($pendingMasuk) {
            $currentStatus = 'Menunggu Konfirmasi Masuk';
        } elseif ($pendingKeluar) {
            $currentStatus = 'Menunggu Konfirmasi Pulang Cepat';
        } elseif ($todayAbsen && $todayAbsen->jam_keluar) {
            $currentStatus = 'Selesai Bekerja';
        } elseif (Auth::user()->status === 'bekerja') {
            $currentStatus = 'Bekerja';
        } elseif ($todayAbsen && $todayAbsen->absenRequests->sortByDesc('created_at')->first()?->status === 'tidak_dikonfirmasi') {
            $currentStatus = 'Ditolak';
        }

        $dashboardAction = null;
        if (!$todayAbsen || ($todayAbsen->status === 'tidak_dikonfirmasi' && !$pendingMasuk)) {
            $dashboardAction = 'masuk';
        } elseif ($todayAbsen && $todayAbsen->status === 'dikonfirmasi' && !$todayAbsen->jam_keluar && !$pendingKeluar) {
            $dashboardAction = 'keluar';
        }

        $stats = [
            'absen_bulan_ini'   => Absen::where('user_id', $userId)
                                        ->whereMonth('tanggal', now(config('app.timezone'))->month)
                                        ->whereYear('tanggal', now(config('app.timezone'))->year)
                                        ->count(),
            'absen_dikonfirmasi'=> Absen::where('user_id', $userId)
                                        ->where('status', 'dikonfirmasi')
                                        ->whereMonth('tanggal', now(config('app.timezone'))->month)
                                        ->whereYear('tanggal', now(config('app.timezone'))->year)
                                        ->count(),
            'pending_requests'  => AbsenRequest::where('user_id', $userId)
                                        ->where('status', 'pending')
                                        ->count(),
            'dikonfirmasi_requests' => AbsenRequest::where('user_id', $userId)
                                        ->where('status', 'dikonfirmasi')
                                        ->count(),
        ];

        $riwayatAbsen = Absen::where('user_id', $userId)
            ->orderByDesc('tanggal')
            ->limit(10)
            ->get();

        $riwayatRequests = AbsenRequest::where('user_id', $userId)
            ->with('absen')
            ->orderByDesc('created_at')
            ->limit(3)
            ->get();

        return view('user.dashboard', compact('stats', 'riwayatAbsen', 'riwayatRequests', 'todayAbsen', 'currentStatus', 'dashboardAction'));
    }
}
