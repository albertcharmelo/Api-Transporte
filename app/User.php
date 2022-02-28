<?php

namespace App;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Passport\HasApiTokens;

class User extends Authenticatable
{
    use Notifiable;
    use HasApiTokens;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'full_name', 'email', 'password','gender','id_card','profile_image','type_id_card','idShow'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];


    /**
     * Get the qrCode associated with the User
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function qrCode()
    {
        return $this->hasOne(QrCodeUser::class,'users_id', 'id');
    }

     /**
     * Get the qrCode associated with the User
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function wallet()
    {
        return $this->hasOne(UserWallet::class,'user_id', 'id');
    }
    /**
     * Get the type_user that owns the User
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function type_user()
    {
        return $this->belongsTo(UserPermission::class, 'type_user','id');
    }

    /**
     * Get the location associated with the User
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function location()
    {
        return $this->hasOne(UserLocation::class, 'user_id', 'id');
    }

    /**
     * Get the lineaTransporte that owns the User
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function lineaTransporte()
    {
        return $this->belongsTo(UserLineaTransporte::class, 'lineaTransporte_id', 'id');
    }
}
