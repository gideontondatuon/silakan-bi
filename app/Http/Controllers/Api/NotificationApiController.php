<?php

namespace App\Http\Controllers\Api;

use App\Enums\PemesananStatus;
use App\Http\Controllers\Controller;
use App\Models\Pemesanan;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class NotificationApiController extends Controller
{
    /**
     * List all notifications for authenticated user.
     */
    public function index(Request $request): JsonResponse
    {
        $user = $request->user();
        $notifications = $user->notifications()->latest()->paginate($request->get('per_page', 15));

        return response()->json([
            'status' => 'success',
            'data' => $notifications,
        ]);
    }

    /**
     * Mark single notification as read.
     */
    public function markAsRead(Request $request, string $id): JsonResponse
    {
        $notification = $request->user()->notifications()->findOrFail($id);
        $notification->markAsRead();

        return response()->json([
            'status' => 'success',
            'message' => 'Notifikasi ditandai telah dibaca.',
            'data' => $notification,
        ]);
    }

    /**
     * Mark all notifications as read.
     */
    public function readAll(Request $request): JsonResponse
    {
        $request->user()->unreadNotifications()->update([
            'read_at' => now(),
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'Semua notifikasi telah dibaca.',
        ]);
    }

    /**
     * Delete notification.
     */
    public function destroy(Request $request, string $id): JsonResponse
    {
        $notification = $request->user()->notifications()->findOrFail($id);
        $notification->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'Notifikasi berhasil dihapus.',
        ]);
    }

    /**
     * Delete all notifications.
     */
    public function destroyAll(Request $request): JsonResponse
    {
        $request->user()->notifications()->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'Semua riwayat notifikasi berhasil dihapus.',
        ]);
    }

    /**
     * Silent real-time background sync endpoint for React & Mobile.
     */
    public function liveSync(Request $request): JsonResponse
    {
        $user = $request->user();
        if (!$user) {
            return response()->json(['status' => 'unauthenticated'], 401);
        }

        $unreadCount = $user->unreadNotifications()->count();
        $latestNotifications = $user->unreadNotifications()
            ->latest()
            ->take(5)
            ->get()
            ->map(function ($n) {
                return [
                    'id' => $n->id,
                    'judul' => $n->data['judul'] ?? 'Notifikasi Baru',
                    'pesan' => $n->data['pesan'] ?? '',
                    'waktu' => $n->data['waktu'] ?? $n->created_at->diffForHumans(),
                    'pemesanan_id' => $n->data['pemesanan_id'] ?? null,
                    'created_at' => $n->created_at->toIso8601String(),
                ];
            });

        $roleVal = is_object($user->role) ? $user->role->value : (string) $user->role;
        $isAdmin = ($roleVal === 'admin');

        $extra = [
            'is_admin' => $isAdmin,
        ];

        if ($isAdmin) {
            $extra['count_pending'] = Pemesanan::where('status', PemesananStatus::PENDING->value)->count();
            $extra['count_disetujui'] = Pemesanan::where('status', PemesananStatus::DISETUJUI->value)->count();
            $extra['count_total'] = Pemesanan::count();
            $extra['latest_booking_id'] = Pemesanan::latest('id')->value('id') ?? 0;
            $extra['latest_updated_at'] = Pemesanan::latest('updated_at')->value('updated_at')?->timestamp ?? 0;
        } else {
            $extra['count_my_pending'] = Pemesanan::where('user_id', $user->id)
                ->where('status', PemesananStatus::PENDING->value)
                ->count();
            $extra['latest_my_booking_id'] = Pemesanan::where('user_id', $user->id)->latest('id')->value('id') ?? 0;
            $extra['latest_my_updated_at'] = Pemesanan::where('user_id', $user->id)->latest('updated_at')->value('updated_at')?->timestamp ?? 0;
        }

        return response()->json([
            'status' => 'success',
            'unread_count' => $unreadCount,
            'notifications' => $latestNotifications,
            'extra' => $extra,
            'server_time' => now()->format('H:i:s'),
        ]);
    }
}
