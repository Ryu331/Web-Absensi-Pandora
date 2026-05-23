@extends('layouts.app')

@section('title', 'Dashboard Admin - ' . config('app.name'))

@section('content')
<div class="mb-8">
    <h2 class="text-3xl font-bold mb-1 text-slate-100">Dashboard Admin</h2>
    <p class="text-slate-400">Kelola sistem absensi dan data pengguna</p>
</div>

{{-- STATS CARDS --}}
<div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-6 gap-4 mb-8">
    <div class="bg-slate-900/95 border border-slate-800 rounded-3xl shadow-xl shadow-slate-950/40 p-5 col-span-1">
        <p class="text-xs text-slate-400 uppercase tracking-wide">Total User</p>
        <p class="text-3xl font-bold text-sky-300 mt-1">{{ $stats['total_users'] }}</p>
    </div>
    <div class="bg-slate-900/95 border border-slate-800 rounded-3xl shadow-xl shadow-slate-950/40 p-5 col-span-1">
        <p class="text-xs text-slate-400 uppercase tracking-wide">Hadir Hari Ini</p>
        <p class="text-3xl font-bold text-emerald-300 mt-1">{{ $stats['hadir_hari_ini'] }}</p>
    </div>
    <div class="bg-slate-900/95 border border-slate-800 rounded-3xl shadow-xl shadow-slate-950/40 p-5 col-span-1">
        <p class="text-xs text-slate-400 uppercase tracking-wide">Pending</p>
        <p class="text-3xl font-bold text-amber-300 mt-1">{{ $stats['pending_requests'] }}</p>
    </div>
    <div class="bg-slate-900/95 border border-slate-800 rounded-3xl shadow-xl shadow-slate-950/40 p-5 col-span-1">
        <p class="text-xs text-slate-400 uppercase tracking-wide">Total Laporan</p>
        <p class="text-3xl font-bold text-violet-300 mt-1">{{ $stats['total_laporans'] }}</p>
    </div>
    <div class="bg-slate-900/95 border border-slate-800 rounded-3xl shadow-xl shadow-slate-950/40 p-5 col-span-1">
        <p class="text-xs text-slate-400 uppercase tracking-wide">Bekerja</p>
        <p class="text-3xl font-bold text-teal-300 mt-1">{{ $stats['sedang_bekerja'] }}</p>
    </div>
    <div class="bg-slate-900/95 border border-slate-800 rounded-3xl shadow-xl shadow-slate-950/40 p-5 col-span-1">
        <p class="text-xs text-slate-400 uppercase tracking-wide">Tidak Aktif</p>
        <p class="text-3xl font-bold text-slate-400 mt-1">{{ $stats['tidak_bekerja'] }}</p>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

    {{-- ABSENSI TERBARU --}}
    <div class="lg:col-span-2 space-y-6">
        <div class="bg-slate-900/95 border border-slate-800 rounded-3xl shadow-xl shadow-slate-950/40 p-6">
            <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-3 mb-4">
                <h3 class="text-lg font-bold text-slate-100">Absensi Terbaru</h3>
                <a href="{{ route('admin.absens.index') }}" class="text-sky-300 hover:text-sky-200 text-sm transition">Lihat Semua →</a>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead class="bg-slate-950/90 border-b border-slate-800">
                        <tr>
                            <th class="px-4 py-2 text-left text-slate-400">Nama</th>
                            <th class="px-4 py-2 text-left text-slate-400">Tanggal</th>
                            <th class="px-4 py-2 text-left text-slate-400">Jam</th>
                            <th class="px-4 py-2 text-left text-slate-400">Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($absenTerbaru as $absen)
                        <tr class="border-b border-slate-800 hover:bg-slate-900">
                            <td class="px-4 py-3 font-medium text-slate-100">{{ $absen->user?->name ?? 'N/A' }}</td>
                            <td class="px-4 py-3 text-slate-300">{{ $absen->tanggal->format('d/m/Y') }}</td>
                            <td class="px-4 py-3 text-slate-300">{{ $absen->jam }}</td>
                            <td class="px-4 py-3">
                                @php
                                $color = match($absen->status) {
                                    'dikonfirmasi'      => 'bg-emerald-900/80 text-emerald-200',
                                    'pending'           => 'bg-amber-900/80 text-amber-200',
                                    'tidak_dikonfirmasi'=> 'bg-rose-900/80 text-rose-200',
                                    default             => 'bg-slate-800 text-slate-200',
                                };
                                @endphp
                                <span class="px-2 py-1 rounded text-xs font-medium {{ $color }}">
                                    {{ ucfirst(str_replace('_', ' ', $absen->status)) }}
                                </span>
                            </td>
                        </tr>
                        @empty
                        <tr><td colspan="4" class="px-4 py-6 text-center text-slate-500">Belum ada data absensi</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        {{-- PERMINTAAN PENDING --}}
        <div class="bg-slate-900/95 border border-slate-800 rounded-3xl shadow-xl shadow-slate-950/40 p-6">
            <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-3 mb-4">
                <h3 class="text-lg font-bold text-slate-100">Permintaan Absensi Pending</h3>
                <a href="{{ route('admin.absen-requests.index') }}" class="text-sky-300 hover:text-sky-200 text-sm transition">Lihat Semua →</a>
            </div>
            <div class="space-y-3">
                @forelse($pendingRequests as $req)
                <div class="flex items-center justify-between p-4 bg-slate-900/90 rounded-3xl border border-slate-800">
                    <div class="flex-1 min-w-0">
                        <p class="font-medium truncate text-slate-100">{{ $req->user?->name ?? 'N/A' }}</p>
                        <p class="text-sm text-slate-400">
                            {{ $req->absen?->tanggal?->format('d/m/Y') ?? '-' }} · {{ $req->absen?->jam ?? '-' }}
                        </p>
                        <p class="text-xs text-slate-500 mt-1">{{ Str::limit($req->catatan ?? '-', 60) }}</p>
                    </div>
                    <div class="flex gap-2 ml-4 shrink-0">
                        <form method="POST" action="{{ route('admin.absen-requests.terima', $req) }}">
                            @csrf
                            <button type="submit" class="px-3 py-1.5 bg-green-600 text-white text-xs rounded hover:bg-green-700">
                                Terima
                            </button>
                        </form>
                        <form method="POST" action="{{ route('admin.absen-requests.tolak', $req) }}">
                            @csrf
                            <button type="submit" class="px-3 py-1.5 bg-red-600 text-white text-xs rounded hover:bg-red-700">
                                Tolak
                            </button>
                        </form>
                    </div>
                </div>
                @empty
                <p class="text-center text-gray-400 py-4">Tidak ada permintaan pending</p>
                @endforelse
            </div>
        </div>
    </div>

    {{-- SIDEBAR MENU ADMIN --}}
    <div class="space-y-4">
        <div class="bg-slate-900/95 border border-slate-800 rounded-3xl shadow-xl shadow-slate-950/40 p-6">
            <h3 class="text-lg font-bold text-slate-100 mb-4">Menu Admin</h3>
            <div class="space-y-2">
                <a href="{{ route('admin.users.create') }}"
                   class="flex items-center gap-2 w-full p-3 bg-slate-800 text-slate-100 rounded-2xl hover:bg-slate-700 transition font-medium text-sm">
                    ➕ Tambah User
                </a>
                <a href="{{ route('admin.users.index') }}"
                   class="flex items-center gap-2 w-full p-3 bg-slate-800 text-slate-100 rounded-2xl hover:bg-slate-700 transition font-medium text-sm">
                    👥 Kelola User
                </a>
                <a href="{{ route('admin.absen-requests.index') }}"
                   class="flex items-center gap-2 w-full p-3 bg-slate-800 text-slate-100 rounded-2xl hover:bg-slate-700 transition font-medium text-sm">
                    📋 Permintaan Absen
                    @if($stats['pending_requests'] > 0)
                        <span class="ml-auto bg-rose-500 text-white text-xs rounded-full px-2">{{ $stats['pending_requests'] }}</span>
                    @endif
                </a>
                <a href="{{ route('admin.absens.index') }}"
                   class="flex items-center gap-2 w-full p-3 bg-slate-800 text-slate-100 rounded-2xl hover:bg-slate-700 transition font-medium text-sm">
                    📅 Semua Absensi
                </a>
                <a href="{{ route('admin.laporans.index') }}"
                   class="flex items-center gap-2 w-full p-3 bg-slate-800 text-slate-100 rounded-2xl hover:bg-slate-700 transition font-medium text-sm">
                    📄 Laporan
                </a>
                <a href="{{ route('admin.cutis.index') }}"
                   class="flex items-center gap-2 w-full p-3 bg-slate-800 text-slate-100 rounded-2xl hover:bg-slate-700 transition font-medium text-sm">
                    📋 Kelola Cuti
                </a>
            </div>
        </div>

        <div class="bg-gradient-to-br from-purple-50 to-purple-100 dark:from-purple-900/30 dark:to-purple-800/20 rounded-lg shadow p-5 border border-purple-200 dark:border-purple-700">
            <h4 class="font-bold text-purple-900 dark:text-purple-200 mb-1">👨‍💼 Mode Admin</h4>
            <p class="text-sm text-purple-700 dark:text-purple-300">Anda memiliki akses penuh ke semua fitur administrasi sistem.</p>
        </div>
    </div>

</div>
@endsection
