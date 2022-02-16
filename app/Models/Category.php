<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Spatie\Activitylog\Traits\LogsActivity;

class Category extends Model
{
    use HasFactory, LogsActivity;

    /** @var array */
    protected $fillable = [
        'name',
        'description',
    ];

    protected static array $logAttributes = ['*'];

    protected static array $logAttributesToIgnore = [
        'updated_at',
    ];

    protected static bool $logOnlyDirty = true;

    protected static bool $submitEmptyLogs = false;

    protected static function boot(): void
    {
        parent::boot();

        static::addGlobalScope('ordered', function (Builder $query): void {
            $table = $query->getModel()->getTable();
            $query->orderBy($table . '.created_at');
        });
    }

    public function institute(): BelongsTo
    {
        return $this->belongsTo(Institute::class);
    }

    public function tools(): BelongsToMany
    {
        return $this->belongsToMany(Tool::class, 'tool_categories')->withTimestamps();
    }

    public function scopeForInstitute(Builder $query, Institute $institute): void
    {
        $query->whereHas('institute', fn (Builder $instituteQuery) => $instituteQuery->where('id', $institute->id));
    }

    public function scopeForTool(Builder $query, Tool $tool): void
    {
        $query->whereHas('tools', fn (Builder $toolQuery) => $toolQuery->where('tools.id', $tool->id));
    }
}
