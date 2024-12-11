<?php

declare(strict_types=1);

namespace App\Models;

use App\Enums\Tags\TagTypes;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Spatie\Tags\HasTags;

class ConceptTool extends Model
{
    use HasTags;

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

    public function originalVersion(): BelongsTo
    {
        return $this->belongsTo(Tool::class, 'tool_id');
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
}
