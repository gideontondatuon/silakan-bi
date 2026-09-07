<?php

namespace App\Http\Controllers\Api;

use App\Actions\ApprovePemesananAction;
use App\Actions\RejectPemesananAction;
use App\Enums\PemesananStatus;
use App\Http\Controllers\Controller;
use App\Models\Pemesanan;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class AdminApprovalApiController extends Controller
{
    /**
     * List bookings for admin approval with tabs and search.
     */
    public function index(Request $request): JsonResponse
    {
        Pemesanan::markFinishedAgendas();

        $tab = $request->get('tab', 'pending');

        $query = Pemesanan::with([
            'user',
            'ruangan',
            'layout',
            'approver',
        ]);

        // Filter by Tab
        if ($tab === 'pending') {
            $query->where('status', PemesananStatus::PENDING->value);
        } elseif ($tab === 'disetujui') {
            $query->where('status', PemesananStatus::DISETUJUI->value);
        } elseif ($tab === 'selesai') {
            $query->where('status', PemesananStatus::SELESAI->value);
        }

        // Search Filter
        if ($request->filled('q')) {
            $search = $request->q;
            $query->where(function ($q) use ($search) {
                $q->where('kode_pemesanan', 'like', "%{$search}%")
                  ->orWhere('judul_kegiatan', 'like', "%{$search}%")
                  ->orWhere('pic_kegiatan', 'like', "%{$search}%")
                  ->orWhereHas('user', function ($qu) use ($search) {
                      $qu->where('name', 'like', "%{$search}%")
                         ->orWhere('nama_unit', 'like', "%{$search}%");
                  })
                  ->orWhereHas('ruangan', function ($qr) use ($search) {
                      $qr->where('nama_ruangan', 'like', "%{$search}%");
                  });
            });
        }

        // Ruangan Filter
        if ($request->filled('ruangan_id')) {
            $query->where('ruangan_id', $request->ruangan_id);
        }

        // Tanggal Filter
        if ($request->filled('tanggal')) {
            $query->whereDate('tanggal_kegiatan', $request->tanggal);
        }

        $pemesanan = $query->latest('tanggal_kegiatan')->paginate($request->get('per_page', 10));

        // Unread/Pending count badges
        $countPending = Pemesanan::where('status', PemesananStatus::PENDING->value)->count();
        $countDisetujui = Pemesanan::where('status', PemesananStatus::DISETUJUI->value)->count();
        $countSelesai = Pemesanan::where('status', PemesananStatus::SELESAI->value)->count();

        return response()->json([
            'status' => 'success',
            'data' => [
                'items' => $pemesanan,
                'counts' => [
                    'pending' => $countPending,
                    'disetujui' => $countDisetujui,
                    'selesai' => $countSelesai,
                ],
            ],
        ]);
    }

    /**
     * Show booking details.
     */
    public function show(Pemesanan $pemesanan): JsonResponse
    {
        $pemesanan->load(['ruangan', 'layout', 'user', 'approver', 'rejector', 'canceller', 'history.user']);

        return response()->json([
            'status' => 'success',
            'data' => $pemesanan,
        ]);
    }

    /**
     * Approve a booking.
     */
    public function approve(
        Request $request,
        Pemesanan $pemesanan,
        ApprovePemesananAction $action
    ): JsonResponse {
        try {
            $catatanAdmin = $request->input('catatan_admin');
            $pemesanan = $action->execute($pemesanan, $request->user(), $catatanAdmin);

            return response()->json([
                'status' => 'success',
                'message' => 'Pemesanan ' . $pemesanan->kode_pemesanan . ' berhasil disetujui.',
                'data' => $pemesanan->load(['ruangan', 'layout', 'approver']),
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage(),
            ], 422);
        }
    }

    /**
     * Reject a booking.
     */
    public function reject(
        Request $request,
        Pemesanan $pemesanan,
        RejectPemesananAction $action
    ): JsonResponse {
        $request->validate([
            'alasan_penolakan' => 'required|string|max:1000',
        ]);

        try {
            $alasan = $request->alasan_penolakan;
            $pemesanan = $action->execute($pemesanan, $request->user(), $alasan);

            return response()->json([
                'status' => 'success',
                'message' => 'Pemesanan ' . $pemesanan->kode_pemesanan . ' berhasil ditolak.',
                'data' => $pemesanan->load(['ruangan', 'layout', 'rejector']),
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage(),
            ], 422);
        }
    }

    /**
     * Selesai Awal by admin.
     */
    public function selesaiAwal(Pemesanan $pemesanan): JsonResponse
    {
        $statusVal = is_object($pemesanan->status) ? $pemesanan->status->value : $pemesanan->status;
        if ($statusVal !== PemesananStatus::DISETUJUI->value) {
            return response()->json([
                'status' => 'error',
                'message' => 'Hanya kegiatan berstatus Disetujui yang dapat diselesaikan lebih awal.',
            ], 422);
        }

        $currentTime = Carbon::now('Asia/Makassar')->format('H:i:s');

        $pemesanan->update([
            'status' => PemesananStatus::SELESAI->value,
            'waktu_selesai' => $currentTime,
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'Kegiatan berhasil diselesaikan lebih awal pada ' . $currentTime . ' WITA.',
            'data' => $pemesanan,
        ]);
    }

    /**
     * Delete booking.
     */
    public function destroy(Pemesanan $pemesanan): JsonResponse
    {
        $kode = $pemesanan->kode_pemesanan;
        $pemesanan->delete();

        return response()->json([
            'status' => 'success',
            'message' => "Pemesanan {$kode} berhasil dihapus dari sistem.",
        ]);
    }
}
