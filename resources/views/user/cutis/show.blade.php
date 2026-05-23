@extends('layouts.app')
@section('title', 'Detail Cuti')

@section('content')
<div class="max-w-2xl mx-auto">
    <div class="mb-6">
        <a href="{{ route('user.cutis.index') }}" class="text-blue-600 hover:underline text-sm">← Kembali ke Riwayat Cuti</a>
        <h2 class="text-2xl font-bold mt-2">Detail Permohonan Cuti</h2>
    </div>

    <div class="bg-white dark:bg-gray-800 rounded-xl shadow p-6">
        <div class="space-y-4">
            <div class="flex justify-between items-start">
                <div>
                    <p class="text-sm text-gray-500">Jenis Cuti</p>
                    <p class="font-semibold text-lg">{{ ucfirst(str_replace('_', ' ', $cuti->jenis_cuti)) }}</p>
                </div>
                @php
                $color = match($cuti->status) {
                    'disetujui' => 'bg-green-100 text-green-700',
                    'pending'   => 'bg-yellow-100 text-yellow-700',
                    'ditolak'   => 'bg-red-100 text-red-700',
                    default     => 'bg-gray-100 text-gray-600',
                };
                @endphp
                <span class="px-3 py-1 rounded-full text-sm font-medium {{ $color }}">
                    {{ ucfirst($cuti->status) }}
                </span>
            </div>

            <div class="border-t dark:border-gray-700 pt-4">
                <p class="text-sm text-gray-500">Tanggal Cuti</p>
                <p class="font-medium">
                    {{ $cuti->tanggal_mulai->format('d/m/Y') }}
                    @if($cuti->tanggal_mulai != $cuti->tanggal_selesai)
                        s/d {{ $cuti->tanggal_selesai->format('d/m/Y') }}
                    @endif
                </p>
            </div>

            <div class="border-t dark:border-gray-700 pt-4">
                <p class="text-sm text-gray-500">Alasan</p>
                <p class="font-medium">{{ $cuti->alasan }}</p>
            </div>

            @if($cuti->catatan_admin)
            <div class="border-t dark:border-gray-700 pt-4">
                <p class="text-sm text-gray-500">Catatan Admin</p>
                <p class="font-medium">{{ $cuti->catatan_admin }}</p>
            </div>
            @endif

            @if($cuti->approved_by)
            <div class="border-t dark:border-gray-700 pt-4">
                <p class="text-sm text-gray-500">Disetujui/Ditolak oleh</p>
                <p class="font-medium">{{ $cuti->approvedBy->name }}</p>
                @if($cuti->approved_at)
                <p class="text-sm text-gray-400">{{ $cuti->approved_at->format('d/m/Y H:i') }}</p>
                @endif
            </div>
            @endif

            <div class="border-t dark:border-gray-700 pt-4">
                <p class="text-sm text-gray-500">Diajukan pada</p>
                <p class="font-medium">{{ $cuti->created_at->format('d/m/Y H:i') }}</p>
            </div>
        </div>
    </div>
</div>
@endsection
