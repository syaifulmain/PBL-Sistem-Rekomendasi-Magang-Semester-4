<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class AdminModel extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'm_admin';

    protected $fillable = [
        'user_id',
        'nama',
    ];

    public function user()
    {
        return $this->belongsTo(UserModel::class, 'user_id');
    }

}
