@extends('layouts.app')
@section('title', 'Data Absensi')

@section('content')
<div class="mb-6">
    <h2 class="text-2xl font-bold">Data Absensi Semua User</h2>
</div>

<form method="GET" class="bg-white dark:bg-gray-800 rounded-lg shadow p-4 mb-6 flex flex-wrap gap-3">
    <select name="user_id" class="px-4 py-3 rounded-xl border border-slate-700 bg-slate-800 text-slate-100 focus:border-blue-500 focus:ring-2 focus:ring-blue-500/40 outline-none transition duration-200 ease-in-out">
        <option value="">Semua User</option>
        @foreach($users as $u)
            <option value="{{ $u->id }}" {{ request('user_id') == $u->id ? 'selected' : '' }}>{{ $u->name }}</option>
        @endforeach
    </select>
    <select name="status" class="px-4 py-3 rounded-xl border border-slate-700 bg-slate-800 text-slate-100 focus:border-blue-500 focus:ring-2 focus:ring-blue-500/40 outline-none transition duration-200 ease-in-out">
        <option value="">Semua Status</option>
        <option value="dikonfirmasi" {{ request('status')==='dikonfirmasi' ? 'selected':'' }}>Dikonfirmasi</option>
        <option value="pending" {{ request('status')==='pending' ? 'selected':'' }}>Pending</option>
        <option value="tidak_dikonfirmasi" {{ request('status')==='tidak_dikonfirmasi' ? 'selected':'' }}>Tidak Dikonfirmasi</option>
    </select>
    <input type="date" name="tanggal_dari" value="{{ request('tanggal_dari') }}"
           class="px-4 py-3 rounded-xl border border-slate-700 bg-slate-800 text-slate-100 focus:border-blue-500 focus:ring-2 focus:ring-blue-500/40 outline-none transition duration-200 ease-in-out">
    <input type="date" name="tanggal_sampai" value="{{ request('tanggal_sampai') }}"
           class="px-4 py-3 rounded-xl border border-slate-700 bg-slate-800 text-slate-100 focus:border-blue-500 focus:ring-2 focus:ring-blue-500/40 outline-none transition duration-200 ease-in-out">
    <button type="submit" class="px-4 py-2 bg-gray-600 text-white rounded-lg text-sm hover:bg-gray-700">Filter</button>
    <a href="{{ route('admin.absens.index') }}" class="px-4 py-2 bg-gray-200 text-gray-700 rounded-lg text-sm hover:bg-gray-300">Reset</a>
</form>

<div class="bg-white dark:bg-gray-800 rounded-lg shadow overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead class="bg-gray-100 dark:bg-gray-700">
                <tr>
                    <th class="px-4 py-3 text-left">Nama</th>
                    <th class="px-4 py-3 text-left">Tanggal</th>
                    <th class="px-4 py-3 text-left">Jam</th>
                    <th class="px-4 py-3 text-left">Lokasi</th>
                    <th class="px-4 py-3 text-left">Status</th>
                    <th class="px-4 py-3 text-left">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($absens as $absen)
                <tr class="border-b dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-700">
                    <td class="px-4 py-3 font-medium">{{ $absen->user?->name ?? 'N/A' }}</td>
                    <td class="px-4 py-3">{{ $absen->tanggal->format('d/m/Y') }}</td>
                    <td class="px-4 py-3">{{ $absen->jam }}</td>
                    <td class="px-4 py-3 text-gray-500 max-w-xs truncate">{{ $absen->lokasi ?? '-' }}</td>
                    <td class="px-4 py-3">
                        @php
                        $color = match($absen->status) {
                            'dikonfirmasi'       => 'bg-green-100 text-green-700',
                            'pending'            => 'bg-yellow-100 text-yellow-700',
                            'tidak_dikonfirmasi' => 'bg-red-100 text-red-700',
                            default              => 'bg-gray-100 text-gray-700',
                        };
                        @endphp
                        <span class="px-2 py-1 rounded text-xs font-medium {{ $color }}">
                            {{ ucfirst(str_replace('_', ' ', $absen->status)) }}
                        </span>
                    </td>
                    <td class="px-4 py-3">
                        <a href="{{ route('admin.absens.show', $absen) }}"
                           class="px-2 py-1 bg-blue-100 text-blue-700 rounded text-xs hover:bg-blue-200">Detail</a>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="px-4 py-8 text-center text-gray-400">Tidak ada data absensi</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($absens->hasPages())
    <div class="p-4 border-t dark:border-gray-700">
        {{ $absens->withQueryString()->links() }}
    </div>
    @endif
</div>
@endsection
