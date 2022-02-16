<?php

declare(strict_types=1);

namespace App\Models;

use App\Enums\InstituteTool\Status;
use App\Helpers\Format;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\Pivot;

class InstituteTool extends Pivot
{
    use HasFactory;

    /** @var bool */
    public $incrementing = true;

    /** @var array */
    protected $fillable = [
        'alternative_tool_id',
        'description_1',
        'description_2',
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
    ];

    /** @var array */
    protected $casts = [
        'published_at' => 'datetime',
    ];

    public static string $disk = 'tools';

    public static array $images = [
        'description_1_image_filename',
        'description_2_image_filename',
    ];

    public function institute(): BelongsTo
    {
        return $this->belongsTo(Institute::class);
    }

    public function tool(): BelongsTo
    {
        return $this->belongsTo(Tool::class);
    }

    public function alternativeTool(): BelongsTo
    {
        return $this->belongsTo(Tool::class, 'alternative_tool_id');
    }

    public function getIsPublishedAttribute(): bool
    {
        return !empty($this->published_at);
    }

    public function getStatusDisplayAttribute(): string
    {
        if (!$this->is_published) {
            return Status::UNPUBLISHED;
        }

        if ($this->status === null) {
            return Status::UNRATED;
        }

        return $this->status;
    }

    public function getDescription1DisplayAttribute(): string
    {
        return Format::stripTagsAndConvertNewlineToHtml($this->description_1);
    }

    public function getDescription2DisplayAttribute(): string
    {
        return Format::stripTagsAndConvertNewlineToHtml($this->description_2);
    }

    public function getExtraInformationDisplayAttribute(): string
    {
        return Format::stripTagsAndConvertNewlineToHtml($this->extra_information);
    }

    public function getWhyUnfitDisplayAttribute(): string
    {
        return Format::stripTagsAndConvertNewlineToHtml($this->why_unfit);
    }
}
