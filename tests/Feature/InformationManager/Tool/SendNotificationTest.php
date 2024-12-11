<?php

declare(strict_types=1);

namespace Tests\Feature\InformationManager\Tool;

use App\Actions\Institute\Tool\AddAction;
use App\Actions\Tool\ChangeFollowingStatusAction;
use App\Mail\InstituteToolNotificationMail;
use App\Models\Institute;
use App\Models\Tool;
use App\Models\User;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Mail;
use Tests\TestCase;

class SendNotificationTest extends TestCase
{
    protected User $informationManager;

    protected Institute $institute;

    protected Tool $tool;

    /** @var Collection<User> */
    protected Collection $followers;

    protected User $otherFollower;

    protected function setUp(): void
    {
        parent::setUp();

        $this->informationManager = User::factory()->informationManager()->create();
        $this->institute = $this->informationManager->institute;

        $this->tool = Tool::factory()->published()->create();
        (new AddAction())->execute($this->tool, $this->institute, [], $this->informationManager);

        $this->followers = User::factory()
            ->count(4)
            ->teacher()
            ->create(['institute_id' => $this->institute]);

        foreach ($this->followers as $follower) {
            (new ChangeFollowingStatusAction())->execute($this->tool, $follower);
        }

        $this->otherFollower = User::factory()->teacher()->create();
        (new ChangeFollowingStatusAction())->execute($this->tool, $this->otherFollower);

        Mail::fake();
    }

    /** @test */
    public function it_sends_mail_to_all_followers_in_same_institute(): void
    {
        $this
            ->actingAs($this->informationManager)
            ->post(route('information-manager.notifications.send'), [
                'tool'    => $this->tool->id,
                'subject' => '::Testmail Subject::',
                'message' => '::Testmail Body::',
            ])

            ->assertSessionHasNoErrors();

        foreach ($this->followers as $follower) {
            Mail::assertQueued(fn (InstituteToolNotificationMail $mail): bool => $mail->hasTo($follower->email));
        }
    }

    /** @test */
    public function it_sends_mail_to_the_sender_as_well(): void
    {
        $this
            ->actingAs($this->informationManager)
            ->post(route('information-manager.notifications.send'), [
                'tool'    => $this->tool->id,
                'subject' => '::Testmail Subject::',
                'message' => '::Testmail Body::',
            ])

            ->assertSessionHasNoErrors();

        Mail::assertQueued(
            fn (InstituteToolNotificationMail $mail): bool => $mail->hasTo($this->informationManager->email)
        );
    }

    /** @test */
    public function it_wont_send_mail_to_followers_in_other_institutes(): void
    {
        /** @var User $otherFollower */
        $otherFollower = User::factory()->teacher()->create();
        (new ChangeFollowingStatusAction())->execute($this->tool, $otherFollower);

        $this
            ->actingAs($this->informationManager)
            ->post(route('information-manager.notifications.send'), [
                'tool'    => $this->tool->id,
                'subject' => '::Testmail Subject::',
                'message' => '::Testmail Body::',
            ]);

        Mail::assertNotQueued(fn (InstituteToolNotificationMail $mail): bool => $mail->hasTo($otherFollower->email));
    }
}
