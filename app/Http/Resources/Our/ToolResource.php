<?php

declare(strict_types=1);

// phpcs:disable Generic.Files.LineLength.TooLong

namespace App\Http\Resources\Our;

use App\Enums\InstituteTool\DataClassification;
use App\Enums\InstituteTool\Status;
use App\Enums\Tags\TagTypes;
use App\Helpers\Locale;
use App\Helpers\WYSIWYG;
use App\Http\Resources\InformationManager\CustomFieldValueResource;
use App\Http\Resources\Other\ToolResource as BaseToolResource;
use App\Http\Resources\TagResource;
use App\Models\Experience;
use App\Models\InstituteTool;
use App\Models\Tool;
use App\Traits\Resources\WithImage;

class ToolResource extends BaseToolResource
{
    use WithImage;

    /** @param \Illuminate\Http\Request $request */
    public function toArray($request): array
    {
        return array_merge(parent::toArray($request), [
            ...$this->getTags(),
            'total_experiences' => $this->experiences->count(),

            'features' => TagResource::collection($this->tagsWithType(TagTypes::FEATURES)),

            'institute' => $this->getInstituteData($request),

            'description_short_en' => WYSIWYG::valueForFrontend($this->description_short_en),
            'description_short_nl' => WYSIWYG::valueForFrontend($this->description_short_nl),
            'description_long_en'  => WYSIWYG::valueForFrontend($this->description_long_en),
            'description_long_nl'  => WYSIWYG::valueForFrontend($this->description_long_nl),

            'permissions' => [
                'share_experience'          => $request->user()->can('create', [Experience::class, $this->resource]),
                'see_all_fields'            => $request->user()->can('seeAllFields', $this->resource),
                'submit_request_for_change' => $request->user()->can('submitRequestForChange', $this->resource),
            ],

            'how_does_it_work_nl' => $this->how_does_it_work_nl,
            'how_does_it_work_en' => $this->how_does_it_work_en,

            'addons_nl' => WYSIWYG::valueForFrontend($this->addons_nl),
            'addons_en' => WYSIWYG::valueForFrontend($this->addons_en),

            'accessibility_facilities_nl' => WYSIWYG::valueForFrontend($this->accessibility_facilities_nl),
            'accessibility_facilities_en' => WYSIWYG::valueForFrontend($this->accessibility_facilities_en),

            'support_for_teachers_nl' => WYSIWYG::valueForFrontend($this->support_for_teachers_nl),
            'support_for_teachers_en' => WYSIWYG::valueForFrontend($this->support_for_teachers_en),

            'instructions_manual_1_url_nl' => $this->instructions_manual_1_url_nl,
            'instructions_manual_1_url_en' => $this->instructions_manual_1_url_en,

            'instructions_manual_2_url_nl' => $this->instructions_manual_2_url_nl,
            'instructions_manual_2_url_en' => $this->instructions_manual_2_url_en,

            'instructions_manual_3_url_nl' => $this->instructions_manual_3_url_nl,
            'instructions_manual_3_url_en' => $this->instructions_manual_3_url_en,

            'system_requirements_nl' => $this->system_requirements_nl,
            'system_requirements_en' => $this->system_requirements_en,

            'personal_data_en' => WYSIWYG::valueForFrontend($this->personal_data_en),
            'personal_data_nl' => WYSIWYG::valueForFrontend($this->personal_data_nl),

            'use_for_education_nl' => WYSIWYG::valueForFrontend($this->use_for_education_nl),
            'use_for_education_en' => WYSIWYG::valueForFrontend($this->use_for_education_en),

            'updated_at' => $this->updated_at->toW3cString(),
        ]);
    }

