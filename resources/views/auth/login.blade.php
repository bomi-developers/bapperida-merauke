<x-auth>
    <div class="flex items-center justify-center min-h-screen p-4">
        <div class="w-full max-w-6xl mx-auto">
            <div class="bg-white rounded-2xl shadow-2xl grid grid-cols-1 lg:grid-cols-2 overflow-hidden">

                <!-- Bagian Kiri -->
                <div class="hidden lg:flex flex-col items-center justify-center text-center p-12 bg-indigo-600">
                    <img src="{{ asset('img/bapperida_white.png') }}" alt="Bapperida Logo" class="mb-8 w-48">

                    <p class="max-w-xs text-white text-lg">
                        Selamat datang di website resmi <br>
                        <span class="font-semibold text-white">Bapperida Merauke, Provinsi Papua Selatan</span>
                    </p>

                    <img src="{{ asset('tailadmin/src/images/illustration/illustration-03.svg') }}" alt="Illustration"
                        class="mt-12 w-full max-w-sm">
                </div>

                <!-- Bagian Kanan (Form) -->
                <div class="p-8 sm:p-12">
                    <p class="text-sm text-gray-500">Selamat Datang</p>
                    <h1 class="text-4xl font-bold mt-1 text-gray-900">Sign In</h1>

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
                                <div class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none">
                                    <svg class="w-5 h-5 text-gray-400" xmlns="http://www.w3.org/2000/svg"
                                        viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd"
                                            d="M10 1a4.5 4.5 0 00-4.5 4.5V9H5a2 2 0 00-2 2v6a2 2 0 002 2h10a2 2 0 002-2v-6a2 2 0 00-2-2h-.5V5.5A4.5 4.5 0 0010 1zm3 8V5.5a3 3 0 10-6 0V9h6z"
                                            clip-rule="evenodd" />
                                    </svg>
                                </div>
                            </div>
                        </div>

                        <!-- Tombol Login -->
                        <button type="submit"
                            class="w-full bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-3 px-4 rounded-lg focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition duration-300">
                            Sign In
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-auth>
