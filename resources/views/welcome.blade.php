<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Web Absensi Pandora</title>
        <link rel="icon" type="image/jpg" href="{{ asset('Logo.jpg') }}">
        @fonts
        @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
            @vite(['resources/css/app.css', 'resources/js/app.js'])
        @endif
    </head>
    <body class="bg-gradient-to-br from-blue-50 to-indigo-100 dark:from-gray-900 dark:to-gray-800 text-gray-900 dark:text-gray-100 min-h-screen">
        <!-- Navigation -->
        <nav class="bg-white dark:bg-gray-800 shadow-md">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between items-center h-16">
                    <div class="flex items-center">
                        <h1 class="text-2xl font-bold text-blue-600">Pandora</h1>
                    </div>
                    <div class="flex items-center gap-4">
                        @auth
                            <a href="{{ route('dashboard') }}" class="px-4 py-2 text-blue-600 hover:text-blue-800 font-medium">Dashboard</a>
                        @else
                            <a href="{{ route('login') }}" class="px-4 py-2 text-blue-600 hover:text-blue-800">Login</a>
                        @endauth
                    </div>
                </div>
            </div>
        </nav>

        <!-- Hero Section -->
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-20">
            <div class="text-center">
                <h2 class="text-5xl font-bold mb-6">Sistem Absensi Pandora</h2>
                <p class="text-xl text-gray-600 dark:text-gray-400 mb-8 max-w-2xl mx-auto">
                    Kelola absensi karyawan dengan mudah dan efisien menggunakan platform digital terpadu.
                </p>
                <div class="flex gap-4 justify-center">
                    @auth
                        <a href="{{ route('dashboard') }}" class="px-8 py-3 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition font-medium">
                            Buka Dashboard
                        </a>
                    @else
                        <a href="{{ route('login') }}" class="px-8 py-3 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition font-medium">
                            Login Sekarang
                        </a>
                    @endauth
                </div>
            </div>

            <!-- Features Section -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8 mt-20">
                <div class="bg-white dark:bg-gray-800 p-8 rounded-lg shadow-lg">
                    <div class="text-4xl mb-4">📊</div>
                    <h3 class="text-xl font-bold mb-3">Dashboard Real-time</h3>
                    <p class="text-gray-600 dark:text-gray-400">
                        Pantau absensi karyawan secara real-time dengan visualisasi data yang mudah dipahami.
                    </p>
                </div>

                <div class="bg-white dark:bg-gray-800 p-8 rounded-lg shadow-lg">
                    <div class="text-4xl mb-4">📱</div>
                    <h3 class="text-xl font-bold mb-3">Akses Mobile</h3>
                    <p class="text-gray-600 dark:text-gray-400">
                        Akses sistem absensi dari mana saja, kapan saja menggunakan perangkat mobile atau web.
                    </p>
                </div>

                <div class="bg-white dark:bg-gray-800 p-8 rounded-lg shadow-lg">
                    <div class="text-4xl mb-4">🔒</div>
                    <h3 class="text-xl font-bold mb-3">Aman & Terpercaya</h3>
                    <p class="text-gray-600 dark:text-gray-400">
                        Data tersimpan dengan aman menggunakan enkripsi tingkat enterprise.
                    </p>
                </div>
            </div>
        </div>

        <!-- Footer -->
        <footer class="bg-white dark:bg-gray-800 shadow-md mt-20">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 text-center text-gray-600 dark:text-gray-400">
                <p>&copy; 2026 Sistem Absensi Pandora. All rights reserved.</p>
            </div>
        </footer>
    </body>
</html>
