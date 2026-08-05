<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\PemesananController;
use App\Http\Controllers\KalenderController;
use App\Http\Controllers\NotificationController;

use App\Http\Controllers\Admin\RuanganController;
use App\Http\Controllers\Admin\FasilitasController;
use App\Http\Controllers\Admin\LayoutRuanganController;
use App\Http\Controllers\Admin\ApprovalController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\LaporanController;

use App\Http\Controllers\Api\LayoutController;
use App\Http\Controllers\Admin\AuditLogController;
use App\Http\Controllers\KegiatanBerlangsungController;



Route::get('/', function () {

    return view('welcome');

});



/*
|--------------------------------------------------------------------------
| Dashboard
|--------------------------------------------------------------------------
*/

Route::get(
    '/dashboard',
    [
        DashboardController::class,
        'index'
    ]
)
->middleware('auth')
->name('dashboard');



/*
|--------------------------------------------------------------------------
| Authentication User
|--------------------------------------------------------------------------
*/

Route::middleware('auth')
->group(function () {


    Route::get(
        '/profile',
        [
            ProfileController::class,
            'edit'
        ]
    )
    ->name('profile.edit');



    Route::patch(
        '/profile',
        [
            ProfileController::class,
            'update'
        ]
    )
    ->name('profile.update');



    Route::delete(
        '/profile',
        [
            ProfileController::class,
            'destroy'
        ]
    )
    ->name('profile.destroy');



    /*
    |--------------------------------------------------------------------------
    | Notification
    |--------------------------------------------------------------------------
    */


    Route::get(
        '/notification/{id}',
        [
            NotificationController::class,
            'read'
        ]
    )
    ->name('notification.read');



    /*
    |--------------------------------------------------------------------------
    | Pemesanan User
    |--------------------------------------------------------------------------
    */


    Route::get(
        '/pemesanan',
        [
            PemesananController::class,
            'index'
        ]
    )
    ->name('pemesanan.index');



    Route::get(
        '/pemesanan/create',
        [
            PemesananController::class,
            'create'
        ]
    )
    ->name('pemesanan.create');



    Route::post(
        '/pemesanan',
        [
            PemesananController::class,
            'store'
        ]
    )
    ->name('pemesanan.store');



    Route::get(
        '/pemesanan/{pemesanan}',
        [
            PemesananController::class,
            'show'
        ]
    )
    ->name('pemesanan.show');


});



/*
|--------------------------------------------------------------------------
| Admin Area
|--------------------------------------------------------------------------
*/


Route::middleware([
    'auth',
    'role:admin'
])
->prefix('admin')
->name('admin.')
->group(function () {



    Route::resource(
        'ruangan',
        RuanganController::class
    );



    Route::resource(
        'fasilitas',
        FasilitasController::class
    );



    Route::resource(
        'layout',
        LayoutRuanganController::class
    );



    Route::resource(
        'users',
        UserController::class
    )
    ->names('users');



    /*
    |--------------------------------------------------------------------------
    | Approval
    |--------------------------------------------------------------------------
    */


    Route::get(
        '/approval',
        [
            ApprovalController::class,
            'index'
        ]
    )
    ->name('approval.index');



    Route::get(
        '/approval/{pemesanan}',
        [
            ApprovalController::class,
            'show'
        ]
    )
    ->name('approval.show');



    Route::post(
        '/approval/{pemesanan}/approve',
        [
            ApprovalController::class,
            'approve'
        ]
    )
    ->name('approval.approve');



    Route::post(
        '/approval/{pemesanan}/reject',
        [
            ApprovalController::class,
            'reject'
        ]
    )
    ->name('approval.reject');

    /*
    |--------------------------------------------------------------------------
    | Laporan
    |--------------------------------------------------------------------------
    */


    Route::get(
        '/laporan',
        [
            LaporanController::class,
            'index'
        ]
    )
    ->name('laporan.index');

    Route::get(
        '/laporan',
        [
            LaporanController::class,
            'index'
        ]
    )
    ->name('laporan.index');

    Route::get(
        '/laporan/export-excel',
        [
            LaporanController::class,
            'exportExcel'
        ]
    )
    ->name('laporan.export.excel');

    Route::get(
        '/laporan/export-pdf',
        [
            LaporanController::class,
            'exportPdf'
        ]
    )
    ->name('laporan.export.pdf');

    Route::get(
        '/audit-log',
        [
            AuditLogController::class,
            'index'
        ]
    )
    ->name('audit-log.index');


});



/*
|--------------------------------------------------------------------------
| Kalender
|--------------------------------------------------------------------------
*/


Route::middleware('auth')
->group(function () {


    Route::get(
        '/kalender',
        [
            KalenderController::class,
            'index'
        ]
    )
    ->name('kalender.index');



    Route::get(
        '/kalender/events',
        [
            KalenderController::class,
            'events'
        ]
    )
    ->name('kalender.events');


});

Route::middleware('auth')
->group(function(){


    Route::get(

        '/kegiatan-berlangsung',

        [

            KegiatanBerlangsungController::class,

            'index'

        ]

    )

    ->name('kegiatan.berlangsung.index');


});



/*
|--------------------------------------------------------------------------
| API
|--------------------------------------------------------------------------
*/


Route::get(
    '/api/ruangan/{id}/layouts',
    [
        LayoutController::class,
        'getLayoutsByRuangan'
    ]
);



require __DIR__.'/auth.php';