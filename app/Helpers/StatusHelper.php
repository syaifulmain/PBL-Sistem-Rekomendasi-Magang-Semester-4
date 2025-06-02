<?php

namespace App\Helpers;

class StatusHelper
{
    public static function getPengajuanStatusBadge($status)
    {
        $badgeClass = match($status) {
            'disetujui' => 'success',
            'ditolak' => 'danger',
            default => 'warning'
        };
        
        return [
            'class' => $badgeClass,
            'text' => ucfirst($status)
        ];
    }


    public static function getMagangStatusBadge($status)
    {
        $badgeClass = match($status) {
            'selesai' => 'success',
            'belum_dimulai' => 'primary',
            'aktif' => 'warning',
            default => 'info'
        };
        
        return [
            'class' => $badgeClass,
            'text' => str_replace('_', ' ', ucfirst($status))
        ];
    }
}
