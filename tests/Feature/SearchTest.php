<?php

use App\Models\Destination;
use App\Models\User;

it('filtre les destinations selon la recherche', function () {
    $visiteur = User::factory()->create(['role' => 'visiteur']);
    Destination::factory()->create(['name' => 'Ile de Goree', 'localite' => 'Dakar']);
    Destination::factory()->create(['name' => 'Lac Rose', 'localite' => 'Tivaouane']);

    $this->actingAs($visiteur)
        ->get(route('visitor.destinations.index', ['q' => 'Goree']))
        ->assertOk()
        ->assertSee('Ile de Goree')
        ->assertDontSee('Lac Rose');
});

it('affiche toutes les destinations sans recherche', function () {
    $visiteur = User::factory()->create(['role' => 'visiteur']);
    Destination::factory()->create(['name' => 'Ile de Goree']);
    Destination::factory()->create(['name' => 'Lac Rose']);

    $this->actingAs($visiteur)
        ->get(route('visitor.destinations.index'))
        ->assertOk()
        ->assertSee('Ile de Goree')
        ->assertSee('Lac Rose');
});
