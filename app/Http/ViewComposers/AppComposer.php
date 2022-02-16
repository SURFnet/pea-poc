<?php

declare(strict_types=1);

namespace App\Http\ViewComposers;

use App\Helpers\Auth;
use Illuminate\View\View;
use Modules\Way2Translate\Helpers\Js;
use Modules\Way2Translate\Models\Locale;

class AppComposer
{
    private array $data = [];

    public function compose(View $view): void
    {
        if (empty($this->data)) {
            $this->data = [
                'activeLocales' => Locale::getActive()->sortBy('native'),
                'currentUser'   => Auth::user(),
                'langJsUrl'     => Js::getLangUrl(),
            ];
        }

        $view->with($this->data);
    }
}
