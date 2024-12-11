<?php

declare(strict_types=1);

namespace App\Models;

use App\Models\Scopes\DefaultSortScope;
use App\Traits\Models\SearchesLocalizedFields;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CustomField extends Model
{
    use HasFactory, SearchesLocalizedFields;

    protected $fillable = [
        'title_en',
        'title_nl',
        'sortkey',
        'tab_type',
    ];

    protected static function booted(): void
    {
        static::addGlobalScope(new DefaultSortScope('sortkey'));
    }

    public function institute(): BelongsTo
    {
        return $this->belongsTo(Institute::class);
    }

    public function scopeForInstitute(Builder $query, Institute $institute): Builder
    {
        return $query->where('institute_id', $institute->id);
    }
}
