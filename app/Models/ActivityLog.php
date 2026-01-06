<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Request;

/**
 * Modelo de Auditoría - Registra todas las acciones críticas del sistema
 * 
 * @property int $id
 * @property int|null $user_id
 * @property string $action
 * @property string|null $model_type
 * @property int|null $model_id
 * @property string|null $description
 * @property array|null $properties
 * @property string|null $ip_address
 * @property string|null $user_agent
 * @property string $severity
 * @property \Illuminate\Support\Carbon $created_at
 * @property \Illuminate\Support\Carbon $updated_at
 * @property-read User|null $user
 * @method static \Illuminate\Database\Eloquent\Builder|ActivityLog severity(string $severity)
 * @method static \Illuminate\Database\Eloquent\Builder|ActivityLog action(string $action)
 * @method static \Illuminate\Database\Eloquent\Builder|ActivityLog recent(int $days = 30)
 */
class ActivityLog extends Model
{
    protected $fillable = [
        'user_id',
        'action',
        'model_type',
        'model_id',
        'description',
        'properties',
        'ip_address',
        'user_agent',
        'severity',
    ];

    protected $casts = [
        'properties' => 'array',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Relación con el usuario que realizó la acción
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Scope para filtrar por severidad
     */
    public function scopeSeverity($query, $severity)
    {
        return $query->where('severity', $severity);
    }

    /**
     * Scope para filtrar por acción
     */
    public function scopeAction($query, $action)
    {
        return $query->where('action', $action);
    }

    /**
     * Scope para logs recientes
     */
    public function scopeRecent($query, $days = 30)
    {
        return $query->where('created_at', '>=', now()->subDays($days));
    }

    /**
     * Método estático para registrar una actividad
     * 
     * @param string $action Acción realizada
     * @param string $description Descripción legible
     * @param array<string, mixed> $options Opciones adicionales (model_type, model_id, properties, severity)
     * @return static
     */
    public static function log(string $action, string $description, array $options = []): self
    {
        /** @var User|null $user */
        $user = Auth::user();
        
        return static::create([
            'user_id' => $user?->id,
            'action' => $action,
            'model_type' => $options['model_type'] ?? null,
            'model_id' => $options['model_id'] ?? null,
            'description' => $description,
            'properties' => $options['properties'] ?? null,
            'ip_address' => Request::ip(),
            'user_agent' => Request::userAgent(),
            'severity' => $options['severity'] ?? 'info',
        ]);
    }

    /**
     * Registrar login exitoso
     */
    public static function logLogin(User $user): self
    {
        return static::log(
            'login',
            "Usuario {$user->name} inició sesión",
            [
                'model_type' => User::class,
                'model_id' => $user->id,
                'severity' => 'info',
            ]
        );
    }

    /**
     * Registrar logout
     */
    public static function logLogout(User $user): self
    {
        return static::log(
            'logout',
            "Usuario {$user->name} cerró sesión",
            [
                'model_type' => User::class,
                'model_id' => $user->id,
                'severity' => 'info',
            ]
        );
    }

    /**
     * Registrar acceso a función crítica (super_admin)
     */
    public static function logCriticalAccess(string $description, array $properties = []): self
    {
        return static::log(
            'critical_access',
            $description,
            [
                'properties' => $properties,
                'severity' => 'critical',
            ]
        );
    }

    /**
     * Registrar cambio en un modelo
     */
    public static function logModelChange(string $action, Model $model, array $changes = []): self
    {
        return static::log(
            $action,
            "Modelo " . class_basename($model) . " #{$model->id} - {$action}",
            [
                'model_type' => get_class($model),
                'model_id' => $model->id,
                'properties' => [
                    'changes' => $changes,
                    'original' => $model->getOriginal(),
                ],
                'severity' => in_array($action, ['delete', 'force_delete']) ? 'warning' : 'info',
            ]
        );
    }
}
