<!DOCTYPE html>
<html lang="en" data-theme="{{ $siteSettings['admin_theme'] ?? 'default' }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $siteSettings['siteName'] ?? 'RaaxO BD' }} — Product Catalog & Inventory</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!-- Dynamic Favicon -->
    @php
        $adminFavicon = !empty($siteSettings['favicon_url']) ? $siteSettings['favicon_url'] : (!empty($siteSettings['site_favicon']) ? $siteSettings['site_favicon'] : '/uploads/settings/favicon_1785930191.ico');
    @endphp
    <link rel="icon" id="admin-favicon" href="{{ $adminFavicon }}">

    <script>
        (function() {
            const origWarn = console.warn;
            console.warn = function(...args) {
                if (args[0] && typeof args[0] === 'string' && args[0].includes('cdn.tailwindcss.com')) return;
                origWarn.apply(console, args);
            };
        })();
    </script>
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
        @include('admin.partials.sidebar', ['activePage' => 'products', 'siteSettings' => $siteSettings])

        <!-- 2. MAIN CONTENT WRAPPER -->
        <main class="flex-1 p-6 lg:p-8 w-full space-y-6 relative z-10 overflow-y-auto max-h-[calc(100vh-3.5rem)]">

        <!-- Top Status Bar & Shortcuts -->
        <header class="bg-white/90 backdrop-blur-xl border border-[#38bdf8]/30 rounded-2xl p-6 flex flex-col md:flex-row justify-between items-start md:items-center gap-4 shadow-sm animate-fade-in">
            <div class="flex items-center gap-4">
                <div class="w-12 h-12 rounded-2xl bg-gradient-to-br from-[#0284c7] to-[#0369a1] text-white flex items-center justify-center shadow-lg shadow-[#0284c7]/20">
                    <i data-lucide="boxes" class="w-6 h-6"></i>
                </div>
                <div>
                    <div class="flex items-center gap-2">
                        <span class="text-[11px] font-bold uppercase tracking-widest text-[#0284c7]">CATALOG & INVENTORY</span>
                        <span class="px-2 py-0.5 rounded-full text-[10px] font-bold uppercase bg-[#e0f2fe] text-[#0284c7] border border-[#bae6fd]">
                            {{ count($products) }} BOTTLES
                        </span>
                    </div>
                    <h2 class="text-[22px] font-serif font-bold text-[#0f172a] uppercase tracking-tight">
                        Product Catalog Inventory
                    </h2>
                </div>
            </div>

            <div class="flex items-center flex-wrap gap-2.5">
                <a href="#addProductForm" class="px-4 py-2.5 bg-[#0284c7] hover:bg-[#0369a1] text-white text-[12px] font-bold uppercase tracking-wider rounded-xl transition-all shadow-md shadow-[#0284c7]/20 flex items-center gap-2">
                    <i data-lucide="plus-circle" class="w-4 h-4"></i> Add New Product
                </a>
                <a href="{{ url('/admin/categories') }}" class="px-4 py-2.5 bg-white hover:bg-[#f8fafc] border border-[#cbd5e1] text-[#475569] text-[12px] font-bold uppercase tracking-wider rounded-xl transition-all shadow-xs flex items-center gap-2">
                    <i data-lucide="layers" class="w-4 h-4"></i> Categories
                </a>
                <a href="{{ url('/admin/orders') }}" class="px-4 py-2.5 bg-white hover:bg-[#f8fafc] border border-[#cbd5e1] text-[#475569] text-[12px] font-bold uppercase tracking-wider rounded-xl transition-all shadow-xs flex items-center gap-2">
                    <i data-lucide="shopping-bag" class="w-4 h-4"></i> Orders
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

        @if ($errors->any())
            <div class="p-4 bg-rose-50 border border-rose-300 text-rose-800 text-[13px] rounded-xl space-y-1 shadow-xs animate-fade-in">
                <div class="flex items-center gap-2 font-bold">
                    <i data-lucide="alert-circle" class="w-4 h-4 text-rose-600"></i>
                    <span>Please correct the errors below:</span>
                </div>
                <ul class="list-disc list-inside text-[12px] pl-2 text-rose-700">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <!-- Quick Inventory Metrics Ribbon -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 animate-fade-in">
            @php
                $totalStock = $products->sum('stock');
                $lowStock = $products->where('stock', '<=', 10)->count();
                $featuredCount = $products->where('is_featured', true)->count();
            @endphp
            <div class="bg-white/90 backdrop-blur-xl border border-[#38bdf8]/30 p-5 rounded-2xl shadow-sm card-elevated">
                <span class="text-[11px] font-bold uppercase tracking-widest text-[#64748b] block">TOTAL PERFUMES</span>
                <div class="flex items-baseline justify-between mt-2">
                    <span class="text-2xl font-serif font-bold text-[#0f172a]">{{ count($products) }}</span>
                    <span class="text-xs font-bold text-[#0284c7] bg-[#e0f2fe] px-2.5 py-1 rounded-lg">Catalog Live</span>
                </div>
            </div>

            <div class="bg-white/90 backdrop-blur-xl border border-[#38bdf8]/30 p-5 rounded-2xl shadow-sm card-elevated">
                <span class="text-[11px] font-bold uppercase tracking-widest text-[#64748b] block">TOTAL BOTTLES IN STOCK</span>
                <div class="flex items-baseline justify-between mt-2">
                    <span class="text-2xl font-serif font-bold text-emerald-700">{{ number_format($totalStock) }}</span>
                    <span class="text-xs font-bold text-emerald-700 bg-emerald-50 px-2.5 py-1 rounded-lg">Available</span>
                </div>
            </div>

            <div class="bg-white/90 backdrop-blur-xl border border-[#38bdf8]/30 p-5 rounded-2xl shadow-sm card-elevated">
                <span class="text-[11px] font-bold uppercase tracking-widest text-[#64748b] block">LOW STOCK ALERTS</span>
                <div class="flex items-baseline justify-between mt-2">
                    <span class="text-2xl font-serif font-bold {{ $lowStock > 0 ? 'text-amber-600' : 'text-slate-700' }}">{{ $lowStock }}</span>
                    <span class="text-xs font-bold {{ $lowStock > 0 ? 'text-amber-700 bg-amber-50' : 'text-slate-600 bg-slate-100' }} px-2.5 py-1 rounded-lg">&le; 10 units</span>
                </div>
            </div>

            <div class="bg-white/90 backdrop-blur-xl border border-[#38bdf8]/30 p-5 rounded-2xl shadow-sm card-elevated">
                <span class="text-[11px] font-bold uppercase tracking-widest text-[#64748b] block">FEATURED LUXURY</span>
                <div class="flex items-baseline justify-between mt-2">
                    <span class="text-2xl font-serif font-bold text-purple-700">{{ $featuredCount }}</span>
                    <span class="text-xs font-bold text-purple-700 bg-purple-50 px-2.5 py-1 rounded-lg">Homepage Hero</span>
                </div>
            </div>
        </div>

        <!-- 3. MAIN WORKSPACE: FORM (LEFT 4) & INVENTORY LIST (RIGHT 8) -->
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 animate-fade-in">
            
            <!-- Left Panel: Add New Product Form -->
            <div id="addProductForm" class="lg:col-span-4 bg-white/90 backdrop-blur-xl border border-[#38bdf8]/30 p-7 rounded-2xl space-y-6 shadow-sm">
                <div class="flex items-center gap-3 border-b border-[#e2e8f0] pb-4">
                    <div class="p-2.5 bg-[#e0f2fe] text-[#0284c7] rounded-xl">
                        <i data-lucide="sparkles" class="w-5 h-5"></i>
                    </div>
                    <div>
                        <h3 class="text-[16px] font-serif font-bold text-[#0f172a] uppercase tracking-wide">
                            Add New Fragrance
                        </h3>
                        <p class="text-[11px] text-[#64748b]">Create a new bottle in the master catalog</p>
                    </div>
                </div>

                <form action="{{ url('/admin/products') }}" method="POST" enctype="multipart/form-data" class="space-y-4">
                    @csrf
                    <div>
                        <label class="text-[11px] uppercase font-bold text-[#475569] tracking-wider block mb-1.5">FRAGRANCE NAME *</label>
                        <input type="text" name="name" required placeholder="e.g. Amber Nuit Extrait" class="w-full border border-[#cbd5e1] px-4 py-2.5 rounded-xl text-[13px] font-medium focus:border-[#0284c7] focus:ring-1 focus:ring-[#0284c7] outline-none bg-white text-[#0f172a] transition-all shadow-xs">
                    </div>

                    <div class="grid grid-cols-2 gap-3">
                        <div>
                            <label class="text-[11px] uppercase font-bold text-[#475569] tracking-wider block mb-1.5">CATEGORY</label>
                            <select name="category_id" class="w-full border border-[#cbd5e1] px-3 py-2.5 rounded-xl text-[12px] font-medium focus:border-[#0284c7] outline-none bg-white text-[#0f172a] transition-all shadow-xs">
                                <option value="">Select Category</option>
                                @php
                                    $rootCats = $categories->whereNull('parent_id');
                                @endphp
                                @if($rootCats->isNotEmpty())
                                    @foreach ($rootCats as $cat)
                                        <optgroup label="{{ $cat->name }}">
                                            <option value="{{ $cat->id }}">{{ $cat->name }} (Main)</option>
                                            @foreach ($categories->where('parent_id', $cat->id) as $sub)
                                                <option value="{{ $sub->id }}">&nbsp;&nbsp;↳ {{ $sub->name }}</option>
                                            @endforeach
                                        </optgroup>
                                    @endforeach
                                @else
                                    @foreach ($categories as $cat)
                                        <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                                    @endforeach
                                @endif
                            </select>
                        </div>
                        <div>
                            <label class="text-[11px] uppercase font-bold text-[#475569] tracking-wider block mb-1.5">GENDER TARGET *</label>
                            <select name="gender" required class="w-full border border-[#cbd5e1] px-3 py-2.5 rounded-xl text-[12px] font-medium focus:border-[#0284c7] outline-none bg-white text-[#0f172a] transition-all shadow-xs">
                                <option value="unisex">Unisex</option>
                                <option value="women">Women</option>
                                <option value="men">Men</option>
                            </select>
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-3">
                        <div>
                            <label class="text-[11px] uppercase font-bold text-[#475569] tracking-wider block mb-1.5">PRICE (৳ / $) *</label>
                            <input type="number" step="0.01" name="price" value="3200.00" required class="w-full border border-[#cbd5e1] px-3 py-2.5 rounded-xl text-[13px] font-bold focus:border-[#0284c7] outline-none bg-white text-[#0284c7] transition-all shadow-xs">
                        </div>
                        <div>
                            <label class="text-[11px] uppercase font-bold text-[#475569] tracking-wider block mb-1.5">STOCK UNITS *</label>
                            <input type="number" name="stock" value="50" required class="w-full border border-[#cbd5e1] px-3 py-2.5 rounded-xl text-[13px] font-bold focus:border-[#0284c7] outline-none bg-white text-[#0f172a] transition-all shadow-xs">
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-3">
                        <div>
                            <label class="text-[11px] uppercase font-bold text-[#475569] tracking-wider block mb-1.5">SCENT FAMILY</label>
                            <input type="text" name="scent_family" placeholder="Floral Amber" class="w-full border border-[#cbd5e1] px-3 py-2.5 rounded-xl text-[12px] focus:border-[#0284c7] outline-none bg-white text-[#0f172a] transition-all shadow-xs">
                        </div>
                        <div>
                            <label class="text-[11px] uppercase font-bold text-[#475569] tracking-wider block mb-1.5">CONCENTRATION</label>
                            <input type="text" name="concentration" value="Eau de Parfum" class="w-full border border-[#cbd5e1] px-3 py-2.5 rounded-xl text-[12px] focus:border-[#0284c7] outline-none bg-white text-[#0f172a] transition-all shadow-xs">
                        </div>
                    </div>

                    <!-- Bottle Image Upload / URL -->
                    <div class="space-y-2 pt-1 border-t border-[#e2e8f0]">
                        <label class="text-[11px] uppercase font-bold text-[#475569] tracking-wider block">BOTTLE IMAGE</label>
                        <input type="file" name="primary_image_file" accept="image/*" class="w-full border border-[#cbd5e1] text-[12px] text-[#475569] rounded-xl file:mr-3 file:py-2 file:px-3 file:rounded-l-xl file:border-0 file:text-[11px] file:font-bold file:bg-[#0284c7] file:text-white cursor-pointer bg-white">
                        <input type="text" name="primary_image_url" placeholder="Or paste Image URL (https://...)" class="w-full border border-[#cbd5e1] px-3 py-2 rounded-xl text-[12px] focus:border-[#0284c7] outline-none bg-white text-[#0f172a] shadow-xs">
                    </div>

                    <div>
                        <label class="text-[11px] uppercase font-bold text-[#475569] tracking-wider block mb-1.5">SHORT EDITORIAL DESCRIPTION</label>
                        <textarea name="short_description" rows="2" placeholder="Brief olfactory notes & fragrance profile..." class="w-full border border-[#cbd5e1] px-3 py-2 rounded-xl text-[12px] focus:border-[#0284c7] outline-none bg-white text-[#0f172a] shadow-xs"></textarea>
                    </div>

                    <div class="flex items-center gap-6 pt-1">
                        <label class="flex items-center gap-2 cursor-pointer text-[12px] font-semibold text-[#475569]">
                            <input type="checkbox" name="is_featured" value="1" class="rounded border-[#cbd5e1] text-[#0284c7] focus:ring-[#0284c7] w-4 h-4">
                            <span>Featured Fragrance</span>
                        </label>
                        <label class="flex items-center gap-2 cursor-pointer text-[12px] font-semibold text-[#475569]">
                            <input type="checkbox" name="is_new_arrival" value="1" checked class="rounded border-[#cbd5e1] text-[#0284c7] focus:ring-[#0284c7] w-4 h-4">
                            <span>New Arrival</span>
                        </label>
                    </div>

                    <button type="submit" class="w-full bg-[#0284c7] hover:bg-[#0369a1] text-white py-3.5 rounded-xl text-[12px] font-bold uppercase tracking-wider cursor-pointer transition-all shadow-md shadow-[#0284c7]/20 flex items-center justify-center gap-2 mt-2">
                        <i data-lucide="plus" class="w-4 h-4"></i> Save Perfume to Catalog
                    </button>
                </form>
            </div>

            <!-- Right Panel: Master Inventory Catalog Table -->
            <div class="lg:col-span-8 bg-white/90 backdrop-blur-xl border border-[#38bdf8]/30 p-7 rounded-2xl space-y-5 shadow-sm">
                <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 border-b border-[#e2e8f0] pb-4">
                    <div class="flex items-center gap-3">
                        <div class="p-2.5 bg-[#e0f2fe] text-[#0284c7] rounded-xl">
                            <i data-lucide="list" class="w-5 h-5"></i>
                        </div>
                        <div>
                            <h3 class="text-[16px] font-serif font-bold text-[#0f172a] uppercase tracking-wide">
                                Master Inventory List
                            </h3>
                            <p class="text-[11px] text-[#64748b]">Live database catalog & stock tracker</p>
                        </div>
                    </div>
                    
                    <!-- Search & Filter Controls -->
                    <div class="flex items-center gap-2.5 w-full sm:w-auto">
                        <div class="relative flex-1 sm:w-56">
                            <i data-lucide="search" class="w-4 h-4 absolute left-3 top-1/2 -translate-y-1/2 text-[#94a3b8]"></i>
                            <input type="text" id="productSearchInput" onkeyup="filterProductTable()" placeholder="Search perfume..." class="w-full pl-9 pr-3 py-2 text-[12px] border border-[#cbd5e1] rounded-xl outline-none focus:border-[#0284c7] bg-white text-[#0f172a] shadow-xs">
                        </div>
                        <select id="genderFilter" onchange="filterProductTable()" class="px-3 py-2 text-[12px] border border-[#cbd5e1] rounded-xl outline-none focus:border-[#0284c7] bg-white text-[#0f172a] font-medium shadow-xs">
                            <option value="">All Genders</option>
                            <option value="unisex">Unisex</option>
                            <option value="women">Women</option>
                            <option value="men">Men</option>
                        </select>
                    </div>
                </div>

                <!-- Products Table -->
                <div class="overflow-x-auto rounded-xl border border-[#e2e8f0] shadow-2xs">
                    <table class="w-full text-left text-[13px]" id="productsMasterTable">
                        <thead class="bg-[#f8fafc] text-[#475569] text-[11px] uppercase font-bold tracking-wider border-b border-[#e2e8f0]">
                            <tr>
                                <th class="p-3.5">BOTTLE</th>
                                <th class="p-3.5">PRODUCT NAME</th>
                                <th class="p-3.5">CATEGORY & GENDER</th>
                                <th class="p-3.5">PRICE</th>
                                <th class="p-3.5">STOCK</th>
                                <th class="p-3.5 text-right">ACTIONS</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-[#e2e8f0] bg-white">
                            @forelse ($products as $p)
                                <tr class="hover:bg-[#f0f9ff]/60 transition-colors product-row" data-name="{{ strtolower($p->name) }}" data-gender="{{ strtolower($p->gender) }}" data-category="{{ strtolower($p->category->name ?? '') }}">
                                    <td class="p-3.5 w-16">
                                        @if(!empty($p->primary_image_url))
                                            <img src="{{ $p->primary_image_url }}" alt="{{ $p->name }}" class="w-12 h-12 rounded-xl object-contain bg-[#f8fafc] border border-[#e2e8f0] p-1 shadow-xs">
                                        @else
                                            <div class="w-12 h-12 rounded-xl bg-[#f1f5f9] border border-[#e2e8f0] flex items-center justify-center text-[#94a3b8]">
                                                <i data-lucide="image" class="w-5 h-5"></i>
                                            </div>
                                        @endif
                                    </td>
                                    <td class="p-3.5">
                                        <div class="font-bold text-[#0f172a] text-[13px]">{{ $p->name }}</div>
                                        <div class="text-[11px] text-[#64748b] mt-0.5">
                                            {{ $p->scent_family ?? $p->concentration ?? 'Fine Fragrance' }}
                                        </div>
                                    </td>
                                    <td class="p-3.5">
                                        <div class="text-[#334155] font-semibold text-[12px]">{{ $p->category->name ?? 'Uncategorized' }}</div>
                                        <span class="inline-block mt-1 px-2 py-0.5 rounded text-[10px] font-bold uppercase tracking-wider {{ $p->gender === 'women' ? 'bg-pink-50 text-pink-700 border border-pink-200' : ($p->gender === 'men' ? 'bg-sky-50 text-sky-700 border border-sky-200' : 'bg-emerald-50 text-emerald-700 border border-emerald-200') }}">
                                            {{ $p->gender }}
                                        </span>
                                    </td>
                                    <td class="p-3.5 text-[#0284c7] font-bold font-mono text-[14px]">
                                        ৳{{ number_format((float)$p->price, 0) }}
                                    </td>
                                    <td class="p-3.5">
                                        @if($p->stock > 10)
                                            <span class="px-2.5 py-1 bg-emerald-50 border border-emerald-200 text-emerald-700 rounded-lg font-bold text-[11px]">
                                                {{ $p->stock }} in stock
                                            </span>
                                        @elseif($p->stock > 0)
                                            <span class="px-2.5 py-1 bg-amber-50 border border-amber-200 text-amber-700 rounded-lg font-bold text-[11px]">
                                                {{ $p->stock }} low stock
                                            </span>
                                        @else
                                            <span class="px-2.5 py-1 bg-rose-50 border border-rose-200 text-rose-700 rounded-lg font-bold text-[11px]">
                                                Out of Stock
                                            </span>
                                        @endif
                                    </td>
                                    <td class="p-3.5 text-right space-x-1 whitespace-nowrap">
                                        <a href="{{ url('/admin/products/' . $p->id . '/edit') }}" class="inline-flex items-center gap-1 px-3 py-1.5 bg-[#e0f2fe] hover:bg-[#0284c7] text-[#0284c7] hover:text-white border border-[#bae6fd] hover:border-[#0284c7] rounded-lg text-[11px] font-bold uppercase tracking-wider transition-all shadow-xs">
                                            <i data-lucide="edit-3" class="w-3.5 h-3.5"></i> Edit
                                        </a>
                                        <a href="{{ url('/product/' . $p->slug) }}" target="_blank" class="inline-flex items-center p-1.5 bg-[#f8fafc] hover:bg-[#f1f5f9] border border-[#cbd5e1] text-[#475569] hover:text-[#0284c7] rounded-lg transition-all shadow-xs" title="View live on storefront">
                                            <i data-lucide="external-link" class="w-3.5 h-3.5"></i>
                                        </a>
                                        <form action="{{ url('/admin/products/' . $p->id) }}" method="POST" class="inline-block" onsubmit="return confirm('Permanently delete {{ addslashes($p->name) }} from database?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="p-1.5 bg-rose-50 hover:bg-rose-600 border border-rose-200 hover:border-rose-600 text-rose-600 hover:text-white rounded-lg transition-all cursor-pointer shadow-xs" title="Delete product">
                                                <i data-lucide="trash-2" class="w-3.5 h-3.5"></i>
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="p-10 text-center text-[#64748b] font-medium">
                                        <div class="flex flex-col items-center justify-center space-y-2">
                                            <i data-lucide="package-open" class="w-8 h-8 text-[#94a3b8]"></i>
                                            <span>No products found in the catalog database.</span>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Classic Minimal Admin Footer -->
        @include('admin.partials.footer')
    </main>
    </div>

    <script>
        lucide.createIcons();

        const allSubmenus = ['orders', 'product', 'purchase', 'contact', 'courier', 'api_gateway', 'seo_sub', 'user_mgmt'];
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

        function filterProductTable() {
            const query = document.getElementById('productSearchInput').value.toLowerCase().trim();
            const gender = document.getElementById('genderFilter').value.toLowerCase().trim();
            const rows = document.querySelectorAll('.product-row');

            rows.forEach(row => {
                const name = row.getAttribute('data-name') || '';
                const rowGender = row.getAttribute('data-gender') || '';
                const category = row.getAttribute('data-category') || '';

                const matchesQuery = !query || name.includes(query) || category.includes(query);
                const matchesGender = !gender || rowGender === gender;

                if (matchesQuery && matchesGender) {
                    row.style.display = '';
                } else {
                    row.style.display = 'none';
                }
            });
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
