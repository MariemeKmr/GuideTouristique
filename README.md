# Guide Touristique

Application web de guide touristique et de mise en relation avec des chauffeurs, pour le Senegal. Trois espaces cohabitent : administrateur, visiteur et chauffeur.

**Laravel 11 - Blade - Tailwind CSS - MySQL / MariaDB (compatible PostgreSQL) - Authentification par roles - Pest - Docker**

## Apercu

Le visiteur explore des destinations, suit ses visites, parcourt les activites et l'annuaire des chauffeurs, puis commande une course en negociant le prix. Le chauffeur publie un profil, recoit les demandes, propose un prix et fait evoluer la course jusqu'a la fin. L'administrateur gere le catalogue (destinations, transports, activites) et traite les signalements. Chaque utilisateur est dirige automatiquement vers l'espace correspondant a son role apres connexion.

## Fonctionnalites

**Authentification et securite**
- Inscription (visiteur ou chauffeur), connexion, deconnexion
- Roles administrateur / visiteur / chauffeur, redirection automatique par role
- Protection des espaces par un middleware de role (un acces refuse renvoie l'utilisateur vers son propre espace)
- Limitation des tentatives de connexion (5 essais par minute et par email/IP)
- Mot de passe oublie et reinitialisation par lien email (message neutre anti-enumeration)
- Messages de validation en francais

**Espace administrateur**
- CRUD complet des destinations, transports et activites
- Tableau de bord avec compteurs (destinations, transports, chauffeurs, visiteurs)
- Signalements : reception, badge Nouveau / Traite, consultation des preuves jointes
- Conversation avec le plaignant et cloture du signalement (lecture seule une fois traite)

**Espace visiteur**
- Exploration des destinations et fiche detaillee
- Marquage des visites avec date et historique "Mes visites"
- Consultation des transports et des activites (filtre par categorie)
- Reservation d'une activite et course planifiee associee
- Annuaire des chauffeurs
- Recherche sur les destinations, les activites et les chauffeurs
- Commande d'une course avec negociation du prix : proposition, acceptation, contre-proposition, refus
- Suivi du statut de la course, confirmation de montee a bord, notation du chauffeur
- Objet perdu : fil de discussion par chauffeur, disponible une fois la course terminee
- Signalement d'un probleme (course en cours ou terminee) avec piece jointe de preuve, et echange avec l'administration ("Mes signalements")

**Espace chauffeur**
- Edition du profil public : zone, vehicule, tarif indicatif, disponibilite, presentation
- Tableau de bord, badge Disponible / Indisponible cote visiteur, note moyenne
- Gestion des courses : proposer un prix, accepter ou refuser une contre-proposition, faire evoluer le statut (en route, arrive, en course, terminee)
- "Mes clients" : recapitulatif des clients, acces a l'objet perdu pour les courses terminees
- Signalement d'un passager et echange avec l'administration

**Interface**
- Barre de navigation responsive avec menu mobile
- Sous-onglets pour alleger la navigation : "Decouvrir" (destinations, transports, activites) cote visiteur, "Mes courses / Activites" cote chauffeur
- Badges de notification (demandes, prix proposes, messages non lus, signalements)

## Stack technique

| Domaine | Choix |
|---------|-------|
| Framework | Laravel 11 |
| Vues | Blade |
| Style | Tailwind CSS (via CDN, sans build) |
| Base de donnees | MySQL / MariaDB (PostgreSQL compatible) |
| Identifiants | UUID |
| Tests | Pest |
| Conteneurisation | Docker |
| Hebergement cible | Render |

## Prerequis

- PHP 8.2 ou plus recent (avec l'extension pdo_sqlite pour lancer les tests)
- Composer
- MySQL / MariaDB (XAMPP convient)

## Installation

```bash
git clone https://github.com/MariemeKmr/GuideTouristique.git
cd GuideTouristique
composer install
```

> Sous Windows, placez le projet hors d'un dossier synchronise (OneDrive) pour eviter que l'antivirus ne verrouille les fichiers pendant l'installation de Composer.

## Configuration

```bash
cp .env.example .env
php artisan key:generate
```

Renseignez la connexion a la base dans le `.env`. Pour que les liens de reinitialisation de mot de passe soient corrects, `APP_URL` doit correspondre a l'adresse reelle (par defaut http://localhost:8000). En local, `MAIL_MAILER=log` ecrit l'email de reinitialisation dans `storage/logs/laravel.log`.

## Lancement

```bash
php artisan migrate --seed
php artisan storage:link
php artisan serve
```

`storage:link` est necessaire une seule fois pour rendre visibles les preuves de signalement televersees. L'application est accessible sur l'adresse affichee dans le terminal (par defaut http://127.0.0.1:8000).

### Comptes de demonstration

Donnees fictives de test, creees par le seeder. Le mot de passe est `password` pour tous les comptes.

| Role | Nom | Email |
|------|-----|-------|
| Administrateur | Admin Principal | admin@guide.test |
| Visiteur | Awa Diop | visiteur@guide.test |
| Chauffeur | Moussa Fall | taximan@guide.test |

Le seeder cree aussi des interactions de demonstration entre Awa Diop et Moussa Fall (courses dont une terminee et notee, reservation d'activite, signalement), ainsi que d'autres comptes fictifs avec des adresses du type `nom@example.com`, accessibles avec le mot de passe `password`. Toutes ces donnees sont fictives et destinees uniquement aux tests.

## Tests

Les tests de fonctionnalites tournent sur une base SQLite en memoire (aucune configuration supplementaire, l'extension pdo_sqlite doit etre activee).

```bash
php artisan test
```

Couverture : authentification, limitation des tentatives, mot de passe oublie, redirection et controle d'acces par role, cycle complet d'une course (de la demande a la notation, avec contre-proposition et controles d'autorisation), recherche, CRUD administrateur, marquage des visites, profil chauffeur.

## Deploiement

Le projet est conteneurise et pret pour Render.

- `Dockerfile` : image PHP 8.2 + Apache, extensions MySQL et PostgreSQL, sans build d'assets (Tailwind via CDN).
- `docker/entrypoint.sh` : ecoute le port fourni par l'hote, lance les migrations, le seed optionnel et la mise en cache de production.
- `render.yaml` : cree le service web et une base PostgreSQL, et cable les variables d'environnement.

Etapes : pousser sur GitHub, creer un Blueprint sur Render, renseigner `APP_KEY` (via `php artisan key:generate --show`) et `APP_URL`, puis deployer. Passer `SEED_ON_DEPLOY` a `false` apres le premier deploiement.

## Structure du projet

```
GuideTouristique/
├── app/
│   ├── Http/
│   │   ├── Controllers/
│   │   │   ├── Auth/AuthController.php          Connexion, inscription, mot de passe oublie
│   │   │   ├── Admin/                           CRUD destinations, transports, activites, signalements
│   │   │   ├── Visitor/                         Destinations, visites, activites, chauffeurs, recherche, courses
│   │   │   ├── Taximan/                         Profil, courses, clients
│   │   │   ├── SignalementController.php        Signalement et conversation admin / plaignant
│   │   │   ├── ObjetPerduController.php         Fil objet perdu par couple chauffeur / client
│   │   │   └── DashboardController.php          Aiguillage et statistiques par role
│   │   ├── Middleware/RoleMiddleware.php        Controle d'acces par role
│   │   └── Requests/                            Validation des formulaires
│   └── Models/                                  User, Destination, Transport, Activite, ActiviteReservation,
│                                                ChauffeurProfile, Course, Signalement, SignalementMessage,
│                                                ObjetThread, ObjetMessage
├── database/
│   ├── factories/
│   ├── migrations/
│   └── seeders/DatabaseSeeder.php
├── resources/views/
│   ├── partials/                                navbar, footer, flash, onglets, barre de recherche, pagination
│   ├── layouts/                                 app, guest
│   ├── auth/                                    login, register, forgot-password, reset-password
│   ├── admin/                                   destinations, transports, activites, signalements
│   ├── visitor/                                 destinations, visites, transports, activites, chauffeurs, courses
│   ├── taximan/                                 profil, courses, clients
│   ├── signalements/                            creation, conversation, mes signalements
│   ├── objets/                                  fil objet perdu
│   └── dashboards/                              admin, visitor, taximan
├── routes/web.php
├── tests/Feature/                               Tests Pest
├── Dockerfile
├── render.yaml
└── docker/entrypoint.sh
```

## Limites connues

- Sur un hebergement gratuit au systeme de fichiers ephemere, les preuves televersees ne persistent pas entre deux deploiements (ajouter un disque persistant ou un stockage S3 pour les conserver).
- Tailwind est charge via CDN pour fonctionner sans build npm, ideal en developpement. Pour la production, il reste possible de compiler Tailwind avec Vite afin de reduire le poids des pages.

## Auteur

Marieme KAMARA

## Licence

Projet personnel a but pedagogique et de demonstration.

© 2026 Guide Touristique - Marieme KAMARA. Ce projet enrichit mon portfolio.
