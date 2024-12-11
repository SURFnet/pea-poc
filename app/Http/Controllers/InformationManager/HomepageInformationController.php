<?php

declare(strict_types=1);

namespace App\Http\Controllers\InformationManager;

use App\Actions\HomepageInformation\EditHomepageInformationAction;
use App\Helpers\Auth;
use App\Http\Controllers\Controller;
use App\Http\Requests\HomepageInformation\EditRequest;
use App\Http\Resources\InstituteResource;
use Illuminate\Http\RedirectResponse;
use Inertia\Inertia;
use Inertia\Response;

class HomepageInformationController extends Controller
{
    public function edit(): Response
    {
        $institute = Auth::user()->institute;

        $this->authorize('editHomepage', $institute);

        return Inertia::render('information-manager/homepage-information/Edit', [
            'institute' => new InstituteResource($institute),
        ]);
    }

    public function update(EditRequest $request): RedirectResponse
    {
        $institute = Auth::user()->institute;

        (new EditHomepageInformationAction())->execute($request->validated(), $institute);

        flash(trans('message.data-saved'), 'success');

        return redirect()->route('information-manager.homepage-information.edit');
    }
}
