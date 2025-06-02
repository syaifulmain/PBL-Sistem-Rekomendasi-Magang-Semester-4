<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class NotificationModel extends Model
{
    protected $table = 'notifications';

    protected $fillable = [
        'type',
        'notifiable_type',
        'notifiable_id',
        'data',
        'read_at'
    ];

    protected $casts = [
        'read_at' => 'datetime',
        'data' => 'array'
    ];

    public function notifiable(): MorphTo
    {
        return $this->morphTo();
    }
}