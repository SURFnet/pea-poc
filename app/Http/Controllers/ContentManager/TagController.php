<?php

declare(strict_types=1);

namespace App\Http\Controllers\ContentManager;

use App\Actions\Tag\CreateAction;
use App\Actions\Tag\DeleteAction;
use App\Actions\Tag\UpdateAction;
use App\Enums\Tags\TagTypes;
use App\Helpers\Index;
use App\Http\Controllers\Controller;
use App\Http\Requests\IndexRequest;
use App\Http\Requests\Tag\StoreRequest;
use App\Http\Requests\Tag\UpdateRequest;
use App\Http\Resources\InformationManager\TagResource;
use App\Http\Resources\PaginationResource;
use App\Models\Tag;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\RedirectResponse;
use Inertia\Inertia;
use Inertia\Response;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;

class TagController extends Controller
{
    public function index(IndexRequest $request): Response
    {
        $this->authorize('viewAll', Tag::class);

        $tags = QueryBuilder::for(Tag::withoutInstitute())
            ->allowedFilters([
                AllowedFilter::callback('name_en', function (Builder $query, $value): void {
                    $query->whereRaw("LOWER(JSON_EXTRACT(name, '$.en')) LIKE '%" . strtolower($value) . "%'");
                }),
                AllowedFilter::callback('name_nl', function (Builder $query, $value): void {
                    $query->whereRaw("LOWER(JSON_EXTRACT(name, '$.nl')) LIKE '%" . strtolower($value) . "%'");
                }),
                AllowedFilter::exact('type'),
            ]);

        $tags = Index::forTable($tags, $request);

        return Inertia::render('content-manager/tag/Index', [
            'tags' => TagResource::collection($tags)->additional([
                'pagination' => new PaginationResource($tags),
            ]),
            'typesSelect' => TagTypes::getTagsTypesAsSelectExcept([TagTypes::CATEGORIES]),
        ]);
    }

    public function create(): Response
    {
        $this->authorize('create', Tag::class);

        return Inertia::render('content-manager/tag/Create', [
            'tagTypes' => TagTypes::getTagsTypesAsSelectExcept([TagTypes::CATEGORIES]),
        ]);
    }

    public function store(StoreRequest $storeRequest): RedirectResponse
    {
        (new CreateAction())->execute($storeRequest->validated());

        flash(trans('message.data-saved'), 'success');

        return redirect()->route('content-manager.tag.index');
    }

    public function edit(Tag $tag): Response
    {
        $this->authorize('update', $tag);

        return Inertia::render('content-manager/tag/Edit', [
            'tag'      => new TagResource($tag),
            'tagTypes' => TagTypes::getTagsTypesAsSelectExcept([TagTypes::CATEGORIES]),
        ]);
    }

    public function update(UpdateRequest $request, Tag $tag): RedirectResponse
    {
        (new UpdateAction())->execute($tag, $request->validated());

        flash(trans('message.data-saved'), 'success');

        return redirect()->route('content-manager.tag.index');
    }

    public function destroy(Tag $tag): RedirectResponse
    {
        $this->authorize('delete', $tag);

        (new DeleteAction())->execute($tag);

        flash(trans('message.data-saved'), 'success');

        return redirect()->route('content-manager.tag.index');
    }
}
