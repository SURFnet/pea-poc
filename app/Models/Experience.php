<?php

declare(strict_types=1);

namespace App\Models;

use App\Helpers\Format;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Spatie\Activitylog\Traits\LogsActivity;

class Experience extends Model
{
    use HasFactory, LogsActivity;

    /** @var array */
    protected $fillable = [
        'rating',
        'title',
        'message',
    ];

    protected static array $logAttributes = ['*'];

    protected static array $logAttributesToIgnore = [
        'updated_at',
    ];

    protected static bool $logOnlyDirty = true;

    protected static bool $submitEmptyLogs = false;

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function tool(): BelongsTo
    {
        return $this->belongsTo(Tool::class);
    }

    public function getMessageDisplayAttribute(): string
    {
        return Format::stripTagsAndConvertNewlineToHtml($this->message);
    }
}
