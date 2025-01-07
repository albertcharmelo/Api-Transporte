<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UserRecarga extends Model
{
    protected $table = 'user_recargas';
    protected $fillable = [
        'banco',
        'referencia',
        'fecha',
        'user_id',
        'created_at',
        'updated_at'
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
