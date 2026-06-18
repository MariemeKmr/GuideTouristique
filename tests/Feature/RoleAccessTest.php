<?php

use App\Models\User;

it('redirige chaque rôle vers son tableau de bord', function () {
    $admin    = User::factory()->create(['role' => 'admin']);
    $visiteur = User::factory()->create(['role' => 'visiteur']);
    $taximan  = User::factory()->create(['role' => 'taximan']);

    $this->actingAs($admin)->get(route('dashboard'))->assertRedirect(route('admin.dashboard'));
    $this->actingAs($visiteur)->get(route('dashboard'))->assertRedirect(route('visitor.dashboard'));
    $this->actingAs($taximan)->get(route('dashboard'))->assertRedirect(route('taximan.dashboard'));
});

it('interdit à un visiteur l\'accès à l\'espace admin', function () {
    $visiteur = User::factory()->create(['role' => 'visiteur']);

    $this->actingAs($visiteur)
        ->get(route('admin.destinations.index'))
        ->assertForbidden();
});

it('redirige un invité vers la connexion', function () {
    $this->get(route('dashboard'))->assertRedirect(route('login'));
});
