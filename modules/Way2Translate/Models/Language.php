<?php

declare(strict_types=1);

namespace Modules\Way2Translate\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Event;
use Modules\Way2Translate\Events\ActivateLanguage;

class Language extends Model
{
    /** @var string */
    protected $table = 'way2translate_languages';

    /** @var array */
    protected $fillable = [
        'locale',
    ];

    /** @var array */
    protected $dates = [
        'activated_at',
        'created_at',
        'updated_at',
    ];

    public static function activate(string $localeCode): void
    {
        if (self::where('locale', $localeCode)->doesntExist()) {
            self::addByLocale($localeCode);
        }

        self::where('locale', $localeCode)->update([
            'activated_at' => Carbon::now(),
        ]);

        Event::dispatch(new ActivateLanguage($localeCode));
    }

    public static function deactivate(string $localeCode): void
    {
        self::where('locale', $localeCode)->update([
            'activated_at' => null,
        ]);
    }

    public static function getActive(): Collection
    {
        return self::whereNotNull('activated_at')->get();
    }

    public static function addByLocale(string $localeCode): void
    {
        self::create([
            'locale'       => $localeCode,
            'activated_at' => null,
        ]);
    }
}
