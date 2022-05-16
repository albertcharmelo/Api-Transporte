<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UserRecarga extends Model
{
    protected $table = 'user_recargas';
    protected $guarded = [];

    public function user()
    {
        return $this->belongsTo(User::class,'user_id');
    }




}
