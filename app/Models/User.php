<?php

declare(strict_types=1);

namespace App\Models;

use App\Enums\Auth\Role;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Activitylog\Traits\LogsActivity;

class User extends Authenticatable implements MustVerifyEmail
{
    use HasFactory, Notifiable, LogsActivity;

    /** @var array */
    protected $fillable = [
        'external_id',
        'name',
        'roles',
    ];

    /** @var array */
    protected $casts = [
        'roles' => 'json',
    ];

    /** @var string|bool */
    protected $rememberTokenName = false;

    protected static array $logAttributes = ['*'];

    protected static array $logAttributesToIgnore = [
        'updated_at',
    ];

    protected static bool $logOnlyDirty = true;

    protected static bool $submitEmptyLogs = false;

    public function institute(): BelongsTo
    {
        return $this->belongsTo(Institute::class);
    }

    public function experiences(): HasMany
    {
        return $this->hasMany(Experience::class);
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

    private function hasAllRoles(): bool
    {
        $missingRoles = array_diff(array_values(Role::toArray()), $this->roles);

        return empty($missingRoles);
    }
}
