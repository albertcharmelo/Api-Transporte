<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UserLocation extends Model
{
    protected $table ='user_location';
    protected $fillable = ['latitud','longitud','user_id','driving'];
}
