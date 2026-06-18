<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Transport extends Model
{
    use HasFactory, HasUuids;

    protected $fillable = [
        'methode',
        'approximation_cout',
        'description',
    ];

    /**
     * Visiteurs associés à ce moyen de transport.
     */
    public function visiteurs(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'transport_visiteur')
            ->withTimestamps();
    }
}
