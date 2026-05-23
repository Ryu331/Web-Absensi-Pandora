<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Laporan;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LaporanController extends Controller
{
    /**
     * Daftar semua laporan.
     */
    public function index(Request $request)
    {
        $query = Laporan::with('user');

        if ($request->filled('user_id')) {
            $query->where('user_id', $request->user_id);
        }

        if ($request->filled('role_pembuat')) {
            $query->where('role_pembuat', $request->role_pembuat);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('judul', 'like', "%{$search}%")
                  ->orWhere('deskripsi', 'like', "%{$search}%");
            });
        }

        $laporans = $query->orderByDesc('created_at')->paginate(20);
        $users    = User::where('role', 'user')->orderBy('name')->get();

        return view('admin.laporans.index', compact('laporans', 'users'));
    }

    /**
     * Form buat laporan baru (oleh admin).
     */
    public function create()
    {
        $users = User::where('role', 'user')->orderBy('name')->get();
        return view('admin.laporans.create', compact('users'));
    }

    /**
     * Simpan laporan baru.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'user_id'   => ['required', 'exists:users,id'],
            'judul'     => ['nullable', 'string', 'max:255'],
            'link'      => ['required', 'url', 'max:255'],
            'deskripsi' => ['nullable', 'string'],
        ]);

        Laporan::create([
            'user_id'      => $validated['user_id'],
            'judul'        => $validated['judul'],
            'link'         => $validated['link'],
            'deskripsi'    => $validated['deskripsi'],
            'role_pembuat' => 'admin',
        ]);

        return redirect()->route('admin.laporans.index')
            ->with('success', 'Laporan berhasil ditambahkan.');
    }

    /**
     * Detail laporan.
     */
    public function show(Laporan $laporan)
    {
        $laporan->load('user');
        return view('admin.laporans.show', compact('laporan'));
    }

    /**
     * Hapus laporan.
     */
    public function destroy(Laporan $laporan)
    {
        $laporan->delete();
        return redirect()->route('admin.laporans.index')
            ->with('success', 'Laporan berhasil dihapus.');
    }
}
