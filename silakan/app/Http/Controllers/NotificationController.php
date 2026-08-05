<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;


class NotificationController extends Controller
{

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

}