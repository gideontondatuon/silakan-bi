<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthApiController;
use App\Http\Controllers\Api\DashboardApiController;
use App\Http\Controllers\Api\PemesananApiController;
use App\Http\Controllers\Api\AdminApprovalApiController;
use App\Http\Controllers\Api\RuanganApiController;
use App\Http\Controllers\Api\LayoutController;
use App\Http\Controllers\Api\HariLiburApiController;
use App\Http\Controllers\Api\UserApiController;
use App\Http\Controllers\Api\LaporanApiController;
use App\Http\Controllers\Api\NotificationApiController;
use App\Http\Controllers\Api\AuditLogApiController;
use App\Http\Controllers\Api\KalenderApiController;
use App\Http\Controllers\DisplayController;

/*
|--------------------------------------------------------------------------
| Public API Routes
|--------------------------------------------------------------------------
*/

Route::get('/test-api', function () {
    return response()->json([
        'status' => 'online',
        'system' => 'SILAKAN API — Bank Indonesia KPwBI Sulut',
        'timestamp' => now()->toIso8601String(),
    ]);
});

// TV Monitor Lobby Kiosk Display Data (Public / Kiosk Mode)
Route::get('/display-data', [DisplayController::class, 'apiData'])->name('api.display-data');

// Layouts query helper
Route::get('/ruangan/{ruangan}/layouts', [LayoutController::class, 'index']);
Route::get('/ruangan/{id}/layouts-by-id', [LayoutController::class, 'getLayoutsByRuangan']);

// Auth login (Stateful Session & Bearer Token)
Route::post('/auth/login', [AuthApiController::class, 'login']);


/*
|--------------------------------------------------------------------------
| Authenticated Routes (User & Admin)
|--------------------------------------------------------------------------
*/

Route::middleware('auth:sanctum')->group(function () {

    // Auth & Profile
    Route::post('/auth/logout', [AuthApiController::class, 'logout']);
    Route::get('/auth/me', [AuthApiController::class, 'me']);
    Route::put('/auth/profile', [AuthApiController::class, 'updateProfile']);
    Route::put('/auth/password', [AuthApiController::class, 'updatePassword']);

    // User Dashboard
    Route::get('/dashboard/user', [DashboardApiController::class, 'user']);

    // Notifications
    Route::get('/notifications', [NotificationApiController::class, 'index']);
    Route::get('/notifications/live-sync', [NotificationApiController::class, 'liveSync']);
    Route::post('/notifications/read-all', [NotificationApiController::class, 'readAll']);
    Route::post('/notifications/{id}/read', [NotificationApiController::class, 'markAsRead']);
    Route::delete('/notifications/{id}', [NotificationApiController::class, 'destroy']);
    Route::delete('/notifications', [NotificationApiController::class, 'destroyAll']);

    // Calendar
    Route::get('/kalender', [KalenderApiController::class, 'index']);
    Route::get('/kalender/events', [KalenderApiController::class, 'events']);

    // Live Activities (Kegiatan Berlangsung)
    Route::get('/kegiatan-berlangsung', [DashboardApiController::class, 'kegiatanBerlangsung']);

    // Rooms & Layouts (Read-only for regular users)
    Route::get('/ruangan', [RuanganApiController::class, 'index']);
    Route::get('/ruangan/{ruangan}', [RuanganApiController::class, 'show']);

    // User Booking Operations
    Route::get('/pemesanan', [PemesananApiController::class, 'index']);
    Route::post('/pemesanan', [PemesananApiController::class, 'store']);
    Route::get('/pemesanan/check-conflict', [PemesananApiController::class, 'checkConflict']);
    Route::get('/pemesanan/{pemesanan}', [PemesananApiController::class, 'show']);
    Route::post('/pemesanan/{pemesanan}/cancel', [PemesananApiController::class, 'cancel']);
    Route::post('/pemesanan/{pemesanan}/selesai-awal', [PemesananApiController::class, 'selesaiAwal']);

    /*
    |--------------------------------------------------------------------------
    | Administrator Only Routes
    |--------------------------------------------------------------------------
    */
    Route::middleware('role:admin')->group(function () {

        // Admin Dashboard
        Route::get('/dashboard/admin', [DashboardApiController::class, 'admin']);
        Route::get('/admin/dashboard', [DashboardApiController::class, 'admin']);

        // Approvals Management
        Route::get('/admin/approval', [AdminApprovalApiController::class, 'index']);
        Route::post('/admin/approval', [AdminApprovalApiController::class, 'store']);
        Route::get('/admin/approval/{pemesanan}', [AdminApprovalApiController::class, 'show']);
        Route::post('/admin/approval/{pemesanan}/approve', [AdminApprovalApiController::class, 'approve']);
        Route::post('/admin/approval/{pemesanan}/reject', [AdminApprovalApiController::class, 'reject']);
        Route::post('/admin/approval/{pemesanan}/selesai-awal', [AdminApprovalApiController::class, 'selesaiAwal']);
        Route::delete('/admin/approval/{pemesanan}', [AdminApprovalApiController::class, 'destroy']);

        // Master Data Ruangan
        Route::apiResource('/admin/ruangan', RuanganApiController::class);

        // Master Data Layout
        Route::apiResource('/admin/layout', LayoutController::class)->parameters(['layout' => 'layout']);

        // Master Hari Libur
        Route::get('/admin/hari-libur', [HariLiburApiController::class, 'index']);
        Route::post('/admin/hari-libur', [HariLiburApiController::class, 'store']);
        Route::delete('/admin/hari-libur/{hariLibur}', [HariLiburApiController::class, 'destroy']);
        Route::post('/admin/hari-libur/sync', [HariLiburApiController::class, 'syncApi']);

        // User Management
        Route::apiResource('/admin/users', UserApiController::class);

        // Reports & Analytics
        Route::get('/admin/laporan', [LaporanApiController::class, 'index']);
        Route::get('/admin/laporan/export-excel', [LaporanApiController::class, 'exportExcel']);

        // Audit Log
        Route::get('/admin/audit-log', [AuditLogApiController::class, 'index']);
    });
});