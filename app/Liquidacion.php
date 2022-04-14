<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Liquidacion extends Model
{
    protected $table = 'liquidacion';
    protected $guarded = [];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
