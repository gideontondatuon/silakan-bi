<?php

namespace App\Http\Controllers;

use App\Actions\CreatePemesananAction;
use App\Http\Requests\StorePemesananRequest;
use App\Models\Pemesanan;
use App\Models\Ruangan;

use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;


class PemesananController extends Controller
{

    public function create(): View
    {
        $ruangan = Ruangan::where(
                'status',
                'aktif'
            )
            ->orderBy(
                'nama_ruangan'
            )
            ->get();


        return view(
            'pemesanan.create',
            compact('ruangan')
        );
    }



    public function store(
        StorePemesananRequest $request,
        CreatePemesananAction $action
    ): RedirectResponse {


        try {


            $action->execute(
                $request->validated()
            );


            return redirect()
                ->route(
                    'pemesanan.index'
                )
                ->with(
                    'success',
                    'Pemesanan berhasil dibuat dan menunggu persetujuan admin.'
                );


        } catch (\Exception $e) {


            return back()
                ->withInput()
                ->with(
                    'error',
                    $e->getMessage()
                );

        }

    }



    public function index(): View
    {

        $pemesanan = Pemesanan::with([
                'ruangan',
                'layout'
            ])
            ->where(
                'user_id',
                auth()->id()
            )
            ->latest()
            ->paginate(10);



        return view(
            'pemesanan.index',
            compact('pemesanan')
        );

    }

    public function show(
        Pemesanan $pemesanan
    ): View {

        abort_if(
            $pemesanan->user_id !== auth()->id(),
            403
        );


        $pemesanan->load([
            'ruangan',
            'layout'
        ]);


        return view(
            'pemesanan.show',
            compact('pemesanan')
        );

    }

}