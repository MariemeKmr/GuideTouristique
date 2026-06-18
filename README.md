# Guide Touristique

Application web de guide touristique avec trois espaces (administrateur, visiteur, chauffeur), une charte graphique chaleureuse inspirée du sable et du lagon, et un footer signé sur chaque page.

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
- Annuaire des chauffeurs avec contact direct (téléphone, email)

**Espace chauffeur**
- Édition du profil public : zone, véhicule, tarif indicatif, disponibilité, présentation
- Récapitulatif sur le tableau de bord, badge Disponible / Indisponible côté visiteur

## Charte graphique

La palette suit une répartition 60 / 30 / 10 / 10 :

| Part | Couleur | Usage |
|------|---------|-------|
| 50 % | Sable clair (fond + texture grain) | Dominante, lisibilité et sensation de clarté |
| 30 % | Bleu lagon | Navigation et surfaces secondaires, climat de confiance |
| 10 % | Terracotta | Boutons d'action, favoris et notifications uniquement |
| 10 % | Bleu nuit profond | Texte fort et pied de page |

Le pied de page affiche « Guide Touristique » et « Marième KAMARA » sur toutes les pages.

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

Mot de passe commun : `password`.

| Rôle | Email |
|------|-------|
| Admin | admin@guide.test |
| Visiteur | visiteur@guide.test |
| Chauffeur | taximan@guide.test |

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
│   │   │   ├── Admin/DestinationController.php  CRUD destinations
│   │   │   ├── Admin/TransportController.php    CRUD transports
│   │   │   ├── Visitor/VisitorController.php    Destinations, visites, chauffeurs
│   │   │   ├── Taximan/TaximanController.php    Profil chauffeur
│   │   │   └── DashboardController.php          Aiguillage et statistiques par rôle
│   │   ├── Middleware/RoleMiddleware.php        Contrôle d'accès par rôle
│   │   └── Requests/                            Validation (Destination, Transport)
│   └── Models/                                  User, Destination, Transport, ChauffeurProfile
├── database/
│   ├── factories/                               Données de démonstration
│   ├── migrations/
│   └── seeders/DatabaseSeeder.php
├── resources/views/
│   ├── partials/                                head-assets (thème + grain), navbar, footer, flash
│   ├── layouts/                                 app, guest
│   ├── auth/                                     login, register
│   ├── admin/                                    destinations, transports
│   ├── visitor/                                  destinations, visits, transports, drivers
│   ├── taximan/profile.blade.php
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

© 2026 Guide Touristique - Marième KAMARA. 
