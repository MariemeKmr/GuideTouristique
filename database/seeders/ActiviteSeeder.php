<?php

namespace Database\Seeders;

use App\Models\Activite;
use App\Models\Destination;
use Illuminate\Database\Seeder;

class ActiviteSeeder extends Seeder
{
    public function run(): void
    {
        // Table de correspondance nom de destination => id (pour lier les activites)
        $dest = Destination::pluck('id', 'name');

        $activites = [
            ['nom' => 'Jet-ski', 'categorie' => 'nautique', 'lieu' => 'Dakar / Saly', 'tarif' => '15 000 a 36 000 FCFA',
             'description' => "Location sur les plages de Dakar (Baie de Hann, Ngor) et de la Petite Cote. Environ 15 000 a 24 000 FCFA pour 15-20 min, 32 000 a 36 000 FCFA pour 30 min."],
            ['nom' => 'Kayak transparent (Ouakam)', 'categorie' => 'nautique', 'lieu' => 'Ouakam', 'tarif' => '5 000 a 7 000 FCFA',
             'description' => "Sur la plage a cote de la Mosquee de la Divinite, l'un des plus beaux spots de Dakar. Balade 30 min a 5 000 FCFA, formule 1h jusqu'a 7 000 FCFA. Gilets fournis, zone surveillee.",
             'dest' => 'Mosquee de la Divinite'],
            ['nom' => 'Kayak transparent (Lac Rose)', 'categorie' => 'nautique', 'lieu' => 'Lac Rose', 'tarif' => '25 000 a 40 000 FCFA',
             'description' => "Naviguer sur le lac en observant le fond, avec vue sur les dunes. Souvent propose en pack journee.",
             'dest' => 'Lac Rose (Lac Retba)'],
            ['nom' => 'Balade en pirogue traditionnelle', 'categorie' => 'nautique', 'lieu' => 'Lac Rose', 'tarif' => '2 000 a 5 000 FCFA',
             'description' => "Petite balade sur le lac en pirogue de bois menee par les villageois.",
             'dest' => 'Lac Rose (Lac Retba)'],
            ['nom' => 'Excursion mangrove (Sine Saloum)', 'categorie' => 'nature', 'lieu' => 'Sine Saloum', 'tarif' => '25 000 a 130 000 FCFA',
             'description' => "Pirogue privee avec piroguier. Circuits partages 25 000 a 40 000 FCFA / personne, ou ~130 000 FCFA la journee pour privatiser une grande pirogue (repas de brousse souvent inclus)."],
            ['nom' => 'Charter de peche / mini-bateau', 'categorie' => 'nautique', 'lieu' => 'Dakar', 'tarif' => '90 000 a 260 000 FCFA / jour',
             'description' => "Petites embarcations pour la peche sportive au large ou des mini-croisieres, selon la taille du bateau et l'inclusion du carburant et du skipper."],
            ['nom' => 'Quad dans les dunes', 'categorie' => 'motorisee', 'lieu' => 'Lac Rose', 'tarif' => '13 000 a 45 000 FCFA',
             'description' => "Activite phare. Petit tour 30-40 min 13 000 a 15 000 FCFA, grand tour 1h 20 000 FCFA, raid aventure 2h et plus 35 000 a 45 000 FCFA.",
             'dest' => 'Lac Rose (Lac Retba)'],
            ['nom' => 'Buggy biplace', 'categorie' => 'motorisee', 'lieu' => 'Lac Rose', 'tarif' => '~30 000 FCFA / h',
             'description' => "Plus stable et puissant que le quad, ideal pour les couples ou les familles.",
             'dest' => 'Lac Rose (Lac Retba)'],
            ['nom' => 'Tour des dunes en 4x4', 'categorie' => 'motorisee', 'lieu' => 'Lac Rose', 'tarif' => '15 000 a 20 000 FCFA',
             'description' => "Confortablement installe pendant qu'un chauffeur franchit les dunes. Prix par vehicule, a partager entre passagers.",
             'dest' => 'Lac Rose (Lac Retba)'],
            ['nom' => 'Balade a dos de dromadaire', 'categorie' => 'animaliere', 'lieu' => 'Lac Rose', 'tarif' => '5 000 a 10 000 FCFA',
             'description' => "Promenade sur les dunes surplombant le lac. 15 min 5 000 FCFA, 30 min 10 000 FCFA.",
             'dest' => 'Lac Rose (Lac Retba)'],
            ['nom' => 'Balade a cheval', 'categorie' => 'animaliere', 'lieu' => 'Lac Rose', 'tarif' => '7 000 a 20 000 FCFA',
             'description' => "Chevauchee le long de l'ocean ou au pas dans les dunes. 30 min 7 000 a 10 000 FCFA, 1h 15 000 a 20 000 FCFA.",
             'dest' => 'Lac Rose (Lac Retba)'],
            ['nom' => 'Safari', 'categorie' => 'animaliere', 'lieu' => 'Reserve de Bandia', 'tarif' => 'Selon formule',
             'description' => "Observation de rhinoceros, girafes, zebres, antilopes et baobabs a moins de 2h de Dakar.",
             'dest' => 'Reserve de Bandia'],
            ['nom' => 'Pack journee Lac Rose', 'categorie' => 'motorisee', 'lieu' => 'Lac Rose', 'tarif' => '~25 000 FCFA / personne',
             'description' => "Forfait tout compris: 1h de quad, 30 min de pirogue sur le lac, une balade a cheval ou a chameau, et un dejeuner traditionnel avec acces piscine.",
             'dest' => 'Lac Rose (Lac Retba)'],
            ['nom' => 'Sejour Oasis du Senegal', 'categorie' => 'nature', 'lieu' => 'Lompoul', 'tarif' => '130 000 a 250 000 FCFA',
             'description' => "Randonnees 4x4, couchers de soleil et balades a dos de dromadaire. Formules 1 a 2 jours avec transfert depuis Dakar et nuit sous tente.",
             'dest' => 'Oasis du Senegal'],
        ];

        foreach ($activites as $a) {
            $destId = isset($a['dest']) ? ($dest[$a['dest']] ?? null) : null;
            unset($a['dest']);
            $a['destination_id'] = $destId;
            Activite::firstOrCreate(['nom' => $a['nom']], $a);
        }
    }
}
