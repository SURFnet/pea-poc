<?php

declare(strict_types=1);

namespace App\Helpers;

class ToolPrefillData
{
    public static function get(): array
    {
        return [
            'privacy_analysis'     => trans('tool.prefills.privacy_analysis'),
            'use_for_education_en' => trans('tool.prefills.use_for_education', [], 'en'),
            'use_for_education_nl' => trans('tool.prefills.use_for_education', [], 'nl'),
        ];
    }

    public static function replaceWithNull(array &$data): void
    {
        if (isset($data['privacy_analysis']) &&
            $data['privacy_analysis'] === trans('tool.prefills.privacy_analysis')
        ) {
            $data['privacy_analysis'] = null;
        }

        if (isset($data['use_for_education_en']) &&
            $data['use_for_education_en'] === trans('tool.prefills.use_for_education', [], 'en')
        ) {
            $data['use_for_education_en'] = null;
        }

        if (isset($data['use_for_education_nl']) &&
            $data['use_for_education_nl'] === trans('tool.prefills.use_for_education', [], 'nl')
        ) {
            $data['use_for_education_nl'] = null;
        }
    }
}
