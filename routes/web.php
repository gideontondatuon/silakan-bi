<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\KalenderController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\PemesananController;
use App\Http\Controllers\ProfileController;

use App\Http\Controllers\Api\LayoutController;

use App\Http\Controllers\Admin\ApprovalController;
use App\Http\Controllers\Admin\AuditLogController;
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Admin\HariLiburController;
use App\Http\Controllers\Admin\LaporanController;
use App\Http\Controllers\Admin\LayoutRuanganController;
use App\Http\Controllers\Admin\RuanganController;
use App\Http\Controllers\Admin\UserController;

use App\Http\Controllers\KegiatanBerlangsungController;


/*
|--------------------------------------------------------------------------
| Halaman Utama
|--------------------------------------------------------------------------
*/

Route::get('/', function () {
    return redirect()->route('login');
});

/*
|--------------------------------------------------------------------------
| Tampilan TV Monitor Lobby / Kiosk Mode (Public)
|--------------------------------------------------------------------------
*/
use App\Http\Controllers\DisplayController;

Route::get('/display', [DisplayController::class, 'index'])->name('display.index');
Route::get('/kiosk', [DisplayController::class, 'index'])->name('display.kiosk');
Route::get('/api/display-data', [DisplayController::class, 'apiData'])->name('api.display-data');

Route::get('/download-manual-book', function() {
    $path = base_path('Manual_Book_SILAKAN_v1.0.doc');
    if (!file_exists($path)) {
        \Illuminate\Support\Facades\Artisan::call('export:manual-word');
    }
    abort_if(!file_exists($path), 404, 'Berkas Manual Book DOC belum tersedia di server.');
    return response()->download($path, 'Manual_Book_SILAKAN_v1.0.doc', [
        'Content-Type' => 'application/msword',
    ]);
})->name('download.manual-book');

Route::get('/download-manual-book-docx', function() {
    $path = base_path('Manual_Book_SILAKAN_v1.0.docx');
    if (!file_exists($path)) {
        $path = public_path('Manual_Book_SILAKAN_v1.0.docx');
    }
    abort_if(!file_exists($path), 404, 'Berkas Manual Book DOCX belum tersedia di server.');
    return response()->download($path, 'Manual_Book_SILAKAN_v1.0.docx', [
        'Content-Type' => 'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
    ]);
})->name('download.manual-book-docx');

Route::get('/download-manual-book-pdf', function() {
    $path = base_path('Manual_Book_SILAKAN_v1.0.pdf');
    if (!file_exists($path)) {
        $path = public_path('Manual_Book_SILAKAN_v1.0.pdf');
    }
    abort_if(!file_exists($path), 404, 'Berkas Manual Book PDF belum tersedia di server.');
    return response()->download($path, 'Manual_Book_SILAKAN_v1.0.pdf', [
        'Content-Type' => 'application/pdf',
    ]);
})->name('download.manual-book-pdf');

Route::get('/download-panduan-ringkas', function() {
    $path = base_path('public/Panduan_Ringkas_SILAKAN.doc');
    if (!file_exists($path)) {
        $path = base_path('Panduan_Ringkas_SILAKAN.doc');
    }
    abort_if(!file_exists($path), 404, 'Berkas Panduan Ringkas DOC belum tersedia di server.');
    return response()->download($path, 'Panduan_Ringkas_SILAKAN.doc', [
        'Content-Type' => 'application/msword',
    ]);
})->name('download.panduan-ringkas');

Route::get('/download-panduan-ringkas-pdf', function() {
    $path = base_path('Panduan_Ringkas_SILAKAN.pdf');
    if (!file_exists($path)) {
        $path = public_path('Panduan_Ringkas_SILAKAN.pdf');
    }
    abort_if(!file_exists($path), 404, 'Berkas Panduan Ringkas PDF belum tersedia di server.');
    return response()->download($path, 'Panduan_Ringkas_SILAKAN.pdf', [
        'Content-Type' => 'application/pdf',
    ]);
})->name('download.panduan-ringkas-pdf');

Route::get('/manual-book-slides', function() {
    $path = base_path('manual_book_slides.html');
    abort_if(!file_exists($path), 404, 'Tampilan slide panduan belum tersedia.');
    return response()->file($path);
})->name('manual-book-slides.view');

Route::get('/download-manual-book-slide-pptx', function() {
    $path = base_path('Manual_Book_Slide_SILAKAN.pptx');
    if (!file_exists($path)) {
        $path = public_path('Manual_Book_Slide_SILAKAN.pptx');
    }
    abort_if(!file_exists($path), 404, 'Berkas Manual Book Slide PPTX belum tersedia di server.');
    return response()->download($path, 'Manual_Book_Slide_SILAKAN_v1.0.pptx', [
        'Content-Type' => 'application/vnd.openxmlformats-officedocument.presentationml.presentation',
    ]);
})->name('download.manual-book-slide-pptx');

