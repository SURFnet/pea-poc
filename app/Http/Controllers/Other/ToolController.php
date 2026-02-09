<?php

declare(strict_types=1);

namespace App\Http\Controllers\Other;

use App\Http\Controllers\Controller;
use App\Http\Resources\ExperienceResource;
use App\Http\Resources\InstituteResource;
use App\Http\Resources\Other\ToolResource;
use App\Models\Institute;
use App\Models\Tool;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class ToolController extends Controller
{
    public function show(Request $request, Tool $tool): Response|RedirectResponse
    {
        $this->authorize('viewOther', $tool);

        if ($request->fullUrl() !== route('other.tool.show', $tool)) {
            return redirect()->route('other.tool.show', $tool);
        }

        $tool->load(['institutes']);

        return Inertia::render('other/tool/Show', [
            'tool'        => new ToolResource($tool),
            'experiences' => ExperienceResource::collection($tool->experiences()->latest()->get()),
            'institutes'  => InstituteResource::collection(
                Institute::usingTool($tool)->orderBy('short_name')->get()
            ),

            'following' => $request->user()->isFollowingTool($tool),

            'backUrl' => route('tool.index'),
        ]);
    }
}
