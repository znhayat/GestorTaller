<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class MaterialSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $materiales = [
            ['nombre' => 'Piel Natural Negra', 'tipo' => 'Cuero', 'unidad' => 'm2', 'precio_unitario' => 45.00, 'stock' => 50],
            ['nombre' => 'Piel Natural Beige', 'tipo' => 'Cuero', 'unidad' => 'm2', 'precio_unitario' => 48.00, 'stock' => 30],
            ['nombre' => 'Polipiel Automotriz Gris', 'tipo' => 'Sintético', 'unidad' => 'metros lineales', 'precio_unitario' => 18.50, 'stock' => 100],
            ['nombre' => 'Alcántara Original Antracita', 'tipo' => 'Microfibra', 'unidad' => 'm2', 'precio_unitario' => 85.00, 'stock' => 20],
            ['nombre' => 'Espuma 5mm (Techo)', 'tipo' => 'Relleno', 'unidad' => 'metros lineales', 'precio_unitario' => 12.00, 'stock' => 200],
            ['nombre' => 'Espuma 10mm (Asientos)', 'tipo' => 'Relleno', 'unidad' => 'm2', 'precio_unitario' => 15.00, 'stock' => 150],
            ['nombre' => 'Pegamento de Contacto Spray', 'tipo' => 'Adhesivo', 'unidad' => 'unidad (400ml)', 'precio_unitario' => 9.50, 'stock' => 60],
            ['nombre' => 'Hilo de Nylon Reforzado', 'tipo' => 'Costura', 'unidad' => 'bobina', 'precio_unitario' => 14.00, 'stock' => 40],
            ['nombre' => 'Grapas Tapicería (Caja)', 'tipo' => 'Fijación', 'unidad' => 'caja 1000u', 'precio_unitario' => 5.00, 'stock' => 100],
        ];

        foreach ($materiales as $mat) {
            \App\Models\Material::create($mat);
        }
    }
}
