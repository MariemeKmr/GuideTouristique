<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, HasUuids, Notifiable;

    /**
     * Rôles disponibles dans l'application.
     */
    public const ROLE_ADMIN    = 'admin';
    public const ROLE_VISITEUR = 'visiteur';
    public const ROLE_TAXIMAN  = 'taximan';

    /**
     * Les attributs assignables en masse.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'first_name',
        'last_name',
        'phone',
        'email',
        'role',
        'password',
    ];

    /**
     * Les attributs cachés lors de la sérialisation.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Le cast des attributs.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password'          => 'hashed',
        ];
    }

    /*
    |--------------------------------------------------------------------------
    | Accesseurs
    |--------------------------------------------------------------------------
    */

    /**
     * Nom complet de l'utilisateur (prénom + nom).
     */
    public function getFullNameAttribute(): string
    {
        return trim("{$this->first_name} {$this->last_name}");
    }

    /*
    |--------------------------------------------------------------------------
    | Helpers de rôle
    |--------------------------------------------------------------------------
    */

    public function isAdmin(): bool
    {
        return $this->role === self::ROLE_ADMIN;
    }

    public function isVisiteur(): bool
    {
        return $this->role === self::ROLE_VISITEUR;
    }

    public function isTaximan(): bool
    {
        return $this->role === self::ROLE_TAXIMAN;
    }

    /**
     * Route du tableau de bord correspondant au rôle de l'utilisateur.
     */
    public function dashboardRoute(): string
    {
        return match ($this->role) {
            self::ROLE_ADMIN   => 'admin.dashboard',
            self::ROLE_TAXIMAN => 'taximan.dashboard',
            default            => 'visitor.dashboard',
        };
    }

    /*
    |--------------------------------------------------------------------------
    | Relations
    |--------------------------------------------------------------------------
    */

    /**
     * Destinations visitées (ou enregistrées) par le visiteur.
     */
    public function destinations(): BelongsToMany
    {
        return $this->belongsToMany(Destination::class, 'destination_visiteur')
            ->withPivot('date_visite')
            ->withTimestamps();
    }

    /**
     * Transports associés au visiteur.
     */
    public function transports(): BelongsToMany
    {
        return $this->belongsToMany(Transport::class, 'transport_visiteur')
            ->withTimestamps();
    }

    /**
     * Profil de chauffeur (uniquement pour les utilisateurs de rôle 'taximan').
     */
    public function chauffeurProfile(): HasOne
    {
        return $this->hasOne(ChauffeurProfile::class);
    }

    /** Demandes de contact envoyees par le visiteur. */
    public function demandesEnvoyees(): HasMany
    {
        return $this->hasMany(ContactRequest::class, 'visiteur_id');
    }

    /** Demandes de contact recues par le chauffeur. */
    public function demandesRecues(): HasMany
    {
        return $this->hasMany(ContactRequest::class, 'chauffeur_id');
    }

    /** Courses demandees par le visiteur. */
    public function coursesVisiteur(): HasMany
    {
        return $this->hasMany(Course::class, 'visiteur_id');
    }

    /** Courses assurees par le chauffeur. */
    public function coursesChauffeur(): HasMany
    {
        return $this->hasMany(Course::class, 'chauffeur_id');
    }

    /** Note moyenne du chauffeur (1 a 5), ou null si aucune note. */
    public function noteMoyenne(): ?float
    {
        $moyenne = $this->coursesChauffeur()->whereNotNull('note')->avg('note');

        return $moyenne ? round((float) $moyenne, 1) : null;
    }

    /** Nombre d'avis recus par le chauffeur. */
    public function nombreAvis(): int
    {
        return $this->coursesChauffeur()->whereNotNull('note')->count();
    }
}
