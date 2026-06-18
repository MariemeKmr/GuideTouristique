<?php

namespace Database\Factories;

use App\Models\Destination;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Destination>
 */
class DestinationFactory extends Factory
{
    protected $model = Destination::class;

    public function definition(): array
    {
        $localites = ['Dakar', 'Saint-Louis', 'Gorée', 'Saly', 'Thiès', 'Touba', 'Ziguinchor'];

        return [
            'name'        => fake()->unique()->streetName(),
            'description' => fake()->optional()->paragraph(),
            'localite'    => fake()->randomElement($localites),
            'rue'         => fake()->optional()->streetAddress(),
        ];
    }
}
