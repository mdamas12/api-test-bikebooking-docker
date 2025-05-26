<?php

namespace App\Http\Controllers;

use App\Models\Season;
use Illuminate\Http\Request;

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
        //
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
    public function update(Request $request, TypeSeason $typeSeason)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(TypeSeason $typeSeason)
    {
        //
    }
}
