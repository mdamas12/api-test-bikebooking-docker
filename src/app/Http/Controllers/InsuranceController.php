<?php

namespace App\Http\Controllers;

use App\Models\Insurance;
use Illuminate\Http\Request;

class InsuranceController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index($company)
    {
        $insurances = Insurance::where('company_id',$company)->get();
        
        return response()->json([
            'status' => true,
            'insurances' => $insurances,
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
    public function store(Request $request, $company)
    {
        
        $insurance = Insurance::create([
            'company_id' => $company,
            'name' => $request->insurance['name'],
            'description' => $request->insurance['description'],
            'price' => $request->insurance['price'],
            'status' => true

        ]);

        return response()->json([
            'status' => true,
            'insurance' => $insurance,
        ],201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Insurance $insurance)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Insurance $insurance)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request,  $insurance)
    {
       $insurance  = Insurance::findOrFail($insurance); 
       
        $insurance->update([ 
            'name' => $request->name,
            'description' => $request->description,
            'price' => $request->price,
            'status' => $request->status,
        ]);

        return response()->json([
            'status' => true,
            'insurance' => $insurance,
        ],200); 
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Insurance $insurance)
    {
        //
    }
}
