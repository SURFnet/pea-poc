<?php

declare(strict_types=1);

namespace Tests\Unit\Actions\Tool;

use App\Actions\Tool\SubmitRequestForChangeAction;
use App\Mail\RequestForChange;
use App\Models\InstituteTool;
use App\Models\Tool;
use App\Models\User;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Mail;
use Tests\TestCase;

class SubmitRequestForChangeActionTest extends TestCase
{
    private SubmitRequestForChangeAction $action;

    protected function setUp(): void
    {
        parent::setUp();

        $this->action = App::make(SubmitRequestForChangeAction::class);
    }

    /** @test */
    public function it_does_not_crash_when_the_user_has_no_email(): void
    {
        Mail::fake();

        $tool = Tool::factory()->create();

        $informationManager = User::factory()
            ->informationManager()
            ->create([
                'email' => null,
            ]);

        $this->action->execute($tool, $informationManager, ':: request for change ::');

        Mail::assertNothingSent();
    }

    /** @test */
    public function it_sends_an_email_when_tool_is_published(): void
    {
        Mail::fake();

        $tool = Tool::factory()->published()->create();

        $informationManager = User::factory()
            ->informationManager()
            ->create();

        $this->action->execute($tool, $informationManager, ':: request for change ::');

        Mail::assertQueued(RequestForChange::class);
    }

    /** @test */
    public function it_sends_an_email_when_tool_is_not_published(): void
    {
        Mail::fake();

        $tool = Tool::factory()->published(false)->create();

        $informationManager = User::factory()
            ->informationManager()
            ->create();

        $this->action->execute($tool, $informationManager, ':: request for change ::');

        Mail::assertQueued(RequestForChange::class);
    }

    /** @test */
    public function it_sends_an_email_when_institute_tool_is_not_published(): void
    {
        Mail::fake();

        $informationManager = User::factory()
            ->informationManager()
            ->create();

        $instituteTool = InstituteTool::factory()
            ->for($informationManager->institute)
            ->published(false)
            ->create();

        $tool = $instituteTool->tool;

        $this->action->execute($tool, $informationManager, ':: request for change ::');

        Mail::assertQueued(RequestForChange::class);
    }

    /** @test */
    public function it_sends_an_email_when_institute_tool_is_published(): void
    {
        Mail::fake();

        $informationManager = User::factory()
            ->informationManager()
            ->create();

        $instituteTool = InstituteTool::factory()
            ->for($informationManager->institute)
            ->published()
            ->create();

        $tool = $instituteTool->tool;

        $this->action->execute($tool, $informationManager, ':: request for change ::');

        Mail::assertQueued(RequestForChange::class);
    }

    /** @test */
    public function the_email_to_is_fetched_from_config(): void
    {
        Mail::fake();

        Config::set('mail.request_for_change.to', 'admin@app.com');

        $tool = Tool::factory()->create();

        $informationManager = User::factory()
            ->informationManager()
            ->create();

        $this->action->execute($tool, $informationManager, ':: request for change ::');

        Mail::assertQueued(function (RequestForChange $mail) {
            return $mail->hasTo('admin@app.com');
        });
    }

    /** @test */
    public function the_email_bcc_is_user(): void
    {
        Mail::fake();

        Config::set('mail.request_for_change.to', 'app@domain.com');

        $tool = Tool::factory()->create();

        $informationManager = User::factory()
            ->informationManager()
            ->create([
                'email' => 'im@app.com',
            ]);

        $this->action->execute($tool, $informationManager, ':: request for change ::');

        Mail::assertQueued(function (RequestForChange $mail) use ($informationManager) {
            return $mail->hasBcc($informationManager->email);
        });
    }

    /** @test */
    public function the_email_subject_is_correct(): void
    {
        $tool = Tool::factory()->create();

        $informationManager = User::factory()
            ->informationManager()
            ->create();

        $requestForChange = ':: request for change ::';

        $mail = new RequestForChange($tool, $informationManager, $requestForChange);

        $mail->assertHasSubject('Request for change - ' . $tool->name);
    }

    /** @test */
    public function the_email_content_is_correct(): void
    {
        $tool = Tool::factory()->create();

        $informationManager = User::factory()
            ->informationManager()
            ->create();

        $requestForChange = ':: request for change & special characters ::';

        $mail = new RequestForChange($tool, $informationManager, $requestForChange);

        $mail->assertSeeInText($informationManager->name);
        $mail->assertSeeInText($informationManager->institute->full_name);
        $mail->assertSeeInText($requestForChange);
    }

    /** @test */
    public function the_email_contains_url_to_our_tool_show_page_when_in_institute_collection(): void
    {
        $informationManager = User::factory()
            ->informationManager()
            ->create();

        $instituteTool = InstituteTool::factory()
            ->for($informationManager->institute)
            ->published()
            ->create();

        $tool = $instituteTool->tool;
        $tool->published_at = Carbon::now();
        $tool->save();

        $mail = new RequestForChange($tool, $informationManager, ':: request for change ::');

        $mail->assertSeeInHtml(route('our.tool.show', $tool));
        $mail->assertDontSeeInHtml(route('other.tool.show', $tool));
    }

    /** @test */
    public function the_email_contains_url_to_other_tool_show_page_when_not_in_institute_collection(): void
    {
        $tool = Tool::factory()->create();

        $informationManager = User::factory()
            ->informationManager()
            ->create();

        $mail = new RequestForChange($tool, $informationManager, ':: request for change ::');

        $mail->assertSeeInHtml(route('other.tool.show', $tool));
        $mail->assertDontSeeInHtml(route('our.tool.show', $tool));
    }
}
