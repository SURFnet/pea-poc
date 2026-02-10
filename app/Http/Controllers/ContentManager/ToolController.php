<?php

declare(strict_types=1);

namespace App\Http\Controllers\ContentManager;

use App\Actions\CustomTool\ConvertToGenericToolAction;
use App\Actions\PendingToolEdit\ClearAction;
use App\Actions\PendingToolEdit\CreateAction as CreatePendingEditAction;
use App\Actions\Tool\Concept\DiscardAction;
use App\Actions\Tool\Concept\PublishAction as PublishConceptAction;
use App\Actions\Tool\Concept\SafelyDiscardAction;
use App\Actions\Tool\Concept\UpdateAction as UpdateConceptAction;
use App\Actions\Tool\CreateAction;
use App\Actions\Tool\PublishAction;
use App\Enums\Tags\TagTypes;
use App\Enums\Tool\Status;
use App\Helpers\Auth;
use App\Helpers\Country;
use App\Helpers\Index;
use App\Helpers\ToolPrefillData;
use App\Http\Controllers\Concerns\HasToolTags;
use App\Http\Controllers\Controller;
use App\Http\Requests\IndexRequest;
use App\Http\Requests\Tool\PublishRequest;
use App\Http\Requests\Tool\StoreRequest;
use App\Http\Requests\Tool\UpdateRequest;
use App\Http\Resources\ContentManager\ConceptToolResource;
use App\Http\Resources\ContentManager\ToolIndexResource;
use App\Http\Resources\ContentManager\ToolResource;
use App\Http\Resources\ExperienceResource;
use App\Http\Resources\InstituteResource;
use App\Http\Resources\PaginationResource;
use App\Http\Resources\PendingToolEditResource;
use App\Http\Resources\ToolLogResource;
use App\Models\Tag;
use App\Models\Tool;
use App\Models\ToolLog;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\App;
use Inertia\Inertia;
use Inertia\Response;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;

class ToolController extends Controller
{
    use HasToolTags;

    public function index(IndexRequest $request): Response
    {
        $this->authorize('viewAll', Tool::class);

        $tools = QueryBuilder::for(Tool::query())
            ->allowedFilters([
                AllowedFilter::partial('name'),
                AllowedFilter::scope('status', 'forStatus'),
                AllowedFilter::scope('feature', 'forFeature'),
                AllowedFilter::scope('is_custom', 'custom'),
            ])
            ->orderBy('name');

        $tools = Index::forTable($tools, $request);

        $featuresAsSelect = [];
        foreach (Tag::whereType(TagTypes::FEATURES)->get() as $feature) {
            $featuresAsSelect[$feature->id] = $feature->getTranslation('name', App::getLocale());
        }

        return Inertia::render('content-manager/tool/Index', [
            'tools' => ToolIndexResource::collection($tools)->additional([
                'pagination' => new PaginationResource($tools),
            ]),
            'features'      => $featuresAsSelect,
            'statusOptions' => Status::asSelect(),
        ]);
    }

    public function show(Tool $tool): Response
    {
        $this->authorize('view', $tool);

        return Inertia::render('content-manager/tool/Show', [
            'tool'        => new ToolResource($tool),
            'experiences' => ExperienceResource::collection($tool->experiences()->latest()->get()),
            'institute'   => new InstituteResource($tool->institute),

            'backUrl' => route('content-manager.tool.index'),
        ]);
    }

    public function create(): Response
    {
        $this->authorize('create', Tool::class);

        return Inertia::render('content-manager/tool/Create', [
            ...$this->getToolTags(),
            'countries' => Country::getAsSelect(false),
            'prefills'  => ToolPrefillData::get(),
        ]);
    }

    public function store(StoreRequest $request, bool $continue = false): RedirectResponse
    {
        $tool = (new CreateAction())->execute($request->validated(), Auth::user());

        flash(trans('message.data-saved'), 'success');

        if ($continue) {
            return redirect()->route('content-manager.tool.edit', $tool);
        }

        return redirect()->route('content-manager.tool.index');
    }

    public function edit(Tool $tool): Response
    {
        $this->authorize('update', $tool);

        $concept = $tool->getOrCreateConceptVersion();

        $user = Auth::user();
        $pendingEdit = $tool->getCurrentPendingEdit(ignoreUser: $user);

        (new ClearAction())->execute($tool, $user);
        (new CreatePendingEditAction())->execute($tool, $user);

        return Inertia::render('content-manager/tool/Edit', [
            'tool' => new ConceptToolResource($concept),
            ...$this->getToolTags(),
            'countries'   => Country::getAsSelect(false),
            'pendingEdit' => $pendingEdit ? new PendingToolEditResource($pendingEdit) : null,
        ]);
    }

    public function update(UpdateRequest $request, Tool $tool, bool $continue = false): RedirectResponse
    {
        (new UpdateConceptAction())->execute($tool, Auth::user(), $request->validated());

        flash(trans('message.data-saved'), 'success');

        if ($continue) {
            return redirect()->route('content-manager.tool.edit', $tool);
        }

        return redirect()->route('content-manager.tool.index');
    }

    public function publish(PublishRequest $request, PublishConceptAction $publishAction, Tool $tool): RedirectResponse
    {
        (new UpdateConceptAction())->execute($tool, Auth::user(), $request->validated());

        $publishAction->execute($tool);

        (new PublishAction())->execute($tool);

        flash(trans('message.entity-published', ['entity' => $tool->name]), 'success');

        return redirect()->route('content-manager.tool.index');
    }

    public function publishConcept(PublishConceptAction $publishAction, Tool $tool): RedirectResponse
    {
        $this->authorize('publishConcept', $tool);

        $publishAction->execute($tool);

        flash(trans('message.concept-published', ['entity' => $tool->name]), 'success');

        return redirect()->route('content-manager.tool.index');
    }

    public function discardConcept(Tool $tool): RedirectResponse
    {
        $this->authorize('discardConcept', $tool);

        (new DiscardAction())->execute($tool);

        flash(trans('message.concept-discarded', ['entity' => $tool->name]), 'success');

        return redirect()->route('content-manager.tool.index');
    }

    public function log(Tool $tool, IndexRequest $request): Response
    {
        $this->authorize('update', $tool);

        $logQuery = ToolLog::forTool($tool)->missingInstitute()->latest();
        $logs = Index::forTable($logQuery, $request);

        return Inertia::render('content-manager/tool/Log', [
            'tool' => new ToolResource($tool),
            'logs' => ToolLogResource::collection($logs)->additional([
                'pagination' => new PaginationResource($logs),
            ]),
        ]);
    }

    public function cancelEdit(Tool $tool, ClearAction $clearAction): RedirectResponse
    {
        $this->authorize('update', $tool);

        $user = Auth::user();

        $clearAction->execute($tool, $user);

        if ((new SafelyDiscardAction())->execute($tool)) {
            flash(trans('message.concept-discarded', [
                'entity' => $tool->name,
            ]), 'success');
        }

        return redirect()->route('content-manager.tool.index');
    }

    public function convert(Tool $tool, ConvertToGenericToolAction $convertAction): RedirectResponse
    {
        $this->authorize('convert', $tool);

        $newTool = $convertAction->execute($tool);

        flash(trans('message.tool-converted', ['entity' => $tool->name]), 'success');

        return redirect()->route('content-manager.tool.edit', $newTool);
    }
}
