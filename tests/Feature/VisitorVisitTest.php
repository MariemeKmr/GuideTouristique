<?php

use App\Models\Destination;
use App\Models\User;

it('permet à un visiteur de marquer une destination comme visitée', function () {
    $visiteur    = User::factory()->create(['role' => 'visiteur']);
    $destination = Destination::factory()->create();

    $this->actingAs($visiteur)->post(route('visitor.destinations.visit', $destination), [
        'date_visite' => '2026-01-15',
    ])->assertRedirect(route('visitor.destinations.show', $destination));

    $this->assertDatabaseHas('destination_visiteur', [
        'user_id'        => $visiteur->id,
        'destination_id' => $destination->id,
        'date_visite'    => '2026-01-15',
    ]);
});

it('liste les destinations visitées dans « Mes visites »', function () {
    $visiteur    = User::factory()->create(['role' => 'visiteur']);
    $destination = Destination::factory()->create(['name' => 'Lac Rose']);

    $this->actingAs($visiteur)->post(route('visitor.destinations.visit', $destination), [
        'date_visite' => '2026-02-01',
    ]);

    $this->actingAs($visiteur)
        ->get(route('visitor.visits'))
        ->assertOk()
        ->assertSee('Lac Rose');
});

it('permet de retirer une destination de ses visites', function () {
    $visiteur    = User::factory()->create(['role' => 'visiteur']);
    $destination = Destination::factory()->create();

    $this->actingAs($visiteur)->post(route('visitor.destinations.visit', $destination));
    $this->actingAs($visiteur)->delete(route('visitor.destinations.unvisit', $destination));

    $this->assertDatabaseMissing('destination_visiteur', [
        'user_id'        => $visiteur->id,
        'destination_id' => $destination->id,
    ]);
});
