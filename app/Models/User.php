<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

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
            $this->hasRole('cargador') => route('panel.carga', absolute: false),
            $this->hasRole('consultor') => route('panel.consulta', absolute: false),
            default => route('personas.index', absolute: false),
        };
    }
}
