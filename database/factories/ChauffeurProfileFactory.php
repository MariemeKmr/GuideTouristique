<?php

namespace Database\Factories;

use App\Models\ChauffeurProfile;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\ChauffeurProfile>
 */
class ChauffeurProfileFactory extends Factory
{
    protected $model = ChauffeurProfile::class;

    public function definition(): array
    {
        $zones = ['Dakar — Plateau', 'Almadies', 'Yoff', 'Ouakam', 'Parcelles Assainies', 'Rufisque'];
        $vehicules = ['Toyota Corolla blanche', 'Hyundai Accent grise', 'Peugeot 301 noire', 'Renault Logan beige'];

        return [
            'zone'            => fake()->randomElement($zones),
            'vehicule'        => fake()->randomElement($vehicules),
            'tarif_indicatif' => fake()->numberBetween(1, 6) * 500 . ' FCFA / course',
            'disponible'      => fake()->boolean(80),
            'bio'             => fake()->optional()->sentence(12),
        ];
    }
}
