<?php

declare(strict_types=1);

namespace App\Http\Controllers\Other;

use App\Http\Controllers\Controller;
use App\Http\Resources\ExperienceResource;
use App\Http\Resources\FeatureResource;
use App\Http\Resources\InstituteResource;
use App\Http\Resources\Other\ToolIndexResource;
use App\Http\Resources\Other\ToolResource;
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
        $this->authorize('viewAllOther', Tool::class);

        $filter = $request->get('filter');
        $searchTerm = $request->get('search');

        $tools = Tool::forOtherThan($request->user()->institute)
            ->with('experiences')
            ->when($filter['features'] ?? false, fn (Builder $query) => $query->forFeatures($filter['features']))
            ->when($searchTerm, fn (Builder $query) => $query->search($searchTerm))
            ->orderBy('name')
            ->get();

        return Inertia::render('other/tool/Index', [
            'tools' => ToolIndexResource::collection($tools),

            'sidebar' => [
                'features'   => FeatureResource::collection(Feature::all()->sortBy('name')),
                'categories' => null,
            ],
        ]);
    }

    public function show(Tool $tool): Response
    {
        $this->authorize('viewOther', $tool);

        $tool->load(['features', 'institutes']);

        return Inertia::render('other/tool/Show', [
            'tool'        => new ToolResource($tool),
            'experiences' => ExperienceResource::collection($tool->experiences()->latest()->get()),
            'institutes'  => InstituteResource::collection(
                $tool->institutesThatUseTool()->orderBy('full_name')->get()
            ),

            'backUrl' => route('other.tool.index'),
        ]);
    }
}
