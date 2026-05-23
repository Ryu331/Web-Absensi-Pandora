<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Cuti;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CutiController extends Controller
{
    /**
     * Tampilkan riwayat cuti user yang sedang login.
     */
    public function index()
    {
        $cutis = Cuti::where('user_id', Auth::id())
            ->orderByDesc('created_at')
            ->paginate(15);

        return view('user.cutis.index', compact('cutis'));
    }

    /**
     * Tampilkan form input permohonan cuti baru.
     */
    public function create()
    {
        return view('user.cutis.create');
    }

    /**
     * Simpan permohonan cuti baru.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'tanggal_mulai'   => ['required', 'date', 'after_or_equal:today'],
            'tanggal_selesai' => ['required', 'date', 'after_or_equal:tanggal_mulai'],
            'jenis_cuti'      => ['required', 'string', 'max:100'],
            'alasan'          => ['required', 'string', 'max:1000'],
        ]);

        Cuti::create([
            'user_id'        => Auth::id(),
            'tanggal_mulai'   => $validated['tanggal_mulai'],
            'tanggal_selesai' => $validated['tanggal_selesai'],
            'jenis_cuti'      => $validated['jenis_cuti'],
            'alasan'          => $validated['alasan'],
            'status'          => 'pending',
        ]);

        return redirect()->route('user.cutis.index')
            ->with('success', 'Permohonan cuti berhasil diajukan. Menunggu persetujuan admin.');
    }

    /**
     * Detail satu permohonan cuti.
     */
    public function show(Cuti $cuti)
    {
        // Pastikan cuti milik user yang sedang login
        if ($cuti->user_id !== Auth::id()) {
            abort(403);
        }

        $cuti->load('approvedBy');
        return view('user.cutis.show', compact('cuti'));
    }
}
