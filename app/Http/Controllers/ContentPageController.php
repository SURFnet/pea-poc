<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Actions\ContentPage\CreateAction;
use App\Actions\ContentPage\DeleteAction;
use App\Actions\ContentPage\UpdateAction;
use App\Helpers\Index;
use App\Http\Requests\ContentPage\StoreRequest;
use App\Http\Requests\ContentPage\UpdateRequest;
use App\Http\Requests\IndexRequest;
use App\Http\Resources\ContentPageResource;
use App\Http\Resources\PaginationResource;
use App\Models\ContentPage;
use Illuminate\Http\RedirectResponse;
use Inertia\Inertia;
use Inertia\Response;
use Spatie\QueryBuilder\QueryBuilder;

class ContentPageController extends Controller
{
    public function index(IndexRequest $request): Response
    {
        $this->authorize('canManage', ContentPage::class);

        $contentPages = QueryBuilder::for(ContentPage::class)
            ->allowedFilters(['id', 'title_en', 'title_nl', 'slug']);

        $contentPages = Index::forTable($contentPages, $request);

        return Inertia::render('content-page/Index', [
            'contentPages' => ContentPageResource::collection($contentPages)->additional([
                'pagination' => new PaginationResource($contentPages),
            ]),
        ]);
    }

    public function create(): Response
    {
        $this->authorize('create', ContentPage::class);

        return Inertia::render('content-page/Create');
    }

    public function store(StoreRequest $request, CreateAction $createAction): RedirectResponse
    {
        $createAction->execute($request->validated());

        flash(trans('message.data-saved'), 'success');

        return redirect()->route('content-page.index');
    }

    public function show(ContentPage $contentPage): Response
    {
        return Inertia::render('content-page/Show', [
            'contentPage' => new ContentPageResource($contentPage),
        ]);
    }

    public function update(
        UpdateRequest $request,
        ContentPage $contentPage,
        UpdateAction $updateAction
    ): RedirectResponse {
        $updateAction->execute($request->validated(), $contentPage);

        flash(trans('message.data-saved'), 'success');

        return redirect()->route('content-page.index');
    }

    public function destroy(ContentPage $contentPage, DeleteAction $deleteAction): RedirectResponse
    {
        $this->authorize('delete', $contentPage);

        $deleteAction->execute($contentPage);

        flash(trans('message.data-saved'), 'success');

        return redirect()->route('content-page.index');
    }

    public function edit(ContentPage $contentPage): Response
    {
        $this->authorize('update', $contentPage);

        return Inertia::render('content-page/Edit', [
            'contentPage' => $contentPage,
        ]);
    }
}
