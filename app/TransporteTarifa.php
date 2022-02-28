<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TransporteTarifa extends Model
{
    protected $table = 'linea_transporte_tarifas' ;
    protected $guarded = [];

    /**
     * Get the user associated with the TransporteTarifa
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function linea()
    {
        return $this->hasOne(UserLineaTransporte::class, 'linea_id', 'id');
    }


   
}
