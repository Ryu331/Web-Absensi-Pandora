@extends('layouts.app')
@section('title', 'Laporan Saya')

@section('content')
<div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-6">
    <div>
        <h2 class="text-2xl font-bold text-gray-800 dark:text-gray-100">Laporan Saya</h2>
        <p class="text-sm text-gray-500 mt-1">Daftar laporan yang sudah Anda kirimkan ke admin</p>
    </div>
    <a href="{{ route('user.laporans.create') }}"
       class="px-4 py-2 bg-purple-600 text-white rounded-lg hover:bg-purple-700 transition text-sm font-semibold shadow-sm whitespace-nowrap">
        ➕ Kirim Laporan Baru
    </a>
</div>

{{-- Stats --}}
<div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mb-6">
    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700 p-4">
        <p class="text-xs text-gray-500 font-medium uppercase tracking-wide">Total Laporan</p>
        <p class="text-2xl font-bold text-gray-800 dark:text-gray-100 mt-1">{{ $laporans->total() }}</p>
    </div>
    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700 p-4">
        <p class="text-xs text-gray-500 font-medium uppercase tracking-wide">Bulan Ini</p>
        <p class="text-2xl font-bold text-purple-600 mt-1">
            {{ \App\Models\Laporan::where('user_id', Auth::id())->whereMonth('created_at', now(config('app.timezone'))->month)->count() }}
        </p>
    </div>
    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700 p-4">
        <p class="text-xs text-gray-500 font-medium uppercase tracking-wide">Terakhir Kirim</p>
        <p class="text-sm font-semibold text-gray-700 dark:text-gray-200 mt-1">
            @if($laporans->count())
                {{ $laporans->first()->created_at->format('d M Y') }}
            @else
                —
            @endif
        </p>
    </div>
</div>

{{-- List laporan --}}
<div class="space-y-4">
    @forelse($laporans as $laporan)
    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700 p-5 hover:shadow-md transition">
        <div class="flex flex-col sm:flex-row justify-between items-start gap-4">
            <div class="flex-1 min-w-0">
                <div class="flex items-start gap-3 mb-2">
                    <div class="w-9 h-9 rounded-full bg-purple-100 text-purple-600 flex items-center justify-center text-sm font-bold shrink-0 mt-0.5">
                        📄
                    </div>
                    <div class="flex-1 min-w-0">
                        <h3 class="font-semibold text-gray-800 dark:text-gray-100 truncate">
                            {{ $laporan->judul ?? '(Tanpa Judul)' }}
                        </h3>
                        <a href="{{ $laporan->link }}" target="_blank" rel="noopener noreferrer"
                           class="text-blue-600 hover:text-blue-800 hover:underline text-xs break-all">
                            🔗 {{ Str::limit($laporan->link, 60) }}
                        </a>
                    </div>
                </div>

                @if($laporan->deskripsi)
                <p class="text-sm text-gray-500 dark:text-gray-400 mt-2 ml-12">
                    {{ Str::limit($laporan->deskripsi, 150) }}
                </p>
                @endif

                <div class="flex items-center gap-3 mt-3 ml-12">
                    <span class="text-xs text-gray-400">
                        🕒 {{ $laporan->created_at->format('d M Y, H:i') }}
                    </span>
                    <span class="text-xs text-gray-400">·</span>
                    <span class="text-xs text-gray-400">
                        {{ $laporan->created_at->diffForHumans() }}
                    </span>
                </div>
            </div>

            {{-- Aksi --}}
            <div class="flex gap-2 shrink-0">
                <a href="{{ $laporan->link }}" target="_blank" rel="noopener noreferrer"
                   class="px-3 py-1.5 bg-blue-50 text-blue-700 rounded-lg text-xs font-medium hover:bg-blue-100 transition">
                    Buka Link
                </a>
                <a href="{{ route('user.laporans.edit', $laporan) }}"
                   class="px-3 py-1.5 bg-yellow-50 text-yellow-700 rounded-lg text-xs font-medium hover:bg-yellow-100 transition">
                    Edit
                </a>
                <form method="POST" action="{{ route('user.laporans.destroy', $laporan) }}"
                      onsubmit="return confirm('Yakin ingin menghapus laporan ini? Tindakan ini tidak bisa dibatalkan.')">
                    @csrf @method('DELETE')
                    <button type="submit"
                            class="px-3 py-1.5 bg-red-50 text-red-700 rounded-lg text-xs font-medium hover:bg-red-100 transition">
                        Hapus
                    </button>
                </form>
            </div>
        </div>
    </div>
    @empty
    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700 p-16 text-center">
        <div class="text-5xl mb-4">📋</div>
        <p class="text-gray-500 text-base font-medium">Belum ada laporan yang dikirim</p>
        <p class="text-gray-400 text-sm mt-1 mb-6">Laporan yang Anda kirimkan akan tampil di sini</p>
        <a href="{{ route('user.laporans.create') }}"
           class="inline-block px-6 py-2.5 bg-purple-600 text-white rounded-lg hover:bg-purple-700 transition text-sm font-semibold">
            Kirim Laporan Pertama
        </a>
    </div>
    @endforelse
</div>

@if($laporans->hasPages())
<div class="mt-6">{{ $laporans->links() }}</div>
@endif
@endsection
