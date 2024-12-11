<?php

declare(strict_types=1);

namespace Tests\Unit\Actions\Tool;

use App\Actions\Tool\NotifyStakeholdersAction;
use App\Enums\Auth\Role;
use App\Enums\InstituteTool\Status;
use App\Mail\ToolUpdated;
use App\Models\InstituteTool;
use App\Models\Tool;
use App\Models\User;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Mail;
use Tests\TestCase;

class NotifyStakeholdersActionTest extends TestCase
{
    private NotifyStakeholdersAction $action;

    protected function setUp(): void
    {
        parent::setUp();

        $this->action = App::make(NotifyStakeholdersAction::class);

        Mail::fake();
    }

    /** @test */
    public function it_does_not_crash_when_there_is_no_user_to_notify(): void
    {
        $tool = Tool::factory()->create();

        $this->action->execute($tool);

        Mail::assertNothingSent();
    }

    /** @test */
    public function it_sends_an_email_when_tool_is_not_published(): void
    {
        $tool = Tool::factory()->published(false)->create();

        /** @var User $informationManager */
        $informationManager = User::factory()
            ->informationManager()
            ->create();

        InstituteTool::factory()
            ->for($informationManager->institute)
            ->for($tool)
            ->create();

        $this->action->execute($tool);

        Mail::assertQueued(function (ToolUpdated $mail) use ($informationManager) {
            return $mail->hasTo($informationManager->email);
        });
    }

    /** @test */
    public function it_sends_an_email_when_tool_is_published(): void
    {
        $tool = Tool::factory()->published()->create();

        /** @var User $informationManager */
        $informationManager = User::factory()
            ->informationManager()
            ->create();

        InstituteTool::factory()
            ->for($informationManager->institute)
            ->for($tool)
            ->create();

        $this->action->execute($tool);

        Mail::assertQueued(function (ToolUpdated $mail) use ($informationManager) {
            return $mail->hasTo($informationManager->email);
        });
    }

    /** @test */
    public function it_sends_an_email_even_if_tool_is_prohibited(): void
    {
        $tool = Tool::factory()->published()->create();

        /** @var User $informationManager */
        $informationManager = User::factory()
            ->informationManager()
            ->create();

        InstituteTool::factory()
            ->for($informationManager->institute)
            ->for($tool)
            ->status(Status::DISALLOWED)
            ->create();

        $this->action->execute($tool);

        Mail::assertQueued(function (ToolUpdated $mail) use ($informationManager) {
            return $mail->hasTo($informationManager->email);
        });
    }

    /** @test */
    public function it_sends_an_email_when_institute_tool_is_not_published(): void
    {
        /** @var User $informationManager */
        $informationManager = User::factory()
            ->informationManager()
            ->create();

        $instituteTool = InstituteTool::factory()
            ->for($informationManager->institute)
            ->published(false)
            ->create();

        $tool = $instituteTool->tool;

        $this->action->execute($tool);

        Mail::assertQueued(function (ToolUpdated $mail) use ($informationManager) {
            return $mail->hasTo($informationManager->email);
        });
    }

    /** @test */
    public function it_sends_an_email_when_institute_tool_is_published(): void
    {
        /** @var User $informationManager */
        $informationManager = User::factory()
            ->informationManager()
            ->create();

        $instituteTool = InstituteTool::factory()
            ->for($informationManager->institute)
            ->published()
            ->create();

        $tool = $instituteTool->tool;

        $this->action->execute($tool);

        Mail::assertQueued(function (ToolUpdated $mail) use ($informationManager) {
            return $mail->hasTo($informationManager->email);
        });
    }

    /** @test */
    public function it_sends_an_email_to_information_managers_with_email(): void
    {
        /** @var User $informationManager */
        $informationManager = User::factory()
            ->informationManager()
            ->create([
                'email' => 'information-manager@test.nl',
            ]);

        $instituteTool = InstituteTool::factory()
            ->for($informationManager->institute)
            ->published()
            ->create();

        $tool = $instituteTool->tool;

        $this->action->execute($tool);

        Mail::assertQueued(function (ToolUpdated $mail) use ($informationManager) {
            return $mail->hasTo($informationManager->email);
        });
    }

