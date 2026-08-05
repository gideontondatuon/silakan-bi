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
            ->select(
                'id',
                'nama_layout',
                'kapasitas_layout'
            )
            ->get();


        return response()->json($layouts);

    }

    public function getLayoutsByRuangan($ruangan_id)
    {
        $layouts = LayoutRuangan::where('ruangan_id', $ruangan_id)->get(['id', 'nama_layout']);
        return response()->json($layouts);
    }

}



