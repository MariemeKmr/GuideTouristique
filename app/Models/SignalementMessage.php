<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SignalementMessage extends Model
{
    use HasUuids;

    protected $fillable = [
        'signalement_id',
        'expediteur_id',
        'contenu',
        'lu',
    ];

    protected function casts(): array
    {
        return ['lu' => 'boolean'];
    }

    public function signalement(): BelongsTo
    {
        return $this->belongsTo(Signalement::class);
    }

    public function expediteur(): BelongsTo
    {
        return $this->belongsTo(User::class, 'expediteur_id');
    }
}
