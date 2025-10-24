<x-landing.layout>
    <section id="first-section" class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 -mt-[300px]">
        <div class="p-8 rounded-2xl shadow-xl">
            <div class="flex flex-col lg:flex-row lg:space-x-12 items-center justify-center">
                <div class="flex-1 w-full lg:w-1-2 mb-12 lg:mb-0">
                    <div class="mb-12">
                        <h2 class="text-4xl font-bold text-blue-800 sm:text-5xl">VISI</h2>
                        <p class="mt-4 text-lg text-gray-700 text-justify">
                            {!! $ProfileDinas->visi !!}
                        </p>
                    </div>
                    <div>
                        <h2 class="text-4xl font-bold text-blue-800 sm:text-5xl">MISI</h2>
                        <div class="mt-6 space-y-6 text-lg leading-8 text-gray-700">
                            {!! $ProfileDinas->misi !!}
                        </div>
                    </div>
                </div>
                <div class="w-full lg:w-1/2 flex justify-center">
                    <img src="{{ asset('assets/LogoKabMerauke.png') }}" alt="Logo Kabupaten Merauke"
                        class="max-w-md w-full h-auto rounded-lg object-cover">
                </div>
            </div>
        </div>
    </section>
</x-landing.layout>
