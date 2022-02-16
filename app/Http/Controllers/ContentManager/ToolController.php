<?php

declare(strict_types=1);

namespace App\Http\Controllers\ContentManager;

use App\Actions\Tool\CreateAction;
use App\Actions\Tool\PublishAction;
use App\Actions\Tool\UpdateAction;
use App\Enums\Tool\AuthenticationMethod;
use App\Enums\Tool\Status;
use App\Enums\Tool\StoredData;
use App\Enums\Tool\SupportedStandard;
use App\Helpers\Index;
use App\Http\Controllers\Controller;
use App\Http\Requests\IndexRequest;
use App\Http\Requests\Tool\PublishRequest;
use App\Http\Requests\Tool\StoreRequest;
use App\Http\Requests\Tool\UpdateRequest;
use App\Http\Resources\ContentManager\ToolIndexResource;
use App\Http\Resources\ContentManager\ToolResource;
use App\Http\Resources\PaginationResource;
use App\Models\Feature;
use App\Models\Tool;
use Illuminate\Http\RedirectResponse;
use Inertia\Inertia;
use Inertia\Response;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;

class ToolController extends Controller
{
    public function index(IndexRequest $request): Response
    {
        $this->authorize('viewAll', Tool::class);

        $tools = QueryBuilder::for(Tool::query()->with('features'))
            ->allowedFilters([
                'name',
                AllowedFilter::partial('feature', 'features.name'),
                AllowedFilter::scope('status', 'forStatus'),
            ])
            ->orderBy('name');

        $tools = Index::forTable($tools, $request, 10);

        return Inertia::render('content-manager/tool/Index', [
            'tools' => ToolIndexResource::collection($tools)->additional([
                'pagination' => new PaginationResource($tools),
            ]),
            'statusOptions' => Status::asSelect(),
        ]);
    }

    public function create(): Response
    {
        $this->authorize('create', Tool::class);

        return Inertia::render('content-manager/tool/Create', [
            'features'              => Feature::pluck('name', 'id'),
            'authenticationMethods' => AuthenticationMethod::asSelect(),
            'storedData'            => StoredData::asSelect(),
            'supportedStandards'    => SupportedStandard::asSelect(),
        ]);
    }

    public function store(StoreRequest $request): RedirectResponse
    {
        (new CreateAction())->execute($request->validated());

        flash(trans('message.data-saved'), 'success');

        return redirect()->route('content-manager.tool.index');
    }

    public function edit(Tool $tool): Response
    {
        $this->authorize('update', $tool);

        $tool->load('features');

        return Inertia::render('content-manager/tool/Edit', [
            'tool'                  => new ToolResource($tool),
            'features'              => Feature::pluck('name', 'id'),
            'authenticationMethods' => AuthenticationMethod::asSelect(),
            'storedData'            => StoredData::asSelect(),
            'supportedStandards'    => SupportedStandard::asSelect(),
        ]);
    }

    public function update(UpdateRequest $request, Tool $tool): RedirectResponse
    {
        (new UpdateAction())->execute($tool, $request->validated());

        flash(trans('message.data-saved'), 'success');

        return redirect()->route('content-manager.tool.index');
    }

    public function publish(PublishRequest $request, Tool $tool): RedirectResponse
    {
        (new UpdateAction())->execute($tool, $request->validated());

        (new PublishAction())->execute($tool);

        flash(trans('message.entity-published', ['entity' => $tool->name]), 'success');

        return redirect()->route('content-manager.tool.index');
    }
}
