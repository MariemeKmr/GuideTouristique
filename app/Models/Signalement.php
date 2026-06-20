<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Signalement extends Model
{
    use HasUuids;

    /** Motifs quand le visiteur signale le chauffeur / le trajet. */
    public const MOTIFS_VISITEUR = [
        'securite'     => 'Securite et conduite dangereuse',
        'comportement' => 'Comportement inapproprie',
        'facturation'  => 'Probleme de facturation',
        'vehicule'     => 'Vehicule non conforme',
        'especes'      => "Demande d'argent liquide",
        'autre'        => 'Autre',
    ];

    /** Motifs quand le chauffeur signale le passager. */
    public const MOTIFS_CHAUFFEUR = [
        'agressif'          => 'Comportement agressif ou impoli',
        'degats'            => 'Degats ou salissures dans le vehicule',
        'securite_passager' => 'Non-respect des regles de securite',
        'mauvais_passager'  => 'Mauvais passager pris en charge',
        'ebriete'           => "Passager en etat d'ebriete extreme",
        'autre'             => 'Autre',
    ];

    protected $fillable = [
        'course_id',
        'auteur_id',
        'cible',
        'motif',
        'description',
        'lu',
    ];

    protected function casts(): array
    {
        return ['lu' => 'boolean'];
    }

    public function course(): BelongsTo
    {
        return $this->belongsTo(Course::class);
    }

    public function auteur(): BelongsTo
    {
        return $this->belongsTo(User::class, 'auteur_id');
    }

    public function messages(): HasMany
    {
        return $this->hasMany(SignalementMessage::class, 'signalement_id');
    }

    public function motifLabel(): string
    {
        return (self::MOTIFS_VISITEUR + self::MOTIFS_CHAUFFEUR)[$this->motif] ?? $this->motif;
    }
}
