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
        return view('admin.layout.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama_layout' => ['required', 'string', 'max:255'],
            'ruangan_id' => ['nullable', 'exists:ruangan,id'],
        ]);

        $layout = LayoutRuangan::create($validated);

        AuditLogService::create(
            'Menambahkan Layout',
            'Master Layout',
            'Menambahkan layout ' . $layout->nama_layout
        );

        return redirect()
            ->route('admin.layout.index')
            ->with('success', 'Master Layout berhasil ditambahkan');
    }

    public function edit(LayoutRuangan $layout)
    {
        return view('admin.layout.edit', compact('layout'));
    }

    public function update(Request $request, LayoutRuangan $layout)
    {
        $validated = $request->validate([
            'nama_layout' => ['required', 'string', 'max:255'],
            'ruangan_id' => ['nullable', 'exists:ruangan,id'],
        ]);

        $layout->update($validated);



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