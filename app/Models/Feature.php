<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Spatie\Activitylog\Traits\LogsActivity;

class Feature extends Model
{
    use HasFactory, LogsActivity;

    /** @var array */
    protected $fillable = [
        'name',
    ];

    protected static array $logAttributes = ['*'];

    protected static array $logAttributesToIgnore = [
        'updated_at',
    ];

    protected static bool $logOnlyDirty = true;

    protected static bool $submitEmptyLogs = false;

    public function tools(): BelongsToMany
    {
        return $this->belongsToMany(Tool::class);
    }
}
