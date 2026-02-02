<x-guest-layout>
    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <form method="POST" action="{{ route('login') }}" class="auth-form">
        @csrf

        <!-- Form Header -->
        <div class="form-header">
            <h2 class="form-title">Welcome Back</h2>
            <p class="form-subtitle">Sign in to your account</p>
        </div>

        <!-- Email Address -->
        <div class="input-group">
            <x-input-label for="email" :value="__('Email')" />
            <div class="input-wrapper">
                <svg class="input-icon" xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <rect x="3" y="4" width="18" height="16" rx="2"></rect>
                    <path d="M3 7l9 6 9-6"></path>
                </svg>
                <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required autofocus autocomplete="username" />
            </div>
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <!-- Password -->
        <div class="input-group">
            <x-input-label for="password" :value="__('Password')" />
            <div class="input-wrapper">
                <svg class="input-icon" xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <rect x="3" y="11" width="18" height="11" rx="2" ry="2"></rect>
                    <path d="M7 11V7a5 5 0 0 1 10 0v4"></path>
                </svg>
                <x-text-input id="password" class="block mt-1 w-full"
                            type="password"
                            name="password"
                            required autocomplete="current-password" />
            </div>
            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        {{-- <!-- Remember Me -->
        <div class="form-options">
            <label for="remember_me" class="checkbox-label">
                <input id="remember_me" type="checkbox" class="checkbox-input" name="remember">
                <span class="checkbox-custom"></span>
                <span class="checkbox-text">{{ __('Remember me') }}</span>
            </label>
        </div> --}}

        <!-- Form Actions -->
        <div class="form-actions">
            <x-primary-button class="auth-button">
                {{ __('Log in') }}
            </x-primary-button>
        </div>

        <!-- Register Link -->
        @if (Route::has('register'))
            <div class="auth-switch">
                Don't have an account?
                <a href="{{ route('register') }}">Sign up</a>
            </div>
        @endif
    </form>

    <style>
        .auth-form {
            max-width: 420px;
            margin: 0 auto;
            padding: 40px;
            background: white;
            border-radius: 12px;
            box-shadow: 0 8px 30px rgba(0, 0, 0, 0.08);
        }

        .form-header {
            text-align: center;
            margin-bottom: 32px;
        }

        .form-title {
            font-size: 26px;
            font-weight: 700;
            color: #2d3748;
            margin-bottom: 8px;
        }

        .form-subtitle {
            font-size: 14px;
            color: #718096;
        }

        .input-group {
            margin-bottom: 24px;
        }

        .input-group label {
            display: block;
            color: #4a5568;
            font-weight: 500;
            font-size: 14px;
            margin-bottom: 6px;
        }

        .input-wrapper {
            position: relative;
        }

        .input-icon {
            position: absolute;
            left: 16px;
            top: 50%;
            transform: translateY(-50%);
            color: #a0aec0;
        }

        .input-wrapper input {
            width: 100%;
            padding: 14px 16px 14px 48px;
            border: 1.5px solid #e2e8f0;
            border-radius: 8px;
            font-size: 15px;
            color: #2d3748;
            transition: border-color 0.2s;
            background: #f8fafc;
        }

        .input-wrapper input:focus {
            outline: none;
            border-color: #4299e1;
            background: white;
        }

        .form-options {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 24px;
        }

        .checkbox-label {
            display: flex;
            align-items: center;
            cursor: pointer;
            position: relative;
            user-select: none;
        }

        .checkbox-input {
            position: absolute;
            opacity: 0;
            cursor: pointer;
            height: 0;
            width: 0;
        }

        .checkbox-custom {
            height: 18px;
            width: 18px;
            background-color: white;
            border: 1.5px solid #cbd5e0;
            border-radius: 4px;
            margin-right: 8px;
            transition: all 0.2s;
            position: relative;
        }

        .checkbox-input:checked ~ .checkbox-custom {
            background-color: #4299e1;
            border-color: #4299e1;
        }

        .checkbox-input:checked ~ .checkbox-custom::after {
            content: "";
            position: absolute;
            left: 5px;
            top: 1px;
            width: 5px;
            height: 9px;
            border: solid white;
            border-width: 0 2px 2px 0;
            transform: rotate(45deg);
        }

        .checkbox-text {
            color: #4a5568;
            font-size: 14px;
        }

        .auth-link {
            color: #4299e1;
            text-decoration: none;
            font-size: 14px;
            font-weight: 500;
        }

        .auth-link:hover {
            text-decoration: underline;
        }

        .form-actions {
            margin-top: 8px;
        }

        .auth-button {
            width: 100%;
            background: #4299e1;
            color: white;
            padding: 12px;
            border: none;
            border-radius: 8px;
            font-size: 15px;
            font-weight: 600;
            cursor: pointer;
            transition: background-color 0.2s;
        }

        .auth-button:hover {
            background: #3182ce;
        }

        .auth-switch {
            text-align: center;
            margin-top: 24px;
            padding-top: 24px;
            border-top: 1px solid #e2e8f0;
            color: #718096;
            font-size: 14px;
        }

        .auth-switch a {
            color: #4299e1;
            text-decoration: none;
            font-weight: 600;
            margin-left: 4px;
        }

        .auth-switch a:hover {
            text-decoration: underline;
        }

        /* Error messages styling */
        .mt-2 {
            margin-top: 8px;
        }

        .mt-2 span {
            color: #e53e3e;
            font-size: 13px;
        }

        /* Responsive */
        @media (max-width: 480px) {
            .auth-form {
                padding: 30px 20px;
                margin: 20px;
            }

            .form-options {
                flex-direction: column;
                gap: 12px;
                align-items: flex-start;
            }

            .auth-link {
                align-self: flex-end;
            }
        }
    </style>
</x-guest-layout>
