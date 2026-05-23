@extends('layouts.app')
@section('title', 'Ajukan Cuti')

@section('content')
<div class="max-w-lg mx-auto">
    <div class="mb-6">
        <a href="{{ route('user.dashboard') }}" class="text-blue-600 hover:underline text-sm">← Kembali ke Dashboard</a>
        <h2 class="text-2xl font-bold mt-2">Ajukan Permohonan Cuti</h2>
        <p class="text-gray-500 text-sm">Isi formulir di bawah ini untuk mengajukan cuti</p>
    </div>

    <div class="bg-slate-900/95 border border-slate-800 rounded-3xl shadow-xl shadow-slate-950/40 p-6">
        <form method="POST" action="{{ route('user.cutis.store') }}" class="space-y-5">
            @csrf

            {{-- TANGGAL MULAI --}}
            <div>
                <label class="block text-sm font-medium text-slate-200 mb-1">
                    📅 Tanggal Mulai
                </label>
                <input type="date" name="tanggal_mulai" value="{{ old('tanggal_mulai') }}"
                       class="w-full px-4 py-3 rounded-xl border border-slate-700 bg-slate-800 text-slate-100 placeholder:text-slate-500 focus:border-blue-500 focus:ring-2 focus:ring-blue-500/40 outline-none transition duration-200 ease-in-out text-sm"
                       required>
            </div>

            {{-- TANGGAL SELESAI --}}
            <div>
                <label class="block text-sm font-medium text-slate-200 mb-1">
                    📅 Tanggal Selesai
                </label>
                <input type="date" name="tanggal_selesai" value="{{ old('tanggal_selesai') }}"
                       class="w-full px-4 py-3 rounded-xl border border-slate-700 bg-slate-800 text-slate-100 placeholder:text-slate-500 focus:border-blue-500 focus:ring-2 focus:ring-blue-500/40 outline-none transition duration-200 ease-in-out text-sm"
                       required>
            </div>

            {{-- JENIS CUTI --}}
            <div>
                <label class="block text-sm font-medium text-slate-200 mb-1">
                    🏷️ Jenis Cuti
                </label>
                <select name="jenis_cuti"
                        class="w-full px-4 py-3 rounded-xl border border-slate-700 bg-slate-800 text-slate-100 focus:border-blue-500 focus:ring-2 focus:ring-blue-500/40 outline-none transition duration-200 ease-in-out text-sm"
                        required>
                    <option value="" class="text-slate-400">Pilih Jenis Cuti</option>
                    <option value="tahunan" class="bg-slate-900 text-slate-100" {{ old('jenis_cuti') == 'tahunan' ? 'selected' : '' }}>Cuti Tahunan</option>
                    <option value="sakit" class="bg-slate-900 text-slate-100" {{ old('jenis_cuti') == 'sakit' ? 'selected' : '' }}>Cuti Sakit</option>
                    <option value="melahirkan" class="bg-slate-900 text-slate-100" {{ old('jenis_cuti') == 'melahirkan' ? 'selected' : '' }}>Cuti Melahirkan</option>
                    <option value="keperluan_keluarga" class="bg-slate-900 text-slate-100" {{ old('jenis_cuti') == 'keperluan_keluarga' ? 'selected' : '' }}>Cuti Keperluan Keluarga</option>
                    <option value="lainnya" class="bg-slate-900 text-slate-100" {{ old('jenis_cuti') == 'lainnya' ? 'selected' : '' }}>Lainnya</option>
                </select>
            </div>

            {{-- ALASAN --}}
            <div>
                <label class="block text-sm font-medium text-slate-200 mb-1">
                    📝 Alasan Cuti
                </label>
                <textarea name="alasan" rows="4"
                          class="w-full px-4 py-3 rounded-xl border border-slate-700 bg-slate-800 text-slate-100 placeholder:text-slate-500 focus:border-blue-500 focus:ring-2 focus:ring-blue-500/40 outline-none transition duration-200 ease-in-out text-sm resize-none"
                          placeholder="Jelaskan alasan pengajuan cuti..." required>{{ old('alasan') }}</textarea>
            </div>

            <button type="submit"
                    class="w-full py-3 bg-sky-600 text-white rounded-xl hover:bg-sky-500 transition duration-200 font-semibold text-sm shadow-sm shadow-slate-950/30">
                📤 Ajukan Permohonan
            </button>
        </form>
    </div>
</div>
@endsection
