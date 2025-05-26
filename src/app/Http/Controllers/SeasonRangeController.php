<?php

namespace App\Http\Controllers;

use App\Models\SeasonRange;
use Illuminate\Http\Request;

class SeasonRangeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index($company)
    {
        $periods = SeasonRange::with(['season'])
        ->where('company_id', $company)
        ->get();

        return response()->json([
            'status' => true,
            'periods' => $periods
        ], 200);
    }

    /**
     * Actualizar Periodo .
     */
    public function update(Request $request, $period_id)
    {
        $period = SeasonRange::where('id', $period_id)->first();
        $period->update($request->all());  
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(SeasonRange $seasonRange)
    {
        //
    }
}
