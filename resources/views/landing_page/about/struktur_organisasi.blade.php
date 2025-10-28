<x-landing.layout>
    <section id="first-section" class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 -mt-[300px]">
        <div class="w-full max-w-7xl px-4">
            <img src="{{ $ProfileDinas->struktur_organisasi
                ? asset('storage/' . $ProfileDinas->struktur_organisasi)
                : asset('assets/LogoKabMerauke.png') }}"
                loading="lazy" alt="Struktur Organisasi BAPPERIDA Merauke"
                class="mx-auto w-full h-auto object-contain rounded-2xl shadow-lg">
        </div>
    </section>
</x-landing.layout>
