<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Season;

class SeasonController extends Controller
{
    public function all(){
        try {
            $response = [
                'message'=> 'Lista de temporada',
                'seasons' => Season::all(),
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
            'name'                         => 'required|string|unique:seasons',
            'description'                  => 'required|string',
            'year'                         => 'required|string',
        ],[
            'name.required'                => 'El nombre es requerido',
            'name.unique'                  => 'Ya existe una temporada con el nombre',
            'description.required'         => 'La descripción es requerida',
            'year.required'                => 'El año es requerido',
        ]);
        if($validator->fails()){
            return response()->json($validator->errors(), 400);
        }
        $season = Season::create([
            'name'                          => $request->get('name'),
            'description'                   => $request->get('description'),
            'year'                          => $request->get('year')
        ]);
        $response = [
            'message'=> 'Season registrado satisfactoriamente',
            'season' => $season,
        ];
        return response()->json($response, 200);
    } 
    public function get($id){
        try {
            $season = Season::find($id);
            if(is_null($season)){
                return response()->json(["message"=>"Season no existente"],404);
            }
            $response = [
                'message'=> 'Season encontrado satisfactoriamente',
                'season' => $season,
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
            'name'                         => 'required|string',
            'description'                  => 'required|string',
            'year'                         => 'required|string',
        ],[
            'name.required'                => 'El nombre es requerido',
            'description.required'         => 'La descripción es requerida',
            'year.required'                => 'El año es requerido',
        ]);
        if($validator->fails()){
            return response()->json($validator->errors(), 400);
        }
        try {
            $season = Season::find($id);
            if(is_null($season)){
                return response()->json(["message"=>"Temporada no existente"],404);
            }            
            $season->fill($request->all());
            $response = [
                'message'=> 'Temporada actualizado satisfactoriamente',
                'season' => $season,
            ];
            $season->update();
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
            $season = Season::find($id);
            if(is_null($season)){
                return response()->json(["message"=>"Season no existente"],404);
            }
            $season->delete();
            $response = [
                'message'=> 'Season eliminado satisfactoriamente',
                'season' => $season,
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
}
