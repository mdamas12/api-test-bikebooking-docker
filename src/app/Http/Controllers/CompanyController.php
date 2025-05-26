<?php

namespace App\Http\Controllers;

use App\Models\Company;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Carbon;
use Illuminate\Validation\Rule;
use App\Notifications\CompanyApplication;
use App\Notifications\CompanyAtivated;
use Illuminate\Support\Facades\Notification;

use App\Jobs\ActivateCompanyJob;

class CompanyController extends Controller
{
   /**
     * Dashboard - Obtener lista de Empresas
     *
     * Retorna una lista de todas las empresas registradas en Bikebooking
     *
     * 
    */
    public function index()
    {
        $companies = Company::where('id', '!=', 1)->get();

        return response()->json([
                'status' => true,
                'data' => $companies
        ],200); 
    }

    /**
     * Dashboard - Registrar una Empresa
     *
     * Endpoint que registra una empresa.
     *
     * 
    */
    public function store(Request $request)
    {

        $rules =  [
            'contact_name' => 'required|string|max:30',
            'email' => 'required|string|max:50|unique:companies,email',
            'company_name' => 'required|string|max:60',
            'fiscal_name' => 'required|string|max:60',
            'cif' => 'required|string|max:50|unique:companies,cif',
            'address' => 'string|max:100',
            'country' => 'string|max:30',
            'phone' => 'string|max:20',
            'website_url' => 'string|max:30',
            'status' => 'required|in:pending,active,disabled',
        ];
        $validator = Validator::make($request->all(), $rules, ['required' => 'El Campo :attribute es requerido']);

        if ($validator->fails()){
            return response()->json($validator->errors());
        }
        $company = Company::create($request->all());

        if ($company->status === 'pending'){
            // Enviar notificación al email de la empresa
            Notification::route('mail', $company->email)
            ->notify(new CompanyApplication($company)); 
        }
        if ($company->status === 'active'){
            $new_user = User::where('email',$company->email)->first();
               
            if(!$new_user){
               $new_user = $this->registerCompanyUser($company->id, $company->email, $company->contact_name);
                 // Enviar notificación al email de la empresa
                  Notification::route('mail', $company->email)
                  ->notify(new CompanyAtivated($new_user));
            }
        }
        return response()->json([
            'status' => true,
            'message' => 'company created successfully',
            'company' => $company
         ],201);
    }



  
    /**
     * Dashboard - Actualizar datos de una Empresa
     *
     * Endpoint que actuqaliza datos de una empresa.
     *
     * 
    */
    public function update(Request $request, string $company_id)
    {
        $company = Company::where('id', $company_id)->first();
        try{
            if(!$company){
                throw new \Exception('error trying to updated company'); 
            }
            else{

                $rules =  [
                    'contact_name' => 'required|string|max:30',
                    'email' => ['required','string','max:50',Rule::unique('companies')->ignore($company->id)],
                    'company_name' => 'required|string|max:60',
                    'fiscal_name' => 'required|string|max:60',
                    'cif' => ['required','string','max:50',Rule::unique('companies')->ignore($company->id)],
                    'address' => 'string|max:100',
                    'country' => 'string|max:30',
                    'phone' => 'string|max:20',
                    'website_url' => 'string|max:50',
                    'status' => 'required|in:pending,active,disabled',
                ];
                $validator = Validator::make($request->all(), $rules, ['required' => 'El Campo :attribute es requerido']);
        
                if ($validator->fails()){
                    throw new \Exception($validator->errors()); 
                }
                else{
                    $company->update($request->all());  
                    return response()->json([
                        'status' => true,
                        'message' => 'Company updated successfully',
                        'company' => $company
                    ],200);
                }

            }
        }catch(\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 400);
        }
       
    }


    /**
     * WEB - Aplicacion de una empresa. 
     *
     * Endpoint que registra una empresa y la ubica en pendiente para usar la app.
     *
     * Al registar la empresa, se le envia una notificacion via email al contacto de la empresa. 
    */
    public function applicationWeb(Request $request){
        $rules =  [
            'contact_name' => 'required|string|max:30',
            'email' => 'required|string|max:50|unique:companies,email',
            'company_name' => 'required|string|max:60',
            'fiscal_name' => 'required|string|max:60',
            'cif' => 'required|string|max:50|unique:companies,cif',
            'address' => 'string|max:100',
            'country' => 'string|max:30',
            'phone' => 'string|max:20',
            'website_url' => 'string|max:30',
            'status' => 'required|in:pending,active,disabled',
        ];
        $validator = Validator::make($request->all(), $rules, ['required' => 'El Campo :attribute es requerido']);

        if ($validator->fails()){
            return response()->json($validator->errors());
        }
        $company = Company::create($request->all());

        // Enviar notificación al email de la empresa
        Notification::route('mail', $company->email)
        ->notify(new CompanyApplication($company));

        return response()->json([
            'status' => true,
            'message' => 'company created successfully',
            'company' => $company
         ],201); 

    }

     /**
     * Dashboard - Activacion de una empresa. 
     *
     * Endpoint que valida y activa una empresa.
     *
     * Al activar una empresa se generara la informacion inicial como: 
     * Rangos de precios, Tipos de bicicletas, Temporadas, 
     * 
     * Si la empresa entra en modo prueba, se registrara 30 dias de prueba.  
    */
    public function activateCompany(Request $request, string $company)
    {

        try{
    
                $rules =  [
                    'company_id' => 'required|exists:companies,id',         
                ];
                $validator = Validator::make($request->all(), $rules, ['required' => 'El Campo :attribute es requerido']);
        
                if ($validator->fails()){
                    throw new \Exception($validator->errors()); 
                }
                else{
                    $company = Company::where('id',$request->company_id)->first();
                    $company->status = 'in progress';
                    $company->save();
                    $userlog = Auth::user();
                    
                    ActivateCompanyJob::dispatch($request->company_id, $request->company_status, $userlog );

                    return response()->json(
                        [
                            'message' => 'La activación se está procesando.',
                            'company' => $company
                        ]);
                }
   
        }catch(\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 400);
        }

    }

    /**
     * Intarnal - Usuario inical de la empresa. 
     *
     * Endpoint que registra un usuario incial de la empresa que se ha activado.
     *
    */
    public function registerCompanyUser(string $company_id, string $email, string $name){
         
        $password = Str::random(8);
        $user = User::create([
            'name' => $name,
            'email' => $email,
            'password' => Hash::make($password),
        ])->assignRole('administrator');
        $company = Company::where('id',$company_id)->first();
        $company->users()->save($user); 
        
        $token = Str::random(64);
        DB::table('password_reset_tokens')->updateOrInsert(
            ['email' => $email],
            [
                'token' => Hash::make($token),
                'created_at' => Carbon::now()
            ]
        );
        
        return [
            'token' => $token,
            'user' => $user,
        ];
           
    }


     /**
     * Intarnal - Periodo de prueba. 
     *
     * Si se activa la empresa en modo prueba, se genera el preriodo en el modelo testing
     * por 30 dias desde la activacion de la empresa.
     *
    */
    public function registerCompanyTesting(string $company_id){


        $fechaActual = Carbon::now();
        $fechaMas30Dias = $fechaActual->copy()->addDays(30);

        $fechaActualStr = $fechaActual->format('Y-m-d');
        $fechaMas30DiasStr = $fechaMas30Dias->format('Y-m-d');

        $table->id();
        $table->foreignId('company_id')->constrained()->onDelete('cascade');
        $table->date('ini_test');
        $table->date('end_test');
         
        
        $testing = TestingCompany::create([
            'company_id' => $company_id,
            'ini_test' => $fechaActualStr,
            'end_test' => $fechaMas30DiasStr,
        ]);

        
        return [
            'testing' => $testing
        ];
           
    }
        
  
}
