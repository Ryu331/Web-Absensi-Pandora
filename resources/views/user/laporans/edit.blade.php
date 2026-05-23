@extends('layouts.app')
@section('title', 'Edit Laporan')

@section('content')
<div class="max-w-2xl mx-auto">
    <div class="mb-6">
        <a href="{{ route('user.laporans.index') }}" class="inline-flex items-center text-blue-600 hover:text-blue-800 text-sm font-medium">
            ← Kembali ke Laporan Saya
        </a>
        <h2 class="text-2xl font-bold mt-2 text-gray-800 dark:text-gray-100">Edit Laporan</h2>
        <p class="text-sm text-gray-500 mt-1">Perbarui informasi laporan yang sudah dikirim.</p>
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
        <form method="POST" action="{{ route('user.laporans.update', $laporan) }}" class="space-y-5">
            @csrf
            @method('PUT')

            {{-- Judul --}}
            <div>
                <label for="judul" class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-1">
                    Judul Laporan <span class="text-gray-400 font-normal">(opsional)</span>
                </label>
                <input
                    type="text"
                    id="judul"
                    name="judul"
                    value="{{ old('judul', $laporan->judul) }}"
                    class="w-full px-4 py-2.5 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-sm bg-white dark:bg-gray-700 dark:text-gray-100 @error('judul') border-red-500 @enderror"
                    placeholder="Judul laporan..."
                >
                @error('judul')
                    <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                @enderror
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
                    value="{{ old('link', $laporan->link) }}"
                    required
                    class="w-full px-4 py-2.5 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-sm bg-white dark:bg-gray-700 dark:text-gray-100 @error('link') border-red-500 @enderror"
                    placeholder="https://docs.google.com/..."
                >
                <p class="mt-1 text-xs text-gray-500">💡 Masukkan URL lengkap yang valid (diawali https://)</p>
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
                    rows="5"
                    class="w-full px-4 py-2.5 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-sm bg-white dark:bg-gray-700 dark:text-gray-100 resize-none @error('deskripsi') border-red-500 @enderror"
                    placeholder="Deskripsi laporan..."
                >{{ old('deskripsi', $laporan->deskripsi) }}</textarea>
                @error('deskripsi')
                    <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                @enderror
            </div>

            {{-- Info laporan asli --}}
            <div class="bg-gray-50 dark:bg-gray-700/50 border border-gray-200 dark:border-gray-600 rounded-lg p-3 text-xs text-gray-500 dark:text-gray-400">
                <p>📅 Dibuat pada: <span class="font-medium">{{ $laporan->created_at->format('d M Y, H:i') }}</span></p>
                @if($laporan->updated_at != $laporan->created_at)
                <p class="mt-1">✏️ Terakhir diperbarui: <span class="font-medium">{{ $laporan->updated_at->format('d M Y, H:i') }}</span></p>
                @endif
            </div>

            {{-- Tombol aksi --}}
            <div class="flex gap-3 pt-1">
                <button
                    type="submit"
                    class="flex-1 py-2.5 bg-blue-600 hover:bg-blue-700 text-white rounded-lg transition font-semibold text-sm shadow-sm"
                >
                    💾 Simpan Perubahan
                </button>
                <a
                    href="{{ route('user.laporans.index') }}"
                    class="flex-1 py-2.5 bg-gray-100 hover:bg-gray-200 text-gray-700 dark:bg-gray-700 dark:text-gray-200 dark:hover:bg-gray-600 rounded-lg transition font-semibold text-sm text-center"
                >
                    Batal
                </a>
            </div>
        </form>
    </div>
</div>
@endsection
