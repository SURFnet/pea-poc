<?php

declare(strict_types=1);

namespace App\Models;

use App\Helpers\Format;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class Experience extends Model
{
    use HasFactory, LogsActivity;

    /** @var array<int, string> */
    protected $fillable = [
        'title',
        'message',
    ];

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logAll()
            ->logExcept([
                'updated_at',
            ])
            ->logOnlyDirty()
            ->dontSubmitEmptyLogs();
    }

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
        return Format::asSimpleHtml($this->message);
    }
}
