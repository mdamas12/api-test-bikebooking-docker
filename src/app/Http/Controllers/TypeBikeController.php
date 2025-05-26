<?php

namespace App\Http\Controllers;

use App\Models\TypeBike;
use Illuminate\Http\Request;

class TypeBikeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $types = TypeBike::all();

        return response()->json([
                'status' => true,
                'types' => $types
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
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(typeBike $typeBike)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(typeBike $typeBike)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, typeBike $typeBike)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(typeBike $typeBike)
    {
        //
    }
}
