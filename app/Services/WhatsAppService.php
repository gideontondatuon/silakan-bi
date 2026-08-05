<?php

namespace App\Services;

use App\Models\Pemesanan;
use App\Models\User;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class WhatsAppService
{
    public function formatNumber(?string $number): ?string
    {
        if (!$number) return null;

        $number = preg_replace('/[^0-9]/', '', $number);

        if (str_starts_with($number, '0')) {
            $number = '62' . substr($number, 1);
        } elseif (str_starts_with($number, '8')) {
            $number = '62' . $number;
        }

        return $number;
    }

    public function sendMessage(?string $target, string $message): bool
    {
        $target = $this->formatNumber($target);
        if (!$target) return false;

        $token = config('whatsapp.api_token');
        $apiUrl = config('whatsapp.api_url');
        $enabled = config('whatsapp.enabled', true);

        // Simulation / Log mode if token is empty
        if (!$enabled || empty($token)) {
            Log::info("WA_GATEWAY_SIMULATION -> To: {$target} | Message:\n{$message}");
            return true;
        }

        try {
            $response = Http::withoutVerifying()->withHeaders([
                'Authorization' => $token,
            ])->asForm()->post($apiUrl, [
                'target' => $target,
                'message' => $message,
            ]);

            if ($response->successful()) {
                Log::info("WA_GATEWAY_SUCCESS -> To: {$target}");
                return true;
            } else {
                Log::error("WA_GATEWAY_ERROR -> To: {$target} | Status: {$response->status()} | Body: {$response->body()}");
                return false;
            }
        } catch (\Exception $e) {
            Log::error("WA_GATEWAY_EXCEPTION -> To: {$target} | Error: {$e->getMessage()}");
            return false;
        }
    }

    public function notifyAdminNewBooking(Pemesanan $pemesanan): void
    {
        $adminWA = config('whatsapp.admin_number');

        // Also check if any admin users have no_wa set
        $adminUsers = User::where('role', 'admin')->whereNotNull('no_wa')->get();

        $appUrl = config('app.url', 'http://127.0.0.1:8000');
        $tanggal = $pemesanan->tanggal_kegiatan->isoFormat('dddd, D MMMM YYYY');

        $msg = "🔴 *[SILAKAN BI] ADA PENGAJUAN PEMESANAN BARU*\n\n"
             . "Halo Admin, terdapat pengajuan penggunaan ruangan baru yang membutuhkan persetujuan:\n\n"
             . "📌 *Kode:* {$pemesanan->kode_pemesanan}\n"
             . "👤 *Pemohon:* {$pemesanan->user->name} (" . ($pemesanan->user->nama_unit ?? '-') . ")\n"
             . "🏢 *Ruangan:* {$pemesanan->ruangan->nama_ruangan}\n"
             . "📐 *Layout:* " . ($pemesanan->layout->nama_layout ?? '-') . "\n"
             . "📅 *Tanggal:* {$tanggal}\n"
             . "⏰ *Waktu:* {$pemesanan->waktu_mulai} - {$pemesanan->waktu_selesai} WITA\n"
             . "📝 *Kegiatan:* {$pemesanan->judul_kegiatan}\n"
             . "👤 *PIC:* {$pemesanan->pic_kegiatan}" . ($pemesanan->no_wa_pic ? " ({$pemesanan->no_wa_pic})" : '') . "\n\n"
             . "Silakan verifikasi & persetujuan di website SILAKAN:\n"
             . "{$appUrl}/admin/approval/{$pemesanan->id}";

        if ($adminWA) {
            $this->sendMessage($adminWA, $msg);
        }

        foreach ($adminUsers as $admin) {
            if ($admin->no_wa && $admin->no_wa !== $adminWA) {
                $this->sendMessage($admin->no_wa, $msg);
            }
        }
    }

    public function notifyUserBookingSubmitted(Pemesanan $pemesanan): void
    {
        $targetWA = $pemesanan->no_wa_pic ?: ($pemesanan->user->no_wa ?? null);
        if (!$targetWA) return;

        $appUrl = config('app.url', 'http://127.0.0.1:8000');
        $tanggal = $pemesanan->tanggal_kegiatan->isoFormat('dddd, D MMMM YYYY');
        $namaPic = $pemesanan->pic_kegiatan ?: $pemesanan->user->name;

        $msg = "📋 *[SILAKAN BI] PENGAJUAN PEMESANAN DITERIMA*\n\n"
             . "Halo {$namaPic}, pengajuan pemesanan ruangan Anda telah berhasil terkirim dan sedang *MENUNGGU PERSETUJUAN* Admin:\n\n"
             . "📌 *Kode:* {$pemesanan->kode_pemesanan}\n"
             . "🏢 *Ruangan:* {$pemesanan->ruangan->nama_ruangan}\n"
             . "📐 *Layout:* " . ($pemesanan->layout?->nama_layout ?? '-') . "\n"
             . "📅 *Tanggal:* {$tanggal}\n"
             . "⏰ *Waktu:* {$pemesanan->waktu_mulai} - {$pemesanan->waktu_selesai} WITA\n"
             . "📝 *Kegiatan:* {$pemesanan->judul_kegiatan}\n\n"
             . "Status pengajuan dapat dipantau melalui website SILAKAN:\n"
             . "{$appUrl}/pemesanan/{$pemesanan->id}";

        $this->sendMessage($targetWA, $msg);
    }

    public function notifyUserBookingApproved(Pemesanan $pemesanan): void
    {
        $targetWA = $pemesanan->no_wa_pic ?: ($pemesanan->user->no_wa ?? null);
        if (!$targetWA) return;

        $appUrl = config('app.url', 'http://127.0.0.1:8000');
        $tanggal = $pemesanan->tanggal_kegiatan->isoFormat('dddd, D MMMM YYYY');
        $namaPic = $pemesanan->pic_kegiatan ?: $pemesanan->user->name;

        $catatanNote = $pemesanan->catatan_admin ? "\n💬 *Catatan Admin:* {$pemesanan->catatan_admin}\n" : "";

        $msg = "✅ *[SILAKAN BI] PEMESANAN RUANGAN DISETUJUI*\n\n"
             . "Halo {$namaPic}, pengajuan pemesanan ruangan Anda telah *DISETUJUI* oleh Admin:\n\n"
             . "📌 *Kode:* {$pemesanan->kode_pemesanan}\n"
             . "🏢 *Ruangan:* {$pemesanan->ruangan->nama_ruangan}\n"
             . "📐 *Layout:* " . ($pemesanan->layout?->nama_layout ?? '-') . "\n"
             . "📅 *Tanggal:* {$tanggal}\n"
             . "⏰ *Waktu:* {$pemesanan->waktu_mulai} - {$pemesanan->waktu_selesai} WITA\n"
             . "📝 *Kegiatan:* {$pemesanan->judul_kegiatan}\n"
             . $catatanNote . "\n"
             . "Mohon hadir tepat waktu dan menjaga kebersihan serta fasilitas ruangan kantor.\n\n"
             . "Detail Pemesanan:\n"
             . "{$appUrl}/pemesanan/{$pemesanan->id}";

        $this->sendMessage($targetWA, $msg);
    }

    public function notifyUserBookingRejected(Pemesanan $pemesanan, string $alasan): void
    {
        $targetWA = $pemesanan->no_wa_pic ?: ($pemesanan->user->no_wa ?? null);
        if (!$targetWA) return;

        $appUrl = config('app.url', 'http://127.0.0.1:8000');
        $tanggal = $pemesanan->tanggal_kegiatan->isoFormat('dddd, D MMMM YYYY');
        $namaPic = $pemesanan->pic_kegiatan ?: $pemesanan->user->name;

        $msg = "❌ *[SILAKAN BI] PEMESANAN RUANGAN DITOLAK*\n\n"
             . "Halo {$namaPic}, pengajuan pemesanan ruangan Anda *DITOLAK* oleh Admin:\n\n"
             . "📌 *Kode:* {$pemesanan->kode_pemesanan}\n"
             . "🏢 *Ruangan:* {$pemesanan->ruangan->nama_ruangan}\n"
             . "📅 *Tanggal:* {$tanggal}\n"
             . "📝 *Kegiatan:* {$pemesanan->judul_kegiatan}\n"
             . "⚠️ *Alasan Penolakan:* {$alasan}\n\n"
             . "Silakan pilih jadwal atau ruangan alternatif lain di website SILAKAN:\n"
             . "{$appUrl}/pemesanan/create";

        $this->sendMessage($targetWA, $msg);
    }
}
