<?php

namespace Database\Seeders;

use App\Models\Establishment;
use Illuminate\Database\Seeder;

class EstablishmentSeeder extends Seeder
{
    public function run(): void
    {
        $establishments = [
            ['name' => 'Université de Paris', 'type' => 'university', 'city' => 'Paris', 'address' => '75005 Paris', 'email' => 'contact@univ-paris.fr'],
            ['name' => 'Ecole Polytechnique', 'type' => 'school', 'city' => 'Palaiseau', 'address' => '91120 Palaiseau', 'email' => 'info@polytechnique.fr'],
            ['name' => 'ISEN Lille', 'type' => 'school', 'city' => 'Lille', 'address' => '59000 Lille', 'email' => 'contact@isen-lille.fr'],
            ['name' => 'Université de Lyon', 'type' => 'university', 'city' => 'Lyon', 'address' => '69007 Lyon', 'email' => 'contact@univ-lyon.fr'],
            ['name' => 'Université de Marseille', 'type' => 'university', 'city' => 'Marseille', 'address' => '13005 Marseille', 'email' => 'contact@univ-mrs.fr'],
            ['name' => 'EM Lyon Business School', 'type' => 'school', 'city' => 'Lyon', 'address' => '69007 Lyon', 'email' => 'admissions@em-lyon.com'],
            ['name' => 'Université de Toulouse', 'type' => 'university', 'city' => 'Toulouse', 'address' => '31000 Toulouse', 'email' => 'contact@univ-toulouse.fr'],
            ['name' => 'INSA Lyon', 'type' => 'school', 'city' => 'Lyon', 'address' => '69100 Villeurbanne', 'email' => 'direction@insa-lyon.fr'],
        ];

        foreach ($establishments as $establishment) {
            Establishment::create($establishment);
        }
    }
}
