<?php

namespace App\Jobs;


use App\Models\Company;
use App\Models\User;
use App\Models\Stock;
use App\Models\Store;
use App\Models\Season;
use App\Models\SeasonRange;
use App\Models\PriceRange;
use App\Models\TestingCompany;

use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Support\Carbon;

use Illuminate\Support\Facades\Notification;
use App\Notifications\CompanyAtivated;
use App\Notifications\CompanyActivatedNotification;



class ActivateCompanyJob implements ShouldQueue
{
    use InteractsWithQueue, Queueable, SerializesModels;

    protected $empresaId;
    protected $status;
    protected $user;

    public function __construct(int $empresaId, string $status, User $user)
    {
        $this->empresaId = $empresaId;
        $this->status = $status;
        $this->user = $user;
    }

    public function handle()
    {
        DB::transaction(function () {
            $company = Company::where('id',$this->empresaId)->first();
            $company->status = $this->status;
            $company->save();

        /**
         *  Crear usuario principal de la empresa
         *
        * */ 
            
           $user =  User::create([
                'name' => $company->contact_name,
                'email' => $company->email,
                'password' => bcrypt('password2025'),
                'company_id' => $company->id,
            ])->assignRole('administrator');
            $token = Str::random(64);
            DB::table('password_reset_tokens')->updateOrInsert(
                ['email' => $company->email],
                [
                    'token' => Hash::make($token),
                    'created_at' => Carbon::now()
                ]
            );

           $newUser = [
            'token' => $token,
            'user' => $user,
            'company' => $company
           ];

        /**
        *  Crear el Stock principal para cada empresa
        *
        * */ 
        
            $stock = Stock::create([
                'company_id' => $company->id,
                'name' => 'Main Stock',
                'status' => true
            ]);

        /**
        *  Crear la tienda principal para cada empresa
        *
        * */ 

            $store = Store::create([
                'company_id' => $company->id,
                'name' => 'Main Store',
                'status' => true
            ]);

        /**
        * Crear temporadas y rangos previos de temporadas
        * Se puede modificar a solo mes ***
        * */ 

            $temporadas = [
                ['name' => 'Baja','ini' => '2025-01-01', 'end' => '2025-04-30'],
                ['name' => 'Media','ini' => '2025-05-01', 'end' => '2025-08-31'],
                ['name' => 'Alta','ini' => '2025-09-01', 'end' => '2025-12-31'],
            ];
         
            foreach ($temporadas as $season) {
                $season = Season::create([
                    'company_id' => $company->id,
                    'name' => $season['name'],
                ]);
                $fechaIni = Carbon::parse($season['ini']);
                $fechaEnd = Carbon::parse($season['end']);
                SeasonRange::create([ 
                    'company_id' => $company->id,
                    'season_id' => $season->id,
                    'ini_season' => $fechaIni,
                    'end_season' => $fechaEnd,
                    'value' => 0,
                ]);
            }

        /**
        * Crear rangos de precios
        *  
        * */ 


            $PricesRanges = [
                ['min_range' => 1, 'max_range' => 1, 'type' => 'discount', 'apply_to' => 'quantity_days','type_value' => 'value','status' => true],
                ['min_range' => 2, 'max_range' => 2, 'type' => 'discount', 'apply_to' => 'quantity_days','type_value' => 'value' ,'status' => true],
                ['min_range' => 3, 'max_range' => 3, 'type' => 'discount', 'apply_to' => 'quantity_days','type_value' => 'value' ,'status' => true],
                ['min_range' => 4, 'max_range' => 4, 'type' => 'discount', 'apply_to' => 'quantity_days','type_value' => 'value' ,'status' => true],
                ['min_range' => 5, 'max_range' => 5, 'type' => 'discount', 'apply_to' => 'quantity_days','type_value' => 'value' ,'status' => true],
                ['min_range' => 6, 'max_range' => 6, 'type' => 'discount', 'apply_to' => 'quantity_days','type_value' => 'value' ,'status' => true],
                ['min_range' => 7, 'max_range' => 7, 'type' => 'discount', 'apply_to' => 'quantity_days','type_value' => 'value' ,'status' => true],
                ['min_range' => 8, 'max_range' => 999, 'type' => 'discount', 'apply_to' => 'quantity_days','type_value' => 'value' ,'status' => true],
           
                ['min_range' => 1, 'max_range' => 25, 'type' => 'increase', 'apply_to' => 'quantity_available','type_value' => 'percentage' ,'status' => true],
                ['min_range' => 26, 'max_range' => 50, 'type' => 'increase', 'apply_to' => 'quantity_available','type_value' => 'percentage' ,'status' => true],
                ['min_range' => 51, 'max_range' => 75, 'type' => 'increase', 'apply_to' => 'quantity_available','type_value' => 'percentage' ,'status' => true],
                ['min_range' => 76, 'max_range' => 100, 'type' => 'increase', 'apply_to' => 'quantity_available','type_value' => 'percentage' ,'status' => true],
            ];

            foreach ($PricesRanges as $range) {
                PriceRange::create([
                    'company_id' => $company->id,
                    'min_range' => $range['min_range'],
                    'max_range' => $range['max_range'],
                    'type' => $range['type'],
                    'apply_to' => $range['apply_to'],
                    'type_value' => $range['type_value'],
                    'status' => $range['status'],
                ]);
            }

        /**
        * Crear periodo de prueba si el status es testing
        *
        **/
            if ($company->status ==='testing'){

                $fechaActual = Carbon::now();
                $fechaMas30Dias = $fechaActual->copy()->addDays(30);
            
                TestingCompany::create([
                    'company_id' => $company->id,
                    'ini_test' => $fechaActual->format('Y-m-d'),
                    'end_test' => $fechaMas30Dias->format('Y-m-d'),
                ]);
            
            }

        /**
        * Enviar notificación al email de la empresa
        *
        **/ 
            Notification::route('mail', $company->email)
            ->notify(new CompanyAtivated($newUser));

           

        /**
        * Enviar notificación al Sistema Bikebooking
        * 
        */
           
            $this->user->notify(new CompanyActivatedNotification($company));
          
        });
    }
}
