<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ObjetThread extends Model
{
    use HasUuids;

    protected $fillable = [
        'chauffeur_id',
        'visiteur_id',
        'rendu',
    ];

    protected function casts(): array
    {
        return ['rendu' => 'boolean'];
    }

    public function messages(): HasMany
    {
        return $this->hasMany(ObjetMessage::class, 'thread_id');
    }

    public function chauffeur(): BelongsTo
    {
        return $this->belongsTo(User::class, 'chauffeur_id');
    }

    public function visiteur(): BelongsTo
    {
        return $this->belongsTo(User::class, 'visiteur_id');
    }
}
