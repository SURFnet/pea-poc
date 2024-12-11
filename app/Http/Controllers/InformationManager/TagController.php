<?php

declare(strict_types=1);

namespace App\Http\Controllers\InformationManager;

use App\Actions\Institute\Tag\CreateAction;
use App\Actions\Institute\Tag\DeleteAction;
use App\Actions\Institute\Tag\UpdateAction;
use App\Enums\Tags\TagTypes;
use App\Helpers\Auth;
use App\Helpers\Index;
use App\Http\Controllers\Controller;
use App\Http\Requests\IndexRequest;
use App\Http\Requests\InstituteTag\StoreRequest;
use App\Http\Requests\InstituteTag\UpdateRequest;
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
        $this->authorize('manageOur', Tag::class);

        $tags = QueryBuilder::for(Tag::forInstitute(Auth::user()->institute)->where('type', TagTypes::CATEGORIES))
            ->allowedFilters([
                AllowedFilter::callback('name_en', function (Builder $query, $value): void {
                    $query->where('name->en', 'LIKE', '%' . $value . '%');
                }),
                AllowedFilter::callback('name_nl', function (Builder $query, $value): void {
                    $query->where('name->nl', 'LIKE', '%' . $value . '%');
                }),
            ]);

        $tags = Index::forTable($tags, $request);

        return Inertia::render('information-manager/tag/Index', [
            'tags' => TagResource::collection($tags)->additional([
                'pagination' => new PaginationResource($tags),
            ]),
            'typesSelect' => TagTypes::asSelect(),
        ]);
    }

    public function create(): Response
    {
        $this->authorize('createForInstitute', Tag::class);

        return Inertia::render('information-manager/tag/Create', [
            'tagTypes' => TagTypes::asSelect(),
        ]);
    }

    public function store(StoreRequest $storeRequest): RedirectResponse
    {
        (new CreateAction())->execute($storeRequest->validated(), Auth::user()->institute);

        flash(trans('message.data-saved'), 'success');

        return redirect()->route('information-manager.tag.index');
    }

    public function edit(Tag $tag): Response
    {
        $this->authorize('updateForInstitute', $tag);

        return Inertia::render('information-manager/tag/Edit', [
            'tag'      => new TagResource($tag),
            'tagTypes' => TagTypes::asSelect(),
        ]);
    }

    public function update(UpdateRequest $request, Tag $tag): RedirectResponse
    {
        (new UpdateAction())->execute($tag, $request->validated());

        flash(trans('message.data-saved'), 'success');

        return redirect()->route('information-manager.tag.index');
    }

    public function destroy(Tag $tag): RedirectResponse
    {
        $this->authorize('deleteForInstitute', $tag);

        (new DeleteAction())->execute($tag);

        flash(trans('message.data-saved'), 'success');

        return redirect()->route('information-manager.tag.index');
    }
}
