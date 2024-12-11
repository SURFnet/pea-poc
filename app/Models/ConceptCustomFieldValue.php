<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ConceptCustomFieldValue extends Model
{
    protected $fillable = [
        'value_en',
        'value_nl',
    ];

    public function customField(): BelongsTo
    {
        return $this->belongsTo(CustomField::class);
    }
}
