<?php

namespace App\Http\Controllers;


use App\Models\CategoryAccesory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Carbon;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Storage;


class CategoryAccesoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index($company)
    {
        $categiories_accesories = CategoryAccesory::where('company_id',$company)->get();
        
        return response()->json([
            'status' => true,
            'categiories_accesories' => $categiories_accesories,
        ],200); 
    }



    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request, $company_id)
    {
  
        $category_accesory = CategoryAccesory::create([
            'company_id' => $company_id,
            'name' => $request->category['name'],
            'description' => $request->category['description'],
            'multiselect' => $request->category['multiselect'],
            'path'=> ""
        ]);

        return response()->json([
            'message' => 'category of accesory created successfully',
            'category_accesory' => $category_accesory
        ], 201);
    }

 
    public function getIcons($filename)
    {
        $path = 'svgs/' . $filename ;
    

        if (!Storage::exists($path)) {
            return response()->json(['message' => 'Icon not found '.$path], 404);
        }

        $file = Storage::get($path);
        $type = Storage::mimeType($path); // debería ser 'image/svg+xml'

        return response($file, 200)
            ->header('Content-Type', $type);
    }

        /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $category_id)
    {
        $category = CategoryAccesory::findOrFail($category_id); 

        /* por ahora no se estara cargando el SVG
            if ($request->hasFile('image')) {
            
                if ($accesory->path && Storage::disk('public')->exists($accesory->path)) {
                    Storage::disk('public')->delete($accesory->path);
                }
            
                $path = $request->file('image')->store('accesories', 'public');
                $request->path = $path;
            }
            else{
                $request->path = $accesory->path;   
            }
        */

        $category->update([ 
       
            'name' => $request->name,
            'description' => $request->description,
            'multiselect' => $request->multiselect,
            'status' => $request->status,
            'path' => "",
        
        ]);


        return response()->json([
            'status' => true,
            'category' => $category 
        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(CategoryAccesory $categoryAccesory)
    {
        //
    }
}
