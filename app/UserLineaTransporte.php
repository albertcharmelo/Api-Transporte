<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UserLineaTransporte extends Model
{
    protected $table = "linea_transporte";
    protected $guarded = [];
    protected $with = ['tarifas:id,tarifa,linea_id', 'users'];

    /**
     * Get all of the tarifas for the UserLineaTransporte
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function tarifas()
    {
        return $this->hasMany(TransporteTarifa::class, 'linea_id', 'id');
    }

    /**
     * Get all of the uuser for the UserLineaTransporte
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function users()
    {
        return $this->hasMany(User::class, 'lineaTransporte_id', 'id');
    }

}
