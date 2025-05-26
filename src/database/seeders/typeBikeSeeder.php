<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\TypeBike;
use App\Models\CategoryBike;

class typeBikeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {

        $bikeTypes = [

            [
                'name' => 'Carretera',
            ],
            [
                'name' => 'Hibrida',
            ],
            [
                'name' => 'MTB',
            ],
            [
                'name' => 'E-bike',
            ],
            [
                'name' => 'City',
            ],
            [
                'name' => 'Gravel',
            ],
            [
                'name' => 'Otros',
            ],
          
        ];
      
        foreach ($bikeTypes as $category) {
            $typeSelected =   TypeBike::create([
                'name' => $category['name'],  
                'status'=> true
            ]);

       
        }

    }
}
