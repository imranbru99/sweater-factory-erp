<?php

namespace App\Models;

use App\Modules\Core\Enums\UserType;
use App\Modules\Core\Models\Branch;
use App\Modules\Core\Models\Concerns\Auditable;
use App\Modules\Core\Models\Role;
use Database\Factories\UserFactory;
use Filament\Auth\MultiFactor\App\Contracts\HasAppAuthentication;
use Filament\Auth\MultiFactor\App\Contracts\HasAppAuthenticationRecovery;
use Filament\Models\Contracts\FilamentUser;
use Filament\Panel;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Collection;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable implements FilamentUser, HasAppAuthentication, HasAppAuthenticationRecovery
{
    /** @use HasFactory<UserFactory> */
    use Auditable, HasApiTokens, HasFactory, Notifiable, SoftDeletes;

    protected $fillable = [
        'name',
        'email',
        'password',
        'user_type',
        'phone',
        'locale',
        'branch_id',
        'employee_code',
        'is_active',
        'two_factor_secret',
        'two_factor_recovery_codes',
        'two_factor_confirmed_at',
        'quiet_hours_start',
        'quiet_hours_end',
    ];

    protected $hidden = [
        'password',
        'remember_token',
        'two_factor_secret',
        'two_factor_recovery_codes',
    ];

    protected ?Collection $permissionNameCache = null;

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'user_type' => UserType::class,
            'is_active' => 'boolean',
            'two_factor_secret' => 'encrypted',
            'two_factor_recovery_codes' => 'encrypted:array',
            'two_factor_confirmed_at' => 'datetime',
        ];
    }

    public function branch(): BelongsTo
    {
        return $this->belongsTo(Branch::class);
    }

    public function branches(): BelongsToMany
    {
        return $this->belongsToMany(Branch::class)->withPivot('is_default');
    }

    public function roles(): BelongsToMany
    {
        return $this->belongsToMany(Role::class);
    }

    public function isSuperAdmin(): bool
    {
        return $this->roles->contains(fn (Role $role): bool => $role->slug === 'admin');
    }

    public function seesAllBranches(): bool
    {
        return $this->isSuperAdmin() || $this->roles->contains(fn (Role $role): bool => $role->sees_all_branches);
    }

    /**
     * @return list<int>
     */
    public function accessibleBranchIds(): array
    {
        if ($this->seesAllBranches()) {
            return Branch::query()->pluck('id')->map(fn ($id): int => (int) $id)->all();
        }

        return $this->branches()->pluck('branches.id')->map(fn ($id): int => (int) $id)->all();
    }

    public function currentBranchId(): ?int
    {
        $sessionId = session('branch_id');
        $allowed = $this->accessibleBranchIds();

        if ($sessionId && in_array((int) $sessionId, $allowed, true)) {
            return (int) $sessionId;
        }

        return $this->branch_id ? (int) $this->branch_id : ($allowed[0] ?? null);
    }

    public function hasPermission(string $name): bool
    {
        if ($this->isSuperAdmin()) {
            return true;
        }

        $this->permissionNameCache ??= $this->roles()
            ->with('permissions:id,name')
            ->get()
            ->flatMap(fn (Role $role) => $role->permissions->pluck('name'))
            ->unique()
            ->values();

        return $this->permissionNameCache->contains($name);
    }

    public function requiresTwoFactor(): bool
    {
        return $this->roles->contains(fn (Role $role): bool => $role->requires_two_factor);
    }

    public function canAccessPanel(Panel $panel): bool
    {
        if (! $this->is_active) {
            return false;
        }

        $panelId = $panel->getId();

        if ($this->isSuperAdmin()) {
            return true;
        }

        return $this->user_type->panel() === $panelId;
    }

    public function wantsChannel(string $channel): bool
    {
        $preference = $this->notificationPreferences()->where('channel', $channel)->first();

        return $preference === null || $preference->enabled;
    }

    public function notificationPreferences(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(\App\Modules\Core\Models\NotificationPreference::class);
    }

    public function getAppAuthenticationSecret(): ?string
    {
        return $this->two_factor_secret;
    }

    public function saveAppAuthenticationSecret(?string $secret): void
    {
        $this->two_factor_secret = $secret;
        $this->two_factor_confirmed_at = filled($secret) ? now() : null;
        $this->save();
    }

    public function getAppAuthenticationHolderName(): string
    {
        return $this->email;
    }

    public function getAppAuthenticationRecoveryCodes(): ?array
    {
        return $this->two_factor_recovery_codes;
    }

    public function saveAppAuthenticationRecoveryCodes(?array $codes): void
    {
        $this->two_factor_recovery_codes = $codes;
        $this->save();
    }
}
