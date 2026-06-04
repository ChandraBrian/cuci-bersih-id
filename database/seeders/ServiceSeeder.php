<?php

namespace Database\Seeders;

use App\Models\Service;
use Illuminate\Database\Seeder;

class ServiceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $services = [
            [
                'name' => 'Cuci Motor Reguler',
                'price' => 20000.00,
                'duration_estimate' => 30,
                'description' => 'Cuci motor standar mencakup pembersihan bodi, roda, dan kolong motor secara cepat dan bersih.',
                'is_active' => true,
            ],
            [
                'name' => 'Cuci Motor Premium',
                'price' => 40000.00,
                'duration_estimate' => 60,
                'description' => 'Cuci motor ekstra bersih mencakup pembersihan detail celah mesin, wax pelindung bodi, dan semir ban.',
                'is_active' => true,
            ],
            [
                'name' => 'Cuci Mobil Reguler',
                'price' => 50000.00,
                'duration_estimate' => 45,
                'description' => 'Cuci mobil luar dan dalam (vacuum interior) serta semir ban.',
                'is_active' => true,
            ],
            [
                'name' => 'Cuci Mobil Premium',
                'price' => 100000.00,
                'duration_estimate' => 90,
                'description' => 'Paket cuci premium dengan pembersihan kolong mobil, vacuum detail, cuci mesin ringan, wax pelindung cat, dan pewangi interior.',
                'is_active' => true,
            ],
        ];

        foreach ($services as $service) {
            Service::updateOrCreate(
                ['name' => $service['name']],
                $service
            );
        }
    }
}
