<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Farm;
class UserFarms extends Model
{
    protected $fillable = [
        'id_user', 
        'id_farm',
    ];
    public function farm()
    {
        return $this->hasOne(Farm::class,'id','id_farm');
    }
}
