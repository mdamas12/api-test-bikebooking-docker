<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Brand;
class BrandSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $bikeBrands = [
            'Bianchi',
            'BMC',
            'Bombtrack',
            'Cannondale',
            'Canyon',
            'Colnago',
            'Commencal',
            'Cube',
            'Decathlon',
            'Van Rysel',
            'Rockrider',
            'Factor',
            'Focus',
            'Fuji',
            'Ghost',
            'Giant',
            'GT Bicycles',
            'Haro',
            'IBIS Cycles',
            'Intense Cycles',
            'Jamis',
            'Juliana',
            'Kona',
            'Lapierre',
            'Liv',
            'Look',
            'Marin Bikes',
            'Merida',
            'Mondraker',
            'Moots',
            'Norco',
            'NS Bikes',
            'Nukeproof',
            'Open',
            'Orbea',
            'Parlee',
            'Pinarello',
            'Pivot Cycles',
            'Polygon',
            'Raleigh',
            'Riese & Müller',
            'Ridley',
            'Ritchey',
            'Rose Bikes',
            'Santa Cruz',
            'Salsa Cycles',
            'Scott',
            'Serotta',
            'Specialized',
            'Surly',
            'Trek',
            'Transition Bikes',
            'Vanmoof',
            'Wilier Triestina',
            'Yeti Cycles',
            'YT Industries',
            'Zerode',
        ];
        
        foreach ($bikeBrands as $brand) {
            $brandSelect =   Brand::create([
                'name' => $brand,
                'description' => 'Bicicletas de Ruta, MTB, gravel, Ciclo turismo',
                'status' => '1'  
            ]);
        }
    }
}
