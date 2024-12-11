<?php

declare(strict_types=1);

namespace App\Mail;

use App\Models\Tool;
use App\Models\User;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;

class RequestForChange extends BaseMailable implements ShouldQueue
{
    public function __construct(
        private readonly Tool $tool,
        private readonly User $user,
        private readonly string $requestForChange
    ) {
        parent::__construct();
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Request for change - ' . $this->tool->name,
        );
    }

    public function content(): Content
    {
        return new Content(
            markdown:'mailable.request-for-change',
            with: [
                'tool'             => $this->tool,
                'user'             => $this->user,
                'requestForChange' => $this->requestForChange,
                'urlToTool'        => $this->getUrlToTool(),
            ],
        );
    }

    private function getUrlToTool(): string
    {
        return route(
            $this->tool->isPublishedForInstitute($this->user->institute) ? 'our.tool.show' : 'other.tool.show',
            $this->tool
        );
    }
}
