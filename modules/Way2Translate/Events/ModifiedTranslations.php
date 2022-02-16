<?php

declare(strict_types=1);

namespace Modules\Way2Translate\Events;

use Illuminate\Queue\SerializesModels;

class ModifiedTranslations extends Event
{
    use SerializesModels;

    public string $localeCode;

    public string $group;

    public string $namespace;

    public function __construct(string $localeCode, string $group, string $namespace)
    {
        $this->localeCode = $localeCode;
        $this->group = $group;
        $this->namespace = $namespace;
    }
}
