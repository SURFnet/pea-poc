<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Enums\Tags\TagTypes;
use App\Models\Tag;
use App\Models\Tool;
use Illuminate\Database\Eloquent\Collection;

class TaggableSeeder extends BaseSeeder
{
    protected function handle(): void
    {
        $tools = Tool::all();

        foreach ($tools as $tool) {
            $this->addTags($tool);
            $this->advanceProgressBar();
        }
    }

    protected function getProgressBarCount(): int
    {
        return Tool::count();
    }

    private function addTags(Tool $tool): void
    {
        foreach (TagTypes::toArray() as $tagType) {
            $tagsWithoutInstitute = Tag::withoutInstitute()->where('type', $tagType)->get();
            $this->attachSomeTags($tool, $tagsWithoutInstitute);

            foreach ($tool->institutes as $institute) {
                $tagsWithInstitute = Tag::forInstitute($institute)->where('type', $tagType)->get();
                $this->attachSomeTags($tool, $tagsWithInstitute);
            }
        }
    }

    private function attachSomeTags(Tool $tool, Collection $tags): void
    {
        foreach ($tags as $tag) {
            if (rand(0, 1)) {
                continue;
            }

            $tool->attachTag($tag);
        }
    }
}
