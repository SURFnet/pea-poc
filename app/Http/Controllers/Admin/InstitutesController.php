<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Actions\Institute\ImpersonateAction;
use App\Helpers\Auth;
use App\Http\Controllers\Controller;
use App\Http\Resources\InstituteResource;
use App\Models\Institute;
use Illuminate\Http\RedirectResponse;
use Inertia\Inertia;
use Inertia\Response;

class InstitutesController extends Controller
{
    public function index(): Response
    {
        $this->authorize('impersonate', Institute::class);

        $institutes = Institute::orderBy('short_name')->whereNot('id', Auth::user()->institute_id)->get();

        return Inertia::render('institutes/Index', [
            'institutes' => InstituteResource::collection($institutes),
        ]);
    }

    public function impersonate(Institute $institute): RedirectResponse
    {
        $this->authorize('impersonate', $institute);

        (new ImpersonateAction())->execute(Auth::user(), $institute);

        return redirect()->route('home.index');
    }
}
