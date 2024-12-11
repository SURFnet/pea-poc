<?php

declare(strict_types=1);

namespace App\Models;

use App\Actions\Institute\Tool\Concept\CreateAction as CreateConceptAction;
use App\Enums\InstituteTool\Status;
use App\Enums\Tags\TagTypes;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Support\Collection;
use Spatie\Tags\HasTags;

class InstituteTool extends Model
{
    use HasFactory, HasTags;

    /** @var bool */
    public $incrementing = true;

    protected $table = 'institute_tool';

    /** @var array<int, string> */
    protected $fillable = [
        'alternative_tool_id',

        'status',
        'conditions_en',
        'conditions_nl',

        'links_with_other_tools_en',
        'links_with_other_tools_nl',
        'sla_url',

        'privacy_contact',
        'privacy_evaluation_url',
        'security_evaluation_url',
        'data_classification',

        'how_to_login_en',
        'how_to_login_nl',
        'availability_en',
        'availability_nl',
        'licensing_en',
        'licensing_nl',
        'request_access_en',
        'request_access_nl',
        'instructions_en',
        'instructions_nl',
        'instructions_manual_1_url',
        'instructions_manual_2_url',
        'instructions_manual_3_url',

        'faq_en',
        'faq_nl',
        'examples_of_usage_en',
        'examples_of_usage_nl',
        'additional_info_heading_en',
        'additional_info_heading_nl',
        'additional_info_text_en',
        'additional_info_text_nl',

        'why_unfit_en',
        'why_unfit_nl',

        'published_at',
    ];

    /** @var array<string, string> */
    protected $casts = [
        'published_at' => 'datetime',
    ];

    public static function getSearchFields(): array
    {
        return [
            'privacy_contact',
            ...self::getLocalizedFields(),
        ];
    }

    public static function getLocalizedFields(): array
    {
        return [
            'conditions',
            'links_with_other_tools',
            'how_to_login',
            'availability',
            'licensing',
            'instructions',
            'faq',
            'examples_of_usage',
            'additional_info_text',
            'why_unfit',
        ];
    }

    public function categories(): Collection
    {
        return $this->tagsWithType(TagTypes::CATEGORIES);
    }

    public function institute(): BelongsTo
    {
        return $this->belongsTo(Institute::class);
    }

    public function tool(): BelongsTo
    {
        return $this->belongsTo(Tool::class);
    }

    public function concept(): HasOne
    {
        return $this->hasOne(ConceptInstituteTool::class, 'institute_tool_id');
    }

    public function alternativeTool(): BelongsTo
    {
        return $this->belongsTo(Tool::class, 'alternative_tool_id');
    }

    public function customFields(): BelongsToMany
    {
        return $this
            ->belongsToMany(CustomField::class, 'custom_field_values')
            ->withTimestamps()
            ->withPivot([
                'value_en',
                'value_nl',
            ]);
    }

    public function scopeForTool(Builder $query, Tool $tool): Builder
    {
        return $query->where('tool_id', $tool->id);
    }

    public function scopeForInstitute(Builder $query, Institute $institute): Builder
    {
        return $query->where('institute_id', $institute->id);
    }

    public function getIsPublishedAttribute(): bool
    {
        return !empty($this->published_at);
    }

    public function getStatusDisplayAttribute(): string
    {
        if (!$this->is_published) {
            return Status::UNPUBLISHED;
        }

        if ($this->status === null) {
            return Status::UNRATED;
        }

        return $this->status;
    }

    public function getOrCreateConceptVersion(): ConceptInstituteTool
    {
        $concept = $this->concept;
        if ($concept === null) {
            (new CreateConceptAction())->execute($this->tool, $this->institute);

            $concept = $this->refresh()->concept;
        }

        return $concept;
    }

    public function alternativeTools(): BelongsToMany
    {
        return $this->belongsToMany(
            Tool::class,
            'alternative_tool_institute_tools',
        );
    }

    public function prohibitedAlternativeTools(): BelongsToMany
    {
        return $this->alternativeTools()->whereHas('instituteTools', function (Builder $builder): Builder {
            return $builder->where('status', '=', Status::DISALLOWED);
        });
    }

    public function allowedAlternativeTools(): BelongsToMany
    {
        return $this->alternativeTools()->whereHas('instituteTools', function (Builder $builder): Builder {
            return $builder->whereIn('status', [Status::ALLOWED, Status::ALLOWED_UNDER_CONDITIONS]);
        });
    }
}
