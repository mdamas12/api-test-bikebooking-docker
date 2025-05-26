<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Company;
use Hash;
class userSeeder extends Seeder
{
     /**
     * Run the database seeds.
     */
    public function run(): void
    {
      $company = Company::where('company_name', 'Bike Booking Engine')->first();
      $user =   User::create([
            'name' => 'user global',
            'email' => 'globaluser@gmail.com',
            'password' => Hash::make('bikebooking2025')

        ]);

      $user->assignRole('global'); //asignamos un role Global
      $company->users()->save($user); //asignamos la empresa Bike booking por ser Global.
    }
}
