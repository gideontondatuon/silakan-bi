<?php

namespace App\Http\Controllers\Api;

use App\Enums\PemesananStatus;
use App\Http\Controllers\Controller;
use App\Models\Pemesanan;
use App\Models\Ruangan;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class DashboardApiController extends Controller
{
    /**
     * Get User Dashboard data.
     */
    public function user(Request $request): JsonResponse
    {
        Pemesanan::markFinishedAgendas();

        $userId = $request->user()->id;

        $totalPemesanan = Pemesanan::where('user_id', $userId)->count();
        $pendingPemesanan = Pemesanan::where('user_id', $userId)->pending()->count();
        $approvedPemesanan = Pemesanan::where('user_id', $userId)->approved()->count();
        $upcomingPemesanan = Pemesanan::where('user_id', $userId)
            ->approved()
            ->upcoming()
            ->count();

        $pemesananTerbaru = Pemesanan::with(['ruangan', 'layout'])
            ->where('user_id', $userId)
            ->latest()
            ->take(5)
            ->get();

        $kegiatanHariIni = Pemesanan::with(['ruangan', 'user'])
            ->approved()
            ->whereDate('tanggal_kegiatan', now()->toDateString())
            ->orderBy('waktu_mulai')
            ->take(5)
            ->get();

        $kegiatanBerlangsung = Pemesanan::with(['ruangan', 'user', 'layout'])
            ->isLive()
            ->orderBy('waktu_mulai')
            ->get();

        return response()->json([
            'status' => 'success',
            'data' => [
                'stats' => [
                    'total' => $totalPemesanan,
                    'pending' => $pendingPemesanan,
                    'approved' => $approvedPemesanan,
                    'upcoming' => $upcomingPemesanan,
                ],
                'pemesanan_terbaru' => $pemesananTerbaru,
                'kegiatan_hari_ini' => $kegiatanHariIni,
                'kegiatan_berlangsung' => $kegiatanBerlangsung,
            ],
        ]);
    }

    /**
     * Get Admin Dashboard data.
     */
    public function admin(Request $request): JsonResponse
    {
        Pemesanan::markFinishedAgendas();

        $totalRuangan = Ruangan::count();
        $totalPemesanan = Pemesanan::count();
        $waitingApproval = Pemesanan::where('status', PemesananStatus::PENDING->value)->count();
        $disetujui = Pemesanan::where('status', PemesananStatus::DISETUJUI->value)->count();
        $ditolak = Pemesanan::where('status', PemesananStatus::DITOLAK->value)->count();
        $pemesananBulanIni = Pemesanan::whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->count();

        $kegiatanHariIni = Pemesanan::with(['user', 'ruangan'])
            ->whereDate('tanggal_kegiatan', today())
            ->where('status', PemesananStatus::DISETUJUI->value)
            ->orderBy('waktu_mulai')
            ->get();

        $kegiatanBerlangsung = Pemesanan::with(['user', 'ruangan', 'layout'])
            ->isLive()
            ->orderBy('waktu_mulai')
            ->get();

        $waitingList = Pemesanan::with(['user', 'ruangan'])
            ->pending()
            ->latest()
            ->limit(5)
            ->get();

        $ruanganTerpopuler = Pemesanan::selectRaw('ruangan_id, COUNT(*) as total')
            ->with('ruangan')
            ->groupBy('ruangan_id')
            ->orderByDesc('total')
            ->limit(5)
            ->get();

        $aktivitasTerbaru = Pemesanan::with(['user', 'ruangan'])
            ->latest()
            ->limit(5)
            ->get();

        $agendaMendatang = Pemesanan::with(['user', 'ruangan', 'layout'])
            ->approved()
            ->upcoming()
            ->orderBy('tanggal_kegiatan')
            ->orderBy('waktu_mulai')
            ->limit(10)
            ->get();

        // Chart 1: Tren Bulanan 6 Bulan Terakhir
        $chartMonthlyLabels = [];
        $chartMonthlyData   = [];
        for ($i = 5; $i >= 0; $i--) {
            $date = now()->subMonths($i);
            $chartMonthlyLabels[] = $date->translatedFormat('M Y');
            $chartMonthlyData[]   = Pemesanan::whereMonth('tanggal_kegiatan', $date->month)
                ->whereYear('tanggal_kegiatan', $date->year)
                ->count();
        }

        // Chart 2: Ruangan Terpopuler
        $chartRuanganLabels = [];
        $chartRuanganData   = [];
        foreach ($ruanganTerpopuler as $rt) {
            $chartRuanganLabels[] = $rt->ruangan?->nama_ruangan ?? 'Ruangan ' . $rt->ruangan_id;
            $chartRuanganData[]   = $rt->total;
        }

        // Chart 3: Distribusi Unit Kerja
        $unitDistribution = Pemesanan::with('user')
            ->selectRaw('user_id, COUNT(*) as total')
            ->groupBy('user_id')
            ->orderByDesc('total')
            ->limit(5)
            ->get();

        $chartUnitLabels = [];
        $chartUnitData   = [];
        foreach ($unitDistribution as $ud) {
            $chartUnitLabels[] = $ud->user?->nama_unit ?? $ud->user?->name ?? 'User';
            $chartUnitData[]   = $ud->total;
        }

        return response()->json([
            'status' => 'success',
            'data' => [
                'stats' => [
                    'total_ruangan' => $totalRuangan,
                    'total_pemesanan' => $totalPemesanan,
                    'waiting_approval' => $waitingApproval,
                    'disetujui' => $disetujui,
                    'ditolak' => $ditolak,
                    'pemesanan_bulan_ini' => $pemesananBulanIni,
                ],
                'kegiatan_hari_ini' => $kegiatanHariIni,
                'kegiatan_berlangsung' => $kegiatanBerlangsung,
                'agenda_mendatang' => $agendaMendatang,
                'waiting_list' => $waitingList,
                'ruangan_terpopuler' => $ruanganTerpopuler,
                'aktivitas_terbaru' => $aktivitasTerbaru,
                'charts' => [
                    'monthly' => [
                        'labels' => $chartMonthlyLabels,
                        'data' => $chartMonthlyData,
                    ],
                    'popular_rooms' => [
                        'labels' => $chartRuanganLabels,
                        'data' => $chartRuanganData,
                    ],
                    'unit_distribution' => [
                        'labels' => $chartUnitLabels,
                        'data' => $chartUnitData,
                    ],
                ],
            ],
        ]);
    }

    /**
     * Get live activities data (Kegiatan Berlangsung).
     */
    public function kegiatanBerlangsung(): JsonResponse
    {
        Pemesanan::markFinishedAgendas();

        $kegiatan = Pemesanan::with(['ruangan', 'layout', 'user'])
            ->isLive()
            ->orderBy('waktu_mulai')
            ->get();

        return response()->json([
            'status' => 'success',
            'data' => $kegiatan,
        ]);
    }
}

