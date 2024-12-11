<?php

declare(strict_types=1);

namespace App\Actions\Tool;

use App\Mail\ToolUpdated;
use App\Models\Tool;
use App\Models\User;
use Illuminate\Support\Facades\Mail;

class NotifyStakeholdersAction
{
    public function execute(Tool $tool): void
    {
        if (!config('mail.notifications_enabled')) {
            return;
        }

        foreach (User::getStakeholdersToNotifyFor($tool) as $user) {
            Mail::to($user)->queue(new ToolUpdated($tool));
        }
    }
}
