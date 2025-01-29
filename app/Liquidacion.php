<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Liquidacion extends Model
{
    protected $table = 'liquidacion';
    protected $fillable = [
        'user_id',
        'banco',
        'cedula',
        'numero_de_cuenta',
        'monto_liquidar',
        'tipo_cuenta',
        'created_at',
        'updated_at',
        'referencia'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
