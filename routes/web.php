<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\Admin\DashboardController as AdminDashboard;
use App\Http\Controllers\Admin\UserController as AdminUser;
use App\Http\Controllers\Admin\AbsenController as AdminAbsen;
use App\Http\Controllers\Admin\CutiController as AdminCuti;
use App\Http\Controllers\Admin\LaporanController as AdminLaporan;
use App\Http\Controllers\User\DashboardController as UserDashboard;
use App\Http\Controllers\User\AbsenController as UserAbsen;
use App\Http\Controllers\User\CutiController as UserCuti;
use App\Http\Controllers\User\LaporanController as UserLaporan;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Root – redirect sesuai role jika sudah login
|--------------------------------------------------------------------------
*/
Route::get('/', function () {
    if (Auth::check()) {
        return Auth::user()->role === 'admin'
            ? redirect()->route('admin.dashboard')
            : redirect()->route('user.dashboard');
    }
    return view('welcome');
});

Route::get('/dashboard', function () {
    return Auth::user()->role === 'admin'
        ? redirect()->route('admin.dashboard')
        : redirect()->route('user.dashboard');
})->middleware('auth')->name('dashboard');

/*
|--------------------------------------------------------------------------
| Auth Routes (guest only)
|--------------------------------------------------------------------------
*/
Route::middleware('guest')->group(function () {
    Route::get('/login',    [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login',   [AuthController::class, 'login']);

    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register',[AuthController::class, 'register'])->name('register.store');
});

Route::post('/logout', [AuthController::class, 'logout'])
    ->middleware('auth')
    ->name('logout');

/*
|--------------------------------------------------------------------------
| Admin Routes
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'role:admin'])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {

        // Dashboard
        Route::get('/dashboard', [AdminDashboard::class, 'index'])->name('dashboard');

        // Manajemen User
        Route::resource('users', AdminUser::class);
        Route::patch('users/{user}/toggle-status', [AdminUser::class, 'toggleStatus'])
            ->name('users.toggle-status');

        // Manajemen Absensi
        Route::get('absens',          [AdminAbsen::class, 'index'])->name('absens.index');
        Route::get('absens/{absen}',  [AdminAbsen::class, 'show'])->name('absens.show');

        // Absen Requests
        Route::get('absen-requests',                              [AdminAbsen::class, 'requests'])->name('absen-requests.index');
        Route::post('absen-requests/{absenRequest}/terima',      [AdminAbsen::class, 'terima'])->name('absen-requests.terima');
        Route::post('absen-requests/{absenRequest}/tolak',       [AdminAbsen::class, 'tolak'])->name('absen-requests.tolak');

        // Laporan
        Route::resource('laporans', AdminLaporan::class)->except(['edit', 'update']);

        // Manajemen Cuti
        Route::get('cutis', [AdminCuti::class, 'index'])->name('cutis.index');
        Route::get('cutis/{cuti}', [AdminCuti::class, 'show'])->name('cutis.show');
        Route::post('cutis/{cuti}/setujui', [AdminCuti::class, 'setujui'])->name('cutis.setujui');
        Route::post('cutis/{cuti}/tolak', [AdminCuti::class, 'tolak'])->name('cutis.tolak');
    });

/*
|--------------------------------------------------------------------------
| User Routes
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'role:user'])
    ->prefix('user')
    ->name('user.')
    ->group(function () {

        // Dashboard
        Route::get('/dashboard', [UserDashboard::class, 'index'])->name('dashboard');

        // Absensi
        Route::get('absens/reverse-geocode', [UserAbsen::class, 'reverseGeocode'])
            ->name('absens.reverse-geocode');
        Route::resource('absens', UserAbsen::class)->only(['index', 'create', 'store', 'show']);

        // Laporan
        Route::resource('laporans', UserLaporan::class);

        // Cuti
        Route::resource('cutis', UserCuti::class)->only(['index', 'create', 'store', 'show']);
    });

/*
|--------------------------------------------------------------------------
| Cuti Routes (Shared for both admin and user)
|--------------------------------------------------------------------------
*/
Route::middleware(['auth'])
    ->prefix('cuti')
    ->name('cuti.')
    ->group(function () {
        Route::get('/', [UserCuti::class, 'index'])->name('index');
        Route::get('/create', [UserCuti::class, 'create'])->name('create');
        Route::post('/', [UserCuti::class, 'store'])->name('store');
        Route::get('/{cuti}', [UserCuti::class, 'show'])->name('show');
    });
