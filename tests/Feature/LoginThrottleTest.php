<?php

use App\Models\User;

it('bloque la connexion apres cinq tentatives echouees', function () {
    $user = User::factory()->create(['role' => 'visiteur']);

    // 5 tentatives avec un mauvais mot de passe
    foreach (range(1, 5) as $ignored) {
        $this->from(route('login'))->post(route('login'), [
            'email'    => $user->email,
            'password' => 'mauvais-mot-de-passe',
        ]);
    }

    // La tentative suivante est bloquee, meme avec le bon mot de passe
    $this->from(route('login'))
        ->post(route('login'), [
            'email'    => $user->email,
            'password' => 'password',
        ])
        ->assertRedirect(route('login'))
        ->assertSessionHasErrors('email');

    $this->assertGuest();
});

it('laisse passer une connexion valide sans blocage', function () {
    $user = User::factory()->create(['role' => 'visiteur']);

    $this->from(route('login'))
        ->post(route('login'), [
            'email'    => $user->email,
            'password' => 'password',
        ])
        ->assertRedirect(route('dashboard'));

    $this->assertAuthenticatedAs($user);
});
