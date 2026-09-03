<?php

namespace App\Models;

use Carbon\Carbon;
use App\Enums\PemesananStatus;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;


class Pemesanan extends Model
{

    use HasFactory;


    protected $table = 'pemesanan';



    protected $fillable = [
        'kode_pemesanan',
        'user_id',
        'ruangan_id',
        'layout_ruangan_id',
        'tanggal_kegiatan',
        'waktu_mulai',
        'waktu_selesai',
        'judul_kegiatan',
        'pic_kegiatan',
        'jenis_pic',
        'no_wa_pic',
        'jumlah_tamu',
        'keterangan_layout',
        'catatan_user',
        'file_disposisi',
        'status',
        'approved_by',
        'approved_at',
        'catatan_admin',
        'rejected_by',
        'rejected_at',
        'alasan_penolakan',
        'cancelled_by',
        'cancelled_at',
        'alasan_pembatalan',
        'reschedule_status',
        'reschedule_tanggal',
        'reschedule_waktu_mulai',
        'reschedule_waktu_selesai',
        'reschedule_alasan',
    ];

    protected $casts = [
        'tanggal_kegiatan' => 'date',
        'jumlah_tamu' => 'integer',
        'status' => PemesananStatus::class,
        'approved_at' => 'datetime',
        'rejected_at' => 'datetime',
        'cancelled_at' => 'datetime',
        'reschedule_tanggal' => 'date',
    ];

