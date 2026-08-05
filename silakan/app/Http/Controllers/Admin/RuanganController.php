<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;

use App\Models\Ruangan;
use App\Models\Fasilitas;

use App\Services\AuditLogService;

use Illuminate\Http\Request;


class RuanganController extends Controller
{


    public function index()
    {

        $ruangans = Ruangan::latest()
            ->paginate(10);


        return view(
            'admin.ruangan.index',
            compact('ruangans')
        );

    }



    public function create()
    {

        $fasilitas = Fasilitas::all();


        return view(
            'admin.ruangan.create',
            compact('fasilitas')
        );

    }



    public function store(
        Request $request
    )
    {


        $validated = $request->validate([

            'nama_ruangan' => [
                'required',
                'string',
                'max:255'
            ],

            'kapasitas' => [
                'required',
                'integer',
                'min:1'
            ],

            'status' => [
                'required',
                'in:aktif,nonaktif,perawatan'
            ],

            'lokasi' => [
                'required',
                'string',
                'max:255'
            ],

        ]);



        $ruangan = Ruangan::create(
            $validated
        );



        $ruangan->fasilitas()->sync(
            $request->fasilitas ?? []
        );



        AuditLogService::create(

            'Menambahkan Ruangan',

            'Master Ruangan',

            'Menambahkan ruangan '
            . $ruangan->nama_ruangan

        );



        return redirect()

            ->route('admin.ruangan.index')

            ->with(
                'success',
                'Ruangan berhasil ditambahkan'
            );

    }



    public function edit(
        Ruangan $ruangan
    )
    {

        $fasilitas = Fasilitas::all();


        return view(
            'admin.ruangan.edit',
            compact(
                'ruangan',
                'fasilitas'
            )
        );

    }



    public function update(
        Request $request,
        Ruangan $ruangan
    )
    {


        $validated = $request->validate([

            'nama_ruangan' => [

                'required',
                'string',
                'max:100'

            ],


            'lokasi' => [

                'required',
                'string',
                'max:150'

            ],


            'kapasitas' => [

                'required',
                'integer',
                'min:1'

            ],


            'deskripsi' => [

                'nullable',
                'string'

            ],

        ]);



        $ruangan->update(
            $validated
        );



        $ruangan->fasilitas()->sync(
            $request->fasilitas ?? []
        );



        AuditLogService::create(

            'Memperbarui Ruangan',

            'Master Ruangan',

            'Memperbarui data ruangan '
            . $ruangan->nama_ruangan

        );



        return redirect()

            ->route('admin.ruangan.index')

            ->with(
                'success',
                'Ruangan diperbarui'
            );

    }



    public function destroy(
        Ruangan $ruangan
    )
    {


        $namaRuangan =
            $ruangan->nama_ruangan;



        $ruangan->delete();



        AuditLogService::create(

            'Menghapus Ruangan',

            'Master Ruangan',

            'Menghapus ruangan '
            . $namaRuangan

        );



        return redirect()

            ->route('admin.ruangan.index')

            ->with(
                'success',
                'Ruangan dihapus'
            );

    }


}