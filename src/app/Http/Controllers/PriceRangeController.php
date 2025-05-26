<?php

namespace App\Http\Controllers;

use App\Models\PriceRange;
use App\Models\Company;
use App\Models\User;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;


class PriceRangeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(string $company)
    {
        $ranges = PriceRange::where('company_id',$company)->get();
        $grouped = $ranges->groupBy('apply_to');

        $rangesByDays = $grouped['quantity_days'] ?? collect();
        $rangesByBikes = $grouped['quantity_bikes'] ?? collect();
        $RangesByAvailable = $grouped['quantity_available'] ?? collect();
        return response()->json([
            'status' => true,
            'ranges' => $ranges,
            'rangesByDays' => $rangesByDays,
            'rangesByBikes' => $rangesByBikes,
            'RangesByAvailable' => $RangesByAvailable
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
        $rules = [
            'company_id' => 'required|exists:companies,id',
            'min_range' => 'required|integer|min:1',
            'max_range' => 'required|integer|gte:min_range',
            'type' => 'required|in:increase,discount',
            'apply_to' => 'required|in:quantity_days,quantity_bikes,quantity_available',
            'type_value' => 'required|in:percentage,value',
            'status' => 'boolean'
        ];

        $validator = Validator::make($request->all(), $rules, [
            'required' => 'The attribute :attribute is required.'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'errors' => $validator->errors()
            ], 422);
        }

        $range = PriceRange::create($request->all());

        return response()->json([
            'message' => 'Price range created successfully',
            'data' => $range
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(PriceRange $priceRange)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(PriceRange $priceRange)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $range = PriceRange::find($id);

        if (!$range) {
            return response()->json(['message' => 'Price range not found'], 404);
        }

        $rules = [
            'company_id' => 'required|exists:companies,id',
            'min_range' => 'required|integer|min:1',
            'max_range' => 'required|integer|gte:min_range',
            'type' => 'required|in:increase,discount',
            'apply_to' => 'required|in:quantity_days,quantity_bikes,quantity_available',
            'type_value' => 'required|in:percentage,value',
            'status' => 'boolean'
        ];

        $validator = Validator::make($request->all(), $rules, [
            'required' => 'The attribute :attribute is required.'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'errors' => $validator->errors()
            ], 422);
        }

        $range->update($request->all());

        return response()->json([
            'message' => 'Price range updated successfully',
            'data' => $range
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $range = PriceRange::find($id);

        if (!$range) {
            return response()->json(['message' => 'Price range not found'], 404);
        }

        $range->delete();

        return response()->json(['message' => 'Price range deleted successfully']);
    }
}
