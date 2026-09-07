<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\LayoutRuangan;
use App\Models\Ruangan;
use App\Services\AuditLogService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class RuanganApiController extends Controller
{
    /**
     * List all ruangan.
     */
    public function index(Request $request): JsonResponse
    {
        $query = Ruangan::with('layouts');

        if ($request->boolean('only_active')) {
            $query->where('status', 'aktif');
        }

        if ($request->filled('q')) {
            $q = $request->q;
            $query->where('nama_ruangan', 'like', "%{$q}%")
                  ->orWhere('lokasi', 'like', "%{$q}%");
        }

        $ruangans = $request->has('per_page')
            ? $query->latest()->paginate($request->get('per_page', 10))
            : $query->orderBy('nama_ruangan')->get();

        return response()->json([
            'status' => 'success',
            'data' => $ruangans,
        ]);
    }

    /**
     * Show single ruangan details.
     */
    public function show(Ruangan $ruangan): JsonResponse
    {
        $ruangan->load('layouts');

        return response()->json([
            'status' => 'success',
            'data' => $ruangan,
        ]);
    }

    /**
     * Create new ruangan.
     */
    public function store(Request $request): JsonResponse
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

        return response()->json([
            'status' => 'success',
            'message' => 'Ruangan berhasil ditambahkan.',
            'data' => $ruangan->load('layouts'),
        ], 201);
    }

    /**
     * Update ruangan.
     */
    public function update(Request $request, Ruangan $ruangan): JsonResponse
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

        return response()->json([
            'status' => 'success',
            'message' => 'Ruangan berhasil diperbarui.',
            'data' => $ruangan->load('layouts'),
        ]);
    }

    /**
     * Delete ruangan.
     */
    public function destroy(Ruangan $ruangan): JsonResponse
    {
        if ($ruangan->pemesanan()->exists()) {
            return response()->json([
                'status' => 'error',
                'message' => "Ruangan '{$ruangan->nama_ruangan}' tidak dapat dihapus karena memiliki riwayat pemesanan. Anda dapat mengubah statusnya menjadi Nonaktif.",
            ], 422);
        }

        $nama = $ruangan->nama_ruangan;
        $ruangan->delete();

        AuditLogService::create(
            'Menghapus Ruangan',
            'Master Ruangan',
            'Menghapus ruangan ' . $nama
        );

        return response()->json([
            'status' => 'success',
            'message' => "Ruangan '{$nama}' berhasil dihapus.",
        ]);
    }
}
