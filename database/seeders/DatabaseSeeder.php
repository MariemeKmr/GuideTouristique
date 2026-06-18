<?php

namespace Database\Seeders;

use App\Models\Destination;
use App\Models\Transport;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Peuple la base avec des comptes de test pour chaque rôle.
     */
    public function run(): void
    {
        // --- Comptes de démonstration (identifiants connus) ---

        User::updateOrCreate(
            ['email' => 'admin@guide.test'],
            [
                'first_name' => 'Admin',
                'last_name'  => 'Principal',
                'phone'      => '+221 77 000 00 00',
                'role'       => User::ROLE_ADMIN,
                'password'   => Hash::make('password'),
            ]
        );

        User::updateOrCreate(
            ['email' => 'visiteur@guide.test'],
            [
                'first_name' => 'Awa',
                'last_name'  => 'Diop',
                'phone'      => '+221 77 111 11 11',
                'role'       => User::ROLE_VISITEUR,
                'password'   => Hash::make('password'),
            ]
        );

        User::updateOrCreate(
            ['email' => 'taximan@guide.test'],
            [
                'first_name' => 'Moussa',
                'last_name'  => 'Fall',
                'phone'      => '+221 77 222 22 22',
                'role'       => User::ROLE_TAXIMAN,
                'password'   => Hash::make('password'),
            ]
        );

        // --- Quelques utilisateurs aléatoires (facultatif) ---
        User::factory(10)->create();

        // --- Données de démonstration ---
        Destination::factory(12)->create();
        Transport::factory(6)->create();
    }
}
