<?php

declare(strict_types=1);

namespace Tests\Feature\SharedTool\Concerns;

use App\Models\Tool;
use App\Models\User;
use Database\Factories\ToolFactory;

trait HasToolTypeProvider
{
    protected User $admin;

    protected User $informationManager;

    protected User $actingUser;

    protected ToolFactory $baseFactory;

    protected string $indexRouteName;

    protected string $createRouteName;

    protected string $storeRouteName;

    protected string $editRouteName;

    protected string $updateRouteName;

    protected string $publishRouteName;

    protected string $cancelEditRouteName;

    protected string $logRouteName;

    protected string $publishConceptRouteName;

    protected string $discardConceptRouteName;

    protected function setupForToolType(bool $isCustomTool): void
    {
        $this->actingUser = $isCustomTool
            ? $this->informationManager
            : $this->admin;

        $this->baseFactory = $isCustomTool
            ? Tool::factory()->customTool($this->actingUser->institute)
            : Tool::factory();

        $this->indexRouteName = $isCustomTool
            ? 'information-manager.custom-tool.index'
            : 'content-manager.tool.index';

        $this->createRouteName = $isCustomTool
            ? 'information-manager.custom-tool.create'
            : 'content-manager.tool.create';

        $this->storeRouteName = $isCustomTool
            ? 'information-manager.custom-tool.store'
            : 'content-manager.tool.store';

        $this->editRouteName = $isCustomTool
            ? 'information-manager.custom-tool.edit'
            : 'content-manager.tool.edit';

        $this->updateRouteName = $isCustomTool
            ? 'information-manager.custom-tool.update'
            : 'content-manager.tool.update';

        $this->publishRouteName = $isCustomTool
            ? 'information-manager.custom-tool.publish'
            : 'content-manager.tool.publish';

        $this->cancelEditRouteName = $isCustomTool
            ? 'information-manager.custom-tool.cancel-edit'
            : 'content-manager.tool.cancel-edit';

        $this->logRouteName = $isCustomTool
            ? 'information-manager.custom-tool.log'
            : 'content-manager.tool.log';

        $this->publishConceptRouteName = $isCustomTool
            ? 'information-manager.custom-tool.publish-concept'
            : 'content-manager.tool.publish-concept';

        $this->discardConceptRouteName = $isCustomTool
            ? 'information-manager.custom-tool.discard-concept'
            : 'content-manager.tool.discard-concept';
    }

    protected function toolTypeProvider(): array
    {
        return [
            'tool' => [
                false,
            ],
            'custom_tool' => [
                true,
            ],
        ];
    }
}
