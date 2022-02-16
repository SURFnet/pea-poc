<?php

declare(strict_types=1);

namespace App\Console\Commands\Bootstrap;

use App\Models\Institute;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;

class Institutes extends Command
{
    /** @var string */
    protected $signature = 'bootstrap:institutes';

    /** @var string */
    protected $description = 'Bootstraps the institutes.';

    public function handle(): void
    {
        $this->info('Bootstrapping institutes...');

        foreach (config('bootstrap.institutes') as $institute) {
            if (Institute::where('domain', $institute['domain'])->exists()) {
                continue;
            }

            $logoFullFilename = basename(Storage::putFile(
                Institute::$disk,
                resource_path('seeding/institutes/full/' . $institute['logo'])
            ));

            $logoSquareFilename = basename(Storage::putFile(
                Institute::$disk,
                resource_path('seeding/institutes/square/' . $institute['logo'])
            ));

            $bannerFilename = basename(Storage::putFile(
                Institute::$disk,
                resource_path('seeding/institutes/banner/' . $institute['banner'])
            ));

            Institute::create([
                'full_name'            => $institute['full_name'],
                'short_name'           => $institute['short_name'],
                'domain'               => $institute['domain'],
                'logo_full_filename'   => $logoFullFilename,
                'logo_square_filename' => $logoSquareFilename,
                'banner_filename'      => $bannerFilename,
            ]);
        }

        $this->info('Bootstrapping institutes done');
    }
}
