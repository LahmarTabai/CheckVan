<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Marque;
use App\Models\Modele;

class MarquesModelesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $marquesData = [
            'Renault' => [
                'Master', 'Trafic', 'Kangoo', 'Express', 'Maxity'
            ],
            'Peugeot' => [
                'Boxer', 'Partner', 'Expert', 'Bipper', 'e-Expert'
            ],
            'Citroën' => [
                'Jumper', 'Berlingo', 'Dispatch', 'Nemo', 'e-Berlingo'
            ],
            'Ford' => [
                'Transit', 'Transit Custom', 'Transit Connect', 'Ranger', 'Tourneo'
            ],
            'Mercedes-Benz' => [
                'Sprinter', 'Vito', 'Citan', 'Atego', 'Actros'
            ],
            'Volkswagen' => [
                'Crafter', 'Transporter', 'Caddy', 'Amarok', 'ID. Buzz Cargo'
            ],
            'Fiat' => [
                'Ducato', 'Doblo', 'Talento', 'Fiorino', 'Scudo'
            ],
            'Iveco' => [
                'Daily', 'Eurocargo', 'Stralis', 'Tector', 'S-Way'
            ],
            'Nissan' => [
                'NV200', 'NV300', 'NV400', 'Navara', 'e-NV200'
            ],
            'Toyota' => [
                'Proace', 'Proace City', 'Hilux', 'Land Cruiser', 'Hiace'
            ]
        ];

        foreach ($marquesData as $marqueNom => $modeles) {
            $marque = Marque::create([
                'nom' => $marqueNom,
                'pays' => in_array($marqueNom, ['Renault', 'Peugeot', 'Citroën']) ? 'France' : 'International',
                'is_active' => true
            ]);

            foreach ($modeles as $modeleNom) {
                Modele::create([
                    'marque_id' => $marque->id,
                    'nom' => $modeleNom,
                    'type_vehicule' => 'van',
                    'is_active' => true
                ]);
            }
        }
    }
}
