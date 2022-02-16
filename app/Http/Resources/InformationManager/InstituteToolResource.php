<?php

declare(strict_types=1);

namespace App\Http\Resources\InformationManager;

use App\Enums\InstituteTool\Status;
use App\Traits\Resources\WithImage;
use Illuminate\Http\Resources\Json\JsonResource;

/** @extends JsonResource<\App\Models\InstituteTool> */
class InstituteToolResource extends JsonResource
{
    use WithImage;

    /**
     * @param \Illuminate\Http\Request $request
     *
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     */
    public function toArray($request): array
    {
        return [
            'alternative_tool_id' => $this->alternative_tool_id,

            'description_1'                => $this->description_1,
            'description_1_image_filename' => $this->description_1_image_filename,
            'description_1_image_url'      => $this->getImageUrl($this->description_1_image_filename),

            'description_2'                => $this->description_2,
            'description_2_image_filename' => $this->description_2_image_filename,
            'description_2_image_url'      => $this->getImageUrl($this->description_2_image_filename),

            'extra_information_title' => $this->extra_information_title,
            'extra_information'       => $this->extra_information,

            'support_title_1' => $this->support_title_1,
            'support_email_1' => $this->support_email_1,
            'support_title_2' => $this->support_title_2,
            'support_email_2' => $this->support_email_2,

            'manual_title_1' => $this->manual_title_1,
            'manual_url_1'   => $this->manual_url_1,
            'manual_title_2' => $this->manual_title_2,
            'manual_url_2'   => $this->manual_url_2,

            'video_title_1' => $this->video_title_1,
            'video_url_1'   => $this->video_url_1,
            'video_title_2' => $this->video_title_2,
            'video_url_2'   => $this->video_url_2,

            'status'         => $this->status,
            'status_display' => trans('institute.tool.statuses.' . ($this->status ?? Status::UNRATED)),

            'why_unfit' => $this->why_unfit,

            'categories' => $this->institute->categories()->forTool($this->tool)->pluck('id'),

            'is_published' => $this->is_published,
            'published_at' => $this->published_at,

            'permissions' => [
                'publish' => $request->user()->can('publishForInstitute', $this->resource->tool),
            ],
        ];
    }
}
