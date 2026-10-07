<x-guest-layout>
    <div class="min-h-screen flex flex-col justify-center items-center bg-cover bg-center" style="background-image: url('{{ asset('images/register-background.jpeg') }}');">
        <div class="bg-white bg-opacity-90 p-8 rounded-lg shadow-lg w-full max-w-md mx-auto my-12">
            <h2 class="text-3xl font-bold text-center text-orange-500 mb-6">Create an Account</h2>
            
            <form method="POST" action="{{ route('register') }}" id="registration-form">
                @csrf

                <!-- Name -->
                <div>
                    <x-input-label for="name" :value="__('Name')" />
                    <x-text-input id="name" class="block mt-1 w-full" type="text" name="name" :value="old('name')" required autofocus autocomplete="name" aria-required="true" />
                    <x-input-error :messages="$errors->get('name')" class="mt-2" />
                </div>

                <!-- Email Address -->
                <div class="mt-4">
                    <x-input-label for="email" :value="__('Email')" />
                    <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required autocomplete="username" aria-required="true" />
                    <x-input-error :messages="$errors->get('email')" class="mt-2" />
                </div>

                <!-- Password -->
                <div class="mt-4">
                    <x-input-label for="password" :value="__('Password')" />
                    <x-text-input id="password" class="block mt-1 w-full" type="password" name="password" required autocomplete="new-password" aria-required="true" />
                    <x-input-error :messages="$errors->get('password')" class="mt-2" />
                    <div id="password-strength" class="mt-2 h-2 bg-gray-200 rounded-full"></div>
                    <p id="password-strength-text" class="text-sm mt-1"></p>
                </div>

                <!-- Confirm Password -->
                <div class="mt-4">
                    <x-input-label for="password_confirmation" :value="__('Confirm Password')" />
                    <x-text-input id="password_confirmation" class="block mt-1 w-full" type="password" name="password_confirmation" required autocomplete="new-password" aria-required="true" />
                    <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
                </div>

                <!-- Terms and Conditions -->
                <div class="mt-4">
                    <label class="inline-flex items-center">
                        <input type="checkbox" class="form-checkbox" name="terms" required aria-required="true">
                        <span class="ml-2">I agree to the <a href="#" class="text-orange-500 hover:underline">Terms and Conditions</a> and <a href="#" class="text-orange-500 hover:underline">Privacy Policy</a></span>
                    </label>
                </div>

                <div class="flex items-center justify-between mt-6">
                    <a class="text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-orange-500" href="{{ route('login') }}">
                        {{ __('Already registered?') }}
                    </a>

                    <button type="submit" class="bg-orange-500 text-white px-6 py-3 rounded-md font-bold transition duration-300 ease-in-out hover:bg-orange-600 hover:-translate-y-0.5 hover:shadow-md">
                        Register
                    </button>
                </div>
            </form>

            <div class="mt-6 grid grid-cols-2 gap-4">
                <a href="{{ route('social.redirect', 'google') }}" class="flex items-center justify-center bg-red-600 text-white px-4 py-2 rounded-md font-bold transition duration-300 ease-in-out hover:bg-red-700 hover:-translate-y-0.5 hover:shadow-md">
                    <i class="fab fa-google mr-2"></i> Google
                </a>
                <a href="{{ route('social.redirect', 'facebook') }}" class="flex items-center justify-center bg-blue-600 text-white px-4 py-2 rounded-md font-bold transition duration-300 ease-in-out hover:bg-blue-700 hover:-translate-y-0.5 hover:shadow-md">
                    <i class="fab fa-facebook-f mr-2"></i> Facebook
                </a>
                <a href="{{ route('social.redirect', 'apple') }}" class="flex items-center justify-center bg-black text-white px-4 py-2 rounded-md font-bold transition duration-300 ease-in-out hover:bg-gray-800 hover:-translate-y-0.5 hover:shadow-md">
                    <i class="fab fa-apple mr-2"></i> Apple
                </a>
                <a href="{{ route('social.redirect', 'github') }}" class="flex items-center justify-center bg-gray-800 text-white px-4 py-2 rounded-md font-bold transition duration-300 ease-in-out hover:bg-gray-700 hover:-translate-y-0.5 hover:shadow-md">
                    <i class="fab fa-github mr-2"></i> GitHub
                </a>
                
            </div>
        </div>
    </div>

    <script>
        const form = document.getElementById('registration-form');
        const passwordInput = document.getElementById('password');
        const confirmPasswordInput = document.getElementById('password_confirmation');
        const strengthIndicator = document.getElementById('password-strength');
        const strengthText = document.getElementById('password-strength-text');

        form.addEventListener('submit', function(event) {
            if (!validateForm()) {
                event.preventDefault();
            }
        });

        passwordInput.addEventListener('input', function() {
            const strength = calculatePasswordStrength(this.value);
            updateStrengthIndicator(strength);
        });

        function validateForm() {
            let isValid = true;
            // Add more validation rules here
            if (passwordInput.value !== confirmPasswordInput.value) {
                alert("Passwords don't match");
                isValid = false;
            }
            return isValid;
        }

        function calculatePasswordStrength(password) {
            let strength = 0;
            if (password.length >= 8) strength += 1;
            if (password.match(/[a-z]+/)) strength += 1;
            if (password.match(/[A-Z]+/)) strength += 1;
            if (password.match(/[0-9]+/)) strength += 1;
            if (password.match(/[$@#&!]+/)) strength += 1;
            return strength;
        }

        function updateStrengthIndicator(strength) {
            let color = 'bg-red-500';
            let text = 'Weak';
            if (strength > 2) {
                color = 'bg-yellow-500';
                text = 'Medium';
            }
            if (strength > 4) {
                color = 'bg-green-500';
                text = 'Strong';
            }

            strengthIndicator.className = `mt-2 h-2 rounded-full ${color}`;
            strengthIndicator.style.width = `${Math.min(100, strength * 20)}%`;
            strengthText.textContent = `Password strength: ${text}`;
        }
    </script>
</x-guest-layout>