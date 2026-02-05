<x-guest-layout>
    <form method="POST" action="{{ route('register') }}" class="register-form">
        @csrf

        <!-- Form Header -->
        <div class="form-header">
            <h2 class="form-title">Create Account</h2>
            <p class="form-subtitle">Join us today</p>
        </div>

        <!-- Name -->
        <div class="input-group">
            <x-input-label for="name" :value="__('Name')" />
            <div class="input-wrapper">
                <svg class="input-icon" xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path>
                    <circle cx="12" cy="7" r="4"></circle>
                </svg>
                <x-text-input id="name" class="block mt-1 w-full" type="text" name="name" :value="old('name')" required autofocus autocomplete="name" />
            </div>
            <x-input-error :messages="$errors->get('name')" class="mt-2" />
        </div>

        <!-- Email Address -->
        <div class="input-group">
            <x-input-label for="email" :value="__('Email')" />
            <div class="input-wrapper">
                <svg class="input-icon" xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <rect x="3" y="4" width="18" height="16" rx="2"></rect>
                    <path d="M3 7l9 6 9-6"></path>
                </svg>
                <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required autocomplete="username" />
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
                            required autocomplete="new-password" />
            </div>
            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <!-- Confirm Password -->
        <div class="input-group">
            <x-input-label for="password_confirmation" :value="__('Confirm Password')" />
            <div class="input-wrapper">
                <svg class="input-icon" xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path>
                    <polyline points="22 4 12 14.01 9 11.01"></polyline>
                </svg>
                <x-text-input id="password_confirmation" class="block mt-1 w-full"
                            type="password"
                            name="password_confirmation" required autocomplete="new-password" />
            </div>
            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
        </div>

        <!-- Form Actions -->
        <div class="form-actions">
            <div class="actions-wrapper">
                <a class="login-link" href="{{ route('login') }}">
                    {{ __('Already registered?') }}
                </a>

                <x-primary-button class="register-button">
                    {{ __('Register') }}
                </x-primary-button>
            </div>
        </div>
    </form>

    <style>
        .register-form {
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
            z-index: 10;
        }

        .input-wrapper input {
            width: 100%;
            padding: 14px 16px 14px 48px;
            border: 1.5px solid #e2e8f0;
            border-radius: 8px;
            font-size: 15px;
            color: #2d3748;
            transition: all 0.2s ease;
            background: #f8fafc;
            box-sizing: border-box;
        }

        .input-wrapper input:focus {
            outline: none;
            border-color: #4299e1;
            background: white;
            box-shadow: 0 0 0 3px rgba(66, 153, 225, 0.1);
        }

        .form-actions {
            margin-top: 32px;
        }

        .actions-wrapper {
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 16px;
        }

        .login-link {
            color: #4299e1;
            text-decoration: none;
            font-size: 14px;
            font-weight: 500;
            white-space: nowrap;
            padding: 8px 4px;
        }

        .login-link:hover {
            text-decoration: underline;
        }

        .register-button {
            background: #4299e1;
            color: white;
            padding: 12px 28px;
            border: none;
            border-radius: 8px;
            font-size: 15px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.2s ease;
            white-space: nowrap;
            min-width: 120px;
            text-align: center;
        }

        .register-button:hover {
            background: #3182ce;
            transform: translateY(-1px);
        }

        /* Error messages styling */
        .mt-2 {
            margin-top: 8px;
        }

        .mt-2 span {
            color: #e53e3e;
            font-size: 13px;
            display: block;
            margin-top: 4px;
        }

        /* Responsive Design */
        @media (max-width: 768px) {
            .register-form {
                padding: 32px 24px;
                margin: 20px 16px;
                max-width: 100%;
            }

            .form-title {
                font-size: 24px;
            }

            .actions-wrapper {
                gap: 12px;
            }

            .register-button {
                padding: 12px 24px;
                min-width: 100px;
            }
        }

        @media (max-width: 640px) {
            .register-form {
                padding: 28px 20px;
                margin: 16px 12px;
            }

            .form-title {
                font-size: 22px;
            }

            .input-wrapper input {
                padding: 12px 16px 12px 44px;
                font-size: 14px;
            }

            .input-icon {
                width: 18px;
                height: 18px;
                left: 14px;
            }

            .actions-wrapper {
                flex-direction: column;
                align-items: stretch;
                gap: 20px;
            }

            .login-link {
                text-align: center;
                order: 2;
                padding: 10px 0;
            }

            .register-button {
                order: 1;
                width: 100%;
                padding: 14px 28px;
                min-width: auto;
            }
        }

        @media (max-width: 480px) {
            .register-form {
                padding: 24px 16px;
                margin: 12px 8px;
                border-radius: 10px;
            }

            .form-header {
                margin-bottom: 28px;
            }

            .form-title {
                font-size: 20px;
                margin-bottom: 6px;
            }

            .form-subtitle {
                font-size: 13px;
            }

            .input-group {
                margin-bottom: 20px;
            }

            .input-group label {
                font-size: 13px;
            }

            .actions-wrapper {
                gap: 16px;
            }

            .login-link {
                font-size: 13px;
            }
        }

        /* For very small screens */
        @media (max-width: 360px) {
            .register-form {
                padding: 20px 14px;
                margin: 8px 6px;
            }

            .form-title {
                font-size: 18px;
            }

            .register-button {
                padding: 12px 20px;
                font-size: 14px;
            }
        }
    </style>
</x-guest-layout>
