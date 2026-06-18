<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Destination extends Model
{
    use HasFactory, HasUuids;

    protected $fillable = [
        'name',
        'description',
        'localite',
        'rue',
    ];

    /**
     * Visiteurs ayant visité (ou enregistré) cette destination.
     */
    public function visiteurs(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'destination_visiteur')
            ->withPivot('date_visite')
            ->withTimestamps();
    }
}
