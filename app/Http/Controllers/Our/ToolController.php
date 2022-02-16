<?php

declare(strict_types=1);

namespace App\Http\Controllers\Our;

use App\Enums\InstituteTool\Status;
use App\Http\Controllers\Controller;
use App\Http\Resources\CategoryResource;
use App\Http\Resources\ExperienceResource;
use App\Http\Resources\FeatureResource;
use App\Http\Resources\Our\ToolIndexResource;
use App\Http\Resources\Our\ToolResource;
use App\Models\Feature;
use App\Models\Tool;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class ToolController extends Controller
{
    public function index(Request $request): Response
    {
        $this->authorize('viewAllOur', Tool::class);

        $institute = $request->user()->institute;
        $filter = $request->get('filter');
        $searchTerm = $request->get('search');

        $tools = Tool::forOur($institute)
            ->with('experiences')
            ->when($filter['features'] ?? false, fn (Builder $query) => $query->forFeatures($filter['features']))
            ->when($filter['categories'] ?? false, fn (Builder $query) => $query->forCategories($filter['categories']))
            ->when($searchTerm, fn (Builder $query) => $query->search($searchTerm))
            ->orderByArray('status', Status::customOrder())
            ->orderBy('name')
            ->get();

        return Inertia::render('our/tool/Index', [
            'tools' => ToolIndexResource::collection($tools),

            'sidebar' => [
                'features'   => FeatureResource::collection(Feature::all()->sortBy('name')),
                'categories' => CategoryResource::collection($institute->categories),
            ],
        ]);
    }

    public function show(Request $request, Tool $tool): Response
    {
        $this->authorize('viewOur', $tool);

        $tool->load('features');

        return Inertia::render('our/tool/Show', [
            'permissions' => [
                'get-support' => $request->user()->can('getSupport', [Tool::class, $tool]),
            ],
            'tool'        => new ToolResource($tool),
            'experiences' => ExperienceResource::collection($tool->experiences()->latest()->get()),

            'backUrl' => route('our.tool.index'),
        ]);
    }
}
