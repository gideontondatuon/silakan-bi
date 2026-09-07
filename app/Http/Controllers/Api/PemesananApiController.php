<?php

namespace App\Http\Controllers\Api;

use App\Actions\CancelPemesananAction;
use App\Actions\CreatePemesananAction;
use App\Enums\PemesananStatus;
use App\Http\Controllers\Controller;
use App\Http\Requests\StorePemesananRequest;
use App\Models\Pemesanan;
use App\Models\Ruangan;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class PemesananApiController extends Controller
{
    /**
     * Get user's bookings with pagination and filters.
     */
    public function index(Request $request): JsonResponse
    {
        Pemesanan::markFinishedAgendas();

        $query = Pemesanan::with(['ruangan', 'layout'])
            ->where('user_id', $request->user()->id);

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('q')) {
            $q = $request->q;
            $query->where(function ($sub) use ($q) {
                $sub->where('kode_pemesanan', 'like', "%{$q}%")
                    ->orWhere('judul_kegiatan', 'like', "%{$q}%")
                    ->orWhere('pic_kegiatan', 'like', "%{$q}%");
            });
        }

        $pemesanan = $query->latest()->paginate($request->get('per_page', 10));

        return response()->json([
            'status' => 'success',
            'data' => $pemesanan,
        ]);
    }

    /**
     * Store new booking using CreatePemesananAction and StorePemesananRequest.
     */
    public function store(
        StorePemesananRequest $request,
        CreatePemesananAction $action
    ): JsonResponse {
        try {
            $data = $request->validated();
            if ($request->hasFile('file_disposisi')) {
                $data['file_disposisi'] = $request->file('file_disposisi');
            }

            $pemesanan = $action->execute(
                $data,
                $request->user()
            );

            return response()->json([
                'status' => 'success',
                'message' => 'Pemesanan berhasil dibuat dan menunggu persetujuan admin.',
                'data' => $pemesanan->load(['ruangan', 'layout']),
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage(),
            ], 422);
        }
    }

    /**
     * Get details of a booking.
     */
    public function show(Request $request, Pemesanan $pemesanan): JsonResponse
    {
        $user = $request->user();
        $roleValue = is_object($user->role) ? $user->role->value : (string) $user->role;

        if ($roleValue !== 'admin' && $pemesanan->user_id !== $user->id) {
            return response()->json([
                'status' => 'error',
                'message' => 'Akses ditolak.',
            ], 403);
        }

        $pemesanan->load(['ruangan', 'layout', 'user', 'approver', 'history.user']);

        return response()->json([
            'status' => 'success',
            'data' => $pemesanan,
        ]);
    }

    /**
     * Cancel a booking by the user.
     */
    public function cancel(
        Request $request,
        Pemesanan $pemesanan,
        CancelPemesananAction $action
    ): JsonResponse {
        if ($pemesanan->user_id !== $request->user()->id) {
            return response()->json([
                'status' => 'error',
                'message' => 'Akses ditolak.',
            ], 403);
        }

        try {
            $pemesanan = $action->execute($pemesanan, $request->user());

            return response()->json([
                'status' => 'success',
                'message' => 'Pemesanan berhasil dibatalkan.',
                'data' => $pemesanan,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage(),
            ], 422);
        }
    }

    /**
     * Finish a booking earlier.
     */
    public function selesaiAwal(Request $request, Pemesanan $pemesanan): JsonResponse
    {
        $user = $request->user();
        $roleValue = is_object($user->role) ? $user->role->value : (string) $user->role;

        if ($roleValue !== 'admin' && $pemesanan->user_id !== $user->id) {
            return response()->json([
                'status' => 'error',
                'message' => 'Akses ditolak.',
            ], 403);
        }

        $statusVal = is_object($pemesanan->status) ? $pemesanan->status->value : $pemesanan->status;
        if ($statusVal !== PemesananStatus::DISETUJUI->value) {
            return response()->json([
                'status' => 'error',
                'message' => 'Hanya kegiatan berstatus Disetujui yang dapat diselesaikan lebih awal.',
            ], 422);
        }

        if (!$pemesanan->tanggal_kegiatan || !$pemesanan->tanggal_kegiatan->isToday()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Hanya kegiatan yang berlangsung hari ini yang dapat diselesaikan lebih awal.',
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
     * Real-time conflict checking API.
     */
    public function checkConflict(Request $request): JsonResponse
    {
        $request->validate([
            'ruangan_id'       => 'required|exists:ruangan,id',
            'tanggal_kegiatan' => 'required|date',
            'waktu_mulai'      => 'required|date_format:H:i',
            'waktu_selesai'    => 'required|date_format:H:i|after:waktu_mulai',
            'exclude_id'       => 'nullable|integer',
        ]);

        $ruanganId  = $request->ruangan_id;
        $tanggal    = $request->tanggal_kegiatan;
        $mulai      = $request->waktu_mulai;
        $selesai    = $request->waktu_selesai;
        $excludeId  = $request->exclude_id;

        $conflicts = Pemesanan::with(['user', 'ruangan'])
            ->where('ruangan_id', $ruanganId)
            ->whereDate('tanggal_kegiatan', $tanggal)
            ->whereIn('status', [
                PemesananStatus::PENDING->value,
                PemesananStatus::DISETUJUI->value,
            ])
            ->when($excludeId, fn($q) => $q->where('id', '!=', $excludeId))
            ->where(function ($query) use ($mulai, $selesai) {
                $query->where(function ($q) use ($mulai, $selesai) {
                    $q->where('waktu_mulai', '<', $selesai)
                      ->where('waktu_selesai', '>', $mulai);
                });
            })
            ->get();

        $hasConflict = $conflicts->isNotEmpty();

        $conflictDetails = $conflicts->map(function ($c) {
            $userUnit = $c->user->nama_unit ?? $c->user->name ?? 'Unit tidak diketahui';
            return [
                'id'              => $c->id,
                'kode_pemesanan'  => $c->kode_pemesanan,
                'judul_kegiatan'  => $c->judul_kegiatan,
                'unit'            => $userUnit,
                'pic'             => $c->pic_kegiatan,
                'waktu_mulai'     => substr($c->waktu_mulai, 0, 5),
                'waktu_selesai'   => substr($c->waktu_selesai, 0, 5),
                'status'          => is_object($c->status) ? $c->status->value : $c->status,
            ];
        });

        return response()->json([
            'status'           => 'success',
            'conflict'         => $hasConflict,
            'count'            => $conflicts->count(),
            'conflicts'        => $conflictDetails,
            'message'          => $hasConflict
                ? "Terdapat {$conflicts->count()} jadwal kegiatan yang bentrok pada ruangan dan jam tersebut."
                : 'Jadwal ruangan tersedia!',
        ]);
    }
}
