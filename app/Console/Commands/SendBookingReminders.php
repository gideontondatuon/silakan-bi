<?php

namespace App\Console\Commands;

use App\Models\Pemesanan;
use App\Services\WhatsAppService;
use Illuminate\Console\Command;

class SendBookingReminders extends Command
{
    /**
     * Nama dan tanda tangan command.
     *
     * @var string
     */
    protected $signature = 'reminders:send-wa';

    /**
     * Deskripsi command.
     *
     * @var string
     */
    protected $description = 'Kirimkan notifikasi pengingat WhatsApp H-1 jam sebelum kegiatan rapat dimulai.';

    /**
     * Jalankan perintah console.
     */
    public function handle(WhatsAppService $waService): int
    {
        $today = now()->toDateString();
        $currentTime = now();

        // Cari pemesanan disetujui hari ini yang waktunya mendekati 1 jam ke depan
        $pemesananList = Pemesanan::with(['ruangan', 'user'])
            ->approved()
            ->whereDate('tanggal_kegiatan', $today)
            ->get();

        $count = 0;
        foreach ($pemesananList as $pemesanan) {
            $waktuMulai = \Carbon\Carbon::parse($pemesanan->tanggal_kegiatan->format('Y-m-d') . ' ' . $pemesanan->waktu_mulai);
            
            // Cek selisih menit antara sekarang dan waktu mulai (antara 45 - 60 menit)
            $diffInMinutes = $currentTime->diffInMinutes($waktuMulai, false);

            if ($diffInMinutes >= 0 && $diffInMinutes <= 60) {
                $targetWA = $pemesanan->no_wa_pic ?: ($pemesanan->user->no_wa ?? null);
                if ($targetWA) {
                    $namaPic = $pemesanan->pic_kegiatan ?: $pemesanan->user->name;
                    $msg = "*[SILAKAN - BANK INDONESIA]*\n"
                         . "*PENGINGAT JADWAL KEGIATAN*\n\n"
                         . "Yth. Bapak/Ibu {$namaPic},\n"
                         . "Mengingatkan bahwa kegiatan rapat Anda akan dimulai dalam 1 jam ke depan:\n\n"
                         . "▪ Ruangan  : {$pemesanan->ruangan->nama_ruangan}\n"
                         . "▪ Waktu    : {$pemesanan->waktu_mulai} - {$pemesanan->waktu_selesai} WITA\n"
                         . "▪ Agenda   : {$pemesanan->judul_kegiatan}\n"
                         . "▪ Lokasi   : KPwBI Prov. Sulawesi Utara\n\n"
                         . "Terima kasih atas perhatian dan kerja samanya.";

                    $waService->sendMessage($targetWA, $msg);
                    $count++;
                }
            }
        }

        $this->info("Berhasil mengirim {$count} pengingat WhatsApp kegiatan rapat hari ini.");
        return Command::SUCCESS;
    }
}
