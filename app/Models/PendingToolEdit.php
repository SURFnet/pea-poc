<?php

declare(strict_types=1);

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PendingToolEdit extends Model
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

    public function scopeRecent(Builder $query): Builder
    {
        $maximumAgeMinutes = config('session.lifetime');

        return $query->where('created_at', '>=', Carbon::now()->subMinutes($maximumAgeMinutes));
    }

    public function scopeForUser(Builder $query, User $user): Builder
    {
        return $query->where('user_id', $user->id);
    }

    public function scopeNotForUser(Builder $query, User $user): Builder
    {
        return $query->where('user_id', '!=', $user->id);
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
        return $query->whereNull('institute_id');
    }
}
