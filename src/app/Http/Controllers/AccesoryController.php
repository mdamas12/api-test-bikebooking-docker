<?php

namespace App\Http\Controllers;

use App\Models\Accesory;
use App\Models\CategoryAccesory;
use App\Models\Company;

use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Carbon;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Storage;

class AccesoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index($company_id)
    {
        $accesories = Accesory::with('category_accesory')
                    ->where('company_id', $company_id)
                    ->get()
                    ->sortBy([
                        ['status', 'desc'], 
                        ['order', 'asc']   
                    ]);
        $agrupados = [];
        foreach ($accesories as $accesory) {
            $categoria = $accesory->category_accesory->name ?? 'Sin Categoría';
         
            $agrupados[$categoria][] = [
                'id' => $accesory->id,
                'name' => $accesory->name,
                'category_accesory_id' => $accesory->category_accesory_id,
                'path' =>  $accesory->path ?  asset('storage/' . $accesory->path) : null,
                'order' => $accesory->order,
                'quantity' => $accesory->quantity,
                'status'  => $accesory->status,
                'price_day' => $accesory->price_day,
                'price_booking' => $accesory->price_booking,
                'is_price_booking'  => $accesory->is_price_booking,

            ];
        }
        
        return response()->json([
            'status' => true,
            'accesories' => $agrupados,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request, $company)
    {
        $category = [
                'name' => $request->input('category.name'),
                'description' => $request->input('category.description')
            ];
       
        if (!empty(array_filter($category, fn($value) => !is_null($value)))) {
            $newCategory = CategoryAccesory::create([
                'company_id' => $company,
                'name' => $category['name'],
                'description' => $category['description'],
            ]);
            $category_id =  $newCategory->id;
        }
        else{
            $category_id = $request->input('category_id');
        }

        if ($request->hasFile('image')) {
            /**Imagen save */
            $path = $request->file('image')->store('accesories', 'public');   
        }
        else {
            $path = "";
        }
  

      
        $accesory = Accesory::create([
            'company_id' => $company,
            'category_accesory_id' => $category_id,
            'name' => $request->input('name'),
            'path' => $path,
            'order' => $request->input('order'),
            'quantity' => $request->input('quantity'),
            'price_day' => $request->input('price_day'),
            'price_booking' => $request->input('price_booking'),
            'is_price_booking' => $request->input('is_price_booking'),
        ]);

        return response()->json([
            'message' => 'accesory  created successfully',
            'accesory' => $accesory 
        ], 201);
    }

        /**
     * Store a newly created resource in storage.
     */
    public function NewCategory($category)
    {
        $rules =  [
            'company_id' => 'required|exists:companies,id',
            'name' => 'required|string|max:60',     
        ];
        $validator = Validator::make($category, $rules, ['required' => 'El Campo :attribute es requerido']);

        if ($validator->fails()){
            return response()->json($validator->errors());
        }
        $category_accesory = CategoryAccesory::create($request->all());

        return response()->json([
            'message' => 'category of accesory created successfully',
            'category_accesory' => $category_accesory
        ], 201);
    }


    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Accesory $accesory)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $accesory_id)
    {
        $accesory = Accesory::findOrFail($accesory_id); 
        
        if ($request->hasFile('image')) {
            /**Imagen dalete */
            if ($accesory->path && Storage::disk('public')->exists($accesory->path)) {
                Storage::disk('public')->delete($accesory->path);
            }
            /**Imagen save */
            $path = $request->file('image')->store('accesories', 'public');
            $request->path = $path;
        }
        else{
            $request->path = $accesory->path;   
        }

       
        
        $accesory->update([ 
       
            'name' => $request->name,
            'price_day' => $request->price_day,
            'price_booking' => $request->price_booking,
            'is_price_booking' => $request->is_price_booking,
            'order' => $request->order,
            'quantity' => $request->quantity,
            'path' => $request->path,
            'status' => $request->status,
        ]);

        return response()->json([
            'status' => true,
            'accesory' => $accesory 
        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Accesory $accesory)
    {
        //
    }
}
