<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class EmployeeSeasons extends Model
{
    protected $fillable = [
        'id_employee', 'id_season'
    ];
}
