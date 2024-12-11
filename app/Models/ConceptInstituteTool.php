<?php

declare(strict_types=1);

namespace App\Models;

use App\Enums\InstituteTool\Status;
use App\Enums\Tags\TagTypes;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Support\Collection;
use Spatie\Tags\HasTags;

class ConceptInstituteTool extends Model
{
    use HasTags;

    /** @var array<int, string> */
    protected $fillable = [
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
    ];

    public static string $disk = 'tools';

    public function categories(): Collection
    {
        return $this->tagsWithType(TagTypes::CATEGORIES);
    }

    public function originalVersion(): BelongsTo
    {
        return $this->belongsTo(InstituteTool::class, 'institute_tool_id');
    }

    public function customFields(): BelongsToMany
    {
        return $this
            ->belongsToMany(CustomField::class, 'concept_custom_field_values')
            ->withTimestamps()
            ->withPivot([
                'value_en',
                'value_nl',
            ]);
    }

    public function getAllCustomFields(): Collection
    {
        return $this
            ->originalVersion
            ->institute
            ->customFields
            ->keyBy('id')
            ->merge($this->customFields->keyBy('id'));
    }

    public function getIsPublishedAttribute(): bool
    {
        return !empty($this->originalVersion->published_at);
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

    public function alternativeTools(): BelongsToMany
    {
        return $this->belongsToMany(Tool::class, 'alternative_concept_institute_tools');
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
