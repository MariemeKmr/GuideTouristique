# Guide Touristique

Application Laravel 11 de guide touristique avec trois rôles : **administrateur**, **visiteur** et **chauffeur (taximan)**.

Ce dépôt contient un **système d'authentification complet** (connexion, inscription, déconnexion, rôles, redirections) ainsi que le **CRUD administrateur des destinations et des transports**.

---

## 1. Prérequis

- PHP **8.2+**
- Composer
- MySQL / MariaDB (XAMPP fonctionne très bien)
- Node.js *(facultatif — voir la note sur Tailwind plus bas)*

---

## 2. Installation

```bash
composer install
cp .env.example .env        # sous Windows : copy .env.example .env
php artisan key:generate
```

Vérifiez la section base de données de votre `.env` (valeurs par défaut prévues pour XAMPP) :

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=guide_touristique
DB_USERNAME=root
DB_PASSWORD=
```

> Créez la base `guide_touristique` dans phpMyAdmin avant de migrer.

```bash
php artisan migrate --seed
php artisan serve
```

L'application est disponible sur **http://127.0.0.1:8000**.

---

## 3. Comptes de démonstration

Créés automatiquement par le seeder. Mot de passe identique pour tous : `password`.

| Rôle      | Email                  | Mot de passe | Redirigé vers        |
|-----------|------------------------|--------------|----------------------|
| Admin     | `admin@guide.test`     | `password`   | `/admin/dashboard`   |
| Visiteur  | `visiteur@guide.test`  | `password`   | `/visitor/dashboard` |
| Chauffeur | `taximan@guide.test`   | `password`   | `/taximan/dashboard` |

---

## 4. Fonctionnement de l'authentification

### Inscription
- Champs : prénom, nom, téléphone (optionnel), email, **type de compte** (visiteur ou chauffeur), mot de passe + confirmation.
- La création d'un compte **administrateur** via le formulaire public est **interdite** (sécurité). Les admins se créent par le seeder ou en base.
- Après inscription, l'utilisateur est connecté et redirigé vers son tableau de bord.

### Connexion
- Email + mot de passe, avec option « Se souvenir de moi ».
- En cas d'échec, message d'erreur en français.
- Après connexion, redirection vers `/dashboard`, qui **aiguille automatiquement** vers le bon tableau de bord selon le rôle.

### Déconnexion
- Bouton dans la barre de navigation (requête `POST` protégée par CSRF).
- Session invalidée et token régénéré.

### Protection par rôle
Un middleware `role` protège chaque tableau de bord :

```php
Route::get('/admin/dashboard', [DashboardController::class, 'admin'])
    ->middleware('role:admin')
    ->name('admin.dashboard');
```

On peut autoriser plusieurs rôles : `->middleware('role:admin,taximan')`.
Un accès non autorisé renvoie une erreur **403**.

---

## 4 bis. Gestion administrateur (CRUD)

Connecté en **admin**, vous accédez via la barre de navigation à deux modules :

- **Destinations** (`/admin/destinations`) : nom, localité, rue, description.
- **Transports** (`/admin/transports`) : méthode, coût approximatif, description.

Chaque module propose la liste paginée, l'ajout, la modification et la suppression (avec confirmation), la validation des champs en français et un message de confirmation après chaque action. Le tableau de bord admin affiche les compteurs (destinations, transports, chauffeurs, visiteurs).

Le seeder insère des **données de démonstration** (12 destinations, 6 transports, contexte sénégalais) pour que les listes ne soient pas vides au premier lancement.

---

## 5. Structure ajoutée / modifiée

```
app/
├── Http/
│   ├── Controllers/
│   │   ├── Auth/AuthController.php      # login / register / logout
│   │   ├── Admin/DestinationController.php # CRUD destinations
│   │   ├── Admin/TransportController.php   # CRUD transports
│   │   └── DashboardController.php      # aiguillage + stats admin
│   ├── Requests/
│   │   ├── DestinationRequest.php       # validation destinations
│   │   └── TransportRequest.php         # validation transports
│   └── Middleware/
│       └── RoleMiddleware.php           # contrôle d'accès par rôle
├── Models/
│   ├── User.php                         # $fillable corrigé + helpers de rôle + relations
│   ├── Destination.php                  # $fillable + relation visiteurs
│   └── Transport.php                    # $fillable + relation visiteurs
bootstrap/app.php                        # alias du middleware 'role'
routes/web.php                           # routes guest / auth + protection par rôle
database/seeders/DatabaseSeeder.php      # comptes de démonstration
lang/fr/validation.php                   # messages de validation en français
resources/views/
├── layouts/{app,guest}.blade.php        # mises en page partagées
├── partials/navbar.blade.php            # barre + déconnexion
├── auth/{login,register}.blade.php      # formulaires fonctionnels
├── admin/destinations/{index,create,edit,_form}.blade.php
├── admin/transports/{index,create,edit,_form}.blade.php
├── dashboards/{admin,visitor,taximan}.blade.php
└── welcome.blade.php                    # page d'accueil sobre
```

---

## 6. Note sur Tailwind

Les vues utilisent **Tailwind via CDN** pour fonctionner **sans build npm** (idéal en développement).

Pour la production, compilez Tailwind avec Vite (déjà présent) :

```bash
npm install
npm run build
```

Remplacez alors la balise CDN par `@vite(['resources/css/app.css', 'resources/js/app.js'])` dans les layouts.

---

## 7. Prochaines étapes prévues

- [x] CRUD des **destinations** (côté admin)
- [x] CRUD des **transports** (côté admin)
- [ ] Côté visiteur : exploration des destinations, marquage des visites
- [ ] Profil public du chauffeur et mise en relation visiteur ↔ chauffeur
