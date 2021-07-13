<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Farm;
class ClientFarms extends Model
{
    protected $fillable = [
        'id_client', 
        'id_farm',
    ];
    public function farm()
    {
        return $this->hasOne(Farm::class,'id','id_farm');
    }
}
