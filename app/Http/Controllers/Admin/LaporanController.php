<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Pemesanan;
use Illuminate\Http\Request;
use Illuminate\View\View;

class LaporanController extends Controller
{
    /**
     * Menampilkan halaman pratinjau cetak / PDF resmi.
     */
    public function cetakPdf(Request $request): View
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
        if ($request->filled('user_id')) {
            $query->where('user_id', $request->user_id);
        }

        $pemesanan = $query->latest('tanggal_kegiatan')->get();

        return view('admin.laporan.cetak', compact('pemesanan'));
    }
}