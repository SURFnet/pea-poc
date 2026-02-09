<?php

declare(strict_types=1);

namespace App\Observers;

use App\Models\Tool;
use Illuminate\Support\Str;

class ToolObserver
{
    /**
     * Handle the Tool "creating" event.
     */
    public function creating(Tool $tool): void
    {
        $tool->slug = $this->getUniqueSlug($tool);
        if ($tool->institute) {
            $tool->institute_slug = $this->getUniqueSlug($tool, 'institute_slug');
        }
    }

    /**
     * Handle the Tool "updating" event.
     */
    public function updating(Tool $tool): void
    {
        $tool->slug = $this->getUniqueSlug($tool);
        if ($tool->institute) {
            $tool->institute_slug = $this->getUniqueSlug($tool, 'institute_slug');
        }
    }

    /**
     * If the slug already exists, suffix it with "-1".
     * If that slug also already exists, suffix it with "-2".
     * Keep increasing that number until the new slug is unique.
     */
    private function getUniqueSlug(Tool $tool, string $column = 'slug'): string
    {
        $slug = Str::slug($tool->name);
        if ($tool->institute) {
            $slug = Str::slug($tool->institute->short_name . ' ' . $slug);
        }
        $slugFinal = $slug;
        $suffix = 1;

        while (Tool::where($column, '=', $slugFinal)->where('id', '<>', $tool->id)->exists()) {
            $slugFinal = "$slug-$suffix";
            $suffix++;
        }

        return $slugFinal;
    }
}
