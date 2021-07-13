<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Illuminate\Support\Facades\Validator;
use App\Poda;
use App\PodaCuartel;
use App\Farm;
use App\Employee;
use App\Quarter;
use App\Season;
use App\User;

Use DB;

class PodaController extends Controller
{
    public function all(){
        try {
            $poda= DB::select("select 
                podas.id, farms.name as farm, seasons.name as season, podas.created_at                     
                from 
                    `podas` 
                inner join 
                    farms on podas.id_farm=farms.id 
                inner join
                    seasons on podas.id_season=seasons.id
                order by podas.id desc
                ");
            $response = [
                'message'=> 'Lista de Poda',
                'poda' => $poda,
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
            'id_farm'        => 'required',
            'id_temporada'      => 'required', 
          //  'date'           => 'required',
            'total'          => 'required',            
            'id_client'      => 'required', 
            'id_user'        => 'required'
        ],[
            'id_farm.required'           => 'El working_day es requerido',            
            //'id_season.required'         => 'La temporada es requerida',
            'date.required'              => 'La fecha es requerido',
            'total.required'             => 'El row es requerido',            
            'id_client.required'         => 'El plant es requerido',
            'id_user.required'           => 'El id_user es requerido'
        ]);
        if($validator->fails()){
            return response()->json($validator->errors(), 400);
        }
        
        $season = Season::find($request->get('id_temporada'))?Season::find($request->get('id_temporada')):
            Season::where('name',$request->get('season_name'))->first();
        $farm = Farm::find($request->get('id_farm'))?Farm::find($request->get('id_farm')):
            Farm::where('name',$request->get('farm_name'))->first();
        $user = User::find($request->get('id_user'));
        
        $messages=[];
        if(is_null($season)||is_null($farm)||is_null($user)){            
          
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

        
        $poda = Poda::create([
            'id_farm' => $request->get('id_farm')?$request->get('id_farm'):$farm->id,            
            'date' => '2020-08-21 12:12:48',            
            'id_season' => $request->get('id_temporada')?$request->get('id_temporada'):$season->id,            
            'total' => $request->get('total'),
            'id_client' => $request->get('id_client'),       
            'id_user' => $request->get('id_user'),            
        ]);

        if($request->get('podaDataCuartel')){
            foreach ($request->get('podaDataCuartel') as $key => $cuartel) {
                $podaCuartel = PodaCuartel::create([
                'id_poda'              =>    $poda->id,             
                'id_quarter'           =>    $cuartel['id_cuartel'],
                'densidad_plantas'     =>    $cuartel['densidadPlantas'], 
                'plantas_muertas'      =>    $cuartel['plantasMuertas'], 
                'medias_plantas'       =>    $cuartel['mediasPlantas'], 	
                'porcentaje_desecho'   =>    $cuartel['porcentajeDesecho'],           
                'causa_desecho'        =>    $cuartel['causaDesecho'],
                'cargadores_plantas'   =>    $cuartel['cargadoresPlantas'],
                'yemas_cargador'          =>    $cuartel['yemasCargador'],
                'cargadores_plantas_alt2' =>    $cuartel['cargadoresPlantasAlt2'],
                'yemas_cargador_alt2'     =>    $cuartel['yemasCargadorAlt2'],
                'pitones_plantas'      =>    $cuartel['pitonesPlantas'],
                'yemas_piton'          =>    $cuartel['yemasPiton'],
                'n_yemas_plantas'      =>    $cuartel['nYemasPlantas'],
                'n_yemas_hectareas'    =>    $cuartel['nYemasHectareas'],
                'yemas_fertiles'       =>    $cuartel['yemasFertilesA1'],
                'yemas_fertilesA2'     =>    $cuartel['yemasFertilesA2'],
                'racimos_plantas_potenciales'       =>    $cuartel['racimosPlantasPotenciales'],
                'racimos_cargador_potenciales'      =>    $cuartel['racimosCargadorPotencialesA1'],
                'racimos_cargador_potencialesA2'    =>    $cuartel['racimosCargadorPotencialesA2'],
                'brotacion_esperada'              =>    $cuartel['brotacionEsperada'],
                'brotes_chupados'                 =>    $cuartel['brotesChupados'],
                'porcentaje_racimos_chico'        =>    $cuartel['porcentajeRacimosChico'],
                'porcentaje_racimos_medianos'     =>    $cuartel['porcentajeRacimosMedianos'],
                'porcentaje_racimos_grandes'      =>    $cuartel['porcentajeRacimosGrandes'],
                'gr_racimos_chico'                =>    $cuartel['grRacimosChico'],
                'gr_racimos_medianos'             =>    $cuartel['grRacimosMedianos'],
                'gr_racimos_grandes'              =>    $cuartel['grRacimosGrandes'],
                'racimos_efectivos_hectareas'     =>    $cuartel['racimosEfectivosHectareas'],
                'racimos_deseados_planta'         =>    $cuartel['racimosDeseadosPlanta'],
                'racimos_efectivos_planta'        =>    $cuartel['racimosEfectivosPlanta'],
                'racimos_deseados_hectareas'      =>    $cuartel['racimosDeseadosHectarea'],
                'racimos_eliminar'                =>    $cuartel['racimosEliminar'],  
                'tipo_arreglo'                    =>    $cuartel['tipoArreglo'],
                'n_plantasha_densidad'            =>    $cuartel['NplantashaDensidad'],
                'n_plantasha'                     =>    $cuartel['Nplantasha'],
                'porcentaje_descarte'             =>    $cuartel['porcentajeDescarte'],
                'kilos_frutasha_efectivos'        =>    $cuartel['kilosFrutashaEfectivos'],
                'kilos_frutasha_deseados'         =>    $cuartel['kilosFrutashaDeseados'],
                'cajas82ha_efectivas'             =>    $cuartel['cajas82haEfectivas'], 
                'cajas82ha_deseados'              =>    $cuartel['cajas82haDeseados'], 
                'n_cajas_deseada_planta'          =>    $cuartel['nCajasDeseadaPlanta'], 
                'balance_cajas_efectiva_deseadas' =>    $cuartel['balanceCajasEfectivaDeseadas'], 
                'cajas82ha_deseadas_cuartel'      =>    $cuartel['cajas82haDeseadasCuartel'] 
                ]);
            }
        }


        $response = [
            'message'=> 'Poda registrado satisfactoriamente',
            'poda' => $poda
            
        ];
        return response()->json($response, 200);
    } 
    public function get($id){
        try {
            $poda = Poda::find($id);
            $season = Season::find($poda->id_season); 
            $farm = Farm::find($poda->id_farm);
            $podaAnterior = Poda::query()                        
            ->join('seasons', 'podas.id_season', '=', 'seasons.id')
            ->where('seasons.year', ($season->year-1))
            ->select('podas.id','podas.id_season')
            ->first();
            $podaCuartel     = PodaCuartel::where('id_poda',$id)->get();
            $seasonAnterior= '';
            if($podaAnterior) {
                $seasonAnterior  = Season::find($podaAnterior->id_season); 
                $podaCuartelAnterior = PodaCuartel::where('id_poda',$podaAnterior->id)->get();
            }else{
                $podaCuartelAnterior = '';
            }
            

            if(is_null($poda)){
                return response()->json(["message"=>"Poda no existente"],404);
            }
            $response = [
                'message'=> 'Poda encontrado satisfactoriamente',
                'poda' => $poda,
                'poda_cuartel' => $podaCuartel,
                'poda_anterior' => $podaAnterior,
                'poda_cuartel_anterior' => $podaCuartelAnterior,
                'season' => $season,
                'season_anterior' => $seasonAnterior, 
                'farm' => $farm
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

    /** Actualiza la poda */
    public function update(Request $request,$id){
        $validator = Validator::make($request->all(), [
            'id_farm'        => 'required',
            'id_temporada'      => 'required', 
          //  'date'           => 'required',
            'total'          => 'required',            
            'id_client'      => 'required', 
            'id_user'        => 'required'
        ],[
            'id_farm.required'           => 'El working_day es requerido',            
            'id_temporada.required'         => 'La temporada es requerida',
            'date.required'              => 'La fecha es requerido',
            'total.required'             => 'El row es requerido',            
            'id_client.required'         => 'El plant es requerido',
            'id_user.required'           => 'El id_user es requerido'
        ]);
        if($validator->fails()){
            return response()->json($validator->errors(), 400);
        }
        try {
            $season = Season::find($request->get('id_temporada'));
            $farm   = Farm::find($request->get('id_farm'));
            $user   = User::find($request->get('id_user'));
            $poda   = Poda::find($id);
            $messages=[];
            if(is_null($season)||is_null($user)||is_null($farm)||is_null($poda)){         
                if(is_null($season)){
                array_push($messages,"Temporada no existente");
                }
                if(is_null($farm)){
                array_push($messages,"Campo no existente");
                }
                if(is_null($user)){
                array_push($messages,"Usuario no existente");
                }
                if(is_null($poda)){
                array_push($messages,"Poda no existente");
                }
                return response()->json(["message"=>$messages],404);
            }
            $poda->fill($request->all());
            DB::table('poda_cuartels')->where('id_poda', $id)->delete();
            if($request->get('podaDataCuartel')){
                foreach ($request->get('podaDataCuartel') as $key => $cuartel) {
                    $podaCuartel = PodaCuartel::create([
                    'id_poda'              =>    $poda->id,             
                    'id_quarter'           =>    $cuartel['id_cuartel'],
                    'densidad_plantas'     =>    $cuartel['densidadPlantas'], 
                    'plantas_muertas'      =>    $cuartel['plantasMuertas'], 
                    'medias_plantas'       =>    $cuartel['mediasPlantas'], 	
                    'porcentaje_desecho'   =>    $cuartel['porcentajeDesecho'],           
                    'causa_desecho'        =>    $cuartel['causaDesecho'],
                    'cargadores_plantas'   =>    $cuartel['cargadoresPlantas'],
                    'yemas_cargador'          =>    $cuartel['yemasCargador'],
                    'cargadores_plantas_alt2' =>    $cuartel['cargadoresPlantasAlt2'],
                    'yemas_cargador_alt2'     =>    $cuartel['yemasCargadorAlt2'],
                    'pitones_plantas'      =>    $cuartel['pitonesPlantas'],
                    'yemas_piton'          =>    $cuartel['yemasPiton'],
                    'n_yemas_plantas'      =>    $cuartel['nYemasPlantas'],
                    'n_yemas_hectareas'    =>    $cuartel['nYemasHectareas'],
                    'yemas_fertiles'       =>    $cuartel['yemasFertilesA1'],
                    'yemas_fertilesA2'     =>    $cuartel['yemasFertilesA2'],
                    'racimos_plantas_potenciales'       =>    $cuartel['racimosPlantasPotenciales'],
                    'racimos_cargador_potenciales'      =>    $cuartel['racimosCargadorPotencialesA1'],
                    'racimos_cargador_potencialesA2'    =>    $cuartel['racimosCargadorPotencialesA2'],
                    'brotacion_esperada'              =>    $cuartel['brotacionEsperada'],
                    'brotes_chupados'                 =>    $cuartel['brotesChupados'],
                    'porcentaje_racimos_chico'        =>    $cuartel['porcentajeRacimosChico'],
                    'porcentaje_racimos_medianos'     =>    $cuartel['porcentajeRacimosMedianos'],
                    'porcentaje_racimos_grandes'      =>    $cuartel['porcentajeRacimosGrandes'],
                    'gr_racimos_chico'                =>    $cuartel['grRacimosChico'],
                    'gr_racimos_medianos'             =>    $cuartel['grRacimosMedianos'],
                    'gr_racimos_grandes'              =>    $cuartel['grRacimosGrandes'],
                    'racimos_efectivos_hectareas'     =>    $cuartel['racimosEfectivosHectareas'],
                    'racimos_deseados_planta'         =>    $cuartel['racimosDeseadosPlanta'],
                    'racimos_efectivos_planta'        =>    $cuartel['racimosEfectivosPlanta'],
                    'racimos_deseados_hectareas'      =>    $cuartel['racimosDeseadosHectarea'],
                    'racimos_eliminar'                =>    $cuartel['racimosEliminar'],  
                    'tipo_arreglo'                    =>    $cuartel['tipoArreglo'],
                    'n_plantasha_densidad'            =>    $cuartel['NplantashaDensidad'],
                    'n_plantasha'                     =>    $cuartel['Nplantasha'],
                    'porcentaje_descarte'             =>    $cuartel['porcentajeDescarte'],
                    'kilos_frutasha_efectivos'        =>    $cuartel['kilosFrutashaEfectivos'],
                    'kilos_frutasha_deseados'         =>    $cuartel['kilosFrutashaDeseados'],
                    'cajas82ha_efectivas'             =>    $cuartel['cajas82haEfectivas'], 
                    'cajas82ha_deseados'              =>    $cuartel['cajas82haDeseados'], 
                    'n_cajas_deseada_planta'          =>    $cuartel['nCajasDeseadaPlanta'], 
                    'balance_cajas_efectiva_deseadas' =>    $cuartel['balanceCajasEfectivaDeseadas'], 
                    'cajas82ha_deseadas_cuartel'      =>    $cuartel['cajas82haDeseadasCuartel'] 
                    ]);
                }
            }
            $response = [
                'message'=> 'Poda actualizado satisfactoriamente',
                'poda' => $poda,
            ];
            $poda->update();
            return response()->json($response, 200);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Ha ocurrido un error al tratar de guardar los datos.',
                'error' => $e->getMessage(),
                'linea' => $e->getLine()
            ], 500);
        }
    }
    /**Elimina la poda */
    public function delete($id){
        try {
            $poda = Poda::find($id);
            if(is_null($poda)){
                return response()->json(["message"=>"Poda no existente"],404);
            }
            $poda->delete();
            $response = [
                'message'=> 'Poda eliminado satisfactoriamente',
                'poda' => $poda,
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
