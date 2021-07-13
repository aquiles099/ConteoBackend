<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Tymon\JWTAuth\Exceptions\JWTException;
use App\User;
use App\Role;
use App\Farm;
use App\Quarter;
use App\UserFarms;
use App\Client;
use App\UserClient;

use JWTAuth;
class UserController extends Controller
{    
    public function roles(){
        try {
            $roles = Role::all();
            $response = [
                'message'=> 'Roles obtenidos satisfactoriamente',
                'roles' => $roles,
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
    public function all(){
        try {
            $response = [
                'message'=> 'Lista de usuarios',
                'users' => User::with("role")->get(),
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
            $user = User::with("role","farms")->find($id);
            if(is_null($user)){
                return response()->json(["message"=>"Usuario no existente"],404);
            }
            $response = [
                'message'=> 'Usuario encontrado satisfactoriamente',
                'user' => $user,
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
    protected function registerFarmsByUser($userId,$farms){
        foreach ($farms as $key => $farmId) {
            UserFarms::create([
                'id_user' => $userId,
                'id_farm' => $farmId,
            ]);
        }        
    }

    protected function registerClientsByUser($userId,$clientes){
        foreach ($clientes as $key => $farmId) {
            UserClient::create([
                'id_user' => $userId,
                'id_client' => $farmId,
            ]);
        }        
    }


    public function store(Request $request){
        $validator = Validator::make($request->get("user_data"), [
            'name' => 'required|string|max:45',
            'last_name' => 'required|string|max:45',
            'email' => 'required|string|email|max:45|unique:users',
            'id_role' => 'required',
            'password' => 'required|string|min:8|confirmed',
        ],[
            'name.required'                  => 'El nombre es requerido',
            'name.max'                       => 'El nombre debe contener como máximo 45 caracteres',
            'last_name.required'             => 'El apellido es requerido',
            'last_name.max'                  => 'El apellido debe contener como máximo 45 caracteres',
            'email.unique'                   => 'Este email ya se encuentra en uso',
            'email.email'                    => 'El email debe de tener un formato ejemplo@ejemplo.com',
            'email.required'                 => 'El email es requerido',
            'id_role.required'               => 'El rol es requerido',
            'password.min'                   => 'La contraseña debe de tener minimo 8 caracteres',
            'password_confirmation.required' => 'La confirmación de la contraseña es requerida',
            'password.confirmed'             => 'Las contraseña no coinciden vuelva a intentar',
        ]);
        if($validator->fails()){
            return response()->json($validator->errors(), 400);
        }
        $user = User::create([
            'name' => $request->get("user_data")['name'],
            'last_name' => $request->get("user_data")['last_name'],
            'email' => $request->get("user_data")['email'],
            'rut' => $request->get("user_data")['rut'],
            'id_role' => $request->get("user_data")['id_role'],          
            'module'   => $request->get("user_data")['module'],
            'password' => Hash::make($request->get("user_data")['password']) 
        ]);
        if(count($request->get('farmsSelected'))>0){            
            $this->registerFarmsByUser($user->id,$request->get('farmsSelected'));
        }

        if(count($request->get('clientsSelected'))>0){            
            $this->registerClientsByUser($user->id,$request->get('clientsSelected'));
        }
        $response = [
            'message'=> 'Usuario registrado satisfactoriamente',
            'user' => $user,
        ];
        return response()->json($response, 200);
    }
    protected function updateFarmsByUser($userId,$farms){        
        $userFarms=UserFarms::where("id_user",$userId)->get();
        foreach ($userFarms as $key => $value) {
            $value->delete();
        }
        $this->registerFarmsByUser($userId,$farms);
    }

    protected function updateClientsByUser($userId,$clients){        
        $userClient=UserClient::where("id_user",$userId)->get();
        foreach ($userClient as $key => $value) {
            $value->delete();
        }
        $this->registerClientsByUser($userId,$clients);
    }

    public function update(Request $request,$id){
        $validator = Validator::make($request->get("user_data"), [
            'name' => 'required|string|max:45',
            'last_name' => 'required|string|max:45',
            'email' => 'required|string|email|max:45',
            'id_role' => 'required',
            'password' => 'string|min:8|confirmed',
        ],[
            'name.required'                  => 'El nombre es requerido',
            'name.max'                       => 'El nombre debe contener como máximo 45 caracteres',
            'last_name.required'             => 'El apellido es requerido',
            'last_name.max'                  => 'El apellido debe contener como máximo 45 caracteres',
            'email.email'                    => 'El email debe de tener un formato ejemplo@ejemplo.com',
            'email.required'                 => 'El email es requerido',
            'id_role'                        => 'El rol es requerido',
            'password.min'                   => 'La contraseña debe de tener minimo 8 caracteres',
            'password.confirmed'             => 'Las contraseña no coinciden vuelva a intentar',
        ]);
        if($validator->fails()){
            return response()->json($validator->errors(), 400);
        }
        try {
            $user = User::find($id);
            if(is_null($user)){
                return response()->json(["message"=>"Usuario no existente"],404);
            }
            $pass_last = $user->password;
            $user->fill($request->get("user_data"));

            if(count($request->get("farmsSelected"))>0){            
                $this->updateFarmsByUser($user->id,$request->get('farmsSelected'));
            }

            if(count($request->get("clientsSelected"))>0){            
                $this->updateClientsByUser($user->id,$request->get('clientsSelected'));
            }

            if (!is_null($request->get("user_data")['password']) && !empty($request->get("user_data")['password'])) {
                $user->password = bcrypt($request->get("user_data")['password']);
            } else {
                $user->password = $pass_last;
            }
            $response = [
                'message'=> 'Usuario actualizado satisfactoriamente',
                'user' => $user
            ];
            $user->update();
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
            $user = User::find($id);
            if(is_null($user)){
                return response()->json(["message"=>"Usuario no existente"],404);
            }
            $user->delete();
            $response = [
                'message'=> 'Usuario eliminado satisfactoriamente',
                'user' => $user,
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
    public function farms($id){
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
    }

    public function farmsClient($id){
        try {
            if($id){
                $client = Client::find($id);
                if (!$client) {
                    return response()->json(['user_not_found'], 404);
                }
            }elseif (! $user = JWTAuth::parseToken()->authenticate()) {
                return response()->json(['user_not_found'], 404);
            }
        
            $farm_res=Farm::query()
                ->join('client_farms', 'client_farms.id_farm', '=', 'farms.id')
                ->where('client_farms.id_client', '=', $client->id)
                ->select('farms.id', 'farms.name')
                ->get();
            $response = [
                'message'=> 'Campos del cliente',
                'farms' => $farm_res,
                'client' => $client
            ];
            return response()->json($response, 200);
        
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

    public function clients($id){
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
                    'message'=> 'Lista de clientes',
                    'clientes' => Client::all(),
                    'user' => $user
                ];
                return response()->json($response, 200);
            }else{
                $client_res= Client::query()
                ->join('user_clients', 'user_clients.id_client', '=', 'clients.id')
                ->where('user_clients.id_user', '=', $user->id)
                ->select('clients.id', 'clients.company_email', 'clients.company_name', 'clients.company_producer', 'clients.company_rut')->get();
                $response = [
                    'message'=> 'Clientes del usuario2',
                    'clientes' => $client_res,
                    'user' => $user
                ];
                return response()->json($response, 200);
            }

          
            return response()->json($response, 200);
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

    public function quarters($id){
        
        $user = User::find($id);

        if($user->id_role==1){

            $response = [
                'message'=> 'Lista de quarters',
                'quarters' => Quarter::all(),
            ];
            return response()->json($response, 200);

        }else{

            $quarter_res=Quarter::query()               
                    ->join('user_farms', 'user_farms.id_farm', '=', 'quarters.id_farm')
                    ->where('user_farms.id_user', '=', $id)
                    ->select('quarters.id', 'quarters.name', 'quarters.description', 'quarters.hectareas', 'quarters.variety',  'quarters.portainjerto')->get();

            $response = [
                'message'=> 'Lista de quarters',
                'quarters' => $quarter_res,
            ];
            return response()->json($response, 200);

        }


        
      
    }
    
}
