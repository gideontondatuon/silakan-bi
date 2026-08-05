<?php

namespace App\Actions;

use App\Enums\PemesananStatus;
use App\Models\Pemesanan;
use App\Models\PemesananStatusHistory;
use App\Models\User;
use App\Services\AuditLogService;
use Exception;
use Illuminate\Support\Facades\DB;

class CancelPemesananAction
{
    public function execute(Pemesanan $pemesanan, User $user): Pemesanan
    {
        return DB::transaction(function () use ($pemesanan, $user) {
            $pemesanan = Pemesanan::lockForUpdate()->findOrFail($pemesanan->id);

            if ($pemesanan->user_id !== $user->id) {
                throw new Exception('Anda tidak berhak membatalkan pemesanan ini.');
            }

            if ($pemesanan->status->value !== PemesananStatus::PENDING->value) {
                throw new Exception('Hanya pemesanan berstatus Pending yang dapat dibatalkan.');
            }

            $statusLama = $pemesanan->status;

            $pemesanan->update([
                'status' => PemesananStatus::CANCEL->value,
            ]);

            PemesananStatusHistory::create([
                'pemesanan_id' => $pemesanan->id,
                'status_lama' => $statusLama,
                'status_baru' => PemesananStatus::CANCEL,
                'changed_by' => $user->id,
                'changed_at' => now(),
            ]);

            AuditLogService::create(
                'Membatalkan Pemesanan',
                'Pemesanan',
                'Membatalkan pemesanan ' . $pemesanan->kode_pemesanan
            );

            return $pemesanan;
        });
    }
}
