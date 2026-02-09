<?php

declare(strict_types=1);

namespace App\Http\Controllers\InformationManager;

use App\Actions\CustomTool\CreateAction as CreateCustomToolAction;
use App\Actions\CustomTool\PublishAction as PublishCustomToolAction;
use App\Actions\PendingToolEdit\ClearAction as ClearPendingToolEditAction;
use App\Actions\PendingToolEdit\CreateAction as CreatePendingToolEditAction;
use App\Actions\Tool\Concept\DiscardAction as DiscardConceptToolAction;
use App\Actions\Tool\Concept\PublishAction as PublishConceptToolAction;
use App\Actions\Tool\Concept\SafelyDiscardAction as SafelyDiscardToolConceptAction;
use App\Actions\Tool\Concept\UpdateAction as UpdateConceptToolAction;
use App\Actions\Tool\PublishAction as PublishToolAction;
use App\Enums\InstituteTool\Sort;
use App\Enums\InstituteTool\Status as InstituteToolStatus;
use App\Enums\Tool\Status as ToolStatus;
use App\Helpers\Auth;
use App\Helpers\Country;
use App\Helpers\Index;
use App\Helpers\ToolPrefillData;
use App\Http\Controllers\Concerns\HasToolTags;
use App\Http\Controllers\Controller;
use App\Http\Requests\IndexRequest;
use App\Http\Requests\Tool\PublishRequest;
use App\Http\Requests\Tool\StoreCustomToolRequest;
use App\Http\Requests\Tool\UpdateRequest;
use App\Http\Resources\ContentManager\ConceptToolResource;
use App\Http\Resources\InformationManager\CustomToolIndexResource;
use App\Http\Resources\PaginationResource;
use App\Http\Resources\PendingToolEditResource;
use App\Http\Resources\ToolLogResource;
use App\Http\Resources\ToolResource;
use App\Models\Tool;
use App\Models\ToolLog;
use App\QueryBuilder\Filters\InstituteToolStatusFilter;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\RedirectResponse;
use Inertia\Inertia;
use Inertia\Response;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\AllowedSort;
use Spatie\QueryBuilder\QueryBuilder;

class CustomToolController extends Controller
{
    use HasToolTags;

    public function index(IndexRequest $request): Response
    {
        $this->authorize('manageOur', Tool::class);

        $currentInstitute = Auth::user()->institute;

        $tools = QueryBuilder::for($currentInstitute->customTools())
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

        return Inertia::render('information-manager/custom-tools/Index', [
            'tools' => CustomToolIndexResource::collection($tools)->additional([
                'pagination' => new PaginationResource($tools),
            ]),
            'statusOptions' => [
                ...InstituteToolStatus::asFilterSelect(),
                ...ToolStatus::asSelect(),
            ],
            'sortOptions' => Sort::asSelect(),
            'categories'  => $categories,
        ]);
    }

    public function create(): Response
    {
        $this->authorize('createCustomTool', Tool::class);

        return Inertia::render('information-manager/custom-tools/Create', [
            'countries' => Country::getAsSelect(false),
            'prefills'  => ToolPrefillData::get(),
            ...$this->getToolTags(),
        ]);
    }

    public function store(
        StoreCustomToolRequest $request,
        CreateCustomToolAction $createCustomToolAction,
        bool $continue = false
    ): RedirectResponse {
        $tool = $createCustomToolAction->execute($request->validated(), Auth::user());

        flash(trans('message.data-saved'), 'success');

        if ($continue) {
            return redirect()->route('information-manager.custom-tool.edit', $tool);
        }

        return redirect()->route('information-manager.custom-tool.index');
    }

