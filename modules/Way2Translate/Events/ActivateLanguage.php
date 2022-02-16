<?php

declare(strict_types=1);

namespace Modules\Way2Translate\Events;

use Illuminate\Queue\SerializesModels;

class ActivateLanguage extends Event
{
    use SerializesModels;

    public string $localeCode;

    public function __construct(string $localeCode)
    {
        $this->localeCode = $localeCode;
    }
}
