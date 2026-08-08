<!DOCTYPE html>
<html lang="en" data-theme="{{ $siteSettings['admin_theme'] ?? 'default' }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $siteSettings['siteName'] ?? 'RaaxO BD' }} — Navigation Menu Builder</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!-- Dynamic Favicon -->
    @php
        $adminFavicon = !empty($siteSettings['favicon_url']) ? $siteSettings['favicon_url'] : (!empty($siteSettings['site_favicon']) ? $siteSettings['site_favicon'] : '/uploads/settings/favicon_1785930191.ico');
    @endphp
    <link rel="icon" id="admin-favicon" href="{{ $adminFavicon }}">

    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://unpkg.com/lucide@latest"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&family=Fraunces:wght@600;700&family=JetBrains+Mono:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Inter', system-ui, -apple-system, sans-serif; }
        .font-serif { font-family: 'Fraunces', Georgia, serif; }
        .font-mono { font-family: 'JetBrains Mono', monospace; }
        
        /* Custom Luxury Scrollbar */
        ::-webkit-scrollbar { width: 6px; height: 6px; }
        ::-webkit-scrollbar-track { background: rgba(241, 245, 249, 0.6); }
        ::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 4px; }
        ::-webkit-scrollbar-thumb:hover { background: #0284c7; }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(6px); }
            to { opacity: 1; transform: translateY(0); }
        }
        .animate-fade-in { animation: fadeIn 0.35s cubic-bezier(0.16, 1, 0.3, 1) forwards; }

        .card-elevated {
            transition: transform 0.25s cubic-bezier(0.16, 1, 0.3, 1), box-shadow 0.25s cubic-bezier(0.16, 1, 0.3, 1);
        }
        .card-elevated:hover {
            transform: translateY(-2px);
            box-shadow: 0 12px 28px -4px rgba(2, 132, 199, 0.14);
        }

        .submenu-panel {
            overflow: hidden;
            max-height: 0;
            opacity: 0;
            transition: max-height 0.35s cubic-bezier(0.4, 0, 0.2, 1), opacity 0.25s ease, margin-top 0.35s ease;
            margin-top: 0;
        }
        .submenu-panel.submenu-open {
            max-height: 220px;
            opacity: 1;
            margin-top: 0.35rem;
        }
        .submenu-chevron {
            display: inline-flex;
            align-items: center;
            transition: transform 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }
        .submenu-chevron.chevron-open {
            transform: rotate(180deg);
        }

        /* Night Mode Overrides */
        body.theme-night {
            background: linear-gradient(135deg, #07090E 0%, #0D121F 50%, #05070B 100%) !important;
            color: #f1f5f9 !important;
        }
        body.theme-night aside,
        body.theme-night header,
        body.theme-night .theme-card,
        body.theme-night .theme-panel {
            background-color: rgba(13, 18, 31, 0.9) !important;
            border-color: #1E283D !important;
            color: #f1f5f9 !important;
        }
        body.theme-night table thead {
            background-color: #090D17 !important;
            border-color: #1E283D !important;
            color: #94a3b8 !important;
        }
        body.theme-night table tbody tr {
            border-color: #1E283D !important;
        }
        body.theme-night table tbody tr:hover {
            background-color: rgba(19, 26, 43, 0.6) !important;
        }
        body.theme-night input,
        body.theme-night select,
        body.theme-night textarea {
            background-color: #131A2B !important;
            border-color: #1E283D !important;
            color: #ffffff !important;
        }
        body.theme-night .theme-subcard {
            background-color: #131A2B !important;
            border-color: #1E283D !important;
        }

        /* Light Mode Clean */
        body.theme-light {
            background: linear-gradient(135deg, #f8fafc 0%, #f1f5f9 50%, #e2e8f0 100%) !important;
            color: #0f172a !important;
        }
    </style>
</head>
<body class="bg-[#f8fafc] text-[#0f172a] font-sans flex flex-col min-h-screen relative overflow-x-hidden selection:bg-[#4338ca] selection:text-white">

    <!-- 1. FULL WIDTH MASTER TOP APP BAR -->
    @include('admin.partials.header')

    <!-- 2. APP WORKSPACE -->
    <div class="flex flex-1 w-full min-h-0 relative overflow-hidden">
        <!-- 1. LEFT SIDEBAR MENU BAR -->
        @include('admin.partials.sidebar', ['activePage' => 'menus', 'siteSettings' => $siteSettings])

        <!-- 2. MAIN CONTENT WRAPPER -->
        <main class="flex-1 p-6 lg:p-8 w-full space-y-6 relative z-10 overflow-y-auto max-h-[calc(100vh-3.5rem)]">

        <!-- Top Navigation Header -->
        <header class="bg-white/90 backdrop-blur-xl border border-[#38bdf8]/30 rounded-2xl p-6 flex flex-col md:flex-row justify-between items-start md:items-center gap-4 shadow-sm animate-fade-in">
            <div class="flex items-center gap-4">
                <div class="w-12 h-12 rounded-2xl bg-gradient-to-br from-[#0284c7] to-[#0369a1] text-white flex items-center justify-center shadow-lg shadow-[#0284c7]/20">
                    <i data-lucide="menu" class="w-6 h-6"></i>
                </div>
                <div>
                    <div class="flex items-center gap-2">
                        <span class="text-[11px] font-bold uppercase tracking-widest text-[#0284c7]">NAVIGATION ARCHITECTURE</span>
                        @php
                            $itemsList = $items ?? ($menuItems ?? collect([]));
                        @endphp
                        <span class="px-2 py-0.5 rounded-full text-[10px] font-bold uppercase bg-[#e0f2fe] text-[#0284c7] border border-[#bae6fd]">
                            {{ count($itemsList) }} ITEMS
                        </span>
                    </div>
                    <h2 class="text-[22px] font-serif font-bold text-[#0f172a] uppercase tracking-tight">
                        Header Navigation Menu Builder
                    </h2>
                </div>
            </div>

            <div class="flex items-center flex-wrap gap-2.5">
                <a href="{{ url('/admin/categories') }}" class="px-4 py-2.5 bg-white hover:bg-[#f8fafc] border border-[#cbd5e1] text-[#475569] text-[12px] font-bold uppercase tracking-wider rounded-xl transition-all shadow-xs flex items-center gap-2">
                    <i data-lucide="folder-tree" class="w-4 h-4"></i> Categories
                </a>
                <a href="{{ url('/admin/products') }}" class="px-4 py-2.5 bg-[#0284c7] hover:bg-[#0369a1] text-white text-[12px] font-bold uppercase tracking-wider rounded-xl transition-all shadow-md shadow-[#0284c7]/20 flex items-center gap-2">
                    <i data-lucide="package" class="w-4 h-4"></i> Products
                </a>
                <a href="{{ url('/') }}" target="_blank" class="px-4 py-2.5 bg-emerald-600 hover:bg-emerald-700 text-white text-[12px] font-bold uppercase tracking-wider rounded-xl transition-all shadow-xs flex items-center gap-2">
                    <i data-lucide="external-link" class="w-4 h-4"></i> Storefront ↗
                </a>
            </div>
        </header>

        <!-- Flash Success Notification -->
        @if (session('success'))
            <div class="p-4 bg-emerald-50 border border-emerald-300 text-emerald-800 text-[13px] rounded-xl flex items-center gap-3 font-semibold shadow-xs animate-fade-in">
                <div class="p-1.5 bg-emerald-500 text-white rounded-lg">
                    <i data-lucide="check" class="w-4 h-4"></i>
                </div>
                <span>{{ session('success') }}</span>
            </div>
        @endif

        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 animate-fade-in">
            <!-- Add New Menu Item Form -->
            <div class="lg:col-span-5 bg-white/90 backdrop-blur-xl border border-[#38bdf8]/30 p-7 rounded-2xl space-y-6 shadow-sm">
                <div class="flex items-center gap-3 border-b border-[#e2e8f0] pb-4">
                    <div class="p-2.5 bg-[#e0f2fe] text-[#0284c7] rounded-xl">
                        <i data-lucide="plus-circle" class="w-5 h-5"></i>
                    </div>
                    <div>
                        <h3 class="text-[16px] font-serif font-bold text-[#0f172a] uppercase tracking-wide">
                            Create Navigation Link
                        </h3>
                        <p class="text-[11px] text-[#64748b]">Add a menu link to the storefront header bar</p>
                    </div>
                </div>

                <form action="{{ url('/admin/menus') }}" method="POST" class="space-y-4">
                    @csrf
                    <div>
                        <label class="text-[11px] uppercase font-bold text-[#475569] tracking-wider block mb-1.5">
                            PARENT ITEM (LEAVE EMPTY FOR TOP MENU)
                        </label>
                        <select name="parent_id" class="w-full border border-[#cbd5e1] px-4 py-2.5 rounded-xl text-[13px] font-medium text-[#0f172a] bg-white focus:outline-none focus:border-[#0284c7] transition-all shadow-xs">
                            <option value="">None (Top-Level Menu Item)</option>
                            @if(isset($parentItems))
                                @foreach ($parentItems as $pItem)
                                    <option value="{{ $pItem->id }}">📌 {{ $pItem->label }}</option>
                                @endforeach
                            @endif
                        </select>
                    </div>

                    <div>
                        <label class="text-[11px] uppercase font-bold text-[#475569] tracking-wider block mb-1.5">
                            MENU LABEL NAME *
                        </label>
                        <input type="text" name="label" required placeholder="e.g. Discovery Set, Men, Gifts, Extrait" class="w-full border border-[#cbd5e1] px-4 py-2.5 rounded-xl text-[13px] font-semibold text-[#0f172a] bg-white focus:outline-none focus:border-[#0284c7] transition-all shadow-xs">
                    </div>

                    <div>
                        <label class="text-[11px] uppercase font-bold text-[#475569] tracking-wider block mb-1.5">
                            TARGET URL LINK *
                        </label>
                        <input type="text" name="url" required placeholder="/perfumes?category=gifts" class="w-full border border-[#cbd5e1] px-4 py-2.5 rounded-xl text-[13px] font-mono text-[#0f172a] bg-white focus:outline-none focus:border-[#0284c7] transition-all shadow-xs">
                    </div>

                    <div>
                        <label class="text-[11px] uppercase font-bold text-[#475569] tracking-wider block mb-1.5">
                            SORT ORDER POSITION
                        </label>
                        <input type="number" name="sort_order" value="1" class="w-full border border-[#cbd5e1] px-4 py-2.5 rounded-xl text-[13px] font-medium text-[#0f172a] bg-white focus:outline-none focus:border-[#0284c7] transition-all shadow-xs">
                    </div>

                    <button type="submit" class="w-full bg-[#0284c7] hover:bg-[#0369a1] text-white py-3.5 rounded-xl text-[12px] font-bold uppercase tracking-wider cursor-pointer transition-all shadow-md shadow-[#0284c7]/20 flex items-center justify-center gap-2">
                        <i data-lucide="save" class="w-4 h-4"></i> Save Navigation Link
                    </button>
                </form>
            </div>

            <!-- Existing Menu Items List & Inline Editors -->
            <div class="lg:col-span-7 bg-white/90 backdrop-blur-xl border border-[#38bdf8]/30 p-7 rounded-2xl space-y-6 shadow-sm">
                <div class="flex items-center justify-between border-b border-[#e2e8f0] pb-4">
                    <div class="flex items-center gap-3">
                        <div class="p-2.5 bg-[#e0f2fe] text-[#0284c7] rounded-xl">
                            <i data-lucide="list-tree" class="w-5 h-5"></i>
                        </div>
                        <div>
                            <h3 class="text-[16px] font-serif font-bold text-[#0f172a] uppercase tracking-wide">
                                Active Navigation Items
                            </h3>
                            <p class="text-[11px] text-[#64748b]">Live items rendered on storefront header</p>
                        </div>
                    </div>
                    <span class="text-[11px] font-mono text-[#0284c7] bg-[#f0f9ff] px-3 py-1 rounded-full border border-[#bae6fd]">
                        {{ count($itemsList) }} ACTIVE ITEMS
                    </span>
                </div>

                <div class="space-y-3">
                    @forelse ($itemsList as $item)
                        <div class="bg-[#f8fafc] border border-[#e2e8f0] p-4 rounded-xl flex flex-col md:flex-row justify-between items-start md:items-center gap-3 hover:border-[#cbd5e1] transition-all shadow-2xs">
                            <form action="{{ url('/admin/menus/' . $item->id) }}" method="POST" class="flex-1 flex flex-col sm:flex-row items-center gap-2.5 w-full">
                                @csrf
                                @method('PUT')
                                <input type="text" name="label" value="{{ $item->label }}" class="bg-white border border-[#cbd5e1] text-[#0f172a] px-3.5 py-2 text-[12px] font-bold rounded-lg w-full sm:w-44 focus:outline-none focus:border-[#0284c7] shadow-xs">
                                <input type="text" name="url" value="{{ $item->url }}" class="bg-white border border-[#cbd5e1] text-[#475569] font-mono px-3.5 py-2 text-[11px] rounded-lg w-full sm:w-56 focus:outline-none focus:border-[#0284c7] shadow-xs">
                                <button type="submit" class="bg-[#e0f2fe] hover:bg-[#0284c7] border border-[#bae6fd] hover:border-[#0284c7] text-[#0284c7] hover:text-white text-[11px] uppercase font-bold px-3.5 py-2 rounded-lg transition-all shrink-0 shadow-xs cursor-pointer">
                                    Update
                                </button>
                            </form>

                            <form action="{{ url('/admin/menus/' . $item->id) }}" method="POST" onsubmit="return confirm('Delete this menu item?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="bg-rose-50 hover:bg-rose-600 border border-rose-200 hover:border-rose-600 text-rose-600 hover:text-white text-[11px] uppercase font-bold px-3.5 py-2 rounded-lg transition-all shrink-0 cursor-pointer shadow-xs">
                                    Delete
                                </button>
                            </form>
                        </div>
                    @empty
                        <div class="text-center py-10 text-[#64748b] text-[13px] bg-[#f8fafc] border border-dashed border-[#cbd5e1] rounded-xl font-medium">
                            No custom menu items yet. Create one on the left.
                        </div>
                    @endforelse
                </div>
            </div>
        </div>

        <!-- Classic Minimal Admin Footer -->
        @include('admin.partials.footer')
    </main>
    </div>

    <script>
        lucide.createIcons();

        const allSubmenus = ['orders', 'product', 'courier', 'purchase', 'contact', 'seo_sub'];
        function toggleSubmenu(menuId) {
            allSubmenus.forEach(id => {
                if (id !== menuId) {
                    const panel = document.getElementById('sub-' + id);
                    const chevron = document.querySelector('[data-chevron="' + id + '"]');
                    if (panel) panel.classList.remove('submenu-open');
                    if (chevron) chevron.classList.remove('chevron-open');
                }
            });
            const sub = document.getElementById('sub-' + menuId);
            const chevron = document.querySelector('[data-chevron="' + menuId + '"]');
            if (sub) sub.classList.toggle('submenu-open');
            if (chevron) chevron.classList.toggle('chevron-open');
        }

        async function globalClearCache() {
            const btn = document.getElementById('globalClearCacheBtn');
            const ogHtml = btn ? btn.innerHTML : '';
            if (btn) {
                btn.disabled = true;
                btn.innerHTML = `<i data-lucide="loader-2" class="w-3.5 h-3.5 animate-spin"></i> Clearing...`;
                lucide.createIcons();
            }
            try {
                const res = await fetch('/api/clear-cache', {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '',
                        'Accept': 'application/json'
                    }
                });
                if (res.ok) {
                    alert('System Cache Cleared Successfully!');
                } else {
                    alert('Failed to clear cache.');
                }
            } catch (e) {
                alert('Network error while clearing cache.');
            } finally {
                if (btn) {
                    btn.disabled = false;
                    btn.innerHTML = ogHtml;
                    lucide.createIcons();
                }
            }
        }

        // Theme Mode Handler
        function setAdminTheme(mode) {
            localStorage.setItem('admin_theme', mode);
            applyTheme(mode);
            fetch('/api/settings', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || ''
                },
                body: JSON.stringify({
                    settings: { admin_theme: mode }
                })
            }).catch(e => console.log('Theme sync note:', e));
        }

        function applyTheme(mode) {
            document.body.classList.remove('theme-night', 'theme-light');
            const btns = ['default', 'light', 'night'];
            btns.forEach(b => {
                const el = document.getElementById('theme-btn-' + b);
                if (el) {
                    el.classList.remove('bg-[#0284c7]', 'text-white', 'shadow-xs');
                    el.classList.add('text-[#64748b]');
                }
            });

            if (mode === 'night') {
                document.body.classList.add('theme-night');
                const el = document.getElementById('theme-btn-night');
                if (el) { el.classList.add('bg-[#0284c7]', 'text-white', 'shadow-xs'); el.classList.remove('text-[#64748b]'); }
            } else if (mode === 'light') {
                document.body.classList.add('theme-light');
                const el = document.getElementById('theme-btn-light');
                if (el) { el.classList.add('bg-[#0284c7]', 'text-white', 'shadow-xs'); el.classList.remove('text-[#64748b]'); }
            } else {
                const el = document.getElementById('theme-btn-default');
                if (el) { el.classList.add('bg-[#0284c7]', 'text-white', 'shadow-xs'); el.classList.remove('text-[#64748b]'); }
            }
        }

        // Load saved theme on boot
        const savedTheme = localStorage.getItem('admin_theme') || '{{ $siteSettings["admin_theme"] ?? "default" }}';
        applyTheme(savedTheme);
    </script>
</body>
</html>