    public function edit(
        Tool $tool,
        ClearPendingToolEditAction $clearPendingToolEditAction,
        CreatePendingToolEditAction $createPendingToolEditAction,
    ): Response {
        $this->authorize('update', $tool);

        $user = Auth::user();
        $concept = $tool->getOrCreateConceptVersion();
        $pendingEdit = $tool->getCurrentPendingEdit(ignoreUser: $user, forInstitute: $user->institute);

        $clearPendingToolEditAction->execute($tool, $user, $user->institute);

        $createPendingToolEditAction->execute($tool, $user, $user->institute);

        return Inertia::render('information-manager/custom-tools/Edit', [
            'tool'        => new ConceptToolResource($concept),
            'countries'   => Country::getAsSelect(false),
            'pendingEdit' => $pendingEdit ? new PendingToolEditResource($pendingEdit) : null,
            ...$this->getToolTags(),
        ]);
    }

    public function update(
        UpdateRequest $request,
        Tool $tool,
        UpdateConceptToolAction $updateConceptToolAction,
        bool $continue = false,
    ): RedirectResponse {
        $updateConceptToolAction->execute($tool, Auth::user(), $request->validated());

        flash(trans('message.data-saved'), 'success');

        if ($continue) {
            return redirect()->route('information-manager.custom-tool.edit', $tool);
        }

        return redirect()->route('information-manager.custom-tool.index');
    }

    public function publish(
        PublishRequest $request,
        Tool $tool,
        UpdateConceptToolAction $updateConceptToolAction,
        PublishConceptToolAction $publishConceptToolAction,
        PublishToolAction $publishToolAction,
        PublishCustomToolAction $publishCustomToolAction,
    ): RedirectResponse {
        $updateConceptToolAction->execute($tool, Auth::user(), $request->validated());

        $publishConceptToolAction->execute($tool);

        $publishToolAction->execute($tool);

        $publishCustomToolAction->execute($tool, Auth::user(), []);

        flash(trans('message.entity-published', ['entity' => $tool->name]), 'success');

        return redirect()->route('information-manager.custom-tool.index');
    }

    public function cancelEdit(
        Tool $tool,
        ClearPendingToolEditAction $clearPendingToolEditAction,
        SafelyDiscardToolConceptAction $safelyDiscardToolConceptAction,
    ): RedirectResponse {
        $this->authorize('update', $tool);

        $user = Auth::user();

        $clearPendingToolEditAction->execute($tool, $user, $user->institute);

        if ($safelyDiscardToolConceptAction->execute($tool)) {
            flash(trans('message.concept-discarded', [
                'entity' => $tool->name,
            ]), 'success');
        }

        return redirect()->route('information-manager.custom-tool.index');
    }

    public function publishConcept(
        Tool $tool,
        PublishConceptToolAction $publishConceptToolAction,
    ): RedirectResponse {
        $this->authorize('publishConcept', $tool);

        $publishConceptToolAction->execute($tool);

        flash()->success(trans('message.concept-published', ['entity' => $tool->name]));

        return redirect()->route('information-manager.custom-tool.index');
    }

    public function discardConcept(
        Tool $tool,
        DiscardConceptToolAction $discardAction,
    ): RedirectResponse {
        $this->authorize('publishConcept', $tool);

        $discardAction->execute($tool);

        flash()->success(trans('message.concept-discarded', ['entity' => $tool->name]));

        return redirect()->route('information-manager.custom-tool.index');
    }

    public function log(IndexRequest $request, Tool $tool): Response
    {
        $this->authorize('update', $tool);

        $logQuery = ToolLog::forTool($tool)->latest();
        $logs = Index::forTable($logQuery, $request);

        return Inertia::render('content-manager/tool/Log', [
            'tool' => new ToolResource($tool),
            'logs' => ToolLogResource::collection($logs)->additional([
                'pagination' => new PaginationResource($logs),
            ]),
        ]);
    }

    public function destroy(Tool $tool): RedirectResponse
    {
        $this->authorize('delete', $tool);

        $tool->delete();

        flash(trans('message.entity-deleted', ['entity' => $tool->name]), 'success');

        return redirect()->route('information-manager.custom-tool.index');
    }
}
