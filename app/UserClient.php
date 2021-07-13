<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Client;
class UserClient extends Model
{
    protected $fillable = [
        'id_user', 
        'id_client',
    ];
    public function Client()
    {
        return $this->hasOne(Client::class,'id','id_client');
    }
}
