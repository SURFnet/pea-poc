<?php

declare(strict_types=1);

namespace App\Http\Controllers\Teacher;

use App\Actions\Experience\CreateAction;
use App\Actions\Experience\DeleteAction;
use App\Actions\Experience\UpdateAction;
use App\Http\Controllers\Controller;
use App\Http\Requests\Experience\StoreRequest;
use App\Http\Requests\Experience\UpdateRequest;
use App\Models\Experience;
use App\Models\Tool;
use Illuminate\Http\RedirectResponse;

class ExperienceController extends Controller
{
    public function store(StoreRequest $request, Tool $tool): RedirectResponse
    {
        (new CreateAction())->execute($tool, $request->user(), $request->validated());

        flash(trans('message.experience.shared'), 'success');

        return redirect()->back();
    }

    public function update(UpdateRequest $request, Experience $experience): RedirectResponse
    {
        (new UpdateAction())->execute($experience, $request->validated());

        flash(trans('message.experience.updated'), 'success');

        return redirect()->back();
    }

    public function destroy(Experience $experience): RedirectResponse
    {
        $this->authorize('delete', $experience);

        (new DeleteAction())->execute($experience);

        flash(trans('message.experience.deleted'), 'success');

        return redirect()->back();
    }
}
