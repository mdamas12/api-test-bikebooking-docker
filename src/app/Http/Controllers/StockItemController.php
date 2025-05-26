<?php

namespace App\Http\Controllers;

use App\Models\StockItem;
use Illuminate\Http\Request;

use App\Models\Company;
use App\Models\User; 
use App\Models\Size; 
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

class StockItemController extends Controller
{
    /**
     * Obtener todos los item (bikes tallas) por bike seleccionada
     */
    public function getItemsByBike($company, $bike_id)
    {


     $empresa = Company::with([
        'stocks.stock_items' => function ($query) use ($bike_id) {
            $query->where('bike_id', $bike_id)->with('size');
        }
    ])->findOrFail($company);

    $resultado = [];

    foreach ($empresa->stocks as $stock) {
        $itemsAgrupados = $stock->stock_items
            ->groupBy(function ($item) {
                return optional($item->size)->name ?? '';
            })
            ->map(function ($items) {
                return $items->sortBy(function ($item) {
                    // Definir la prioridad del estado
                    $prioridad = [
                        'active' => 1,
                        'reserved' => 2,
                        'maintenance' => 3,
                        'damaged' => 4,
                        'disabled' => 5, 
                        'sale' => 6
                    ];
                    return $prioridad[$item->status] ?? 99;
                })->values(); // resetea los índices
            });
    
        $resultado[$stock->name] = $itemsAgrupados;
    }

    return response()->json([
        'status' => true,
        'items' => $resultado
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
            'stock_id' => 'required|exists:stocks,id',
            'bike_id' => 'required|exists:bikes,id', 
            'code' => 'required|string|max:50|unique:stock_items,code', 
            //'status',['active','reserved','maintenance','damaged','disabled','sale']);
            'arrival' => 'required|date',  
        ];
        $validator = Validator::make($request->all(), $rules, ['required' => 'El Campo :attribute es requerido']);

        if ($validator->fails()){
            return response()->json($validator->errors());
        }
        if (empty($request->size_id)) {
            $newSize = Size::create([
                'company_id' => $request->company_id,
                'name' => $request->size_name,
            ]);
            $size_id =  $newSize->id;
        }
        else{
           $size_id = $request->size_id;
        }

        $item = StockItem::create([
            'stock_id' => $request->stock_id,
            'bike_id' => $request->bike_id,
            'code' => $request->code,
            'size_id' => $size_id,
            'arrival' => $request->arrival,
            'status' => 'active',
            'dimension' => $request->dimension,
        ]);

        return response()->json([
            'message' => 'Item created successfully',
            'item' => $item
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(StockItem $stockItem)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(StockItem $stockItem)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $stockItem)
    {
        $rules =  [
            'stock_id' => 'required|exists:stocks,id',
            'bike_id' => 'required|exists:bikes,id', 
            'code' => [Rule::unique('stock_items', 'code')->ignore($stockItem)], 
        ];
        $validator = Validator::make($request->all(), $rules, ['required' => 'El Campo :attribute es requerido']);

        if ($validator->fails()){
            return response()->json($validator->errors());
        }
        $item = StockItem::where('id', $stockItem)->first();
        $item->code = $request->code;
        $item->arrival = $request->arrival;
        $item->output = $request->output;
        $item->status = $request->status;
        $item->dimension = $request->dimension;
        $item->inbooking = $request->inbooking;
        $item->serial = $request->serial;
        $item->insale = $request->insale;

        $item->save();
        return response()->json([
            'message' => 'Item updated successfully',
            'item' => $item
        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(StockItem $stockItem)
    {
        //
    }
}