Route::get('/download-manual-book-slide-pdf', function() {
    $path = base_path('Manual_Book_Slide_SILAKAN.pdf');
    if (!file_exists($path)) {
        $path = public_path('Manual_Book_Slide_SILAKAN.pdf');
    }
    abort_if(!file_exists($path), 404, 'Berkas Manual Book Slide PDF belum tersedia di server.');
    return response()->download($path, 'Manual_Book_Slide_SILAKAN_v1.0.pdf', [
        'Content-Type' => 'application/pdf',
    ]);
})->name('download.manual-book-slide-pdf');

Route::get('/download-sdd', function() {
    $path = base_path('SDD_SILAKAN_v2.doc');
    if (!file_exists($path)) {
        \Illuminate\Support\Facades\Artisan::call('export:sdd-word');
    }
    abort_if(!file_exists($path), 404, 'Berkas Software Design Document belum tersedia di server.');
    return response()->download($path, 'Software_Design_Document_SILAKAN_KPwBI_Sulut.doc', [
        'Content-Type' => 'application/msword',
    ]);
})->name('download.sdd');


/*
|--------------------------------------------------------------------------
| Area Pengguna Terautentikasi
|--------------------------------------------------------------------------
|
| Seluruh route di dalam grup ini hanya dapat diakses oleh pengguna
| yang sudah login.
|
*/

Route::middleware('auth')->group(function () {

    /*
    |--------------------------------------------------------------------------
    | Pengarah Dashboard
    |--------------------------------------------------------------------------
    |
    | Route ini mengarahkan pengguna ke dashboard berdasarkan role.
    |
    */

    Route::get(
        '/dashboard',
        [DashboardController::class, 'index']
    )->name('dashboard');


    /*
    |--------------------------------------------------------------------------
    | Dashboard User
    |--------------------------------------------------------------------------
    */

    Route::get(
        '/user/dashboard',
        [DashboardController::class, 'user']
    )
        ->middleware('role:user')
        ->name('user.dashboard');


    /*
    |--------------------------------------------------------------------------
    | Profile
    |--------------------------------------------------------------------------
    */

    Route::get(
        '/profile',
        [ProfileController::class, 'edit']
    )->name('profile.edit');

    Route::patch(
        '/profile',
        [ProfileController::class, 'update']
    )->name('profile.update');


    /*
    |--------------------------------------------------------------------------
    | Notification
    |--------------------------------------------------------------------------
    */

    Route::get(
        '/notifications/live-sync',
        [NotificationController::class, 'liveSync']
    )->name('notifications.liveSync');

    Route::get(
        '/notifications',
        [NotificationController::class, 'index']
    )->name('notifications.index');

    Route::get(
        '/notification/{id}',
        [NotificationController::class, 'read']
    )->name('notification.read');

    Route::post(
        '/notifications/read-all',
        [NotificationController::class, 'readAll']
    )->name('notifications.readAll');

    Route::delete(
        '/notification/{id}',
        [NotificationController::class, 'destroy']
    )->name('notification.destroy');

    Route::delete(
        '/notifications/destroy-all',
        [NotificationController::class, 'destroyAll']
    )->name('notifications.destroyAll');


    /*
    |--------------------------------------------------------------------------
    | Pemesanan User
    |--------------------------------------------------------------------------
    */

    Route::get(
        '/pemesanan',
        [PemesananController::class, 'index']
    )->name('pemesanan.index');

    Route::get(
        '/pemesanan/create',
        [PemesananController::class, 'create']
    )->name('pemesanan.create');

    Route::post(
        '/pemesanan',
        [PemesananController::class, 'store']
    )->name('pemesanan.store');

    Route::get(
        '/pemesanan/{pemesanan}',
        [PemesananController::class, 'show']
    )->name('pemesanan.show');

    Route::post(
        '/pemesanan/{pemesanan}/cancel',
        [PemesananController::class, 'cancel']
    )->name('pemesanan.cancel');

    Route::post(
        '/pemesanan/{pemesanan}/selesai-awal',
        [PemesananController::class, 'selesaiAwal']
    )->name('pemesanan.selesai-awal');


    /*
    |--------------------------------------------------------------------------
    | Kalender
    |--------------------------------------------------------------------------
    */

    Route::get(
        '/kalender',
        [KalenderController::class, 'index']
    )->name('kalender.index');

    Route::get(
        '/kalender/events',
        [KalenderController::class, 'events']
    )->name('kalender.events');


    /*
    |--------------------------------------------------------------------------
    | Layout Ruangan
    |--------------------------------------------------------------------------
    |
    | URL tetap memakai /api agar kode fetch frontend tidak perlu diubah.
    | Route ini menggunakan session authentication dari middleware web.
    |
    */

    Route::get(
        '/api/ruangan/{id}/layouts',
        [LayoutController::class, 'getLayoutsByRuangan']
    )->name('api.ruangan.layouts');

    Route::get(
        '/api/pemesanan/check-conflict',
        [PemesananController::class, 'checkConflict']
    )->name('api.pemesanan.check-conflict');
});


