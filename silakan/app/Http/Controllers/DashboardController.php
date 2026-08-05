<?php

namespace App\Http\Controllers;

use App\Enums\PemesananStatus;
use App\Models\Pemesanan;
use App\Models\Ruangan;
use Illuminate\View\View;


class DashboardController extends Controller
{

    public function index(): View
    {

        $totalRuangan = Ruangan::count();


        $waitingApproval = Pemesanan::where(
            'status',
            PemesananStatus::PENDING
        )
        ->count();



        $kegiatanHariIni = Pemesanan::whereDate(
            'tanggal_kegiatan',
            today()
        )
        ->whereIn(
            'status',
            [
                PemesananStatus::PENDING,
                PemesananStatus::DISETUJUI
            ]
        )
        ->count();



        $kegiatanBerlangsung = Pemesanan::approved()
            ->today()
            ->get()
            ->filter(
                fn ($item) =>
                $item->isLive()
            );



        $waitingList = Pemesanan::with([
                'user',
                'ruangan'
            ])
            ->pending()
            ->latest()
            ->limit(5)
            ->get();



        return view(
            'admin.dashboard',
            compact(
                'totalRuangan',
                'waitingApproval',
                'kegiatanHariIni',
                'kegiatanBerlangsung',
                'waitingList'
            )
        );

    }

}