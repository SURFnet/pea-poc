<?php

declare(strict_types=1);

namespace App\Actions\Institute\Tool;

use App\Helpers\Auth;
use App\Mail\InstituteToolNotificationMail;
use App\Models\Tool;
use App\Models\User;
use Illuminate\Support\Facades\Mail;

class SendNotificationAction
{
    private User $sender;

    public function __construct()
    {
        $this->sender = Auth::user();
    }

    public function execute(Tool $tool, array $data): void
    {
        $tool
            ->followers()
            ->fromInstitute($this->sender->institute)
            ->withEmail()
            ->each(fn (User $follower) => $this->sendNotificationTo($follower, $tool, $data));

        $this->sendNotificationTo($this->sender, $tool, $data);
    }

    private function sendNotificationTo(User $recipient, Tool $tool, array $data): void
    {
        $toolUpdateMail = new InstituteToolNotificationMail(
            $this->sender,
            $tool,
            $data['subject'],
            $data['message']
        );

        Mail::to($recipient)->queue($toolUpdateMail);
    }
}
