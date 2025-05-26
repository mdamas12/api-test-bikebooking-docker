<?php

namespace App\Http\Controllers;

use App\Models\Store;
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

class StoreController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index($company)
    {
        $stores = Store::where('company_id',$company)->get();
     
        return response()->json([
            'status' => true,
            'bikes' => $stores,
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
            'stock_id' => 'required|exists:stocks,id',
            'name' => 'required|string|max:60',     
        ];
        $validator = Validator::make($request->all(), $rules, ['required' => 'El Campo :attribute es requerido']);

        if ($validator->fails()){
            return response()->json($validator->errors());
        }
        $store = Store::create($request->all());

        return response()->json([
            'message' => 'Store created successfully',
            'data' => $store
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Store $store)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Store $store)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Store $store)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Store $store)
    {
        //
    }
}
