<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\User;
use App\Models\Company;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\DB;

use Illuminate\Support\Facades\Password;
use Illuminate\Auth\Events\PasswordReset;


class AuthController extends Controller
{
    public function register(Request $request)
    {
       
    $rules = [
            'name' => 'required|string|max:100',
            'email' => 'required|string|email|max:100|unique:users',
            'password' => 'required|string|min:8',
          
        ];

        $validator = Validator::make($request->all(), $rules, ['required' => 'the attribute :attribute is required']);
        if($validator->fails()){
        
            return response()->json([
                'status' => false,
                'errors' => $validator->errors()->all()
            ],400);

        }
        
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ])->assignRole('global');
        $company = Company::where('id','1')->first();
        $company->users()->save($user); 
        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'status' => true,
            'message' => 'User registered successfully', 
            'user' => $user, 
            'access_token' => $token, 
            'token_type' => 'Bearer'
        ],201);
    }

    public function login(Request $request)
        {

            $rules = [
                'email' => 'required|string|email|max:30',
                'password' => 'required|string',
            ];

            $validator = Validator::make($request->all(), $rules, ['required' => 'the attribute :attribute is required']);
            if($validator->fails()){
                return response()->json([
                    'status' => false,
                    'errors' => $validator->errors()->all()
                ],400);

            }

            if(!Auth::attempt($request->only('email','password'))){
                return response()->json([
                    'status' => false,
                    'errors' => ['Unauthorized']
                ],401);
            }

            
            
            //$user = User::where('email', $request->email)->first();
            $user = Auth::user();
            if ($user->status === 0 ){
                return response()->json([
                    'status' => false,
                    'errors' => ['blocked user']
                ],401); 
            }
            $user->role = Auth::user()->getRoleNames()->first();
            $token = $user->createToken('auth_token')->plainTextToken;
            $company = auth()->user()->company;
            

            return response()->json([
                'status' => true,
                'message' => 'User Logged in successfully',
                'access_token' => $token, 
                'user'=> $user,
                'role' =>  $user->role,
                'company' => $company
            ],200);
        }

    public function resetPassword(Request $request)
        {
            $request->validate([
                'email' => 'required|email',
                'token' => 'required|string',
                'password' => 'required|min:8',
            ]);
        
            $record = DB::table('password_reset_tokens')->where('email', $request->email)->first();
        
            if (!$record || !Hash::check($request->token, $record->token)) {
                return response()->json(['message' => 'Token inválido o expirado'], 400);
            }
        
            $user = User::where('email', $request->email)->firstOrFail();
            $user->password = Hash::make($request->password);
            $user->save();
        
            DB::table('password_reset_tokens')->where('email', $request->email)->delete();
        
            return response()->json(['message' => 'Contraseña actualizada correctamente']);
        }

    public function getGlobalUsers(){
        $globaluser = [];
        
        $users = User::with('roles')->get();
        foreach ($users as $user) {
            $role = $user['roles']['0'];
            if ($role['name']== 'global'){
                array_push($globaluser, $user);
            }
                
        }
        return response()->json([
            'status' => true,
            'data' => $globaluser,
           
        ],200);
        
    }

 public function logout(){
    atth()->user()->tokens()->delete();
    return response()->json([
        'status' => true,
        'message' => 'User Logged out successfully',

    ],200);
   }

}
