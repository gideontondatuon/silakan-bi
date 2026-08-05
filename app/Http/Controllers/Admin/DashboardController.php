<?php

namespace App\Http\Controllers\Admin;

use App\Enums\PemesananStatus;
use App\Http\Controllers\Controller;
use App\Models\Pemesanan;
use App\Models\Ruangan;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(): View
    {
        /*
        |--------------------------------------------------------------------------
        | Summary Card
        |--------------------------------------------------------------------------
        */

        $totalRuangan = Ruangan::count();

        $totalPemesanan = Pemesanan::count();

        $waitingApproval = Pemesanan::where(
            'status',
            PemesananStatus::PENDING->value
        )->count();

        $disetujui = Pemesanan::where(
            'status',
            PemesananStatus::DISETUJUI->value
        )->count();

        $ditolak = Pemesanan::where(
            'status',
            PemesananStatus::DITOLAK->value
        )->count();

        $pemesananBulanIni = Pemesanan::whereMonth(
            'created_at',
            now()->month
        )
            ->whereYear(
                'created_at',
                now()->year
            )
            ->count();


        /*
        |--------------------------------------------------------------------------
        | Agenda Hari Ini
        |--------------------------------------------------------------------------
        */

        $kegiatanHariIni = Pemesanan::with([
            'user',
            'ruangan',
        ])
            ->whereDate(
                'tanggal_kegiatan',
                today()
            )
            ->where(
                'status',
                PemesananStatus::DISETUJUI->value
            )
            ->orderBy('waktu_mulai')
            ->get();


        /*
        |--------------------------------------------------------------------------
        | Kegiatan Sedang Berlangsung
        |--------------------------------------------------------------------------
        */

        $kegiatanBerlangsung = Pemesanan::with([
            'user',
            'ruangan',
        ])
            ->isLive()
            ->orderBy('waktu_mulai')
            ->get();


        /*
        |--------------------------------------------------------------------------
        | Waiting Approval
        |--------------------------------------------------------------------------
        */

        $waitingList = Pemesanan::with([
            'user',
            'ruangan',
        ])
            ->pending()
            ->latest()
            ->limit(5)
            ->get();


        /*
        |--------------------------------------------------------------------------
        | Ruangan Terpopuler
        |--------------------------------------------------------------------------
        */

        $ruanganTerpopuler = Pemesanan::selectRaw(
            'ruangan_id, COUNT(*) as total'
        )
            ->with('ruangan')
            ->groupBy('ruangan_id')
            ->orderByDesc('total')
            ->limit(5)
            ->get();


        /*
        |--------------------------------------------------------------------------
        | Aktivitas Terbaru
        |--------------------------------------------------------------------------
        */

        $aktivitasTerbaru = Pemesanan::with([
            'user',
            'ruangan',
        ])
            ->latest()
            ->limit(5)
            ->get();


        /*
        |--------------------------------------------------------------------------
        | Agenda Mendatang (Upcoming Agendas)
        |--------------------------------------------------------------------------
        */

        $agendaMendatang = Pemesanan::with([
            'user',
            'ruangan',
            'layout',
        ])
            ->where('tanggal_kegiatan', '>=', today())
            ->where('status', PemesananStatus::DISETUJUI->value)
            ->orderBy('tanggal_kegiatan')
            ->orderBy('waktu_mulai')
            ->limit(10)
            ->get();


        /*
        |--------------------------------------------------------------------------
        | Chart Data Aggregation (Chart.js)
        |--------------------------------------------------------------------------
        */

        // 1. Tren Pemesanan 6 Bulan Terakhir
        $chartMonthlyLabels = [];
        $chartMonthlyData   = [];
        for ($i = 5; $i >= 0; $i--) {
            $date = now()->subMonths($i);
            $chartMonthlyLabels[] = $date->translatedFormat('M Y');
            $chartMonthlyData[]   = Pemesanan::whereMonth('tanggal_kegiatan', $date->month)
                ->whereYear('tanggal_kegiatan', $date->year)
                ->count();
        }

        // 2. Ruangan Terpopuler Chart
        $chartRuanganLabels = [];
        $chartRuanganData   = [];
        foreach ($ruanganTerpopuler as $rt) {
            $chartRuanganLabels[] = $rt->ruangan?->nama_ruangan ?? 'Ruangan ' . $rt->ruangan_id;
            $chartRuanganData[]   = $rt->total;
        }

        // 3. Distribusi Pemakaian per Unit Kerja
        $unitDistribution = Pemesanan::with('user')
            ->selectRaw('user_id, COUNT(*) as total')
            ->groupBy('user_id')
            ->orderByDesc('total')
            ->limit(5)
            ->get();

        $chartUnitLabels = [];
        $chartUnitData   = [];
        foreach ($unitDistribution as $ud) {
            $chartUnitLabels[] = $ud->user?->nama_unit ?? $ud->user?->name ?? 'User (Dihapus)';
            $chartUnitData[]   = $ud->total;
        }


        return view(
            'admin.dashboard',
            compact(
                'totalRuangan',
                'totalPemesanan',
                'waitingApproval',
                'disetujui',
                'ditolak',
                'pemesananBulanIni',
                'kegiatanHariIni',
                'kegiatanBerlangsung',
                'agendaMendatang',
                'waitingList',
                'ruanganTerpopuler',
                'aktivitasTerbaru',
                'chartMonthlyLabels',
                'chartMonthlyData',
                'chartRuanganLabels',
                'chartRuanganData',
                'chartUnitLabels',
                'chartUnitData'
            )
        );
    }
}