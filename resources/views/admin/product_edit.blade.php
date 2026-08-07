<!DOCTYPE html>
<html lang="en" data-theme="{{ $siteSettings['admin_theme'] ?? 'default' }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit {{ $product->name }} — {{ $siteSettings['siteName'] ?? 'RaaxO BD' }} Admin</title>
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
                        <a href="{{ url('/admin/products') }}" class="block w-full text-left px-3 py-1.5 text-[12px] font-bold uppercase text-[#0284c7] bg-[#f0f9ff] rounded-lg">• List Products</a>
                        <a href="{{ url('/admin/categories') }}" class="block w-full text-left px-3 py-1.5 text-[12px] font-bold uppercase text-[#64748b] hover:text-[#0284c7] hover:bg-[#f8fafc] rounded-lg">• Category & Sub Category</a>
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
    <main class="flex-1 min-h-screen p-6 lg:p-10 max-w-[1400px] w-full mx-auto space-y-8 relative z-10">

        <!-- Top Navigation Bar -->
        <header class="bg-white/90 backdrop-blur-xl border border-[#38bdf8]/30 rounded-2xl p-6 flex flex-col md:flex-row justify-between items-start md:items-center gap-4 shadow-sm animate-fade-in">
            <div class="flex items-center gap-4">
                <a href="{{ url('/admin/products') }}" class="p-2.5 bg-[#f8fafc] hover:bg-[#f1f5f9] border border-[#cbd5e1] text-[#475569] hover:text-[#0284c7] rounded-xl transition-all shadow-xs">
                    <i data-lucide="arrow-left" class="w-5 h-5"></i>
                </a>
                <div>
                    <div class="flex items-center gap-2">
                        <span class="text-[11px] font-bold uppercase tracking-widest text-[#0284c7]">
                            PRODUCT MANAGEMENT
                        </span>
                        <span class="text-[11px] font-mono text-[#64748b] bg-[#f1f5f9] px-2.5 py-0.5 rounded-full border border-[#e2e8f0]">
                            ID: #{{ $product->id }}
                        </span>
                    </div>
                    <h1 class="text-[22px] font-serif font-bold text-[#0f172a] uppercase tracking-tight truncate max-w-xl">
                        Edit: {{ $product->name }}
                    </h1>
                </div>
            </div>

            <div class="flex items-center flex-wrap gap-3">
                <a href="{{ url('/product/' . $product->slug) }}" target="_blank" class="inline-flex items-center gap-2 bg-[#f8fafc] hover:bg-[#f1f5f9] border border-[#cbd5e1] text-[#475569] hover:text-[#0284c7] px-4 py-2.5 rounded-xl text-[12px] font-bold uppercase tracking-wider transition-all shadow-xs">
                    <i data-lucide="external-link" class="w-4 h-4"></i> View Live
                </a>
                <a href="{{ url('/admin/products') }}" class="inline-flex items-center gap-2 bg-[#0284c7] hover:bg-[#0369a1] text-white px-4 py-2.5 rounded-xl text-[12px] font-bold uppercase tracking-wider transition-all shadow-md shadow-[#0284c7]/20">
                    <i data-lucide="list" class="w-4 h-4"></i> Product List
                </a>
            </div>
        </header>

        <!-- Success & Error Banners -->
        @if (session('success'))
            <div class="p-4 bg-emerald-50 border border-emerald-300 text-emerald-800 text-[13px] rounded-xl flex items-center gap-3 font-semibold shadow-xs animate-fade-in">
                <div class="p-1.5 bg-emerald-500 text-white rounded-lg">
                    <i data-lucide="check" class="w-4 h-4"></i>
                </div>
                <span>{{ session('success') }}</span>
            </div>
        @endif

        @if (isset($errors) && $errors->any())
            <div class="bg-rose-50 border border-rose-300 p-4 rounded-xl text-rose-800 text-[13px] space-y-1 shadow-xs animate-fade-in">
                <div class="font-bold flex items-center gap-2">
                    <i data-lucide="alert-circle" class="w-4 h-4 text-rose-600"></i> Please correct the following errors:
                </div>
                <ul class="list-disc list-inside text-[12px] space-y-0.5 text-rose-700 pl-2">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <!-- Main Product Edit Form -->
        <form action="{{ url('/admin/products/' . $product->id) }}" method="POST" enctype="multipart/form-data" class="space-y-8 animate-fade-in">
            @csrf
            @method('PUT')

            <!-- SECTION 1: Product Essential Identity -->
            <div class="bg-white/90 backdrop-blur-xl border border-[#38bdf8]/30 p-7 sm:p-8 rounded-2xl space-y-6 shadow-sm">
                <div class="flex items-center gap-3 border-b border-[#e2e8f0] pb-4">
                    <div class="p-2.5 bg-[#e0f2fe] text-[#0284c7] rounded-xl">
                        <i data-lucide="package" class="w-5 h-5"></i>
                    </div>
                    <div>
                        <h2 class="text-[16px] font-serif font-bold text-[#0f172a] uppercase tracking-wide">
                            Essential Product Information
                        </h2>
                        <p class="text-[11px] text-[#64748b]">Configure title, brand collection category, gender, and public URL slug.</p>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="text-[11px] uppercase font-bold text-[#475569] tracking-wider block mb-2">
                            Product Title / Name *
                        </label>
                        <input 
                            type="text" 
                            name="name" 
                            value="{{ old('name', $product->name) }}" 
                            required 
                            placeholder="e.g. L'Ombre d'Ambre Extrait"
                            class="w-full border border-[#cbd5e1] px-4 py-3 rounded-xl text-[14px] font-semibold text-[#0f172a] bg-white focus:outline-none focus:border-[#0284c7] focus:ring-1 focus:ring-[#0284c7] transition-all shadow-xs"
                        >
                    </div>

                    <div>
                        <label class="text-[11px] uppercase font-bold text-[#475569] tracking-wider block mb-2">
                            URL Slug (Identifier)
                        </label>
                        <input 
                            type="text" 
                            name="slug" 
                            value="{{ old('slug', $product->slug) }}" 
                            placeholder="e.g. lombre-dambre-extrait"
                            class="w-full border border-[#cbd5e1] px-4 py-3 rounded-xl text-[14px] font-mono text-[#0f172a] bg-white focus:outline-none focus:border-[#0284c7] focus:ring-1 focus:ring-[#0284c7] transition-all shadow-xs"
                        >
                        <span class="text-[11px] text-[#64748b] mt-1 block">Live link: /product/{{ $product->slug }}</span>
                    </div>

                    <div>
                        <label class="text-[11px] uppercase font-bold text-[#475569] tracking-wider block mb-2">
                            Category / Collection
                        </label>
                        <select 
                            name="category_id" 
                            class="w-full border border-[#cbd5e1] px-4 py-3 rounded-xl text-[14px] font-medium text-[#0f172a] bg-white focus:outline-none focus:border-[#0284c7] transition-all shadow-xs"
                        >
                            <option value="">No Specific Category</option>
                            @php
                                $rootCats = $categories->whereNull('parent_id');
                            @endphp
                            @if($rootCats->isNotEmpty())
                                @foreach ($rootCats as $cat)
                                    <optgroup label="{{ $cat->name }}">
                                        <option value="{{ $cat->id }}" {{ old('category_id', $product->category_id) == $cat->id ? 'selected' : '' }}>
                                            {{ $cat->name }} (Main Category)
                                        </option>
                                        @foreach ($categories->where('parent_id', $cat->id) as $sub)
                                            <option value="{{ $sub->id }}" {{ old('category_id', $product->category_id) == $sub->id ? 'selected' : '' }}>
                                                &nbsp;&nbsp;↳ {{ $sub->name }} (Subcategory)
                                            </option>
                                        @endforeach
                                    </optgroup>
                                @endforeach
                            @else
                                @foreach ($categories as $cat)
                                    <option value="{{ $cat->id }}" {{ old('category_id', $product->category_id) == $cat->id ? 'selected' : '' }}>
                                        {{ $cat->name }}
                                    </option>
                                @endforeach
                            @endif
                        </select>
                    </div>

                    <div>
                        <label class="text-[11px] uppercase font-bold text-[#475569] tracking-wider block mb-2">
                            Gender Target *
                        </label>
                        <select 
                            name="gender" 
                            required
                            class="w-full border border-[#cbd5e1] px-4 py-3 rounded-xl text-[14px] font-medium text-[#0f172a] bg-white focus:outline-none focus:border-[#0284c7] transition-all shadow-xs"
                        >
                            <option value="unisex" {{ old('gender', $product->gender) === 'unisex' ? 'selected' : '' }}>Unisex / Universal</option>
                            <option value="women" {{ old('gender', $product->gender) === 'women' ? 'selected' : '' }}>Women</option>
                            <option value="men" {{ old('gender', $product->gender) === 'men' ? 'selected' : '' }}>Men</option>
                        </select>
                    </div>
                </div>
            </div>

            <!-- SECTION 2: Pricing, Stock & Sizes -->
            <div class="bg-white/90 backdrop-blur-xl border border-[#38bdf8]/30 p-7 sm:p-8 rounded-2xl space-y-6 shadow-sm">
                <div class="flex items-center gap-3 border-b border-[#e2e8f0] pb-4">
                    <div class="p-2.5 bg-emerald-50 text-emerald-600 rounded-xl">
                        <i data-lucide="tag" class="w-5 h-5"></i>
                    </div>
                    <div>
                        <h2 class="text-[16px] font-serif font-bold text-[#0f172a] uppercase tracking-wide">
                            Pricing, Inventory & Bottle Sizes
                        </h2>
                        <p class="text-[11px] text-[#64748b]">Manage base price, stock inventory level, concentration, and volume formats.</p>
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-6">
                    <div>
                        <label class="text-[11px] uppercase font-bold text-[#475569] tracking-wider block mb-2">
                            Price (৳ / USD) *
                        </label>
                        <input 
                            type="number" 
                            step="0.01" 
                            name="price" 
                            value="{{ old('price', $product->price) }}" 
                            required
                            placeholder="3200.00"
                            class="w-full border border-[#cbd5e1] px-4 py-3 rounded-xl text-[14px] font-bold text-[#0284c7] bg-white focus:outline-none focus:border-[#0284c7] focus:ring-1 focus:ring-[#0284c7] transition-all shadow-xs"
                        >
                    </div>

                    <div>
                        <label class="text-[11px] uppercase font-bold text-[#475569] tracking-wider block mb-2">
                            Stock Quantity *
                        </label>
                        <input 
                            type="number" 
                            name="stock" 
                            value="{{ old('stock', $product->stock ?? 50) }}" 
                            required
                            placeholder="50"
                            class="w-full border border-[#cbd5e1] px-4 py-3 rounded-xl text-[14px] font-bold text-[#0f172a] bg-white focus:outline-none focus:border-[#0284c7] focus:ring-1 focus:ring-[#0284c7] transition-all shadow-xs"
                        >
                    </div>

                    <div>
                        <label class="text-[11px] uppercase font-bold text-[#475569] tracking-wider block mb-2">
                            Concentration
                        </label>
                        <input 
                            type="text" 
                            name="concentration" 
                            value="{{ old('concentration', $product->concentration ?? 'Eau de Parfum') }}" 
                            placeholder="e.g. Extrait de Parfum"
                            class="w-full border border-[#cbd5e1] px-4 py-3 rounded-xl text-[14px] font-medium text-[#0f172a] bg-white focus:outline-none focus:border-[#0284c7] transition-all shadow-xs"
                        >
                    </div>

                    <div>
                        <label class="text-[11px] uppercase font-bold text-[#475569] tracking-wider block mb-2">
                            Sizes (Comma Separated)
                        </label>
                        <input 
                            type="text" 
                            name="sizes" 
                            value="{{ old('sizes', is_array($product->sizes) ? implode(', ', $product->sizes) : $product->sizes) }}" 
                            placeholder="50ml, 100ml"
                            class="w-full border border-[#cbd5e1] px-4 py-3 rounded-xl text-[14px] font-medium text-[#0f172a] bg-white focus:outline-none focus:border-[#0284c7] transition-all shadow-xs"
                        >
                    </div>
                </div>
            </div>

            <!-- SECTION 3: Olfactory Architecture (Fragrance Notes) -->
            <div class="bg-white/90 backdrop-blur-xl border border-[#38bdf8]/30 p-7 sm:p-8 rounded-2xl space-y-6 shadow-sm">
                <div class="flex items-center gap-3 border-b border-[#e2e8f0] pb-4">
                    <div class="p-2.5 bg-purple-50 text-purple-600 rounded-xl">
                        <i data-lucide="sparkles" class="w-5 h-5"></i>
                    </div>
                    <div>
                        <h2 class="text-[16px] font-serif font-bold text-[#0f172a] uppercase tracking-wide">
                            Olfactory Pyramid & Scent Architecture
                        </h2>
                        <p class="text-[11px] text-[#64748b]">Detail the scent family classification and top, heart, and base notes.</p>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="text-[11px] uppercase font-bold text-[#475569] tracking-wider block mb-2">
                            Scent Family / Character
                        </label>
                        <input 
                            type="text" 
                            name="scent_family" 
                            value="{{ old('scent_family', $product->scent_family) }}" 
                            placeholder="e.g. Amber Woody / Oriental Rose"
                            class="w-full border border-[#cbd5e1] px-4 py-3 rounded-xl text-[14px] font-medium text-[#0f172a] bg-white focus:outline-none focus:border-[#0284c7] transition-all shadow-xs"
                        >
                    </div>

                    <div>
                        <label class="text-[11px] uppercase font-bold text-[#475569] tracking-wider block mb-2">
                            Top Notes (Initial Impact)
                        </label>
                        <input 
                            type="text" 
                            name="notes_top" 
                            value="{{ old('notes_top', $product->notes_top) }}" 
                            placeholder="e.g. Calabrian Bergamot, Pink Pepper"
                            class="w-full border border-[#cbd5e1] px-4 py-3 rounded-xl text-[14px] font-medium text-[#0f172a] bg-white focus:outline-none focus:border-[#0284c7] transition-all shadow-xs"
                        >
                    </div>

                    <div>
                        <label class="text-[11px] uppercase font-bold text-[#475569] tracking-wider block mb-2">
                            Heart Notes (The Core Body)
                        </label>
                        <input 
                            type="text" 
                            name="notes_heart" 
                            value="{{ old('notes_heart', $product->notes_heart) }}" 
                            placeholder="e.g. Damascena Rose, Rare Iris, Saffron"
                            class="w-full border border-[#cbd5e1] px-4 py-3 rounded-xl text-[14px] font-medium text-[#0f172a] bg-white focus:outline-none focus:border-[#0284c7] transition-all shadow-xs"
                        >
                    </div>

                    <div>
                        <label class="text-[11px] uppercase font-bold text-[#475569] tracking-wider block mb-2">
                            Base Notes (Lingering Trail)
                        </label>
                        <input 
                            type="text" 
                            name="notes_base" 
                            value="{{ old('notes_base', $product->notes_base) }}" 
                            placeholder="e.g. Precious Amber, Cambodian Oud, Vanilla Bean"
                            class="w-full border border-[#cbd5e1] px-4 py-3 rounded-xl text-[14px] font-medium text-[#0f172a] bg-white focus:outline-none focus:border-[#0284c7] transition-all shadow-xs"
                        >
                    </div>
                </div>
            </div>

            <!-- SECTION 4: Visuals & Bottle Photography -->
            <div class="bg-white/90 backdrop-blur-xl border border-[#38bdf8]/30 p-7 sm:p-8 rounded-2xl space-y-6 shadow-sm">
                <div class="flex items-center gap-3 border-b border-[#e2e8f0] pb-4">
                    <div class="p-2.5 bg-[#e0f2fe] text-[#0284c7] rounded-xl">
                        <i data-lucide="image" class="w-5 h-5"></i>
                    </div>
                    <div>
                        <h2 class="text-[16px] font-serif font-bold text-[#0f172a] uppercase tracking-wide">
                            Bottle Photography & Media
                        </h2>
                        <p class="text-[11px] text-[#64748b]">Upload high-resolution bottle imagery or specify direct image URLs.</p>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                    <!-- Primary Bottle Image -->
                    <div class="bg-[#f8fafc] p-6 rounded-2xl border border-[#e2e8f0] space-y-4">
                        <div class="flex items-center justify-between">
                            <label class="text-[11px] uppercase font-bold text-[#475569] tracking-wider">
                                Primary Bottle Image (Main)
                            </label>
                            @if ($product->primary_image_url)
                                <span class="text-[10px] font-bold text-emerald-700 bg-emerald-50 px-2 py-0.5 rounded border border-emerald-200">Active Image</span>
                            @endif
                        </div>

                        @if ($product->primary_image_url)
                            <div class="w-full h-48 bg-white rounded-xl overflow-hidden border border-[#e2e8f0] flex items-center justify-center p-3 shadow-2xs">
                                <img src="{{ $product->primary_image_url }}" alt="Primary Bottle" class="h-full w-auto object-contain rounded-lg">
                            </div>
                        @endif

                        <div>
                            <span class="text-[11px] text-[#475569] block mb-1.5 font-bold">Upload New Primary Image:</span>
                            <input 
                                type="file" 
                                name="primary_image_file" 
                                accept="image/*"
                                class="w-full border border-[#cbd5e1] text-[12px] text-[#475569] rounded-xl file:mr-3 file:py-2 file:px-3 file:rounded-l-xl file:border-0 file:text-[11px] file:font-bold file:bg-[#0284c7] file:text-white cursor-pointer bg-white"
                            >
                        </div>

                        <div>
                            <span class="text-[11px] text-[#475569] block mb-1.5 font-bold">Or Image URL:</span>
                            <input 
                                type="text" 
                                name="primary_image_url" 
                                value="{{ old('primary_image_url', $product->primary_image_url) }}" 
                                placeholder="https://..."
                                class="w-full border border-[#cbd5e1] px-3.5 py-2.5 rounded-xl text-[12px] font-mono focus:border-[#0284c7] outline-none bg-white text-[#0f172a] shadow-xs"
                            >
                        </div>
                    </div>

                    <!-- Secondary / Hover Image -->
                    <div class="bg-[#f8fafc] p-6 rounded-2xl border border-[#e2e8f0] space-y-4">
                        <div class="flex items-center justify-between">
                            <label class="text-[11px] uppercase font-bold text-[#475569] tracking-wider">
                                Secondary Image (Hover Cross-fade)
                            </label>
                            @if ($product->secondary_image_url)
                                <span class="text-[10px] font-bold text-emerald-700 bg-emerald-50 px-2 py-0.5 rounded border border-emerald-200">Active Image</span>
                            @endif
                        </div>

                        @if ($product->secondary_image_url)
                            <div class="w-full h-48 bg-white rounded-xl overflow-hidden border border-[#e2e8f0] flex items-center justify-center p-3 shadow-2xs">
                                <img src="{{ $product->secondary_image_url }}" alt="Secondary Bottle" class="h-full w-auto object-contain rounded-lg">
                            </div>
                        @endif

                        <div>
                            <span class="text-[11px] text-[#475569] block mb-1.5 font-bold">Upload New Secondary Image:</span>
                            <input 
                                type="file" 
                                name="secondary_image_file" 
                                accept="image/*"
                                class="w-full border border-[#cbd5e1] text-[12px] text-[#475569] rounded-xl file:mr-3 file:py-2 file:px-3 file:rounded-l-xl file:border-0 file:text-[11px] file:font-bold file:bg-[#0284c7] file:text-white cursor-pointer bg-white"
                            >
                        </div>

                        <div>
                            <span class="text-[11px] text-[#475569] block mb-1.5 font-bold">Or Image URL:</span>
                            <input 
                                type="text" 
                                name="secondary_image_url" 
                                value="{{ old('secondary_image_url', $product->secondary_image_url) }}" 
                                placeholder="https://..."
                                class="w-full border border-[#cbd5e1] px-3.5 py-2.5 rounded-xl text-[12px] font-mono focus:border-[#0284c7] outline-none bg-white text-[#0f172a] shadow-xs"
                            >
                        </div>
                    </div>
                </div>
            </div>

            <!-- SECTION 5: Editorial Descriptions & Showcase Badges -->
            <div class="bg-white/90 backdrop-blur-xl border border-[#38bdf8]/30 p-7 sm:p-8 rounded-2xl space-y-6 shadow-sm">
                <div class="flex items-center gap-3 border-b border-[#e2e8f0] pb-4">
                    <div class="p-2.5 bg-amber-50 text-amber-600 rounded-xl">
                        <i data-lucide="file-text" class="w-5 h-5"></i>
                    </div>
                    <div>
                        <h2 class="text-[16px] font-serif font-bold text-[#0f172a] uppercase tracking-wide">
                            Story, Descriptions & Curated Badges
                        </h2>
                        <p class="text-[11px] text-[#64748b]">Refine the luxury story text and homepage feature badges.</p>
                    </div>
                </div>

                <div class="space-y-6">
                    <div>
                        <label class="text-[11px] uppercase font-bold text-[#475569] tracking-wider block mb-2">
                            Short Editorial Excerpt / Tagline
                        </label>
                        <input 
                            type="text" 
                            name="short_description" 
                            value="{{ old('short_description', $product->short_description) }}" 
                            placeholder="e.g. A sensual tribute to amber and rare woods, bottled in sculpted crystal."
                            class="w-full border border-[#cbd5e1] px-4 py-3 rounded-xl text-[14px] font-medium text-[#0f172a] bg-white focus:outline-none focus:border-[#0284c7] transition-all shadow-xs"
                        >
                    </div>

                    <div>
                        <label class="text-[11px] uppercase font-bold text-[#475569] tracking-wider block mb-2">
                            Full Story & Perfume Description
                        </label>
                        <textarea 
                            name="description" 
                            rows="5" 
                            placeholder="Crafted in Grasse with the purest essential absolutes..."
                            class="w-full border border-[#cbd5e1] px-4 py-3 rounded-xl text-[14px] font-medium text-[#0f172a] bg-white focus:outline-none focus:border-[#0284c7] transition-all leading-relaxed shadow-xs"
                        >{{ old('description', $product->description) }}</textarea>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 pt-2">
                        <label class="flex items-center gap-3 p-4 bg-[#f8fafc] border border-[#e2e8f0] rounded-xl cursor-pointer hover:border-[#0284c7]/50 transition-colors">
                            <input 
                                type="checkbox" 
                                name="is_featured" 
                                value="1" 
                                {{ old('is_featured', $product->is_featured) ? 'checked' : '' }}
                                class="w-5 h-5 text-[#0284c7] rounded border-[#cbd5e1] focus:ring-[#0284c7]"
                            >
                            <div>
                                <span class="text-[13px] font-bold text-[#0f172a] block">Feature on Homepage</span>
                                <span class="text-[11px] text-[#64748b]">Display this perfume in curated homepage hero sections</span>
                            </div>
                        </label>

                        <label class="flex items-center gap-3 p-4 bg-[#f8fafc] border border-[#e2e8f0] rounded-xl cursor-pointer hover:border-[#0284c7]/50 transition-colors">
                            <input 
                                type="checkbox" 
                                name="is_new_arrival" 
                                value="1" 
                                {{ old('is_new_arrival', $product->is_new_arrival) ? 'checked' : '' }}
                                class="w-5 h-5 text-[#0284c7] rounded border-[#cbd5e1] focus:ring-[#0284c7]"
                            >
                            <div>
                                <span class="text-[13px] font-bold text-[#0f172a] block">New Arrival Badge</span>
                                <span class="text-[11px] text-[#64748b]">Highlight with special NEW release badge on storefront</span>
                            </div>
                        </label>
                    </div>
                </div>
            </div>

            <!-- SECTION 5: Per-Product SEO & Social Search Optimization -->
            <div class="bg-white/90 backdrop-blur-xl border border-[#38bdf8]/30 p-7 sm:p-8 rounded-2xl space-y-6 shadow-sm">
                <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 border-b border-[#e2e8f0] pb-4">
                    <div class="flex items-center gap-3">
                        <div class="p-2.5 bg-emerald-50 text-emerald-600 rounded-xl">
                            <i data-lucide="search" class="w-5 h-5"></i>
                        </div>
                        <div>
                            <h2 class="text-[16px] font-serif font-bold text-[#0f172a] uppercase tracking-wide flex items-center gap-2">
                                Per-Product SEO & Social Meta
                                <span class="text-[10px] bg-emerald-100 text-emerald-800 font-bold px-2 py-0.5 rounded-full uppercase">Google & Social Optimized</span>
                            </h2>
                            <p class="text-[11px] text-[#64748b]">Customize how this specific fragrance appears on Google Search, Facebook, Instagram, and WhatsApp.</p>
                        </div>
                    </div>

                    <button 
                        type="button" 
                        onclick="autoGenerateProductSeo()" 
                        class="inline-flex items-center gap-1.5 px-3.5 py-2 bg-[#f0f9ff] hover:bg-[#e0f2fe] text-[#0284c7] border border-[#bae6fd] rounded-xl text-[11px] font-bold uppercase tracking-wider transition-all cursor-pointer shadow-2xs self-start sm:self-auto"
                        title="Auto-fill Meta Title and Description based on current product details"
                    >
                        <i data-lucide="sparkles" class="w-3.5 h-3.5"></i>
                        Auto-Generate from Details
                    </button>
                </div>

                <!-- Google Search Snippet Live Preview -->
                <div class="bg-[#f8fafc] border border-[#cbd5e1] p-5 rounded-2xl space-y-2.5">
                    <div class="flex items-center justify-between text-[11px] font-bold uppercase tracking-wider text-[#64748b]">
                        <span class="flex items-center gap-1.5">
                            <i data-lucide="globe" class="w-3.5 h-3.5 text-[#0284c7]"></i>
                            Google Search Result Preview
                        </span>
                        <span class="text-[10px] font-mono text-[#94a3b8]">Live SERP Simulator</span>
                    </div>

                    <div class="bg-white p-4 rounded-xl border border-[#e2e8f0] shadow-2xs space-y-1 font-sans">
                        <div class="flex items-center gap-2 text-[12px] text-[#202124]">
                            <div class="w-5 h-5 rounded-full bg-[#f1f5f9] flex items-center justify-center text-[10px] font-bold text-[#0284c7] overflow-hidden">
                                @if(!empty($siteSettings['favicon_url']))
                                    <img src="{{ $siteSettings['favicon_url'] }}" class="w-full h-full object-contain">
                                @else
                                    R
                                @endif
                            </div>
                            <span class="text-[12px] text-[#202124] font-medium">{{ $siteSettings['siteName'] ?? 'RaaxO BD' }}</span>
                            <span class="text-[12px] text-[#5f6368]">› product › <span id="serp-slug-preview">{{ $product->slug }}</span></span>
                        </div>
                        <h4 id="serp-title-preview" class="text-[18px] text-[#1a0dab] hover:underline cursor-pointer font-normal leading-snug">
                            {{ $product->meta_title ?: ($product->name . ' — ' . ($product->scent_family ?: 'Luxury Fragrance') . ' | ' . ($siteSettings['siteName'] ?? 'RaaxO BD')) }}
                        </h4>
                        <p id="serp-desc-preview" class="text-[13px] text-[#4d5156] leading-relaxed line-clamp-2">
                            {{ $product->meta_description ?: ($product->short_description ?: 'Discover ' . $product->name . '. Luxury handcrafted perfume extrait with high concentration longevity, available exclusively at ' . ($siteSettings['siteName'] ?? 'RaaxO BD') . '.') }}
                        </p>
                        <div class="flex items-center gap-3 pt-1 text-[11px] text-[#0f766e] font-medium">
                            <span>৳ {{ number_format($product->price, 2) }}</span>
                            <span>•</span>
                            <span class="text-emerald-700 font-bold">✔ In stock</span>
                            <span>•</span>
                            <span>{{ $product->concentration ?: 'Extrait de Parfum' }}</span>
                        </div>
                    </div>
                </div>

                <!-- Custom Meta Title -->
                <div>
                    <div class="flex items-center justify-between mb-1.5">
                        <label class="text-[11px] uppercase font-bold text-[#475569] tracking-wider">
                            Custom Meta Title (Google Page Title)
                        </label>
                        <span id="title-char-count" class="text-[11px] font-mono text-[#64748b]">
                            0 / 60 chars (Recommended: 50-60)
                        </span>
                    </div>
                    <input 
                        type="text" 
                        id="input_meta_title"
                        name="meta_title" 
                        value="{{ old('meta_title', $product->meta_title) }}" 
                        placeholder="e.g. {{ $product->name }} — Luxury Extrait de Parfum | {{ $siteSettings['siteName'] ?? 'RaaxO BD' }}"
                        maxlength="100"
                        oninput="updateSeoPreview()"
                        class="w-full border border-[#cbd5e1] px-4 py-3 rounded-xl text-[14px] font-semibold text-[#0f172a] bg-white focus:outline-none focus:border-[#0284c7] focus:ring-1 focus:ring-[#0284c7] transition-all shadow-xs"
                    >
                    <span class="text-[11px] text-[#64748b] mt-1 block">
                        Leave blank to automatically fallback to: <strong class="text-[#334155]">{{ $product->name }} — {{ $siteSettings['siteName'] ?? 'RaaxO BD' }}</strong>
                    </span>
                </div>

                <!-- Custom Meta Description -->
                <div>
                    <div class="flex items-center justify-between mb-1.5">
                        <label class="text-[11px] uppercase font-bold text-[#475569] tracking-wider">
                            Custom Meta Description (Google Snippet)
                        </label>
                        <span id="desc-char-count" class="text-[11px] font-mono text-[#64748b]">
                            0 / 160 chars (Recommended: 120-160)
                        </span>
                    </div>
                    <textarea 
                        id="input_meta_description"
                        name="meta_description" 
                        rows="3" 
                        maxlength="300"
                        placeholder="e.g. Experience {{ $product->name }}, a refined {{ $product->scent_family ?: 'luxury' }} fragrance crafted with notes of {{ $product->notes_top ?: 'bergamot' }}. Free delivery across Bangladesh."
                        oninput="updateSeoPreview()"
                        class="w-full border border-[#cbd5e1] px-4 py-3 rounded-xl text-[13px] font-medium text-[#0f172a] bg-white focus:outline-none focus:border-[#0284c7] transition-all leading-relaxed shadow-xs"
                    >{{ old('meta_description', $product->meta_description) }}</textarea>
                    <span class="text-[11px] text-[#64748b] mt-1 block">
                        Leave blank to automatically fallback to the product's short editorial excerpt.
                    </span>
                </div>

                <!-- Meta Keywords & Custom Social Image -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="text-[11px] uppercase font-bold text-[#475569] tracking-wider block mb-1.5">
                            SEO Meta Keywords (Comma separated)
                        </label>
                        <input 
                            type="text" 
                            id="input_meta_keywords"
                            name="meta_keywords" 
                            value="{{ old('meta_keywords', $product->meta_keywords) }}" 
                            placeholder="e.g. {{ strtolower($product->name) }}, luxury perfume bangladesh, extrait de parfum, {{ strtolower($product->scent_family ?: 'oud') }}"
                            class="w-full border border-[#cbd5e1] px-4 py-3 rounded-xl text-[13px] font-medium text-[#0f172a] bg-white focus:outline-none focus:border-[#0284c7] transition-all shadow-xs"
                        >
                        <span class="text-[11px] text-[#64748b] mt-1 block">
                            Helps secondary search engines and local directory indexing.
                        </span>
                    </div>

                    <div>
                        <label class="text-[11px] uppercase font-bold text-[#475569] tracking-wider block mb-1.5">
                            Custom Social Share (OG) Image
                        </label>
                        <div class="space-y-2">
                            <input 
                                type="file" 
                                name="og_image_file" 
                                accept="image/*"
                                class="w-full border border-[#cbd5e1] text-[12px] text-[#475569] rounded-xl file:mr-3 file:py-2 file:px-3 file:rounded-l-xl file:border-0 file:text-[11px] file:font-bold file:bg-emerald-600 file:text-white cursor-pointer bg-white"
                            >
                            <input 
                                type="text" 
                                name="og_image_url" 
                                value="{{ old('og_image_url', $product->og_image_url) }}" 
                                placeholder="Or image URL (https://...)"
                                class="w-full border border-[#cbd5e1] px-3.5 py-2 rounded-xl text-[12px] font-mono focus:border-[#0284c7] outline-none bg-white text-[#0f172a] shadow-xs"
                            >
                        </div>
                        <span class="text-[11px] text-[#64748b] mt-1 block">
                            If empty, defaults to the product's primary bottle photo on Facebook/WhatsApp shares.
                        </span>
                    </div>
                </div>

                <!-- Structured Data (Schema.org JSON-LD) Rich Snippet Badge -->
                <div class="p-4 bg-emerald-50/70 border border-emerald-200 rounded-xl flex items-start gap-3">
                    <i data-lucide="check-circle-2" class="w-5 h-5 text-emerald-600 shrink-0 mt-0.5"></i>
                    <div class="text-[12px] text-emerald-900 space-y-1">
                        <strong class="font-bold block">Automatic Schema.org Product Rich Snippet Enabled</strong>
                        <p class="text-emerald-800 text-[11px]">
                            Google search crawlers will automatically receive structured JSON-LD data including product name, price (৳{{ number_format($product->price, 2) }} BDT), stock availability ({{ $product->stock > 0 ? 'InStock' : 'OutOfStock' }}), brand ({{ $siteSettings['siteName'] ?? 'RaaxO BD' }}), and scent family.
                        </p>
                    </div>
                </div>
            </div>

            <!-- Sticky Bottom Form Actions Bar -->
            <div class="sticky bottom-6 bg-white/95 backdrop-blur-xl border border-[#38bdf8]/40 p-5 rounded-2xl shadow-xl flex flex-col sm:flex-row items-center justify-between gap-4 z-30">
                <div class="flex items-center gap-2 text-[12px] text-[#64748b]">
                    <i data-lucide="clock" class="w-4 h-4 text-[#94a3b8]"></i>
                    <span>Last updated: {{ $product->updated_at ? $product->updated_at->diffForHumans() : 'Recently' }}</span>
                </div>

                <div class="flex items-center gap-3 w-full sm:w-auto justify-end">
                    <a 
                        href="{{ url('/admin/products') }}" 
                        class="px-6 py-3 bg-[#f8fafc] hover:bg-[#f1f5f9] border border-[#cbd5e1] text-[#475569] hover:text-[#0f172a] rounded-xl text-[12px] font-bold uppercase tracking-wider transition-all shadow-xs"
                    >
                        Cancel
                    </a>

                    <button 
                        type="submit" 
                        class="px-8 py-3 bg-[#0284c7] hover:bg-[#0369a1] text-white rounded-xl text-[12px] font-bold uppercase tracking-wider cursor-pointer transition-all shadow-md shadow-[#0284c7]/25 flex items-center gap-2"
                    >
                        <i data-lucide="check" class="w-4 h-4"></i> Save Changes to MySQL
                    </button>
                </div>
            </div>
        </form>

        <!-- Danger Zone: Delete Product -->
        <div class="bg-rose-50 border border-rose-200 p-6 sm:p-8 rounded-2xl space-y-4 shadow-xs">
            <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
                <div>
                    <h3 class="text-[15px] font-bold text-rose-700 uppercase tracking-wide flex items-center gap-2">
                        <i data-lucide="trash-2" class="w-4 h-4 text-rose-600"></i> Delete Product from Catalog
                    </h3>
                    <p class="text-[12px] text-rose-600 mt-1">
                        Permanently remove this perfume bottle from your database and storefront catalog.
                    </p>
                </div>

                <form 
                    action="{{ url('/admin/products/' . $product->id) }}" 
                    method="POST" 
                    onsubmit="return confirm('Are you sure you want to permanently delete {{ addslashes($product->name) }}? This action cannot be undone.');"
                >
                    @csrf
                    @method('DELETE')
                    <button 
                        type="submit" 
                        class="px-5 py-2.5 bg-rose-600 hover:bg-rose-700 text-white rounded-xl text-[12px] font-bold uppercase tracking-wider transition-all cursor-pointer shadow-sm"
                    >
                        Delete Product
                    </button>
                </form>
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

        // ── PER-PRODUCT SEO PREVIEW & AUTO-GENERATION ──
        const defaultSiteName = @json($siteSettings['siteName'] ?? 'RaaxO BD');
        const prodBaseName = @json($product->name);
        const prodScentFamily = @json($product->scent_family ?? 'Luxury Fragrance');
        const prodShortDesc = @json($product->short_description ?? '');

        function updateSeoPreview() {
            const titleInput = document.getElementById('input_meta_title');
            const descInput = document.getElementById('input_meta_description');
            const serpTitle = document.getElementById('serp-title-preview');
            const serpDesc = document.getElementById('serp-desc-preview');
            const titleCount = document.getElementById('title-char-count');
            const descCount = document.getElementById('desc-char-count');

            const currentTitle = titleInput?.value.trim() || `${prodBaseName} — ${prodScentFamily} | ${defaultSiteName}`;
            const currentDesc = descInput?.value.trim() || prodShortDesc || `Discover ${prodBaseName}. Luxury handcrafted perfume extrait with exceptional longevity, available at ${defaultSiteName}.`;

            if (serpTitle) serpTitle.textContent = currentTitle;
            if (serpDesc) serpDesc.textContent = currentDesc;

            if (titleCount && titleInput) {
                const len = titleInput.value.length;
                titleCount.textContent = `${len} / 60 chars` + (len > 60 ? ' ⚠ (May be truncated by Google)' : ' (Recommended: 50-60)');
                titleCount.className = len > 60 ? 'text-[11px] font-mono text-amber-600 font-bold' : 'text-[11px] font-mono text-[#64748b]';
            }

            if (descCount && descInput) {
                const len = descInput.value.length;
                descCount.textContent = `${len} / 160 chars` + (len > 160 ? ' ⚠ (May be truncated by Google)' : ' (Recommended: 120-160)');
                descCount.className = len > 160 ? 'text-[11px] font-mono text-amber-600 font-bold' : 'text-[11px] font-mono text-[#64748b]';
            }
        }

        function autoGenerateProductSeo() {
            const titleInput = document.getElementById('input_meta_title');
            const descInput = document.getElementById('input_meta_description');
            const keywordsInput = document.getElementById('input_meta_keywords');

            const prodName = document.querySelector('input[name="name"]')?.value || prodBaseName;
            const scent = document.querySelector('input[name="scent_family"]')?.value || prodScentFamily;
            const concentration = document.querySelector('input[name="concentration"]')?.value || 'Extrait de Parfum';
            const shortDesc = document.querySelector('input[name="short_description"]')?.value || '';
            const topNotes = document.querySelector('input[name="notes_top"]')?.value || '';

            if (titleInput) {
                titleInput.value = `${prodName} — ${concentration} | ${defaultSiteName}`;
            }

            if (descInput) {
                if (shortDesc) {
                    descInput.value = `${shortDesc} Buy ${prodName} authentic fragrance online at ${defaultSiteName} with fast delivery in Bangladesh.`;
                } else {
                    descInput.value = `Shop ${prodName} ${concentration} by ${defaultSiteName}. Handcrafted ${scent} with notes of ${topNotes || 'luxury oils'}. 100% authentic fragrance in BD.`;
                }
            }

            if (keywordsInput && !keywordsInput.value) {
                const kw = [prodName.toLowerCase(), 'perfume bd', 'luxury fragrance', scent.toLowerCase(), 'buy perfume online bangladesh'];
                keywordsInput.value = kw.join(', ');
            }

            updateSeoPreview();
        }

        // Initialize preview on page load
        document.addEventListener('DOMContentLoaded', () => {
            updateSeoPreview();
        });
        updateSeoPreview();
    </script>
</body>
</html>
