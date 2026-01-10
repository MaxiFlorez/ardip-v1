<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreUserRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Models\ActivityLog;
use App\Models\Brigada;
use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    /**
     * Display a listing of the users.
     */
    public function index(Request $request)
    {
        $query = User::with(['roles', 'brigada']);

        // Búsqueda por nombre o email
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'LIKE', "%{$search}%")
                  ->orWhere('email', 'LIKE', "%{$search}%");
            });
        }

        // Filtro por rol
        if ($request->filled('role_id')) {
            $query->whereHas('roles', function ($q) use ($request) {
                $q->where('roles.id', $request->role_id);
            });
        }

        // Filtro por estado
        if ($request->filled('active')) {
            $query->where('active', $request->active === '1');
        }

        $users = $query->orderBy('created_at', 'desc')->paginate(15);
        $roles = Role::all();

        // Registrar auditoría
        ActivityLog::log(
            'view_users_list',
            (Auth::user()?->name ?? 'Usuario desconocido') . ' visualizó la lista de usuarios',
            ['severity' => 'info']
        );

        return view('admin.users.index', compact('users', 'roles'));
    }

    /**
     * Show the form for creating a new user.
     */
    public function create()
    {
        $roles = Role::all();
        $brigadas = Brigada::all();

        return view('admin.users.create', compact('roles', 'brigadas'));
    }

    /**
     * Store a newly created user in storage.
     */
    public function store(StoreUserRequest $request)
    {
        // Crear usuario
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'brigada_id' => $request->brigada_id,
            'active' => $request->has('active') ? (bool) $request->active : true,
            'email_verified_at' => now(), // Auto-verificar
        ]);

        // Asignar rol
        $user->roles()->sync([$request->role_id]);

        // Registrar auditoría CRÍTICA
        ActivityLog::log(
            'user_created',
            (Auth::user()?->name ?? 'Usuario desconocido') . " creó el usuario: {$user->name} ({$user->email})",
            [
                'model_type' => User::class,
                'model_id' => $user->id,
                'properties' => [
                    'user_data' => $user->only(['name', 'email', 'brigada_id', 'active']),
                    'role_assigned' => Role::find($request->role_id)->name,
                ],
                'severity' => 'warning',
            ]
        );

        return redirect()
            ->route('admin.users.index')
            ->with('success', "Usuario {$user->name} creado exitosamente.");
    }

    /**
     * Display the specified user.
     */
    public function show(User $user)
    {
        $user->load(['roles', 'brigada']);

        // Obtener historial de actividad del usuario (últimos 50)
        $activityLogs = ActivityLog::where('user_id', $user->id)
            ->orWhere(function ($q) use ($user) {
                $q->where('model_type', User::class)
                  ->where('model_id', $user->id);
            })
            ->orderBy('created_at', 'desc')
            ->paginate(50);

        // Registrar que se visualizó el perfil
        ActivityLog::log(
            'view_user_profile',
            (Auth::user()?->name ?? 'Usuario desconocido') . " visualizó el perfil de {$user->name}",
            [
                'model_type' => User::class,
                'model_id' => $user->id,
                'severity' => 'info',
            ]
        );

        return view('admin.users.show', compact('user', 'activityLogs'));
    }

    /**
     * Show the form for editing the specified user.
     */
    public function edit(User $user)
    {
        $user->load('roles');
        $roles = Role::all();
        $brigadas = Brigada::all();

        // Prevenir auto-edición si es el único super_admin
        if ($user->isSuperAdmin() && User::whereHas('roles', function ($q) {
            $q->where('name', 'super_admin');
        })->count() === 1 && Auth::id() !== $user->id) {
            return redirect()
                ->route('admin.users.index')
                ->with('error', 'No puedes editar al único Super Administrador del sistema.');
        }

        return view('admin.users.edit', compact('user', 'roles', 'brigadas'));
    }

    /**
     * Update the specified user in storage.
     */
    public function update(UpdateUserRequest $request, User $user)
    {
        $originalData = $user->only(['name', 'email', 'brigada_id', 'active']);
        $changes = [];

        // Actualizar datos básicos
        $user->name = $request->name;
        $user->email = $request->email;
        $user->brigada_id = $request->brigada_id;
        $user->active = $request->has('active') ? (bool) $request->active : $user->active;

        // Actualizar contraseña solo si se proporcionó
        if ($request->filled('password')) {
            $user->password = Hash::make($request->password);
            $changes['password'] = 'actualizada';
        }

        $user->save();

        // Actualizar rol
        $oldRole = $user->roles->first()?->name;
        $user->roles()->sync([$request->role_id]);
        $newRole = Role::find($request->role_id)->name;

        if ($oldRole !== $newRole) {
            $changes['role'] = ['from' => $oldRole, 'to' => $newRole];
        }

        // Detectar cambios
        $updatedData = $user->only(['name', 'email', 'brigada_id', 'active']);
        foreach ($updatedData as $key => $value) {
            if ($originalData[$key] !== $value) {
                $changes[$key] = ['from' => $originalData[$key], 'to' => $value];
            }
        }

        // Registrar auditoría CRÍTICA
        ActivityLog::log(
            'user_updated',
            (Auth::user()?->name ?? 'Usuario desconocido') . " actualizó el usuario: {$user->name}",
            [
                'model_type' => User::class,
                'model_id' => $user->id,
                'properties' => [
                    'changes' => $changes,
                ],
                'severity' => 'warning',
            ]
        );

        return redirect()
            ->route('admin.users.index')
            ->with('success', "Usuario {$user->name} actualizado exitosamente.");
    }

    /**
     * Remove the specified user from storage.
     */
    public function destroy(User $user)
    {
        // Prevenir auto-eliminación
        if (Auth::id() === $user->id) {
            return redirect()
                ->route('admin.users.index')
                ->with('error', 'No puedes eliminar tu propia cuenta.');
        }

        // Prevenir eliminación del último super_admin
        if ($user->isSuperAdmin() && User::whereHas('roles', function ($q) {
            $q->where('name', 'super_admin');
        })->count() === 1) {
            return redirect()
                ->route('admin.users.index')
                ->with('error', 'No puedes eliminar al único Super Administrador del sistema.');
        }

        $userName = $user->name;
        $userEmail = $user->email;
        $userId = $user->id;

        // Registrar auditoría CRÍTICA antes de eliminar
        ActivityLog::log(
            'user_deleted',
            (Auth::user()?->name ?? 'Usuario desconocido') . " eliminó el usuario: {$userName} ({$userEmail})",
            [
                'model_type' => User::class,
                'model_id' => $userId,
                'properties' => [
                    'deleted_user' => [
                        'id' => $userId,
                        'name' => $userName,
                        'email' => $userEmail,
                        'roles' => $user->roles->pluck('name')->toArray(),
                    ],
                ],
                'severity' => 'critical',
            ]
        );

        $user->delete();

        return redirect()
            ->route('admin.users.index')
            ->with('success', "Usuario {$userName} eliminado exitosamente.");
    }

    /**
     * Display activity history for a specific user
     */
    public function history(User $user)
    {
        $activityLogs = ActivityLog::where('user_id', $user->id)
            ->orWhere(function ($q) use ($user) {
                $q->where('model_type', User::class)
                  ->where('model_id', $user->id);
            })
            ->orderBy('created_at', 'desc')
            ->paginate(100);

        // Registrar que se consultó el historial
        ActivityLog::log(
            'view_user_history',
            (Auth::user()?->name ?? 'Usuario desconocido') . " consultó el historial de actividad de {$user->name}",
            [
                'model_type' => User::class,
                'model_id' => $user->id,
                'severity' => 'info',
            ]
        );

        return view('admin.users.history', compact('user', 'activityLogs'));
    }
}
