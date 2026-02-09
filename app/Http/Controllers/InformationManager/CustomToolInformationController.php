<?php

declare(strict_types=1);

namespace App\Http\Controllers\InformationManager;

use App\Actions\Institute\Tool\Concept\DiscardAction;
use App\Actions\Institute\Tool\Concept\PublishAction as PublishConceptInstituteToolAction;
use App\Actions\Institute\Tool\Concept\SafelyDiscardAction;
use App\Actions\Institute\Tool\Concept\UpdateAction as UpdateConceptInstituteToolAction;
use App\Actions\Institute\Tool\PublishAction as PublishInstituteToolAction;
use App\Actions\Institute\Tool\UnpublishAction as UnpublishInstituteToolConceptAction;
use App\Actions\PendingToolEdit\ClearAction;
use App\Actions\PendingToolEdit\ClearAction as ClearPendingToolEditAction;
use App\Actions\PendingToolEdit\CreateAction as CreatePendingToolEditAction;
use App\Enums\InstituteTool\DataClassification;
use App\Enums\InstituteTool\Status;
use App\Helpers\Auth;
use App\Http\Controllers\Controller;
use App\Http\Requests\InstituteTool\PublishRequest;
use App\Http\Requests\InstituteTool\UpdateRequest;
use App\Http\Resources\InformationManager\InstituteToolResource;
use App\Http\Resources\Our\ToolResource;
use App\Http\Resources\PendingToolEditResource;
use App\Http\Resources\TagResource;
use App\Models\InstituteTool;
use App\Models\Tool;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\RedirectResponse;
use Inertia\Inertia;
use Inertia\Response;

class CustomToolInformationController extends Controller
{
    public function edit(
        Tool $tool,
        ClearPendingToolEditAction $clearAction,
        CreatePendingToolEditAction $createAction,
    ): Response {
        $user = Auth::user();
        $user->load(['institute']);

        $this->authorize('update', $tool);

        $instituteTool = InstituteTool::forTool($tool)->forInstitute($user->institute)->firstOrFail();
        $concept = $instituteTool->getOrCreateConceptVersion();

        $pendingEdit = $tool->getCurrentPendingEdit(ignoreUser: $user, forInstitute: $user->institute);

        $clearAction->execute($tool, $user, $user->institute);
        $createAction->execute($tool, $user, $user->institute);

        return Inertia::render('information-manager/custom-tools/information/Edit', [
            'categories'          => TagResource::collection($user->institute->categories()),
            'statusOptions'       => Status::asSelect(),
            'dataClassifications' => DataClassification::asSelect(),
            'instituteTool'       => new InstituteToolResource($concept),
            'tool'                => new ToolResource($tool),
            'backUrl'             => route('information-manager.tool.index'),
            'pendingEdit'         => $pendingEdit
                ? new PendingToolEditResource($pendingEdit)
                : null,
            'alternativeTools' => ToolResource::collection(
                Tool::query()->whereHas('instituteTools', function (Builder $query) use ($user): void {
                    $query->where('institute_id', $user->institute->id)
                        ->whereIn('status', [Status::ALLOWED_UNDER_CONDITIONS, Status::ALLOWED]);
                })->get()
            ),
        ]);
    }

    public function update(
        UpdateRequest $request,
        Tool $tool,
        UpdateConceptInstituteToolAction $updateAction,
        bool $continue = false
    ): RedirectResponse {
        $updateAction->execute($tool, Auth::user(), $request->validated());

        flash()->success(trans('message.data-saved'));

        if ($continue) {
            return redirect()->route('information-manager.custom-tool.information.edit', $tool);
        }

        return redirect()->route('information-manager.custom-tool.index');
    }

    public function publish(
        PublishRequest $request,
        Tool $tool,
        UpdateConceptInstituteToolAction $updateAction,
        PublishConceptInstituteToolAction $publishConceptAction,
        PublishInstituteToolAction $publishAction,
    ): RedirectResponse {
        $updateAction->execute($tool, Auth::user(), $request->validated());

        $publishConceptAction->execute($tool, Auth::user()->institute);

        $publishAction->execute($tool, Auth::user()->institute);

        flash()->success(trans('message.entity-published', [
            'entity' => $tool->name,
        ]));

        return redirect()->route('information-manager.custom-tool.index');
    }

    public function unpublish(
        PublishRequest $request,
        Tool $tool,
        UpdateConceptInstituteToolAction $updateAction,
        UnpublishInstituteToolConceptAction $unpublishAction,
    ): RedirectResponse {
        $user = Auth::user();

        $updateAction->execute($tool, $user, $request->validated());

        $unpublishAction->execute($tool, $user->institute);

        flash()->success(trans('message.entity-unpublished', [
            'entity' => $tool->name,
        ]));

        return redirect()->route('information-manager.custom-tool.index');
    }

    public function publishConcept(
        Tool $tool,
        PublishConceptInstituteToolAction $publishAction,
    ): RedirectResponse {
        $instituteTool = InstituteTool::forTool($tool)
            ->forInstitute(Auth::user()->institute)
            ->firstOrFail();

        $this->authorize('publishConceptForInstitute', [$tool, $instituteTool]);

        $publishAction->execute($tool, Auth::user()->institute);

        flash()->success(trans('message.concept-published', [
            'entity' => $tool->name,
        ]));

        return redirect()->route('information-manager.custom-tool.index');
    }

    public function discardConcept(Tool $tool, DiscardAction $discardAction): RedirectResponse
    {
        $instituteTool = InstituteTool::forTool($tool)
            ->forInstitute(Auth::user()->institute)
            ->firstOrFail();

        $this->authorize('publishConceptForInstitute', [$tool, $instituteTool]);

        $discardAction->execute($tool, Auth::user()->institute);

        flash()->success(trans('message.concept-discarded', [
            'entity' => $tool->name,
        ]));

        return redirect()->route('information-manager.custom-tool.index');
    }

    public function cancelEdit(
        Tool $tool,
        ClearAction $clearAction,
        SafelyDiscardAction $safelyDiscardAction,
    ): RedirectResponse {
        $this->authorize('update', $tool);

        $user = Auth::user();

        $clearAction->execute($tool, $user, $user->institute);

        if ($safelyDiscardAction->execute($tool, $user->institute)) {
            flash()->success(trans('message.concept-discarded', [
                'entity' => $tool->name,
            ]));
        }

        return redirect()->route('information-manager.custom-tool.index');
    }
}
