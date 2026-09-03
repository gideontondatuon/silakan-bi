<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;

use App\Models\Ruangan;

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
        $layouts = \App\Models\LayoutRuangan::orderBy('nama_layout')->get();

        return view(
            'admin.ruangan.create',
            compact('layouts')
        );
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama_ruangan' => ['required', 'string', 'max:255'],
            'kapasitas' => ['required', 'integer', 'min:1'],
            'status' => ['required', 'in:aktif,nonaktif,perawatan'],
            'lokasi' => ['required', 'string', 'max:255'],
            'layouts' => ['nullable', 'array'],
            'layouts.*' => ['exists:layout_ruangan,id'],
        ]);

        $ruangan = Ruangan::create($validated);

        $ruangan->layouts()->sync($request->layouts ?? []);

        AuditLogService::create(
            'Menambahkan Ruangan',
            'Master Ruangan',
            'Menambahkan ruangan ' . $ruangan->nama_ruangan
        );

        return redirect()
            ->route('admin.ruangan.index')
            ->with('success', 'Ruangan berhasil ditambahkan');
    }

    public function edit(Ruangan $ruangan)
    {
        $layouts = \App\Models\LayoutRuangan::orderBy('nama_layout')->get();
        $ruangan->load(['layouts']);

        return view(
            'admin.ruangan.edit',
            compact('ruangan', 'layouts')
        );
    }

    public function update(Request $request, Ruangan $ruangan)
    {
        $validated = $request->validate([
            'nama_ruangan' => ['required', 'string', 'max:100'],
            'lokasi' => ['required', 'string', 'max:150'],
            'kapasitas' => ['required', 'integer', 'min:1'],
            'status' => ['required', 'in:aktif,nonaktif,perawatan'],
            'layouts' => ['nullable', 'array'],
            'layouts.*' => ['exists:layout_ruangan,id'],
        ]);

        $ruangan->update($validated);

        $ruangan->layouts()->sync($request->layouts ?? []);

        AuditLogService::create(
            'Memperbarui Ruangan',
            'Master Ruangan',
            'Memperbarui data ruangan ' . $ruangan->nama_ruangan
        );

        return redirect()
            ->route('admin.ruangan.index')
            ->with('success', 'Ruangan berhasil diperbarui.');
    }

    public function destroy(Ruangan $ruangan)
    {
        if ($ruangan->pemesanan()->exists()) {
            return back()->with('error', "Ruangan '{$ruangan->nama_ruangan}' tidak dapat dihapus karena memiliki riwayat pemesanan. Anda dapat mengubah status ruangan menjadi Nonaktif.");
        }

        $namaRuangan = $ruangan->nama_ruangan;

        $ruangan->delete();

        AuditLogService::create(
            'Menghapus Ruangan',
            'Master Ruangan',
            'Menghapus ruangan ' . $namaRuangan
        );

        return redirect()
            ->route('admin.ruangan.index')
            ->with('success', 'Ruangan berhasil dihapus.');
    }


}