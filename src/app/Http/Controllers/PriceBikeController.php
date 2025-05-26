<?php

namespace App\Http\Controllers;

use App\Models\PriceBikeRange;
use App\Models\PriceRange;
use Illuminate\Http\Request;

use App\Models\Company;
use App\Models\User;
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

class PriceBikeController extends Controller
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
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $rules =  [
            'stock_item_id' => 'required|exists:stock_items,id',
            'price_range_id' => 'required|exists:price_ranges,id',
            'value' => 'required|numeric|min:0',
        ];
        $validator = Validator::make($request->all(), $rules, ['required' => 'El Campo :attribute es requerido']);

        if ($validator->fails()){
            return response()->json($validator->errors());
        }
        $priceItem = PriceBikeRange::create($request->all());

        return response()->json([
            'message' => 'price Item  created successfully',
            'data' => $priceItem
        ], 201);
    }

    public function savePriceByRangeItem(Request $request)
    {

        $rules =  [
            'stock_item_id' => 'required|exists:stock_items,id',
            'price_range_id' => 'required|exists:price_ranges,id',
            'value' => 'required|numeric|min:0',
        ];
        $validator = Validator::make($request->all(), $rules, ['required' => 'El Campo :attribute es requerido']);

        if ($validator->fails()){
            return response()->json($validator->errors());
        }

        $PriceRangeBike = PriceBikeRange::where('stock_item_id', $request->stock_item_id)
                         ->where('price_range_id', $request->price_range_id)
                         ->first();

        if ($PriceRangeBike){
        
            $PriceRangeBike->value = $request->value;
            $PriceRangeBike->save();
            return response()->json([
                'message' => 'price Item  updated successfully',
                'PriceRangeBike' => $PriceRangeBike
            ], 200);

        }
        else{
            $priceItem = PriceBikeRange::create($request->all());
            return response()->json([
                'message' => 'price Item by Bike  created successfully',
                'PriceRangeBike' => $priceItem
            ], 201);
        }
    }

    /**
     * Display the specified resource.
     */
    public function getPriceRangesWithItem($company, $bike_id)
    {
       // $pricebyBike = PriceBikeRange::where('stock_item_id',$item_stock_id)->get();
   
       $priceRanges = PriceRange::with([
        'price_bike_ranges' => function ($query) use ($bike_id) {
            $query->where('bike_id', $bike_id);
        }
        ])
        ->where('company_id', $company)
        ->where('status', true)
        ->get();

        $result = $priceRanges->map(function($range) {
            $value = $range->price_bike_ranges->first()->value ?? 0;
            return [
                'price_range_id' => $range->id,
                'min_range' => $range->min_range,
                'max_range' => $range->max_range,
                'type' => $range->type,
                'apply_to' => $range->apply_to,
                'type_value' => $range->type_value,
                'value' => $value,
            ];
        });

        $grouped = $result->groupBy('apply_to');

        $rangesByDays = $grouped['quantity_days'] ?? collect();
        $RangesByAvailable = $grouped['quantity_available'] ?? collect();

        return response()->json([
            'status' => true,
            'rangesByDays' => $rangesByDays,
            'rangesByAvailable' => $RangesByAvailable
        ],200); 
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(priceBike $priceBike)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function updatePriceBikeRange(Request $request,  $bike_id)
    {

        $ranges = $request->all();
        foreach ($ranges as $range) {
            $price_range_id = $range['price_range_id'];
            $value = $range['value'];

            $priceByBikeRange = PriceBikeRange::where('price_range_id', $price_range_id)->where('bike_id',$bike_id)->first();
            $priceByBikeRange->value = $value;
            $priceByBikeRange->save();
        }
     
        return response()->json([
            'status' => true,
        ],200); 
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(priceBike $priceBike)
    {
        //
    }
}
