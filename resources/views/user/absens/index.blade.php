@extends('layouts.app')
@section('title', 'Riwayat Absensi')

@section('content')
<div class="flex justify-between items-center mb-6">
    <h2 class="text-2xl font-bold">Riwayat Absensi Saya</h2>
    <a href="{{ route('user.absens.create') }}"
       class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition text-sm font-medium">
        ✅ Absen Sekarang
    </a>
</div>

<div class="bg-white dark:bg-gray-800 rounded-lg shadow overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead class="bg-gray-100 dark:bg-gray-700">
                <tr>
                    <th class="px-4 py-3 text-left">Tanggal</th>
                    <th class="px-4 py-3 text-left">Jam Masuk</th>
                    <th class="px-4 py-3 text-left">Jam Pulang</th>
                    <th class="px-4 py-3 text-left">Lokasi</th>
                    <th class="px-4 py-3 text-left">Foto</th>
                    <th class="px-4 py-3 text-left">Status</th>
                    <th class="px-4 py-3 text-left">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($absens as $absen)
                <tr class="border-b dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-700">
                    <td class="px-4 py-3 font-medium">{{ $absen->tanggal->format('d/m/Y') }}</td>
                    <td class="px-4 py-3">{{ $absen->jam }}</td>
                    <td class="px-4 py-3">{{ $absen->jam_keluar ?? '-' }}</td>
                    <td class="px-4 py-3 text-gray-500 max-w-xs truncate">{{ $absen->lokasi ?? '-' }}</td>
                    <td class="px-4 py-3">
                        @if($absen->foto_wajah)
                            <img src="{{ Storage::url($absen->foto_wajah) }}" alt="foto"
                                 class="w-10 h-10 rounded-full object-cover">
                        @else
                            <span class="text-gray-400">-</span>
                        @endif
                    </td>
                    <td class="px-4 py-3">
                        @php
                        $color = match($absen->status) {
                            'dikonfirmasi'       => 'bg-green-100 text-green-700',
                            'pending'            => 'bg-yellow-100 text-yellow-700',
                            'tidak_dikonfirmasi' => 'bg-red-100 text-red-700',
                            default              => 'bg-gray-100 text-gray-600',
                        };
                        @endphp
                        <span class="px-2 py-1 rounded text-xs font-medium {{ $color }}">
                            {{ ucfirst(str_replace('_',' ',$absen->status)) }}
                        </span>
                    </td>
                    <td class="px-4 py-3">
                        <a href="{{ route('user.absens.show', $absen) }}"
                           class="px-2 py-1 bg-blue-100 text-blue-700 rounded text-xs hover:bg-blue-200">Detail</a>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="px-4 py-8 text-center text-gray-400">
                        Belum ada riwayat absensi.
                        <a href="{{ route('user.absens.create') }}" class="text-blue-600 hover:underline">Absen sekarang →</a>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($absens->hasPages())
    <div class="p-4 border-t dark:border-gray-700">
        {{ $absens->links() }}
    </div>
    @endif
</div>
@endsection
