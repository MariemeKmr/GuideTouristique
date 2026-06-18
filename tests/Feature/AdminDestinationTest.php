<?php

use App\Models\Destination;
use App\Models\User;

it('permet à un admin de créer une destination', function () {
    $admin = User::factory()->create(['role' => 'admin']);

    $this->actingAs($admin)->post(route('admin.destinations.store'), [
        'name'        => 'Île de Gorée',
        'localite'    => 'Dakar',
        'rue'         => null,
        'description' => 'Site historique classé.',
    ])->assertRedirect(route('admin.destinations.index'));

    $this->assertDatabaseHas('destinations', ['name' => 'Île de Gorée']);
});

it('valide les champs obligatoires d\'une destination', function () {
    $admin = User::factory()->create(['role' => 'admin']);

    $this->actingAs($admin)
        ->post(route('admin.destinations.store'), ['name' => ''])
        ->assertSessionHasErrors(['name', 'localite']);
});

it('permet à un admin de modifier puis supprimer une destination', function () {
    $admin       = User::factory()->create(['role' => 'admin']);
    $destination = Destination::factory()->create(['name' => 'Ancien nom']);

    $this->actingAs($admin)->put(route('admin.destinations.update', $destination), [
        'name'     => 'Nouveau nom',
        'localite' => 'Saint-Louis',
    ])->assertRedirect(route('admin.destinations.index'));

    $this->assertDatabaseHas('destinations', ['name' => 'Nouveau nom']);

    $this->actingAs($admin)
        ->delete(route('admin.destinations.destroy', $destination))
        ->assertRedirect(route('admin.destinations.index'));

    $this->assertDatabaseMissing('destinations', ['id' => $destination->id]);
});
