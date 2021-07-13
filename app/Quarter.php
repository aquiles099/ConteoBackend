<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Quarter extends Model
{
    //
    protected $fillable = [
        'name', 
        'description',
        'hectareas',
        'variety',
        'portainjerto',
        'number_plants', 
        'number_plants_equivalent',
        'number_plants_ha', 
        'number_plants_equivalent_ha',
        'number_clusters_desired',
        'number_clusters_ha',
        'number_clusters_for_quarters',
        'id_farm',
        'formacion',
        'edad_plantas',
        'distancia_entrehilera',
        'distancia_sobrehilera',
        'densidad_plantas',
        'superficie'
        
    ];
}