/*
|--------------------------------------------------------------------------
| Area Administrator
|--------------------------------------------------------------------------
|
| Seluruh route di dalam grup ini hanya dapat diakses pengguna dengan
| role admin.
|
*/

Route::middleware([
    'auth',
    'role:admin',
])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {

        /*
        |--------------------------------------------------------------------------
        | Dashboard Admin
        |--------------------------------------------------------------------------
        */

        Route::get(
            '/dashboard',
            [AdminDashboardController::class, 'index']
        )->name('dashboard');


        /*
        |--------------------------------------------------------------------------
        | Master Ruangan
        |--------------------------------------------------------------------------
        */

        Route::resource(
            'ruangan',
            RuanganController::class
        )->except(['show']);



        /*
        |--------------------------------------------------------------------------
        | Master Layout Ruangan
        |--------------------------------------------------------------------------
        */

        Route::resource(
            'layout',
            LayoutRuanganController::class
        )->except(['show']);


        /*
        |--------------------------------------------------------------------------
        | Master Hari Libur
        |--------------------------------------------------------------------------
        */

        Route::post('/hari-libur/sync', [HariLiburController::class, 'syncApi'])->name('hari-libur.sync');
        Route::resource('hari-libur', HariLiburController::class)->only(['index', 'store', 'destroy']);


        /*
        |--------------------------------------------------------------------------
        | Manajemen User
        |--------------------------------------------------------------------------
        */

        Route::resource(
            'users',
            UserController::class
        )->except(['show']);


        /*
        |--------------------------------------------------------------------------
        | Approval Pemesanan
        |--------------------------------------------------------------------------
        */

        Route::get(
            '/approval',
            [ApprovalController::class, 'index']
        )->name('approval.index');

        Route::get(
            '/approval/create',
            [ApprovalController::class, 'create']
        )->name('approval.create');

        Route::post(
            '/approval',
            [ApprovalController::class, 'store']
        )->name('approval.store');

        Route::get(
            '/approval/{pemesanan}',
            [ApprovalController::class, 'show']
        )->name('approval.show');

        Route::post(
            '/approval/{pemesanan}/approve',
            [ApprovalController::class, 'approve']
        )->name('approval.approve');

        Route::post(
            '/approval/{pemesanan}/reject',
            [ApprovalController::class, 'reject']
        )->name('approval.reject');

        Route::post(
            '/approval/{pemesanan}/selesai-awal',
            [ApprovalController::class, 'selesaiAwal']
        )->name('approval.selesai-awal');

        Route::delete(
            '/approval/{pemesanan}',
            [ApprovalController::class, 'destroy']
        )->name('approval.destroy');


        /*
        |--------------------------------------------------------------------------
        | Kegiatan Berlangsung
        |--------------------------------------------------------------------------
        */

        Route::get(
            '/kegiatan-berlangsung',
            [KegiatanBerlangsungController::class, 'index']
        )->name('kegiatan-berlangsung.index');


        /*
        |--------------------------------------------------------------------------
        | Laporan & Ekspor Data
        |--------------------------------------------------------------------------
        */

        Route::get('/laporan', [LaporanController::class, 'index'])->name('laporan.index');
        Route::get('/laporan/export-excel', [LaporanController::class, 'exportExcel'])->name('laporan.export-excel');
        Route::get('/laporan/cetak', [LaporanController::class, 'cetakPdf'])->name('laporan.cetak');


        /*
        |--------------------------------------------------------------------------
        | Audit Log
        |--------------------------------------------------------------------------
        */

        Route::get(
            '/audit-log',
            [AuditLogController::class, 'index']
        )->name('audit-log.index');
    });


/*
|--------------------------------------------------------------------------
| Authentication Routes
|--------------------------------------------------------------------------
*/

require __DIR__.'/auth.php';