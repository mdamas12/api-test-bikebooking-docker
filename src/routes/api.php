<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CompanyController;
use App\Http\Controllers\PriceRangeController; 
use App\Http\Controllers\BikeController;
use App\Http\Controllers\TypeBikeController;
use App\Http\Controllers\StockController;
use App\Http\Controllers\StockItemController; 
use App\Http\Controllers\StoreController;  
use App\Http\Controllers\PriceBikeController;
use App\Http\Controllers\SizeController;  
use App\Http\Controllers\CategoryAccesoryController;     
use App\Http\Controllers\AccesoryController; 
use App\Http\Controllers\BikeAccesoryController; 
use App\Http\Controllers\InsuranceController; 
use App\Http\Controllers\SeasonRangeController; 
use App\Http\Controllers\SeasonController;
use App\Http\Controllers\SeasonBikeController; 
use App\Http\Controllers\RateController;
use App\Http\Controllers\FeatureRateController;
use App\Http\Controllers\FeatureByRateController;

Route::post('auth/login', [AuthController::class, 'login']);
Route::post('auth/reset-password', [AuthController::class, 'resetPassword']);
Route::post('companies/application', [CompanyController::class, 'applicationWeb']);
/**
 * Rutas con Auth Obligatorio
 */
Route::middleware('auth:sanctum')->group(function(){ 
    Route::get('dashboard/users', [AuthController::class, 'getGlobalUsers']); 
    Route::post('dashboard/users/register', [AuthController::class, 'register']);
    Route::get('dashboard/companies/get-types', [TypeBikeController::class, 'index']); 

    Route::get('dashboard/companies', [CompanyController::class, 'index']); 
    Route::get('dashboard/companies/{id}', [CompanyController::class, 'show']); 
    Route::post('dashboard/companies/register', [CompanyController::class, 'store']); 
    Route::put('dashboard/companies/update/{id}', [CompanyController::class, 'update']); 
    Route::put('dashboard/companies/activate/{id}', [CompanyController::class, 'activateCompany']); 



    Route::post('dashboard/companies/create-stock', [StockController::class, 'store']); 
    Route::get('dashboard/companies/{company}/get-stocks', [StockController::class, 'index']); 
    Route::put('dashboard/companies/update-stock/{stock}', [StockController::class, 'update']); 

    /**
     * Tipos de Bicecletas
     */



    
    /**
     * Temporadas */ 
     Route::get('dashboard/companies/{company}/get-seasons', [SeasonController::class, 'index']); 
     Route::post('dashboard/companies/create-season', [SeasonController::class, 'store']);
     Route::put('dashboard/companies/update-season/{id}', [SeasonController::class, 'update']);
     Route::delete('dashboard/companies/delete-season/{id}', [SeasonController::class, 'destroy']);

    /** 
     * Periodos */
     Route::get('dashboard/companies/{company}/get-periods', [SeasonRangeController::class, 'index']); 
     Route::post('dashboard/companies/create-period', [SeasonRangeController::class, 'store']); 
     Route::put('dashboard/companies/update-period/{period_id}', [SeasonRangeController::class, 'update']);
     Route::delete('dashboard/companies/delete-period/{id}', [SeasonRangeController::class, 'destroy']);
     Route::get('dashboard/companies/{company}/get-periods-bike/{id}', [SeasonRangeController::class, 'getPeriodbyBike']); 
     Route::post('dashboard/companies/modify-period-bike', [SeasonBikeController::class, 'modifyPeriodBike']); 
     //Route::put('dashboard/companies/update-period-bike/{id}', [SeasonBikeController::class, 'update']); 
    // Route::delete('dashboard/companies/delete-period-bike/{id}', [SeasonBikeController::class, 'destroy']); 

   

     /**
     * Tarifas ^ caracteristicas */
     Route::get('dashboard/companies/{company}/get-rates', [RateController::class, 'index']);
     Route::post('dashboard/companies/create-rate', [RateController::class, 'store']);
     Route::put('dashboard/companies/update-rate/{rate_id}', [RateController::class, 'update']);
     Route::delete('dashboard/companies/delete-rate/{rate_id}', [RateController::class, 'destroy']);

     Route::get('dashboard/companies/{company}/get-features-rates', [FeatureRateController::class, 'index']); 
     Route::post('dashboard/companies/create-feature-rate', [FeatureRateController::class, 'store']);
     Route::put('dashboard/companies/update-feature-rate/{id}', [FeatureRateController::class, 'update']);
     Route::delete('dashboard/companies/delete-feature-rate/{id}', [FeatureRateController::class, 'destroy']);
    
     /**
     * Seguros */
     Route::get('dashboard/companies/{company}/get-insurances', [InsuranceController::class, 'index']); 
     Route::post('dashboard/companies/{company}/create-insurance', [InsuranceController::class, 'store']);
     Route::put('dashboard/companies/update-insurance/{insurance_id}', [InsuranceController::class, 'update']);

    /**
     * Gestion de Accesorios  */
    Route::get('dashboard/companies/{company}/get-categories_accesories', [CategoryAccesoryController::class, 'index']); 
    Route::post('dashboard/companies/{company}/create-category-accesory', [CategoryAccesoryController::class, 'store']); 
    Route::post('/dashboard/companies/update-category-accesory/{category_id}', [CategoryAccesoryController::class, 'update']); 
    Route::get('dashboard/companies/{company_id}/get-accesories', [AccesoryController::class, 'index']);  
    Route::get('dashboard/companies/{company_id}/get-accesories-selected/{bike_id}', [BikeAccesoryController::class, 'getSelectedBybike']);
    Route::post('dashboard/companies/{company}/create-accesory', [AccesoryController::class, 'store']); 
    Route::post('dashboard/companies/update-accesory/{accesory_id}', [AccesoryController::class, 'update']);  
    Route::post('dashboard/companies/save-accesory-bike', [BikeAccesoryController::class, 'saveAccesoryBybike']); 
    Route::get('/icons/{filename}', [CategoryAccesoryController::class, 'getIcons']);
   
    /**
     * Gestion de Items de Bicicletas (bike por tallas)  */
    Route::post('dashboard/companies/create-item-stock', [StockItemController::class, 'store']); //crear un item de una bike
    //Route::get('dashboard/companies/get-item-stock/{bike_id}', [StockItemController::class, 'getItemsByBike']); //obtener todas las bicicletas (item) por bike
    Route::get('dashboard/companies/{company}/get-item-stock/{bike_id}', [StockItemController::class, 'getItemsByBike']);
    Route::put('dashboard/companies/update-item-stock/{item_id}', [StockItemController::class, 'update']); //Actualizar item 
   

    Route::post('dashboard/companies/create-price-bike', [PriceBikeController::class, 'store']); 
    Route::post('dashboard/companies/save-price-bike', [PriceBikeController::class, 'savePriceByRangeItem']); 
    Route::get('dashboard/companies/{company}/get-price-bike/{bike_id}', [PriceBikeController::class, 'getPriceRangesWithItem']); 
    Route::put('dashboard/companies/update-price-range-bike/{bike_id}', [PriceBikeController::class, 'updatePriceBikeRange']); 

    Route::post('dashboard/companies/create-store', [StoreController::class, 'store']); 
    Route::get('dashboard/companies/{company}/get-stores', [StoreController::class, 'index']); 

    Route::get('dashboard/companies/{company}/get-ranges', [PriceRangeController::class, 'index']); 
    Route::post('dashboard/companies/create-range', [PriceRangeController::class, 'store']); 
    Route::put('dashboard/companies/update-range/{id}', [PriceRangeController::class, 'update']); 
    Route::delete('dashboard/companies/delete-range/{id}', [PriceRangeController::class, 'destroy']); 
    
    Route::get('dashboard/companies/{company}/get-sizes', [SizeController::class, 'index']); /*listar Tallas por Empresa */
    Route::post('dashboard/companies/create-size', [SizeController::class, 'store']); /* Crear Talla ( M, S, L) */
    
    /**
     * Gestion de bicicletas
     */

    Route::post('dashboard/companies/create-bike', [BikeController::class, 'store']); 
    Route::put('dashboard/companies/update-bike/{bike_id}', [BikeController::class, 'update']); 
    Route::get('dashboard/companies/{company}/get-bikes', [BikeController::class, 'index']); 
    Route::get('dashboard/companies/bikes-detail/{bike_id}', [BikeController::class, 'show']); 

    Route::get('/dashboard/notifications', function () {
        return auth()->user()->notifications;
    });
});

/*
Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');
*/
