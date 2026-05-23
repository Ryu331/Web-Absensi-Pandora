<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Laporan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LaporanController extends Controller
{
    /**
     * Daftar laporan milik user yang sedang login.
     */
    public function index()
    {
        $laporans = Laporan::where('user_id', Auth::id())
            ->orderByDesc('created_at')
            ->paginate(15);

        return view('user.laporans.index', compact('laporans'));
    }

    /**
     * Form buat laporan baru.
     */
    public function create()
    {
        return view('user.laporans.create');
    }

    /**
     * Simpan laporan baru.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'judul'     => ['nullable', 'string', 'max:255'],
            'link'      => ['required', 'url', 'max:255'],
            'deskripsi' => ['nullable', 'string'],
        ]);

        Laporan::create([
            'user_id'      => Auth::id(),
            'judul'        => $validated['judul'],
            'link'         => $validated['link'],
            'deskripsi'    => $validated['deskripsi'],
            'role_pembuat' => 'user',
        ]);

        return redirect()->route('user.laporans.index')
            ->with('success', 'Laporan berhasil dikirim.');
    }

    /**
     * Detail satu laporan milik user.
     */
    public function show(Laporan $laporan)
    {
        if ($laporan->user_id !== Auth::id()) {
            abort(403);
        }

        return view('user.laporans.show', compact('laporan'));
    }

    /**
     * Form edit laporan.
     */
    public function edit(Laporan $laporan)
    {
        if ($laporan->user_id !== Auth::id()) {
            abort(403);
        }

        return view('user.laporans.edit', compact('laporan'));
    }

    /**
     * Update laporan.
     */
    public function update(Request $request, Laporan $laporan)
    {
        if ($laporan->user_id !== Auth::id()) {
            abort(403);
        }

        $validated = $request->validate([
            'judul'     => ['nullable', 'string', 'max:255'],
            'link'      => ['required', 'url', 'max:255'],
            'deskripsi' => ['nullable', 'string'],
        ]);

        $laporan->update($validated);

        return redirect()->route('user.laporans.index')
            ->with('success', 'Laporan berhasil diperbarui.');
    }

    /**
     * Hapus laporan.
     */
    public function destroy(Laporan $laporan)
    {
        if ($laporan->user_id !== Auth::id()) {
            abort(403);
        }

        $laporan->delete();

        return redirect()->route('user.laporans.index')
            ->with('success', 'Laporan berhasil dihapus.');
    }
}
