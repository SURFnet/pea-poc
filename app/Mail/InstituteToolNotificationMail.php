<?php

declare(strict_types=1);

namespace App\Mail;

use App\Models\Tool;
use App\Models\User;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;

class InstituteToolNotificationMail extends BaseMailable
{
    public function __construct(
        private readonly User $sender,
        private readonly Tool $tool,
        string $subject,
        private readonly string $message,
    ) {
        parent::__construct();
        $this->subject = $subject;
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            replyTo: $this->sender->email,
            subject: $this->subject,
        );
    }

    public function content(): Content
    {
        return new Content(
            markdown: 'emails.institute-tool-notification',
            with: [
                'message' => $this->message,
                'tool'    => $this->tool,
                'sender'  => $this->sender,
                'url'     => $this->getPublicToolUrl(),
            ]
        );
    }

    private function getPublicToolUrl(): string
    {
        if ($this->tool->isPublishedForInstitute($this->sender->institute)) {
            return route('our.tool.show', $this->tool);
        }

        return route('other.tool.show', $this->tool);
    }
}
