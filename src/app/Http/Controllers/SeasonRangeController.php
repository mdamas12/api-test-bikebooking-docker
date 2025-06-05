<?php

namespace App\Http\Controllers;

use App\Models\SeasonRange;
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

class SeasonRangeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index($company)
    {
        $periods = SeasonRange::with(['season'])
        ->where('company_id', $company)
        ->get();

        return response()->json([
            'status' => true,
            'periods' => $periods
        ], 200);
    }

public function getPeriodbyBike($company, $id)
{
    $bikeId = $id;
   
    $periods = SeasonRange::with('season')
        ->with(['season_bikes' => function ($query) use ($bikeId) {
            $query->where('bike_id', $bikeId);
        }])
        ->where('company_id', $company)
        ->get()
        ->map(function ($period) use ($bikeId) {
            $baseValue = $period->season_bikes->isEmpty();
    

            $season_bikes_inicial = (object) [
                'id' => null,
                'bike_id' => $bikeId,
                'season_range_id' => $period->id,
                'value' => 0,
                'status' => 0,
                'created_at' => null,
                'updated_at' => null,
            ];

            return [
                'id' => $period->id,
                'company_id' => $period->company_id,
                'ini_season' => $period->ini_season,
                'end_season' => $period->end_season,
                'status' => $period->status,
                'created_at' => $period->created_at,
                'updated_at' => $period->updated_at,
                'season_id' => $period->season_id,
                'base_value' => $baseValue,
                'current_base_value' => $baseValue,
                'value' =>  $period->value,  
                'bike_value' =>   $period->season_bikes->first()?->value ?? 0,
                'current_bike_value' =>   $period->season_bikes->first()?->value ?? 0,
                'season' => $period->season,  
                'season_bikes' => $period->season_bikes,
            ];
        });

        return response()->json([
            'status' => true,
            'periods' => $periods,
        ], 200);
}


    

    /**
     * Registrar un nuevo periodo de temporada
     */
    public function store(Request $request)
    {  


        try {
            $rules =  [
                'company_id' => 'required|exists:companies,id',
                'season_id' => 'required|exists:seasons,id',
                'value' => 'numeric',
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

            $period = SeasonRange::create($request->all());

            return response()->json([
                'status' => true,
                'message' => 'Periodo registrado con éxito',
            ],201); 

        } catch (\Exception $e) {
            Log::error('Error inesperado al registrar periodo: ' . $e->getMessage());
            return response()->json([
                'status' => false,
                'message' => 'Ocurrió un error inesperado',
            ],500);
        
        }
    }

    /**
     * Actualizar Periodo .
     */
    public function update(Request $request, $period_id)
    {
        $period = SeasonRange::where('id', $period_id)->first();
        $period->update($request->all());  
    }

    /**
    * Eliminar Periodo.
    */

    public function destroy( $id)
    {

        try {
            $period = SeasonRange::findOrFail($id); 

            $period->delete(); 

            return response()->json([
                'status' => true,
                'message' => 'Periodo eliminado con éxito',
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
