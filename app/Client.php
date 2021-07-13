<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\ClientFarms;

class Client extends Model
{
    protected $fillable = [
        'company_name', 'company_producer','company_rut', 'company_email' 
    ];

    public function farms() {
        return $this->hasMany(ClientFarms::class, 'id_client');
    }
}