    /** @param \Illuminate\Http\Request $request */
    private function getInstituteData($request): array
    {
        /** @var Tool $tool */
        $tool = $this->resource;
        $institute = $request->user()->institute;

        $instituteTool = InstituteTool::forTool($tool)->forInstitute($institute)->first();
        if ($instituteTool === null) {
            $instituteTool = new InstituteTool();
        }

        $alternativeTools = $instituteTool->allowedAlternativeTools()->select('tools.*')->get();

        $alternativeTool = null;
        if ($alternativeTools->count() > 0) {
            $alternativeTool = AlternativeToolResource::collection($alternativeTools);
        }

        $dataClassificationDisplay = null;
        if ($instituteTool->data_classification) {
            $dataClassificationDisplay = DataClassification::getTranslation($instituteTool->data_classification);
        }

        return [
            'alternative_tools' => $alternativeTool,

            'categories'     => TagResource::collection($instituteTool->categories()),
            'status'         => $instituteTool->status ?? Status::UNRATED,
            'status_display' => Status::getTranslation($instituteTool->status ?? Status::UNRATED),
            'conditions'     => WYSIWYG::valueForFrontend(Locale::getLocalizedFieldValue($instituteTool, 'conditions')),

            'links_with_other_tools' => WYSIWYG::valueForFrontend(
                Locale::getLocalizedFieldValue($instituteTool, 'links_with_other_tools')
            ),
            'sla_url' => $instituteTool->sla_url,

            'privacy_contact'             => $instituteTool->privacy_contact,
            'privacy_evaluation_url'      => $instituteTool->privacy_evaluation_url,
            'security_evaluation_url'     => $instituteTool->security_evaluation_url,
            'data_classification_display' => $dataClassificationDisplay,

            'how_to_login'              => Locale::getLocalizedFieldValue($instituteTool, 'how_to_login'),
            'availability'              => WYSIWYG::valueForFrontend(Locale::getLocalizedFieldValue($instituteTool, 'availability')),
            'licensing'                 => Locale::getLocalizedFieldValue($instituteTool, 'licensing'),
            'request_access'            => WYSIWYG::valueForFrontend(Locale::getLocalizedFieldValue($instituteTool, 'request_access')),
            'instructions'              => WYSIWYG::valueForFrontend(Locale::getLocalizedFieldValue($instituteTool, 'instructions')),
            'instructions_manual_1_url' => $instituteTool->instructions_manual_1_url,
            'instructions_manual_2_url' => $instituteTool->instructions_manual_2_url,
            'instructions_manual_3_url' => $instituteTool->instructions_manual_3_url,

            'faq'                     => WYSIWYG::valueForFrontend(Locale::getLocalizedFieldValue($instituteTool, 'faq')),
            'examples_of_usage'       => WYSIWYG::valueForFrontend(Locale::getLocalizedFieldValue($instituteTool, 'examples_of_usage')),
            'additional_info_heading' => Locale::getLocalizedFieldValue($instituteTool, 'additional_info_heading'),
            'additional_info_text'    => WYSIWYG::valueForFrontend(Locale::getLocalizedFieldValue($instituteTool, 'additional_info_text')),

            'custom_fields' => CustomFieldValueResource::collection($instituteTool->customFields),

            'tooltips' => $this->getTooltips(),

            'why_unfit' => $instituteTool->status === Status::DISALLOWED ?
                WYSIWYG::valueForFrontend(Locale::getLocalizedFieldValue($instituteTool, 'why_unfit')) : null,

            'updated_at' => $instituteTool->updated_at?->toW3cString(),
        ];
    }

    private function getTags(): array
    {
        $tags = [];

        foreach (TagTypes::toArray() as $type) {
            $tags[$type] = TagResource::collection($this->tagsWithType($type));
        }

        return $tags;
    }

    private function getTooltips(): array
    {
        return [
            'status'                    => trans('institute.tool.tooltip.status'),
            'conditions'                => Locale::getLocalizedTranslation('institute.tool.tooltip.conditions'),
            'faq'                       => Locale::getLocalizedTranslation('institute.tool.tooltip.faq'),
            'examples_of_usage'         => Locale::getLocalizedTranslation('institute.tool.tooltip.examples_of_usage'),
            'additional_info_heading'   => Locale::getLocalizedTranslation('institute.tool.tooltip.additional_info_heading'),
            'additional_info_text'      => Locale::getLocalizedTranslation('institute.tool.tooltip.additional_info_text'),
            'how_to_login'              => Locale::getLocalizedTranslation('institute.tool.tooltip.how_to_login'),
            'availability'              => Locale::getLocalizedTranslation('institute.tool.tooltip.availability'),
            'licensing'                 => Locale::getLocalizedTranslation('institute.tool.tooltip.licensing'),
            'request_access'            => Locale::getLocalizedTranslation('institute.tool.tooltip.request_access'),
            'instructions'              => Locale::getLocalizedTranslation('institute.tool.tooltip.instructions'),
            'instructions_manual_1_url' => trans('institute.tool.tooltip.instructions_manual_1_url'),
            'instructions_manual_2_url' => trans('institute.tool.tooltip.instructions_manual_2_url'),
            'instructions_manual_3_url' => trans('institute.tool.tooltip.instructions_manual_3_url'),
            'links_with_other_tools'    => Locale::getLocalizedTranslation('institute.tool.tooltip.links_with_other_tools'),
            'sla_url'                   => trans('institute.tool.tooltip.sla_url'),
            'privacy_contact'           => trans('institute.tool.tooltip.privacy_contact'),
            'privacy_evaluation_url'    => trans('institute.tool.tooltip.privacy_evaluation_url'),
            'security_evaluation_url'   => trans('institute.tool.tooltip.security_evaluation_url'),
            'data_classification'       => trans('institute.tool.tooltip.data_classification'),
            'categories'                => trans('institute.tool.tooltip.categories'),
            'why_unfit'                 => Locale::getLocalizedTranslation('institute.tool.tooltip.why_unfit'),
        ];
    }
}
