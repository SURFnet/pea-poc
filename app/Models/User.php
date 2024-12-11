<?php

declare(strict_types=1);

namespace App\Models;

use App\Enums\Auth\Role;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Contracts\Translation\HasLocalePreference;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Query\JoinClause;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class User extends Authenticatable implements MustVerifyEmail, HasLocalePreference
{
    use HasFactory, Notifiable, LogsActivity;

    /** @var array<int, string> */
    protected $fillable = [
        'external_id',
        'email',
        'name',
        'roles',
    ];

    /** @var array<string, string> */
    protected $casts = [
        'roles' => 'json',
    ];

    /** @var string */
    protected $rememberTokenName = '';

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

    public function institute(): BelongsTo
    {
        if ($this->impersonated_institute_id !== null) {
            return $this->impersonatedInstitute();
        }

        return $this->belongsTo(Institute::class);
    }

    public function impersonatedInstitute(): BelongsTo
    {
        return $this->belongsTo(Institute::class);
    }

    public function experiences(): HasMany
    {
        return $this->hasMany(Experience::class);
    }

    public function followingTools(): BelongsToMany
    {
        return $this
            ->belongsToMany(self::class, 'tool_followers')
            ->withTimestamps();
    }

    public function isSuperAdmin(): bool
    {
        return $this->hasAllRoles();
    }

    public function isContentManager(): bool
    {
        return in_array(Role::CONTENT_MANAGER, $this->roles, true);
    }

    public function isInformationManager(): bool
    {
        return in_array(Role::INFORMATION_MANAGER, $this->roles, true);
    }

    public function isTeacher(): bool
    {
        return in_array(Role::TEACHER, $this->roles, true);
    }

    public function isOnlyRole(string $role): bool
    {
        if (count($this->roles) !== 1) {
            return false;
        }

        return in_array($role, $this->roles, true);
    }

    public function isFollowingTool(Tool $tool): bool
    {
        return $this->followingTools()->where('tool_id', $tool->id)->exists();
    }

    public function isImpersonating(): bool
    {
        return $this->impersonated_institute_id !== null;
    }

    public function preferredLocale(): string
    {
        return $this->language;
    }

    public function scopeFromInstitute(Builder $query, Institute $institute): Builder
    {
        return $query->where('institute_id', $institute->id);
    }

    public function scopeWithEmail(Builder $query): Builder
    {
        return $query->whereNotNull('email');
    }

    public function scopeFollowingTool(Builder $query, Tool $tool): Builder
    {
        return $query->whereRelation('followingTools', 'tool_id', '=', $tool->id);
    }

    public static function getStakeholdersToNotifyFor(Tool $tool): Collection
    {
        return self::select('users.*')
            ->join('institute_tool', function (JoinClause $join) use ($tool): void {
                $join
                    ->on('institute_tool.institute_id', '=', 'users.institute_id')
                    ->on('institute_tool.tool_id', '=', DB::raw($tool->id));
            })
            ->whereJsonContains('roles', Role::INFORMATION_MANAGER)
            ->whereNotNull('email')
            ->get();
    }

    private function hasAllRoles(): bool
    {
        $missingRoles = array_diff(array_values(Role::toArray()), $this->roles);

        return empty($missingRoles);
    }
}
