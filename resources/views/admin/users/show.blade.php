@extends('layouts.app')
@section('title', 'Detail User')

@section('content')
<div class="mb-6 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
    <div>
        <a href="{{ route('admin.users.index') }}" class="text-blue-600 hover:underline text-sm">Kembali ke Daftar User</a>
        <h2 class="text-2xl font-bold mt-2">Detail User</h2>
    </div>
    <a href="{{ route('admin.users.edit', $user) }}" class="px-4 py-2 bg-yellow-500 text-white rounded-lg hover:bg-yellow-600 text-sm font-medium">
        Edit User
    </a>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-5">
        <h3 class="font-semibold mb-4">Profil</h3>
        <dl class="space-y-3 text-sm">
            <div>
                <dt class="text-gray-500">Nama</dt>
                <dd class="font-medium">{{ $user->name }}</dd>
            </div>
            <div>
                <dt class="text-gray-500">Email</dt>
                <dd class="font-medium break-all">{{ $user->email }}</dd>
            </div>
            <div>
                <dt class="text-gray-500">Role</dt>
                <dd class="font-medium">{{ ucfirst($user->role) }}</dd>
            </div>
            <div>
                <dt class="text-gray-500">Status</dt>
                <dd>
                    <span class="px-2 py-1 rounded text-xs font-medium {{ $user->status === 'bekerja' ? 'bg-green-100 text-green-700' : 'bg-gray-100 text-gray-700' }}">
                        {{ $user->status === 'bekerja' ? 'Bekerja' : 'Tidak Bekerja' }}
                    </span>
                </dd>
            </div>
            <div>
                <dt class="text-gray-500">Terdaftar</dt>
                <dd class="font-medium">{{ $user->created_at?->format('d/m/Y H:i') }}</dd>
            </div>
        </dl>
    </div>

    <div class="lg:col-span-2 bg-white dark:bg-gray-800 rounded-lg shadow overflow-hidden">
        <div class="p-5 border-b dark:border-gray-700">
            <h3 class="font-semibold">Riwayat Absensi Terbaru</h3>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="bg-gray-100 dark:bg-gray-700">
                    <tr>
                        <th class="px-4 py-3 text-left">Tanggal</th>
                        <th class="px-4 py-3 text-left">Jam</th>
                        <th class="px-4 py-3 text-left">Status</th>
                        <th class="px-4 py-3 text-left">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($user->absens as $absen)
                    <tr class="border-b dark:border-gray-700">
                        <td class="px-4 py-3">{{ $absen->tanggal?->format('d/m/Y') }}</td>
                        <td class="px-4 py-3">{{ $absen->jam }}</td>
                        <td class="px-4 py-3">{{ ucfirst(str_replace('_', ' ', $absen->status)) }}</td>
                        <td class="px-4 py-3">
                            <a href="{{ route('admin.absens.show', $absen) }}" class="text-blue-600 hover:underline">Detail</a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" class="px-4 py-8 text-center text-gray-400">Belum ada absensi</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
