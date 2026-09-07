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
        $countSemua = Pemesanan::count();

        return response()->json([
            'status' => 'success',
            'data' => [
                'items' => $pemesanan,
                'counts' => [
                    'pending' => $countPending,
                    'disetujui' => $countDisetujui,
                    'selesai' => $countSelesai,
                    'semua' => $countSemua,
                ],
            ],
        ]);
    }

    /**
     * Store new booking by Admin (instantly Disetujui).
     */
    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'ruangan_id' => 'required|exists:ruangan,id',
            'layout_ruangan_id' => 'nullable|exists:layout_ruangan,id',
            'tanggal_kegiatan' => 'required|date',
            'waktu_mulai' => ['required'],
            'waktu_selesai' => ['required', 'after:waktu_mulai'],
            'judul_kegiatan' => 'required|string|max:150',
            'user_id' => 'nullable|exists:users,id',
            'pic_kegiatan' => 'required|string|max:255',
            'jenis_pic' => 'required|in:Organik,Non Organik',
            'no_wa_pic' => 'nullable|string|max:20',
            'jumlah_tamu' => 'required|integer|min:1',
            'keterangan_layout' => 'nullable|string',
            'catatan_user' => 'nullable|string',
            'file_disposisi' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:5120',
        ]);

        $ruangan = \App\Models\Ruangan::findOrFail($validated['ruangan_id']);
        if ($validated['jumlah_tamu'] > $ruangan->kapasitas) {
            return response()->json([
                'status' => 'error',
                'message' => "Jumlah tamu ({$validated['jumlah_tamu']}) melebihi kapasitas maksimal ruangan {$ruangan->nama_ruangan} ({$ruangan->kapasitas} orang).",
            ], 422);
        }

        $bentrok = Pemesanan::where('ruangan_id', $validated['ruangan_id'])
            ->whereDate('tanggal_kegiatan', $validated['tanggal_kegiatan'])
            ->whereIn('status', [PemesananStatus::DISETUJUI->value, PemesananStatus::SELESAI->value])
            ->where(function ($query) use ($validated) {
                $query->where('waktu_mulai', '<', $validated['waktu_selesai'])
                      ->where('waktu_selesai', '>', $validated['waktu_mulai']);
            })
            ->exists();

        if ($bentrok) {
            return response()->json([
                'status' => 'error',
                'message' => 'Ruangan sudah memiliki agenda kegiatan pada tanggal dan jam tersebut.',
            ], 422);
        }

        $filePath = null;
        if ($request->hasFile('file_disposisi')) {
            $filePath = $request->file('file_disposisi')->store('disposisi', 'public');
        }

        do {
            $kode = 'SIL-' . now()->format('Ymd') . '-' . strtoupper(\Illuminate\Support\Str::random(5));
        } while (Pemesanan::where('kode_pemesanan', $kode)->exists());

        $ownerUserId = !empty($validated['user_id']) ? $validated['user_id'] : $request->user()->id;

        $pemesanan = Pemesanan::create([
            'kode_pemesanan' => $kode,
            'user_id' => $ownerUserId,
            'ruangan_id' => $validated['ruangan_id'],
            'layout_ruangan_id' => !empty($validated['layout_ruangan_id']) ? $validated['layout_ruangan_id'] : null,
            'tanggal_kegiatan' => $validated['tanggal_kegiatan'],
            'waktu_mulai' => $validated['waktu_mulai'],
            'waktu_selesai' => $validated['waktu_selesai'],
            'judul_kegiatan' => $validated['judul_kegiatan'],
            'pic_kegiatan' => $validated['pic_kegiatan'],
            'jenis_pic' => $validated['jenis_pic'],
            'no_wa_pic' => $validated['no_wa_pic'] ?? null,
            'jumlah_tamu' => $validated['jumlah_tamu'],
            'keterangan_layout' => $validated['keterangan_layout'] ?? null,
            'catatan_user' => $validated['catatan_user'] ?? null,
            'file_disposisi' => $filePath,
            'status' => PemesananStatus::DISETUJUI->value,
            'approved_by' => $request->user()->id,
            'approved_at' => now(),
            'catatan_admin' => 'Rapat dijadwalkan langsung oleh Administrator Sarpras.',
        ]);

        \App\Services\AuditLogService::create(
            'Menambahkan Rapat (Admin)',
            'Approval',
            "Admin menambahkan rapat {$pemesanan->kode_pemesanan} ({$pemesanan->judul_kegiatan}) di ruangan {$ruangan->nama_ruangan}."
        );

        return response()->json([
            'status' => 'success',
            'message' => "Rapat '{$pemesanan->judul_kegiatan}' berhasil dijadwalkan dan langsung berstatus Disetujui.",
            'data' => $pemesanan->load(['ruangan', 'layout', 'user']),
        ], 201);
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
