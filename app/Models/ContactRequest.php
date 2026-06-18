<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ContactRequest extends Model
{
    use HasUuids;

    protected $fillable = [
        'visiteur_id',
        'chauffeur_id',
        'lu',
    ];

    protected function casts(): array
    {
        return ['lu' => 'boolean'];
    }

    public function visiteur(): BelongsTo
    {
        return $this->belongsTo(User::class, 'visiteur_id');
    }

    public function chauffeur(): BelongsTo
    {
        return $this->belongsTo(User::class, 'chauffeur_id');
    }
}
