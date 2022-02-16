<?php

declare(strict_types=1);

namespace Tests\Feature\Other\Tool;

use App\Enums\InstituteTool\Status as InstituteToolStatus;
use App\Models\InstituteTool;
use App\Models\Tool;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Tests\TestCase;

class CanSupportTest extends TestCase
{
    /** @test */
    public function a_teacher_can_get_support_for_a_tool(): void
    {
        $instituteTool = $this->createInstituteTool($this->teacher, InstituteToolStatus::RECOMMENDED);

        $this->assertTrue($this->teacher->can('getSupport', $instituteTool->tool));
    }

    /** @test */
    public function an_information_manager_can_get_support_for_a_tool(): void
    {
        $instituteTool = $this->createInstituteTool($this->informationManager, InstituteToolStatus::RECOMMENDED);

        $this->assertTrue($this->informationManager->can('getSupport', $instituteTool->tool));
    }

    /** @test */
    public function an_content_manager_can_not_get_support_for_a_tool(): void
    {
        $instituteTool = $this->createInstituteTool($this->contentManager, InstituteToolStatus::RECOMMENDED);

        $this->assertTrue($this->contentManager->cannot('getSupport', $instituteTool->tool));
    }

    /**
     * @test
     *
     * @dataProvider supportedStatuses
     */
    public function tools_with_supported_status_offer_support(string $status): void
    {
        $instituteTool = $this->createInstituteTool($this->informationManager, $status);

        $this->assertTrue($this->informationManager->can('getSupport', $instituteTool->tool));
    }

    /**
     * @test
     *
     * @dataProvider unsupportedStatuses
     */
    public function tools_without_supported_status_do_not_offer_support(string $status): void
    {
        $instituteTool = $this->createInstituteTool($this->informationManager, $status);

        $this->assertTrue($this->informationManager->cannot('getSupport', $instituteTool->tool));
    }

    private function createInstituteTool(User $actingUser, string $status): Model
    {
        return InstituteTool::factory()
            ->for(Tool::factory()->published()->create())
            ->for($actingUser->institute)
            ->published()
            ->create([
                'status' => $status,
            ]);
    }

    public function supportedStatuses(): array
    {
        return [
            [InstituteToolStatus::RECOMMENDED],
            [InstituteToolStatus::SUPPORTED],
        ];
    }

    public function unsupportedStatuses(): array
    {
        return [
            [InstituteToolStatus::FREE_TO_USE],
            [InstituteToolStatus::PROHIBITED],
            [InstituteToolStatus::NOT_RECOMMENDED],
        ];
    }
}
