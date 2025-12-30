<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Collection;
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
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read Notifiable|null $notifications
 * @property-read Collection|Role[] $roles
 * @method bool hasRole(string $roleName)
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
        'brigada_id',
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

    /**
     * Always eager-load roles to avoid extra queries in hasRole()
     */
    protected $with = ['roles'];

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
    public function hasRole($roleName)
    {
        return $this->roles->pluck('name')->contains($roleName);
    }

    /**
     * Ruta por defecto según el rol del usuario
     */
    public function getDefaultRoute(): string
    {
        return match (true) {
            $this->hasRole('admin') => route('dashboard', absolute: false),
            $this->hasRole('cargador') => route('procedimientos.index', absolute: false),
            $this->hasRole('consultor') => route('dashboard.consultor', absolute: false),
            default => route('personas.index', absolute: false),
        };
    }
}
