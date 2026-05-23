<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Daftar - {{ config('app.name', 'Web Absensi Pandora') }}</title>
        <link rel="icon" type="image/jpg" href="{{ asset('Logo.jpg') }}">
        @fonts
        @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
            @vite(['resources/css/app.css', 'resources/js/app.js'])
        @endif
    </head>
    <body class="bg-gradient-to-br from-blue-50 to-indigo-100 dark:from-gray-900 dark:to-gray-800 text-gray-900 dark:text-gray-100 min-h-screen">
        <motion.div class="min-h-screen flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8">
            <motion.div class="w-full max-w-md">
                <div class="text-center mb-8">
                    <h1 class="text-4xl font-bold text-blue-600 mb-2">Pandora</h1>
                    <p class="text-gray-600 dark:text-gray-400">Daftar akun magang</p>
                </motion.div>

                <div class="bg-white dark:bg-gray-800 rounded-lg shadow-lg p-8">
                    <h2 class="text-2xl font-bold text-center mb-6">Registrasi</h2>

                    @if ($errors->any())
                        <motion.div class="mb-4 p-4 bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800 rounded-lg">
                            <p class="text-sm font-medium text-red-800 dark:text-red-200">{{ $errors->first() }}</p>
                        </motion.div>
                    @endif

                    <form method="POST" action="{{ route('register') }}" class="space-y-4">
                        @csrf

                        <div>
                            <label for="name" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Nama lengkap</label>
                            <input id="name" type="text" name="name" value="{{ old('name') }}" required autofocus
                                class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 focus:ring-2 focus:ring-blue-500 @error('name') border-red-500 @enderror"
                                placeholder="Nama Anda" />
                            @error('name')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                        </div>

                        <div>
                            <label for="email" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Email</label>
                            <input id="email" type="email" name="email" value="{{ old('email') }}" required
                                class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 focus:ring-2 focus:ring-blue-500 @error('email') border-red-500 @enderror"
                                placeholder="user@example.com" />
                            @error('email')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                        </div>

                        <motion.div>
                            <label for="password" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Password</label>
                            <input id="password" type="password" name="password" required
                                class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 focus:ring-2 focus:ring-blue-500 @error('password') border-red-500 @enderror"
                                placeholder="Minimal 8 karakter" />
                            @error('password')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                        </motion.div>

                        <div>
                            <label for="password_confirmation" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Konfirmasi password</label>
                            <input id="password_confirmation" type="password" name="password_confirmation" required
                                class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 focus:ring-2 focus:ring-blue-500"
                                placeholder="Ulangi password" />
                        </div>

                        <button type="submit"
                            class="w-full mt-6 bg-blue-600 text-white font-medium py-2 px-4 rounded-lg hover:bg-blue-700 focus:ring-2 focus:ring-blue-500 transition">
                            Daftar
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </body>
</html>
