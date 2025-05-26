<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Company;
use App\Models\User;
use Hash;

class CompanySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $company =   Company::create([
            'contact_name' => 'Isaac Cruz',
            'email' => 'isaacruz@gmail.com',
            'company_name' => 'Bike Booking Engine',
            'fiscal_name' => 'Bike Booking Engine CA',
            'cif' => '409358744',
            'address' => 'Palma, Mallorca',
            'country' => 'España',
            'phone' => '04125877478',
            'website_url' => 'https://bikebookingengine.com/',
            'status' => 'active',
 
        ]);


    }
}
