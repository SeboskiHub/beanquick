<x-guest-layout>
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 p-6">

        {{-- Columna izquierda: Registro de cliente --}}
        <div class="bg-white p-6 rounded-lg shadow">
            <h2 class="text-2xl font-semibold mb-4 text-center">Registro de Cliente</h2>

            <form method="POST" action="{{ route('register') }}">
                @csrf

                <!-- Name -->
                <div>
                    <x-input-label for="name" :value="__('Nombre completo')" />
                    <x-text-input id="name" class="block mt-1 w-full" type="text"
                        name="name" :value="old('name')" required autofocus autocomplete="name" />
                    <x-input-error :messages="$errors->get('name')" class="mt-2" />
                </div>

                <!-- Email Address -->
                <div class="mt-4">
                    <x-input-label for="email" :value="__('Correo electrónico')" />
                    <x-text-input id="email" class="block mt-1 w-full" type="email"
                        name="email" :value="old('email')" required autocomplete="username" />
                    <x-input-error :messages="$errors->get('email')" class="mt-2" />
                </div>

                <!-- Password -->
                <div class="mt-4">
                    <x-input-label for="password" :value="__('Contraseña')" />
                    <x-text-input id="password" class="block mt-1 w-full" type="password"
                        name="password" required autocomplete="new-password" />
                    <x-input-error :messages="$errors->get('password')" class="mt-2" />
                </div>

                <!-- Confirm Password -->
                <div class="mt-4">
                    <x-input-label for="password_confirmation" :value="__('Confirmar contraseña')" />
                    <x-text-input id="password_confirmation" class="block mt-1 w-full"
                        type="password" name="password_confirmation" required autocomplete="new-password" />
                    <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
                </div>

                {{-- Rol oculto: por defecto cliente --}}
                <input type="hidden" name="rol" value="cliente">

                <div class="flex items-center justify-end mt-4">
                    <a class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
                        href="{{ route('login') }}">
                        {{ __('¿Ya tienes una cuenta?') }}
                    </a>

                    <x-primary-button class="ms-4">
                        {{ __('Registrarse') }}
                    </x-primary-button>
                </div>
            </form>
        </div>

        {{-- Columna derecha: invitación a registrar empresa --}}
        <div class="bg-gray-100 p-6 rounded-lg shadow flex flex-col justify-center items-center text-center">
            <h2 class="text-xl font-semibold mb-3">¿Quieres registrar tu empresa?</h2>
            <p class="text-gray-600 mb-4">
                Llena el formulario especial de registro empresarial y nos pondremos en contacto para activar tu cuenta.
            </p>
            <a href="{{ route('registro.empresa') }}"
               class="btn btn-dark px-4 py-2 rounded-md bg-gray-900 text-white hover:bg-gray-700 transition">
                Registrar mi empresa
            </a>
        </div>

    </div>
</x-guest-layout>
