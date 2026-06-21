<?php

use App\Models\User;

it('redirige chaque role vers son tableau de bord', function () {
    $admin    = User::factory()->create(['role' => 'admin']);
    $visiteur = User::factory()->create(['role' => 'visiteur']);
    $taximan  = User::factory()->create(['role' => 'taximan']);

    $this->actingAs($admin)->get(route('dashboard'))->assertRedirect(route('admin.dashboard'));
    $this->actingAs($visiteur)->get(route('dashboard'))->assertRedirect(route('visitor.dashboard'));
    $this->actingAs($taximan)->get(route('dashboard'))->assertRedirect(route('taximan.dashboard'));
});

it('renvoie un visiteur vers son espace s\'il tente d\'acceder a l\'admin', function () {
    $visiteur = User::factory()->create(['role' => 'visiteur']);

    $this->actingAs($visiteur)
        ->get(route('admin.destinations.index'))
        ->assertRedirect(route('visitor.dashboard'))
        ->assertSessionHas('error');
});

it('renvoie un taximan vers son espace s\'il tente d\'acceder a l\'admin', function () {
    $taximan = User::factory()->create(['role' => 'taximan']);

    $this->actingAs($taximan)
        ->get(route('admin.destinations.index'))
        ->assertRedirect(route('taximan.dashboard'))
        ->assertSessionHas('error');
});

it('renvoie un visiteur vers son espace s\'il tente d\'acceder a l\'espace chauffeur', function () {
    $visiteur = User::factory()->create(['role' => 'visiteur']);

    $this->actingAs($visiteur)
        ->get(route('taximan.courses.index'))
        ->assertRedirect(route('visitor.dashboard'))
        ->assertSessionHas('error');
});

it('renvoie un taximan vers son espace s\'il tente d\'acceder a l\'espace visiteur', function () {
    $taximan = User::factory()->create(['role' => 'taximan']);

    $this->actingAs($taximan)
        ->get(route('visitor.courses.index'))
        ->assertRedirect(route('taximan.dashboard'))
        ->assertSessionHas('error');
});

it('autorise chaque role a consulter son propre espace', function () {
    $admin    = User::factory()->create(['role' => 'admin']);
    $visiteur = User::factory()->create(['role' => 'visiteur']);
    $taximan  = User::factory()->create(['role' => 'taximan']);

    $this->actingAs($admin)->get(route('admin.destinations.index'))->assertOk();
    $this->actingAs($visiteur)->get(route('visitor.courses.index'))->assertOk();
    $this->actingAs($taximan)->get(route('taximan.courses.index'))->assertOk();
});

it('redirige un invite vers la connexion', function () {
    $this->get(route('dashboard'))->assertRedirect(route('login'));
});
