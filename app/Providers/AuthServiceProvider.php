<?php

declare(strict_types=1);

namespace App\Providers;

use App\Models\CustomField;
use App\Models\Experience;
use App\Models\Tool;
use App\Policies\CustomFieldPolicy;
use App\Policies\ExperiencePolicy;
use App\Policies\TagTypePolicy;
use App\Policies\ToolPolicy;
use App\Policies\TranslationPolicy;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;
use Modules\Way2Translate\Models\Translation;

class AuthServiceProvider extends ServiceProvider
{
    /** @var array<class-string, class-string> */
    protected $policies = [
        CustomField::class => CustomFieldPolicy::class,
        Experience::class  => ExperiencePolicy::class,
        Tool::class        => ToolPolicy::class,

        // Modules
        Translation::class => TranslationPolicy::class,
    ];

    public function boot(): void
    {
        $this->registerPolicies();

        Gate::define('filter-by-tag-type', [TagTypePolicy::class, 'filterBy']);
    }
}
