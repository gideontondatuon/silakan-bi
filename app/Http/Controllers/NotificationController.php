<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;


class NotificationController extends Controller
{


    public function index()
    {

        $notifications =
            auth()
            ->user()
            ->notifications()
            ->latest()
            ->paginate(10);



        return view(
            'notifications.index',
            compact('notifications')
        );

    }




    public function read(
        string $id
    ): RedirectResponse {
        $notification = auth()
            ->user()
            ->notifications()
            ->findOrFail($id);

        $notification->markAsRead();

        $pemesananId = $notification->data['pemesanan_id'] ?? null;

        if (!$pemesananId) {
            return redirect()->route('notifications.index');
        }

        if (!\App\Models\Pemesanan::where('id', $pemesananId)->exists()) {
            return redirect()->route('notifications.index')
                ->with('error', 'Pemesanan terkait sudah tidak ditemukan dalam sistem atau telah dihapus.');
        }

        $roleVal = is_object(auth()->user()->role) ? auth()->user()->role->value : auth()->user()->role;

        if ($roleVal === 'admin') {
            return redirect()->route('admin.approval.show', $pemesananId);
        }

        return redirect()->route('pemesanan.show', $pemesananId);
    }

    public function readAll(): RedirectResponse
    {

        auth()
        ->user()
        ->unreadNotifications()
        ->update([
            'read_at' => now()
        ]);


        return redirect()
            ->route('notifications.index')
            ->with(
                'success',
                'Semua notifikasi telah dibaca.'
            );

    }

    public function destroy(string $id): RedirectResponse
    {
        $notification = auth()->user()->notifications()->findOrFail($id);
        $notification->delete();

        return back()->with('success', 'Notifikasi berhasil dihapus.');
    }

    public function destroyAll(): RedirectResponse
    {
        auth()->user()->notifications()->delete();

        return redirect()
            ->route('notifications.index')
            ->with('success', 'Semua riwayat notifikasi berhasil dihapus.');
    }

    /**
     * Silent real-time background synchronization endpoint.
     * Mengembalikan jumlah notifikasi dan status pemesanan terkini tanpa reload halaman.
     */
    public function liveSync(\Illuminate\Http\Request $request): \Illuminate\Http\JsonResponse
    {
        $user = auth()->user();
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
                    'url' => route('notification.read', $n->id),
                    'created_at' => $n->created_at->toIso8601String(),
                ];
            });

        $roleVal = is_object($user->role) ? $user->role->value : $user->role;
        $isAdmin = ($roleVal === 'admin');

        $extra = [
            'is_admin' => $isAdmin,
        ];

        if ($isAdmin) {
            $extra['count_pending'] = \App\Models\Pemesanan::where('status', \App\Enums\PemesananStatus::PENDING)->count();
            $extra['count_disetujui'] = \App\Models\Pemesanan::where('status', \App\Enums\PemesananStatus::DISETUJUI)->count();
            $extra['count_total'] = \App\Models\Pemesanan::count();
            $extra['latest_booking_id'] = \App\Models\Pemesanan::latest('id')->value('id') ?? 0;
            $extra['latest_updated_at'] = \App\Models\Pemesanan::latest('updated_at')->value('updated_at')?->timestamp ?? 0;
        } else {
            $extra['count_my_pending'] = \App\Models\Pemesanan::where('user_id', $user->id)
                ->where('status', \App\Enums\PemesananStatus::PENDING)
                ->count();
            $extra['latest_my_booking_id'] = \App\Models\Pemesanan::where('user_id', $user->id)->latest('id')->value('id') ?? 0;
            $extra['latest_my_updated_at'] = \App\Models\Pemesanan::where('user_id', $user->id)->latest('updated_at')->value('updated_at')?->timestamp ?? 0;
        }

        return response()->json([
            'status' => 'success',
            'unread_count' => $unreadCount,
            'notifications' => $latestNotifications,
            'extra' => $extra,
            'server_time' => now()->isoFormat('HH:mm:ss'),
        ]);
    }
}