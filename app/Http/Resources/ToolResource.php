<?php

declare(strict_types=1);

namespace App\Http\Resources;

use App\Traits\Resources\WithImage;
use Illuminate\Http\Resources\Json\JsonResource;

/** @extends JsonResource<\App\Models\Tool> */
class ToolResource extends JsonResource
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
            'id' => $this->id,

            'name'                      => $this->name,
            'description_short'         => $this->description_short,
            'description_short_display' => $this->description_short_display,
            'image_filename'            => $this->image_filename,

            'supported_standards'         => $this->supported_standards,
            'supported_standards_display' => $this->supported_standards_display,
            'additional_standards'        => $this->additional_standards,

            'authentication_methods'         => $this->authentication_methods,
            'authentication_methods_display' => $this->authentication_methods_display,

            'stored_data'         => $this->stored_data,
            'stored_data_display' => $this->stored_data_display,
            'other_stored_data'   => $this->other_stored_data,

            'european_data_storage'           => $this->european_data_storage,
            'surf_standards_framework_agreed' => $this->surf_standards_framework_agreed,
            'has_processing_agreement'        => $this->has_processing_agreement,

            'description_long_1'           => $this->description_long_1,
            'description_long_1_display'   => $this->description_long_1_display,
            'description_1_image_filename' => $this->description_1_image_filename,
            'description_long_2'           => $this->description_long_2,
            'description_long_2_display'   => $this->description_long_2_display,
            'description_2_image_filename' => $this->description_2_image_filename,

            'info_url' => $this->info_url,

            'features' => $this->whenLoaded('features', fn () => FeatureResource::collection($this->features)),

            'image_url'               => $this->getImageUrl($this->image_filename, true),
            'description_1_image_url' => $this->getImageUrl($this->description_1_image_filename),
            'description_2_image_url' => $this->getImageUrl($this->description_2_image_filename),
        ];
    }
}
