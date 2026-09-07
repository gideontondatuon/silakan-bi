<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\LayoutRuangan;
use App\Models\Ruangan;
use App\Services\AuditLogService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class LayoutController extends Controller
{
    /**
     * Get layouts associated with a ruangan (pivot + direct).
     */
    public function index(Request $request, ?Ruangan $ruangan = null): JsonResponse
    {
        if ($ruangan && $ruangan->exists) {
            $pivotLayouts = $ruangan
                ->layouts()
                ->select('layout_ruangan.id', 'layout_ruangan.nama_layout')
                ->get();

            $directLayouts = LayoutRuangan::where('ruangan_id', $ruangan->id)
                ->select('id', 'nama_layout')
                ->get();

            $layouts = $pivotLayouts->merge($directLayouts)->unique('id')->values();
            return response()->json($layouts);
        }

        $query = LayoutRuangan::with(['ruangan', 'ruangans']);

        if ($request->filled('q')) {
            $query->where('nama_layout', 'like', "%{$request->q}%");
        }

        $layouts = $request->has('per_page')
            ? $query->latest()->paginate($request->get('per_page', 10))
            : $query->orderBy('nama_layout')->get();

        return response()->json([
            'status' => 'success',
            'data' => $layouts,
        ]);
    }

    /**
     * Helper endpoint for dynamic layout select by ruangan ID.
     */
    public function getLayoutsByRuangan($ruangan_id): JsonResponse
    {
        $ruangan = Ruangan::find($ruangan_id);
        if (!$ruangan) {
            return response()->json([]);
        }

        $pivotLayouts = $ruangan->layouts()->select('layout_ruangan.id', 'layout_ruangan.nama_layout')->get();
        $directLayouts = LayoutRuangan::where('ruangan_id', $ruangan_id)->select('id', 'nama_layout')->get();

        $layouts = $pivotLayouts->merge($directLayouts)->unique('id')->values();

        return response()->json($layouts);
    }

    /**
     * Show single layout.
     */
    public function show(LayoutRuangan $layout): JsonResponse
    {
        return response()->json([
            'status' => 'success',
            'data' => $layout->load(['ruangan', 'ruangans']),
        ]);
    }

    /**
     * Store a new layout.
     */
    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'nama_layout' => ['required', 'string', 'max:255'],
            'ruangan_id' => ['nullable', 'exists:ruangan,id'],
        ]);

        $layout = LayoutRuangan::create($validated);

        AuditLogService::create(
            'Menambahkan Layout',
            'Master Layout',
            'Menambahkan layout ' . $layout->nama_layout
        );

        return response()->json([
            'status' => 'success',
            'message' => 'Layout berhasil ditambahkan.',
            'data' => $layout->load('ruangan'),
        ], 201);
    }

    /**
     * Update a layout.
     */
    public function update(Request $request, LayoutRuangan $layout): JsonResponse
    {
        $validated = $request->validate([
            'nama_layout' => ['required', 'string', 'max:255'],
            'ruangan_id' => ['nullable', 'exists:ruangan,id'],
        ]);

        $layout->update($validated);

        AuditLogService::create(
            'Memperbarui Layout',
            'Master Layout',
            'Memperbarui layout ' . $layout->nama_layout
        );

        return response()->json([
            'status' => 'success',
            'message' => 'Layout berhasil diperbarui.',
            'data' => $layout->load('ruangan'),
        ]);
    }

    /**
     * Delete a layout.
     */
    public function destroy(LayoutRuangan $layout): JsonResponse
    {
        $nama = $layout->nama_layout;
        $layout->delete();

        AuditLogService::create(
            'Menghapus Layout',
            'Master Layout',
            'Menghapus layout ' . $nama
        );

        return response()->json([
            'status' => 'success',
            'message' => "Layout '{$nama}' berhasil dihapus.",
        ]);
    }
}
