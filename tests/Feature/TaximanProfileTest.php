<?php

use App\Models\User;

it('permet à un chauffeur de compléter son profil', function () {
    $taximan = User::factory()->create(['role' => 'taximan']);

    $this->actingAs($taximan)->put(route('taximan.profile.update'), [
        'first_name'      => 'Moussa',
        'last_name'       => 'Fall',
        'phone'           => '+221 77 222 22 22',
        'zone'            => 'Dakar - Plateau',
        'vehicule'        => 'Toyota Corolla blanche',
        'tarif_indicatif' => '2 000 FCFA / course',
        'disponible'      => '1',
        'bio'             => 'Chauffeur expérimenté.',
    ])->assertRedirect(route('taximan.profile.edit'));

    $this->assertDatabaseHas('users', [
        'id'    => $taximan->id,
        'phone' => '+221 77 222 22 22',
    ]);

    $this->assertDatabaseHas('chauffeur_profiles', [
        'user_id'    => $taximan->id,
        'zone'       => 'Dakar - Plateau',
        'disponible' => true,
    ]);
});

it('affiche le profil du chauffeur côté visiteur', function () {
    $visiteur = User::factory()->create(['role' => 'visiteur']);
    $taximan  = User::factory()->create(['role' => 'taximan', 'first_name' => 'Moussa', 'last_name' => 'Fall']);

    $this->actingAs($visiteur)
        ->get(route('visitor.drivers.show', $taximan))
        ->assertOk()
        ->assertSee('Moussa Fall');
});

it('renvoie 404 pour un profil chauffeur inexistant (non-taximan)', function () {
    $visiteur = User::factory()->create(['role' => 'visiteur']);
    $autre    = User::factory()->create(['role' => 'visiteur']);

    $this->actingAs($visiteur)
        ->get(route('visitor.drivers.show', $autre))
        ->assertNotFound();
});
