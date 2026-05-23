@extends('layouts.app')
@section('title', 'Permintaan Absensi')

@section('content')
<div class="mb-6">
    <h2 class="text-2xl font-bold">Permintaan Konfirmasi Absensi</h2>
    <p class="text-gray-500 text-sm mt-1">Konfirmasi atau tolak permintaan absensi dari user</p>
</div>

<form method="GET" class="bg-white dark:bg-gray-800 rounded-lg shadow p-4 mb-6 flex gap-3">
    <select name="status" class="px-4 py-3 rounded-xl border border-slate-700 bg-slate-800 text-slate-100 focus:border-blue-500 focus:ring-2 focus:ring-blue-500/40 outline-none transition duration-200 ease-in-out">
        <option value="">Semua Status</option>
        <option value="pending" {{ request('status')==='pending' ? 'selected':'' }}>Pending</option>
        <option value="dikonfirmasi" {{ request('status')==='dikonfirmasi' ? 'selected':'' }}>Dikonfirmasi</option>
        <option value="tidak_dikonfirmasi" {{ request('status')==='tidak_dikonfirmasi' ? 'selected':'' }}>Tidak Dikonfirmasi</option>
    </select>
    <button type="submit" class="px-4 py-2 bg-gray-600 text-white rounded-lg text-sm hover:bg-gray-700">Filter</button>
    <a href="{{ route('admin.absen-requests.index') }}" class="px-4 py-2 bg-gray-200 text-gray-700 rounded-lg text-sm hover:bg-gray-300">Reset</a>
</form>

<div class="space-y-4">
    @forelse($absenRequests as $req)
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-5 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div class="flex-1">
            <div class="flex items-center gap-3 mb-2">
                <p class="font-semibold">{{ $req->user?->name ?? 'N/A' }}</p>
                @php
                $color = match($req->status) {
                    'pending'            => 'bg-yellow-100 text-yellow-700',
                    'dikonfirmasi'       => 'bg-green-100 text-green-700',
                    'tidak_dikonfirmasi' => 'bg-red-100 text-red-700',
                    default              => 'bg-gray-100 text-gray-600',
                };
                $typeLabel = $req->request_type === 'keluar' ? 'Pulang Cepat' : 'Masuk';
                @endphp
                <span class="px-2 py-0.5 rounded text-xs font-medium bg-slate-100 text-slate-700">
                    {{ $typeLabel }}
                </span>
                <span class="px-2 py-0.5 rounded text-xs font-medium {{ $color }}">
                    {{ ucfirst(str_replace('_',' ',$req->status)) }}
                </span>
            </div>
            <p class="text-sm text-gray-500">
                📅 {{ $req->absen?->tanggal?->format('d F Y') ?? '-' }}
                · ⏰ {{ $req->absen?->jam ?? '-' }}
            </p>
            @if($req->catatan)
            <p class="text-sm text-gray-600 mt-1">📝 {{ $req->catatan }}</p>
            @endif
            @if($req->validator)
            <p class="text-xs text-gray-400 mt-1">
                Diproses oleh {{ $req->validator->name }} pada {{ $req->validated_at?->format('d/m/Y H:i') }}
            </p>
            @endif
        </div>

        @if($req->status === 'pending')
        <div class="flex gap-2 shrink-0">
            <form method="POST" action="{{ route('admin.absen-requests.terima', $req) }}">
                @csrf
                <button type="submit" class="px-4 py-2 bg-green-600 text-white rounded-lg text-sm hover:bg-green-700 font-medium">
                    ✅ {{ $req->request_type === 'keluar' ? 'Terima Pulang Cepat' : 'Terima' }}
                </button>
            </form>
            <form method="POST" action="{{ route('admin.absen-requests.tolak', $req) }}">
                @csrf
                <button type="submit" class="px-4 py-2 bg-red-600 text-white rounded-lg text-sm hover:bg-red-700 font-medium">
                    ❌ {{ $req->request_type === 'keluar' ? 'Tolak Pulang Cepat' : 'Tolak' }}
                </button>
            </form>
        </div>
        @endif
    </div>
    @empty
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-12 text-center text-gray-400">
        Tidak ada permintaan absensi
    </div>
    @endforelse
</div>

@if($absenRequests->hasPages())
<div class="mt-6">
    {{ $absenRequests->withQueryString()->links() }}
</div>
@endif
@endsection
