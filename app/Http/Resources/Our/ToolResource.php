<?php

declare(strict_types=1);

namespace App\Http\Resources\Our;

use App\Enums\InstituteTool\Status;
use App\Http\Resources\CategoryResource;
use App\Http\Resources\ToolResource as BaseToolResource;
use App\Models\Experience;
use App\Models\InstituteTool;
use App\Traits\Resources\WithImage;

class ToolResource extends BaseToolResource
{
    use WithImage;

    /** @param \Illuminate\Http\Request $request */
    public function toArray($request): array
    {
        return array_merge(parent::toArray($request), [
            'rating'            => $this->rating,
            'total_experiences' => $this->experiences->count(),

            'institute' => $this->getInstituteData($request),

            'permissions' => [
                'share_experience'          => $request->user()->can('create', [Experience::class, $this->resource]),
                'see_technical_information' => $request->user()->isInformationManager(),
            ],
        ]);
    }

    /** @param \Illuminate\Http\Request $request */
    private function getInstituteData($request): array
    {
        $institute = $request->user()->institute;

        $data = $institute->tools()->find($this->resource)?->pivot;
        if ($data === null) {
            $data = new InstituteTool();
        }

        $categories = $institute->categories()->forTool($this->resource)->get();

        return [
             'description_1'                => $data->description_1,
             'description_1_display'        => $data->description_1_display,
             'description_1_image_filename' => $data->description_1_image_filename,
             'description_1_image_url'      => $this->getImageUrl($data->description_1_image_filename),

             'description_2'                => $data->description_2,
             'description_2_display'        => $data->description_2_display,
             'description_2_image_filename' => $data->description_2_image_filename,
             'description_2_image_url'      => $this->getImageUrl($data->description_2_image_filename),

             'extra_information_title'   => $data->extra_information_title,
             'extra_information'         => $data->extra_information,
             'extra_information_display' => $data->extra_information_display,

             'support_title_1' => $data->support_title_1,
             'support_email_1' => $data->support_email_1,
             'support_title_2' => $data->support_title_2,
             'support_email_2' => $data->support_email_2,

             'manual_title_1' => $data->manual_title_1,
             'manual_url_1'   => $data->manual_url_1,
             'manual_title_2' => $data->manual_title_2,
             'manual_url_2'   => $data->manual_url_2,

             'video_title_1' => $data->video_title_1,
             'video_url_1'   => $data->video_url_1,
             'video_title_2' => $data->video_title_2,
             'video_url_2'   => $data->video_url_2,

             'categories' => CategoryResource::collection($categories),

             'status'         => $data->status ?? Status::UNRATED,
             'status_display' => trans('institute.tool.statuses.' . ($data->status ?? Status::UNRATED)),

             'why_unfit'         => $data->why_unfit,
             'why_unfit_display' => $data->why_unfit_display,
             'alternative_tool'  => $data->alternativeTool ? new AlternativeToolResource($data->alternativeTool) : null,
         ];
    }
}
