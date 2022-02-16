<?php

declare(strict_types=1);

namespace App\Providers;

use App\Helpers\Url;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Str;

class SubdomainServiceProvider extends ServiceProvider
{
    private ?string $subdomain;

    private ?string $appName;

    public function register(): void
    {
        $subdomain = Url::getSubdomain(Request::root());
        if (empty($subdomain)) {
            return;
        }

        $this->appName = Str::slug(config('app.name'));
        $this->subdomain = $subdomain;

        $this->setUniqueSessionName();
        $this->setUniqueCacheName();
    }

    private function setUniqueSessionName(): void
    {
        Config::set('session.cookie', $this->subdomain . '_' . $this->appName . '_session');
    }

    private function setUniqueCacheName(): void
    {
        Config::set('cache.prefix', $this->subdomain . '_' . $this->appName . '_cache');
    }
}
