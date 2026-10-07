<x-guest-layout>
    <div id="loading-screen" class="fixed inset-0 z-50 flex items-center justify-center bg-gray-900 bg-opacity-50 hidden">
        <div class="animate-spin rounded-full h-32 w-32 border-t-2 border-b-2 border-white"></div>
    </div>

    <div class="min-h-screen flex flex-col items-center justify-center bg-gradient-to-br from-indigo-500 to-purple-600 relative">
        <div class="absolute inset-0 bg-cover bg-center opacity-10" style="background-image: url('{{ asset('images/pattern.svg') }}');"></div>

        <div class="mb-8">
            <a href="/" class="flex items-center">
                <x-application-logo class="w-16 h-16 fill-current text-white" />
                <span class="ml-3 text-2xl font-bold text-white">TrackSave</span>
            </a>
        </div>

        <div class="w-full max-w-md">
            <form method="POST" action="{{ route('login') }}" class="bg-white p-8 rounded-lg shadow-xl relative z-10">
                @csrf

                <h1 class="text-3xl font-bold text-gray-800 text-center mb-6">Welcome Back</h1>

                <!-- Email Address -->
                <div class="mb-6">
                    <label for="email" class="block mb-2 text-sm font-medium text-gray-700">Email</label>
                    <input id="email" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent" type="email" name="email" :value="old('email')" required autofocus autocomplete="username" />
                    <x-input-error :messages="$errors->get('email')" class="mt-2" />
                </div>

                <!-- Password -->
                <div class="mb-6">
                    <label for="password" class="block mb-2 text-sm font-medium text-gray-700">Password</label>
                    <input id="password" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent" type="password" name="password" required autocomplete="current-password" />
                    <x-input-error :messages="$errors->get('password')" class="mt-2" />
                </div>

                <!-- Remember Me -->
                <div class="flex items-center justify-between mb-6">
                    <label for="remember_me" class="inline-flex items-center">
                        <input id="remember_me" type="checkbox" class="rounded border-gray-300 text-indigo-600 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" name="remember">
                        <span class="ml-2 text-sm text-gray-600">Remember me</span>
                    </label>
                    @if (Route::has('password.request'))
                        <a class="text-sm text-indigo-600 hover:text-indigo-800" href="{{ route('password.request') }}">
                            Forgot password?
                        </a>
                    @endif
                </div>

                <button type="submit" class="w-full bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-3 px-4 rounded-md transition duration-300 mb-4">
                    Log in
                </button>

                <div class="flex items-center justify-center mb-6">
                    <span class="text-sm text-gray-500">Or login with</span>
                </div>
            
                <div class="grid grid-cols-4 gap-4 mb-6">
                    <a href="{{ route('social.redirect', 'google') }}" class="flex items-center justify-center py-2 px-4 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50">
                        <svg class="w-5 h-5 mr-2" /* Google SVG */>
                            <!-- SVG path -->
                        </svg>
                        Google
                    </a>
                    <a href="{{ route('social.redirect', 'facebook') }}" class="flex items-center justify-center py-2 px-4 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50">
                        <svg class="w-5 h-5 mr-2" /* Facebook SVG */>
                            <!-- SVG path -->
                        </svg>
                        Facebook
                    </a>
                    <a href="{{ route('social.redirect', 'github') }}" class="flex items-center justify-center py-2 px-4 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50">
                        <svg class="w-5 h-5 mr-2" /* GitHub SVG */>
                            <!-- SVG path -->
                        </svg>
                        GitHub
                    </a>
                    <a href="{{ route('social.redirect', 'twitter') }}" class="flex items-center justify-center py-2 px-4 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50">
                        <svg class="w-5 h-5 mr-2" /* Twitter SVG */>
                            <!-- SVG path -->
                        </svg>
                        Twitter
                    </a>
                </div>
            
                <div class="text-center">
                    <span class="text-sm text-gray-600">Don't have an account?</span>
                    <a href="{{ route('register') }}" class="text-sm text-indigo-600 hover:text-indigo-800 font-medium ml-1">
                        Register now
                    </a>
                </div>
            </form>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const form = document.querySelector('form');
            const loadingScreen = document.getElementById('loading-screen');

            form.addEventListener('submit', function(e) {
                e.preventDefault();
                loadingScreen.classList.remove('hidden');
                this.submit();
            });
        });
    </script>
</x-guest-layout>