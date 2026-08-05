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


        $notification =
            auth()
            ->user()
            ->notifications()
            ->findOrFail($id);



        $notification->markAsRead();



        $pemesananId = $notification->data['pemesanan_id'] ?? null;

        if (!$pemesananId) {
            return redirect()->route('notifications.index');
        }

        if (auth()->user()->role->value === 'admin') {
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
}