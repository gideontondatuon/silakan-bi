<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;

use App\Models\LayoutRuangan;
use App\Models\Ruangan;

use App\Services\AuditLogService;

use Illuminate\Http\Request;


class LayoutRuanganController extends Controller
{


    public function index()
    {

        $layouts = LayoutRuangan::with('ruangan')
            ->latest()
            ->paginate(10);


        return view(
            'admin.layout.index',
            compact('layouts')
        );

    }



    public function create()
    {

        $ruangan = Ruangan::where(
                'status',
                'aktif'
            )
            ->get();


        return view(
            'admin.layout.create',
            compact('ruangan')
        );

    }



    public function store(
        Request $request
    )
    {


        $validated = $request->validate([

            'ruangan_id' => [

                'required',

                'exists:ruangan,id'

            ],


            'nama_layout' => [

                'required',

                'string',

                'max:255'

            ],


            'kapasitas_layout' => [

                'required',

                'integer',

                'min:1'

            ],

        ]);



        $layout = LayoutRuangan::create(
            $validated
        );



        AuditLogService::create(

            'Menambahkan Layout',

            'Master Layout',

            'Menambahkan layout '
            . $layout->nama_layout

        );



        return redirect()

            ->route('admin.layout.index')

            ->with(
                'success',
                'Layout berhasil ditambahkan'
            );

    }



    public function edit(
        LayoutRuangan $layout
    )
    {


        $ruangan = Ruangan::where(
                'status',
                'aktif'
            )
            ->get();


        return view(
            'admin.layout.edit',
            compact(
                'layout',
                'ruangan'
            )
        );

    }



    public function update(
        Request $request,
        LayoutRuangan $layout
    )
    {


        $validated = $request->validate([

            'ruangan_id' => [

                'required',

                'exists:ruangan,id'

            ],


            'nama_layout' => [

                'required',

                'string',

                'max:255'

            ],


            'kapasitas_layout' => [

                'required',

                'integer',

                'min:1'

            ],

        ]);



        $layout->update(
            $validated
        );



        AuditLogService::create(

            'Memperbarui Layout',

            'Master Layout',

            'Memperbarui layout '
            . $layout->nama_layout

        );



        return redirect()

            ->route('admin.layout.index')

            ->with(
                'success',
                'Layout berhasil diperbarui'
            );


    }



    public function destroy(
        LayoutRuangan $layout
    )
    {


        $namaLayout =
            $layout->nama_layout;



        $layout->delete();



        AuditLogService::create(

            'Menghapus Layout',

            'Master Layout',

            'Menghapus layout '
            . $namaLayout

        );



        return redirect()

            ->route('admin.layout.index')

            ->with(
                'success',
                'Layout berhasil dihapus'
            );

    }


}