<x-layout>
    <x-header></x-header>

    <main>
        <div class="mx-auto max-w-screen-2xl p-4 md:p-6 2xl:p-10">
            <div class="mx-auto max-w-3xl">

                <!-- Breadcrumb -->
                <div class="mb-6 flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
                    <h2 class="text-title-md2 font-bold text-black dark:text-white">
                        Website Settings
                    </h2>

                    <nav>
                        <ol class="flex items-center gap-2">
                            <li>
                                <a class="font-medium" href="{{ route('home') }}">Dashboard /</a>
                            </li>
                            <li class="font-medium text-primary">Website Settings</li>
                        </ol>
                    </nav>
                </div>

                <!-- Settings Section -->
                <div class="grid grid-cols-5 gap-8">
                    <!-- Left Column: Website Info -->
                    <div class="col-span-5 xl:col-span-3">
                        <div
                            class="rounded-sm border border-stroke bg-white shadow-default dark:border-strokedark dark:bg-boxdark">
                            <div class="border-b border-stroke px-7 py-4 dark:border-strokedark">
                                <h3 class="font-medium text-black dark:text-white">General Information</h3>
                            </div>
                            <div class="p-7">
                                <form action="{{ route('website-setting.update') }}" method="POST"
                                    enctype="multipart/form-data">
                                    @csrf
                                    @method('PUT')

                                    <!-- Nama Kantor -->
                                    <div class="mb-5.5">
                                        <label class="mb-3 block text-sm font-medium text-black dark:text-white">Nama
                                            Kantor</label>
                                        <input type="text" name="nama_kantor"
                                            value="{{ $settings->nama_kantor ?? '' }}"
                                            class="w-full rounded border border-stroke bg-gray px-4 py-3 font-medium text-black focus:border-primary focus-visible:outline-none dark:border-strokedark dark:bg-meta-4 dark:text-white">
                                    </div>

                                    <!-- Alamat -->
                                    <div class="mb-5.5">
                                        <label
                                            class="mb-3 block text-sm font-medium text-black dark:text-white">Alamat</label>
                                        <textarea name="alamat" rows="3"
                                            class="w-full rounded border border-stroke bg-gray px-4 py-3 font-medium text-black focus:border-primary dark:border-strokedark dark:bg-meta-4 dark:text-white">{{ $settings->alamat ?? '' }}</textarea>
                                    </div>

                                    <!-- Telepon & Email -->
                                    <div class="mb-5.5 flex gap-4">
                                        <input type="text" name="telepon" placeholder="Telepon"
                                            value="{{ $settings->telepon ?? '' }}"
                                            class="w-1/2 rounded border border-stroke bg-gray px-4 py-3 font-medium text-black focus:border-primary dark:border-strokedark dark:bg-meta-4 dark:text-white">
                                        <input type="email" name="email" placeholder="Email"
                                            value="{{ $settings->email ?? '' }}"
                                            class="w-1/2 rounded border border-stroke bg-gray px-4 py-3 font-medium text-black focus:border-primary dark:border-strokedark dark:bg-meta-4 dark:text-white">
                                    </div>

                                    <!-- Website -->
                                    <div class="mb-5.5">
                                        <label
                                            class="mb-3 block text-sm font-medium text-black dark:text-white">Website</label>
                                        <input type="text" name="website" value="{{ $settings->website ?? '' }}"
                                            class="w-full rounded border border-stroke bg-gray px-4 py-3 font-medium text-black focus:border-primary dark:border-strokedark dark:bg-meta-4 dark:text-white">
                                    </div>

                                    <!-- Logo & Favicon -->
                                    <div class="mb-5.5 flex gap-4">
                                        <div class="w-1/2">
                                            <label
                                                class="mb-3 block text-sm font-medium text-black dark:text-white">Logo</label>
                                            <input type="file" name="logo" class="w-full">
                                            @if ($settings->logo)
                                                <img src="{{ asset($settings->logo) }}" alt="Logo"
                                                    class="mt-2 h-16">
                                            @endif
                                        </div>
                                        <div class="w-1/2">
                                            <label
                                                class="mb-3 block text-sm font-medium text-black dark:text-white">Favicon</label>
                                            <input type="file" name="favicon" class="w-full">
                                            @if ($settings->favicon)
                                                <img src="{{ asset($settings->favicon) }}" alt="Favicon"
                                                    class="mt-2 h-10">
                                            @endif
                                        </div>
                                    </div>

                                    <!-- Maps -->
                                    <div class="mb-5.5">
                                        <label class="mb-3 block text-sm font-medium text-black dark:text-white">Maps
                                            Embed (iframe)</label>
                                        <textarea name="maps_iframe" rows="4"
                                            class="w-full rounded border border-stroke bg-gray px-4 py-3 font-medium text-black focus:border-primary dark:border-strokedark dark:bg-meta-4 dark:text-white">{{ $settings->maps_iframe ?? '' }}</textarea>
                                    </div>

                                    <!-- Media Sosial -->
                                    <div class="mb-5.5 grid grid-cols-2 gap-4">
                                        <input type="text" name="facebook" placeholder="Facebook"
                                            value="{{ $settings->facebook ?? '' }}"
                                            class="rounded border border-stroke bg-gray px-4 py-3 text-black">
                                        <input type="text" name="instagram" placeholder="Instagram"
                                            value="{{ $settings->instagram ?? '' }}"
                                            class="rounded border border-stroke bg-gray px-4 py-3 text-black">
                                        <input type="text" name="twitter" placeholder="Twitter"
                                            value="{{ $settings->twitter ?? '' }}"
                                            class="rounded border border-stroke bg-gray px-4 py-3 text-black">
                                        <input type="text" name="linkedin" placeholder="LinkedIn"
                                            value="{{ $settings->linkedin ?? '' }}"
                                            class="rounded border border-stroke bg-gray px-4 py-3 text-black">
                                        <input type="text" name="youtube" placeholder="YouTube"
                                            value="{{ $settings->youtube ?? '' }}"
                                            class="rounded border border-stroke bg-gray px-4 py-3 text-black">
                                    </div>

                                    <!-- SEO Metadata -->
                                    <div class="mb-5.5">
                                        <label class="mb-3 block text-sm font-medium text-black dark:text-white">Meta
                                            Title</label>
                                        <input type="text" name="meta_title"
                                            value="{{ $settings->meta_title ?? '' }}"
                                            class="w-full rounded border border-stroke bg-gray px-4 py-3 text-black">
                                    </div>
                                    <div class="mb-5.5">
                                        <label class="mb-3 block text-sm font-medium text-black dark:text-white">Meta
                                            Description</label>
                                        <textarea name="meta_description" rows="3"
                                            class="w-full rounded border border-stroke bg-gray px-4 py-3 text-black">{{ $settings->meta_description ?? '' }}</textarea>
                                    </div>
                                    <div class="mb-5.5">
                                        <label class="mb-3 block text-sm font-medium text-black dark:text-white">Meta
                                            Keywords</label>
                                        <input type="text" name="meta_keywords"
                                            value="{{ $settings->meta_keywords ?? '' }}"
                                            class="w-full rounded border border-stroke bg-gray px-4 py-3 text-black">
                                    </div>

                                    <!-- Maintenance Mode -->
                                    <div class="mb-5.5 flex items-center gap-3">
                                        <input type="checkbox" name="is_maintenance" value="1"
                                            {{ $settings->is_maintenance ? 'checked' : '' }} class="w-5 h-5">
                                        <label class="text-black dark:text-white">Enable Maintenance Mode</label>
                                    </div>

                                    <!-- Submit -->
                                    <div class="flex justify-end gap-4.5">
                                        <button type="submit"
                                            class="flex justify-center rounded border border-stroke px-6 py-2 font-medium text-black hover:shadow-1 dark:border-strokedark dark:text-white">
                                            Cancel
                                        </button>
                                        <button type="submit"
                                            class="flex justify-center rounded bg-primary px-6 py-2 font-medium text-gray hover:bg-opacity-90">
                                            Save Settings
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>

                    <!-- Right Column: Preview (Optional) -->
                    <div class="col-span-5 xl:col-span-2">
                        <div
                            class="rounded-sm border border-stroke bg-white shadow-default dark:border-strokedark dark:bg-boxdark p-7">
                            <h3 class="font-medium text-black dark:text-white mb-4">Website Preview</h3>
                            <div class="mb-4">
                                <strong>Logo:</strong>
                                @if ($settings->logo)
                                    <img src="{{ asset($settings->logo) }}" class="h-16" alt="Logo">
                                @endif
                            </div>
                            <div class="mb-4">
                                <strong>Favicon:</strong>
                                @if ($settings->favicon)
                                    <img src="{{ asset($settings->favicon) }}" class="h-10" alt="Favicon">
                                @endif
                            </div>
                            <div class="mb-4">
                                <strong>Maps:</strong>
                                {!! $settings->maps_iframe ?? 'Maps not set' !!}
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </main>
</x-layout>
