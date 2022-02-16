<?php

declare(strict_types=1);

namespace App\Http\Controllers\InformationManager;

use App\Actions\Institute\Tool\AddAction;
use App\Actions\Institute\Tool\PublishAction;
use App\Actions\Institute\Tool\UnpublishAction;
use App\Actions\Institute\Tool\UpdateAction;
use App\Enums\InstituteTool\Status;
use App\Helpers\Auth;
use App\Helpers\Index;
use App\Http\Controllers\Controller;
use App\Http\Requests\IndexRequest;
use App\Http\Requests\InstituteTool\PublishRequest;
use App\Http\Requests\InstituteTool\StoreRequest;
use App\Http\Requests\InstituteTool\UpdateRequest;
use App\Http\Resources\InformationManager\InstituteToolResource;
use App\Http\Resources\InformationManager\ToolIndexResource;
use App\Http\Resources\InstituteResource;
use App\Http\Resources\Our\ToolResource;
use App\Http\Resources\PaginationResource;
use App\Models\Tool;
use App\QueryBuilder\Filters\InstituteToolStatusFilter;
use Illuminate\Http\RedirectResponse;
use Inertia\Inertia;
use Inertia\Response;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;

class ToolController extends Controller
{
    public function index(IndexRequest $request): Response
    {
        $this->authorize('manageOur', Tool::class);

        $currentInstitute = Auth::user()->institute;
        $tools = QueryBuilder::for($currentInstitute->toolsWithCategories())
            ->allowedFilters([
                'name',
                'description_short',
                AllowedFilter::scope('category', 'forCategory'),
                AllowedFilter::callback('status', new InstituteToolStatusFilter()),
            ])
            ->orderBy('name');

        $tools = Index::forTable($tools, $request, 10);

        return Inertia::render('information-manager/tools/Index', [
            'tools' => ToolIndexResource::collection($tools)->additional([
                'pagination' => new PaginationResource($tools),
            ]),
            'categoryOptions' => $currentInstitute->categories->pluck('name', 'id'),
            'statusOptions'   => Status::asFilterSelect(),
        ]);
    }

    public function create(Tool $tool): Response
    {
        $this->authorize('addToInstitute', $tool);

        $institute = Auth::user()->institute;

        return Inertia::render('information-manager/tools/Create', [
            'categories'    => $institute->categories->pluck('name', 'id'),
            'institute'     => new InstituteResource($institute),
            'statusOptions' => Status::asSelect(),
            'tool'          => new ToolResource($tool),
            'backUrl'       => route('other.tool.show', $tool),
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

        return Inertia::render('information-manager/tools/Edit', [
            'categories'    => $institute->categories->pluck('name', 'id'),
            'institute'     => new InstituteResource($institute),
            'statusOptions' => Status::asSelect(),
            'tool'          => new ToolResource($tool),
            'instituteTool' => new InstituteToolResource($institute->tools()->find($tool)->pivot),
            'backUrl'       => route('information-manager.tool.index'),
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
}
