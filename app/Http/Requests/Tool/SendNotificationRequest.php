<?php

declare(strict_types=1);

namespace App\Http\Requests\Tool;

use App\Models\Institute;
use App\Models\Tool;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class SendNotificationRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->can('sendNotifications', [Institute::class]);
    }

    public function rules(): array
    {
        return [
            'tool'    => ['required', Rule::in(Tool::pluck('id'))],
            'subject' => ['required', 'string'],
            'message' => ['required', 'string'],
        ];
    }

    public function attributes(): array
    {
        return trans('institute.notifications.attributes');
    }
}
