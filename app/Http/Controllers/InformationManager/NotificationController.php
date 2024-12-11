<?php

declare(strict_types=1);

namespace App\Http\Controllers\InformationManager;

use App\Actions\Institute\Tool\SendNotificationAction;
use App\Helpers\Auth;
use App\Http\Controllers\Controller;
use App\Http\Requests\Tool\SendNotificationRequest;
use App\Models\Tool;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\RedirectResponse;
use Inertia\Inertia;
use Inertia\Response;

class NotificationController extends Controller
{
    public function create(Tool $tool = null): Response
    {
        $institute = Auth::user()->institute;
        $tools = Tool::query()
            ->select('tools.id', 'tools.name')
            ->withCount(['followers' => function (Builder $query) use ($institute): void {
                $query->where('institute_id', $institute->id);
            }])
            ->orderBy('name')
            ->get()
            ->mapWithKeys(function (Tool $tool): array {
                $name = trans_choice('institute.notifications.attributes.tool_name', $tool->followers_count, [
                    'tool' => $tool->name,
                ]);

                return [$tool->id => $name];
            });

        return Inertia::render('information-manager/notifications/Create', [
            'tools' => $tools,
            'tool'  => $tool,
        ]);
    }

    public function send(SendNotificationRequest $request, SendNotificationAction $action): RedirectResponse
    {
        $tool = Tool::find($request->validated('tool'));

        $action->execute($tool, $request->validated());

        flash(trans('message.update-sent'), 'success');

        return redirect()->route('information-manager.notifications.create');
    }
}
