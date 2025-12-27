@forelse ($beritaTerkini as $terkini)
    <div
        class="bg-white rounded-2xl shadow-lg flex flex-col transition-all duration-300 hover:shadow-2xl hover:shadow-blue-500/20">
        <a href="{{ route('berita.public.show', $terkini) }}">
            <img loading="lazy" src="{{ $terkini->cover_image ? asset('storage/' . $terkini->cover_image) : 'https://placehold.co/600x400/e2e8f0/667eea?text=Berita' }}"
                alt="Cover Berita {{ $terkini->title }}" class="w-full h-40 object-cover rounded-t-2xl">
        </a>
        <div class="p-4 flex flex-col flex-grow">
            <h3 class="text-sm font-bold text-blue-800 mb-2 leading-tight">
                <a href="{{ route('berita.public.show', $terkini) }}" class="hover:underline">
                    {{ Str::limit($terkini->title, 50) }}
                </a>
            </h3>
            <p class="text-xs text-gray-500 mb-3">{{ $terkini->created_at->isoFormat('D MMMM YYYY') }}</p>
            <p class="text-xs text-gray-700 mb-3 leading-relaxed flex-grow">
                {{ Str::limit($terkini->excerpt, 75) }}
            </p>

            {{-- Social Share (sesuai UI) --}}
            <div class="flex items-center justify-between text-sm text-gray-600 border-t border-gray-200 pt-3 mt-auto">
                <span class="text-xs font-medium">Bagikan:</span>
                @php
                    $shareUrl = route('berita.public.show', $terkini);
                    $shareTextWithUrl = urlencode($terkini->title . ' ' . $shareUrl);
                @endphp
                <div class="flex items-center space-x-2 text-gray-500">
                    <a href="https://api.whatsapp.com/send?text={{ $shareTextWithUrl }}" target="_blank"
                        class="social-icon-small whatsapp" title="WhatsApp"><i class="bi bi-whatsapp"></i></a>
                    <a href="https://www.facebook.com/sharer/sharer.php?u={{ $shareUrl }}" target="_blank"
                        class="social-icon-small facebook" title="Facebook"><i class="bi bi-facebook"></i></a>
                    <a href="https://twitter.com/intent/tweet?url={{ $shareUrl }}&text={{ urlencode($terkini->title) }}"
                        target="_blank" class="social-icon-small twitter" title="X"><i
                            class="bi bi-twitter-x"></i></a>
                </div>
            </div>
        </div>
    </div>
@empty
    <div class="text-center py-16 bg-white rounded-xl shadow-lg col-span-1 md:col-span-2 lg:col-span-4">
        <i class="bi bi-search text-6xl text-gray-400"></i>
        <h3 class="mt-4 text-2xl font-semibold text-gray-700">Tidak Ada Berita</h3>
        <p class="mt-2 text-gray-500">Tidak ada berita yang cocok dengan kriteria pencarian Anda.</p>
    </div>
@endforelse