    /** @test */
    public function it_does_not_try_to_send_an_email_to_information_managers_without_email(): void
    {
        /** @var User $informationManager */
        $informationManager = User::factory()
            ->informationManager()
            ->create([
                'email' => null,
            ]);

        $instituteTool = InstituteTool::factory()
            ->for($informationManager->institute)
            ->published()
            ->create();

        $tool = $instituteTool->tool;

        $this->action->execute($tool);

        Mail::assertNotQueued(ToolUpdated::class);
    }

    /** @test */
    public function it_sends_an_email_to_multiple_information_managers_of_the_same_institute(): void
    {
        /** @var User $informationManagerOne */
        $informationManagerOne = User::factory()
            ->informationManager()
            ->create();

        $institute = $informationManagerOne->institute;

        /** @var User $informationManagerTwo */
        $informationManagerTwo = User::factory()
            ->informationManager()
            ->for($institute)
            ->create();

        $instituteTool = InstituteTool::factory()
            ->for($institute)
            ->published()
            ->create();

        $tool = $instituteTool->tool;

        $this->action->execute($tool);

        Mail::assertQueued(function (ToolUpdated $mail) use ($informationManagerOne) {
            return $mail->hasTo($informationManagerOne->email);
        });

        Mail::assertQueued(function (ToolUpdated $mail) use ($informationManagerTwo) {
            return $mail->hasTo($informationManagerTwo->email);
        });
    }

    /** @test */
    public function it_sends_an_email_to_multiple_information_managers_of_different_institutes(): void
    {
        /** @var User $informationManagerOne */
        $informationManagerOne = User::factory()
            ->informationManager()
            ->create();

        $instituteToolOne = InstituteTool::factory()
            ->for($informationManagerOne->institute)
            ->published()
            ->create();

        $tool = $instituteToolOne->tool;

        /** @var User $informationManagerTwo */
        $informationManagerTwo = User::factory()
            ->informationManager()
            ->create();

        InstituteTool::factory()
            ->for($informationManagerTwo->institute)
            ->for($tool)
            ->published()
            ->create();

        $this->action->execute($tool);

        Mail::assertQueued(function (ToolUpdated $mail) use ($informationManagerOne) {
            return $mail->hasTo($informationManagerOne->email);
        });

        Mail::assertQueued(function (ToolUpdated $mail) use ($informationManagerTwo) {
            return $mail->hasTo($informationManagerTwo->email);
        });
    }

    /** @test */
    public function it_does_not_send_an_email_to_information_managers_when_tool_does_not_belong_to_institute(): void
    {
        $tool = Tool::factory()->published()->create();

        User::factory()
            ->informationManager()
            ->create();

        $this->action->execute($tool);

        Mail::assertNotQueued(ToolUpdated::class);
    }

    /**
     * @test
     *
     * @dataProvider locales
     */
    public function the_email_has_correct_locale(string $locale): void
    {
        $tool = Tool::factory()->published(false)->create();

        /** @var User $informationManager */
        $informationManager = User::factory()
            ->informationManager()
            ->create([
                'language' => $locale,
            ]);

        InstituteTool::factory()
            ->for($informationManager->institute)
            ->for($tool)
            ->create();

        $this->action->execute($tool);

        Mail::assertQueued(function (ToolUpdated $mail) use ($informationManager, $locale) {
            return $mail->hasTo($informationManager->email) && $mail->locale === $locale;
        });
    }

    /**
     * @test
     *
     * @dataProvider rolesThatShouldNotBeEmailed
     */
    public function it_does_not_send_an_email_to_users_that_should_not_be_emailed(string $role): void
    {
        /** @var User $user */
        $user = User::factory()
            ->withRoles([$role])
            ->create();

        $institute = $user->institute;

        $instituteTool = InstituteTool::factory()
            ->for($institute)
            ->published()
            ->create();

        $tool = $instituteTool->tool;

        $this->action->execute($tool);

        Mail::assertNotQueued(ToolUpdated::class);
    }

    public function rolesThatShouldNotBeEmailed(): array
    {
        return Arr::map(
            Arr::where(Role::toArray(), fn ($role) => $role !== Role::INFORMATION_MANAGER),
            fn ($role) => [$role]
        );
    }

    public function locales(): array
    {
        return [
            ['en'],
            ['nl'],
        ];
    }
}
