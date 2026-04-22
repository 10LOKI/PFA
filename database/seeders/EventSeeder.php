<?php

namespace Database\Seeders;

use App\Models\Event;
use App\Models\User;
use Illuminate\Database\Seeder;

class EventSeeder extends Seeder
{
    public function run(): void
    {
        $partners = User::where('role', 'partner')->get();

        if ($partners->isEmpty()) {
            $this->command->warn('No partners found. Skipping event seeding.');

            return;
        }

        $events = [
            [
                'title' => 'Nettoyage du Parc de la Villette',
                'description' => 'Participation au nettoyage du parc avec collecte des déchets.',
                'category' => 'environment',
                'city' => 'Paris',
                'address' => 'Parc de la Villette, 75019 Paris',
                'latitude' => 48.8863,
                'longitude' => 2.3874,
                'volunteer_quota' => 20,
                'duration_hours' => 4,
                'points_reward' => 100,
                'urgency_multiplier' => 1.00,
                'status' => 'approved',
            ],
            [
                'title' => 'Aide aux personnes âgées',
                'description' => 'Accompagnement et aide aux personnes âgées dans leur quotidien.',
                'category' => 'social',
                'city' => 'Lyon',
                'address' => 'Maison de Retraite Les Tilleuls, 69006 Lyon',
                'latitude' => 45.7640,
                'longitude' => 4.8357,
                'volunteer_quota' => 10,
                'duration_hours' => 3,
                'points_reward' => 80,
                'urgency_multiplier' => 1.20,
                'status' => 'approved',
            ],
            [
                'title' => 'Tri des déchets',
                'description' => 'Tri et recyclage des déchets collectés.',
                'category' => 'environment',
                'city' => 'Marseille',
                'address' => 'Centre de Tri, 13002 Marseille',
                'latitude' => 43.2965,
                'longitude' => 5.3698,
                'volunteer_quota' => 15,
                'duration_hours' => 2,
                'points_reward' => 50,
                'urgency_multiplier' => 1.00,
                'status' => 'approved',
            ],
            [
                'title' => 'Animation bibliothèque',
                'description' => 'Aide à l\'animation et au prêt de livres pour les enfants.',
                'category' => 'education',
                'city' => 'Toulouse',
                'address' => 'Bibliothèque Centrale, 31000 Toulouse',
                'latitude' => 43.6047,
                'longitude' => 1.4442,
                'volunteer_quota' => 8,
                'duration_hours' => 3,
                'points_reward' => 75,
                'urgency_multiplier' => 1.00,
                'status' => 'approved',
            ],
            [
                'title' => 'Jardinage urbain',
                'description' => 'Entretien des jardins communautaires et plantation.',
                'category' => 'environment',
                'city' => 'Lille',
                'address' => 'Jardin des Weppes, 59000 Lille',
                'latitude' => 50.6292,
                'longitude' => 3.0573,
                'volunteer_quota' => 12,
                'duration_hours' => 4,
                'points_reward' => 100,
                'urgency_multiplier' => 1.10,
                'status' => 'approved',
            ],
        ];

        $now = now();
        foreach ($events as $eventData) {
            $partner = $partners->random();

            $startsAt = $now->copy()->addDays(rand(1, 30));
            $endsAt = $startsAt->copy()->addHours($eventData['duration_hours']);

            Event::create(array_merge($eventData, [
                'partner_id' => $partner->id,
                'starts_at' => $startsAt,
                'ends_at' => $endsAt,
            ]));
        }
    }
}
