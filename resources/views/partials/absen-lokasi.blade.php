<div class="sm:col-span-2">
    <dt class="text-gray-500">Lokasi</dt>
    <dd class="font-medium mt-1">{{ $absen->lokasi ?? '-' }}</dd>
    @if($absen->hasCoordinates())
        <p class="mt-1 text-xs text-gray-400 dark:text-gray-500">
            Koordinat: {{ $absen->latitude }}, {{ $absen->longitude }}
        </p>
        <a href="{{ $absen->googleMapsUrl() }}"
           target="_blank"
           rel="noopener noreferrer"
           class="inline-flex items-center gap-1.5 mt-2 px-3 py-1.5 bg-blue-600 text-white text-xs font-medium rounded-lg hover:bg-blue-700 transition">
            <span aria-hidden="true">📍</span>
            Buka di Google Maps
        </a>
    @endif
</div>
