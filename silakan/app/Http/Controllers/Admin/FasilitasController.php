<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;

use App\Models\Fasilitas;

use App\Services\AuditLogService;

use Illuminate\Http\Request;


class FasilitasController extends Controller
{


    public function index()
    {

        $fasilitas = Fasilitas::latest()
            ->paginate(10);


        return view(
            'admin.fasilitas.index',
            compact('fasilitas')
        );

    }



    public function create()
    {

        return view(
            'admin.fasilitas.create'
        );

    }



    public function store(
        Request $request
    )
    {


        $validated = $request->validate([

            'nama_fasilitas' => [

                'required',

                'string',

                'max:255'

            ],

        ]);



        $fasilitas = Fasilitas::create(
            $validated
        );



        AuditLogService::create(

            'Menambahkan Fasilitas',

            'Master Fasilitas',

            'Menambahkan fasilitas '
            . $fasilitas->nama_fasilitas

        );



        return redirect()

            ->route('admin.fasilitas.index')

            ->with(
                'success',
                'Fasilitas berhasil ditambahkan'
            );

    }



    public function edit(
        Fasilitas $fasilita
    )
    {

        return view(

            'admin.fasilitas.edit',

            [

                'fasilitas' => $fasilita

            ]

        );

    }



    public function update(
        Request $request,
        Fasilitas $fasilita
    )
    {


        $validated = $request->validate([

            'nama_fasilitas' => [

                'required',

                'string',

                'max:255'

            ],

        ]);



        $fasilita->update(
            $validated
        );



        AuditLogService::create(

            'Memperbarui Fasilitas',

            'Master Fasilitas',

            'Memperbarui fasilitas '
            . $fasilita->nama_fasilitas

        );



        return redirect()

            ->route('admin.fasilitas.index')

            ->with(
                'success',
                'Fasilitas berhasil diperbarui'
            );

    }



    public function destroy(
        Fasilitas $fasilita
    )
    {


        $namaFasilitas =
            $fasilita->nama_fasilitas;



        $fasilita->delete();



        AuditLogService::create(

            'Menghapus Fasilitas',

            'Master Fasilitas',

            'Menghapus fasilitas '
            . $namaFasilitas

        );



        return redirect()

            ->route('admin.fasilitas.index')

            ->with(
                'success',
                'Fasilitas berhasil dihapus'
            );

    }


}