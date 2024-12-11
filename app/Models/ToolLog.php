<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ToolLog extends Model
{
    use HasFactory;

    /** @var array<int, string> */
    protected $fillable = [];

    public function tool(): BelongsTo
    {
        return $this->belongsTo(Tool::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function institute(): BelongsTo
    {
        return $this->belongsTo(Institute::class);
    }

    public function scopeForTool(Builder $query, Tool $tool): Builder
    {
        return $query->where('tool_id', $tool->id);
    }

    public function scopeForInstitute(Builder $query, Institute $institute): Builder
    {
        return $query->where('institute_id', $institute->id);
    }

    public function scopeMissingInstitute(Builder $query): Builder
    {
        return $query->where('institute_id', '=', null);
    }
}
