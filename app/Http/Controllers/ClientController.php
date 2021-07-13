<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Tymon\JWTAuth\Exceptions\JWTException;
use App\Client;
use App\Role;
use App\Farm;
use App\Quarter;
use App\ClientFarms;
use JWTAuth;
class ClientController extends Controller
{    
  
    public function all(){
        try {
            $response = [
                'message'=> 'Lista de clientes',
                'clientes' => Client::all(),
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
    public function get($id){
        try {
            $client = Client::with("farms")->find($id);
            $farm_res=Farm::query()
            ->join('client_farms', 'client_farms.id_farm', '=', 'farms.id')
            ->where('client_farms.id_client', '=', $id)->get();
            if(is_null($client)){
                return response()->json(["message"=>"Cliente no existente"],404);
            }
            $response = [
                'message'=> 'Cliente encontrado satisfactoriamente',
                'client' => $client,
                'farms' => $farm_res
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
    protected function registerFarmsByClient($clientId,$farms){
        foreach ($farms as $key => $farmId) {
            ClientFarms::create([
                'id_client' => $clientId,
                'id_farm'   => $farmId,
            ]);
        }        
    }
    public function store(Request $request){
        $validator = Validator::make($request->get("client_data"), [
            'company_name'      => 'required',
            'company_producer'  => 'required',
            'company_rut'       => 'required',
            'company_email'     => 'required',
        ],[
            'company_name.required'          => 'El nombre es requerido',
            'company_producer.required'      => 'El nombre del productor es requerido',
            'company_rut.required'           => 'El rut de la compañia es requerido',            
            'company_email.required'         => 'El email de la compañia es requerido',            
        ]);
        if($validator->fails()){
            return response()->json($validator->errors(), 400);
        }
        $client = Client::create([
            'company_name' => $request->get("client_data")['company_name'],
            'company_producer' => $request->get("client_data")['company_producer'],
            'company_rut' => $request->get("client_data")['company_rut'],
            'company_email' => $request->get("client_data")['company_email'],            
        ]);
        if(count($request->get('farmsSelected'))>0){            
            $this->registerFarmsByClient($client->id,$request->get('farmsSelected'));
        }
        $response = [
            'message'=> 'Cliente registrado satisfactoriamente',
            'client' => $client,
        ];
        return response()->json($response, 200);
    }
    protected function updateFarmsByClient($clientId,$farms){        
        $clientFarms=ClientFarms::where("id_client",$clientId)->get();
        foreach ($clientFarms as $key => $value) {
            $value->delete();
        }
        $this->registerFarmsByClient($clientId,$farms);
    }
    public function update(Request $request,$id){
        $validator = Validator::make($request->get("client_data"), [
            'company_name'      => 'required',
            'company_producer'  => 'required',
            'company_rut'       => 'required',
            'company_email'     => 'required',
        ],[
            'company_name.required'          => 'El nombre es requerido',
            'company_producer.required'      => 'El nombre del productor es requerido',
            'company_rut.required'           => 'El rut de la compañia es requerido',            
            'company_email.required'         => 'El email de la compañia es requerido',            
        ]);
        if($validator->fails()){
            return response()->json($validator->errors(), 400);
        }
        try {
            $client = Client::find($id);
            if(is_null($client)){
                return response()->json(["message"=>"Cliente no existente"],404);
            }
            
            $client->fill($request->get("client_data"));

            if(count($request->get("farmsSelected"))>0){            
                $this->updateFarmsByClient($client->id,$request->get('farmsSelected'));
            }
        
            $response = [
                'message'=> 'Cliente actualizado satisfactoriamente',
                'client' => $client,
            ];
            $client->update();
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
            $client = Client::find($id);
            if(is_null($client)){
                return response()->json(["message"=>"Cliente no existente"],404);
            }
            $client->delete();
            $response = [
                'message'=> 'Cliente eliminado satisfactoriamente',
                'client' => $client,
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

    public function getFarmClient($id){
        try {
            $farm_res=Farm::query()
            ->join('client_farms', 'client_farms.id_farm', '=', 'farms.id')
            ->where('client_farms.id_client', '=', $id)->get();
            $response = [
                'message'=> 'Campos del cliente',
                'farms' => $farm_res,                
            ];
        return response()->json($response, 200);
            return response()->json($response, 200);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Ha ocurrido un error al tratar de obtener los datos.',
                'error' => $e->getMessage(),
                'linea' => $e->getLine()
            ], 500);
        }
    }
    /*public function farms($id){
        try {
            if($id){
                $user = User::find($id);
                if (!$user) {
                    return response()->json(['user_not_found'], 404);
                }
            }elseif (! $user = JWTAuth::parseToken()->authenticate()) {
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
    }*/


    
}
