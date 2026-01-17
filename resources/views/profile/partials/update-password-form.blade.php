<form method="post" action="{{ route('password.update') }}" class="space-y-4">
    @csrf
    @method('put')

    <div>
        <label for="update_password_current_password" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
            Contraseña Actual
        </label>
        <input 
            id="update_password_current_password" 
            name="current_password" 
            type="password" 
            class="w-full rounded-md shadow-sm border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-primary-500 dark:focus:border-primary-600 focus:ring-primary-500 dark:focus:ring-primary-600" 
            autocomplete="current-password"
            required
        />
        @error('current_password', 'updatePassword')
            <p class="mt-1 text-sm text-danger-600 dark:text-danger-400">{{ $message }}</p>
        @enderror
    </div>

    <div>
        <label for="update_password_password" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
            Nueva Contraseña
        </label>
        <input 
            id="update_password_password" 
            name="password" 
            type="password" 
            class="w-full rounded-md shadow-sm border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-primary-500 dark:focus:border-primary-600 focus:ring-primary-500 dark:focus:ring-primary-600" 
            autocomplete="new-password"
            required
        />
        @error('password', 'updatePassword')
            <p class="mt-1 text-sm text-danger-600 dark:text-danger-400">{{ $message }}</p>
        @enderror
        <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">
            Mínimo 8 caracteres
        </p>
    </div>

    <div>
        <label for="update_password_password_confirmation" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
            Confirmar Nueva Contraseña
        </label>
        <input 
            id="update_password_password_confirmation" 
            name="password_confirmation" 
            type="password" 
            class="w-full rounded-md shadow-sm border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-primary-500 dark:focus:border-primary-600 focus:ring-primary-500 dark:focus:ring-primary-600" 
            autocomplete="new-password"
            required
        />
        @error('password_confirmation', 'updatePassword')
            <p class="mt-1 text-sm text-danger-600 dark:text-danger-400">{{ $message }}</p>
        @enderror
    </div>

    <div class="flex items-center gap-3 pt-4">
        <button 
            type="submit" 
            class="inline-flex items-center px-4 py-2 bg-success-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-success-700 active:bg-success-900 focus:outline-none focus:ring-2 focus:ring-success-500 focus:ring-offset-2 transition ease-in-out duration-150"
        >
            🔐 Actualizar Contraseña
        </button>
    </div>
</form>
