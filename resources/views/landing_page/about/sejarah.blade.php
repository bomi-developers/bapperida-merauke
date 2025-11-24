@section('meta_title', $meta_title)
@section('meta_description', $meta_description)
<x-landing.layout>
    <section id="first-section" class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 -mt-[300px]">
        <div class="p-8 rounded-2xl shadow-xl">
            {!! $ProfileDinas->sejarah ?? '' !!}
        </div>
    </section>
</x-landing.layout>
