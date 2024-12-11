<?php

declare(strict_types=1);

namespace App\Actions\Institute\Tool;

use App\Models\Institute;
use App\Models\InstituteTool;
use App\Models\Tool;
use App\Models\User;
use Spatie\QueueableAction\QueueableAction;

class AddAction
{
    use QueueableAction;

    public function execute(Tool $tool, Institute $institute, array $data, User $user): void
    {
        $tool->institutes()->attach($institute);

        (new UpdateAction())->execute($tool, $institute, $data, $user);

        $this->setCustomFields($tool, $institute, $data);
    }

    private function setCustomFields(Tool $tool, Institute $institute, array $data): void
    {
        if (!isset($data['custom_fields'])) {
            return;
        }

        $instituteTool = InstituteTool::forTool($tool)->forInstitute($institute)->firstOrFail();

        $instituteTool->customFields()->sync([]);
        foreach ($data['custom_fields'] as $customField) {
            if ($customField['value_en'] === null) {
                continue;
            }

            $instituteTool->customFields()->attach($customField['id'], [
                'value_en' => $customField['value_en'],
                'value_nl' => $customField['value_nl'],
            ]);
        }
    }
}
