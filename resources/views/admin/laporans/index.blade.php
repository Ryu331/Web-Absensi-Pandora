@extends('layouts.app')
@section('title', 'Manajemen Laporan')

@section('content')
<div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-6">
    <div>
        <h2 class="text-2xl font-bold text-gray-800 dark:text-gray-100">Manajemen Laporan</h2>
        <p class="text-sm text-gray-500 mt-1">Semua laporan yang dikirimkan oleh user</p>
    </div>
    <a href="{{ route('admin.laporans.create') }}"
       class="px-4 py-2 bg-purple-600 text-white rounded-lg hover:bg-purple-700 transition text-sm font-semibold shadow-sm">
        ➕ Tambah Laporan
    </a>
</div>

{{-- Filter Form --}}
<div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700 p-4 mb-6">
    <form method="GET" class="flex flex-wrap gap-3 items-end">
        <div class="flex flex-col gap-1">
            <label class="text-xs font-medium text-gray-500">Filter User</label>
            <select name="user_id" class="px-4 py-3 rounded-xl border border-slate-700 bg-slate-800 text-slate-100 focus:border-blue-500 focus:ring-2 focus:ring-blue-500/40 outline-none transition duration-200 ease-in-out">
                <option value="">Semua User</option>
                @foreach($users as $u)
                    <option value="{{ $u->id }}" {{ request('user_id') == $u->id ? 'selected' : '' }}>{{ $u->name }}</option>
                @endforeach
            </select>
        </div>
        <div class="flex flex-col gap-1">
            <label class="text-xs font-medium text-gray-500">Pembuat</label>
            <select name="role_pembuat" class="px-4 py-3 rounded-xl border border-slate-700 bg-slate-800 text-slate-100 focus:border-blue-500 focus:ring-2 focus:ring-blue-500/40 outline-none transition duration-200 ease-in-out">
                <option value="">Semua Pembuat</option>
                <option value="user" {{ request('role_pembuat')==='user' ? 'selected':'' }}>User</option>
                <option value="admin" {{ request('role_pembuat')==='admin' ? 'selected':'' }}>Admin</option>
            </select>
        </div>
        <div class="flex flex-col gap-1 flex-1 min-w-48">
            <label class="text-xs font-medium text-gray-500">Cari Judul</label>
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari judul atau deskripsi..."
                   class="px-4 py-3 rounded-xl border border-slate-700 bg-slate-800 text-slate-100 placeholder:text-slate-400 focus:border-blue-500 focus:ring-2 focus:ring-blue-500/40 outline-none transition duration-200 ease-in-out">
        </div>
        <button type="submit" class="px-4 py-2 bg-gray-700 text-white rounded-lg text-sm hover:bg-gray-800 font-medium">
            🔍 Filter
        </button>
        <a href="{{ route('admin.laporans.index') }}" class="px-4 py-2 bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-200 rounded-lg text-sm hover:bg-gray-200 dark:hover:bg-gray-600 font-medium">
            Reset
        </a>
    </form>
</div>

{{-- Total --}}
<p class="text-sm text-gray-500 mb-4">Menampilkan <strong>{{ $laporans->total() }}</strong> laporan</p>

{{-- List laporan --}}
<div class="space-y-4">
    @forelse($laporans as $laporan)
    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700 p-5 hover:shadow-md transition">
        <div class="flex flex-col sm:flex-row justify-between items-start gap-4">
            <div class="flex-1 min-w-0">
                <div class="flex flex-wrap items-center gap-2 mb-1">
                    <h3 class="font-semibold text-gray-800 dark:text-gray-100">
                        {{ $laporan->judul ?? '(Tanpa Judul)' }}
                    </h3>
                    <span class="px-2 py-0.5 rounded-full text-xs font-semibold {{ $laporan->role_pembuat === 'admin' ? 'bg-purple-100 text-purple-700' : 'bg-blue-100 text-blue-700' }}">
                        {{ ucfirst($laporan->role_pembuat) }}
                    </span>
                </div>
                <div class="flex items-center gap-2 mb-2 text-sm text-gray-500">
                    <span>👤 {{ $laporan->user?->name ?? 'N/A' }}</span>
                    <span>·</span>
                    <span>{{ $laporan->created_at->format('d M Y, H:i') }}</span>
                </div>
                <a href="{{ $laporan->link }}" target="_blank" rel="noopener noreferrer"
                   class="text-blue-600 hover:text-blue-800 hover:underline text-sm break-all">
                    🔗 {{ Str::limit($laporan->link, 80) }}
                </a>
                @if($laporan->deskripsi)
                <p class="text-sm text-gray-500 dark:text-gray-400 mt-2">
                    {{ Str::limit($laporan->deskripsi, 150) }}
                </p>
                @endif
            </div>

            <div class="flex gap-2 shrink-0">
                <a href="{{ $laporan->link }}" target="_blank" rel="noopener noreferrer"
                   class="px-3 py-1.5 bg-blue-50 text-blue-700 rounded-lg text-xs font-medium hover:bg-blue-100 transition">
                    Buka
                </a>
                <form method="POST" action="{{ route('admin.laporans.destroy', $laporan) }}"
                      onsubmit="return confirm('Yakin ingin menghapus laporan ini?')">
                    @csrf @method('DELETE')
                    <button type="submit" class="px-3 py-1.5 bg-red-50 text-red-700 rounded-lg text-xs font-medium hover:bg-red-100 transition">
                        Hapus
                    </button>
                </form>
            </div>
        </div>
    </div>
    @empty
    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700 p-16 text-center">
        <div class="text-5xl mb-4">📭</div>
        <p class="text-gray-500 font-medium">Tidak ada laporan ditemukan</p>
        <p class="text-gray-400 text-sm mt-1">Coba ubah filter pencarian</p>
    </div>
    @endforelse
</div>

@if($laporans->hasPages())
<div class="mt-6">{{ $laporans->withQueryString()->links() }}</div>
@endif
@endsection
