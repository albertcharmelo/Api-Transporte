<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class DatosChofer extends Model
{
    protected $table = 'datos_chofer';
    protected $primaryKey = 'id';
    protected $guarded = [];
    protected $with = ['user:id,full_name,email'];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
