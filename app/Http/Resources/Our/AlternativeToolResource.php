<?php

declare(strict_types=1);

namespace App\Http\Resources\Our;

use App\Enums\InstituteTool\Status;
use App\Http\Resources\ToolIndexResource as BaseToolIndexResource;
use App\Models\InstituteTool;
use App\Traits\Resources\WithImage;
use Illuminate\Http\Request;

class AlternativeToolResource extends BaseToolIndexResource
{
    use WithImage;

    /**
     * @param \Illuminate\Http\Request $request
     */
    public function toArray($request): array
    {
        return array_merge(parent::toArray($request), [
            'name'              => $this->name,
            'description_short' => $this->description_short,
            'rating'            => $this->rating,
            'institute'         => $this->getInstituteData($request),
        ]);
    }

    private function getInstituteData(Request $request): array
    {
        $institute = $request->user()->institute;

        $data = $institute->tools()->find($this->resource)?->pivot;
        if ($data === null) {
            $data = new InstituteTool();
        }

        return [
             'status'         => $data->status ?? Status::UNRATED,
             'status_display' => trans('institute.tool.statuses.' . ($data->status ?? 'unrated')),
         ];
    }
}
