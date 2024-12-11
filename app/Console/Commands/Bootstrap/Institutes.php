<?php

declare(strict_types=1);

namespace App\Console\Commands\Bootstrap;

use App\Helpers\File;
use App\Models\Institute;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\App;

class Institutes extends Command
{
    /** @var string */
    protected $signature = 'bootstrap:institutes';

    /** @var string */
    protected $description = 'Bootstraps the institutes.';

    public function handle(): void
    {
        $this->info('Bootstrapping institutes...');

        $institutes = $this->getInstitutesToBootstrap();

        foreach ($institutes as $bootstrapInstitute) {
            if (Institute::where('domain', $bootstrapInstitute['domain'])->exists()) {
                $this->updateInstitute($bootstrapInstitute);

                continue;
            }

            $this->createNewInstitute($bootstrapInstitute);
        }

        $this->info('Bootstrapping institutes done');
    }

    /** @return array<int,array<string,string>> $institutes */
    private function getInstitutesToBootstrap(): array
    {
        $institutes = config('bootstrap.institutes');

        $allowedEnvironments = array_merge(
            config('constants.environment.development'),
            config('constants.environment.staging'),
            config('constants.environment.testing'),
        );

        if (App::environment($allowedEnvironments)) {
            $institutes = array_merge($institutes, config('bootstrap.test-institutes'));
        }

        return $institutes;
    }

    private function updateInstitute(array $bootstrapInstitute): void
    {
        $institute = Institute::where('domain', $bootstrapInstitute['domain'])->first();

        // Get current set of pictures
        $replacedImages = [
            'logo_full_filename'   => $institute->logo_full_filename,
            'logo_square_filename' => $institute->logo_square_filename,
            'banner_filename'      => $institute->banner_filename,
        ];

        // Store new set of pictures
        $logoPath = resource_path('seeding/institutes/full/' . $bootstrapInstitute['logo']);
        $bannerPath = resource_path('seeding/institutes/banner/' . $bootstrapInstitute['banner']);

        $logoFullFilename = File::storeFromPath($logoPath, Institute::$disk);
        $logoSquareFilename = File::storeFromPath($logoPath, Institute::$disk);
        $bannerFilename = File::storeFromPath($bannerPath, Institute::$disk);

        $institute->update([
            'full_name_en'         => $bootstrapInstitute['full_name_en'],
            'full_name_nl'         => $bootstrapInstitute['full_name_nl'],
            'short_name'           => $bootstrapInstitute['short_name'],
            'logo_full_filename'   => $logoFullFilename,
            'logo_square_filename' => $logoSquareFilename,
            'banner_filename'      => $bannerFilename,
        ]);

        // Finally, delete old pictures
        foreach ($replacedImages as $fileName) {
            File::delete($fileName);
        }
    }

    private function createNewInstitute(array $bootstrapInstitute): void
    {
        $logoPath = resource_path('seeding/institutes/full/' . $bootstrapInstitute['logo']);
        $bannerPath = resource_path('seeding/institutes/banner/' . $bootstrapInstitute['banner']);

        $logoFullFilename = File::storeFromPath($logoPath, Institute::$disk);
        $logoSquareFilename = File::storeFromPath($logoPath, Institute::$disk);
        $bannerFilename = File::storeFromPath($bannerPath, Institute::$disk);

        Institute::create([
            'full_name_en'         => $bootstrapInstitute['full_name_en'],
            'full_name_nl'         => $bootstrapInstitute['full_name_nl'],
            'short_name'           => $bootstrapInstitute['short_name'],
            'domain'               => $bootstrapInstitute['domain'],
            'logo_full_filename'   => $logoFullFilename,
            'logo_square_filename' => $logoSquareFilename,
            'banner_filename'      => $bannerFilename,
        ]);
    }
}
