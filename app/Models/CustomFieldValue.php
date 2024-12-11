<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CustomFieldValue extends Model
{
    use HasFactory;

    protected $fillable = [
        'value_en',
        'value_nl',
    ];

    public function customField(): BelongsTo
    {
        return $this->belongsTo(CustomField::class);
    }

    public function instituteTool(): BelongsTo
    {
        return $this->belongsTo(InstituteTool::class);
    }
}
