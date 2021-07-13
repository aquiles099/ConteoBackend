<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Illuminate\Support\Facades\Validator;
use App\Count;
use App\Farm;
use App\Employee;
use App\Quarter;
use App\Season;
use App\User;

Use DB;

class CountController extends Controller
{
    public function all(){
        try {
            $response = [
                'message'=> 'Lista de Conteo',
                'counts' => Count::all(),
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
            'working_day'    => 'required|string|max:45',
            'countscol'      => 'required', 
            'date'           => 'required',
            'row'            => 'required',
            'cant'           => 'required',
            'plant'          => 'required', 
            'id_user'        => 'required'
        ],[
            'working_day.required'       => 'El working_day es requerido',
            'working_day.max'            => 'El working_day debe contener como máximo 45 caracteres',
            'countscol.required'         => 'El countscol es requerido',
            'date'                       => 'La fecha es requerido',
            'row'                        => 'El row es requerido',
            'cant'                       => 'El cant es requerido',
            'plant'                      => 'El plant es requerido',
            'id_user.required'           => 'El id_user es requerido'
        ]);
        if($validator->fails()){
            return response()->json($validator->errors(), 400);
        }
        $employee = Employee::find($request->get('id_employee'))?Employee::find($request->get('id_employee')):Employee::where('rut',$request->get('employee_rut'))->first();
        $quarter = Quarter::find($request->get('id_quarter'))?Quarter::find($request->get('id_quarter')):
            Quarter::where('name',$request->get('quarter_name'))->first();
        $season = Season::find($request->get('id_season'))?Season::find($request->get('id_season')):
            Season::where('name',$request->get('season_name'))->first();
        $farm = Farm::find($request->get('id_farm'))?Farm::find($request->get('id_farm')):
            Farm::where('name',$request->get('farm_name'))->first();
        $user = User::find($request->get('id_user'));
        
        $messages=[];
        if(is_null($employee)||is_null($quarter)||is_null($season)||is_null($farm)||is_null($user)){
            if(is_null($employee)){
               array_push($messages,"Empleado no existente");
            }
            if(is_null($quarter)){
               array_push($messages,"Cuartel no existente");
            }
            if(is_null($season)){
               array_push($messages,"Temporada no existente");
            }
            if(is_null($farm)){
               array_push($messages,"Campo no existente");
            }
            if(is_null($user)){
               array_push($messages,"Usuario no existente");
            }
            return response()->json(["message"=>$messages],404);
        }

        //$diff=(($season->number_clusters_desired- $request->get('cant')*100)/$season->number_clusters_desired);

        $count = Count::create([
            'id_employee' => $request->get('id_employee')?$request->get('id_employee'):$employee->id,
            'employee_rut' => $request->get('employee_rut')?$request->get('employee_rut'):$employee->rut,
            'working_day' => $request->get('working_day'),
            'countscol' => $request->get('countscol')=='' ? 'Completado': $request->get('countscol'),
            'date' => $request->get('date'),
            'id_quarter' => $request->get('id_quarter')?$request->get('id_quarter'):$quarter->id,
            'quarter_name' => $request->get('quarter_name')?$request->get('quarter_name'):$quarter->name,
            'id_season' => $request->get('id_season')?$request->get('id_season'):$season->id,
            'season_name' => $request->get('season_name')?$request->get('season_name'):$season->name,
            'id_farm' => $request->get('id_farm')?$request->get('id_farm'):$farm->id,
            'farm_name' => $request->get('farm_name')?$request->get('farm_name'):$farm->name,
            'row' => $request->get('row'),
            'plant' => $request->get('plant'),
            'cant' => $request->get('cant'),
            'obj' => $season->number_clusters_desired,            
            'diff' => 1,//number_format((float)$diff, 2, '.', '').'%',
            'id_user' => $request->get('id_user'),
            'registered_in_batch' => $request->get('registered_in_batch') ? true : false
        ]);
        $response = [
            'message'=> 'Conteo registrado satisfactoriamente',
            'count' => $count
            
        ];
        return response()->json($response, 200);
    } 
    public function get($id){
        try {            
            $count = Count::find($id);
            if(is_null($count)){
                return response()->json(["message"=>"Count no existente"],404);
            }
            $response = [
                'message'=> 'Conteo encontrado satisfactoriamente',
                'count' => $count,
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
            'id_employee'    => 'required',
            'working_day'    => 'required|string|max:45', 
            'countscol'      => 'required|string|max:45', 
            'date'           => 'required',
            'id_quarter'     => 'required',
            'id_season'      => 'required',
            'id_farm'        => 'required',
            'row'            => 'required|integer',
            'plant'          => 'required|string|max:45', 
            'id_user'        => 'required'
        ],[
            'id_employee.required'       => 'El id_employee es requerido',
            'working_day.required'       => 'El working_day es requerido',
            'working_day.max'            => 'El working_day debe contener como máximo 45 caracteres',
            'countscol.required'         => 'El countscol es requerido',
            'countscol.max'              => 'El countscol debe contener como máximo 45 caracteres',
            'date'                       => 'La fecha es requerido',
            'id_quarter.required'        => 'El id_quarter es requerido',
            'id_season.required'         => 'El id_season es requerido',
            'id_farm.required'           => 'El id_farm es requerido',
            'row'                        => 'El row es requerido',
            'row.integer'                => 'El row debe ser un número entero',
            'plant.required'             => 'El plant es requerido',
            'plant.max'                  => 'El plant debe contener como máximo 45 caracteres',
            'id_user.required'           => 'El id_user es requerido'
        ]);
        if($validator->fails()){
            return response()->json($validator->errors(), 400);
        }
        try {
            $employee = Employee::find($request->get('id_employee'));
            $quarter = Quarter::find($request->get('id_quarter'));
            $season = Season::find($request->get('id_season'));
            $farm = Farm::find($request->get('id_farm'));
            $user = User::find($request->get('id_user'));
            $count = Count::find($id);
            $messages=[];
            if(is_null($employee)||is_null($quarter)||is_null($season)||is_null($user)||is_null($farm)||is_null($count)){
                if(is_null($employee)){
                array_push($messages,"Empleado no existente");
                }
                if(is_null($quarter)){
                array_push($messages,"Quarter no existente");
                }
                if(is_null($season)){
                array_push($messages,"Temporada no existente");
                }
                if(is_null($farm)){
                array_push($messages,"Campo no existente");
                }
                if(is_null($user)){
                array_push($messages,"Usuario no existente");
                }
                if(is_null($count)){
                array_push($messages,"Conteo no existente");
                }
                return response()->json(["message"=>$messages],404);
            }
            $count->fill($request->all());
            $response = [
                'message'=> 'Count actualizado satisfactoriamente',
                'count' => $count,
            ];
            $count->update();
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
            $count = Count::find($id);
            if(is_null($count)){
                return response()->json(["message"=>"Count no existente"],404);
            }
            $count->delete();
            $response = [
                'message'=> 'Count eliminado satisfactoriamente',
                'count' => $count,
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

    public function cardData(){
        
        try {
            $farm = DB::table('farms')->count();
            $employee = DB::table('employees')->count();
            $quarter = DB::table('quarters')->count();        

            $response = [            
                'farm' => $farm,
                'employee' => $employee,
                'quarter' => $quarter,
            ];

            return response()->json($response, 200);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Ha ocurrido un error.',
                'error' => $e->getMessage(),
                'linea' => $e->getLine()
            ], 500);
        }
        
    }

    public function conteoRacimos(Request $request){
        
        $dates=[];
        $employee_array=[];
        $counts_data=[];
        $counts_result=[];
        $counts_date_result=[];
        $counts= Count::query()->select('date')->groupBy('date')->get();   
        $user_id=$request->user_id;
        $farm=$request->farm;
        $quarters=$request->quarters;
        $season=$request->season;        
        $query_far_employee= $farm!='' ? 'WHERE counts.id_farm='.$farm:'' ;
        $query_far= $farm!='' ? ' and counts.id_farm='.$farm:'' ;
        $query_quart= $quarters? ' and counts.id_quarter='.$quarters: '';
        $query_seas=$season ? ' and counts.id_season='.$season:'';

        if($farm==''){
            return response()->json([
                'message' => 'Ha ocurrido un error.',
                'error' => 'No se encuentra campos registrados'
                
            ], 500);            

        }else if($quarters==''){
            return response()->json([
                'message' =>  'Ha ocurrido un error.',
                'error' => 'No se encuentra cuarteles registrados',
                
            ], 500);  
        }else if($season==''){
            return response()->json([
                'message' => 'Ha ocurrido un error.',
                'error' => 'No se encuentra temoporadas registradas'
                
            ], 500);  

        }
        

        $query_posicion=DB::select("select SUM(cant) as suma  from counts where cant=28" );        

        foreach ($counts as $count){            
            array_push($dates,$count->date );
        }

        foreach($dates as $date){             
            $counts_ori= DB::select("select 
                DAY(counts.date) as day,MONTH(counts.date) as mon,quarters.number_clusters_desired as deseado, COUNT(counts.id) as contar, SUM(counts.cant) as suma, STDDEV(counts.cant) as dev 
                from 
                    `counts` 
                inner join 
                    quarters on counts.id_quarter=quarters.id 
                where `date` = '$date' ".$query_far.$query_quart.$query_seas);

            array_push($counts_data, $counts_ori);        
        }

        //Empleados para conteo


        $employees=DB::select("
                SELECT counts.id, id_employee, employees.name,employees.last_name 
                    FROM 
                counts 
                    inner join 
                employees 
                    on 
                employees.id=counts.id_employee    
            ".$query_far_employee.$query_quart.$query_seas." Group by id_employee ");
        
        //return $employees;
    

        foreach ($employees as $employee){                 
            $employe_data = [            
                'id'       => $employee->id,
                'name'     => $employee->name." ".$employee->last_name
               
            ];    
            array_push($employee_array,$employe_data);
        }

        foreach($employee_array as $emplo){            

            foreach($dates as $date){        


                $counts_employee= DB::select("select 
                DAY(counts.date) as day,MONTH(counts.date) as mon,COUNT(counts.id) as contar, SUM(counts.cant) as suma 
                from 
                    counts 
                inner join 
                    seasons on counts.id_season=seasons.id 
                where  counts.id_employee=".$emplo['id']." and date = '$date' ".$query_far.$query_quart.$query_seas);    

                array_push($counts_date_result, $counts_employee);        
                  
            }
            

            $employe_count = [            
                'employee'   => $emplo['name'],
                'count'      => $counts_date_result
               
            ];
            
            array_push($counts_result, $employe_count);  

        } 

        $employee_res=Employee::count();
        $user_data = User::find($user_id);

        if($user_data->id_role==1){
            $quarter_res=Quarter::count();
            $farm_res=Farm::count();

        }else{
            $quarter_res=Quarter::query()               
            ->join('user_farms', 'user_farms.id_farm', '=', 'quarters.id_farm')
            ->where('user_farms.id_user', '=', $user_id)->count();   

            $farm_res=Farm::query()               
            ->join('user_farms', 'user_farms.id_farm', '=', 'farms.id')
            ->where('user_farms.id_user', '=', $user_id)->count();    ;
        }


        
        $response = [            
            'counts'      => $counts_data,
            'posicion'    => $query_posicion,
            'employees'   => $counts_result,
            'quarter'     => $quarter_res,
            'farms'       => $farm_res,
            'employee_res'=> $employee_res,

        ];



        return $response;
        
        
        
    }
    public function getImportDates(){
        try {
            $counts=Count::select([
            DB::raw('DATE(created_at)'),
            ])->where("registered_in_batch",1)->groupBy('created_at')->distinct()->get();
            $response = [
                'counts' => $counts
            ];

            return response()->json($response, 200);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Ha ocurrido un error.',
                'error' => $e->getMessage(),
                'linea' => $e->getLine()
            ], 500);
        }
        
    }
    public function getRecordsPerBatch(Request $request){
        try {
            $counts=Count::where("registered_in_batch",1)
                ->where("created_at",">=",$request->date." 00:00:00")
                ->where("created_at","<=",$request->date." 23:59:59")
                ->get();
            $response = [
                'date' => $request->date,
                'counts' => $counts
            ];

            return response()->json($response, 200);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Ha ocurrido un error.',
                'error' => $e->getMessage(),
                'linea' => $e->getLine()
            ], 500);
        }        
    }
    public function deleteRecordsPerBatch(Request $request){
        try {
            Count::whereIn('id', $request->deleteElementsIds)->delete(); 
            $response = [
                'message' => 'Conteos eliminados satisfactoriamente'
            ];

            return response()->json($response, 200);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Ha ocurrido un error.',
                'error' => $e->getMessage(),
                'linea' => $e->getLine()
            ], 500);
        }        
    }
}