    protected $appends = [
        'durasi',
        'durasi_format'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function ruangan()
    {
        return $this->belongsTo(Ruangan::class);
    }

    public function layout()
    {
        return $this->belongsTo(LayoutRuangan::class, 'layout_ruangan_id');
    }

    public function approver()
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    public function rejecter()
    {
        return $this->belongsTo(User::class, 'rejected_by');
    }

    public function canceller()
    {
        return $this->belongsTo(User::class, 'cancelled_by');
    }



    /*
    |--------------------------------------------------------------------------
    | Display Attribute
    |--------------------------------------------------------------------------
    */


    public function getDisplayStatusAttribute()
    {

        return $this->status
            ? $this->status->label()
            : '-';

    }


    /*
    |--------------------------------------------------------------------------
    | Accessor
    |--------------------------------------------------------------------------
    */


    public function getDurasiAttribute()
    {

        if(
            !$this->waktu_mulai ||
            !$this->waktu_selesai
        ){

            return 0;

        }



        $mulai =
            Carbon::parse(
                $this->waktu_mulai
            );


        $selesai =
            Carbon::parse(
                $this->waktu_selesai
            );



        return round(

            $mulai->diffInMinutes(
                $selesai
            ) / 60,

            2

        );

    }

    public function getDurasiFormatAttribute()
    {

        if(
            !$this->waktu_mulai ||
            !$this->waktu_selesai
        ){

            return '-';

        }


        $mulai =
            Carbon::parse(
                $this->waktu_mulai
            );


        $selesai =
            Carbon::parse(
                $this->waktu_selesai
            );


        $menit =
            $mulai->diffInMinutes(
                $selesai
            );


        $jam =
            floor(
                $menit / 60
            );


        $sisaMenit =
            $menit % 60;



        if($jam > 0 && $sisaMenit > 0){

            return $jam
                . ' jam '
                . $sisaMenit
                . ' menit';

        }



        if($jam > 0){

            return $jam
                . ' jam';

        }



        return $sisaMenit
            . ' menit';

    }

    /*
    |--------------------------------------------------------------------------
    | Query Scope
    |--------------------------------------------------------------------------
    */


    public function scopeApproved($query)
    {
        return $query->whereIn('status', [
            PemesananStatus::DISETUJUI,
            PemesananStatus::SELESAI,
        ]);
    }

    public function scopeSelesai($query)
    {
        return $query->where('status', PemesananStatus::SELESAI);
    }



    public function scopePending($query)
    {

        return $query->where(
            'status',
            PemesananStatus::PENDING
        );

    }



    public function scopeRejected($query)
    {

        return $query->where(
            'status',
            PemesananStatus::DITOLAK
        );

    }



    public function scopeToday($query)
    {

        return $query->whereDate(
            'tanggal_kegiatan',
            today()
        );

    }



    public function scopeIsLive($query)
    {
        return $query
            ->where('status', PemesananStatus::DISETUJUI)
            ->whereDate('tanggal_kegiatan', today())
            ->whereTime('waktu_mulai', '<=', now()->format('H:i:s'))
            ->whereTime('waktu_selesai', '>=', now()->format('H:i:s'));
    }

    public function scopeUpcoming($query)
    {
        $today = \Carbon\Carbon::now('Asia/Makassar')->toDateString();
        $currentTime = \Carbon\Carbon::now('Asia/Makassar')->format('H:i:s');

        return $query->where(function ($q) use ($today, $currentTime) {
            $q->whereDate('tanggal_kegiatan', '>', $today)
              ->orWhere(function ($subQ) use ($today, $currentTime) {
                  $subQ->whereDate('tanggal_kegiatan', $today)
                       ->whereTime('waktu_selesai', '>', $currentTime);
              });
        });
    }



    public function scopeConflict($query, array $data, $excludeId = null)
    {
        return $query
            ->where('ruangan_id', $data['ruangan_id'])
            ->whereDate('tanggal_kegiatan', $data['tanggal_kegiatan'])
            ->whereIn('status', [PemesananStatus::DISETUJUI->value, PemesananStatus::SELESAI->value])
            ->when($excludeId, fn($q) => $q->where('id', '!=', $excludeId))
            ->where(function ($q) use ($data) {
                $q->where('waktu_mulai', '<', $data['waktu_selesai'])
                  ->where('waktu_selesai', '>', $data['waktu_mulai']);
            });
    }

    public function canBeFinishedEarly(): bool
    {
        $statusVal = is_object($this->status) ? $this->status->value : $this->status;
        if ($statusVal !== PemesananStatus::DISETUJUI->value) {
            return false;
        }

        if (!$this->tanggal_kegiatan || !$this->tanggal_kegiatan->isToday()) {
            return false;
        }

        $currentTime = \Carbon\Carbon::now('Asia/Makassar')->format('H:i:s');

        return $currentTime >= $this->waktu_mulai && $currentTime < $this->waktu_selesai;
    }

    public function getIsFinishedAttribute(): bool
    {
        $statusVal = is_object($this->status) ? $this->status->value : $this->status;
        if ($statusVal === PemesananStatus::SELESAI->value) {
            return true;
        }
        if ($statusVal !== PemesananStatus::DISETUJUI->value) {
            return false;
        }

        if (!$this->tanggal_kegiatan) {
            return false;
        }

        $today = \Carbon\Carbon::now('Asia/Makassar')->toDateString();
        $currentTime = \Carbon\Carbon::now('Asia/Makassar')->format('H:i:s');
        $eventDate = $this->tanggal_kegiatan->toDateString();

        return $eventDate < $today || ($eventDate === $today && $this->waktu_selesai <= $currentTime);
    }

    public static function markFinishedAgendas(): int
    {
        $today = \Carbon\Carbon::now('Asia/Makassar')->toDateString();
        $currentTime = \Carbon\Carbon::now('Asia/Makassar')->format('H:i:s');

        return static::where('status', PemesananStatus::DISETUJUI->value)
            ->where(function ($q) use ($today, $currentTime) {
                $q->whereDate('tanggal_kegiatan', '<', $today)
                  ->orWhere(function ($subQ) use ($today, $currentTime) {
                      $subQ->whereDate('tanggal_kegiatan', $today)
                           ->where('waktu_selesai', '<=', $currentTime);
                  });
            })
            ->update(['status' => PemesananStatus::SELESAI->value]);
    }
}