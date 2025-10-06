<aside id="sidebar"
    class="w-64 -translate-x-full fixed lg:static lg:translate-x-0 h-full bg-slate-800 text-slate-300 transition-transform duration-300 ease-in-out z-30">
    <div class="flex flex-col h-full">
        <!-- Sidebar Header -->
        <div class="flex items-center justify-between p-6">
            <a href="{{ route('home') }}">
                <img src="{{ asset('img/bapperida_white.png') }}" alt="Logo Dark">
            </a>
        </div>

        <!-- Navigation -->
        <nav class="flex-1 px-4 py-4 space-y-4 overflow-y-auto no-scrollbar">
            <div>
                <h3 class="px-4 mb-2 text-xs font-semibold tracking-wider text-slate-400 uppercase">Menu</h3>
                <ul class="space-y-1">
                    <li>
                        <a href="{{ route('home') }}"
                            class="flex items-center gap-3 px-4 py-2 rounded-lg 
                            {{ request()->routeIs('home')
                                ? 'bg-slate-700 text-white font-semibold'
                                : 'bg-transparent  dark:text-slate-300 hover:bg-slate-700 dark:hover:bg-slate-700' }}">
                            <i class="bi bi-grid-1x2-fill"></i>
                            <span>Dashboard</span>
                        </a>
                    </li>
                </ul>
            </div>
            <div>
                <h3 class="px-4 mb-2 text-xs font-semibold tracking-wider text-slate-400 uppercase">Pegawai</h3>
                <ul class="space-y-1">
                    <li>
                        <a href="{{ route('admin.bidang.index') }}"
                            class="flex items-center gap-3 px-4 py-2 rounded-lg {{ request()->routeIs('admin.bidang.index')
                                ? 'bg-slate-700 text-white font-semibold'
                                : 'bg-transparent  dark:text-slate-300 hover:bg-slate-700 dark:hover:bg-slate-700' }}"><i
                                class="bi bi-building"></i><span>Bidang</span></a>
                    </li>
                    <li>
                        <a href="{{ route('admin.golongan') }}"
                            class="flex items-center gap-3 px-4 py-2 rounded-lg {{ request()->routeIs('admin.golongan')
                                ? 'bg-slate-700 text-white font-semibold'
                                : 'bg-transparent  dark:text-slate-300 hover:bg-slate-700 dark:hover:bg-slate-700' }}"><i
                                class="bi bi-bar-chart-steps"></i><span>Golongan</span></a>
                    </li>
                    <li>
                        <a href="{{ route('admin.jabatan') }}"
                            class="flex items-center gap-3 px-4 py-2 rounded-lg {{ request()->routeIs('admin.jabatan')
                                ? 'bg-slate-700 text-white font-semibold'
                                : 'bg-transparent  dark:text-slate-300 hover:bg-slate-700 dark:hover:bg-slate-700' }}"><i
                                class="bi bi-person-badge"></i><span>Jabatan</span></a>

                    </li>
                    <li>
                        <a href="{{ route('admin.pegawai') }}"
                            class="flex items-center gap-3 px-4 py-2 rounded-lg {{ request()->routeIs('admin.pegawai')
                                ? 'bg-slate-700 text-white font-semibold'
                                : 'bg-transparent  dark:text-slate-300 hover:bg-slate-700 dark:hover:bg-slate-700' }}"><i
                                class="bi bi-people-fill"></i><span>Data Pegawai</span></a>
                    </li>
                </ul>
            </div>
            <div>
                <h3 class="px-4 mb-2 text-xs font-semibold tracking-wider text-slate-400 uppercase">Settings
                </h3>
                <ul class="space-y-1">
                    <li>
                        <a href="{{ route('profile') }}"
                            class="flex items-center gap-3 px-4 py-2 rounded-lg {{ request()->routeIs('profile')
                                ? 'bg-slate-700 text-white font-semibold'
                                : 'bg-transparent  dark:text-slate-300 hover:bg-slate-700 dark:hover:bg-slate-700' }}"><i
                                class="bi bi-person-badge"></i><span>Akun Setting</span></a>
                    </li>
                    <li>
                        <a href="{{ route('website-setting') }}"
                            class="flex items-center gap-3 px-4 py-2 rounded-lg {{ request()->routeIs('website-setting')
                                ? 'bg-slate-700 text-white font-semibold'
                                : 'bg-transparent  dark:text-slate-300 hover:bg-slate-700 dark:hover:bg-slate-700' }}"><i
                                class="bi bi-info-circle-fill"></i><span>Profile Dinas</span></a>
                    </li>
                    <li>
                        <a href="#"
                            class="flex items-center gap-3 px-4 py-2 rounded-lg hover:bg-slate-700 transition-colors"><i
                                class="bi bi-gear-fill"></i><span>Website Settings</span></a>
                    </li>
                </ul>
            </div>
            <div>
                <h3 class="px-4 mb-2 text-xs font-semibold tracking-wider text-slate-400 uppercase">Tracker
                    System</h3>
                <ul class="space-y-1">
                    <li><a href="#"
                            class="flex items-center gap-3 px-4 py-2 rounded-lg hover:bg-slate-700 transition-colors"><i
                                class="bi bi-box-arrow-in-right"></i><span>Log Login</span></a></li>
                    <li><a href="#"
                            class="flex items-center gap-3 px-4 py-2 rounded-lg hover:bg-slate-700 transition-colors"><i
                                class="bi bi-activity"></i><span>Log Aktifitas</span></a></li>
                    <li><a href="#"
                            class="flex items-center gap-3 px-4 py-2 rounded-lg hover:bg-slate-700 transition-colors"><i
                                class="bi bi-eye-fill"></i><span>Log Tampilan</span></a></li>
                </ul>
            </div>
        </nav>
    </div>
</aside>
