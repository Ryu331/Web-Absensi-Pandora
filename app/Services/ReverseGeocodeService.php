<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;

class ReverseGeocodeService
{
    /**
     * Ubah koordinat GPS menjadi alamat teks (reverse geocoding).
     */
    public function resolve(float $latitude, float $longitude): array
    {
        $mapsUrl = $this->googleMapsUrl($latitude, $longitude);

        try {
            $response = Http::withHeaders([
                'User-Agent' => config('app.name') . ' (' . (config('app.url') ?: 'localhost') . ')',
            ])
                ->timeout(12)
                ->get('https://nominatim.openstreetmap.org/reverse', [
                    'lat'             => $latitude,
                    'lon'             => $longitude,
                    'format'          => 'json',
                    'accept-language' => 'id',
                    'zoom'            => 18,
                ]);

            if ($response->successful()) {
                $data = $response->json();
                $address = $this->formatAddress($data, $latitude, $longitude);

                if ($this->isCoordinateOnly($address, $latitude, $longitude) === false) {
                    return [
                        'address'  => Str::limit($address, 255, ''),
                        'maps_url' => $mapsUrl,
                    ];
                }
            }

            $backup = $this->tryBigDataCloud($latitude, $longitude);
            if ($backup) {
                return [
                    'address'  => Str::limit($backup, 255, ''),
                    'maps_url' => $mapsUrl,
                ];
            }

            return $this->fallback($latitude, $longitude, $mapsUrl);
        } catch (\Throwable) {
            $backup = $this->tryBigDataCloud($latitude, $longitude);
            if ($backup) {
                return [
                    'address'  => Str::limit($backup, 255, ''),
                    'maps_url' => $mapsUrl,
                ];
            }

            return $this->fallback($latitude, $longitude, $mapsUrl);
        }
    }

    private function tryBigDataCloud(float $latitude, float $longitude): ?string
    {
        try {
            $response = Http::timeout(10)->get('https://api.bigdatacloud.net/data/reverse-geocode-client', [
                'latitude'         => $latitude,
                'longitude'        => $longitude,
                'localityLanguage' => 'id',
            ]);

            if (! $response->successful()) {
                return null;
            }

            $d = $response->json();
            $parts = array_values(array_unique(array_filter([
                $d['locality'] ?? null,
                $d['city'] ?? null,
                $d['principalSubdivision'] ?? null,
                $d['countryName'] ?? null,
            ])));

            return $parts !== [] ? implode(', ', $parts) : null;
        } catch (\Throwable) {
            return null;
        }
    }

    private function isCoordinateOnly(string $address, float $latitude, float $longitude): bool
    {
        $coord = sprintf('%.6f, %.6f', $latitude, $longitude);

        return $address === $coord || str_starts_with($address, 'Lat:');
    }

    private function formatAddress(array $data, float $latitude, float $longitude): string
    {
        $a = $data['address'] ?? [];

        $jalan = trim(implode(' ', array_filter([
            $a['house_number'] ?? null,
            $a['road'] ?? $a['pedestrian'] ?? $a['footway'] ?? $a['residential'] ?? null,
        ])));

        $parts = array_values(array_unique(array_filter([
            $jalan ?: null,
            $a['neighbourhood'] ?? $a['suburb'] ?? $a['village'] ?? $a['hamlet'] ?? null,
            $a['city_district'] ?? null,
            $a['city'] ?? $a['town'] ?? $a['municipality'] ?? $a['county'] ?? null,
            $a['state'] ?? $a['region'] ?? null,
            isset($a['postcode']) ? 'Kode pos ' . $a['postcode'] : null,
        ])));

        if ($parts !== []) {
            return implode(', ', $parts);
        }

        if (! empty($data['display_name'])) {
            return $data['display_name'];
        }

        return sprintf('%.6f, %.6f', $latitude, $longitude);
    }

    private function googleMapsUrl(float $latitude, float $longitude): string
    {
        return 'https://www.google.com/maps?q=' . $latitude . ',' . $longitude;
    }

    private function fallback(float $latitude, float $longitude, string $mapsUrl): array
    {
        return [
            'address'  => sprintf('%.6f, %.6f', $latitude, $longitude),
            'maps_url' => $mapsUrl,
        ];
    }
}
