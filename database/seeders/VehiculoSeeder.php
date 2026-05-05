<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class VehiculoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data = [
            'Audi' => ['A1', 'A3', 'A4', 'A6', 'Q3', 'Q5', 'TT'],
            'BMW' => ['Serie 1', 'Serie 3', 'Serie 5', 'X1', 'X3', 'X5', 'Z4'],
            'Citroen' => ['C3', 'C4', 'C5', 'Berlingo', 'Jumpy'],
            'Fiat' => ['500', 'Panda', 'Tipo', 'Ducato'],
            'Ford' => ['Fiesta', 'Focus', 'Kuga', 'Transit', 'Mustang'],
            'Hyundai' => ['i10', 'i20', 'i30', 'Tucson', 'Kona'],
            'Kia' => ['Picanto', 'Rio', 'Ceed', 'Sportage', 'Niro'],
            'Mercedes-Benz' => ['Clase A', 'Clase C', 'Clase E', 'GLA', 'GLC', 'Vito'],
            'Opel' => ['Corsa', 'Astra', 'Insignia', 'Mokka', 'Vivaro'],
            'Peugeot' => ['208', '308', '508', '2008', '3008', 'Partner'],
            'Renault' => ['Clio', 'Megane', 'Captur', 'Kadjar', 'Kangoo', 'Trafic'],
            'Seat' => ['Ibiza', 'Leon', 'Arona', 'Ateca', 'Tarraco', 'Alhambra'],
            'Toyota' => ['Yaris', 'Corolla', 'C-HR', 'RAV4', 'Hilux'],
            'Volkswagen' => ['Polo', 'Golf', 'Passat', 'Tiguan', 'T-Roc', 'Transporter', 'Caddy']
        ];

        foreach ($data as $marcaNombre => $modelos) {
            $marca = \App\Models\Marca::firstOrCreate(['nombre' => $marcaNombre]);
            foreach ($modelos as $modeloNombre) {
                \App\Models\Modelo::firstOrCreate([
                    'marca_id' => $marca->id,
                    'nombre' => $modeloNombre
                ]);
            }
        }
    }
}
