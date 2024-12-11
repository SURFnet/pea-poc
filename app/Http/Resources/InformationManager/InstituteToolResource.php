<?php

declare(strict_types=1);

namespace App\Http\Resources\InformationManager;

use App\Enums\InstituteTool\Status;
use App\Http\Resources\ToolResource;
use App\Models\ConceptInstituteTool;
use Illuminate\Http\Resources\Json\JsonResource;

/** @mixin \App\Models\ConceptInstituteTool */
class InstituteToolResource extends JsonResource
{
    /**
     * @param \Illuminate\Http\Request $request
     *
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     */
    public function toArray($request): array
    {
        /** @var ConceptInstituteTool $concept */
        $concept = $this->resource;

        $tool = $concept->originalVersion->tool;

        return [
            'alternative_tools_ids' => $concept->allowedAlternativeTools()->pluck('tool_id'),

            'prohibited_alternative_tools_tool' => ToolResource::collection($concept->prohibitedAlternativeTools),
            'alternative_tools_tool'            => ToolResource::collection($concept->allowedAlternativeTools),

            'categories'     => $concept->categories()->pluck('id'),
            'status'         => $concept->status,
            'status_display' => Status::getTranslation($concept->status ?? Status::UNRATED),
            'conditions_en'  => $concept->conditions_en,
            'conditions_nl'  => $concept->conditions_nl,

            'links_with_other_tools_en' => $concept->links_with_other_tools_en,
            'links_with_other_tools_nl' => $concept->links_with_other_tools_nl,
            'sla_url'                   => $concept->sla_url,

            'privacy_contact'         => $concept->privacy_contact,
            'privacy_evaluation_url'  => $concept->privacy_evaluation_url,
            'security_evaluation_url' => $concept->security_evaluation_url,
            'data_classification'     => $concept->data_classification,

            'how_to_login_en'           => $concept->how_to_login_en,
            'how_to_login_nl'           => $concept->how_to_login_nl,
            'availability_en'           => $concept->availability_en,
            'availability_nl'           => $concept->availability_nl,
            'licensing_en'              => $concept->licensing_en,
            'licensing_nl'              => $concept->licensing_nl,
            'request_access_en'         => $concept->request_access_en,
            'request_access_nl'         => $concept->request_access_nl,
            'instructions_en'           => $concept->instructions_en,
            'instructions_nl'           => $concept->instructions_nl,
            'instructions_manual_1_url' => $concept->instructions_manual_1_url,
            'instructions_manual_2_url' => $concept->instructions_manual_2_url,
            'instructions_manual_3_url' => $concept->instructions_manual_3_url,

            'faq_en'                     => $concept->faq_en,
            'faq_nl'                     => $concept->faq_nl,
            'examples_of_usage_en'       => $concept->examples_of_usage_en,
            'examples_of_usage_nl'       => $concept->examples_of_usage_nl,
            'additional_info_heading_en' => $concept->additional_info_heading_en,
            'additional_info_heading_nl' => $concept->additional_info_heading_nl,
            'additional_info_text_en'    => $concept->additional_info_text_en,
            'additional_info_text_nl'    => $concept->additional_info_text_nl,

            'why_unfit_en' => $concept->why_unfit_en,
            'why_unfit_nl' => $concept->why_unfit_nl,

            'custom_fields' => CustomFieldValueResource::collection($concept->getAllCustomFields()),

            'is_published' => $concept->originalVersion->is_published,
            'published_at' => $concept->originalVersion->published_at,

            'permissions' => [
                'publish' => $request->user()->can('publishForInstitute', $tool),
            ],
        ];
    }
}
