<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Activite extends Model
{
    use HasFactory, HasUuids;

    /** Categories disponibles : cle => libelle */
    public const CATEGORIES = [
        'nautique'   => 'Nautique',
        'motorisee'  => 'Motorisee',
        'animaliere' => 'Animaliere',
        'nature'     => 'Nature',
        'culturelle' => 'Culturelle',
    ];

    protected $fillable = [
        'nom',
        'categorie',
        'lieu',
        'tarif',
        'description',
        'destination_id',
    ];

    public function destination(): BelongsTo
    {
        return $this->belongsTo(Destination::class);
    }

    /** Libelle lisible de la categorie */
    public function categorieLabel(): string
    {
        return self::CATEGORIES[$this->categorie] ?? $this->categorie;
    }
}
