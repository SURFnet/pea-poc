<?php

declare(strict_types=1);

namespace App;

use Illuminate\Foundation\Application as BaseApplication;

class Application extends BaseApplication
{
    public function publicPath(): string
    {
        return $this->basePath . DIRECTORY_SEPARATOR . 'public_html';
    }
}
