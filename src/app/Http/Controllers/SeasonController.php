<?php

namespace App\Http\Controllers;

use App\Models\Season;
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

class SeasonController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index($company)
    {
        $seasons = Season::where('company_id', $company)->get();

        return response()->json([
            'status' => true,
            'seasons' => $seasons
        ], 200);
    }


    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {

        $rules =  [
            'company_id' => 'required|exists:companies,id',
            'name' => 'required|string|max:50|unique:seasons,name',
        ];
        $validator = Validator::make($request->all(), $rules, 
            ['required' => 'El Campo :attribute es requerido',
            'unique' => 'El Campo :attribute ya está registrado.',
        ]);

        if ($validator->fails()){
            return response()->json([
                'status' => false,
                'error' => $validator->errors()
            ],400);
        }

        $season = Season::create([
            'company_id' => $request->company_id,
            'name' => $request->name,
        ]);

        return response()->json([
            'status' => true,
            'message' => 'season created successfully',
            'season' => $season
         ],201);
    }

    /**
     * Display the specified resource.
     */
    public function show(TypeSeason $typeSeason)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(TypeSeason $typeSeason)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $season = Season::where('id', $id)->first(); 
        
        $rules =  [
            'company_id' => 'required|exists:companies,id',
            'name' => ['required','string','max:50', Rule::unique('seasons', 'name')->ignore($season->id)],
        ];
        $validator = Validator::make($request->all(), $rules, 
            ['required' => 'El Campo :attribute es requerido',
            'unique' => 'El Campo :attribute ya está registrado.',
        ]);

        if ($validator->fails()){
            return response()->json([
                'status' => false,
                'error' => $validator->errors()
            ],400);
        }
        else{
            $season->update($request->all());  
            return response()->json([
                'status' => true,
                'message' => 'Season  updated successfully',
            ],200);  
        }

    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy( $id)
    {

        try {
            $season = Season::findOrFail($id); 

            $season->delete(); 

            return response()->json([
                'status' => true,
                'message' => 'Temporada eliminada con éxito',
            ],200); 

        } catch (ModelNotFoundException $e) {
            return response()->json([
                'status' => false,
                'message' => 'Temporada no encontrada',
            ],404); 

        } catch (QueryException $e) {
            // Posibles errores como violación de claves foráneas
            Log::error('Error en la consulta al eliminar la temporada: ' . $e->getMessage());
            return response()->json([
                'status' => false,
                'message' => 'No se puede eliminar la temporada, puede estar relacionada con otros datos.',
            ],409); 
        } catch (\Exception $e) {
            Log::error('Error inesperado al eliminar la temporada: ' . $e->getMessage());
            return response()->json([
                'status' => false,
                'message' => 'Ocurrió un error inesperado',
            ],500);
        
        }
    }
}
