<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\AuditLog;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class AuditLogApiController extends Controller
{
    /**
     * List audit logs with date and module filtering.
     */
    public function index(Request $request): JsonResponse
    {
        $query = AuditLog::with('user');

        if ($request->filled('tanggal_mulai')) {
            $query->whereDate('created_at', '>=', $request->tanggal_mulai);
        }

        if ($request->filled('tanggal_selesai')) {
            $query->whereDate('created_at', '<=', $request->tanggal_selesai);
        }

        if ($request->filled('modul')) {
            $modul = $request->modul;
            if ($modul === 'Master Data') {
                $query->where('modul', 'like', '%Master%');
            } elseif ($modul === 'User') {
                $query->where(function ($sub) {
                    $sub->where('modul', 'like', '%User%')->orWhere('modul', 'like', '%Auth%');
                });
            } else {
                $query->where('modul', 'like', "%{$modul}%");
            }
        }

        if ($request->filled('q')) {
            $q = $request->q;
            $query->where(function ($sub) use ($q) {
                $sub->where('aksi', 'like', "%{$q}%")
                    ->orWhere('keterangan', 'like', "%{$q}%")
                    ->orWhere('modul', 'like', "%{$q}%")
                    ->orWhereHas('user', function ($qu) use ($q) {
                        $qu->where('name', 'like', "%{$q}%")
                           ->orWhere('username', 'like', "%{$q}%")
                           ->orWhere('nama_unit', 'like', "%{$q}%");
                    });
            });
        }

        $logs = $query->latest()->paginate($request->get('per_page', 15));

        return response()->json([
            'status' => 'success',
            'data' => $logs,
        ]);
    }
}
