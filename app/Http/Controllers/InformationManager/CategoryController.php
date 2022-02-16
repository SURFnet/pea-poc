<?php

declare(strict_types=1);

namespace App\Http\Controllers\InformationManager;

use App\Actions\Category\CreateAction;
use App\Actions\Category\DeleteAction;
use App\Actions\Category\UpdateAction;
use App\Helpers\Auth;
use App\Helpers\Index;
use App\Http\Controllers\Controller;
use App\Http\Requests\Category\StoreRequest;
use App\Http\Requests\Category\UpdateRequest;
use App\Http\Requests\IndexRequest;
use App\Http\Resources\CategoryResource;
use App\Http\Resources\PaginationResource;
use App\Models\Category;
use Illuminate\Http\RedirectResponse;
use Inertia\Inertia;
use Inertia\Response;
use Spatie\QueryBuilder\QueryBuilder;

class CategoryController extends Controller
{
    public function index(IndexRequest $request): Response
    {
        $this->authorize('viewAll', Category::class);

        $categories = QueryBuilder::for(Auth::user()->institute->categories())
            ->allowedFilters([
                'name',
                'description',
            ]);

        $categories = Index::forTable($categories, $request, 10);

        return Inertia::render('information-manager/category/Index', [
            'categories' => CategoryResource::collection($categories)->additional([
                'pagination' => new PaginationResource($categories),
            ]),
        ]);
    }

    public function create(): Response
    {
        $this->authorize('create', Category::class);

        return Inertia::render('information-manager/category/Create');
    }

    public function store(StoreRequest $request): RedirectResponse
    {
        (new CreateAction())->execute($request->validated(), Auth::user()->institute);

        flash(trans('message.data-saved'), 'success');

        return redirect()->route('information-manager.category.index');
    }

    public function edit(Category $category): Response
    {
        $this->authorize('update', $category);

        return Inertia::render('information-manager/category/Edit', [
            'category' => new CategoryResource($category),
        ]);
    }

    public function update(UpdateRequest $request, Category $category): RedirectResponse
    {
        (new UpdateAction())->execute($category, $request->validated());

        flash(trans('message.data-saved'), 'success');

        return redirect()->route('information-manager.category.index');
    }

    public function destroy(Category $category): RedirectResponse
    {
        $this->authorize('delete', $category);

        (new DeleteAction())->execute($category);

        flash(trans('message.data-saved'), 'success');

        return redirect()->route('information-manager.category.index');
    }
}
