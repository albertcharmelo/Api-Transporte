<?php

namespace App;

use App\Http\Controllers\PaymentBankController;
use Illuminate\Database\Eloquent\Model;

class BncToken extends Model
{
    protected $fillable = [
        'token',
        'expiration_date',
    ];
}
