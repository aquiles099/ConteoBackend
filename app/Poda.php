<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Poda extends Model
{
    protected $fillable = [
    	'id_season','id_farm','date', 'total', 'id_user', 'id_client'
	];
	


}
