<?php

namespace App\Http\Controllers;

use App\Models\BikeAccesory;
use Illuminate\Http\Request;
use App\Models\Accesory;
use App\Models\CategoryAccesory;
use App\Models\Company;

use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Carbon;
use Illuminate\Validation\Rule;

class BikeAccesoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function getSelectedBybike($company_id, $bike_id, )
    {
     // Accesorios activos agrupados por categoría
     $accesories = Accesory::with('category_accesory')
     ->where('company_id', $company_id)
     ->where('status', true)
     ->get()
     ->groupBy(fn($item) => optional($item->category_accesory)->name ?? 'Sin categoría');

        // Obtener los IDs de los accesorios asignados a la bicicleta
        $bikeAccesoryIds = BikeAccesory::where('bike_id', $bike_id)->pluck('accesory_id')->toArray();

        // Formatear los accesorios agregando la propiedad `selected`
        $resultado = [];
        foreach ($accesories as $categoria => $items) {
            $resultado[$categoria] = $items->map(function ($item) use ($bikeAccesoryIds) {
                return [
                    'id' => $item->id,
                    'name' => $item->name,
                    'selected' => in_array($item->id, $bikeAccesoryIds),
                ];
            });
        }

        return response()->json([
            'status' => true,
            'accesories' => $resultado,
        ], 200);
    }

    public function saveAccesoryBybike(Request $request)
    { 
        foreach ($request->accesoriesSelected as $accessory) {

            $accesorySelect = BikeAccesory::where('accesory_id',$accessory['id'] )->first();

            if(($accesorySelect) && ($accessory['selected'] === false)) {
                $accesorySelect->delete();
            } else {
                $accesorySaved = BikeAccesory::create([
                    'bike_id' => $request->bike_id,
                    'accesory_id' => $accessory['id'] ,
                ]);
            }
        }
        return response()->json([
            'status' => true,
        ], 200);
        
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(BikeAccesory $bikeAccesory)
    {
        //
    }
}
