<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Quarter;
class QuarterController extends Controller
{
    public function all(){
        try {
            $response = [
                'message'=> 'Lista de Cuarteles',
                'quarters' => Quarter::all(),
            ];
            return response()->json($response, 200);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Ha ocurrido un error al tratar de obtener los datos.',
                'error' => $e->getMessage(),
                'linea' => $e->getLine()
            ], 500);
        }
    }  
    public function store(Request $request){
        $validator = Validator::make($request->all(), [
            'name'                         => 'required|string|max:45|unique:quarters',
            'number_plants'                => 'required|integer',
            'number_plants_equivalent'     => 'required|integer',
            'number_plants_ha'             => 'required|integer',
            'number_plants_equivalent_ha'  => 'required|integer',
            'number_clusters_desired'      => 'required|integer',
            'number_clusters_ha'           => 'required|integer',
            'number_clusters_for_quarters' => 'required|integer',
            'description'                  => 'required|string',            
            'variety'                      => 'required|string',
            'portainjerto'                 => 'required|string',
            'id_farm'                      => 'required',
            'formacion'                    => 'required',
            'edad_plantas'                 => 'required',
            'distancia_entrehilera'        => 'required',
            'distancia_sobrehilera'        => 'required',
            'densidad_plantas'             => 'required',
        ],[
            'name.required'                         => 'El nombre es requerido',
            'name.unique'                           => 'Ya existe un cuartel con el nombre',
            'name.max'                              => 'El nombre debe contener como máximo 45 caracteres',
            'number_plants.required'                => 'El number_plants es requerido', 
            'number_plants.integer'                 => 'El number_plants debe ser un número entero',
            'number_plants_equivalent.required'     => 'El number_plants_equivalent es requerido',
            'number_plants_equivalent.integer'      => 'El number_plants_equivalent debe ser un número entero',
            'number_plants_ha.required'             => 'El number_plants_ha es requerido', 
            'number_plants_ha.integer'              => 'El number_plants_ha debe ser un número entero',
            'number_plants_equivalent_ha.required'  => 'El number_plants_equivalent_ha es requerido',
            'number_plants_equivalent_ha.integer'   => 'El number_plants_equivalent_ha debe ser un número entero',
            'number_clusters_desired.required'      => 'El number_clusters_desired es requerido',
            'number_clusters_desired.integer'       => 'El number_clusters_desired debe ser un número entero',
            'number_clusters_ha.required'           => 'El number_clusters_ha es requerido',
            'number_clusters_ha.integer'            => 'El number_clusters_ha debe ser un número entero',
            'number_clusters_for_quarters.required' => 'El number_clusters_for_quarters es requerido',
            'number_clusters_for_quarters.integer'  => 'El number_clusters_for_quarters debe ser un número entero',
            'description.required'                  => 'La descripción es requerida',
            'hectareas.required'                    => 'La hectareas es requerida',
            'variety.required'                      => 'La variedad es requerida',
            'portainjerto.required'                 => 'El portainjerto es requerido',
            'id_farm'                               => 'Debe seleccionar un campo',
            'formacion.required'                    => 'La formación es requerida ',
            'edad_plantas.required'                 => 'La edad de las plantas es requerido',
            'distancia_entrehilera.required'        => 'La distancia entre hilera es requerido',
            'distancia_sobrehilera.required'        => 'La distancia sobre hilera es requerido',
            'densidad_plantas.required'             => 'La densidad de plantas es requerido'
        ]);
        if($validator->fails()){
            return response()->json($validator->errors(), 400);
        }

        $quarter = Quarter::create([
            'name' => $request->get('name'),
            'number_plants' => $request->get('number_plants'),
            'number_plants_equivalent' => $request->get('number_plants_equivalent'),
            'number_plants_ha' => $request->get('number_plants_ha'),
            'number_plants_equivalent_ha' => $request->get('number_plants_equivalent_ha'),
            'number_clusters_desired' => $request->get('number_clusters_desired'),
            'number_clusters_ha' => $request->get('number_clusters_ha'),
            'number_clusters_for_quarters' => $request->get('number_clusters_for_quarters'),
            'description'              => $request->get('description'),
            'hectareas'                => $request->get('superficie'),
            'variety'                  => $request->get('variety'),
            'portainjerto'             => $request->get('portainjerto'),
            'id_farm'                  => intval($request->get('id_farm')),
            'formacion'                => $request->get('formacion'),
            'edad_plantas'             => $request->get('edad_plantas'),
            'distancia_entrehilera'    => $request->get('distancia_entrehilera'),
            'distancia_sobrehilera'    => $request->get('distancia_sobrehilera'),
            'densidad_plantas'         => $request->get('densidad_plantas'),
            'superficie'               => $request->get('superficie'),
            
        ]);
        $response = [
            'message'=> 'Cuartel registrado satisfactoriamente',
            'quarter' => $quarter,
        ];
        return response()->json($response, 200);
    } 
    public function get($id){
        try {
            $quarter = Quarter::find($id);
            if(is_null($quarter)){
                return response()->json(["message"=>"Cuartel no existente"],404);
            }
            $response = [
                'message'=> 'Cuartel encontrado satisfactoriamente',
                'quarter' => $quarter,
            ];
            return response()->json($response, 200);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Ha ocurrido un error al tratar de obtener los datos.',
                'error' => $e->getMessage(),
                'linea' => $e->getLine()
            ], 500);
        }
    }
    public function update(Request $request,$id){
        $validator = Validator::make($request->all(), [
            'name'                         => 'required|string|max:45',
            'number_plants'                => 'required|integer',
            'number_plants_equivalent'     => 'required|integer',
            'number_plants_ha'             => 'required|integer',
            'number_plants_equivalent_ha'  => 'required|integer',
            'number_clusters_desired'      => 'required|integer',
            'number_clusters_ha'           => 'required|integer',
            'number_clusters_for_quarters' => 'required|integer',
            'description'                  => 'required|string',            
            'variety'                      => 'required|string',
            'portainjerto'                 => 'required|string',
            'formacion'                    => 'required',
            'edad_plantas'                 => 'required',
            'distancia_entrehilera'        => 'required',
            'distancia_sobrehilera'        => 'required',
            'densidad_plantas'             => 'required',
            'superficie'                   => 'required',
        ],[
            'name.required'                         => 'El nombre es requerido',
            'name.max'                              => 'El nombre debe contener como máximo 45 caracteres',
            'number_plants.required'                => 'El number_plants es requerido', 
            'number_plants.integer'                 => 'El number_plants debe ser un número entero',
            'number_plants_equivalent.required'     => 'El number_plants_equivalent es requerido',
            'number_plants_equivalent.integer'      => 'El number_plants_equivalent debe ser un número entero',
            'number_plants_ha.required'             => 'El number_plants_ha es requerido', 
            'number_plants_ha.integer'              => 'El number_plants_ha debe ser un número entero',
            'number_plants_equivalent_ha.required'  => 'El number_plants_equivalent_ha es requerido',
            'number_plants_equivalent_ha.integer'   => 'El number_plants_equivalent_ha debe ser un número entero',
            'number_clusters_desired.required'      => 'El number_clusters_desired es requerido',
            'number_clusters_desired.integer'       => 'El number_clusters_desired debe ser un número entero',
            'number_clusters_ha.required'           => 'El number_clusters_ha es requerido',
            'number_clusters_ha.integer'            => 'El number_clusters_ha debe ser un número entero',
            'number_clusters_for_quarters.required' => 'El number_clusters_for_quarters es requerido',
            'number_clusters_for_quarters.integer'  => 'El number_clusters_for_quarters debe ser un número entero',
            'description.required'                  => 'La descripción es requerida',
            'hectareas.required'                    => 'La hectareas es requerida',
            'variety.required'                      => 'La variedad es requerida',
            'portainjerto.required'                 => 'El portainjerto es requerido',
            'formacion.required'                    => 'La formación es requerida ',
            'edad_plantas.required'                 => 'La edad de las plantas es requerido',
            'distancia_entrehilera.required'        => 'La distancia entre hilera es requerido',
            'distancia_sobrehilera.required'        => 'La distancia sobre hilera es requerido',
            'densidad_plantas.required'             => 'La densidad de plantas es requerido',
            'superficie.required'                   => 'Superficie es requerido'
        ]);
        if($validator->fails()){
            return response()->json($validator->errors(), 400);
        }
        try {
            $quarter = Quarter::find($id);
            if(is_null($quarter)){
                return response()->json(["message"=>"Cuartel no existente"],404);
            }
            $quarter->fill($request->all());
            $quarter->update();
           
            $response = [
                'message'=> 'Cuartel actualizado satisfactoriamente',
                'quarter' => $quarter,
            ];
            
            return response()->json($response, 200);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Ha ocurrido un error al tratar de guardar los datos.',
                'error' => $e->getMessage(),
                'linea' => $e->getLine()
            ], 500);
        }
    }
    public function delete($id){
        try {
            $quarter = Quarter::find($id);
            if(is_null($quarter)){
                return response()->json(["message"=>"Cuartel no existente"],404);
            }
            $quarter->delete();
            $response = [
                'message'=> 'Cuartel eliminado satisfactoriamente',
                'quarter' => $quarter,
            ];
            return response()->json($response, 200);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Ha ocurrido un error al tratar de eliminar los datos.',
                'error' => $e->getMessage(),
                'linea' => $e->getLine()
            ], 500);
        }
    }

    public function getFarm($id){
        try {
            $quarter_res=Quarter::query()                           
            ->where('id_farm', '=', $id)->get();

            $response = [
                'message'=> 'Lista de cuarteles por campo',
                'quarters' => $quarter_res,
            ];
            return response()->json($response, 200);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Ha ocurrido un error al tratar de obtener los datos.',
                'error' => $e->getMessage(),
                'linea' => $e->getLine()
            ], 500);
        }
    }
    
}
