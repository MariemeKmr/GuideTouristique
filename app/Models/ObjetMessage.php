<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ObjetMessage extends Model
{
    use HasUuids;

    protected $fillable = [
        'course_id',
        'expediteur_id',
        'contenu',
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

    public function expediteur(): BelongsTo
    {
        return $this->belongsTo(User::class, 'expediteur_id');
    }
}
