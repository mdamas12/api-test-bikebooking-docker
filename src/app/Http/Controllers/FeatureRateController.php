<?php

namespace App\Http\Controllers;

use App\Models\FeatureRate;
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

class FeatureRateController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index($company)
    {
        $features = FeatureRate::where('company_id',$company)->get();
        return response()->json([
                'status' => true,
                'message' => 'La caracteristica  ha sido creada con éxito',
                'features' => $features
            ],200); 
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
          
        //
    }

    /**
     * Store a newly created resource in storage.
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
                    'message' => $validator->errors()
                ],400);
            }

            $featureRate = FeatureRate::create($request->all());

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


    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        try {
            
            $feature = FeatureRate::findOrFail($id); 

            $rules =  [
                'company_id' => 'required|exists:companies,id',
                'name' => ['required','string','max:50', Rule::unique('feature_rates', 'name')->ignore($id)],
            ];
            $validator = Validator::make($request->all(), $rules, 
                ['required' => 'El Campo :attribute es requerido',
                'unique' => 'El Campo :attribute ya está registrado.',
            ]);

            if ($validator->fails()){
                $error =  $validator->errors()->all();
                return response()->json([
                    'status' => false,
                    'message' => $error
                ],400);
            }

            $feature->update($request->all());

            return response()->json([
                'status' => true,
                'message' => 'La caracteristica  ha sido actualizada con éxito',
            ],201); 

        } catch (ModelNotFoundException $e) {
            return response()->json([
                'status' => false,
                'message' => 'Caracteristica no encontrada',
            ],404); 

        } catch (\Exception $e) {
            Log::error('Error inesperado al actualizar Caracteristica: ' . $e->getMessage());
            return response()->json([
                'status' => false,
                'message' => $e->getMessage(),
            ],500);
        
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        try {
            
            $feature = FeatureRate::findOrFail($id); 
            $feature->delete();

            return response()->json([
                'status' => true,
                'message' => 'La caracteristica  ha sido eliminada con éxito',
            ],201); 

        } catch (\Exception $e) {
            Log::error('Error inesperado al eliminar Caracteristica: ' . $e->getMessage());
            return response()->json([
                'status' => false,
                'message' => $e->getMessage(),
            ],500);
        
        }
    }
}
