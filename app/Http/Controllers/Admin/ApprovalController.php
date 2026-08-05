<?php

namespace App\Http\Controllers\Admin;


use App\Actions\ApprovePemesananAction;
use App\Actions\RejectPemesananAction;

use App\Http\Controllers\Controller;

use App\Models\Pemesanan;

use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;



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
            ->paginate(10, ['*'], 'pending_page');

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
            'ruangan',
            'layout'
        ]);



        return view(
            'admin.approval.show',
            compact('pemesanan')
        );

    }





    public function approve(
        Request $request,
        Pemesanan $pemesanan,
        ApprovePemesananAction $action
    ): RedirectResponse {
        try {
            $action->execute(
                $pemesanan,
                auth()->user(),
                $request->catatan_admin
            );

            return redirect()
                ->route('admin.approval.index')
                ->with('success', 'Pemesanan berhasil disetujui.');
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }
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

                $request->alasan_penolakan,

                auth()->user()

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
            return back()->with('error', $e->getMessage());
        }
    }
}