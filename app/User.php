<?php

namespace App;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Passport\HasApiTokens;
use Illuminate\Auth\Passwords\CanResetPassword;
use Illuminate\Support\Str; // Importar la clase Str
class User extends Authenticatable
{
    use Notifiable;
    use HasApiTokens;
    use CanResetPassword;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'full_name', 'email', 'password', 'gender', 'id_card', 'profile_image', 'type_id_card', 'idShow', 'token_notification'
    ];
    protected $with = ['location'];

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
        return $this->hasOne(QrCodeUser::class, 'users_id', 'id');
    }

    /**
     * Get the qrCode associated with the User
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function wallet()
    {
        return $this->hasOne(UserWallet::class, 'user_id', 'id');
    }
    /**
     * Get the type_user that owns the User
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function type_user()
    {
        return $this->belongsTo(UserPermission::class, 'type_user', 'id');
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


    public function datos_chofer()
    {
        return $this->hasOne(DatosChofer::class, 'user_id', 'id');
    }

    public function datos_bancarios()
    {
        return $this->hasOne(UserDatosBancarios::class, 'user_id', 'id');
    }

    public function liquidacion_pendiente()
    {

        return $this->hasOne(Liquidacion::class, 'user_id', 'id');
    }



    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */

    /**
     * Boot method to attach model events.
     */
    protected static function boot()
    {
        parent::boot();

        // Evento creating para asignar un UUID al campo idShow
        static::creating(function ($user) {
            $user->idShow = (string) Str::uuid();
        });
    }
}
