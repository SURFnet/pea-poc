<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Helpers\Auth;
use App\Helpers\Locale;
use App\Http\Resources\TagResource;
use App\Models\Institute;
use Inertia\Inertia;
use Inertia\Response;

class HomeController extends Controller
{
    public function index(): Response
    {
        /** @var Institute $institute */
        $institute = Auth::user()->institute;

        return Inertia::render('home/Index', [
            'categories'          => TagResource::collection($institute->categories()),
            'homepageInformation' => [
                'title' => Locale::getLocalizedFieldValue($institute, 'homepage_title'),
                'body'  => Locale::getLocalizedFieldValue($institute, 'homepage_body'),
            ],
        ]);
    }
}
