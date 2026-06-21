<?php

use App\Models\Course;
use App\Models\User;

/** Cree un couple visiteur / chauffeur de test. */
function coupleCourse(): array
{
    return [
        User::factory()->create(['role' => 'visiteur']),
        User::factory()->create(['role' => 'taximan']),
    ];
}

it('deroule le cycle complet d\'une course, de la demande a la notation', function () {
    [$awa, $moussa] = coupleCourse();

    // 1. Le visiteur demande une course a un chauffeur
    $this->actingAs($awa)
        ->from(route('visitor.courses.index'))
        ->post(route('visitor.courses.store', $moussa), [
            'depart'      => 'Aeroport AIBD',
            'destination' => 'Plateau',
        ])
        ->assertRedirect(route('visitor.courses.index'));

    $course = Course::where('visiteur_id', $awa->id)
        ->where('chauffeur_id', $moussa->id)
        ->firstOrFail();
    expect($course->statut)->toBe('demandee');

    // 2. Le chauffeur propose un prix
    $this->actingAs($moussa)
        ->from(route('taximan.courses.index'))
        ->patch(route('taximan.courses.price.propose', $course), ['prix' => 5000])
        ->assertRedirect();
    expect($course->refresh()->statut)->toBe('prix_propose');
    expect($course->prix)->toBe(5000);

    // 3. Le visiteur accepte le prix
    $this->actingAs($awa)
        ->from(route('visitor.courses.index'))
        ->patch(route('visitor.courses.price.accept', $course))
        ->assertRedirect();
    expect($course->refresh()->statut)->toBe('acceptee');

    // 4. Le chauffeur fait avancer la course : en_route -> arrive -> attente_client
    foreach (['en_route', 'arrive', 'attente_client'] as $etape) {
        $this->actingAs($moussa)
            ->from(route('taximan.courses.index'))
            ->patch(route('taximan.courses.advance', $course))
            ->assertRedirect();
        expect($course->refresh()->statut)->toBe($etape);
    }

    // 5. Le visiteur confirme qu'il est bien monte
    $this->actingAs($awa)
        ->from(route('visitor.courses.index'))
        ->patch(route('visitor.courses.confirm', $course), ['reponse' => 'oui'])
        ->assertRedirect();
    expect($course->refresh()->statut)->toBe('en_course');

    // 6. Le chauffeur termine la course
    $this->actingAs($moussa)
        ->from(route('taximan.courses.index'))
        ->patch(route('taximan.courses.advance', $course))
        ->assertRedirect();
    expect($course->refresh()->statut)->toBe('terminee');

    // 7. Le visiteur note la course
    $this->actingAs($awa)
        ->from(route('visitor.courses.index'))
        ->patch(route('visitor.courses.rate', $course), ['note' => 5, 'commentaire' => 'Parfait'])
        ->assertRedirect();
    $course->refresh();
    expect($course->note)->toBe(5);
    expect($course->commentaire)->toBe('Parfait');
});

it('annule la course quand le client refuse le prix', function () {
    [$awa, $moussa] = coupleCourse();
    $course = Course::create([
        'visiteur_id'  => $awa->id,
        'chauffeur_id' => $moussa->id,
        'depart'       => 'Yoff',
        'destination'  => 'Almadies',
        'statut'       => 'prix_propose',
        'prix'         => 4000,
    ]);

    $this->actingAs($awa)
        ->from(route('visitor.courses.index'))
        ->patch(route('visitor.courses.price.refuse', $course))
        ->assertRedirect();

    $course->refresh();
    expect($course->statut)->toBe('annulee');
    expect($course->annulee_par)->toBe('prix');
});

it('gere une contre-proposition acceptee par le chauffeur', function () {
    [$awa, $moussa] = coupleCourse();
    $course = Course::create([
        'visiteur_id'  => $awa->id,
        'chauffeur_id' => $moussa->id,
        'depart'       => 'Yoff',
        'destination'  => 'Lac Rose',
        'statut'       => 'prix_propose',
        'prix'         => 6000,
    ]);

    // Le client propose un autre prix
    $this->actingAs($awa)
        ->from(route('visitor.courses.index'))
        ->patch(route('visitor.courses.price.counter', $course), ['prix' => 4500])
        ->assertRedirect();
    expect($course->refresh()->statut)->toBe('contre_propose');
    expect($course->prix)->toBe(4500);

    // Le chauffeur accepte la contre-proposition
    $this->actingAs($moussa)
        ->from(route('taximan.courses.index'))
        ->patch(route('taximan.courses.counter.accept', $course))
        ->assertRedirect();
    expect($course->refresh()->statut)->toBe('acceptee');
});

it('empeche un visiteur d\'agir sur une course qui n\'est pas la sienne', function () {
    [$awa, $moussa] = coupleCourse();
    $intrus = User::factory()->create(['role' => 'visiteur']);
    $course = Course::create([
        'visiteur_id'  => $awa->id,
        'chauffeur_id' => $moussa->id,
        'depart'       => 'A',
        'destination'  => 'B',
        'statut'       => 'prix_propose',
        'prix'         => 4000,
    ]);

    // L'acces interdit (403) est renvoye vers le tableau de bord de l'intrus.
    $this->actingAs($intrus)
        ->from(route('visitor.courses.index'))
        ->patch(route('visitor.courses.price.accept', $course))
        ->assertRedirect(route('visitor.dashboard'));

    expect($course->refresh()->statut)->toBe('prix_propose');
});

it('empeche un chauffeur de faire avancer la course d\'un autre chauffeur', function () {
    [$awa, $moussa] = coupleCourse();
    $autre = User::factory()->create(['role' => 'taximan']);
    $course = Course::create([
        'visiteur_id'  => $awa->id,
        'chauffeur_id' => $moussa->id,
        'depart'       => 'A',
        'destination'  => 'B',
        'statut'       => 'acceptee',
        'prix'         => 4000,
    ]);

    $this->actingAs($autre)
        ->from(route('taximan.courses.index'))
        ->patch(route('taximan.courses.advance', $course))
        ->assertRedirect(route('taximan.dashboard'));

    expect($course->refresh()->statut)->toBe('acceptee');
});
