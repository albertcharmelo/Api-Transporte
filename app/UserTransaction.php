<?php

namespace App;

use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Model;

class UserTransaction extends Model
{
    protected $table = 'transactions';
    protected $fillable = ['driver_id','client_id','transaction','amount','invoice'];

 
    

}
