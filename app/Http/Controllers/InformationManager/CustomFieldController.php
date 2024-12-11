<?php

declare(strict_types=1);

namespace App\Http\Controllers\InformationManager;

use App\Actions\CustomField\CreateAction;
use App\Actions\CustomField\DeleteAction;
use App\Actions\CustomField\UpdateAction;
use App\Enums\Tool\Tabs;
use App\Helpers\Auth;
use App\Helpers\Index;
use App\Http\Controllers\Controller;
use App\Http\Requests\CustomField\StoreRequest;
use App\Http\Requests\CustomField\UpdateRequest;
use App\Http\Requests\IndexRequest;
use App\Http\Resources\CustomFieldResource;
use App\Http\Resources\PaginationResource;
use App\Models\CustomField;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\RedirectResponse;
use Inertia\Inertia;
use Inertia\Response;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;

class CustomFieldController extends Controller
{
    public function index(IndexRequest $request): Response
    {
        $this->authorize('viewAll', CustomField::class);

        $customFields = QueryBuilder::for(Auth::user()->institute->customFields())
            ->allowedFilters([
                AllowedFilter::callback('title', function (Builder $query, string $value): void {
                    $query->searchLocalizedField('title', $value);
                }),
                AllowedFilter::callback('tab_type', function (Builder $query, string $value): void {
                    $query->whereLike('tab_type', $value);
                }),
            ]);

        $customFields = Index::forTable($customFields, $request);

        return Inertia::render('information-manager/custom-field/Index', [
            'customFields' => CustomFieldResource::collection($customFields)->additional([
                'pagination' => new PaginationResource($customFields),
            ]),
            'tabTypes' => Tabs::asSelect(),
        ]);
    }

    public function create(): Response
    {
        $this->authorize('create', CustomField::class);

        return Inertia::render('information-manager/custom-field/Create', [
            'tabTypes' => Tabs::asSelect(),
        ]);
    }

    public function store(StoreRequest $request): RedirectResponse
    {
        (new CreateAction())->execute($request->validated(), Auth::user()->institute);

        flash(trans('message.data-saved'), 'success');

        return redirect()->route('information-manager.custom-field.index');
    }

    public function edit(CustomField $customField): Response
    {
        $this->authorize('update', $customField);

        return Inertia::render('information-manager/custom-field/Edit', [
            'customField' => new CustomFieldResource($customField),
            'tabTypes'    => Tabs::asSelect(),
        ]);
    }

    public function update(UpdateRequest $request, CustomField $customField): RedirectResponse
    {
        (new UpdateAction())->execute($customField, $request->validated());

        flash(trans('message.data-saved'), 'success');

        return redirect()->route('information-manager.custom-field.index');
    }

    public function destroy(CustomField $customField): RedirectResponse
    {
        $this->authorize('delete', $customField);

        (new DeleteAction())->execute($customField);

        flash(trans('message.data-saved'), 'success');

        return redirect()->route('information-manager.custom-field.index');
    }
}
