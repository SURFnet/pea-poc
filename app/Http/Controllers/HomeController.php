<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Helpers\Auth;
use App\Http\Resources\CategoryResource;
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
            'categories' => CategoryResource::collection($institute->categories),
        ]);
    }
}
