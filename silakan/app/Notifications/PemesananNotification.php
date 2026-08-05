<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;


class PemesananNotification extends Notification
{

    use Queueable;


    public function __construct(
        public string $judul,
        public string $pesan,
        public int $pemesananId
    )
    {

    }



    public function via(object $notifiable): array
    {

        return [

            'database'

        ];

    }



    public function toDatabase(object $notifiable): array
    {

        return [

            'judul' =>
                $this->judul,


            'pesan' =>
                $this->pesan,


            'pemesanan_id' =>
                $this->pemesananId,


            'waktu' =>
                now()->format('d-m-Y H:i'),

        ];

    }

}