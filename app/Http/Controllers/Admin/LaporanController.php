<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Pemesanan;
use App\Models\Ruangan;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\View\View;
use App\Exports\PemesananExport;
use Maatwebsite\Excel\Facades\Excel;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class LaporanController extends Controller
{
    /**
     * Menampilkan halaman filter dan pratinjau laporan.
     */
    public function index(Request $request): View
    {
        $baseQuery = Pemesanan::query();

        // Filter Rentang Tanggal
        if ($request->filled('tanggal_mulai')) {
            $baseQuery->whereDate('tanggal_kegiatan', '>=', $request->tanggal_mulai);
        }
        if ($request->filled('tanggal_selesai')) {
            $baseQuery->whereDate('tanggal_kegiatan', '<=', $request->tanggal_selesai);
        }

        // Filter Ruangan
        if ($request->filled('ruangan_id')) {
            $baseQuery->where('ruangan_id', $request->ruangan_id);
        }

        // Filter Unit / User
        if ($request->filled('user_id')) {
            $baseQuery->where('user_id', $request->user_id);
        }

        // Ringkasan Total (dihitung berdasarkan filter umum sebelum status)
        $totalPemesanan = (clone $baseQuery)->count();
        $totalDisetujui = (clone $baseQuery)->where('status', \App\Enums\PemesananStatus::DISETUJUI->value)->count();
        $totalDitolak   = (clone $baseQuery)->where('status', \App\Enums\PemesananStatus::DITOLAK->value)->count();

        $query = (clone $baseQuery)->with(['ruangan', 'user', 'layout', 'approver']);

        // Filter Status spesifik untuk tabel
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $pemesanan = $query->latest('tanggal_kegiatan')->paginate(15)->withQueryString();

        $ruangans = Ruangan::orderBy('nama_ruangan')->get();
        $users = User::orderBy('name')->get();

        return view('admin.laporan.index', compact(
            'pemesanan',
            'ruangans',
            'users',
            'totalPemesanan',
            'totalDisetujui',
            'totalDitolak'
        ));
    }

    /**
     * Mengunduh berkas laporan dalam format resmi Excel (.xlsx).
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
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        if ($request->filled('user_id')) {
            $query->where('user_id', $request->user_id);
        }

        $data = $query->latest('tanggal_kegiatan')->get();
        $fileName = 'Laporan_Pemesanan_Ruangan_' . date('Ymd_His') . '.xlsx';

        return Excel::download(new PemesananExport($data), $fileName);
    }

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
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        if ($request->filled('user_id')) {
            $query->where('user_id', $request->user_id);
        }

        $pemesanan = $query->latest('tanggal_kegiatan')->get();

        return view('admin.laporan.cetak', compact('pemesanan'));
    }
}