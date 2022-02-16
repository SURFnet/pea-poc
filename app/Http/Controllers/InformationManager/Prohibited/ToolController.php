<?php

declare(strict_types=1);

namespace App\Http\Controllers\InformationManager\Prohibited;

use App\Actions\Institute\Tool\Prohibited\AddAction;
use App\Actions\Institute\Tool\Prohibited\UpdateAction;
use App\Actions\Institute\Tool\PublishAction;
use App\Actions\Institute\Tool\UnpublishAction;
use App\Helpers\Auth;
use App\Http\Controllers\Controller;
use App\Http\Requests\InstituteTool\Prohibited\PublishRequest;
use App\Http\Requests\InstituteTool\Prohibited\StoreRequest;
use App\Http\Requests\InstituteTool\Prohibited\UpdateRequest;
use App\Http\Resources\InformationManager\InstituteToolResource;
use App\Http\Resources\Our\ToolResource;
use App\Models\Institute;
use App\Models\Tool;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Collection;
use Inertia\Inertia;
use Inertia\Response;

class ToolController extends Controller
{
    public function create(Tool $tool): Response
    {
        $this->authorize('addToInstitute', $tool);

        $institute = Auth::user()->institute;

        return Inertia::render('information-manager/tools/prohibited/Create', [
            'tool'             => new ToolResource($tool),
            'alternativeTools' => $this->getAlternativeTools($institute, $tool),
            'backUrl'          => route('other.tool.show', $tool),
        ]);
    }

    public function store(StoreRequest $request, Tool $tool): RedirectResponse
    {
        (new AddAction())->execute($tool, Auth::user()->institute, $request->validated());

        flash(trans('message.data-saved'), 'success');

        return redirect()->route('information-manager.tool.index');
    }

    public function edit(Tool $tool): Response
    {
        $this->authorize('updateForInstitute', $tool);

        $institute = Auth::user()->institute;

        return Inertia::render('information-manager/tools/prohibited/Edit', [
            'tool'             => new ToolResource($tool),
            'instituteTool'    => new InstituteToolResource($institute->tools()->find($tool)->pivot),
            'alternativeTools' => $this->getAlternativeTools($institute, $tool),
            'backUrl'          => route('information-manager.tool.index'),
        ]);
    }

    public function update(UpdateRequest $request, Tool $tool): RedirectResponse
    {
        (new UpdateAction())->execute($tool, Auth::user()->institute, $request->validated());

        flash(trans('message.data-saved'), 'success');

        return redirect()->route('information-manager.tool.index');
    }

    public function publish(PublishRequest $request, Tool $tool): RedirectResponse
    {
        $institute = Auth::user()->institute;

        (new UpdateAction())->execute($tool, $institute, $request->validated());

        (new PublishAction())->execute($tool, $institute);

        flash(trans('message.entity-published', ['entity' => $tool->name]), 'success');

        return redirect()->route('information-manager.tool.index');
    }

    public function unpublish(PublishRequest $request, Tool $tool): RedirectResponse
    {
        $institute = Auth::user()->institute;

        (new UpdateAction())->execute($tool, $institute, $request->validated());

        (new UnpublishAction())->execute($tool, $institute);

        flash(trans('message.entity-unpublished', ['entity' => $tool->name]), 'success');

        return redirect()->route('information-manager.tool.index');
    }

    private function getAlternativeTools(Institute $institute, Tool $originalTool): ?Collection
    {
        $alternativeTools = $originalTool->getAvailableAlternativeTools($institute);

        return $alternativeTools->count() ? $alternativeTools->pluck('name', 'id') : null;
    }
}
