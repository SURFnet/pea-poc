<?php

declare(strict_types=1);

namespace App\Models;

use App\Actions\Tool\Concept\CreateAction as CreateConceptAction;
use App\Enums\InstituteTool\Status as InstituteToolStatus;
use App\Enums\Tags\TagTypes;
use App\Enums\Tool\Status;
use App\Traits\Models\SearchesLocalizedFields;
use Exception;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Query\JoinClause;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Tags\HasTags;

/**
 * @SuppressWarnings(PHPMD.TooManyPublicMethods)
 * @SuppressWarnings(PHPMD.TooManyMethods)
 */
class Tool extends Model
{
    use HasFactory, LogsActivity, SearchesLocalizedFields, HasTags;

    /** @var array<int, string> */
    protected $fillable = [
        'name',
        'supplier',
        'supplier_url',
        'description_short_en',
        'description_short_nl',
        'addons_en',
        'addons_nl',

        'system_requirements_en',
        'system_requirements_nl',

        'supplier_country',
        'personal_data_en',
        'personal_data_nl',
        'privacy_policy_url',
        'model_processor_agreement_url',
        'privacy_analysis',
        'supplier_agrees_with_surf_standards',
        'dtia_by_external_url',
        'dpia_by_external_url',
        'jurisdiction',

        'instructions_manual_1_url_en',
        'instructions_manual_1_url_nl',
        'instructions_manual_2_url_en',
        'instructions_manual_2_url_nl',
        'instructions_manual_3_url_en',
        'instructions_manual_3_url_nl',
        'support_for_teachers_en',
        'support_for_teachers_nl',
        'availability_surf',
        'accessibility_facilities_en',
        'accessibility_facilities_nl',

        'description_long_en',
        'description_long_nl',
        'use_for_education_en',
        'use_for_education_nl',
        'how_does_it_work_en',
        'how_does_it_work_nl',
    ];

    /** @var array<string, string> */
    protected $casts = [
        'published_at' => 'datetime',
    ];

    public static string $disk = 'tools';

    /** @var array<int, string> */
    public static array $images = [
        'logo_filename',
        'image_1_filename',
        'image_2_filename',
    ];

    public static function getSearchFields(): array
    {
        return [
            'name',
            ...self::getLocalizedFields(),
        ];
    }

    public static function getLocalizedFields(): array
    {
        return [
            'addons',
            'system_requirements',
            'instructions_manual_1_url',
            'instructions_manual_2_url',
            'instructions_manual_3_url',
            'support_for_teachers',
            'accessibility_facilities',
            'use_for_education',
            'how_does_it_work',
            'description_short',
            'description_long',
        ];
    }

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

    public function features(): Collection
    {
        return $this->tagsWithType(TagTypes::FEATURES);
    }

    public function softwareType(): Collection
    {
        return $this->tagsWithType(TagTypes::SOFTWARE_TYPES);
    }

    public function devices(): Collection
    {
        return $this->tagsWithType(TagTypes::DEVICES);
    }

    public function standards(): Collection
    {
        return $this->tagsWithType(TagTypes::STANDARDS);
    }

    public function operatingSystem(): Collection
    {
        return $this->tagsWithType(TagTypes::OPERATING_SYSTEMS);
    }

    public function dataProcessingLocation(): Collection
    {
        return $this->tagsWithType(TagTypes::DATA_PROCESSING_LOCATIONS);
    }

    public function certification(): Collection
    {
        return $this->tagsWithType(TagTypes::CERTIFICATIONS);
    }

    public function workingMethods(): Collection
    {
        return $this->tagsWithType(TagTypes::WORKING_METHODS);
    }

    public function targetGroup(): Collection
    {
        return $this->tagsWithType(TagTypes::TARGET_GROUPS);
    }

    public function complexity(): Collection
    {
        return $this->tagsWithType(TagTypes::COMPLEXITY);
    }

    public function experiences(): HasMany
    {
        return $this->hasMany(Experience::class);
    }

    public function concept(): HasOne
    {
        return $this->hasOne(ConceptTool::class);
    }

    public function followers(): BelongsToMany
    {
        return $this
            ->belongsToMany(User::class, 'tool_followers')
            ->withTimestamps();
    }

    public function institutes(): BelongsToMany
    {
        return $this
            ->belongsToMany(Institute::class)
            ->withTimestamps()
            ->withPivot([
                'status',
                'published_at',
            ]);
    }

    public function instituteTools(): HasMany
    {
        return $this->hasMany(InstituteTool::class);
    }

    public static function forOur(Institute $institute): Builder|BelongsToMany
    {
        return $institute->publishedToolsWithCategories()->forStatus(Status::PUBLISHED);
    }

