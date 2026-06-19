<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Course extends Model
{
    use HasUuids;

    /** Statuts possibles : cle => libelle */
    public const STATUTS = [
        'demandee'  => 'Demandee',
        'prix_propose'   => 'Prix propose',
        'contre_propose' => 'Contre-proposition',
        'acceptee'  => 'Acceptee',
        'en_route'  => 'En route',
        'arrive'    => 'Arrive',
        'attente_client' => 'Attente du client',
        'en_course' => 'En course',
        'terminee'  => 'Terminee',
        'annulee'   => 'Annulee',
    ];

    protected $fillable = [
        'visiteur_id',
        'chauffeur_id',
        'depart',
        'destination',
        'prix',
        'activite_id',
        'date_prevue',
        'statut',
        'annulee_par',
        'alerte_chauffeur',
        'note',
        'commentaire',
    ];

    protected function casts(): array
    {
        return ['note' => 'integer', 'prix' => 'integer', 'date_prevue' => 'date', 'alerte_chauffeur' => 'boolean'];
    }

    public function visiteur(): BelongsTo
    {
        return $this->belongsTo(User::class, 'visiteur_id');
    }

    public function chauffeur(): BelongsTo
    {
        return $this->belongsTo(User::class, 'chauffeur_id');
    }

    public function activite(): BelongsTo
    {
        return $this->belongsTo(Activite::class);
    }

    public function messagesObjet(): HasMany
    {
        return $this->hasMany(ObjetMessage::class, 'course_id');
    }

    public function statutLabel(): string
    {
        return self::STATUTS[$this->statut] ?? $this->statut;
    }

    public function estTerminee(): bool
    {
        return $this->statut === 'terminee';
    }

    public function peutEtreNotee(): bool
    {
        return $this->statut === 'terminee' && is_null($this->note);
    }
}
