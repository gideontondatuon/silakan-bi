<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\LaporanController;

/*
|--------------------------------------------------------------------------
| Dokumentasi & Berkas Manual Book SILAKAN
|--------------------------------------------------------------------------
*/

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
| Pratinjau Cetak / PDF Laporan (Official Bank Indonesia Printable Document)
|--------------------------------------------------------------------------
*/

Route::get('/admin/laporan/cetak', [LaporanController::class, 'cetakPdf'])
    ->middleware(['auth', 'role:admin'])
    ->name('admin.laporan.cetak');

/*
|--------------------------------------------------------------------------
| Named Routes Alias untuk Kompatibilitas Sistem Internal Laravel
|--------------------------------------------------------------------------
*/

Route::get('/login', function () {
    return view('react-app');
})->name('login');

Route::get('/dashboard', function () {
    return view('react-app');
})->name('dashboard');

/*
|--------------------------------------------------------------------------
| React Frontend SPA Fallback Layer
|--------------------------------------------------------------------------
| Seluruh rute frontend (SPA) dilayani oleh kontainer react-app.blade.php
| dan dikelola secara modular oleh React Router.
*/

Route::get('/react/{any?}', function () {
    return view('react-app');
})->where('any', '.*');

Route::get('/{any?}', function () {
    return view('react-app');
})->where('any', '^(?!api|sanctum|up).*$')->name('react.spa');