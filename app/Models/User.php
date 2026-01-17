<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

/**
 * App\Models\User
 *
 * @property int $id
 * @property string $name
 * @property string $email
 * @property \Illuminate\Support\Carbon|null $email_verified_at
 * @property string $password
 * @property string|null $remember_token
 * @property bool $active
 * @property \Illuminate\Support\Carbon|null $last_login_at
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read Notifiable|null $notifications
 * @property-read Collection|Role[] $roles
 * @property-read Collection|ActivityLog[] $activityLogs
 *
 * @method bool hasRole(string $roleName)
 * @method bool hasAnyRole(array $roles)
 * @method bool isSuperAdmin()
 * @method bool isAdmin()
 */
class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'jerarquia',
        'brigada_id',
        'active',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    // Eager loading global de 'roles' removido para evitar cargas innecesarias.

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'last_login_at' => 'datetime',
            'active' => 'boolean',
        ];
    }

    /**
     * Roles relationship
     */
    public function roles()
    {
        return $this->belongsToMany(Role::class);
    }

    /**
     * Brigada relationship
     */
    public function brigada()
    {
        return $this->belongsTo(Brigada::class);
    }

    /**
     * Check if the user has a given role by name
     */
    public function hasRole($roleName): bool
    {
        return $this->roles?->pluck('name')->contains($roleName) ?? false;
    }

    /**
     * Check if the user is a super admin
     */
    public function isSuperAdmin(): bool
    {
        return $this->hasRole('super_admin');
    }

    /**
     * Check if the user is admin or super admin
     */
    public function isAdmin(): bool
    {
        return $this->hasRole('admin') || $this->isSuperAdmin();
    }

    /**
     * Check if user has any of the given roles
     */
    public function hasAnyRole(array $roles): bool
    {
        return $this->roles?->pluck('name')->intersect($roles)->isNotEmpty() ?? false;
    }

    /**
     * Relación con logs de actividad
     */
    public function activityLogs()
    {
        return $this->hasMany(ActivityLog::class);
    }

    /**
     * NUEVO: Ruta de inicio centralizada según el rol del usuario.
     * Reglas de negocio:
     * - super_admin -> admin.users.index
     * - admin -> dashboard
     * - panel-carga o panel-consulta -> procedimientos.index
     * - Fallback -> procedimientos.index
     */
    public function getHomeRoute(): string
    {
        if ($this->hasRole('super_admin')) {
            return 'admin.users.index';
        }

        if ($this->hasRole('admin')) {
            return 'dashboard';
        }

        if ($this->hasAnyRole(['panel-carga', 'panel-consulta'])) {
            return 'procedimientos.index';
        }

        return 'procedimientos.index';
    }

    /**
     * (Compat) Ruta previa; se mantiene temporalmente por si hay referencias legacy.
     */
    public function getDefaultRoute(): string
    {
        return match (true) {
            $this->hasRole('super_admin') => route('dashboard', absolute: false),
            $this->hasRole('admin') => route('dashboard', absolute: false),
            $this->hasRole('panel-carga') => route('procedimientos.index', absolute: false),
            $this->hasRole('panel-consulta') => route('dashboard.consultor', absolute: false),
            default => route('personas.index', absolute: false),
        };
    }
}
