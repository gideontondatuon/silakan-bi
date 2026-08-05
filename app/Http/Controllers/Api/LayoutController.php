<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Ruangan;
use App\Models\LayoutRuangan;

class LayoutController extends Controller
{
    public function index(Ruangan $ruangan)
    {
        $layouts = $ruangan
            ->layouts()
            ->select('layout_ruangan.id', 'layout_ruangan.nama_layout', 'layout_ruangan.kapasitas_layout')
            ->get();

        if ($layouts->isEmpty()) {
            $layouts = LayoutRuangan::select('id', 'nama_layout', 'kapasitas_layout')->get();
        }

        return response()->json($layouts);
    }

    public function getLayoutsByRuangan($ruangan_id)
    {
        $ruangan = Ruangan::find($ruangan_id);
        if (!$ruangan) {
            return response()->json(LayoutRuangan::select('id', 'nama_layout')->get());
        }

        $pivotLayouts = $ruangan->layouts()->select('layout_ruangan.id', 'layout_ruangan.nama_layout')->get();
        $directLayouts = LayoutRuangan::where('ruangan_id', $ruangan_id)->select('id', 'nama_layout')->get();

        $layouts = $pivotLayouts->merge($directLayouts)->unique('id')->values();

        // Fallback: If room has no specific layouts assigned, return all general layouts
        if ($layouts->isEmpty()) {
            $layouts = LayoutRuangan::select('id', 'nama_layout')->get();
        }

        return response()->json($layouts);
    }
}
