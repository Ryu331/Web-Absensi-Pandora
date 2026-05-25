@extends('layouts.app')
@section('title', 'Detail Absensi Saya')

@section('content')
<div class="mb-6">
    <a href="{{ route('user.absens.index') }}" class="text-blue-600 hover:underline text-sm">Kembali ke Riwayat Absensi</a>
    <h2 class="text-2xl font-bold mt-2">Detail Absensi Saya</h2>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    <div class="lg:col-span-2 bg-white dark:bg-gray-800 rounded-lg shadow p-5">
        <h3 class="font-semibold mb-4">Informasi Absensi</h3>
        <dl class="grid grid-cols-1 sm:grid-cols-2 gap-4 text-sm">
            <div>
                <dt class="text-gray-500">Tanggal</dt>
                <dd class="font-medium">{{ $absen->tanggal?->format('d/m/Y') }}</dd>
            </div>
            <div>
                <dt class="text-gray-500">Jam Masuk</dt>
                <dd class="font-medium">{{ $absen->jam }}</dd>
            </div>
            <div>
                <dt class="text-gray-500">Jam Pulang</dt>
                <dd class="font-medium">{{ $absen->jam_keluar ?? '-' }}</dd>
            </div>
            <div>
                <dt class="text-gray-500">Status</dt>
                <dd class="font-medium">{{ ucfirst(str_replace('_', ' ', $absen->status)) }}</dd>
            </div>
            @include('partials.absen-lokasi', ['absen' => $absen])
        </dl>
    </div>

    <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-5">
        <h3 class="font-semibold mb-4">Foto Wajah</h3>
        @if($absen->foto_wajah)
            <img src="{{ Storage::url($absen->foto_wajah) }}" alt="Foto wajah absensi" class="w-full rounded-lg object-cover">
        @else
            <div class="aspect-square rounded-lg bg-gray-100 dark:bg-gray-700 flex items-center justify-center text-gray-400 text-sm">
                Tidak ada foto
            </div>
        @endif
    </div>
</div>

<div class="mt-6 bg-white dark:bg-gray-800 rounded-lg shadow overflow-hidden">
    <div class="p-5 border-b dark:border-gray-700">
        <h3 class="font-semibold">Status Request</h3>
    </div>
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead class="bg-gray-100 dark:bg-gray-700">
                <tr>
                    <th class="px-4 py-3 text-left">Jenis</th>
                    <th class="px-4 py-3 text-left">Status</th>
                    <th class="px-4 py-3 text-left">Catatan</th>
                    <th class="px-4 py-3 text-left">Validator</th>
                    <th class="px-4 py-3 text-left">Waktu</th>
                </tr>
            </thead>
            <tbody>
                @forelse($absen->absenRequests as $request)
                <tr class="border-b dark:border-gray-700">
                    <td class="px-4 py-3">{{ $request->request_type === 'keluar' ? 'Pulang Cepat' : 'Masuk' }}</td>
                    <td class="px-4 py-3">{{ ucfirst(str_replace('_', ' ', $request->status)) }}</td>
                    <td class="px-4 py-3">{{ $request->catatan ?? '-' }}</td>
                    <td class="px-4 py-3">{{ $request->validator?->name ?? '-' }}</td>
                    <td class="px-4 py-3">{{ $request->validated_at?->format('d/m/Y H:i') ?? '-' }}</td>
                </tr>
                @empty
                <tr>
                    <td colspan="4" class="px-4 py-8 text-center text-gray-400">Belum ada request</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
