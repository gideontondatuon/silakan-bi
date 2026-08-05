<?php

namespace App\Http\Controllers\Admin;

use App\Actions\RejectPemesananAction;
use App\Enums\PemesananStatus;
use App\Http\Controllers\Controller;
use App\Models\Pemesanan;
use App\Notifications\StatusPemesananNotification;

use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use App\Services\AuditLogService;


class ApprovalController extends Controller
{

    public function index(): View
    {

        $pemesanan = Pemesanan::with([
                'user',
                'ruangan',
                'layout'
            ])
            ->pending()
            ->latest()
            ->paginate(10);


        return view(
            'admin.approval.index',
            compact('pemesanan')
        );

    }


    public function show(
        Pemesanan $pemesanan
    ): View {

        $pemesanan->load([
            'user',
            'ruangan.fasilitas',
            'layout'
        ]);


        return view(
            'admin.approval.show',
            compact('pemesanan')
        );

    }



    public function approve(
        Pemesanan $pemesanan
    ): RedirectResponse {


        $konflik = Pemesanan::conflict([

            'ruangan_id' =>
                $pemesanan->ruangan_id,

            'tanggal_kegiatan' =>
                $pemesanan->tanggal_kegiatan,

            'waktu_mulai' =>
                $pemesanan->waktu_mulai,

            'waktu_selesai' =>
                $pemesanan->waktu_selesai,

        ])
        ->where(
            'id',
            '!=',
            $pemesanan->id
        )
        ->exists();



        if ($konflik) {


            return back()

                ->with(
                    'error',
                    'Ruangan sudah digunakan pada waktu tersebut.'
                );

        }



        $pemesanan->update([

            'status' =>
                PemesananStatus::DISETUJUI,

            'approved_by' =>
                auth()->id(),

            'approved_at' =>
                now(),

        ]);

        AuditLogService::create(

            'Menyetujui Pemesanan',

            'Approval',

            'Menyetujui pemesanan '
            . $pemesanan->kode_pemesanan

        );



        $pemesanan->user->notify(

            new StatusPemesananNotification(

                'Pemesanan Disetujui',

                'Pemesanan '
                . $pemesanan->kode_pemesanan
                . ' telah disetujui admin.',

                $pemesanan->id

            )

        );



        return redirect()

            ->route(
                'admin.approval.index'
            )

            ->with(
                'success',
                'Pemesanan berhasil disetujui.'
            );

    }



      public function reject(

        Request $request,

        Pemesanan $pemesanan,

        RejectPemesananAction $action

    ): RedirectResponse {


        $request->validate([

            'alasan_penolakan' => [

                'required',

                'string',

                'max:500'

            ]

        ]);



        try {


            $action->execute(

                $pemesanan,

                $request->alasan_penolakan

            );

            AuditLogService::create(

                'Menolak Pemesanan',

                'Approval',

                'Menolak pemesanan '
                . $pemesanan->kode_pemesanan
                . '. Alasan: '
                . $request->alasan_penolakan

            );



            $pemesanan->user->notify(

                new StatusPemesananNotification(

                    'Pemesanan Ditolak',

                    'Pemesanan '
                    . $pemesanan->kode_pemesanan
                    . ' ditolak admin. Alasan: '
                    . $request->alasan_penolakan,

                    $pemesanan->id

                )

            );



            return redirect()

                ->route(
                    'admin.approval.index'
                )

                ->with(

                    'success',

                    'Pemesanan berhasil ditolak.'

                );


        } catch (\Exception $e) {


            return back()

                ->with(

                    'error',

                    $e->getMessage()

                );

        }

    }

}