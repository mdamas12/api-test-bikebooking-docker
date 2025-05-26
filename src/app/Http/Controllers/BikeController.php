<?php

namespace App\Http\Controllers;

use App\Models\Bike;
use Illuminate\Http\Request;

use App\Models\Company;
use App\Models\User;
use App\Models\Stock;
use App\Models\PriceRange;
use App\Models\PriceBikeRange;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Carbon;
use Illuminate\Validation\Rule;
use App\Notifications\CompanyApplication;
use App\Notifications\CompanyAtivated;
use Illuminate\Support\Facades\Notification;

class BikeController extends Controller
{
    /**
     * Listar Bicicletas
     * Lista de todas las bicicletas por empresa ordenadas por orden.
     */
    public function index($companyId)
    {

        $bikes = Bike::with(['type_bike','insurance','image_bike'])
            ->where('company_id', $companyId)
            ->orderBy('order')
            ->get();
 
        return response()->json([
            'status' => true,
            'bikes' => $bikes
        ], 200);
    }


    /**
     * Detalle de Bicicleta
     * Muestra toda la informacion de la bicicleta.
     */
    public function show($bike_id)
    {
        $bike = Bike::with(['type_bike','insurance','image_bike'])
                ->where('id', $bike_id)
                ->first();
 
        return response()->json([
            'status' => true,
            'bike' => $bike
        ], 200);
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
        $rules =  [
            'company_id' => 'required|exists:companies,id',
            'type_bike_id' => 'required|exists:type_bikes,id',
            'name' => 'required|string|max:150',
            'price' => 'required|numeric|min:0',
            
        ];
        $validator = Validator::make($request->all(), $rules, ['required' => 'El Campo :attribute es requerido']);

        if ($validator->fails()){
            return response()->json($validator->errors());
        }
       
        $bike = Bike::create($request->all());
        $bike->load('type_bike', 'insurance', 'image_bike');
        $newOrden = $this->updateOrderBike($request->order, $bike);
        $this->createPriceByrange($bike->company_id, $bike->id, $bike->price);
        return response()->json([
            'status' => true,
            'message' => 'Bike created successfully',
            'bike' => $bike
        ], 201);
    }

  

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Bike $bike)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request,  $bike_id)
    {
        $bike = Bike::where('id', $bike_id)->first();
        try{
            if(!$bike){
                throw new \Exception('error trying to updated Bike'); 
            }
            else{

                $rules =  [
                    'company_id' => 'required|exists:companies,id',
                    'type_bike_id' => 'required|exists:type_bikes,id',
                    'name' => 'required|string|max:150',
                    'price' => 'required|numeric|min:0',
                    'status' => 'boolean'
                    
                ];
                $validator = Validator::make($request->all(), $rules, ['required' => 'El Campo :attribute es requerido']);
        
                if ($validator->fails()){
                    throw new \Exception($validator->errors()); 
                }
                else{
                    if ($bike->order != $request->order){
                        $newOrden = $this->updateOrderBike($request->order, $bike);
                    }
                    $bike->update($request->all());  
                    return response()->json([
                        'status' => true,
                        'message' => 'Bike updated successfully',
                        'bike' => $bike
                    ],200);
                }

            }
        }catch(\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 400);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Bike $bike)
    {
        //
    }

    // Crear nueva imagen
    public function createImage(Request $request)
        {
            $validator = Validator::make($request->all(), [
                'bike_id' => 'required|exists:bikes,id',
                'image' => 'required|image|max:2048', // max 2MB
                'is_featured' => 'boolean'
            ]);
    
            if ($validator->fails()) {
                return response()->json(['errors' => $validator->errors()], 422);
            }
    
            if ($request->is_featured) {
                // Si esta imagen será destacada, desmarcar las otras
                ImageBike::where('bike_id', $request->bike_id)
                    ->update(['is_featured' => false]);
            }
    
            $path = $request->file('image')->store('bikes', 'public');
    
            $imageBike = ImageBike::create([
                'bike_id' => $request->bike_id,
                'path' => $path,
                'is_featured' => $request->is_featured ?? false,
            ]);
    
            return response()->json($imageBike, 201);
    }
    
    public function createPriceByrange($company_id, $bike_id, $price){
        $ranges = PriceRange::where('company_id',$company_id)->get();

        foreach ($ranges as $range) {
            if ($range->apply_to === 'quantity_days'){

                $priceBikeByrange = PriceBikeRange::create([
                    'bike_id' => $bike_id,
                    'price_range_id' => $range->id,
                    'value' => $price,
                ]); 
            }
            else{
                $priceBikeByrange = PriceBikeRange::create([
                    'bike_id' => $bike_id,
                    'price_range_id' => $range->id,
                    'value' => 0,
                ]);   
            }
        }
       
    }

    public function updateOrderBike($newOrder, Bike $bike)
    {
        $total = Bike::count();
        if ($newOrder > $bike->order) {
            // Mover otras hacia abajo
            Bike::where('order', '<=', $newOrder)
                ->where('order', '<>', 1)
                ->decrement('order');
        } else {
            // Mover otras hacia arriba
            Bike::where('order', '>=', $newOrder)
                ->where('order', '<>', $total)
                ->increment('order');
        }

        return $newOrder;
    }

}
