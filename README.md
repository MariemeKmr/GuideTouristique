# Guide Touristique

Application web de guide touristique avec trois espaces (administrateur, visiteur, chauffeur).

**Laravel 11 · Blade · Tailwind CSS · MySql / MariaDB · Authentification par rôles · Pest**

## Aperçu

L'application permet de découvrir des destinations, de suivre ses visites et d'entrer en contact avec des chauffeurs. Trois rôles cohabitent : l'**administrateur** gère le catalogue (destinations, transports), le **visiteur** explore et marque ses visites, le **chauffeur** publie un profil que les visiteurs peuvent consulter et appeler. Chaque utilisateur est automatiquement dirigé vers l'espace correspondant à son rôle après connexion.

## Fonctionnalités

**Authentification**
- Inscription (visiteur ou chauffeur), connexion, déconnexion
- Rôles administrateur / visiteur / chauffeur et redirection automatique par rôle
- Protection des espaces par un middleware de rôle
- Messages de validation en français

**Espace administrateur**
- CRUD complet des destinations (nom, localité, rue, description)
- CRUD complet des transports (méthode, coût indicatif, description)
- Tableau de bord avec compteurs (destinations, transports, chauffeurs, visiteurs)

**Espace visiteur**
- Exploration des destinations et fiche détaillée
- Marquage des visites avec date, historique « Mes visites »
- Consultation des moyens de transport
- Annuaire des chauffeurs avec contact via la plateforme (notification au chauffeur)
- Réservation d'une course, suivi du statut et notation du chauffeur

**Espace chauffeur**
- Édition du profil public : zone, véhicule, tarif indicatif, disponibilité, présentation
- Récapitulatif sur le tableau de bord, badge Disponible / Indisponible côté visiteur
- Gestion des courses : accepter, faire évoluer le statut (en route, arrivé, en course, terminée)
- Demandes de contact reçues et note moyenne calculée à partir des courses

## Stack technique

| Domaine | Choix |
|---------|-------|
| Framework | Laravel 11 |
| Vues | Blade |
| Style | Tailwind CSS (via CDN, sans build) |
| Base de données | MySQL / MariaDB |
| Identifiants | UUID |
| Tests | Pest |

## Prérequis

- PHP 8.2 ou plus récent
- Composer
- MySQL / MariaDB (XAMPP convient)

## Installation

```bash
git clone https://github.com/MariemeKmr/GuideTouristique.git
cd GuideTouristique
composer install
```

> Sous Windows, placez le projet hors d'un dossier synchronisé (OneDrive) pour éviter que l'antivirus ne verrouille les fichiers pendant l'installation de Composer.

## Configuration

Créez le fichier `.env` à partir de l'exemple, puis générez la clé d'application :

```bash
cp .env.example .env
php artisan key:generate
```

## Lancement

```bash
php artisan migrate --seed
php artisan serve
```

L'application est accessible sur l'adresse affichée dans le terminal (par défaut http://127.0.0.1:8000).

### Comptes de démonstration

Données fictives de test, créées par le seeder. Le mot de passe est `password` pour tous les comptes.

| Rôle | Nom | Email |
|------|-----|-------|
| Administrateur | Admin Principal | admin@guide.test |
| Visiteur | Awa Diop | visiteur@guide.test |
| Chauffeur | Moussa Fall | taximan@guide.test |

Le seeder génère aussi d'autres comptes fictifs (visiteurs, chauffeurs, administrateurs) avec des adresses du type `nom@example.com`, accessibles eux aussi avec le mot de passe `password`. Toutes ces données sont fictives et destinées uniquement aux tests.

## Tests

Les tests de fonctionnalités tournent sur une base SQLite en mémoire (aucune configuration supplémentaire).

```bash
php artisan test
```

Couverture : authentification, redirection par rôle et contrôle d'accès, CRUD administrateur, marquage des visites, profil chauffeur.

## Structure du projet

```
GuideTouristique/
├── app/
│   ├── Http/
│   │   ├── Controllers/
│   │   │   ├── Auth/AuthController.php          Connexion, inscription, déconnexion
│   │   │   ├── Admin/                           CRUD destinations, transports, activités
│   │   │   ├── Visitor/                         Destinations, visites, chauffeurs, courses
│   │   │   ├── Taximan/                         Profil chauffeur, courses
│   │   │   └── DashboardController.php          Aiguillage et statistiques par rôle
│   │   ├── Middleware/RoleMiddleware.php        Contrôle d'accès par rôle
│   │   └── Requests/                            Validation des formulaires
│   └── Models/                                  User, Destination, Transport, Activite, ChauffeurProfile, ContactRequest, Course
├── database/
│   ├── factories/                               Données de démonstration
│   ├── migrations/
│   └── seeders/DatabaseSeeder.php
├── resources/views/
│   ├── partials/                                navbar, footer, flash, pagination
│   ├── layouts/                                 app, guest
│   ├── auth/                                     login, register
│   ├── admin/                                    destinations, transports, activités
│   ├── visitor/                                  destinations, visites, transports, chauffeurs, courses
│   ├── taximan/                                  profil, courses
│   ├── dashboards/                               admin, visitor, taximan
│   └── welcome.blade.php
├── routes/web.php
└── tests/Feature/                               Tests Pest
```

## Limites connues

Tailwind est chargé via CDN pour fonctionner sans build npm, ce qui est idéal en développement. Pour la production, il est recommandé de compiler Tailwind avec Vite (déjà présent dans le projet) afin de réduire le poids des pages.

## Auteur

Marième KAMARA

## Licence

Projet personnel à but pédagogique et de démonstration.

© 2026 Guide Touristique - Marième KAMARA. Ce projet enrichit mon portfolio.
