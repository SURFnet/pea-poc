<?php

declare(strict_types=1);

namespace App\Mail;

use App\Models\Tool;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;

class ToolUpdated extends BaseMailable
{
    public function __construct(private readonly Tool $tool)
    {
        parent::__construct();
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: trans('mailable.tool_updated.subject', [
                'tool' => $this->tool->name,
            ])
        );
    }

    public function content(): Content
    {
        return new Content(
            markdown: 'mailable.tool-updated',
            with: [
                'tool' => $this->tool,
            ],
        );
    }
}
