<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Count extends Model
{
    protected $fillable = [
    	'id_employee','employee_rut','working_day', 
    	'countscol', 'date', 'id_quarter','quarter_name',
    	'id_season','season_name', 'row', 'plant','cant', 
    	'obj', 'diff','id_user','id_farm','farm_name',
    	'registered_in_batch' //registrado en lote
    ];
}
