<?php

namespace Database\Seeders;

use App\Models\ChauffeurProfile;
use App\Models\Activite;
use App\Models\ActiviteReservation;
use App\Models\Course;
use App\Models\Destination;
use App\Models\Signalement;
use App\Models\Transport;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        /*
        |--------------------------------------------------------------------
        | Comptes de demonstration (identifiants connus)
        |--------------------------------------------------------------------
        */
        User::updateOrCreate(['email' => 'admin@guide.test'], [
            'first_name' => 'Admin', 'last_name' => 'Principal',
            'phone' => '+221 77 000 00 00', 'role' => User::ROLE_ADMIN,
            'password' => Hash::make('password'),
        ]);

        User::updateOrCreate(['email' => 'visiteur@guide.test'], [
            'first_name' => 'Awa', 'last_name' => 'Diop',
            'phone' => '+221 77 111 11 11', 'role' => User::ROLE_VISITEUR,
            'password' => Hash::make('password'),
        ]);

        User::updateOrCreate(['email' => 'taximan@guide.test'], [
            'first_name' => 'Moussa', 'last_name' => 'Fall',
            'phone' => '+221 77 222 22 22', 'role' => User::ROLE_TAXIMAN,
            'password' => Hash::make('password'),
        ]);

        // Profil du chauffeur de demonstration (Moussa).
        $moussaCompte = User::where('email', 'taximan@guide.test')->first();
        if ($moussaCompte) {
            ChauffeurProfile::updateOrCreate(['user_id' => $moussaCompte->id], [
                'zone'            => 'Dakar - Plateau',
                'vehicule'        => 'Toyota Corolla blanche',
                'tarif_indicatif' => '2 500 FCFA / course',
                'disponible'      => true,
                'bio'             => 'Chauffeur ponctuel et sympathique, connait bien Dakar et ses environs.',
            ]);
        }

        // Chauffeurs supplementaires pour etoffer l'annuaire (donnees explicites, sans Faker).
        $chauffeursDemo = [
            ['first_name' => 'Cheikh', 'last_name' => 'Ndiaye', 'email' => 'cheikh.ndiaye@example.com', 'phone' => '+221 77 333 33 33',
             'zone' => 'Almadies', 'vehicule' => 'Hyundai Accent grise', 'tarif_indicatif' => '3 000 FCFA / course', 'disponible' => true,
             'bio' => 'Specialiste des transferts aeroport et des longues distances.'],
            ['first_name' => 'Awa', 'last_name' => 'Sow', 'email' => 'awa.sow@example.com', 'phone' => '+221 77 444 44 44',
             'zone' => 'Yoff', 'vehicule' => 'Peugeot 301 noire', 'tarif_indicatif' => '2 000 FCFA / course', 'disponible' => true,
             'bio' => 'Conduite douce, ideale pour decouvrir la ville en toute tranquillite.'],
            ['first_name' => 'Ibrahima', 'last_name' => 'Ba', 'email' => 'ibrahima.ba@example.com', 'phone' => '+221 77 555 55 55',
             'zone' => 'Parcelles Assainies', 'vehicule' => 'Renault Logan beige', 'tarif_indicatif' => '2 500 FCFA / course', 'disponible' => false,
             'bio' => 'Disponible en soiree et le week-end pour vos deplacements.'],
        ];
        foreach ($chauffeursDemo as $c) {
            $compte = User::updateOrCreate(['email' => $c['email']], [
                'first_name' => $c['first_name'], 'last_name' => $c['last_name'],
                'phone' => $c['phone'], 'role' => User::ROLE_TAXIMAN,
                'password' => Hash::make('password'),
            ]);
            ChauffeurProfile::updateOrCreate(['user_id' => $compte->id], [
                'zone'            => $c['zone'],
                'vehicule'        => $c['vehicule'],
                'tarif_indicatif' => $c['tarif_indicatif'],
                'disponible'      => $c['disponible'],
                'bio'             => $c['bio'],
            ]);
        }

        // Visiteurs supplementaires (donnees explicites).
        $visiteursDemo = [
            ['first_name' => 'Mariama', 'last_name' => 'Diallo', 'email' => 'mariama.diallo@example.com', 'phone' => '+221 76 111 22 33'],
            ['first_name' => 'Ousmane', 'last_name' => 'Sarr', 'email' => 'ousmane.sarr@example.com', 'phone' => '+221 76 222 33 44'],
        ];
        foreach ($visiteursDemo as $v) {
            User::updateOrCreate(['email' => $v['email']], [
                'first_name' => $v['first_name'], 'last_name' => $v['last_name'],
                'phone' => $v['phone'], 'role' => User::ROLE_VISITEUR,
                'password' => Hash::make('password'),
            ]);
        }

        /*
        |--------------------------------------------------------------------
        | Destinations reelles (incontournables du Senegal)
        |--------------------------------------------------------------------
        */
        $destinations = [
            ['name' => 'Ile de Goree', 'localite' => 'Dakar', 'rue' => null,
             'description' => "A 20 minutes de chaloupe de Dakar, ile pietonne classee par l'UNESCO. A voir absolument: la Maison des Esclaves, pour comprendre l'histoire de la traite negriere."],
            ['name' => 'Monument de la Renaissance Africaine', 'localite' => 'Dakar', 'rue' => 'Ouakam',
             'description' => "Statue en bronze de 52 metres dominant Ouakam. Vue panoramique exceptionnelle sur toute la presqu'ile."],
            ['name' => 'Musee des Civilisations Noires', 'localite' => 'Dakar', 'rue' => 'Plateau',
             'description' => "Chef-d'oeuvre architectural moderne mettant en valeur l'histoire, l'art et les technologies des civilisations africaines."],
            ['name' => 'Ile de Ngor', 'localite' => 'Dakar', 'rue' => 'Ngor',
             'description' => "Petit paradis a quelques minutes en pirogue. Prisee pour ses vagues de surf, ses galeries d'art a ciel ouvert et ses restaurants de poissons frais."],
            ['name' => 'Mosquee de la Divinite', 'localite' => 'Dakar', 'rue' => 'Corniche Ouest',
             'description' => "Au bord de l'eau le long de la Corniche Ouest, un point de vue unique. La plage voisine accueille la balade en kayak transparent."],
            ['name' => 'Marche Soumbedioune', 'localite' => 'Dakar', 'rue' => 'Soumbedioune',
             'description' => "Marche aux poissons et village artisanal. Ideal pour l'energie locale, les tissus et l'artisanat."],
            ['name' => 'Marche Kermel', 'localite' => 'Dakar', 'rue' => 'Plateau',
             'description' => "Marche historique et sa magnifique halle ronde, au coeur du Plateau."],
            ['name' => 'Lac Rose (Lac Retba)', 'localite' => 'Lac Rose', 'rue' => 'Tivaouane Peulh-Niague',
             'description' => "Lac aux reflets roses selon la saison. Terrain de jeu pour le quad, le buggy, le cheval, le chameau et le kayak transparent dans les dunes."],
            ['name' => 'Reserve de Bandia', 'localite' => 'Bandia', 'rue' => 'Route de Mbour, Sindia',
             'description' => "Safari a moins de 2h de Dakar: rhinoceros, girafes, zebres, antilopes et majestueux baobabs."],
            ['name' => 'La Somone', 'localite' => 'Mbour', 'rue' => null,
             'description' => "Village cotier calme de la Petite Cote, lagune et belles plages pour une ambiance decontractee."],
            ['name' => 'Popenguine', 'localite' => 'Popenguine', 'rue' => null,
             'description' => "Village cotier paisible de la Petite Cote, reserve naturelle et plages tranquilles."],
            ['name' => 'Oasis du Senegal', 'localite' => 'Lompoul', 'rue' => null,
             'description' => "Alternative au desert de Lompoul (ferme): domaine de 300 hectares avec grande dune, plan d'eau et palmeraie. Randonnees 4x4 et balades a dos de dromadaire."],
        ];

        foreach ($destinations as $d) {
            Destination::firstOrCreate(['name' => $d['name']], $d);
        }

        /*
        |--------------------------------------------------------------------
        | Transports reels (tarifs indicatifs en FCFA)
        |--------------------------------------------------------------------
        */
        $transports = [
            ['methode' => 'Train Express Regional (TER)', 'approximation_cout' => '500 a 2 500 FCFA',
             'description' => "Relie Dakar a Diamniadio et l'aeroport AIBD. Seconde classe selon les zones (500, 1 000, 1 500 FCFA), premiere classe a tarif fixe de 2 500 FCFA."],
            ['methode' => 'Bus Rapid Transit (BRT)', 'approximation_cout' => '400 a 500 FCFA',
             'description' => "Reseau sectorise. 400 FCFA dans une meme zone, 500 FCFA des qu'on franchit une limite de zone."],
            ['methode' => 'Taxi urbain (jaune et noir)', 'approximation_cout' => '1 000 a 3 500 FCFA',
             'description' => "Course moyenne en centre-ville entre 1 000 et 2 000 FCFA. Trajet plus long (Almadies, Yoff) entre 2 500 et 3 500 FCFA. Le prix se negocie souvent."],
            ['methode' => 'Car rapide / minibus Tata', 'approximation_cout' => '100 a 350 FCFA',
             'description' => "Transport en commun le plus populaire et economique pour les courtes et moyennes distances."],
            ['methode' => 'Dakar Dem Dikk (urbain)', 'approximation_cout' => '150 a 275 FCFA',
             'description' => "Bus publics bleu et blanc quadrillant la capitale, tarifs tres abordables selon la ligne."],
            ['methode' => 'Moto-taxi (Jakarta)', 'approximation_cout' => '300 a 1 000 FCFA',
             'description' => "Pratique en peripherie et dans les villes secondaires pour se faufiler dans la circulation. Prix selon la distance negociee."],
            ['methode' => 'Taxi-brousse (7 places)', 'approximation_cout' => '1 500 a 11 500 FCFA',
             'description' => "Interurbain au depart des gares routieres (ex: Beaux Maraichers a Pikine). Dakar-Joal 1 500, Dakar-Kaolack 3 500, Dakar-Saint-Louis ~5 000, Dakar-Ziguinchor ~11 000 FCFA."],
            ['methode' => 'Bus interurbain (Dakar Dem Dikk)', 'approximation_cout' => '2 500 a 12 000 FCFA',
             'description' => "Autocars climatises a horaires fixes. Dakar-Mbour 2 500, Dakar-Touba 4 000, Dakar-Tambacounda 9 000, Dakar-Kedougou 12 000 FCFA."],
            ['methode' => 'Chaloupe Dakar-Goree', 'approximation_cout' => '1 500 a 6 000 FCFA',
             'description' => "Liaison maritime. Non-resident adulte 5 200 a 6 000 FCFA, resident avec CIN 1 500 FCFA adulte / 500 FCFA enfant."],
            ['methode' => 'Pirogue traditionnelle', 'approximation_cout' => '1 000 a 2 000 FCFA',
             'description' => "Traversee simple (ex: Dakar vers l'ile de Ngor) en aller-retour par personne. Excursions privees disponibles dans le Sine Saloum et la Somone."],
        ];

        foreach ($transports as $t) {
            Transport::firstOrCreate(['methode' => $t['methode']], $t);
        }

        /*
        |--------------------------------------------------------------------
        | Profils chauffeurs pour chaque utilisateur de role taximan
        |--------------------------------------------------------------------
        */
        // Tous les chauffeurs disposent deja d'un profil explicite cree plus haut.

        // Activites reelles (apres les destinations pour pouvoir les lier)
        $this->call(ActiviteSeeder::class);

        /*
        |--------------------------------------------------------------------
        | Interactions de demonstration entre Awa Diop et Moussa Fall
        |--------------------------------------------------------------------
        */
        $awa    = User::where('email', 'visiteur@guide.test')->first();
        $moussa = User::where('email', 'taximan@guide.test')->first();

        if ($awa && $moussa) {
            // 1 visite realisee dans le compte d'Awa
            $goree = Destination::where('name', 'Ile de Goree')->first();
            if ($goree && ! DB::table('destination_visiteur')->where('user_id', $awa->id)->where('destination_id', $goree->id)->exists()) {
                DB::table('destination_visiteur')->insert([
                    'id'            => (string) Str::uuid(),
                    'user_id'       => $awa->id,
                    'destination_id' => $goree->id,
                    'date_visite'   => now()->subDays(12)->toDateString(),
                    'created_at'    => now(),
                    'updated_at'    => now(),
                ]);
            }

            // Course terminee et notee (Moussa apparait dans "Mes clients" d'Awa, objet perdu disponible)
            $terminee = Course::firstOrCreate(
                ['visiteur_id' => $awa->id, 'chauffeur_id' => $moussa->id, 'depart' => 'Aeroport AIBD', 'destination' => 'Plateau, Dakar'],
                ['prix' => 8000, 'statut' => 'terminee', 'note' => 5, 'commentaire' => 'Chauffeur ponctuel et tres sympathique.']
            );
            $terminee->forceFill(['created_at' => now()->subDays(9), 'updated_at' => now()->subDays(9)])->save();

            // Deuxieme course terminee (sans note) pour etoffer l'historique
            Course::firstOrCreate(
                ['visiteur_id' => $awa->id, 'chauffeur_id' => $moussa->id, 'depart' => 'Plateau', 'destination' => 'Almadies'],
                ['prix' => 3500, 'statut' => 'terminee']
            )->forceFill(['created_at' => now()->subDays(5), 'updated_at' => now()->subDays(5)])->save();

            // Course en cours (le client est dans la voiture)
            Course::firstOrCreate(
                ['visiteur_id' => $awa->id, 'chauffeur_id' => $moussa->id, 'depart' => 'Yoff', 'destination' => 'Marche Soumbedioune'],
                ['prix' => 2500, 'statut' => 'en_course']
            );

            // Nouvelle demande en attente de prix (cote chauffeur)
            Course::firstOrCreate(
                ['visiteur_id' => $awa->id, 'chauffeur_id' => $moussa->id, 'depart' => 'Plateau', 'destination' => 'Lac Rose'],
                ['statut' => 'demandee']
            );

            // Reservation d'activite avec course planifiee confiee a Moussa
            $activite = Activite::first();
            if ($activite) {
                ActiviteReservation::firstOrCreate(
                    ['visiteur_id' => $awa->id, 'activite_id' => $activite->id, 'date_activite' => now()->addDays(3)->toDateString()],
                    ['chauffeur_id' => $moussa->id]
                );

                Course::firstOrCreate(
                    ['visiteur_id' => $awa->id, 'chauffeur_id' => $moussa->id, 'activite_id' => $activite->id],
                    ['depart' => 'Almadies', 'destination' => $activite->lieu ?: $activite->nom, 'prix' => 6000, 'statut' => 'acceptee', 'date_prevue' => now()->addDays(3)->toDateString()]
                );
            }

            // Un signalement pour illustrer l'echange admin / plaignant
            Signalement::firstOrCreate(
                ['course_id' => $terminee->id, 'auteur_id' => $awa->id, 'motif' => 'facturation'],
                ['cible' => 'chauffeur', 'description' => "Le tarif final ne correspondait pas a ce qui etait annonce au depart."]
            );
        }
    }
}
