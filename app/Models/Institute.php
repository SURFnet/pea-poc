<?php

declare(strict_types=1);

namespace App\Models;

use App\Enums\InstituteTool\Status as InstituteToolStatus;
use App\Enums\Tags\TagTypes;
use App\Helpers\Locale;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class Institute extends Model
{
    use HasFactory, LogsActivity;

    /** @var array<int, string> */
    protected $fillable = [
        'full_name_en',
        'full_name_nl',
        'short_name',
        'domain',
        'logo_square_filename',
        'logo_full_filename',
        'banner_filename',
        'homepage_title_en',
        'homepage_body_en',
        'homepage_title_nl',
        'homepage_body_nl',
    ];

    public static string $disk = 'institutes';

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logAll()
            ->logExcept([
                'updated_at',
            ])
            ->logOnlyDirty()
            ->dontSubmitEmptyLogs();
    }

    /** @return Collection<Tag> */
    public function categories(): Collection
    {
        return Tag::whereType(TagTypes::CATEGORIES)->where('institute_id', $this->id)->get();
    }

    public function users(): HasMany
    {
        return $this->hasMany(User::class);
    }

    public function customFields(): HasMany
    {
        return $this->hasMany(CustomField::class);
    }

    public function tools(): BelongsToMany
    {
        return $this
            ->belongsToMany(Tool::class)
            ->whereNotNull('tools.published_at')
            ->withTimestamps()
            ->withPivot([
                'status',
                'published_at',
                'updated_at',
            ]);
    }

    public function scopeUsingTool(Builder $query, Tool $tool): Builder
    {
        $publishedInstituteTools = InstituteTool::where('tool_id', $tool->id)
            ->whereIn('status', [
                InstituteToolStatus::ALLOWED,
                InstituteToolStatus::ALLOWED_UNDER_CONDITIONS,
            ])
            ->whereNotNull('published_at')
            ->pluck('institute_id');

        return $query->whereIn('id', $publishedInstituteTools);
    }

    public function publishedToolsWithCategories(): BelongsToMany
    {
        return $this->tools()->wherePivotNotNull('published_at');
    }

    public function hasTool(Tool $tool): bool
    {
        return $this->tools()->where('tools.id', $tool->id)->exists();
    }

    public function getFullNameAttribute(): string
    {
        return Locale::getLocalizedFieldValue($this, 'full_name');
    }
}
