<?php

declare(strict_types=1);

namespace App\Models;

use Database\Factories\TagFactory;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Spatie\Tags\Tag as OriginalTagModel;

class Tag extends OriginalTagModel
{
    public array $translatable = ['name', 'slug', 'description'];

    protected static function newFactory(): TagFactory
    {
        return TagFactory::new();
    }

    public function institute(): BelongsTo
    {
        return $this->belongsTo(Institute::class);
    }

    public function scopeWithoutInstitute(Builder $query): void
    {
        $query->whereNull('institute_id');
    }

    public function scopeForInstitute(Builder $query, Institute $institute): void
    {
        $query->where('institute_id', $institute->id);
    }

    public function scopeAccessibleForInstitute(Builder $query, Institute $institute): void
    {
        $query->where(function (Builder $query) use ($institute): void {
            $query->withoutInstitute();
            $query->orWhere(fn (Builder $query) => $query->forInstitute($institute));
        });
    }

    public function scopeWhereSlug(Builder $query, string $slug, string $locale): void
    {
        $query->where("slug->{$locale}", $slug);
    }

    public function scopeWhereSlugIn(Builder $query, array $slugs, string $locale): void
    {
        $query->whereIn("slug->{$locale}", $slugs);
    }
}
