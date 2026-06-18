<?php

it('affiche la page d\'accueil', function () {
    $this->get('/')->assertStatus(200);
});