    public function scopeSearch(Builder $query, ?string $searchTerm): void
    {
        if ($searchTerm === null) {
            return;
        }

        $query->where(function (Builder $query) use ($searchTerm): void {
            foreach (self::getSearchFields() as $searchField) {
                if (in_array($searchField, self::getLocalizedFields())) {
                    $query->orWhere(fn ($query) => $query->searchLocalizedField($searchField, $searchTerm, 'tools'));
                } else {
                    $query->orWhere($searchField, 'LIKE', '%' . $searchTerm . '%');
                }
            }

            foreach (InstituteTool::getSearchFields() as $searchField) {
                if (in_array($searchField, InstituteTool::getLocalizedFields())) {
                    $query->orWhere(
                        fn ($query) => $query->searchLocalizedField($searchField, $searchTerm, 'institute_tool')
                    );
                } else {
                    $query->orWhere('institute_tool.' . $searchField, 'LIKE', '%' . $searchTerm . '%');
                }
            }
        });
    }

    public function scopeForCategory(Builder $query, string $categoryId): void
    {
        $query->whereHas('instituteTools', function (Builder $query) use ($categoryId): void {
            $query->whereHas('tags', function (Builder $query) use ($categoryId): void {
                $query->where('id', $categoryId);
            });
        });
    }

    public function scopeForFeature(Builder $query, string $featureTagId): void
    {
        $query->whereHas('tags', function (Builder $query) use ($featureTagId): void {
            $query->where('id', $featureTagId);
        });
    }

    public function scopeForTags(Builder $query, Institute $institute, ?array $tags): void
    {
        if (empty($tags)) {
            return;
        }

        $tags = Tag::whereIn('id', $tags)->get();

        $query->where(function (Builder $query) use ($institute, $tags): void {
            $query->forTagsOfInstituteTools($institute, $tags->whereIn('type', TagTypes::forInstituteTool()));
            $query->forTagsOfTools($tags->whereIn('type', TagTypes::forTool()));
        });
    }

    public function scopeForTagsOfInstituteTools(Builder $query, Institute $institute, Collection $tags): void
    {
        if ($tags->isEmpty()) {
            return;
        }

        $query->whereHas('instituteTools', function (Builder $query) use ($institute, $tags): void {
            $query
                ->where('institute_id', $institute->id)
                ->whereNotNull('published_at')
                ->whereHas('tags', function (Builder $query) use ($tags): void {
                    $query->whereIn('id', $tags->pluck('id'));
                }, '=', $tags->count());
        });
    }

    public function scopeForTagsOfTools(Builder $query, Collection $tags): void
    {
        if ($tags->isEmpty()) {
            return;
        }

        $query->whereHas('tags', function (Builder $query) use ($tags): void {
            $query->whereIn('id', $tags->pluck('id'));
        }, '=', $tags->count());
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

    /** @return Collection<int, Tool> */
    public function getAvailableAlternativeTools(Institute $institute): Collection
    {
        /** @var Collection<int, Tool> $result */
        $result = self::forOur($institute)
            ->whereKeyNot($this->id)
            ->whereDoesntHave('institutes', function ($query) use ($institute): void {
                $query->where('institutes.id', $institute->id);
                $query->where('institute_tool.status', InstituteToolStatus::DISALLOWED);
            })
            ->get();

        return $result;
    }

    public function isPublishedForInstitute(Institute $institute): bool
    {
        return InstituteTool::forTool($this)->forInstitute($institute)->whereNotNull('published_at')->exists();
    }

    public function getCurrentPendingEdit(User $ignoreUser, Institute $forInstitute = null): ?PendingToolEdit
    {
        return PendingToolEdit::forTool($this)
            ->when($forInstitute === null, fn ($query) => $query->missingInstitute())
            ->when($forInstitute !== null, fn ($query) => $query->forInstitute($forInstitute))
            ->notForUser($ignoreUser)
            ->recent()
            ->oldest()
            ->first();
    }

    public function getOrCreateConceptVersion(): ConceptTool
    {
        $concept = $this->concept;
        if ($concept === null) {
            (new CreateConceptAction())->execute($this);

            $concept = $this->refresh()->concept;
        }

        return $concept;
    }

    public static function getToolsQueryForOverview(Institute $institute, array $tagFilter, string $searchTerm): Builder
    {
        $statuses = implode(', ', Arr::map(InstituteToolStatus::customOrder(), fn ($status) => "'$status'"));
        $query = self::select([
            'tools.*',
            'institute_tool.id AS institute_tool_id',
            'institute_tool.status as status_institute',
        ])
        ->leftJoin('institute_tool', function (JoinClause $join) use ($institute): void {
            $join
                ->on('institute_tool.tool_id', '=', 'tools.id')
                ->on('institute_tool.institute_id', '=', DB::raw($institute->id))
                ->whereNotNull('institute_tool.published_at');
        })
        ->whereNotNull('tools.published_at')
        ->orderByRaw("FIELD(IFNULL(institute_tool.status, '-'), $statuses, '-'), ISNULL(institute_tool.id)")
        ->orderBy('name');

        if (!empty($tagFilter)) {
            $query->forTags($institute, $tagFilter);
        }

        if (!empty($searchTerm)) {
            $query->search($searchTerm);
        }

        return $query;
    }

    public static function getTagClassName(): string
    {
        return Tag::class;
    }
}
