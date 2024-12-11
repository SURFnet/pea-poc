<?php

declare(strict_types=1);

namespace App\Http\Resources\InformationManager;

use App\Helpers\Locale;
use App\Helpers\WYSIWYG;
use App\Models\CustomField;
use Illuminate\Http\Resources\Json\JsonResource;

/** @mixin \App\Models\CustomField */
class CustomFieldValueResource extends JsonResource
{
    private CustomField $field;

    public function __construct($resource)
    {
        parent::__construct($resource);

        $this->field = $this->resource;
    }

    /**
     * @param \Illuminate\Http\Request $request
     *
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     */
    public function toArray($request): array
    {
        return [
            'id' => $this->field->id,

            'title_en' => $this->field->title_en,
            'title_nl' => $this->field->title_nl,

            'value_en' => WYSIWYG::valueForFrontend($this->field->pivot?->value_en),
            'value_nl' => WYSIWYG::valueForFrontend($this->field->pivot?->value_nl),

            'tab_type' => $this->field->tab_type,

            'title' => Locale::getLocalizedFieldValue($this->field, 'title'),
            'value' => $this->when(
                $this->field->pivot !== null,
                fn () => WYSIWYG::valueForFrontend(
                    Locale::getLocalizedFieldValue($this->field->pivot, 'value')
                )
            ),
        ];
    }
}
