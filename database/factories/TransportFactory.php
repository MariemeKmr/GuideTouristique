<?php

namespace Database\Factories;

use App\Models\Transport;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Transport>
 */
class TransportFactory extends Factory
{
    protected $model = Transport::class;

    public function definition(): array
    {
        $methodes = ['Taxi', 'Bus Dakar Dem Dikk', 'Car rapide', 'Location de voiture', 'VTC', 'Pirogue'];

        return [
            'methode'            => fake()->randomElement($methodes),
            'approximation_cout' => fake()->numberBetween(500, 25000) . ' FCFA',
            'description'        => fake()->optional()->sentence(12),
        ];
    }
}
