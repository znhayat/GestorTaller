<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class VehiculosSeeder extends Seeder
{
    public function run()
    {
        $data = [
            'Audi' => ['A1', 'A3', 'A4', 'A5', 'A6', 'Q3', 'Q5', 'Q7', 'TT', 'R8'],
            'BMW' => ['Serie 1', 'Serie 2', 'Serie 3', 'Serie 4', 'Serie 5', 'X1', 'X3', 'X5', 'Z4', 'M2', 'M3', 'M4'],
            'Mercedes-Benz' => ['Clase A', 'Clase B', 'Clase C', 'Clase E', 'Clase S', 'GLA', 'GLC', 'GLE', 'CLA', 'AMG GT'],
            'Volkswagen' => ['Golf', 'Polo', 'Passat', 'Tiguan', 'T-Roc', 'Scirocco', 'Touareg', 'Arteon'],
            'Seat' => ['Ibiza', 'Leon', 'Arona', 'Ateca', 'Tarraco', 'Alhambra'],
            'Cupra' => ['Formentor', 'Leon', 'Ateca', 'Born'],
            'Porsche' => ['911', 'Cayenne', 'Panamera', 'Macan', 'Taycan', 'Boxster'],
            'Land Rover' => ['Range Rover Evoque', 'Range Rover Sport', 'Defender', 'Discovery'],
            'Toyota' => ['Corolla', 'Yaris', 'RAV4', 'C-HR', 'Land Cruiser', 'Supra'],
            'Ford' => ['Fiesta', 'Focus', 'Mustang', 'Kuga', 'Puma', 'Ranger'],
            'Peugeot' => ['208', '308', '508', '2008', '3008', '5008'],
            'Renault' => ['Clio', 'Megane', 'Captur', 'Kadjar', 'Austral', 'Arkana'],
            'Tesla' => ['Model 3', 'Model Y', 'Model S', 'Model X'],
            'Ferrari' => ['488', 'F8 Tributo', 'Roma', 'Portofino', 'SF90'],
            'Lamborghini' => ['Urus', 'Huracán', 'Aventador'],
        ];

        foreach ($data as $marca => $modelos) {
            DB::table('marcas')->updateOrInsert(
                ['nombre' => $marca],
                ['created_at' => now(), 'updated_at' => now()]
            );
            $marcaId = DB::table('marcas')->where('nombre', $marca)->first()->id;

            foreach ($modelos as $modelo) {
                DB::table('modelos')->updateOrInsert(
                    ['marca_id' => $marcaId, 'nombre' => $modelo],
                    ['created_at' => now(), 'updated_at' => now()]
                );
            }
        }
    }
}
