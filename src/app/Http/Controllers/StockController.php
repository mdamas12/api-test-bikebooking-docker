<?php

namespace App\Http\Controllers;

use App\Models\Stock;
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

class StockController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index($company)
    {
        $Stocks = Stock::where('company_id',$company)->get();
     
        return response()->json([
            'status' => true,
            'stocks' => $Stocks,
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
        $rules =  [
            'company_id' => 'required|exists:companies,id',
            'name' => 'required|string|max:60',     
        ];
        $validator = Validator::make($request->all(), $rules, ['required' => 'El Campo :attribute es requerido']);

        if ($validator->fails()){
            return response()->json($validator->errors());
        }
        $stock = Stock::create($request->all());

        return response()->json([
            'message' => 'Stock created successfully',
            'data' => $stock
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Stock $stock)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Stock $stock)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $stock)
    {
        $stock = Stock::where('id', $stock)->first();
        $stock->update($request->all());  
        return response()->json([
            'status' => true,
            'message' => 'Stock  updated successfully',
        ],200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
         //
    }
}
