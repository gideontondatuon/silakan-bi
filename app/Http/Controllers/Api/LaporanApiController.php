<?php

namespace App\Http\Controllers\Api;

use App\Exports\PemesananExport;
use App\Http\Controllers\Controller;
use App\Models\Pemesanan;
use App\Models\Ruangan;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class LaporanApiController extends Controller
{
    /**
     * Get filtered report data with summary counters.
     */
    public function index(Request $request): JsonResponse
    {
        Pemesanan::markFinishedAgendas();

        $baseQuery = Pemesanan::query();

        if ($request->filled('tanggal_mulai')) {
            $baseQuery->whereDate('tanggal_kegiatan', '>=', $request->tanggal_mulai);
        }
        if ($request->filled('tanggal_selesai')) {
            $baseQuery->whereDate('tanggal_kegiatan', '<=', $request->tanggal_selesai);
        }
        if ($request->filled('ruangan_id')) {
            $baseQuery->where('ruangan_id', $request->ruangan_id);
        }
        if ($request->filled('user_id')) {
            $baseQuery->where('user_id', $request->user_id);
        }
        if ($request->filled('jenis_pic')) {
            $baseQuery->where('jenis_pic', $request->jenis_pic);
        }

        $totalPemesanan = (clone $baseQuery)->count();
        $totalDisetujui = (clone $baseQuery)->whereIn('status', [
            \App\Enums\PemesananStatus::DISETUJUI->value,
            \App\Enums\PemesananStatus::SELESAI->value,
        ])->count();
        $totalDitolak = (clone $baseQuery)->where('status', \App\Enums\PemesananStatus::DITOLAK->value)->count();

        $query = (clone $baseQuery)->with(['ruangan', 'user', 'layout', 'approver']);

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        } elseif ($request->boolean('disetujui_only')) {
            $query->whereIn('status', [
                \App\Enums\PemesananStatus::DISETUJUI->value,
                \App\Enums\PemesananStatus::SELESAI->value,
            ]);
        }

        $perPage = (int) $request->get('per_page', 15);
        $items = $request->boolean('all')
            ? $query->latest('tanggal_kegiatan')->get()
            : $query->latest('tanggal_kegiatan')->paginate($perPage);

        $ruanganList = Ruangan::orderBy('nama_ruangan')->get(['id', 'nama_ruangan']);
        $userList = User::orderBy('nama_unit')->get(['id', 'name', 'nama_unit']);

        return response()->json([
            'status' => 'success',
            'data' => [
                'summary' => [
                    'total' => $totalPemesanan,
                    'disetujui' => $totalDisetujui,
                    'ditolak' => $totalDitolak,
                ],
                'items' => $items,
                'filter_options' => [
                    'ruangan' => $ruanganList,
                    'users' => $userList,
                ],
            ],
        ]);
    }

    /**
     * Download Excel report.
     */
    public function exportExcel(Request $request): BinaryFileResponse
    {
        $query = Pemesanan::with(['ruangan', 'user', 'layout', 'approver']);

        if ($request->filled('tanggal_mulai')) {
            $query->whereDate('tanggal_kegiatan', '>=', $request->tanggal_mulai);
        }
        if ($request->filled('tanggal_selesai')) {
            $query->whereDate('tanggal_kegiatan', '<=', $request->tanggal_selesai);
        }
        if ($request->filled('ruangan_id')) {
            $query->where('ruangan_id', $request->ruangan_id);
        }
        if ($request->filled('user_id')) {
            $query->where('user_id', $request->user_id);
        }
        if ($request->filled('jenis_pic')) {
            $query->where('jenis_pic', $request->jenis_pic);
        }
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        } elseif ($request->boolean('disetujui_only')) {
            $query->whereIn('status', [
                \App\Enums\PemesananStatus::DISETUJUI->value,
                \App\Enums\PemesananStatus::SELESAI->value,
            ]);
        }

        $data = $query->latest('tanggal_kegiatan')->get();
        $statusSuffix = $request->status === 'Disetujui' || $request->boolean('disetujui_only') ? '_Disetujui' : '';
        $fileName = 'Laporan_Pemesanan_Ruangan' . $statusSuffix . '_' . date('Ymd_His') . '.xlsx';

        return Excel::download(new PemesananExport($data), $fileName);
    }
}
