@extends('layouts.app')
@section('title', 'Kirim Laporan')

@section('content')
<div class="max-w-2xl mx-auto">
    <div class="mb-6">
        <a href="{{ route('user.laporans.index') }}" class="inline-flex items-center text-blue-600 hover:text-blue-800 text-sm font-medium">
            ← Kembali ke Laporan Saya
        </a>
        <h2 class="text-2xl font-bold mt-2 text-gray-800 dark:text-gray-100">Kirim Laporan Baru</h2>
        <p class="text-sm text-gray-500 mt-1">Isi form berikut untuk mengirimkan laporan kepada admin.</p>
    </div>

    {{-- Error Global --}}
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
        <form method="POST" action="{{ route('user.laporans.store') }}" class="space-y-5">
            @csrf

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
                    class="w-full px-4 py-2.5 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-purple-500 text-sm bg-white dark:bg-gray-700 dark:text-gray-100 @error('judul') border-red-500 @enderror"
                    placeholder="Contoh: Laporan Mingguan Minggu ke-3 Mei"
                >
                @error('judul')
                    <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                @enderror
            </div>

            {{-- Link Laporan --}}
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
                    class="w-full px-4 py-2.5 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-purple-500 text-sm bg-white dark:bg-gray-700 dark:text-gray-100 @error('link') border-red-500 @enderror"
                    placeholder="https://docs.google.com/document/..."
                >
                <p class="mt-1 text-xs text-gray-500">
                    💡 Masukkan link Google Docs, Google Drive, atau platform lainnya. Pastikan link dapat diakses oleh admin.
                </p>
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
                    class="w-full px-4 py-2.5 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-purple-500 text-sm bg-white dark:bg-gray-700 dark:text-gray-100 resize-none @error('deskripsi') border-red-500 @enderror"
                    placeholder="Tuliskan ringkasan isi laporan, pencapaian, atau informasi tambahan yang ingin disampaikan kepada admin..."
                >{{ old('deskripsi') }}</textarea>
                @error('deskripsi')
                    <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                @enderror
            </div>

            {{-- Info pengirim --}}
            <div class="bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-700 rounded-lg p-3 text-sm text-blue-700 dark:text-blue-300">
                <span class="font-medium">Dikirim sebagai:</span> {{ Auth::user()->name }}
                &nbsp;·&nbsp;
                <span class="text-xs text-blue-500">{{ now(config('app.timezone'))->format('d M Y, H:i') }} WIB</span>
            </div>

            {{-- Tombol aksi --}}
            <div class="flex gap-3 pt-1">
                <button
                    type="submit"
                    class="flex-1 py-2.5 bg-purple-600 hover:bg-purple-700 text-white rounded-lg transition font-semibold text-sm shadow-sm"
                >
                    📤 Kirim Laporan ke Admin
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
