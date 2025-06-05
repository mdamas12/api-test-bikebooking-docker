<?php

namespace App\Http\Controllers;

use App\Models\Rate;
use App\Models\FeatureByRate;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Illuminate\Support\Str;
use Illuminate\Support\Carbon;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\Log;

class RateController extends Controller
{
   /**
    * Listar Tarifas
    */
    public function index($company)
    {
    $rates = Rate::with('rates_by_bikes')
    ->where('company_id', $company)
    ->get()
    ->map(function ($rate) {
        $rate->feature_rate_ids = $rate->rates_by_bikes->pluck('feature_rate_id')->toArray();
        return $rate;
    })
    ->toArray();
        return response()->json([
            'status' => true,
            'rates' => $rates
        ],200); 
    }

    /*
    * Crear Tarifa
    */

    public function store(Request $request)
    {
        try {
            $rules =  [
                'company_id' => 'required|exists:companies,id',
                'name' => ['required','string','max:50', Rule::unique('feature_rates', 'name')],
            ];
            $validator = Validator::make($request->all(), $rules, 
                ['required' => 'El Campo :attribute es requerido',
                'unique' => 'El Campo :attribute ya está registrado.',
            ]);

            if ($validator->fails()){     
                return response()->json([
                    'status' => false,
                    'message' =>  $validator->errors()->all()
                ],400);
            }

            $Rate = Rate::create([
                'company_id' => $request->company_id,
                'name' => $request->name, 
                'description' => $request->description, 
                'value' => $request->value, 
                'status' => $request->status,
            ]);
            if ($Rate){
                foreach ($request->selectedFeatures as $feature) {
                    $featureByrate = FeatureByRate::create([
                        'rate_id' => $Rate->id,
                        'feature_rate_id' => $feature,
                    ]);
                } 
            }
          
            return response()->json([
                'status' => true,
                'message' => 'La caracteristica  ha sido creada con éxito',
            ],201); 

        } catch (\Exception $e) {
            Log::error('Error inesperado al crear Caracteristica: ' . $e->getMessage());
            return response()->json([
                'status' => false,
                'message' => $e->getMessage(),
            ],500);
        
        }
    }

    public function update(Request $request, $rate_id)
    {
        try {
            
            $rate = Rate::findOrFail($rate_id); 

            $rules =  [
                'company_id' => 'required|exists:companies,id',
                'name' => ['required','string','max:50', Rule::unique('rates', 'name')->ignore($rate_id)],
            ];
            $validator = Validator::make($request->all(), $rules, 
                ['required' => 'El Campo :attribute es requerido',
                'unique' => 'El Campo :attribute ya está registrado.',
            ]);

            if ($validator->fails()){
                return response()->json([
                    'status' => false,
                    'message' => $validator->errors()->all()
                ],400);
            }

            $rate->update([
                'company_id' => $request->company_id,
                'name' => $request->name, 
                'description' => $request->description, 
                'value' => $request->value, 
                'status' => $request->status,
            ]);

            $selectedFeatures = $request->selectedFeatures;

            // Obtener todos los IDs actuales asociados a esa tarifa
            $existingFeatures = FeatureByRate::where('rate_id', $rate_id)->pluck('feature_rate_id')->toArray();

            // Calcular IDs para eliminar (los que ya existen pero no fueron seleccionados)
            $featuresToDelete = array_diff($existingFeatures, $selectedFeatures);

            // Calcular IDs para agregar (los que fueron seleccionados pero no existen)
            $featuresToInsert = array_diff($selectedFeatures, $existingFeatures);

            // Eliminar los que sobran
            FeatureByRate::where('rate_id', $rate_id)
                ->whereIn('feature_rate_id', $featuresToDelete)
                ->delete();

            // Agregar los nuevos
            foreach ($featuresToInsert as $feature_id) {
                FeatureByRate::create([
                    'rate_id' => $rate_id,
                    'feature_rate_id' => $feature_id,
                ]);
            } 

            return response()->json([
                'status' => true,
                'message' => 'La Tarifa  ha sido actualizada con éxito',
            ],200); 

        } catch (ModelNotFoundException $e) {
            return response()->json([
                'status' => false,
                'message' => 'Tarifa no encontrada',
            ],404); 

        } catch (\Exception $e) {
            Log::error('Error inesperado al actualizar Tarifa: ' . $e->getMessage());
            return response()->json([
                'status' => false,
                'message' => $e->getMessage(),
            ],500);
        
        }
    }

    public function destroy($rate_id)
    {
        try {

            FeatureByRate::where('rate_id', $rate_id)
                ->delete();
            
            $rate = Rate::findOrFail($rate_id); 
            $rate->delete();

            return response()->json([
                'status' => true,
                'message' => 'La Tarifa  ha sido eliminada con éxito',
            ],201); 

        } catch (\Exception $e) {
            Log::error('Error inesperado al eliminar tarifa: ' . $e->getMessage());
            return response()->json([
                'status' => false,
                'message' => $e->getMessage(),
            ],500);
        
        }
    }
    
}
