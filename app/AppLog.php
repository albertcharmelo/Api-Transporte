<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class AppLog extends Model
{
    protected $table = 'app_logs';
    protected $fillable = [
        'type',
        'description',
        'http_code',
        'reference',
        'amount',
        'bank_code',
        'phone_number',
        'user_id',
        'chofer_id'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function chofer()
    {
        return $this->belongsTo(DatosChofer::class);
    }
}
