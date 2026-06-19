<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ActiviteReservation extends Model
{
    use HasUuids;

    protected $fillable = [
        'visiteur_id',
        'activite_id',
        'date_activite',
        'chauffeur_id',
    ];

    protected function casts(): array
    {
        return ['date_activite' => 'date'];
    }

    public function visiteur(): BelongsTo
    {
        return $this->belongsTo(User::class, 'visiteur_id');
    }

    public function activite(): BelongsTo
    {
        return $this->belongsTo(Activite::class);
    }

    public function chauffeur(): BelongsTo
    {
        return $this->belongsTo(User::class, 'chauffeur_id');
    }
}
