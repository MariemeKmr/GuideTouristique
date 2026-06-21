<?php

use App\Models\User;
use Illuminate\Auth\Notifications\ResetPassword;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Password;

it('envoie un lien de reinitialisation par email', function () {
    Notification::fake();
    $user = User::factory()->create(['role' => 'visiteur']);

    $this->from(route('password.request'))
        ->post(route('password.email'), ['email' => $user->email])
        ->assertRedirect()
        ->assertSessionHas('success');

    Notification::assertSentTo($user, ResetPassword::class);
});

it('reinitialise le mot de passe avec un token valide', function () {
    $user  = User::factory()->create(['role' => 'visiteur']);
    $token = Password::createToken($user);

    $this->from(route('password.reset', $token))
        ->post(route('password.update'), [
            'token'                 => $token,
            'email'                 => $user->email,
            'password'              => 'nouveau-mot-de-passe',
            'password_confirmation' => 'nouveau-mot-de-passe',
        ])
        ->assertRedirect(route('login'))
        ->assertSessionHas('success');

    expect(Hash::check('nouveau-mot-de-passe', $user->fresh()->password))->toBeTrue();
});

it('refuse la reinitialisation avec un token invalide', function () {
    $user = User::factory()->create(['role' => 'visiteur']);

    $this->from(route('password.reset', 'jeton-bidon'))
        ->post(route('password.update'), [
            'token'                 => 'jeton-bidon',
            'email'                 => $user->email,
            'password'              => 'nouveau-mot-de-passe',
            'password_confirmation' => 'nouveau-mot-de-passe',
        ])
        ->assertSessionHasErrors('email');
});
