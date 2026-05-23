<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Absen;
use App\Models\AbsenRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AbsenController extends Controller
{
    /**
     * Daftar semua absensi dengan filter.
     */
    public function index(Request $request)
    {
        $query = Absen::with('user');

        if ($request->filled('user_id')) {
            $query->where('user_id', $request->user_id);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('tanggal_dari')) {
            $query->whereDate('tanggal', '>=', $request->tanggal_dari);
        }

        if ($request->filled('tanggal_sampai')) {
            $query->whereDate('tanggal', '<=', $request->tanggal_sampai);
        }

        $absens   = $query->orderByDesc('tanggal')->orderByDesc('jam')->paginate(20);
        $users    = User::where('role', 'user')->orderBy('name')->get();

        return view('admin.absens.index', compact('absens', 'users'));
    }

    /**
     * Detail satu record absensi.
     */
    public function show(Absen $absen)
    {
        $absen->load(['user', 'absenRequests.validator']);
        return view('admin.absens.show', compact('absen'));
    }

    /**
     * Daftar semua AbsenRequest yang masuk.
     */
    public function requests(Request $request)
    {
        $query = AbsenRequest::with(['user', 'absen', 'validator']);

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $absenRequests = $query->orderByDesc('created_at')->paginate(20);

        return view('admin.absens.requests', compact('absenRequests'));
    }

    /**
     * Terima / konfirmasi AbsenRequest.
     */
    public function terima(AbsenRequest $absenRequest)
    {
        if ($absenRequest->status !== 'pending') {
            return back()->with('error', 'Request ini sudah diproses sebelumnya.');
        }

        $absenRequest->load(['absen', 'user']);
        $absenRequest->konfirmasi(Auth::user());

        return back()->with('success', 'Absen dikonfirmasi. Status user diubah menjadi bekerja.');
    }

    /**
     * Tolak / tidak konfirmasi AbsenRequest.
     */
    public function tolak(AbsenRequest $absenRequest)
    {
        if ($absenRequest->status !== 'pending') {
            return back()->with('error', 'Request ini sudah diproses sebelumnya.');
        }

        $absenRequest->load(['absen', 'user']);
        $absenRequest->tidakKonfirmasi(Auth::user());

        return back()->with('success', 'Absen ditolak. Status user tetap tidak bekerja.');
    }
}
