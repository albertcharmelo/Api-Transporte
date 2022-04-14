<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UserDatosBancarios extends Model
{
    protected $table = 'user_datos_bancarios';
    protected $guarded = [];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    
}
