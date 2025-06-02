<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\DatabaseMessage;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Queue\SerializesModels;

class PengajuanMagangNotification extends Notification
{
    use Queueable, SerializesModels;

    public $pengajuan;

    public function __construct($pengajuan)
    {
        $this->pengajuan = $pengajuan;
    }

    public function via($notifiable)
    {
        return ['database'];
    }

    public function toDatabase($notifiable)
    {
        return [
            'pengajuan_id' => $this->pengajuan->id,
            'lowongan' => $this->pengajuan->lowongan->judul,
            'mahasiswa' => $this->pengajuan->mahasiswa->nama,
            'status' => $this->pengajuan->status,
            'type' => $this->pengajuan->status,
        ];
    }

    public function toArray($notifiable)
    {
        return [
            'pengajuan_id' => $this->pengajuan->id,
            'lowongan' => $this->pengajuan->lowongan->judul,
            'mahasiswa' => $this->pengajuan->mahasiswa->nama,
            'status' => $this->pengajuan->status,
            'type' => $this->pengajuan->status,
        ];
    }
}
