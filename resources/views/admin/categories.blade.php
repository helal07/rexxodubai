<!DOCTYPE html>
<html lang="en" data-theme="{{ $siteSettings['admin_theme'] ?? 'default' }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $siteSettings['siteName'] ?? 'RaaxO BD' }} — Categories & Subcategories</title>
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
<body class="bg-gradient-to-br from-[#e0f2fe] via-[#f0f9ff] to-[#bae6fd] text-[#0f172a] font-sans flex min-h-screen relative overflow-x-hidden selection:bg-[#0284c7] selection:text-white">

    <!-- 1. LEFT SIDEBAR MENU BAR -->
    <aside class="w-64 lg:w-72 bg-white/90 backdrop-blur-xl border-r border-[#38bdf8]/30 min-h-screen p-6 flex flex-col justify-between shrink-0 relative z-20 shadow-sm">
        <div class="space-y-8">
            <div class="flex items-center gap-3 border-b border-[#e2e8f0] pb-6">
                <div id="sidebarLogoContainer" class="flex items-center justify-center">
                    @if(!empty($siteSettings['logo_url']) || !empty($siteSettings['site_logo']))
                        <img id="sidebarLogoImg" src="{{ $siteSettings['logo_url'] ?? $siteSettings['site_logo'] }}" alt="Logo" class="max-h-10 max-w-[120px] object-contain rounded-lg shadow-sm" />
                    @else
                        <div class="w-10 h-10 rounded-xl bg-[#0284c7] text-white flex items-center justify-center shadow-md">
                            <i data-lucide="shield-check" class="w-6 h-6"></i>
                        </div>
                    @endif
                </div>
                <div>
                    <h1 class="text-[16px] font-serif font-bold text-[#0f172a] uppercase tracking-wide leading-none" id="sidebarBrandName">
                        {{ $siteSettings['siteName'] ?? 'RaaxO BD' }}
                    </h1>
                    <span class="text-[10px] text-[#0284c7] font-bold uppercase tracking-wider block mt-1">
                        EXECUTIVE CONTROL HUB
                    </span>
                </div>
            </div>

            <div class="space-y-1.5">
                <span class="text-[10px] font-bold uppercase tracking-widest text-[#94a3b8] px-3 block mb-2">
                    MAIN MENU NAVIGATION
                </span>

                <!-- 1. DASHBOARD -->
                <a href="{{ url('/admin/dashboard') }}" class="w-full px-4 py-3 text-[13px] font-bold uppercase tracking-wider flex items-center gap-3 rounded-xl transition-all cursor-pointer text-[#475569] hover:bg-[#f1f5f9] hover:text-[#0284c7]">
                    <i data-lucide="layout-dashboard" class="w-4 h-4"></i> Dashboard
                </a>

                <!-- 2. ORDERS -->
                <div>
                    <button type="button" onclick="toggleSubmenu('orders')" class="w-full px-4 py-3 text-[13px] font-bold uppercase tracking-wider flex items-center justify-between rounded-xl transition-all cursor-pointer text-[#475569] hover:bg-[#f1f5f9] hover:text-[#0284c7]">
                        <div class="flex items-center gap-3">
                            <i data-lucide="shopping-bag" class="w-4 h-4"></i> Orders
                        </div>
                        <span data-chevron="orders" class="submenu-chevron"><i data-lucide="chevron-down" class="w-4 h-4"></i></span>
                    </button>
                    <div id="sub-orders" class="submenu-panel ml-4 pl-3 border-l-2 border-[#38bdf8]/40 space-y-1">
                        <a href="{{ url('/admin/orders') }}" class="block w-full text-left px-3 py-1.5 text-[12px] font-bold uppercase text-[#64748b] hover:text-[#0284c7] hover:bg-[#f8fafc] rounded-lg">• Total Orders</a>
                        <a href="{{ url('/admin/orders?status=completed') }}" class="block w-full text-left px-3 py-1.5 text-[12px] font-bold uppercase text-[#64748b] hover:text-emerald-700 hover:bg-[#f8fafc] rounded-lg">• Success Orders</a>
                        <a href="{{ url('/admin/orders?status=cancelled') }}" class="block w-full text-left px-3 py-1.5 text-[12px] font-bold uppercase text-[#64748b] hover:text-rose-700 hover:bg-[#f8fafc] rounded-lg">• Return / Cancelled</a>
                    </div>
                </div>

                <!-- 3. PRODUCT (ACTIVE) -->
                <div>
                    <button type="button" onclick="toggleSubmenu('product')" class="w-full px-4 py-3 text-[13px] font-bold uppercase tracking-wider flex items-center justify-between rounded-xl transition-all cursor-pointer bg-[#0284c7] text-white shadow-md">
                        <div class="flex items-center gap-3">
                            <i data-lucide="package" class="w-4 h-4"></i> Product
                        </div>
                        <span data-chevron="product" class="submenu-chevron chevron-open"><i data-lucide="chevron-down" class="w-4 h-4"></i></span>
                    </button>
                    <div id="sub-product" class="submenu-panel submenu-open ml-4 pl-3 border-l-2 border-[#38bdf8]/40 space-y-1">
                        <a href="{{ url('/admin/products') }}#addProductForm" class="block w-full text-left px-3 py-1.5 text-[12px] font-bold uppercase text-[#64748b] hover:text-[#0284c7] hover:bg-[#f8fafc] rounded-lg">• Add Product</a>
                        <a href="{{ url('/admin/products') }}" class="block w-full text-left px-3 py-1.5 text-[12px] font-bold uppercase text-[#64748b] hover:text-[#0284c7] hover:bg-[#f8fafc] rounded-lg">• List Products</a>
                        <a href="{{ url('/admin/categories') }}" class="block w-full text-left px-3 py-1.5 text-[12px] font-bold uppercase text-[#0284c7] bg-[#f0f9ff] rounded-lg">• Category & Sub Category</a>
                    </div>
                </div>

                <!-- 4. MENUS / NAVIGATION -->
                <a href="{{ url('/admin/menus') }}" class="w-full px-4 py-3 text-[13px] font-bold uppercase tracking-wider flex items-center gap-3 rounded-xl transition-all cursor-pointer text-[#475569] hover:bg-[#f1f5f9] hover:text-[#0284c7]">
                    <i data-lucide="menu" class="w-4 h-4"></i> Navigation Menus
                </a>

                <!-- 5. COURIER -->
                <div>
                    <button type="button" onclick="toggleSubmenu('courier')" class="w-full px-4 py-3 text-[13px] font-bold uppercase tracking-wider flex items-center justify-between rounded-xl transition-all cursor-pointer text-[#475569] hover:bg-[#f1f5f9] hover:text-[#0284c7]">
                        <div class="flex items-center gap-3">
                            <i data-lucide="truck" class="w-4 h-4"></i> Courier
                        </div>
                        <span data-chevron="courier" class="submenu-chevron"><i data-lucide="chevron-down" class="w-4 h-4"></i></span>
                    </button>
                    <div id="sub-courier" class="submenu-panel ml-4 pl-3 border-l-2 border-[#38bdf8]/40 space-y-1">
                        <a href="{{ url('/admin/courier') }}" class="block w-full text-left px-3 py-1.5 text-[12px] font-bold uppercase text-[#64748b] hover:text-[#0284c7] hover:bg-[#f8fafc] rounded-lg">• Courier Hub & API</a>
                        <a href="{{ url('/admin/courier') }}#dispatch-section" class="block w-full text-left px-3 py-1.5 text-[12px] font-bold uppercase text-[#64748b] hover:text-[#0284c7] hover:bg-[#f8fafc] rounded-lg">• Send Courier</a>
                        <a href="{{ url('/admin/courier') }}#history-section" class="block w-full text-left px-3 py-1.5 text-[12px] font-bold uppercase text-[#64748b] hover:text-[#0284c7] hover:bg-[#f8fafc] rounded-lg">• Courier History</a>
                    </div>
                </div>

                <!-- 6. SITE SETTING -->
                <a href="{{ url('/admin/dashboard') }}#site_setting" class="w-full px-4 py-3 text-[13px] font-bold uppercase tracking-wider flex items-center gap-3 rounded-xl transition-all cursor-pointer text-[#475569] hover:bg-[#f1f5f9] hover:text-[#0284c7]">
                    <i data-lucide="sliders" class="w-4 h-4"></i> Site Settings
                </a>
            </div>
        </div>

        <div class="space-y-4 pt-6 border-t border-[#e2e8f0]">
            <!-- Theme Toggle Bar -->
            <div class="bg-[#f8fafc] border border-[#e2e8f0] rounded-xl p-2 flex items-center justify-between">
                <span class="text-[10px] font-bold uppercase tracking-wider text-[#64748b] px-1">Theme</span>
                <div class="flex items-center gap-1">
                    <button type="button" onclick="setAdminTheme('default')" title="Sky Executive (Default)" class="px-2 py-1 rounded text-[10px] font-bold uppercase transition-all bg-[#0284c7] text-white shadow-xs" id="theme-btn-default">Default</button>
                    <button type="button" onclick="setAdminTheme('light')" title="Clean Light" class="px-2 py-1 rounded text-[10px] font-bold uppercase transition-all text-[#64748b] hover:bg-[#e2e8f0]" id="theme-btn-light">Light</button>
                    <button type="button" onclick="setAdminTheme('night')" title="Night / Dark Mode" class="px-2 py-1 rounded text-[10px] font-bold uppercase transition-all text-[#64748b] hover:bg-[#e2e8f0]" id="theme-btn-night">Night</button>
                </div>
            </div>

            <a href="{{ url('/') }}" target="_blank" class="w-full px-4 py-2.5 bg-[#f8fafc] hover:bg-[#f1f5f9] border border-[#e2e8f0] text-[#0284c7] text-[12px] font-bold uppercase tracking-wider rounded-xl flex items-center justify-center gap-2 transition-all shadow-xs">
                <i data-lucide="external-link" class="w-4 h-4"></i> View Storefront
            </a>

            <div class="flex items-center justify-between px-2 pt-2">
                <div class="flex items-center gap-3">
                    <div class="w-9 h-9 rounded-full bg-[#0284c7]/10 text-[#0284c7] flex items-center justify-center font-bold text-sm">
                        AD
                    </div>
                    <div>
                        <span class="text-[12px] font-bold block leading-none">Super Admin</span>
                        <span class="text-[10px] text-[#64748b] block mt-0.5">Active Session</span>
                    </div>
                </div>
                <form action="{{ url('/logout') }}" method="POST">
                    @csrf
                    <button type="submit" title="Logout" class="p-2 hover:bg-rose-50 text-rose-600 rounded-lg transition-colors cursor-pointer">
                        <i data-lucide="log-out" class="w-4 h-4"></i>
                    </button>
                </form>
            </div>
        </div>
    </aside>

    <!-- 2. MAIN CONTENT WRAPPER -->
    <main class="flex-1 min-h-screen p-6 lg:p-10 max-w-[1600px] w-full mx-auto space-y-8 relative z-10">

        <!-- Top Navigation Header -->
        <header class="bg-white/90 backdrop-blur-xl border border-[#38bdf8]/30 rounded-2xl p-6 flex flex-col md:flex-row justify-between items-start md:items-center gap-4 shadow-sm animate-fade-in">
            <div class="flex items-center gap-4">
                <div class="w-12 h-12 rounded-2xl bg-gradient-to-br from-[#0284c7] to-[#0369a1] text-white flex items-center justify-center shadow-lg shadow-[#0284c7]/20">
                    <i data-lucide="layers" class="w-6 h-6"></i>
                </div>
                <div>
                    <div class="flex items-center gap-2">
                        <span class="text-[11px] font-bold uppercase tracking-widest text-[#0284c7]">TAXONOMY ARCHITECTURE</span>
                        <span class="px-2 py-0.5 rounded-full text-[10px] font-bold uppercase bg-[#e0f2fe] text-[#0284c7] border border-[#bae6fd]">
                            {{ count($categories) }} CATEGORIES
                        </span>
                    </div>
                    <h2 class="text-[22px] font-serif font-bold text-[#0f172a] uppercase tracking-tight">
                        Categories & Subcategories
                    </h2>
                </div>
            </div>

            <div class="flex items-center flex-wrap gap-2.5">
                <a href="{{ url('/admin/products') }}" class="px-4 py-2.5 bg-[#0284c7] hover:bg-[#0369a1] text-white text-[12px] font-bold uppercase tracking-wider rounded-xl transition-all shadow-md shadow-[#0284c7]/20 flex items-center gap-2">
                    <i data-lucide="package" class="w-4 h-4"></i> Product Inventory
                </a>
                <a href="{{ url('/admin/menus') }}" class="px-4 py-2.5 bg-white hover:bg-[#f8fafc] border border-[#cbd5e1] text-[#475569] text-[12px] font-bold uppercase tracking-wider rounded-xl transition-all shadow-xs flex items-center gap-2">
                    <i data-lucide="menu" class="w-4 h-4"></i> Menu Builder
                </a>
                <a href="{{ url('/perfumes') }}" target="_blank" class="px-4 py-2.5 bg-emerald-600 hover:bg-emerald-700 text-white text-[12px] font-bold uppercase tracking-wider rounded-xl transition-all shadow-xs flex items-center gap-2">
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
            <!-- Add New Category Form -->
            <div class="lg:col-span-5 bg-white/90 backdrop-blur-xl border border-[#38bdf8]/30 p-7 rounded-2xl space-y-6 shadow-sm">
                <div class="flex items-center gap-3 border-b border-[#e2e8f0] pb-4">
                    <div class="p-2.5 bg-[#e0f2fe] text-[#0284c7] rounded-xl">
                        <i data-lucide="folder-plus" class="w-5 h-5"></i>
                    </div>
                    <div>
                        <h3 class="text-[16px] font-serif font-bold text-[#0f172a] uppercase tracking-wide">
                            Create Category / Subcategory
                        </h3>
                        <p class="text-[11px] text-[#64748b]">Add a root category or attach a nested subcategory</p>
                    </div>
                </div>

                <form action="{{ url('/admin/categories') }}" method="POST" class="space-y-4">
                    @csrf
                    <div>
                        <label class="text-[11px] uppercase font-bold text-[#475569] tracking-wider block mb-1.5">
                            PARENT CATEGORY (LEAVE EMPTY FOR MAIN)
                        </label>
                        <select name="parent_id" class="w-full border border-[#cbd5e1] px-4 py-2.5 rounded-xl text-[13px] font-medium text-[#0f172a] bg-white focus:outline-none focus:border-[#0284c7] transition-all shadow-xs">
                            <option value="">None (Top-Level Main Category)</option>
                            @foreach ($parentCategories as $pCat)
                                <option value="{{ $pCat->id }}">📁 {{ $pCat->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="text-[11px] uppercase font-bold text-[#475569] tracking-wider block mb-1.5">
                            CATEGORY NAME *
                        </label>
                        <input type="text" name="name" required placeholder="e.g. Eau de Parfum, Rare Oud, Luxury Gifts" class="w-full border border-[#cbd5e1] px-4 py-2.5 rounded-xl text-[13px] font-semibold text-[#0f172a] bg-white focus:outline-none focus:border-[#0284c7] transition-all shadow-xs">
                    </div>

                    <div>
                        <label class="text-[11px] uppercase font-bold text-[#475569] tracking-wider block mb-1.5">
                            URL SLUG (OPTIONAL - AUTO GENERATED)
                        </label>
                        <input type="text" name="slug" placeholder="e.g. eau-de-parfum" class="w-full border border-[#cbd5e1] px-4 py-2.5 rounded-xl text-[13px] font-mono text-[#0f172a] bg-white focus:outline-none focus:border-[#0284c7] transition-all shadow-xs">
                    </div>

                    <div>
                        <label class="text-[11px] uppercase font-bold text-[#475569] tracking-wider block mb-1.5">
                            DESCRIPTION (OPTIONAL)
                        </label>
                        <textarea name="description" rows="2" placeholder="Brief fragrance family or edition note..." class="w-full border border-[#cbd5e1] px-4 py-2 rounded-xl text-[12px] text-[#0f172a] bg-white focus:outline-none focus:border-[#0284c7] transition-all shadow-xs"></textarea>
                    </div>

                    <div>
                        <label class="text-[11px] uppercase font-bold text-[#475569] tracking-wider block mb-1.5">
                            SORT ORDER
                        </label>
                        <input type="number" name="sort_order" value="1" class="w-full border border-[#cbd5e1] px-4 py-2.5 rounded-xl text-[13px] font-medium text-[#0f172a] bg-white focus:outline-none focus:border-[#0284c7] transition-all shadow-xs">
                    </div>

                    <button type="submit" class="w-full bg-[#0284c7] hover:bg-[#0369a1] text-white py-3.5 rounded-xl text-[12px] font-bold uppercase tracking-wider cursor-pointer transition-all shadow-md shadow-[#0284c7]/20 flex items-center justify-center gap-2">
                        <i data-lucide="save" class="w-4 h-4"></i> Save Category
                    </button>
                </form>
            </div>

            <!-- Categories Tree & Hierarchy List -->
            <div class="lg:col-span-7 bg-white/90 backdrop-blur-xl border border-[#38bdf8]/30 p-7 rounded-2xl space-y-6 shadow-sm">
                <div class="flex items-center justify-between border-b border-[#e2e8f0] pb-4">
                    <div class="flex items-center gap-3">
                        <div class="p-2.5 bg-[#e0f2fe] text-[#0284c7] rounded-xl">
                            <i data-lucide="folder-tree" class="w-5 h-5"></i>
                        </div>
                        <div>
                            <h3 class="text-[16px] font-serif font-bold text-[#0f172a] uppercase tracking-wide">
                                Category Hierarchy
                            </h3>
                            <p class="text-[11px] text-[#64748b]">Live organized category taxonomy tree</p>
                        </div>
                    </div>
                    <span class="text-[11px] font-mono text-[#0284c7] bg-[#f0f9ff] px-3 py-1 rounded-full border border-[#bae6fd]">
                        {{ count($categories) }} CATEGORIES
                    </span>
                </div>

                <div class="space-y-4">
                    @forelse ($parentCategories as $parent)
                        <div class="bg-[#f8fafc] border border-[#e2e8f0] rounded-xl p-4.5 space-y-3 hover:border-[#cbd5e1] transition-all shadow-2xs">
                            <!-- Main Parent Category Header -->
                            <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-3 pb-3 border-b border-[#e2e8f0]">
                                <div class="flex items-center gap-2.5">
                                    <div class="p-2 bg-[#e0f2fe] text-[#0284c7] rounded-lg">
                                        <i data-lucide="folder" class="w-4 h-4"></i>
                                    </div>
                                    <div>
                                        <span class="font-bold text-[14px] text-[#0f172a]">{{ $parent->name }}</span>
                                        <span class="text-[11px] font-mono text-[#64748b] ml-2">/perfumes?category={{ $parent->slug }}</span>
                                    </div>
                                </div>

                                <div class="flex items-center gap-2">
                                    <a href="{{ url('/perfumes?category=' . $parent->slug) }}" target="_blank" class="text-[#475569] hover:text-[#0284c7] p-1.5 rounded-lg bg-white border border-[#cbd5e1] text-[11px] flex items-center gap-1 shadow-2xs" title="View live catalog">
                                        <i data-lucide="external-link" class="w-3.5 h-3.5"></i>
                                    </a>
                                    <form action="{{ url('/admin/categories/' . $parent->id) }}" method="POST" onsubmit="return confirm('Delete parent category and all subcategories?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="bg-rose-50 hover:bg-rose-600 border border-rose-200 hover:border-rose-600 text-rose-600 hover:text-white text-[11px] uppercase font-bold p-1.5 rounded-lg transition-all cursor-pointer shadow-2xs" title="Delete category">
                                            <i data-lucide="trash-2" class="w-3.5 h-3.5"></i>
                                        </button>
                                    </form>
                                </div>
                            </div>

                            <!-- Subcategories Child Items -->
                            @php
                                $subCategories = $categories->where('parent_id', $parent->id);
                            @endphp
                            @if ($subCategories->isNotEmpty())
                                <div class="pl-4 sm:pl-6 space-y-2 border-l-2 border-[#38bdf8]/40 ml-2">
                                    @foreach ($subCategories as $sub)
                                        <div class="flex items-center justify-between bg-white p-3 rounded-lg border border-[#e2e8f0] hover:border-[#cbd5e1] transition-all shadow-2xs">
                                            <div class="flex items-center gap-2">
                                                <i data-lucide="corner-down-right" class="w-3.5 h-3.5 text-[#94a3b8]"></i>
                                                <span class="text-[13px] font-semibold text-[#1e293b]">{{ $sub->name }}</span>
                                                <span class="text-[10px] font-mono text-[#64748b] bg-[#f1f5f9] px-2 py-0.5 rounded">
                                                    {{ $sub->slug }}
                                                </span>
                                            </div>
                                            <div class="flex items-center gap-2">
                                                <form action="{{ url('/admin/categories/' . $sub->id) }}" method="POST" onsubmit="return confirm('Delete subcategory {{ addslashes($sub->name) }}?');">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="text-rose-500 hover:text-rose-700 p-1 rounded transition-colors cursor-pointer">
                                                        <i data-lucide="trash" class="w-3.5 h-3.5"></i>
                                                    </button>
                                                </form>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            @else
                                <div class="pl-4 text-[11px] text-[#94a3b8] italic">
                                    No subcategories configured for this group.
                                </div>
                            @endif
                        </div>
                    @empty
                        <div class="p-8 text-center text-[#64748b] font-medium bg-[#f8fafc] rounded-xl border border-[#e2e8f0]">
                            No categories registered in database yet.
                        </div>
                    @endforelse
                </div>
            </div>
        </div>

        <!-- Classic Minimal Admin Footer -->
        @include('admin.partials.footer')
    </main>

    <script>
        lucide.createIcons();

        function toggleSubmenu(id) {
            const panel = document.getElementById('sub-' + id);
            const chevron = document.querySelector('[data-chevron="' + id + '"]');
            if (panel) {
                panel.classList.toggle('submenu-open');
            }
            if (chevron) {
                chevron.classList.toggle('chevron-open');
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
