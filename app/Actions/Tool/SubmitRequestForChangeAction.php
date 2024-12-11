<?php

declare(strict_types=1);

namespace App\Actions\Tool;

use App\Mail\RequestForChange;
use App\Models\Tool;
use App\Models\User;
use Illuminate\Support\Facades\Mail;

class SubmitRequestForChangeAction
{
    public function execute(Tool $tool, User $user, string $requestForChange): void
    {
        $mail = new RequestForChange($tool, $user, $requestForChange);

        $mailable = Mail::to(config('mail.request_for_change.to'));

        if ($user->email !== null) {
            $mailable->bcc($user);
        }

        $mailable->queue($mail);
    }
}
