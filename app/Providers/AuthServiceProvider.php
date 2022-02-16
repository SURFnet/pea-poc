<?php

declare(strict_types=1);

namespace App\Providers;

use App\Models\Category;
use App\Models\Experience;
use App\Models\Tool;
use App\Policies\CategoryPolicy;
use App\Policies\ExperiencePolicy;
use App\Policies\ToolPolicy;
use App\Policies\TranslationPolicy;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Modules\Way2Translate\Models\Translation;

class AuthServiceProvider extends ServiceProvider
{
    /** @var array */
    protected $policies = [
        Tool::class       => ToolPolicy::class,
        Category::class   => CategoryPolicy::class,
        Experience::class => ExperiencePolicy::class,

        // Modules
        Translation::class => TranslationPolicy::class,
    ];

    public function boot(): void
    {
        $this->registerPolicies();
    }
}
