<?php

declare(strict_types=1);

namespace App\Http\Controllers\InformationManager;

use App\Actions\Institute\Tool\AddAction;
use App\Actions\Institute\Tool\Concept\DiscardAction as DiscardConceptAction;
use App\Actions\Institute\Tool\Concept\PublishAction as PublishConceptAction;
use App\Actions\Institute\Tool\Concept\SafelyDiscardAction;
use App\Actions\Institute\Tool\Concept\UpdateAction as UpdateConceptAction;
use App\Actions\Institute\Tool\PublishAction;
use App\Actions\Institute\Tool\UnpublishAction;
use App\Actions\PendingToolEdit\ClearAction;
use App\Actions\PendingToolEdit\CreateAction as CreatePendingEditAction;
use App\Actions\Tool\SubmitRequestForChangeAction;
use App\Enums\InstituteTool\DataClassification;
use App\Enums\InstituteTool\Sort;
use App\Enums\InstituteTool\Status;
use App\Helpers\Auth;
use App\Helpers\Index;
use App\Http\Controllers\Controller;
use App\Http\Requests\IndexRequest;
use App\Http\Requests\InstituteTool\PublishRequest;
use App\Http\Requests\InstituteTool\StoreRequest;
use App\Http\Requests\InstituteTool\UpdateRequest;
use App\Http\Requests\RequestForChangeRequest;
use App\Http\Resources\InformationManager\CustomFieldValueResource;
use App\Http\Resources\InformationManager\InstituteToolResource;
use App\Http\Resources\InformationManager\ToolIndexResource;
use App\Http\Resources\Our\ToolResource;
use App\Http\Resources\PaginationResource;
use App\Http\Resources\PendingToolEditResource;
use App\Http\Resources\TagResource;
use App\Http\Resources\ToolLogResource;
use App\Models\InstituteTool;
use App\Models\Tool;
use App\Models\ToolLog;
use App\QueryBuilder\Filters\InstituteToolStatusFilter;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\AllowedSort;
use Spatie\QueryBuilder\QueryBuilder;

class ToolController extends Controller
{
    public function index(IndexRequest $request): Response
    {
        $this->authorize('manageOur', Tool::class);

        $currentInstitute = Auth::user()->institute;

        $tools = QueryBuilder::for($currentInstitute->tools())
            ->allowedFilters([
                AllowedFilter::partial('name'),
                AllowedFilter::callback('description_short', function (Builder $query, string $value): void {
                    $query->searchLocalizedField('description_short', $value);
                }),
                AllowedFilter::scope('category', 'forCategory'),
                AllowedFilter::callback('status', new InstituteToolStatusFilter()),
            ])
            ->allowedSorts(AllowedSort::field('updated_at', 'pivot_updated_at'))
            ->defaultSort('name');

        $tools = Index::forTable($tools, $request);

        $categories = [];
        foreach ($currentInstitute->categories() as $category) {
            $categories[$category->id] = $category['name'];
        }

        return Inertia::render('information-manager/tools/Index', [
            'tools' => ToolIndexResource::collection($tools)->additional([
                'pagination' => new PaginationResource($tools),
            ]),
            'statusOptions' => Status::asFilterSelect(),
            'sortOptions'   => Sort::asSelect(),
            'categories'    => $categories,
        ]);
    }

    public function create(Tool $tool, Request $request): Response
    {
        $this->authorize('addToInstitute', $tool);

        $institute = Auth::user()->institute;

        $defaultStatus = null;
        if ($request->get('status') && in_array($request->get('status'), Status::toArray())) {
            $defaultStatus = $request->get('status');
        }

        return Inertia::render('information-manager/tools/Create', [
            'categories'          => TagResource::collection($institute->categories()),
            'statusOptions'       => Status::asSelect(),
            'dataClassifications' => DataClassification::asSelect(),
            'tool'                => new ToolResource($tool),
            'customFields'        => CustomFieldValueResource::collection($institute->customFields),
            'backUrl'             => route('other.tool.show', $tool), 'alternativeTools' => ToolResource::collection(
                InstituteTool::forInstitute($institute)
                    ->whereIn('status', [Status::ALLOWED_UNDER_CONDITIONS, Status::ALLOWED])
                    ->get()
                    ->pluck('tool')
            ),
            'defaultStatus' => $defaultStatus,
        ]);
    }

    public function store(StoreRequest $request, Tool $tool, bool $continue = false): RedirectResponse
    {
        $user = Auth::user();

        (new AddAction())->execute($tool, $user->institute, $request->validated(), $user);

        flash(trans('message.data-saved'), 'success');

        if ($continue) {
            return redirect()->route('information-manager.tool.edit', $tool);
        }

        return redirect()->route('information-manager.tool.index');
    }

