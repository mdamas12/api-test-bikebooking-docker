<?php

namespace App\Http\Controllers;

use App\Models\SeasonBike;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

use Illuminate\Support\Str;
use Illuminate\Support\Carbon;

/** Para el manejo de exception en try/catch */

use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\Log;

class SeasonBikeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            $rules =  [
                'season_range_id' => 'required|exists:season_ranges,id',
                'bike_id' => 'required|exists:bikes,id',
                'value' => 'required|numeric',
            ];
            $validator = Validator::make($request->all(), $rules, 
                ['required' => 'El Campo :attribute es requerido',
                'unique' => 'El Campo :attribute ya está registrado.',
            ]);

            if ($validator->fails()){
                return response()->json([
                    'status' => false,
                    'message' => $validator->errors()
                ],400);
            }

            $periodbyBike = SeasonBike::create($request->all());

            return response()->json([
                'status' => true,
                'message' => 'Periodo para bicileta actualzado con éxito',
            ],201); 

        } catch (\Exception $e) {
            Log::error('Error inesperado al actualizar periodo: ' . $e->getMessage());
            return response()->json([
                'status' => false,
                'message' => $e->getMessage(),
            ],500);
        
        }
    }

    /**
     * Modificar periodo (temporada) para una bicicleta
     */
    public function modifyPeriodBike(Request $request)
    {

        foreach ($request->periods as $period) {

            if (($period["bike_value"] != $period["current_bike_value"]) || ($period["base_value"] != $period["current_base_value"])){
                
                if($period["base_value"] === false){

                    if(empty($period["season_bikes"])){

                 

                        $periodbyBike = SeasonBike::create([
                            'bike_id' => $request->bike_id,
                            'season_range_id' => $period["id"],
                            'value' => $period["bike_value"],
                            'status' => 1
                        ]);
                    } 
                    else{
           
                    $season_bike  = $period["season_bikes"][0]; 
                    $periodbyBike = SeasonBike::findOrFail($season_bike["id"]); 
                    $periodbyBike->value = $period["bike_value"];
                    $periodbyBike->save();

                    }
                }
                else{
                     $season_bike  = $period["season_bikes"][0]; 
                     $periodbyBike = SeasonBike::findOrFail($season_bike["id"]);  
                     $periodbyBike->delete();
                }
            }
        }

        return response()->json([
            'status' => true,
            'message' => 'Periodo para bicileta actualzado con éxito',
        ],200);
        
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(SeasonBike $seasonBike)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {  
        try {

            $period = SeasonBike::findOrFail($id); 

            $rules =  [
                'season_range_id' => 'required|exists:season_ranges,id',
                'bike_id' => 'required|exists:bikes,id',
                'value' => 'required|numeric'
            ];
            $validator = Validator::make($request->all(), $rules, 
                ['required' => 'El Campo :attribute es requerido',
                'unique' => 'El Campo :attribute ya está registrado.',
            ]);

            if ($validator->fails()){
                return response()->json([
                    'status' => false,
                    'message' => $validator->errors()
                ],400);
            }
            else{
               $period->update($request->all()); 
                return response()->json([
                    'status' => true,
                    'message' => 'Periodo actualizado con éxito',
                ],200);
            }
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'status' => false,
                'message' => 'Periodo no encontrado',
            ],404); 

        } catch (\Exception $e) {
            Log::error('Error inesperado al actualizar periodo: ' . $e->getMessage());
            return response()->json([
                'status' => false,
                'message' =>  $e->getMessage(),
            ],500);
        
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy( $id)
    {
        try {
            $period = SeasonBike::findOrFail($id); 

            $period->delete(); 

            return response()->json([
                'status' => true,
                'message' => 'Periodo actualizado con éxito',
            ],200); 

        } catch (ModelNotFoundException $e) {
            return response()->json([
                'status' => false,
                'message' => 'Periodo no encontrado',
            ],404); 

        } catch (QueryException $e) {
            // Posibles errores como violación de claves foráneas
            Log::error('Error en la consulta al eliminar la Periodo: ' . $e->getMessage());
            return response()->json([
                'status' => false,
                'message' => 'No se puede eliminar el Periodo, puede estar relacionado con otros datos.',
            ],409); 
        } catch (\Exception $e) {
            Log::error('Error inesperado al eliminar Periodo: ' . $e->getMessage());
            return response()->json([
                'status' => false,
                'message' => 'Ocurrió un error inesperado',
            ],500);
        
        }
    }
}
