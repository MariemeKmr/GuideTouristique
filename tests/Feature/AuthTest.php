<?php

use App\Models\User;

it('affiche la page de connexion aux invités', function () {
    $this->get(route('login'))->assertOk();
});

it('permet à un visiteur de s\'inscrire et le connecte', function () {
    $response = $this->post(route('register'), [
        'first_name'            => 'Awa',
        'last_name'             => 'Diop',
        'phone'                 => '+221 77 123 45 67',
        'email'                 => 'awa@example.com',
        'role'                  => 'visiteur',
        'password'              => 'motdepasse123',
        'password_confirmation' => 'motdepasse123',
    ]);

    $response->assertRedirect(route('dashboard'));
    $this->assertAuthenticated();
    $this->assertDatabaseHas('users', [
        'email' => 'awa@example.com',
        'role'  => 'visiteur',
    ]);
});

it('refuse la création d\'un compte administrateur via l\'inscription', function () {
    $this->post(route('register'), [
        'first_name'            => 'Pirate',
        'last_name'             => 'Admin',
        'email'                 => 'pirate@example.com',
        'role'                  => 'admin',
        'password'              => 'motdepasse123',
        'password_confirmation' => 'motdepasse123',
    ])->assertSessionHasErrors('role');

    $this->assertDatabaseMissing('users', ['email' => 'pirate@example.com']);
});

it('connecte un utilisateur avec les bons identifiants', function () {
    $user = User::factory()->create([
        'email' => 'test@example.com',
        'role'  => 'visiteur',
    ]); // mot de passe par défaut de la factory : "password"

    $this->post(route('login'), [
        'email'    => 'test@example.com',
        'password' => 'password',
    ])->assertRedirect(route('dashboard'));

    $this->assertAuthenticatedAs($user);
});

it('rejette une connexion avec un mauvais mot de passe', function () {
    User::factory()->create(['email' => 'test@example.com']);

    $this->post(route('login'), [
        'email'    => 'test@example.com',
        'password' => 'mauvais',
    ])->assertSessionHasErrors('email');

    $this->assertGuest();
});

it('déconnecte un utilisateur connecté', function () {
    $user = User::factory()->create();

    $this->actingAs($user)->post(route('logout'))->assertRedirect(route('login'));
    $this->assertGuest();
});
