<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Farm;
use JWTAuth;
class FarmController extends Controller
{
    public function all(){
        try {
            if (! $user = JWTAuth::parseToken()->authenticate()) {
                return response()->json(['user_not_found'], 404);
            }
            if($user->id_role==1){
                $response = [
                    'message'=> 'Lista de Campos',
                    'farms' => Farm::all(),
                    'user' => $user
                ];
                return response()->json($response, 200);
            }else{
                $farm_res=Farm::query()
                    ->join('user_farms', 'user_farms.id_farm', '=', 'farms.id')
                    ->where('user_farms.id_user', '=', $user->id)->get();
                $response = [
                    'message'=> 'Campos del usuario',
                    'farms' => $farm_res,
                    'user' => $user
                ];
                return response()->json($response, 200);
            }
        } catch (Tymon\JWTAuth\Exceptions\TokenExpiredException $e) {
            return response()->json(['token_expired'], $e->getStatusCode());
        } catch (Tymon\JWTAuth\Exceptions\TokenInvalidException $e) {
            return response()->json(['token_invalid'], $e->getStatusCode());
        } catch (Tymon\JWTAuth\Exceptions\JWTException $e) {
            return response()->json(['token_absent'], $e->getStatusCode());
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Ha ocurrido un error al tratar de eliminar los datos.',
                'error' => $e->getMessage(),
                'linea' => $e->getLine()
            ], 500);
        }      
    }  
    public function store(Request $request){
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:45|unique:farms',
            'description' => 'required|string',
            'address' => 'required|string',
            'social_reason' => 'required|string'
        ],[
            'name.required'                  => 'El nombre es requerido',
            'name.unique'                    => 'Ya existe un campo con el nombre',
            'name.max'                       => 'El nombre debe contener como máximo 45 caracteres',
            'description.required'           => 'La descripción es requerida',
            'address.required'               => 'La dirección es requerida',
            'social_reason.required'         => 'La razón social es requerida'
        ]);
        if($validator->fails()){
            return response()->json($validator->errors(), 400);
        }
        $farm = Farm::create([
            'name' => $request->get('name'),
            'description' => $request->get('description'),
            'address' => $request->get('address'),
            'social_reason' => $request->get('social_reason'),
        ]);
        $response = [
            'message'=> 'Campo registrado satisfactoriamente',
            'farm' => $farm,
        ];
        return response()->json($response, 200);
    } 
    public function get($id){
        try {
            $farm = Farm::find($id);
            if(is_null($farm)){
                return response()->json(["message"=>"Campo no existente"],404);
            }
            $response = [
                'message'=> 'Farm encontrado satisfactoriamente',
                'farm' => $farm,
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
            'name' => 'required|string|max:45',
            'description' => 'required|string',
            'address' => 'required|string',
            'social_reason' => 'required|string'
        ],[
            'name.required'                  => 'El nombre es requerido',
            'name.max'                       => 'El nombre debe contener como máximo 45 caracteres',
            'description.required'           => 'La descripción es requerida',
            'address.required'               => 'La dirección es requerida',
            'social_reason.required'         => 'La razón social es requerida'
        ]);
        if($validator->fails()){
            return response()->json($validator->errors(), 400);
        }
        try {
            $farm = Farm::find($id);
            if(is_null($farm)){
                return response()->json(["message"=>"Campo no existente"],404);
            }
            $farm->fill($request->all());
            $response = [
                'message'=> 'Farm actualizado satisfactoriamente',
                'farm' => $farm,
            ];
            $farm->update();
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
            $farm = Farm::find($id);
            if(is_null($farm)){
                return response()->json(["message"=>"Campo no existente"],404);
            }
            $farm->delete();
            $response = [
                'message'=> 'Campo eliminado satisfactoriamente',
                'farm' => $farm,
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
