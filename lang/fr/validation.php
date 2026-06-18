<?php

/**
 * Traductions françaises des messages de validation (sous-ensemble
 * couvrant les règles utilisées par l'authentification).
 * Vous pouvez compléter ce fichier avec le projet laravel-lang si besoin.
 */

return [
    'required'  => 'Le champ :attribute est obligatoire.',
    'email'     => 'Le champ :attribute doit être une adresse email valide.',
    'unique'    => 'Cette valeur de :attribute est déjà utilisée.',
    'confirmed' => 'La confirmation de :attribute ne correspond pas.',
    'in'        => 'La valeur de :attribute est invalide.',
    'string'    => 'Le champ :attribute doit être une chaîne de caractères.',
    'max'       => [
        'string' => 'Le champ :attribute ne doit pas dépasser :max caractères.',
    ],
    'min'       => [
        'string' => 'Le champ :attribute doit contenir au moins :min caractères.',
    ],

    /*
    | Noms d'attributs personnalisés (repris si non fournis dans le contrôleur).
    */
    'attributes' => [
        'first_name' => 'prénom',
        'last_name'  => 'nom',
        'phone'      => 'téléphone',
        'email'      => 'adresse email',
        'role'       => 'type de compte',
        'password'   => 'mot de passe',
    ],
];
