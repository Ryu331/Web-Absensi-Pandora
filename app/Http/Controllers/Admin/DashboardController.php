<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Absen;
use App\Models\AbsenRequest;
use App\Models\Laporan;
use App\Models\User;
use Carbon\Carbon;

class DashboardController extends Controller
{
    /**
     * Tampilkan dashboard admin dengan statistik lengkap.
     */
    public function index()
    {
        $stats = [
            'total_users'        => User::where('role', 'user')->count(),
            'hadir_hari_ini'     => Absen::whereDate('tanggal', Carbon::today(config('app.timezone')))->where('status', 'dikonfirmasi')->count(),
            'pending_requests'   => AbsenRequest::where('status', 'pending')->count(),
            'total_laporans'     => Laporan::count(),
            'sedang_bekerja'     => User::where('status', 'bekerja')->where('role', 'user')->count(),
            'tidak_bekerja'      => User::where('status', 'tidak_bekerja')->where('role', 'user')->count(),
        ];

        $absenTerbaru = Absen::with('user')
            ->orderByDesc('created_at')
            ->limit(10)
            ->get();

        $pendingRequests = AbsenRequest::with(['user', 'absen'])
            ->where('status', 'pending')
            ->orderByDesc('created_at')
            ->limit(5)
            ->get();

        return view('admin.dashboard', compact('stats', 'absenTerbaru', 'pendingRequests'));
    }
}
