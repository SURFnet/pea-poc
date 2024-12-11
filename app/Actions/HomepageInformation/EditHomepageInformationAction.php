<?php

declare(strict_types=1);

namespace App\Actions\HomepageInformation;

use App\Helpers\WYSIWYG;
use App\Models\Institute;
use Spatie\QueueableAction\QueueableAction;

class EditHomepageInformationAction
{
    use QueueableAction;

    public function execute(array $data, Institute $institute): void
    {
        $institute->update([
            'homepage_title_en' => $data['homepage_title_en'],
            'homepage_body_en'  => WYSIWYG::isEmpty($data['homepage_body_en']) ? null : $data['homepage_body_en'],
            'homepage_title_nl' => $data['homepage_title_nl'],
            'homepage_body_nl'  => WYSIWYG::isEmpty($data['homepage_body_nl']) ? null : $data['homepage_body_nl'],
        ]);
    }
}
