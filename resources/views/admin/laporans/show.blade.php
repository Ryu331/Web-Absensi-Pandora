@extends('layouts.app')
@section('title', 'Detail Laporan')

@section('content')
<div class="mb-6">
    <a href="{{ route('admin.laporans.index') }}" class="text-blue-600 hover:underline text-sm">Kembali ke Manajemen Laporan</a>
    <h2 class="text-2xl font-bold mt-2">Detail Laporan</h2>
</div>

<div class="bg-white dark:bg-gray-800 rounded-lg shadow p-5">
    <div class="flex flex-col sm:flex-row sm:items-start sm:justify-between gap-4">
        <div>
            <h3 class="text-xl font-semibold">{{ $laporan->judul ?? '(Tanpa Judul)' }}</h3>
            <p class="text-sm text-gray-500 mt-1">
                Dibuat oleh {{ $laporan->user?->name ?? 'N/A' }} sebagai {{ ucfirst($laporan->role_pembuat) }}
                pada {{ $laporan->created_at?->format('d/m/Y H:i') }}
            </p>
        </div>
        <a href="{{ $laporan->link }}" target="_blank" rel="noopener noreferrer" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 text-sm font-medium">
            Buka Link
        </a>
    </div>

    <div class="mt-5 space-y-4 text-sm">
        <div>
            <p class="text-gray-500 mb-1">Link</p>
            <a href="{{ $laporan->link }}" target="_blank" rel="noopener noreferrer" class="text-blue-600 hover:underline break-all">{{ $laporan->link }}</a>
        </div>
        <div>
            <p class="text-gray-500 mb-1">Deskripsi</p>
            <p class="whitespace-pre-line">{{ $laporan->deskripsi ?? '-' }}</p>
        </div>
    </div>
</div>
@endsection
