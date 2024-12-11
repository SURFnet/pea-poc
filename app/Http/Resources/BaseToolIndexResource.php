<?php

declare(strict_types=1);

namespace App\Http\Resources;

use App\Helpers\Format;
use App\Helpers\Locale;
use App\Models\ConceptTool;
use App\Models\Tool;
use App\Traits\Resources\WithImage;
use Illuminate\Http\Resources\Json\JsonResource;

/** @mixin \App\Models\Tool */
abstract class BaseToolIndexResource extends JsonResource
{
    use WithImage;

    /**
     * @param \Illuminate\Http\Request $request
     *
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     */
    public function toArray($request): array
    {
        $tool = $this->getTool();

        return [
            'id' => $tool->id,

            ...$this->getToolData($tool),
        ];
    }

    protected function getTool(): Tool
    {
        return $this->resource;
    }

    protected function getToolData(Tool|ConceptTool $tool): array
    {
        $shortDescription = Locale::getLocalizedFieldValue($tool, 'description_short');

        return [
            'name' => $tool->name,

            'description_short_stripped_tags' => Format::asSimpleHtml($shortDescription),

            'logo_url' => $this->getImageUrl($tool->logo_filename, true),
        ];
    }
}
