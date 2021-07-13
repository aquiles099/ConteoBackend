<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Employee;
use App\EmployeeSeasons;
class EmployeeController extends Controller
{
    public function all(){
        try {
            $response = [
                'message'=> 'Lista de empleados',
                'empleados' => Employee::all(),
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
    protected function registerSeasonsByEmployee($employeeId,$seasons){
        foreach ($seasons as $key => $seasonId) {
            EmployeeSeasons::create([
                'id_employee' => $employeeId,
                'id_season' => $seasonId,
            ]);
        }        
    }
    public function store(Request $request){
        $validator = Validator::make($request->get("employee_data"), [
            'name' => 'required|string|max:45',
            'last_name' => 'required|string|max:45',
            'email' => 'required|string|email|max:45',
            'direction' => 'required|string',
            'rut' => 'required|string|unique:employees',
        ],[
            'name.required'                  => 'El nombre es requerido',
            'name.max'                       => 'El nombre debe contener como máximo 45 caracteres',
            'last_name.required'             => 'El apellido es requerido',
            'last_name.max'                  => 'El apellido debe contener como máximo 45 caracteres',
            'email.email'                    => 'El email debe de tener un formato ejemplo@ejemplo.com',
            'email.required'                 => 'El email es requerido',
            'direction.required'             => 'La dirección es requerida',
            'rut.required'                   => 'El rut es requerido',
            'rut.unique'                     => 'Ya existe un empleado con el rut',
        ]);
        if($validator->fails()){
            return response()->json($validator->errors(), 400);
        }
        $employee = Employee::create([
            'name' => $request->get('employee_data')['name'],
            'last_name' => $request->get('employee_data')['last_name'],
            'email' => $request->get('employee_data')['email'],
            'rut' => $request->get('employee_data')['rut'],
            'direction' => $request->get('employee_data')['direction'],
            'active' => $request->get('employee_data')['active']
        ]);
        if(count($request->get('seasonsSelected'))>0){            
            $this->registerSeasonsByEmployee($employee->id,$request->get('seasonsSelected'));
        }
        $response = [
            'message'=> 'Empleado registrado satisfactoriamente',
            'employee' => $employee,
        ];
        return response()->json($response, 200);
    } 
    public function get($id){
        try {
            $employee = Employee::find($id);
            if(is_null($employee)){
                return response()->json(["message"=>"Empleado no existente"],404);
            }
            $response = [
                'message'=> 'Empleado encontrado satisfactoriamente',
                'employee' => $employee,
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
    protected function updateSeasonsByEmployee($employeeId,$seasons){        
        $employeeSeasons=EmployeeSeasons::where("id_employee",$employeeId)->get();
        foreach ($employeeSeasons as $key => $value) {
            $value->delete();
        }
        $this->registerSeasonsByEmployee($employeeId,$seasons);
    }
    public function update(Request $request,$id){
        $validator = Validator::make($request->get("employee_data"), [
            'name' => 'required|string|max:45',
            'last_name' => 'required|string|max:45',
            'email' => 'required|string|email|max:45',
            'direction' => 'required|string',
        ],[
            'name.required'                  => 'El nombre es requerido',
            'name.max'                       => 'El nombre debe contener como máximo 45 caracteres',
            'last_name.required'             => 'El apellido es requerido',
            'last_name.max'                  => 'El apellido debe contener como máximo 45 caracteres',
            'email.email'                    => 'El email debe de tener un formato ejemplo@ejemplo.com',
            'email.required'                 => 'El email es requerido',
            'direction.required'             => 'La dirección es requerida',
        ]);
        if($validator->fails()){
            return response()->json($validator->errors(), 400);
        }
        try {
            $employee = Employee::find($id);
            if(is_null($employee)){
                return response()->json(["message"=>"Empleado no existente"],404);
            }

            $employee->fill($request->get("employee_data"));

            if(count($request->get('seasonsSelected'))>0){            
                $this->updateSeasonsByEmployee($employee->id,$request->get('seasonsSelected'));
            }
            $response = [
                'message'=> 'Empleado actualizado satisfactoriamente',
                'employee' => $employee,
            ];
            $employee->update();
            return response()->json($response, 200);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Ha ocurrido un error al tratar de guardar los datos.',
                'error' => $e->getMessage(),
                'linea' => $e->getLine()
            ], 500);
        }
    }
    public function seasons($id){
        $response = [
            'message'=> 'Temporadas del empleado',
            'seasons' => EmployeeSeasons::where("id_employee",$id)->get(),
        ];
        return response()->json($response, 200);
    }
    public function delete($id){
        try {
            $employee = Employee::find($id);
            if(is_null($employee)){
                return response()->json(["message"=>"Empleado no existente"],404);
            }
            $employee->delete();
            $response = [
                'message'=> 'Empleado eliminado satisfactoriamente',
                'employee' => $employee,
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
