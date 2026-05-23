@extends('layouts.app')
@section('title', 'Kelola User')

@section('content')
<div class="flex justify-between items-center mb-6">
    <h2 class="text-2xl font-bold">Kelola User</h2>
    <a href="{{ route('admin.users.create') }}"
       class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition text-sm font-medium">
        ➕ Tambah User
    </a>
</div>

{{-- FILTER --}}
<form method="GET" class="bg-white dark:bg-gray-800 rounded-lg shadow p-4 mb-6 flex flex-wrap gap-3">
    <input type="text" name="search" value="{{ request('search') }}"
           placeholder="Cari nama / email..."
           class="flex-1 min-w-48 px-4 py-3 rounded-xl border border-slate-700 bg-slate-800 text-slate-100 placeholder:text-slate-400 focus:border-blue-500 focus:ring-2 focus:ring-blue-500/40 outline-none transition duration-200 ease-in-out">
    <select name="status" class="px-4 py-3 rounded-xl border border-slate-700 bg-slate-800 text-slate-100 focus:border-blue-500 focus:ring-2 focus:ring-blue-500/40 outline-none transition duration-200 ease-in-out">
        <option value="">Semua Status</option>
        <option value="bekerja" {{ request('status') === 'bekerja' ? 'selected' : '' }}>Bekerja</option>
        <option value="tidak_bekerja" {{ request('status') === 'tidak_bekerja' ? 'selected' : '' }}>Tidak Bekerja</option>
    </select>
    <button type="submit" class="px-4 py-2 bg-gray-600 text-white rounded-lg text-sm hover:bg-gray-700">Filter</button>
    <a href="{{ route('admin.users.index') }}" class="px-4 py-2 bg-gray-200 text-gray-700 rounded-lg text-sm hover:bg-gray-300">Reset</a>
</form>

<div class="bg-white dark:bg-gray-800 rounded-lg shadow overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead class="bg-gray-100 dark:bg-gray-700">
                <tr>
                    <th class="px-4 py-3 text-left">Nama</th>
                    <th class="px-4 py-3 text-left">Email</th>
                    <th class="px-4 py-3 text-left">Status</th>
                    <th class="px-4 py-3 text-left">Total Absen</th>
                    <th class="px-4 py-3 text-left">Terdaftar</th>
                    <th class="px-4 py-3 text-left">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($users as $user)
                <tr class="border-b dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-700">
                    <td class="px-4 py-3 font-medium">{{ $user->name }}</td>
                    <td class="px-4 py-3 text-gray-500">{{ $user->email }}</td>
                    <td class="px-4 py-3">
                        <span class="px-2 py-1 rounded text-xs font-medium {{ $user->status === 'bekerja' ? 'bg-green-100 text-green-700' : 'bg-gray-100 text-gray-600' }}">
                            {{ $user->status === 'bekerja' ? 'Bekerja' : 'Tidak Bekerja' }}
                        </span>
                    </td>
                    <td class="px-4 py-3">{{ $user->absens_count }}</td>
                    <td class="px-4 py-3 text-gray-500">{{ $user->created_at->format('d/m/Y') }}</td>
                    <td class="px-4 py-3">
                        <div class="flex gap-2">
                            <a href="{{ route('admin.users.show', $user) }}"
                               class="px-2 py-1 bg-blue-100 text-blue-700 rounded text-xs hover:bg-blue-200">Detail</a>
                            <a href="{{ route('admin.users.edit', $user) }}"
                               class="px-2 py-1 bg-yellow-100 text-yellow-700 rounded text-xs hover:bg-yellow-200">Edit</a>
                            <form method="POST" action="{{ route('admin.users.toggle-status', $user) }}">
                                @csrf @method('PATCH')
                                <button type="submit"
                                        class="px-2 py-1 {{ $user->status === 'bekerja' ? 'bg-orange-100 text-orange-700 hover:bg-orange-200' : 'bg-teal-100 text-teal-700 hover:bg-teal-200' }} rounded text-xs">
                                    {{ $user->status === 'bekerja' ? 'Nonaktifkan' : 'Aktifkan' }}
                                </button>
                            </form>
                            <form method="POST" action="{{ route('admin.users.destroy', $user) }}"
                                  onsubmit="return confirm('Yakin hapus user {{ $user->name }}? Semua data absensinya akan terhapus.')">
                                @csrf @method('DELETE')
                                <button type="submit" class="px-2 py-1 bg-red-100 text-red-700 rounded text-xs hover:bg-red-200">Hapus</button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="px-4 py-8 text-center text-gray-400">Tidak ada user ditemukan</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($users->hasPages())
    <div class="p-4 border-t dark:border-gray-700">
        {{ $users->withQueryString()->links() }}
    </div>
    @endif
</div>
@endsection
