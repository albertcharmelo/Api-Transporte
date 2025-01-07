<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class BankUrisApi extends Model
{
    // Constantes
    public const URI_VALIDATE_P2P = 'https://servicios.bncenlinea.com:16500/Api/Position/ValidateP2P';
    public const URI_SENDP2P = 'https://servicios.bncenlinea.com:16500/Api/MobPayment/SendP2P';
    public const URI_HISTORY = 'https://servicios.bncenlinea.com:16500/Api/Position/History';
    public const URI_AUTH = 'https://servicios.bncenlinea.com:16500/Api/Auth/LogOn';
    public const URI_BANKLIST = 'https://servicios.bncenlinea.com:16500/Api/Services/Banks';
}
