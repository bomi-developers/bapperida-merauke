<x-auth>
    <style>
        /* Hide default browser password reveal button */
        input[type="password"]::-ms-reveal,
        input[type="password"]::-ms-clear {
            display: none;
        }

        input[type="password"]::-webkit-credentials-auto-fill-button {
            /* visibility: hidden; pointer-events: none; position: absolute; right: 0; */
        }

        /* For some WebKit browsers */
        input[type="password"]::-webkit-textfield-decoration-container {
            /* */
        }

        /* Standard way to hide webkit reveal button if supported */
        input[type="password"]::-webkit-contacts-auto-fill-button,
        input[type="password"]::-webkit-credentials-auto-fill-button {
            visibility: hidden;
            pointer-events: none;
            position: absolute;
            right: 0;
        }
    </style>

    <div class="flex items-center justify-center min-h-screen p-4">
        <div class="w-full max-w-6xl mx-auto">
            <div class="bg-white rounded-2xl shadow-2xl grid grid-cols-1 lg:grid-cols-2 overflow-hidden">

                <!-- Bagian Kiri -->
                <div
                    class="hidden lg:flex flex-col items-center justify-center text-center p-12 bg-indigo-600 transition-all duration-300 hover:brightness-110 hover:shadow-[0_0_30px_rgba(79,70,229,0.6)]">
                    <a href="{{ url('/') }}">
                        <img src="{{ asset('img/bapperida_white.png') }}" alt="Bapperida Logo" class="mb-8 w-48">
                    </a>

                    <p class="max-w-xs text-white text-lg">
                        Selamat datang di website resmi <br>
                        <span class="font-semibold text-white">Bapperida Merauke, Provinsi Papua Selatan</span>
                    </p>

                    <img src="{{ asset('tailadmin/src/images/illustration/illustration-03.svg') }}" alt="Illustration"
                        class="mt-12 w-sm h-60">
                </div>

                <!-- Bagian Kanan (Form) -->
                <div class="p-8 sm:p-12">
                    <p class="text-sm text-gray-500">Selamat Datang</p>
                    <h1 class="text-4xl font-bold mt-1 text-gray-900">Sign In</h1>
                    @error('g-recaptcha-response')
                        <div class="text-red-700 p-4 bg-red-100 rounded-lg text-sm mt-3">Silakan verifikasi reCAPTCHA
                            {{ $message }}</div>
                    @enderror
                    @error('email')
                        <div class="text-red-700 p-4 bg-red-100 rounded-lg text-sm mt-3">Silakan verifikasi reCAPTCHA
                            {{ $message }}</div>
                    @enderror
                    @error('password')
                        <div class="text-red-700 p-4 bg-red-100 rounded-lg text-sm mt-3">Silakan verifikasi reCAPTCHA
                            {{ $message }}</div>
                    @enderror
                    <form method="POST" action="{{ route('login') }}" class="mt-8 space-y-6">
                        @csrf

                        <!-- Email -->
                        <div>
                            <label for="email" class="text-sm font-medium text-gray-700 block mb-2">Email /
                                NIP</label>
                            <div class="relative">
                                <input type="email" id="email" name="email"
                                    placeholder="Masukkan Email atau NIP"
                                    class="w-full bg-white border border-gray-300 rounded-lg py-3 pl-4 pr-10 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition">
                                <div class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none">
                                    <svg class="w-5 h-5 text-gray-400" xmlns="http://www.w3.org/2000/svg"
                                        viewBox="0 0 20 20" fill="currentColor">
                                        <path
                                            d="M3 4a2 2 0 00-2 2v1.161l8.441 4.221a1.25 1.25 0 001.118 0L19 7.162V6a2 2 0 00-2-2H3z" />
                                        <path
                                            d="M19 8.839l-7.77 3.885a2.75 2.75 0 01-2.46 0L1 8.839V14a2 2 0 002 2h14a2 2 0 002-2V8.839z" />
                                    </svg>
                                </div>
                            </div>
                        </div>

                        <!-- Password -->
                        <div>
                            <label for="password" class="text-sm font-medium text-gray-700 block mb-2">Password</label>
                            <div class="relative">
                                <input type="password" id="password" name="password" placeholder="Password"
                                    class="w-full bg-white border border-gray-300 rounded-lg py-3 pl-4 pr-10 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition">

                                <!-- Tombol Toggle Password -->
                                <button type="button" onclick="togglePassword()"
                                    class="absolute inset-y-0 right-0 flex items-center pr-3 text-gray-400 hover:text-indigo-600 focus:outline-none">
                                    <!-- Icon Mata (Show) -->
                                    <svg id="eye-icon" class="w-5 h-5" xmlns="http://www.w3.org/2000/svg"
                                        viewBox="0 0 20 20" fill="currentColor">
                                        <path d="M10 12a2 2 0 100-4 2 2 0 000 4z" />
                                        <path fill-rule="evenodd"
                                            d="M.458 10C1.732 5.943 5.522 3 10 3s8.268 2.943 9.542 7c-1.274 4.057-5.064 7-9.542 7S1.732 14.057.458 10zM14 10a4 4 0 11-8 0 4 4 0 018 0z"
                                            clip-rule="evenodd" />
                                    </svg>
                                    <!-- Icon Mata Dicoret (Hide) - Default Hidden -->
                                    <svg id="eye-off-icon" class="w-5 h-5 hidden" xmlns="http://www.w3.org/2000/svg"
                                        viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd"
                                            d="M3.707 2.293a1 1 0 00-1.414 1.414l14 14a1 1 0 001.414-1.414l-1.473-1.473A10.014 10.014 0 0019.542 10C18.268 5.943 14.478 3 10 3a9.958 9.958 0 00-4.512 1.074l-1.78-1.781zm4.261 4.26l1.514 1.515a2.003 2.003 0 012.45 2.45l1.514 1.514a4 4 0 00-5.478-5.478z"
                                            clip-rule="evenodd" />
                                        <path
                                            d="M12.454 16.697L9.75 13.992a4 4 0 01-3.742-3.741L2.335 6.578A9.98 9.98 0 00.458 10c1.274 4.057 5.064 7 9.542 7 .847 0 1.669-.105 2.454-.303z" />
                                    </svg>
                                </button>
                            </div>
                        </div>

                        <div class="flex justify-center items-center">
                            {!! NoCaptcha::display() !!}
                        </div>

                        <!-- Tombol Login -->
                        <button type="submit"
                            class="w-full bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-4 px-4 rounded-xl focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition duration-300">
                            Sign In
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    {!! NoCaptcha::renderJs() !!}

    <script>
        function togglePassword() {
            const passwordInput = document.getElementById('password');
            const eyeIcon = document.getElementById('eye-icon');
            const eyeOffIcon = document.getElementById('eye-off-icon');

            if (passwordInput.type === 'password') {
                passwordInput.type = 'text';
                eyeIcon.classList.add('hidden');
                eyeOffIcon.classList.remove('hidden');
            } else {
                passwordInput.type = 'password';
                eyeIcon.classList.remove('hidden');
                eyeOffIcon.classList.add('hidden');
            }
        }
    </script>
</x-auth>
