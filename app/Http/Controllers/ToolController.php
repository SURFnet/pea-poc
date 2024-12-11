<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Actions\Tool\ChangeFollowingStatusAction;
use App\Enums\Tags\TagTypes;
use App\Helpers\Index;
use App\Http\Requests\IndexRequest;
use App\Http\Resources\PaginationResource;
use App\Http\Resources\TagResource;
use App\Http\Resources\ToolIndexResource;
use App\Models\Institute;
use App\Models\Tag;
use App\Models\Tool;
use App\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;
use Mcamara\LaravelLocalization\Facades\LaravelLocalization;

class ToolController extends Controller
{
    public function index(IndexRequest $request, string $tags = null): Response
    {
        $this->authorize('viewAllWithinInstitute', Tool::class);

        $user = $request->user();
        $institute = $user->institute;
        $searchTerm = $request->get('search');
        $filterableTagTypes = $this->getFilterableTagTypes($user);
        $tagIds = $tags ? $this->getTagIdsToFilter($tags, $filterableTagTypes, $institute) : [];

        $toolQuery = Tool::getToolsQueryForOverview($institute, $tagIds, $searchTerm ?? '');
        $totalCount = $toolQuery->count();
        $toolCountWithoutFilterOrSearch = Tool::getToolsQueryForOverview($institute, [], '')->count();

        $tools = Index::forTable($toolQuery, $request);

        return Inertia::render('tool/Index', [
            'tools' => ToolIndexResource::collection($tools)->additional([
                'pagination' => new PaginationResource($tools),
            ]),
            'totalToolCount'                 => $totalCount,
            'toolCountWithoutFilterOrSearch' => $toolCountWithoutFilterOrSearch,
            'selectedTagFilters'             => $this->getGroupedTagList(
                $filterableTagTypes,
                Tag::whereIn('id', $tagIds)
            ),

            'sidebar' => [
                'tags' => $this->getGroupedTagList($filterableTagTypes, Tag::accessibleForInstitute($institute)),
            ],
        ]);
    }

    public function changeFollowingStatus(
        Request $request,
        Tool $tool,
        ChangeFollowingStatusAction $action
    ): RedirectResponse {
        $user = $request->user();

        $action->execute($tool, $user);

        $following = $user->fresh()->isFollowingTool($tool);

        flash(trans($following ? 'message.following-tool' : 'message.stopped-following-tool'), 'success');

        return redirect()->back();
    }

    private function getGroupedTagList(array $tagTypes, Builder $tagQuery): array
    {
        $groupedTags = [];
        foreach ($tagTypes as $tagType) {
            $tagsForType = $tagQuery->clone()->where('type', $tagType)->get();

            if ($tagsForType->isEmpty()) {
                continue;
            }

            $groupedTags[$tagType] = TagResource::collection($tagsForType);
        }

        return $groupedTags;
    }

    private function getTagIdsToFilter(string $tagsFromUrl, array $tagTypes, Institute $institute): array
    {
        $locale = LaravelLocalization::getCurrentLocale();
        $tagIds = [];

        foreach (explode('/', $tagsFromUrl) as $tagTypePart) {
            if (!str_contains($tagTypePart, ':')) {
                continue;
            }

            [$tagType, $tagSlugsCsv] = explode(':', $tagTypePart);
            if (!in_array($tagType, $tagTypes)) {
                continue;
            }

            $tagSlugs = explode(',', $tagSlugsCsv);

            $tagIds = [
                ...$tagIds,
                ...Tag::select('id')
                    ->whereSlugIn($tagSlugs, $locale)
                    ->where('type', $tagType)
                    ->accessibleForInstitute($institute)
                    ->pluck('id')
                    ->toArray(),
            ];
        }

        return $tagIds;
    }

    private function getFilterableTagTypes(User $user): array
    {
        return array_filter(TagTypes::toArray(), fn ($tagType) => $user->can('filter-by-tag-type', $tagType));
    }
}
