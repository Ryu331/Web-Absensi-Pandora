<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Cuti;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CutiController extends Controller
{
    /**
     * Daftar semua permohonan cuti dengan filter.
     */
    public function index(Request $request)
    {
        $query = Cuti::with(['user', 'approvedBy']);

        if ($request->filled('user_id')) {
            $query->where('user_id', $request->user_id);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('tanggal_dari')) {
            $query->whereDate('tanggal_mulai', '>=', $request->tanggal_dari);
        }

        if ($request->filled('tanggal_sampai')) {
            $query->whereDate('tanggal_selesai', '<=', $request->tanggal_sampai);
        }

        $cutis = $query->orderByDesc('created_at')->paginate(20);
        $users = User::where('role', 'user')->orderBy('name')->get();

        return view('admin.cutis.index', compact('cutis', 'users'));
    }

    /**
     * Detail satu permohonan cuti.
     */
    public function show(Cuti $cuti)
    {
        $cuti->load(['user', 'approvedBy']);
        return view('admin.cutis.show', compact('cuti'));
    }

    /**
     * Setujui permohonan cuti.
     */
    public function setujui(Request $request, Cuti $cuti)
    {
        if ($cuti->status !== 'pending') {
            return back()->with('error', 'Permohonan ini sudah diproses sebelumnya.');
        }

        $validated = $request->validate([
            'catatan_admin' => ['nullable', 'string', 'max:1000'],
        ]);

        $cuti->update([
            'status'        => 'disetujui',
            'approved_by'   => Auth::id(),
            'approved_at'   => now(),
            'catatan_admin' => $validated['catatan_admin'] ?? null,
        ]);

        return back()->with('success', 'Permohonan cuti berhasil disetujui.');
    }

    /**
     * Tolak permohonan cuti.
     */
    public function tolak(Request $request, Cuti $cuti)
    {
        if ($cuti->status !== 'pending') {
            return back()->with('error', 'Permohonan ini sudah diproses sebelumnya.');
        }

        $validated = $request->validate([
            'catatan_admin' => ['nullable', 'string', 'max:1000'],
        ]);

        $cuti->update([
            'status'        => 'ditolak',
            'approved_by'   => Auth::id(),
            'approved_at'   => now(),
            'catatan_admin' => $validated['catatan_admin'] ?? null,
        ]);

        return back()->with('success', 'Permohonan cuti berhasil ditolak.');
    }
}
