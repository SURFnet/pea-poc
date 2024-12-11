<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Enums\Tags\TagTypes;
use App\Models\Institute;
use App\Models\Tag;
use Illuminate\Support\Arr;

class TagSeeder extends BaseSeeder
{
    protected function handle(): void
    {
        $this->seedTags();
        $this->seedCategoryTags();
    }

    protected function getProgressBarCount(): int
    {
        return count(Arr::flatten($this->getTags())) + Institute::count();
    }

    private function getTags(): array
    {
        return [
            TagTypes::FEATURES => [
                'Chat', 'Create learning materials', 'Edit video, sound or image', 'Exams and assignments',
                'Feedback', 'File share', 'Mindmaps', 'Notebook', 'Presenting', 'Project Planning',
                'Quiz, Games or Polls', 'Revision', 'Schedule or Roster', 'Surveys', 'Whiteboard',
            ],
            TagTypes::SOFTWARE_TYPES            => ['Install software', 'plugin', 'cloud'],
            TagTypes::DEVICES                   => ['smartphone', 'tablet', 'PC'],
            TagTypes::STANDARDS                 => ['SURFconext', 'LTI', 'SAML', 'OOAPI', 'xAPI'],
            TagTypes::OPERATING_SYSTEMS         => ['Linux', 'Windows', 'macOS', 'IOS', 'Android'],
            TagTypes::DATA_PROCESSING_LOCATIONS => ['Inside EU', 'Outside EU'],
            TagTypes::CERTIFICATIONS            => ['ISO27001'],
            TagTypes::WORKING_METHODS           => ['Working method 1', 'Working method 2'],
            TagTypes::TARGET_GROUPS             => ['Target group 1', 'Target group 2'],
            TagTypes::COMPLEXITY                => ['basis', 'experienced', 'expert'],
        ];
    }

    private function seedTags(): void
    {
        $tags = $this->getTags();

        foreach (TagTypes::toArray() as $tagType) {
            if ($tagType === TagTypes::CATEGORIES) {
                // Categories are seeded separately by institute
                continue;
            }

            foreach ($tags[$tagType] as $tag) {
                Tag::factory()->create([
                    'name' => $tag,
                    'type' => $tagType,
                ]);

                $this->advanceProgressBar();
            }
        }
    }

    private function seedCategoryTags(): void
    {
        foreach (Institute::all() as $institute) {
            Tag::factory(rand(1, 20))
                ->withInstitute($institute)
                ->create([
                    'type' => TagTypes::CATEGORIES,
                ]);

            $this->advanceProgressBar();
        }
    }
}
