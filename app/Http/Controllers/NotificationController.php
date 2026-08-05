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



        $pemesananId =
            $notification
            ->data['pemesanan_id'];



        if(
            auth()->user()->role->value === 'admin'
        ) {


            return redirect()

                ->route(
                    'admin.approval.show',
                    $pemesananId
                );


        }



        return redirect()

            ->route(
                'pemesanan.show',
                $pemesananId
            );


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


}