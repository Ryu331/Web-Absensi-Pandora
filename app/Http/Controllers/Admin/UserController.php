<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Validation\Rules\Password;

class UserController extends Controller
{
    /**
     * Daftar semua user (kecuali admin sendiri).
     */
    public function index(Request $request)
    {
        $query = User::where('role', 'user');

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $users = $query->withCount('absens')->orderBy('name')->paginate(15);

        return view('admin.users.index', compact('users'));
    }

    /**
     * Tampilkan form tambah user.
     */
    public function create()
    {
        return view('admin.users.create');
    }

    /**
     * Simpan user baru (oleh admin).
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'     => ['required', 'string', 'max:255'],
            'email'    => ['required', 'string', 'email', 'max:255', 'unique:users,email'],
            'password' => ['required', 'confirmed', Password::min(8)],
            'role'     => ['required', 'in:user,admin'],
            'status'   => ['required', 'in:bekerja,tidak_bekerja'],
        ]);

        User::create([
            'name'     => $validated['name'],
            'email'    => $validated['email'],
            'password' => $validated['password'],
            'role'     => $validated['role'],
            'status'   => $validated['status'],
        ]);

        return redirect()->route('admin.users.index')
            ->with('success', 'User berhasil ditambahkan.');
    }

    /**
     * Tampilkan detail user beserta riwayat absensinya.
     */
    public function show(User $user)
    {
        $user->load(['absens' => function ($q) {
            $q->orderByDesc('tanggal')->limit(20);
        }, 'absenRequests.absen']);

        return view('admin.users.show', compact('user'));
    }

    /**
     * Tampilkan form edit user.
     */
    public function edit(User $user)
    {
        return view('admin.users.edit', compact('user'));
    }

    /**
     * Update data user.
     */
    public function update(Request $request, User $user)
    {
        $rules = [
            'name'   => ['required', 'string', 'max:255'],
            'email'  => ['required', 'string', 'email', 'max:255', 'unique:users,email,' . $user->id],
            'role'   => ['required', 'in:user,admin'],
            'status' => ['required', 'in:bekerja,tidak_bekerja'],
        ];

        if ($request->filled('password')) {
            $rules['password'] = ['confirmed', Password::min(8)];
        }

        $validated = $request->validate($rules);

        $data = [
            'name'   => $validated['name'],
            'email'  => $validated['email'],
            'role'   => $validated['role'],
            'status' => $validated['status'],
        ];

        if ($request->filled('password')) {
            $data['password'] = $request->password;
        }

        $user->update($data);

        return redirect()->route('admin.users.index')
            ->with('success', 'Data user berhasil diperbarui.');
    }

    /**
     * Hapus user (beserta data absensinya via cascade).
     */
    public function destroy(User $user)
    {
        if ($user->role === 'admin') {
            return back()->with('error', 'Tidak bisa menghapus akun admin.');
        }

        $user->delete();

        return redirect()->route('admin.users.index')
            ->with('success', 'User berhasil dihapus.');
    }

    /**
     * Toggle status user bekerja / tidak_bekerja.
     */
    public function toggleStatus(User $user)
    {
        $user->update([
            'status' => $user->status === 'bekerja' ? 'tidak_bekerja' : 'bekerja',
        ]);

        return back()->with('success', 'Status user berhasil diubah.');
    }
}
