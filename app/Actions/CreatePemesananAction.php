<?php

namespace App\Actions;

use App\Enums\PemesananStatus;
use App\Enums\Role;
use App\Models\LayoutRuangan;
use App\Models\Pemesanan;
use App\Models\User;
use App\Notifications\PemesananNotification;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use App\Services\AuditLogService;

class CreatePemesananAction
{
    public function execute(array $data, User $user): Pemesanan
    {
        $pemesanan = DB::transaction(function () use ($data, $user) {
            $ruangan = \App\Models\Ruangan::lockForUpdate()->findOrFail($data['ruangan_id']);

            if ($data['jumlah_tamu'] > $ruangan->kapasitas) {
                throw new Exception('Jumlah tamu melebihi kapasitas maksimal ruangan (' . $ruangan->kapasitas . ' orang).');
            }

            $bentrok = Pemesanan::where('ruangan_id', $data['ruangan_id'])
                ->whereDate('tanggal_kegiatan', $data['tanggal_kegiatan'])
                ->where('status', PemesananStatus::DISETUJUI->value)
                ->where(function ($query) use ($data) {
                    $query->where('waktu_mulai', '<', $data['waktu_selesai'])
                        ->where('waktu_selesai', '>', $data['waktu_mulai']);
                })
                ->lockForUpdate()
                ->exists();

            if ($bentrok) {
                throw new Exception('Ruangan sudah digunakan pada waktu tersebut.');
            }

            $filePath = null;
            if (isset($data['file_disposisi']) && $data['file_disposisi'] instanceof \Illuminate\Http\UploadedFile) {
                $filePath = $data['file_disposisi']->store('disposisi', 'public');
            }

            return Pemesanan::create([
                'kode_pemesanan' => $this->generateKodePemesanan(),
                'user_id' => $user->id,
                'ruangan_id' => $data['ruangan_id'],
                'layout_ruangan_id' => !empty($data['layout_ruangan_id']) ? $data['layout_ruangan_id'] : null,
                'tanggal_kegiatan' => $data['tanggal_kegiatan'],
                'waktu_mulai' => $data['waktu_mulai'],
                'waktu_selesai' => $data['waktu_selesai'],
                'judul_kegiatan' => $data['judul_kegiatan'],
                'pic_kegiatan' => $data['pic_kegiatan'],
                'jenis_pic' => $data['jenis_pic'],
                'no_wa_pic' => $data['no_wa_pic'] ?? null,
                'jumlah_tamu' => $data['jumlah_tamu'],
                'keterangan_layout' => $data['keterangan_layout'] ?? null,
                'catatan_user' => $data['catatan_user'] ?? null,
                'file_disposisi' => $filePath,
                'status' => PemesananStatus::PENDING->value,
            ]);
        });

        AuditLogService::create(
            'Membuat Pemesanan',
            'Pemesanan',
            'Membuat pengajuan pemesanan ' . $pemesanan->kode_pemesanan
        );

        try {
            User::where('role', Role::ADMIN->value)
                ->get()
                ->each(function ($admin) use ($pemesanan) {
                    $admin->notify(
                        new PemesananNotification(
                            'Pengajuan Pemesanan Baru',
                            'Pemesanan ' . $pemesanan->kode_pemesanan . ' membutuhkan approval admin.',
                            $pemesanan->id
                        )
                    );
                });

            // Trigger WhatsApp Notification to Admin
            (new \App\Services\WhatsAppService())->notifyAdminNewBooking($pemesanan);

            // Trigger WhatsApp Notification to PIC / User
            (new \App\Services\WhatsAppService())->notifyUserBookingSubmitted($pemesanan);
        } catch (\Exception $e) {
            report($e);
        }

        return $pemesanan;
    }

    private function generateKodePemesanan(): string
    {
        do {
            $kode = 'SIL-' . now()->format('Ymd') . '-' . strtoupper(Str::random(5));
        } while (Pemesanan::where('kode_pemesanan', $kode)->exists());

        return $kode;
    }
}