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
        'alasan_penolakan',
        'catatan_admin',

    ];



    protected $casts = [

        'tanggal_kegiatan' => 'date',

        'jumlah_tamu' => 'integer',

        'status' => PemesananStatus::class,

        'approved_at' => 'datetime',

    ];

    protected $appends = [

        'durasi',

        'durasi_format'

    ];



    public function user()
    {

        return $this->belongsTo(
            User::class
        );

    }



    public function ruangan()
    {

        return $this->belongsTo(
            Ruangan::class
        );

    }



    public function layout()
    {

        return $this->belongsTo(
            LayoutRuangan::class,
            'layout_ruangan_id'
        );

    }

    public function approver()
    {
        return $this->belongsTo(User::class, 'approved_by');
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

        return $query->where(
            'status',
            PemesananStatus::DISETUJUI
        );

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

            ->where(
                'status',
                PemesananStatus::DISETUJUI
            )

            ->whereDate(
                'tanggal_kegiatan',
                today()
            )

            ->whereTime(
                'waktu_mulai',
                '<=',
                now()->format('H:i:s')
            )

            ->whereTime(
                'waktu_selesai',
                '>=',
                now()->format('H:i:s')
            );

    }



    public function scopeConflict(
        $query,
        $data
    )
    {

        return $query

            ->where(
                'ruangan_id',
                $data['ruangan_id']
            )

            ->where(
                'tanggal_kegiatan',
                $data['tanggal_kegiatan']
            )

            ->where(
                'status',
                PemesananStatus::DISETUJUI
            )

            ->where(function ($q) use ($data) {


                $q

                ->whereBetween(
                    'waktu_mulai',
                    [
                        $data['waktu_mulai'],
                        $data['waktu_selesai']
                    ]
                )

                ->orWhereBetween(
                    'waktu_selesai',
                    [
                        $data['waktu_mulai'],
                        $data['waktu_selesai']
                    ]
                )

                ->orWhere(function ($q2) use ($data) {


                    $q2

                    ->where(
                        'waktu_mulai',
                        '<=',
                        $data['waktu_mulai']
                    )

                    ->where(
                        'waktu_selesai',
                        '>=',
                        $data['waktu_selesai']
                    );


                });


            });


    }

}