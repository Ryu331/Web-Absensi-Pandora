@extends('layouts.app')
@section('title', 'Tambah Laporan')

@section('content')
<div class="max-w-2xl mx-auto">
    <div class="mb-6">
        <a href="{{ route('admin.laporans.index') }}" class="inline-flex items-center text-blue-600 hover:text-blue-800 text-sm font-medium">
            ← Kembali ke Laporan
        </a>
        <h2 class="text-2xl font-bold mt-2 text-gray-800 dark:text-gray-100">Tambah Laporan</h2>
        <p class="text-sm text-gray-500 mt-1">Admin dapat menambahkan laporan atas nama user tertentu.</p>
    </div>

    @if($errors->any())
        <div class="mb-4 p-4 bg-red-50 border border-red-300 rounded-lg text-sm text-red-700">
            <p class="font-semibold mb-1">Terdapat kesalahan pada form:</p>
            <ul class="list-disc list-inside space-y-1">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-md p-6 border border-gray-100 dark:border-gray-700">
        <form method="POST" action="{{ route('admin.laporans.store') }}" class="space-y-5">
            @csrf

            {{-- Pilih User --}}
            <div>
                <label for="user_id" class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-1">
                    User <span class="text-red-500">*</span>
                </label>
                <select
                    id="user_id"
                    name="user_id"
                    required
                    class="w-full px-4 py-3 rounded-xl border border-slate-700 bg-slate-800 text-slate-100 text-sm placeholder:text-slate-400 focus:border-blue-500 focus:ring-2 focus:ring-blue-500/40 outline-none transition duration-200 ease-in-out @error('user_id') border-red-500 @enderror"
                >
                    <option value="">— Pilih User —</option>
                    @foreach($users as $u)
                        <option value="{{ $u->id }}" {{ old('user_id') == $u->id ? 'selected' : '' }}>
                            {{ $u->name }} ({{ $u->email }})
                        </option>
                    @endforeach
                </select>
                @error('user_id')
                    <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                @enderror
            </div>

            {{-- Judul --}}
            <div>
                <label for="judul" class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-1">
                    Judul Laporan <span class="text-gray-400 font-normal">(opsional)</span>
                </label>
                <input
                    type="text"
                    id="judul"
                    name="judul"
                    value="{{ old('judul') }}"
                    class="w-full px-4 py-3 rounded-xl border border-slate-700 bg-slate-800 text-slate-100 text-sm placeholder:text-slate-400 focus:border-blue-500 focus:ring-2 focus:ring-blue-500/40 outline-none transition duration-200 ease-in-out"
                    placeholder="Judul laporan..."
                >
            </div>

            {{-- Link --}}
            <div>
                <label for="link" class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-1">
                    Link Laporan <span class="text-red-500">*</span>
                </label>
                <input
                    type="url"
                    id="link"
                    name="link"
                    value="{{ old('link') }}"
                    required
                    class="w-full px-4 py-3 rounded-xl border border-slate-700 bg-slate-800 text-slate-100 text-sm placeholder:text-slate-400 focus:border-blue-500 focus:ring-2 focus:ring-blue-500/40 outline-none transition duration-200 ease-in-out @error('link') border-red-500 @enderror"
                    placeholder="https://..."
                >
                @error('link')
                    <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                @enderror
            </div>

            {{-- Deskripsi --}}
            <div>
                <label for="deskripsi" class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-1">
                    Deskripsi <span class="text-gray-400 font-normal">(opsional)</span>
                </label>
                <textarea
                    id="deskripsi"
                    name="deskripsi"
                    rows="4"
                    class="w-full px-4 py-2.5 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-purple-500 text-sm bg-white dark:bg-gray-700 dark:text-gray-100 resize-none"
                    placeholder="Deskripsi laporan..."
                >{{ old('deskripsi') }}</textarea>
            </div>

            {{-- Tombol aksi --}}
            <div class="flex gap-3 pt-1">
                <button
                    type="submit"
                    class="flex-1 py-2.5 bg-purple-600 hover:bg-purple-700 text-white rounded-lg transition font-semibold text-sm shadow-sm"
                >
                    💾 Simpan Laporan
                </button>
                <a
                    href="{{ route('admin.laporans.index') }}"
                    class="flex-1 py-2.5 bg-gray-100 hover:bg-gray-200 text-gray-700 dark:bg-gray-700 dark:text-gray-200 dark:hover:bg-gray-600 rounded-lg transition font-semibold text-sm text-center"
                >
                    Batal
                </a>
            </div>
        </form>
    </div>
</div>
@endsection
