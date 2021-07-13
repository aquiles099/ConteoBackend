<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PodaCuartel extends Model
{
    protected $fillable = [
		'id_poda',
		'id_quarter',
		'densidad_plantas', 
		'plantas_muertas', 
		'medias_plantas', 	
		'porcentaje_desecho',           
		'causa_desecho',
		'cargadores_plantas',
		'cargadores_plantas_alt2',
		'yemas_cargador',
		'yemas_cargador_alt2',
		'pitones_plantas',
		'yemas_piton',
		'n_yemas_plantas',
		'n_yemas_hectareas',
		'yemas_fertiles',
		'yemas_fertilesA2',
		'racimos_plantas_potenciales',
		'racimos_cargador_potenciales',
		'racimos_cargador_potencialesA2',
		'brotacion_esperada',
		'brotes_chupados',
		'porcentaje_racimos_chico',
		'porcentaje_racimos_medianos',
		'porcentaje_racimos_grandes',
		'gr_racimos_chico',
		'gr_racimos_medianos',
		'gr_racimos_grandes',
		'racimos_efectivos_hectareas',
		'racimos_deseados_planta',
		'racimos_efectivos_planta',
		'racimos_deseados_hectareas',
		'racimos_eliminar',  
		'tipo_arreglo',
		'n_plantasha_densidad',
		'n_plantasha',
		'porcentaje_descarte',
		'kilos_frutasha_efectivos',
		'kilos_frutasha_deseados',
		'cajas82ha_efectivas',
		'cajas82ha_deseados',
		'n_cajas_deseada_planta',
		'balance_cajas_efectiva_deseadas',
		'cajas82ha_deseadas_cuartel', 
	];
	



}
