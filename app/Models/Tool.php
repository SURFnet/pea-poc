<?php

declare(strict_types=1);

namespace App\Models;

use App\Casts\ArrayNotNull;
use App\Enums\InstituteTool\Status as InstituteToolStatus;
use App\Enums\Tool\Status;
use App\Enums\Tool\StoredData;
use App\Helpers\Format;
use Exception;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Collection;
use Spatie\Activitylog\Traits\LogsActivity;

class Tool extends Model
{
    use HasFactory, LogsActivity;

    /** @var array */
    protected $fillable = [
        'name',
        'description_short',
        'description_long_1',
        'description_long_2',
        'info_url',
        'supported_standards',
        'additional_standards',
        'authentication_methods',
        'stored_data',
        'other_stored_data',
        'european_data_storage',
        'surf_standards_framework_agreed',
        'has_processing_agreement',
    ];

    /** @var array */
    protected $casts = [
        'supported_standards'             => ArrayNotNull::class,
        'authentication_methods'          => ArrayNotNull::class,
        'stored_data'                     => ArrayNotNull::class,
        'european_data_storage'           => 'boolean',
        'surf_standards_framework_agreed' => 'boolean',
        'has_processing_agreement'        => 'boolean',
        'published_at'                    => 'datetime',
    ];

    protected static array $logAttributes = ['*'];

    protected static array $logAttributesToIgnore = [
        'updated_at',
    ];

    protected static bool $logOnlyDirty = true;

    protected static bool $submitEmptyLogs = false;

    public static string $disk = 'tools';

    public static array $images = [
        'image_filename',
        'description_1_image_filename',
        'description_2_image_filename',
    ];

    public static array $searchFields = [
        'name',
        'description_short',
        'description_long_1',
        'description_long_2',
    ];

    public function features(): BelongsToMany
    {
        return $this->belongsToMany(Feature::class);
    }

    public function categories(): BelongsToMany
    {
        return $this->belongsToMany(Category::class, 'tool_categories')->withTimestamps();
    }

    public function experiences(): HasMany
    {
        return $this->hasMany(Experience::class);
    }

    public function institutes(): BelongsToMany
    {
        return $this
            ->belongsToMany(Institute::class)
            ->withTimestamps()
            ->using(InstituteTool::class)
            ->withPivot([
                'alternative_tool_id',
                'description_1',
                'description_1_image_filename',
                'description_2',
                'description_2_image_filename',
                'extra_information_title',
                'extra_information',
                'support_title_1',
                'support_email_1',
                'support_title_2',
                'support_email_2',
                'manual_title_1',
                'manual_url_1',
                'manual_title_2',
                'manual_url_2',
                'video_title_1',
                'video_url_1',
                'video_title_2',
                'video_url_2',
                'status',
                'why_unfit',
                'published_at',
            ]);
    }

    public function institutesThatUseTool(): BelongsToMany
    {
        return $this->institutes()->wherePivotIn('status', [
            InstituteToolStatus::RECOMMENDED,
            InstituteToolStatus::SUPPORTED,
            InstituteToolStatus::FREE_TO_USE,
        ])->wherePivotNotNull('published_at');
    }

    public static function forOtherThan(Institute $institute): Builder
    {
        return self::forStatus(Status::PUBLISHED)->exceptForInstitute($institute);
    }

    public static function forOur(Institute $institute): BelongsToMany | Builder
    {
        return $institute->publishedToolsWithCategories()->forStatus(Status::PUBLISHED);
    }

    public function scopeSearch(Builder $query, string $searchTerm): void
    {
        (new Collection(explode(' ', $searchTerm)))
            ->filter()
            ->each(fn ($word) => $query->searchForWord($word));
    }

    public function scopeSearchForWord(Builder $query, string $word): void
    {
        $query->where(function ($query) use ($word): void {
            $word = '%' . $word . '%';

            foreach (self::$searchFields as $searchField) {
                $query->orWhere($searchField, 'like', $word);
            }
        });
    }

    public function scopeForFeatures(Builder $query, array $features): void
    {
        $query->whereHas('features', function ($query) use ($features): void {
            $query->whereIn('features.id', $features);
        });
    }

    public function scopeForCategories(Builder $query, array $categories): void
    {
        $query->whereHas('categories', function ($query) use ($categories): void {
            $query->whereIn('categories.id', $categories);
        });
    }

    public function scopeForCategory(Builder $query, int $category): void
    {
        $query->forCategories([$category]);
    }

    public function scopeExceptForInstitute(Builder $query, Institute $institute): void
    {
        $query->whereDoesntHave('institutes', function ($query) use ($institute): void {
            $query->where('institutes.id', $institute->id);
            $query->whereNotNull('institute_tool.published_at');
        });
    }

    public function scopeForStatus(Builder $query, string $status): void
    {
        switch ($status) {
            case Status::CONCEPT:
                $query->whereNull('tools.published_at');
                break;
            case Status::PUBLISHED:
                $query->whereNotNull('tools.published_at');
                break;
            default:
                throw new Exception('Status ' . $status . ' not supported');
        }
    }

    public function getRatingAttribute(): float
    {
        $rating = $this->experiences->average('rating') ?? 0;

        return round($rating, 0);
    }

    public function getStatusAttribute(): string
    {
        return $this->published_at ? Status::PUBLISHED : Status::CONCEPT;
    }

    public function getStatusDisplayAttribute(): string
    {
        return Status::asSelect()[$this->status];
    }

    public function getIsPublishedAttribute(): bool
    {
        return !empty($this->published_at);
    }

    public function getDescriptionShortDisplayAttribute(): string
    {
        return Format::stripTagsAndConvertNewlineToHtml($this->description_short);
    }

    public function getDescriptionLong1DisplayAttribute(): string
    {
        return Format::stripTagsAndConvertNewlineToHtml($this->description_long_1);
    }

    public function getDescriptionLong2DisplayAttribute(): string
    {
        return Format::stripTagsAndConvertNewlineToHtml($this->description_long_2);
    }

    public function getSupportedStandardsDisplayAttribute(): string
    {
        $translated = array_map(
            fn ($item) => trans('tool.supported_standards.' . $item),
            $this->supported_standards
        );

        return implode(', ', $translated);
    }

    public function getAuthenticationMethodsDisplayAttribute(): string
    {
        $translated = array_map(
            fn ($item) => trans('tool.authentication_methods.' . $item),
            $this->authentication_methods
        );

        return implode(', ', $translated);
    }

    public function getStoredDataDisplayAttribute(): string
    {
        $storedData = $this->stored_data;
        $otherIndex = array_search(StoredData::OTHER, $storedData);

        // If "Other" has been selected, we remove it from the list in order to replace it
        if ($otherIndex !== false) {
            unset($storedData[$otherIndex]);
        }

        $translated = array_map(
            fn ($item) => trans('tool.stored_data.' . $item),
            $storedData
        );

        if ($otherIndex !== false) {
            $translated[] = $this->other_stored_data;
        }

        return implode(', ', $translated);
    }

    public function getAvailableAlternativeTools(Institute $institute): Collection
    {
        return self::forOur($institute)
            ->whereKeyNot($this->id)
            ->whereDoesntHave('institutes', function ($query) use ($institute): void {
                $query->where('institutes.id', $institute->id);
                $query->where('institute_tool.status', InstituteToolStatus::PROHIBITED);
            })
            ->get();
    }

    public function isPublishedForInstitute(Institute $institute): bool
    {
        return $institute->tools()->find($this)?->pivot->published_at !== null;
    }
}
