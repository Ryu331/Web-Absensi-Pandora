@extends('layouts.app')
@section('title', 'Riwayat Cuti')

@section('content')
<div class="flex justify-between items-center mb-6">
    <h2 class="text-2xl font-bold">Riwayat Cuti Saya</h2>
    <a href="{{ route('user.cutis.create') }}"
       class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition text-sm font-medium">
        📤 Ajukan Cuti
    </a>
</div>

<div class="bg-white dark:bg-gray-800 rounded-lg shadow overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead class="bg-gray-100 dark:bg-gray-700">
                <tr>
                    <th class="px-4 py-3 text-left">Tanggal</th>
                    <th class="px-4 py-3 text-left">Jenis Cuti</th>
                    <th class="px-4 py-3 text-left">Alasan</th>
                    <th class="px-4 py-3 text-left">Status</th>
                    <th class="px-4 py-3 text-left">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($cutis as $cuti)
                <tr class="border-b dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-700">
                    <td class="px-4 py-3 font-medium">
                        {{ $cuti->tanggal_mulai->format('d/m/Y') }}
                        @if($cuti->tanggal_mulai != $cuti->tanggal_selesai)
                            s/d {{ $cuti->tanggal_selesai->format('d/m/Y') }}
                        @endif
                    </td>
                    <td class="px-4 py-3">{{ ucfirst(str_replace('_', ' ', $cuti->jenis_cuti)) }}</td>
                    <td class="px-4 py-3 text-gray-500 max-w-xs truncate">{{ $cuti->alasan }}</td>
                    <td class="px-4 py-3">
                        @php
                        $color = match($cuti->status) {
                            'disetujui' => 'bg-green-100 text-green-700',
                            'pending'   => 'bg-yellow-100 text-yellow-700',
                            'ditolak'   => 'bg-red-100 text-red-700',
                            default     => 'bg-gray-100 text-gray-600',
                        };
                        @endphp
                        <span class="px-2 py-1 rounded text-xs font-medium {{ $color }}">
                            {{ ucfirst($cuti->status) }}
                        </span>
                    </td>
                    <td class="px-4 py-3">
                        <a href="{{ route('user.cutis.show', $cuti) }}"
                           class="px-2 py-1 bg-blue-100 text-blue-700 rounded text-xs hover:bg-blue-200">Detail</a>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="px-4 py-8 text-center text-gray-400">
                        Belum ada riwayat cuti.
                        <a href="{{ route('user.cutis.create') }}" class="text-blue-600 hover:underline">Ajukan cuti sekarang →</a>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($cutis->hasPages())
    <div class="p-4 border-t dark:border-gray-700">
        {{ $cutis->links() }}
    </div>
    @endif
</div>
@endsection
