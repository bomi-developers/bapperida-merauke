<!DOCTYPE html>
<html lang="en">
{{-- head --}}
<x-landing.head></x-landing.head>

<body class="bg-gray-100">
    {{-- navbar --}}
    <x-landing.navbar></x-landing.navbar>
    {{-- hading for title --}}
    <x-landing.heading></x-landing.heading>
    {{ $slot }}
    {{-- footer page --}}
    <x-landing.footer></x-landing.footer>
    {{-- more script --}}
    <x-landing.script></x-landing.script>
</body>

</html>
