<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Spatie\Activitylog\Traits\LogsActivity;

class Institute extends Model
{
    use HasFactory, LogsActivity;

    /** @var array */
    protected $fillable = [
        'full_name',
        'short_name',
        'domain',
        'logo_square_filename',
        'logo_full_filename',
        'banner_filename',
    ];

    protected static array $logAttributes = ['*'];

    protected static array $logAttributesToIgnore = [
        'updated_at',
    ];

    protected static bool $logOnlyDirty = true;

    protected static bool $submitEmptyLogs = false;

    public static string $disk = 'institutes';

    public function categories(): HasMany
    {
        return $this->hasMany(Category::class);
    }

    public function users(): HasMany
    {
        return $this->hasMany(User::class);
    }

    public function tools(): BelongsToMany
    {
        return $this
            ->belongsToMany(Tool::class)
            ->whereNotNull('tools.published_at')
            ->withTimestamps()
            ->using(InstituteTool::class)
            ->withPivot([
                'alternative_tool_id',
                'description_1',
                'description_1_image_filename',
                'description_2',
                'description_2_image_filename',
                'extra_information_title',
                'extra_information',
                'support_title_1',
                'support_email_1',
                'support_title_2',
                'support_email_2',
                'manual_title_1',
                'manual_url_1',
                'manual_title_2',
                'manual_url_2',
                'video_title_1',
                'video_url_1',
                'video_title_2',
                'video_url_2',
                'status',
                'why_unfit',
                'published_at',
            ]);
    }

    public function toolsWithCategories(): BelongsToMany
    {
        return $this->tools()->with(['categories' => fn ($categories) => $categories->forInstitute($this)]);
    }

    public function publishedToolsWithCategories(): BelongsToMany
    {
        return $this->toolsWithCategories()->wherePivotNotNull('published_at');
    }

    public function hasTool(Tool $tool): bool
    {
        return $this->tools()->where('tools.id', $tool->id)->exists();
    }

    public function hasPublishedTool(Tool $tool): bool
    {
        return $this->tools()
            ->where('tools.id', $tool->id)
            ->wherePivotNotNull('published_at')
            ->exists();
    }
}
