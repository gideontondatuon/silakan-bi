<?php

namespace App\Http\Controllers\Admin;


use App\Actions\ApprovePemesananAction;
use App\Actions\RejectPemesananAction;
use App\Http\Controllers\Controller;
use App\Models\Pemesanan;
use App\Models\Ruangan;
use App\Models\User;
use App\Enums\PemesananStatus;
use App\Services\AuditLogService;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Illuminate\Support\Str;

class ApprovalController extends Controller
{
    public function index(Request $request): View
    {
        Pemesanan::markFinishedAgendas();

        $tab = $request->get('tab', 'pending');

        $query = Pemesanan::with([
            'user',
            'ruangan',
            'layout',
            'approver'
        ]);

        // Filter by Tab
        if ($tab === 'pending') {
            $query->where('status', \App\Enums\PemesananStatus::PENDING->value);
        } elseif ($tab === 'disetujui') {
            $query->where('status', \App\Enums\PemesananStatus::DISETUJUI->value);
        } elseif ($tab === 'selesai') {
            $query->where('status', \App\Enums\PemesananStatus::SELESAI->value);
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

        // Date Filter
        if ($request->filled('tanggal')) {
            $query->whereDate('tanggal_kegiatan', $request->tanggal);
        }

        $pemesanan = $query->latest('tanggal_kegiatan')
            ->latest('id')
            ->paginate(12)
            ->withQueryString();

        $countPending   = Pemesanan::where('status', \App\Enums\PemesananStatus::PENDING->value)->count();
        $countDisetujui = Pemesanan::where('status', \App\Enums\PemesananStatus::DISETUJUI->value)->count();
        $countSelesai   = Pemesanan::where('status', \App\Enums\PemesananStatus::SELESAI->value)->count();
        $countSemua     = Pemesanan::count();

        $ruangans = Ruangan::orderBy('nama_ruangan')->get();

        return view(
            'admin.approval.index',
            compact('pemesanan', 'tab', 'countPending', 'countDisetujui', 'countSelesai', 'countSemua', 'ruangans')
        );
    }





    public function show(
        Pemesanan $pemesanan
    ): View {


        $pemesanan->load([
            'user',
            'ruangan',
            'layout'
        ]);



        return view(
            'admin.approval.show',
            compact('pemesanan')
        );

    }





    public function approve(
        Request $request,
        Pemesanan $pemesanan,
        ApprovePemesananAction $action
    ): RedirectResponse {
        try {
            $action->execute(
                $pemesanan,
                auth()->user(),
                $request->catatan_admin
            );

            return redirect()
                ->route('admin.approval.index')
                ->with('success', 'Pemesanan berhasil disetujui.');
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }







    public function reject(

        Request $request,

        Pemesanan $pemesanan,

        RejectPemesananAction $action

    ): RedirectResponse {


        $request->validate([


            'alasan_penolakan' => [

                'required',

                'string',

                'max:500'

            ]


        ]);





        try {


            $action->execute(

                $pemesanan,

                $request->alasan_penolakan,

                auth()->user()

            );



            return redirect()

                ->route(
                    'admin.approval.index'
                )

                ->with(

                    'success',

                    'Pemesanan berhasil ditolak.'

                );



        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }

    public function selesaiAwal(Pemesanan $pemesanan): RedirectResponse
    {
        $statusVal = is_object($pemesanan->status) ? $pemesanan->status->value : $pemesanan->status;
        if ($statusVal !== \App\Enums\PemesananStatus::DISETUJUI->value) {
            return back()->with('error', 'Hanya kegiatan berstatus Disetujui yang dapat diselesaikan lebih awal.');
        }

        if (!$pemesanan->tanggal_kegiatan || !$pemesanan->tanggal_kegiatan->isToday()) {
            return back()->with('error', 'Hanya kegiatan yang berlangsung hari ini yang dapat diselesaikan lebih awal.');
        }

        $currentTime = \Carbon\Carbon::now('Asia/Makassar')->format('H:i:s');

        if ($currentTime >= $pemesanan->waktu_selesai) {
            return back()->with('error', 'Kegiatan ini sudah selesai sesuai jadwal dan tidak dapat diselesaikan lebih awal.');
        }

        if ($currentTime < $pemesanan->waktu_mulai) {
            return back()->with('error', 'Kegiatan ini belum dimulai sesuai jadwal.');
        }

        $pemesanan->update([
            'waktu_selesai' => $currentTime,
            'status' => \App\Enums\PemesananStatus::SELESAI,
        ]);

        \App\Services\AuditLogService::create(
            'Menyelesaikan Kegiatan Lebih Awal',
            'Approval',
            "Admin menyelesaikan pemesanan {$pemesanan->kode_pemesanan} lebih awal pada pukul {$currentTime} WITA."
        );

        return back()->with('success', "Kegiatan pada ruangan {$pemesanan->ruangan->nama_ruangan} berhasil diselesaikan lebih awal pada pukul {$currentTime} WITA. Ruangan kini berstatus kosong dan tersedia.");
    }

    public function destroy(Pemesanan $pemesanan): RedirectResponse
    {
        try {
            $kode = $pemesanan->kode_pemesanan;
            $statusLama = is_object($pemesanan->status) ? $pemesanan->status->value : $pemesanan->status;
            $ruanganNama = $pemesanan->ruangan?->nama_ruangan ?? 'Ruangan';
            $judul = $pemesanan->judul_kegiatan;

            // Hapus file disposisi jika ada
            if ($pemesanan->file_disposisi && \Illuminate\Support\Facades\Storage::disk('public')->exists($pemesanan->file_disposisi)) {
                \Illuminate\Support\Facades\Storage::disk('public')->delete($pemesanan->file_disposisi);
            }

            $pemesanan->delete();

            \App\Services\AuditLogService::create(
                'Menghapus Pemesanan',
                'Pemesanan',
                "Admin menghapus pemesanan {$kode} ({$judul} di {$ruanganNama}, Status: {$statusLama}) dari sistem."
            );

            return back()->with('success', "Pemesanan {$kode} ({$judul}) berhasil dihapus dari sistem.");
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal menghapus pemesanan: ' . $e->getMessage());
        }
    }

    public function create(): View
    {
        $ruangan = Ruangan::where('status', 'aktif')
            ->orderBy('nama_ruangan')
            ->get();

        $units = User::where('role', 'user')
            ->orderBy('nama_unit')
            ->get();

        return view('admin.approval.create', compact('ruangan', 'units'));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'ruangan_id' => 'required|exists:ruangan,id',
            'layout_ruangan_id' => 'nullable|exists:layout_ruangan,id',
            'tanggal_kegiatan' => 'required|date|after_or_equal:today',
            'waktu_mulai' => 'required|date_format:H:i',
            'waktu_selesai' => 'required|date_format:H:i|after:waktu_mulai',
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

        $ruangan = Ruangan::findOrFail($validated['ruangan_id']);
        if ($validated['jumlah_tamu'] > $ruangan->kapasitas) {
            return back()->withInput()->with('error', "Jumlah tamu ({$validated['jumlah_tamu']}) melebihi kapasitas maksimal ruangan {$ruangan->nama_ruangan} ({$ruangan->kapasitas} orang).");
        }

        $nowMakassar = \Carbon\Carbon::now('Asia/Makassar');
        $today = $nowMakassar->toDateString();
        $currentTime = $nowMakassar->format('H:i');

        if ($validated['tanggal_kegiatan'] === $today && $validated['waktu_mulai'] < $currentTime) {
            return back()->withInput()->with('error', "Waktu mulai ({$validated['waktu_mulai']} WITA) tidak dapat menggunakan jam yang sudah terlewat untuk hari ini (waktu saat ini: {$currentTime} WITA).");
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
            return back()->withInput()->with('error', 'Ruangan sudah memiliki agenda kegiatan pada tanggal dan jam tersebut.');
        }

        $filePath = null;
        if ($request->hasFile('file_disposisi')) {
            $filePath = $request->file('file_disposisi')->store('disposisi', 'public');
        }

        do {
            $kode = 'SIL-' . now()->format('Ymd') . '-' . strtoupper(Str::random(5));
        } while (Pemesanan::where('kode_pemesanan', $kode)->exists());

        $ownerUserId = !empty($validated['user_id']) ? $validated['user_id'] : auth()->id();

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
            'approved_by' => auth()->id(),
            'approved_at' => now(),
            'catatan_admin' => 'Rapat dijadwalkan langsung oleh Administrator Sarpras.',
        ]);

        AuditLogService::create(
            'Menambahkan Rapat (Admin)',
            'Approval',
            "Admin menambahkan rapat {$pemesanan->kode_pemesanan} ({$pemesanan->judul_kegiatan}) di ruangan {$ruangan->nama_ruangan}."
        );

        try {
            (new \App\Services\WhatsAppService())->notifyUserBookingApproved($pemesanan);
        } catch (\Exception $e) {
            report($e);
        }

        return redirect()
            ->route('admin.approval.index', ['tab' => 'disetujui'])
            ->with('success', "Rapat '{$pemesanan->judul_kegiatan}' berhasil dijadwalkan dan langsung berstatus Disetujui.");
    }
}