<?php

namespace App\Http\Controllers;

use App\Enums\Role;
use App\Models\Pemesanan;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class DashboardController extends Controller
{
    /**
     * Mengarahkan pengguna ke dashboard berdasarkan role.
     */
    public function index(Request $request): RedirectResponse
    {
        $user = $request->user();
        if (!$user) {
            return redirect()->route('login');
        }

        $roleValue = is_object($user->role) ? $user->role->value : $user->role;

        if ($roleValue === 'admin') {
            return redirect()->route('admin.dashboard');
        }

        return redirect()->route('user.dashboard');
    }

    /**
     * Menampilkan dashboard pengguna biasa.
     */
    public function user(): View
    {
        Pemesanan::markFinishedAgendas();

        $userId = auth()->id();

        // Stats
        $totalPemesanan = Pemesanan::where('user_id', $userId)->count();
        $pendingPemesanan = Pemesanan::where('user_id', $userId)->pending()->count();
        $approvedPemesanan = Pemesanan::where('user_id', $userId)->approved()->count();
        $upcomingPemesanan = Pemesanan::where('user_id', $userId)
            ->approved()
            ->upcoming()
            ->count();

        // 5 Pemesanan Terbaru Milik User
        $pemesananTerbaru = Pemesanan::with(['ruangan', 'layout'])
            ->where('user_id', $userId)
            ->latest()
            ->take(5)
            ->get();

        // Agenda / Kegiatan Hari Ini (Seluruh Ruangan)
        $kegiatanHariIni = Pemesanan::with(['ruangan', 'user'])
            ->approved()
            ->whereDate('tanggal_kegiatan', now()->toDateString())
            ->orderBy('waktu_mulai')
            ->take(5)
            ->get();

        // Kegiatan Sedang Berlangsung Saat Ini (LIVE)
        $kegiatanBerlangsung = Pemesanan::with(['ruangan', 'user', 'layout'])
            ->isLive()
            ->orderBy('waktu_mulai')
            ->get();

        return view('dashboard', compact(
            'totalPemesanan',
            'pendingPemesanan',
            'approvedPemesanan',
            'upcomingPemesanan',
            'pemesananTerbaru',
            'kegiatanHariIni',
            'kegiatanBerlangsung'
        ));
    }
}