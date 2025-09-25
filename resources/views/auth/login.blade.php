<x-auth>

    <!-- ===== Content Area Start ===== -->
    <div class="relative flex flex-1 flex-col overflow-y-auto overflow-x-hidden">
        <!-- ===== Main Content Start ===== -->
        <main>
            <div class="mx-auto max-w-screen-2xl p-4 md:p-6 2xl:p-10">


                <!-- ====== Forms Section Start -->
                <div
                    class="rounded-sm border border-stroke bg-white shadow-default dark:border-strokedark dark:bg-boxdark">
                    <div class="flex flex-wrap items-center">
                        <div class="hidden w-full xl:block xl:w-1/2">
                            <div class="px-26 py-17.5 text-center">
                                <a class="mb-5.5 inline-block" href="{{ url('/') }}">
                                    <img class="hidden dark:block" width="250"
                                        src="{{ asset('img/bapperida_white.png') }}" alt="Logo" />
                                    <img class="dark:hidden" width="250" src="{{ asset('img/bapperida_black.png') }}"
                                        alt="Logo" />
                                </a>

                                <p class="font-medium 2xl:px-20">
                                    Selamat datang di website resmi Bapperida Merauke, Provinsi Papua Selatan
                                </p>

                                <span class="mt-15 inline-block">
                                    <img src="{{ asset('tailadmin/src/images/illustration/illustration-03.svg') }}"
                                        alt="illustration" />
                                </span>
                            </div>
                        </div>
                        <div class="w-full border-stroke dark:border-strokedark xl:w-1/2 xl:border-l-2">
                            <div class="w-full p-4 sm:p-12.5 xl:p-17.5">
                                <div
                                    class="flex w-full border-l-6 border-warning bg-warning bg-opacity-[15%] px-4 py-5 shadow-md dark:bg-[#1B1B24] dark:bg-opacity-30 md:p-5 mb-5">
                                    <div
                                        class="mr-5 flex h-9 w-9 items-center justify-center rounded-lg bg-warning bg-opacity-30">
                                        <svg width="19" height="16" viewBox="0 0 19 16" fill="none"
                                            xmlns="http://www.w3.org/2000/svg">
                                            <path
                                                d="M1.50493 16H17.5023C18.6204 16 19.3413 14.9018 18.8354 13.9735L10.8367 0.770573C10.2852 -0.256858 8.70677 -0.256858 8.15528 0.770573L0.156617 13.9735C-0.334072 14.8998 0.386764 16 1.50493 16ZM10.7585 12.9298C10.7585 13.6155 10.2223 14.1433 9.45583 14.1433C8.6894 14.1433 8.15311 13.6155 8.15311 12.9298V12.9015C8.15311 12.2159 8.6894 11.688 9.45583 11.688C10.2223 11.688 10.7585 12.2159 10.7585 12.9015V12.9298ZM8.75236 4.01062H10.2548C10.6674 4.01062 10.9127 4.33826 10.8671 4.75288L10.2071 10.1186C10.1615 10.5049 9.88572 10.7455 9.50142 10.7455C9.11929 10.7455 8.84138 10.5028 8.79579 10.1186L8.13574 4.75288C8.09449 4.33826 8.33984 4.01062 8.75236 4.01062Z"
                                                fill="#FBBF24"></path>
                                        </svg>
                                    </div>
                                    <div class="w-full">
                                        <h5 class="mb-3 text-lg font-bold text-[#9D5425]">
                                            Website Maintenance
                                        </h5>
                                        <p class="leading-relaxed text-[#D0915C]">
                                            Saat ini website sedang dalam perbaikan. Beberapa fitur mungkin tidak
                                            tersedia.
                                        </p>
                                    </div>
                                </div>
                                <span class="mb-1.5 block font-medium">Start for</span>
                                <h2 class="mb-9 text-2xl font-bold text-black dark:text-white sm:text-title-xl2">
                                    Sign In
                                </h2>

                                <form method="POST" action="{{ route('login') }}">
                                    @csrf
                                    <div class="mb-4">
                                        <label class="mb-2.5 block font-medium text-black dark:text-white">Email</label>
                                        <div class="relative">
                                            <input type="email" placeholder="Enter your email" name="email"
                                                value="{{ old('email') }}"
                                                class="w-full rounded-lg border border-stroke bg-transparent py-4 pl-6 pr-10 outline-none focus:border-primary focus-visible:shadow-none dark:border-form-strokedark dark:bg-form-input dark:focus:border-primary" />

                                            <span class="absolute right-4 top-4">
                                                <svg class="fill-current" width="22" height="22"
                                                    viewBox="0 0 22 22" fill="none"
                                                    xmlns="http://www.w3.org/2000/svg">
                                                    <g opacity="0.5">
                                                        <path
                                                            d="M19.2516 3.30005H2.75156C1.58281 3.30005 0.585938 4.26255 0.585938 5.46567V16.6032C0.585938 17.7719 1.54844 18.7688 2.75156 18.7688H19.2516C20.4203 18.7688 21.4172 17.8063 21.4172 16.6032V5.4313C21.4172 4.26255 20.4203 3.30005 19.2516 3.30005ZM19.2516 4.84692C19.2859 4.84692 19.3203 4.84692 19.3547 4.84692L11.0016 10.2094L2.64844 4.84692C2.68281 4.84692 2.71719 4.84692 2.75156 4.84692H19.2516ZM19.2516 17.1532H2.75156C2.40781 17.1532 2.13281 16.8782 2.13281 16.5344V6.35942L10.1766 11.5157C10.4172 11.6875 10.6922 11.7563 10.9672 11.7563C11.2422 11.7563 11.5172 11.6875 11.7578 11.5157L19.8016 6.35942V16.5688C19.8703 16.9125 19.5953 17.1532 19.2516 17.1532Z"
                                                            fill="" />
                                                    </g>
                                                </svg>
                                            </span>
                                        </div>
                                    </div>

                                    <div class="mb-6">
                                        <label class="mb-2.5 block font-medium text-black dark:text-white">Re-type
                                            Password</label>
                                        <div class="relative">
                                            <input type="password" placeholder="6+ Characters, 1 Capital letter"
                                                name="password" required autocomplete="current-password"
                                                class="w-full rounded-lg border border-stroke bg-transparent py-4 pl-6 pr-10 outline-none focus:border-primary focus-visible:shadow-none dark:border-form-strokedark dark:bg-form-input dark:focus:border-primary" />

                                            <span class="absolute right-4 top-4">
                                                <svg class="fill-current" width="22" height="22"
                                                    viewBox="0 0 22 22" fill="none"
                                                    xmlns="http://www.w3.org/2000/svg">
                                                    <g opacity="0.5">
                                                        <path
                                                            d="M16.1547 6.80626V5.91251C16.1547 3.16251 14.0922 0.825009 11.4797 0.618759C10.0359 0.481259 8.59219 0.996884 7.52656 1.95938C6.46094 2.92188 5.84219 4.29688 5.84219 5.70626V6.80626C3.84844 7.18438 2.33594 8.93751 2.33594 11.0688V17.2906C2.33594 19.5594 4.19219 21.3813 6.42656 21.3813H15.5016C17.7703 21.3813 19.6266 19.525 19.6266 17.2563V11C19.6609 8.93751 18.1484 7.21876 16.1547 6.80626ZM8.55781 3.09376C9.31406 2.40626 10.3109 2.06251 11.3422 2.16563C13.1641 2.33751 14.6078 3.98751 14.6078 5.91251V6.70313H7.38906V5.67188C7.38906 4.70938 7.80156 3.78126 8.55781 3.09376ZM18.1141 17.2906C18.1141 18.7 16.9453 19.8688 15.5359 19.8688H6.46094C5.05156 19.8688 3.91719 18.7344 3.91719 17.325V11.0688C3.91719 9.52189 5.15469 8.28438 6.70156 8.28438H15.2953C16.8422 8.28438 18.1141 9.52188 18.1141 11V17.2906Z"
                                                            fill="" />
                                                        <path
                                                            d="M10.9977 11.8594C10.5852 11.8594 10.207 12.2031 10.207 12.65V16.2594C10.207 16.6719 10.5508 17.05 10.9977 17.05C11.4102 17.05 11.7883 16.7063 11.7883 16.2594V12.6156C11.7883 12.2031 11.4102 11.8594 10.9977 11.8594Z"
                                                            fill="" />
                                                    </g>
                                                </svg>
                                            </span>
                                        </div>
                                    </div>

                                    <div class="mb-5">
                                        <input type="submit" value="Sign In"
                                            class="w-full cursor-pointer rounded-lg border border-primary bg-primary p-4 font-medium text-white transition hover:bg-opacity-90" />
                                    </div>

                                    {{-- <div class="mt-6 text-center">
                                        <p class="font-medium">
                                            Don’t have any account?
                                            <a href="{{ route('register') }}" class="text-primary">Sign Up</a>
                                        </p>
                                    </div> --}}
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- ====== Forms Section End -->
            </div>
        </main>
        <!-- ===== Main Content End ===== -->
    </div>
    <!-- ===== Content Area End ===== -->
</x-auth>
