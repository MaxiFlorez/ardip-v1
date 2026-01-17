<form method="post" action="{{ route('profile.update') }}" class="space-y-4">
    @csrf
    @method('patch')

    <div>
        <label for="name" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
            Nombre Completo
        </label>
        <input 
            id="name" 
            name="name" 
            type="text" 
            class="w-full rounded-md shadow-sm border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-primary-500 dark:focus:border-primary-600 focus:ring-primary-500 dark:focus:ring-primary-600" 
            value="{{ old('name', $user->name) }}" 
            required 
            autofocus 
        />
        @error('name')
            <p class="mt-1 text-sm text-danger-600 dark:text-danger-400">{{ $message }}</p>
        @enderror
    </div>

    <div>
        <label for="email" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
            Correo Electrónico
        </label>
        <input 
            id="email" 
            name="email" 
            type="email" 
            class="w-full rounded-md shadow-sm border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-primary-500 dark:focus:border-primary-600 focus:ring-primary-500 dark:focus:ring-primary-600" 
            value="{{ old('email', $user->email) }}" 
            required 
        />
        @error('email')
            <p class="mt-1 text-sm text-danger-600 dark:text-danger-400">{{ $message }}</p>
        @enderror

        @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && ! $user->hasVerifiedEmail())
            <div class="mt-2 p-3 bg-warning-50 dark:bg-warning-900/30 border-l-4 border-warning-500 rounded">
                <p class="text-sm text-warning-800 dark:text-warning-200">
                    Su dirección de correo electrónico no está verificada.
                    <button 
                        form="send-verification" 
                        class="underline text-sm text-warning-700 dark:text-warning-300 hover:text-warning-900 dark:hover:text-warning-100"
                    >
                        Haga clic aquí para reenviar el correo de verificación.
                    </button>
                </p>
            </div>
            
            @if (session('status') === 'verification-link-sent')
                <p class="mt-2 text-sm text-success-600 dark:text-success-400">
                    Se ha enviado un nuevo enlace de verificación a su dirección de correo electrónico.
                </p>
            @endif
        @endif
    </div>

    <div class="flex items-center gap-3 pt-4">
        <button 
            type="submit" 
            class="inline-flex items-center px-4 py-2 bg-success-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-success-700 active:bg-success-900 focus:outline-none focus:ring-2 focus:ring-success-500 focus:ring-offset-2 transition ease-in-out duration-150"
        >
            💾 Guardar Cambios
        </button>
    </div>
</form>

<form id="send-verification" method="post" action="{{ route('verification.send') }}" class="hidden">
    @csrf
</form>
