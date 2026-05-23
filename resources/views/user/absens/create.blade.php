@extends('layouts.app')
@section('title', 'Input Absensi')

@section('content')
<div class="max-w-lg mx-auto">
    <div class="mb-6">
        <a href="{{ route('user.dashboard') }}" class="text-blue-600 hover:underline text-sm">← Kembali ke Dashboard</a>
        <h2 class="text-2xl font-bold mt-2">Input Absensi Harian</h2>
        <p class="text-gray-500 text-sm">{{ now(config('app.timezone'))->isoFormat('dddd, D MMMM Y') }}</p>
    </div>

    @if($action === 'completed')
    <div class="bg-emerald-50 border border-emerald-200 rounded-xl p-6 text-center">
        <div class="text-4xl mb-3">🎉</div>
        <h3 class="text-lg font-bold text-emerald-800">Absensi hari ini selesai</h3>
        <p class="text-emerald-700 mt-2 text-sm">Jam masuk: {{ $todayAbsen->jam }} · Jam pulang: {{ $todayAbsen->jam_keluar }}</p>
        <a href="{{ route('user.absens.index') }}"
           class="mt-4 inline-block px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition text-sm">
            Lihat Riwayat Absen
        </a>
    </div>
    @elseif($action === 'pending_masuk')
    <div class="bg-yellow-50 border border-yellow-200 rounded-xl p-6 text-center">
        <div class="text-4xl mb-3">⏳</div>
        <h3 class="text-lg font-bold text-yellow-800">Menunggu konfirmasi masuk</h3>
        <p class="text-yellow-700 mt-2 text-sm">Permintaan absen masuk Anda sedang diproses oleh admin.</p>
        <a href="{{ route('user.absens.index') }}"
           class="mt-4 inline-block px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition text-sm">
            Lihat Riwayat Absen
        </a>
    </div>
    @elseif($action === 'pending_keluar')
    <div class="bg-yellow-50 border border-yellow-200 rounded-xl p-6 text-center">
        <div class="text-4xl mb-3">⏳</div>
        <h3 class="text-lg font-bold text-yellow-800">Menunggu konfirmasi pulang cepat</h3>
        <p class="text-yellow-700 mt-2 text-sm">Permohonan pulang cepat Anda sedang diproses oleh admin.</p>
        <a href="{{ route('user.absens.index') }}"
           class="mt-4 inline-block px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition text-sm">
            Lihat Riwayat Absen
        </a>
    </div>
    @else
    <div class="bg-white dark:bg-gray-800 rounded-xl shadow p-6">

        <div class="bg-blue-50 dark:bg-blue-900/20 rounded-lg p-4 mb-6 text-center">
            <p class="text-sm text-blue-600 font-medium">Waktu Absen</p>
            <p class="text-3xl font-bold text-blue-800 dark:text-blue-300" id="jamSekarang">--:--:--</p>
        </div>

        <form method="POST" action="{{ route('user.absens.store') }}" enctype="multipart/form-data" class="space-y-5">
            @csrf
            <input type="hidden" name="request_type" value="{{ $action === 'keluar' ? 'keluar' : 'masuk' }}">

            <div class="rounded-lg bg-slate-50 dark:bg-slate-900 p-4 border border-slate-200 dark:border-slate-700">
                <p class="text-sm text-slate-500 dark:text-slate-300">Tipe absensi</p>
                <p class="text-lg font-semibold mt-1">{{ $action === 'keluar' ? 'Pulang Cepat' : 'Masuk' }}</p>
                @if($action === 'keluar')
                <p class="text-xs text-slate-400 mt-1">Ajukan permohonan pulang cepat. Admin akan mengkonfirmasi sebelum jam pulang Anda tercatat.</p>
                @else
                <p class="text-xs text-slate-400 mt-1">Ajukan absensi masuk. Admin akan memverifikasi sebelum Anda dianggap bekerja.</p>
                @endif
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                    📍 Lokasi
                </label>
                <div class="flex gap-2">
                    <input type="text" name="lokasi" id="lokasi" value="{{ old('lokasi') }}"
                           class="flex-1 px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 text-sm bg-white dark:bg-gray-900 text-gray-900 dark:text-gray-100"
                           placeholder="Nama lokasi / alamat" readonly>
                    <button type="button" onclick="ambilLokasi()"
                            class="px-3 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 text-sm whitespace-nowrap">
                        Ambil GPS
                    </button>
                </div>
                <input type="hidden" name="latitude" id="latitude" value="{{ old('latitude') }}">
                <input type="hidden" name="longitude" id="longitude" value="{{ old('longitude') }}">
                <p id="lokasiStatus" class="mt-1 text-xs text-gray-400"></p>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                    📝 Catatan (opsional)
                </label>
                <textarea name="catatan" rows="3"
                          class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 text-sm resize-none bg-white dark:bg-gray-900 text-gray-900 dark:text-gray-100"
                          placeholder="Tuliskan alasan atau keterangan...">{{ old('catatan') }}</textarea>
            </div>

            <button type="submit"
                    class="w-full py-3 bg-green-600 text-white rounded-lg hover:bg-green-700 transition font-semibold text-sm">
                {{ $action === 'keluar' ? 'Ajukan Pulang Cepat' : 'Ajukan Absen Masuk' }}
            </button>
        </form>
    </div>
    @endif
</div>

<script>
    function updateJam() {
        const el = document.getElementById('jamSekarang');
        if (el) {
            const now = new Date();
            el.textContent = now.toLocaleTimeString('id-ID', {hour:'2-digit', minute:'2-digit', second:'2-digit'});
        }
    }
    setInterval(updateJam, 1000);
    updateJam();

    function ambilLokasi() {
        const statusEl = document.getElementById('lokasiStatus');
        statusEl.textContent = 'Mengambil lokasi GPS...';

        if (!navigator.geolocation) {
            statusEl.textContent = 'Browser tidak mendukung geolokasi.';
            return;
        }

        navigator.geolocation.getCurrentPosition(
            function(pos) {
                const lat = pos.coords.latitude.toFixed(8);
                const lng = pos.coords.longitude.toFixed(8);

                document.getElementById('latitude').value  = lat;
                document.getElementById('longitude').value = lng;
                document.getElementById('lokasi').value    = `Lat: ${lat}, Lng: ${lng}`;
                document.getElementById('lokasi').removeAttribute('readonly');

                statusEl.textContent = '✅ Lokasi berhasil diambil. Anda bisa mengedit nama lokasi.';
                statusEl.className   = 'mt-1 text-xs text-green-600';
            },
            function(err) {
                statusEl.textContent = 'Gagal ambil GPS: ' + err.message;
                statusEl.className   = 'mt-1 text-xs text-red-500';
                document.getElementById('lokasi').removeAttribute('readonly');
            },
            { enableHighAccuracy: true, timeout: 10000 }
        );
    }
</script>
@endsection
