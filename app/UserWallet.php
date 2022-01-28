<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UserWallet extends Model
{
    protected $table = "users_wallet";
    protected $fillable = ['user_id','creditos'];

    /**
     * Get the user associated with the UserWallet
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id','id');
    }


 
}
