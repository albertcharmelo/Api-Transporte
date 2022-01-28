<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class QrCodeUser extends Model
{
    protected $table = 'users_qr';
    protected $fillable = ['users_id', 'qr_image','qr_idShow','qr_name'];



}
