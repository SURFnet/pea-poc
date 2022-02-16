<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Enums\InstituteTool\Status;
use App\Helpers\File;
use App\Models\Feature;
use App\Models\Institute;
use App\Models\InstituteTool;
use App\Models\Tool;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class PredefinedToolSeeder extends BaseSeeder
{
    private int $totalTools = 20;

    public function handle(): void
    {
        $requiredStatuses = Status::toArray();

        $institute = Institute::where('domain', 'eduid.nl')->first();

        foreach ($this->predefinedTools() as $toolName) {
            $tool = Tool::factory()->withImages()->published()->create([
                'name'           => $toolName,
                'image_filename' => $this->prepareToolImage($toolName),
            ]);

            $tool->features()->attach(Feature::get()->random(rand(1, 4)));

            $factory = InstituteTool::factory()
                ->for($institute)
                ->for($tool)
                ->withImages()
                ->published();

            if (!empty($requiredStatuses)) {
                $factory->status(array_shift($requiredStatuses));
            }

            $factory->create();

            $this->advanceProgressBar();
        }
    }

    protected function getProgressBarCount(): int
    {
        return $this->totalTools;
    }

    private function prepareToolImage(string $toolName): string
    {
        $srcFilename = Str::slug($toolName);
        $srcFile = resource_path('seeding/tools/main/' . $srcFilename . '.png');

        $targetFilename = File::generateFilename('png');

        Storage::disk(Tool::$disk)->put($targetFilename, file_get_contents($srcFile));

        return $targetFilename;
    }

    private function predefinedTools(): array
    {
        return [
            'CoronaCheck',
            'DigiD',
            'bol.com',
            'Jumbo Extra\'s',
            'TikTok',
            'Vinted - Second-hand clothing',
            'Tasker',
            'Simple Gallery Pro: Photos',
            'The Wonder Weeks',
            'Topo GPS Netherlands',
            'Twitch: Livestream Multiplayer Games & Esports',
            'Microsoft OneDrive',
            'QR & Barcode Reader',
            'Telegram',
            'Social Deal - The best deals',
            'Google Home',
            'Spotify: Music and Podcasts',
            'Netflix',
            'Pinterest',
        ];
    }
}
