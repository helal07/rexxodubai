@php
    $siteName = $siteSettings['siteName'] ?? $siteSettings['site_name'] ?? 'IT Solution';
    $logoUrl = $siteSettings['logo_url'] ?? $siteSettings['site_logo'] ?? '';
    $currentUser = Auth::user();
    $userName = $currentUser->name ?? 'Md Al Helal';
    $userEmail = $currentUser->email ?? 'admin@helal.com';
    $userRole = $currentUser->role ?? ($currentUser && $currentUser->is_admin ? 'Super Administrator' : 'Staff');
    $userAvatar = $currentUser ? ($currentUser->avatar_url ?? $currentUser->avatar) : '';
    $todayDate = date('d-m-Y');
@endphp

<!-- 1. FULL WIDTH MASTER TOP APP BAR -->
<header class="w-full bg-[#4338ca] text-white px-4 sm:px-6 py-2.5 flex items-center justify-between gap-4 sticky top-0 z-40 shadow-md border-b border-[#3730a3] shrink-0 h-14">
    <!-- LEFT: Logo / Brand + Company Name + Live Status + Sidebar Toggle -->
    <div class="flex items-center gap-3.5 min-w-0">
        <!-- Sidebar Toggle Icon -->
        <button type="button" onclick="toggleSidebarCollapse()" class="p-1.5 rounded-lg text-white/80 hover:text-white hover:bg-white/10 transition-colors cursor-pointer" title="Toggle Sidebar">
            <i data-lucide="menu" class="w-5 h-5"></i>
        </button>

        <!-- Brand / Company Identity -->
        <a href="{{ url('/admin/dashboard') }}" class="flex items-center gap-2.5 group shrink-0">
            @if(!empty($logoUrl))
                <img src="{{ $logoUrl }}" alt="Logo" class="h-8 max-w-[120px] object-contain rounded bg-white/10 p-0.5" onerror="this.style.display='none'; this.nextElementSibling.classList.remove('hidden');" />
                <div class="hidden w-8 h-8 rounded-lg bg-white/20 text-white flex items-center justify-center font-bold text-xs">
                    <i data-lucide="sparkles" class="w-4 h-4"></i>
                </div>
            @else
                <div class="w-8 h-8 rounded-lg bg-white/20 text-white flex items-center justify-center font-bold text-xs">
                    <i data-lucide="sparkles" class="w-4 h-4"></i>
                </div>
            @endif

            <div class="flex items-center gap-2">
                <span class="text-[15px] font-bold text-white tracking-wide group-hover:text-indigo-200 transition-colors truncate">
                    {{ $siteName }}
                </span>
                <!-- Green Online Status Dot -->
                <span class="w-2.5 h-2.5 rounded-full bg-emerald-400 border-2 border-[#4338ca] inline-block shrink-0 shadow-xs" title="System Online"></span>
            </div>
        </a>
    </div>

    <!-- RIGHT: POS Shortcut, Live Date, Notifications, Theme, Cache, Profile -->
    <div class="flex items-center gap-2 sm:gap-3 shrink-0">
        <!-- POS / Create Sale Button -->
        <a href="{{ url('/admin/dashboard#create_order') }}" onclick="if(typeof switchSection === 'function'){ switchSection('create_order'); return false; }" title="Create Sale / POS Terminal" class="px-3 py-1.5 bg-emerald-500 hover:bg-emerald-600 border border-emerald-400 text-white rounded-lg text-[12px] font-bold flex items-center gap-1.5 transition-all shadow-xs cursor-pointer">
            <i data-lucide="shopping-cart" class="w-3.5 h-3.5"></i>
            <span class="hidden sm:inline">POS / Create Sale</span>
        </a>

        <!-- Live Date (e.g. 08-08-2026) -->
        <div class="hidden md:flex items-center gap-1.5 px-3 py-1.5 bg-white/10 border border-white/15 text-white/90 rounded-lg text-[12px] font-medium select-none">
            <i data-lucide="calendar" class="w-3.5 h-3.5 text-white/70"></i>
            <span class="font-mono">{{ $todayDate }}</span>
        </div>

        <!-- Notifications Bell -->
        <button type="button" onclick="showToastNotice('You have no new notifications.')" class="p-2 rounded-lg text-white/80 hover:text-white hover:bg-white/10 transition-colors relative cursor-pointer" title="Notifications">
            <i data-lucide="bell" class="w-4 h-4"></i>
            <span class="w-2 h-2 rounded-full bg-rose-400 absolute top-1.5 right-1.5"></span>
        </button>

        <!-- Theme Switcher Pill -->
        <div class="flex items-center bg-black/20 border border-white/10 rounded-lg p-0.5 gap-0.5">
            <button type="button" onclick="setAdminTheme('default')" id="theme-btn-default" title="Default Theme" class="p-1.5 rounded-md text-white transition-all bg-white/25 shadow-2xs cursor-pointer">
                <i data-lucide="sun" class="w-3.5 h-3.5"></i>
            </button>
            <button type="button" onclick="setAdminTheme('light')" id="theme-btn-light" title="Light Theme" class="p-1.5 rounded-md text-white/70 hover:text-white hover:bg-white/10 transition-all cursor-pointer">
                <i data-lucide="cloud" class="w-3.5 h-3.5"></i>
            </button>
            <button type="button" onclick="setAdminTheme('night')" id="theme-btn-night" title="Dark Theme" class="p-1.5 rounded-md text-white/70 hover:text-white hover:bg-white/10 transition-all cursor-pointer">
                <i data-lucide="moon" class="w-3.5 h-3.5"></i>
            </button>
        </div>

        <!-- Quick Clear Cache -->
        <button type="button" onclick="globalClearCache()" id="globalClearCacheBtn" title="Clear Cache" class="p-2 rounded-lg text-white/80 hover:text-white hover:bg-white/10 transition-colors cursor-pointer">
            <i data-lucide="refresh-cw" class="w-4 h-4"></i>
        </button>

        <!-- Divider -->
        <div class="w-px h-6 bg-white/20 hidden sm:block"></div>

        <!-- User Profile Pill -->
        <div class="relative" id="profileDropdownContainer">
            <button type="button" onclick="toggleProfileDropdown()" class="flex items-center gap-2 pl-1.5 pr-2.5 py-1 rounded-lg hover:bg-white/10 border border-transparent hover:border-white/15 transition-all cursor-pointer group">
                <div class="w-7 h-7 rounded-full overflow-hidden bg-white text-[#4338ca] flex items-center justify-center font-bold text-[11px] shadow-sm shrink-0 border border-white/30">
                    @if(!empty($userAvatar))
                        <img src="{{ $userAvatar }}" alt="{{ $userName }}" class="w-full h-full object-cover" onerror="this.style.display='none'; this.nextElementSibling.classList.remove('hidden');" />
                        <span class="hidden">{{ strtoupper(substr($userName, 0, 2)) }}</span>
                    @else
                        <span>{{ strtoupper(substr($userName, 0, 2)) }}</span>
                    @endif
                </div>
                <span class="text-[12px] font-bold text-white group-hover:text-indigo-100 transition-colors leading-tight hidden sm:inline">
                    {{ $userName }}
                </span>
                <i data-lucide="chevron-down" class="w-3.5 h-3.5 text-white/70 group-hover:text-white transition-transform duration-200" id="profileChevron"></i>
            </button>

            <!-- Profile Dropdown Menu -->
            <div id="profileDropdownMenu" class="hidden absolute right-0 top-full mt-2 w-64 bg-white border border-slate-200 rounded-2xl shadow-2xl py-2 z-50 animate-fade-in text-slate-800 divide-y divide-slate-100">
                <!-- User Header -->
                <div class="px-4 py-3 bg-gradient-to-r from-slate-50 to-indigo-50/50 rounded-t-xl">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-full overflow-hidden bg-[#4338ca] text-white flex items-center justify-center font-bold text-[12px] shrink-0 border-2 border-white shadow-xs">
                            @if(!empty($userAvatar))
                                <img src="{{ $userAvatar }}" alt="{{ $userName }}" class="w-full h-full object-cover" onerror="this.style.display='none'; this.nextElementSibling.classList.remove('hidden');" />
                                <span class="hidden">{{ strtoupper(substr($userName, 0, 2)) }}</span>
                            @else
                                <span>{{ strtoupper(substr($userName, 0, 2)) }}</span>
                            @endif
                        </div>
                        <div class="min-w-0 flex-1">
                            <p class="text-[13px] font-bold text-slate-900 truncate leading-tight">{{ $userName }}</p>
                            <p class="text-[10.5px] text-slate-500 truncate font-mono">{{ $userEmail }}</p>
                            <span class="inline-flex items-center gap-1 mt-1 px-2 py-0.5 rounded-full text-[9.5px] font-bold bg-indigo-100 text-[#4338ca]">
                                <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                                {{ $userRole }}
                            </span>
                        </div>
                    </div>
                </div>

                <!-- Profile & Account Actions (Standard Management) -->
                <div class="py-1.5">
                    @if(request()->is('admin/dashboard') || request()->is('admin'))
                        <button type="button" onclick="switchSection('profile'); toggleProfileDropdown();" class="w-full flex items-center gap-3 px-4 py-2.5 text-[12px] font-semibold text-slate-700 hover:bg-indigo-50 hover:text-[#4338ca] transition-colors cursor-pointer text-left group">
                            <div class="w-7 h-7 rounded-lg bg-indigo-50 group-hover:bg-indigo-100 flex items-center justify-center text-[#4338ca] shrink-0 transition-colors">
                                <i data-lucide="user" class="w-4 h-4"></i>
                            </div>
                            <div>
                                <span class="block text-[12.5px] font-bold text-slate-800 group-hover:text-[#4338ca]">My Profile & Info</span>
                                <span class="block text-[10px] text-slate-400 font-normal">Photo, address & contacts</span>
                            </div>
                        </button>
                    @else
                        <a href="{{ url('/admin/dashboard#profile') }}" class="w-full flex items-center gap-3 px-4 py-2.5 text-[12px] font-semibold text-slate-700 hover:bg-indigo-50 hover:text-[#4338ca] transition-colors cursor-pointer text-left group">
                            <div class="w-7 h-7 rounded-lg bg-indigo-50 group-hover:bg-indigo-100 flex items-center justify-center text-[#4338ca] shrink-0 transition-colors">
                                <i data-lucide="user" class="w-4 h-4"></i>
                            </div>
                            <div>
                                <span class="block text-[12.5px] font-bold text-slate-800 group-hover:text-[#4338ca]">My Profile & Info</span>
                                <span class="block text-[10px] text-slate-400 font-normal">Photo, address & contacts</span>
                            </div>
                        </a>
                    @endif

                    @if(request()->is('admin/dashboard') || request()->is('admin'))
                        <button type="button" onclick="switchSection('profile_password'); toggleProfileDropdown();" class="w-full flex items-center gap-3 px-4 py-2.5 text-[12px] font-semibold text-slate-700 hover:bg-indigo-50 hover:text-[#4338ca] transition-colors cursor-pointer text-left group">
                            <div class="w-7 h-7 rounded-lg bg-amber-50 group-hover:bg-amber-100 flex items-center justify-center text-amber-600 shrink-0 transition-colors">
                                <i data-lucide="key-round" class="w-4 h-4"></i>
                            </div>
                            <div>
                                <span class="block text-[12.5px] font-bold text-slate-800 group-hover:text-[#4338ca]">Security & Password</span>
                                <span class="block text-[10px] text-slate-400 font-normal">Update login credentials</span>
                            </div>
                        </button>
                    @else
                        <a href="{{ url('/admin/dashboard#profile_password') }}" class="w-full flex items-center gap-3 px-4 py-2.5 text-[12px] font-semibold text-slate-700 hover:bg-indigo-50 hover:text-[#4338ca] transition-colors cursor-pointer text-left group">
                            <div class="w-7 h-7 rounded-lg bg-amber-50 group-hover:bg-amber-100 flex items-center justify-center text-amber-600 shrink-0 transition-colors">
                                <i data-lucide="key-round" class="w-4 h-4"></i>
                            </div>
                            <div>
                                <span class="block text-[12.5px] font-bold text-slate-800 group-hover:text-[#4338ca]">Security & Password</span>
                                <span class="block text-[10px] text-slate-400 font-normal">Update login credentials</span>
                            </div>
                        </a>
                    @endif

                    @if(request()->is('admin/dashboard') || request()->is('admin'))
                        <button type="button" onclick="switchSection('users_management'); toggleProfileDropdown();" class="w-full flex items-center gap-3 px-4 py-2.5 text-[12px] font-semibold text-slate-700 hover:bg-indigo-50 hover:text-[#4338ca] transition-colors cursor-pointer text-left group">
                            <div class="w-7 h-7 rounded-lg bg-emerald-50 group-hover:bg-emerald-100 flex items-center justify-center text-emerald-600 shrink-0 transition-colors">
                                <i data-lucide="users-round" class="w-4 h-4"></i>
                            </div>
                            <div>
                                <span class="block text-[12.5px] font-bold text-slate-800 group-hover:text-[#4338ca]">Admin Users & Staff</span>
                                <span class="block text-[10px] text-slate-400 font-normal">Full user management</span>
                            </div>
                        </button>
                    @else
                        <a href="{{ url('/admin/dashboard#users_management') }}" class="w-full flex items-center gap-3 px-4 py-2.5 text-[12px] font-semibold text-slate-700 hover:bg-indigo-50 hover:text-[#4338ca] transition-colors cursor-pointer text-left group">
                            <div class="w-7 h-7 rounded-lg bg-emerald-50 group-hover:bg-emerald-100 flex items-center justify-center text-emerald-600 shrink-0 transition-colors">
                                <i data-lucide="users-round" class="w-4 h-4"></i>
                            </div>
                            <div>
                                <span class="block text-[12.5px] font-bold text-slate-800 group-hover:text-[#4338ca]">Admin Users & Staff</span>
                                <span class="block text-[10px] text-slate-400 font-normal">Full user management</span>
                            </div>
                        </a>
                    @endif
                </div>

                <!-- Sign Out -->
                <div class="p-1.5 bg-slate-50/50 rounded-b-xl">
                    <form action="{{ url('/logout') }}" method="POST">
                        @csrf
                        <button type="submit" class="w-full flex items-center gap-3 px-3 py-2 text-[12px] font-bold text-rose-600 hover:bg-rose-50 rounded-xl transition-colors cursor-pointer">
                            <div class="w-7 h-7 rounded-lg bg-rose-50 flex items-center justify-center text-rose-600 shrink-0">
                                <i data-lucide="log-out" class="w-4 h-4"></i>
                            </div>
                            <span>Sign Out</span>
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</header>

<script>
    function toggleSidebarCollapse() {
        const sidebar = document.getElementById('mainAdminSidebar');
        if (sidebar) {
            sidebar.classList.toggle('hidden');
        }
    }

    function toggleProfileDropdown() {
        const menu = document.getElementById('profileDropdownMenu');
        const chevron = document.getElementById('profileChevron');
        if (menu) {
            menu.classList.toggle('hidden');
            if (chevron) chevron.classList.toggle('rotate-180');
        }
    }

    document.addEventListener('click', function(e) {
        const container = document.getElementById('profileDropdownContainer');
        const menu = document.getElementById('profileDropdownMenu');
        const chevron = document.getElementById('profileChevron');
        if (container && menu && !container.contains(e.target)) {
            menu.classList.add('hidden');
            if (chevron) chevron.classList.remove('rotate-180');
        }
    });

    function globalClearCache() {
        const btn = document.getElementById('globalClearCacheBtn');
        const ogContent = btn ? btn.innerHTML : '';
        if (btn) {
            btn.innerHTML = `<i data-lucide="loader-2" class="w-4 h-4 animate-spin"></i>`;
            if (window.lucide) lucide.createIcons();
        }

        fetch('/admin/settings', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Accept': 'application/json'
            },
            body: JSON.stringify({ _action: 'clear_cache' })
        })
        .finally(() => {
            setTimeout(() => {
                if (btn) {
                    btn.innerHTML = ogContent;
                    if (window.lucide) lucide.createIcons();
                }
                if (typeof showToastNotice === 'function') {
                    showToastNotice('Cache Cleared Successfully! 🚀');
                } else {
                    alert('Cache Cleared Successfully!');
                }
            }, 500);
        });
    }
</script>