    public function edit(Tool $tool): Response
    {
        $this->authorize('updateForInstitute', $tool);

        $user = Auth::user();
        $institute = $user->institute;

        $instituteTool = InstituteTool::forTool($tool)->forInstitute($institute)->firstOrFail();
        $concept = $instituteTool->getOrCreateConceptVersion();

        $pendingEdit = $tool->getCurrentPendingEdit(ignoreUser: $user, forInstitute: $institute);

        (new ClearAction())->execute($tool, $user, $institute);
        (new CreatePendingEditAction())->execute($tool, $user, $institute);

        return Inertia::render('information-manager/tools/Edit', [
            'categories'          => TagResource::collection($institute->categories()),
            'statusOptions'       => Status::asSelect(),
            'dataClassifications' => DataClassification::asSelect(),
            'instituteTool'       => new InstituteToolResource($concept),
            'tool'                => new ToolResource($tool),
            'backUrl'             => route('information-manager.tool.index'),
            'pendingEdit'         => $pendingEdit
                ? new PendingToolEditResource($pendingEdit) : null, 'alternativeTools' => ToolResource::collection(
                    InstituteTool::forInstitute($institute)
                    ->whereIn('status', [Status::ALLOWED_UNDER_CONDITIONS, Status::ALLOWED])
                    ->get()
                    ->pluck('tool')
                ),
        ]);
    }

    public function update(UpdateRequest $request, Tool $tool, bool $continue = false): RedirectResponse
    {
        $user = Auth::user();
        (new UpdateConceptAction())->execute($tool, $user, $request->validated());

        flash(trans('message.data-saved'), 'success');

        if ($continue) {
            return redirect()->route('information-manager.tool.edit', $tool);
        }

        return redirect()->route('information-manager.tool.index');
    }

    public function publish(PublishRequest $request, PublishConceptAction $publishAction, Tool $tool): RedirectResponse
    {
        $user = Auth::user();
        $institute = $user->institute;

        (new UpdateConceptAction())->execute($tool, $user, $request->validated());

        $publishAction->execute($tool, $institute);

        (new PublishAction())->execute($tool, $institute);

        flash(trans('message.entity-published', [
            'entity' => $tool->name,
        ]), 'success');

        return redirect()->route('information-manager.tool.index');
    }

    public function unpublish(PublishRequest $request, Tool $tool): RedirectResponse
    {
        $user = Auth::user();
        $institute = $user->institute;

        (new UpdateConceptAction())->execute($tool, $user, $request->validated());

        (new UnpublishAction())->execute($tool, $institute);

        flash(trans('message.entity-unpublished', [
            'entity' => $tool->name,
        ]), 'success');

        return redirect()->route('information-manager.tool.index');
    }

    public function publishConcept(PublishConceptAction $publishAction, Tool $tool): RedirectResponse
    {
        $this->authorize('publishConceptForInstitute', $tool);

        $publishAction->execute($tool, Auth::user()->institute);

        flash(trans('message.concept-published', [
            'entity' => $tool->name,
        ]), 'success');

        return redirect()->route('information-manager.tool.index');
    }

    public function discardConcept(Tool $tool): RedirectResponse
    {
        $this->authorize('discardConceptForInstitute', $tool);

        (new DiscardConceptAction())->execute($tool, Auth::user()->institute);

        flash(trans('message.concept-discarded', [
            'entity' => $tool->name,
        ]), 'success');

        return redirect()->route('information-manager.tool.index');
    }

    public function log(Tool $tool, IndexRequest $request): Response
    {
        $this->authorize('updateForInstitute', $tool);

        $logQuery = ToolLog::forTool($tool)->forInstitute(Auth::user()->institute)->latest();
        $logs = Index::forTable($logQuery, $request);

        return Inertia::render('information-manager/tools/Log', [
            'tool' => new ToolResource($tool),
            'logs' => ToolLogResource::collection($logs)->additional([
                'pagination' => new PaginationResource($logs),
            ]),
        ]);
    }

    public function cancelEdit(Tool $tool, ClearAction $clearAction): RedirectResponse
    {
        $this->authorize('updateForInstitute', $tool);

        $user = Auth::user();

        $clearAction->execute($tool, $user, $user->institute);

        if ((new SafelyDiscardAction())->execute($tool, $user->institute)) {
            flash(trans('message.concept-discarded', [
                'entity' => $tool->name,
            ]), 'success');
        }

        return redirect()->route('information-manager.tool.index');
    }

    public function requestForChange(
        RequestForChangeRequest $request,
        Tool $tool,
        SubmitRequestForChangeAction $action
    ): RedirectResponse {
        $action->execute($tool, $request->user(), $request->request_for_change);

        flash(trans('message.request_for_change_sent'), 'success');

        return redirect()->back();
    }
}
