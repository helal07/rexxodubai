<!DOCTYPE html>
<html lang="en" data-theme="{{ $siteSettings['admin_theme'] ?? 'default' }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $siteSettings['siteName'] ?? 'RaaxO BD' }} — Master Admin Panel</title>
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
    <!-- Instant Zero-Flicker Pre-Router: Prevents brief flash of dashboard when loading a direct hash menu URL -->
    <script>
        (function() {
            var h = window.location.hash.replace('#', '');
            if (h && h !== 'dashboard') {
                document.write('<style id="pre-route-hide">#section-dashboard { display: none !important; } #section-' + h + ' { display: block !important; }</style>');
            }
        })();
    </script>
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

        /* Card Hover Lift & Glow */
        .card-elevated {
            transition: transform 0.25s cubic-bezier(0.16, 1, 0.3, 1), box-shadow 0.25s cubic-bezier(0.16, 1, 0.3, 1);
        }
        .card-elevated:hover {
            transform: translateY(-2px);
            box-shadow: 0 12px 28px -4px rgba(2, 132, 199, 0.14);
        }

        /* Submenu accordion slide animation */
        .submenu-panel {
            overflow: hidden;
            max-height: 0;
            opacity: 0;
            transition: max-height 0.35s cubic-bezier(0.4, 0, 0.2, 1),
                        opacity 0.25s ease,
                        margin-top 0.35s ease;
            margin-top: 0;
        }
        .submenu-panel.submenu-open {
            max-height: 220px;
            opacity: 1;
            margin-top: 0.35rem;
        }

        /* Chevron rotation for open submenus */
        .submenu-chevron {
            display: inline-flex;
            align-items: center;
            transition: transform 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }
        .submenu-chevron.chevron-open {
            transform: rotate(180deg);
        }

        /* Section content fade + slide animation */
        .section-content {
            transition: opacity 0.3s ease, transform 0.3s ease;
        }
        .section-content.section-entering {
            animation: sectionFadeIn 0.35s cubic-bezier(0.16, 1, 0.3, 1) forwards;
        }
        @keyframes sectionFadeIn {
            from { opacity: 0; transform: translateY(12px); }
            to   { opacity: 1; transform: translateY(0); }
        }

        /* Shimmer button effect */
        .btn-shimmer {
            position: relative;
            overflow: hidden;
        }
        .btn-shimmer::after {
            content: '';
            position: absolute;
            top: -50%;
            left: -50%;
            width: 200%;
            height: 200%;
            background: linear-gradient(60deg, transparent, rgba(255, 255, 255, 0.2), transparent);
            transform: translateX(-100%);
            transition: transform 0.6s ease;
        }
        .btn-shimmer:hover::after {
            transform: translateX(100%);
        }

        /* Night Mode Theme Overrides */
        body.theme-night {
            background: linear-gradient(135deg, #07090E 0%, #0D121F 50%, #05070B 100%) !important;
            color: #f1f5f9 !important;
        }
        body.theme-night aside,
        body.theme-night header,
        body.theme-night .section-content > div,
        body.theme-night .bg-white\/90,
        body.theme-night .bg-white\/80 {
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
        body.theme-night .bg-\[\#f8fafc\],
        body.theme-night .bg-\[\#f1f5f9\] {
            background-color: #131A2B !important;
            border-color: #1E283D !important;
            color: #f1f5f9 !important;
        }

        /* Light Mode Clean */
        body.theme-light {
            background: linear-gradient(135deg, #f8fafc 0%, #f1f5f9 50%, #e2e8f0 100%) !important;
            color: #0f172a !important;
        }
    </style>
</head>
<body class="bg-[#f8fafc] text-[#0f172a] font-sans flex flex-col min-h-screen relative overflow-x-hidden selection:bg-[#4338ca] selection:text-white">
    
    <div id="toast" class="hidden fixed top-16 right-6 z-50 bg-[#4338ca] text-white px-5 py-3 rounded-xl shadow-2xl border border-white/20 flex items-center gap-3 animate-fade-in text-[13px] font-bold">
        <i data-lucide="check-circle-2" class="w-5 h-5 text-emerald-300"></i>
        <span id="toastMsg">Action completed successfully!</span>
    </div>

    <!-- 1. FULL WIDTH MASTER TOP APP BAR -->
    @include('admin.partials.header')

    <!-- 2. APP WORKSPACE (SIDEBAR ON LEFT + MAIN CONTENT ON RIGHT) -->
    <div class="flex flex-1 w-full min-h-0 relative overflow-hidden">
        <!-- 1. LEFT SIDEBAR MENU BAR -->
        @include('admin.partials.sidebar', ['activePage' => 'dashboard', 'siteSettings' => $siteSettings])

        <!-- 2. MAIN CONTENT AREA -->
        <main class="flex-1 p-6 space-y-6 relative z-10 overflow-y-auto max-h-[calc(100vh-3.5rem)]">

        <!-- SECTION 1: EXECUTIVE DASHBOARD (FULL POPULATED ANALYTICS & LIVE STREAM) -->
        <div id="section-dashboard" class="section-content space-y-6 animate-fade-in">
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-4">
                <div class="bg-white/90 border border-[#38bdf8]/30 p-4 rounded-2xl shadow-sm"><span class="text-[11px] font-bold text-[#64748b] uppercase">TOTAL CUSTOMERS</span><div class="text-[22px] font-bold font-serif">1,482</div></div>
                <div class="bg-white/90 border border-[#38bdf8]/30 p-4 rounded-2xl shadow-sm"><span class="text-[11px] font-bold text-[#64748b] uppercase">TOTAL ORDERS</span><div class="text-[22px] font-bold font-serif">384</div></div>
                <div class="bg-white/90 border border-sky-400/40 p-4 rounded-2xl shadow-sm bg-gradient-to-br from-white to-sky-50"><span class="text-[11px] font-bold text-sky-700 uppercase">IN WAY ORDERS</span><div class="text-[22px] font-bold text-sky-900 font-serif">4 Orders</div></div>
                <div class="bg-white/90 border border-emerald-400/40 p-4 rounded-2xl shadow-sm bg-gradient-to-br from-white to-emerald-50"><span class="text-[11px] font-bold text-emerald-700 uppercase">SUCCESS ORDERS</span><div class="text-[22px] font-bold text-emerald-900 font-serif">318 Orders</div></div>
                <div class="bg-white/90 border border-rose-300/40 p-4 rounded-2xl shadow-sm bg-gradient-to-br from-white to-rose-50"><span class="text-[11px] font-bold text-rose-700 uppercase">RETURN ORDERS</span><div class="text-[22px] font-bold text-rose-900 font-serif">24 Orders</div></div>
            </div>

            <!-- Analytics Bar Chart & Monthly Trend -->
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">
                <div class="lg:col-span-7 bg-white/90 border border-[#38bdf8]/30 p-6 rounded-2xl shadow-sm space-y-4">
                    <div class="flex justify-between items-center border-b pb-3">
                        <h3 class="text-[15px] font-serif font-bold text-[#0f172a] uppercase flex items-center gap-2">
                            <i data-lucide="bar-chart-3" class="w-4 h-4 text-[#0284c7]"></i> Weekly Sales Velocity (৳ BDT)
                        </h3>
                        <span class="text-[11px] text-emerald-700 font-bold bg-emerald-50 border border-emerald-300 px-2.5 py-0.5 rounded-full">+18.4% vs last week</span>
                    </div>

                    <div class="h-44 flex items-end justify-between gap-3 pt-6 px-2">
                        <div class="flex-1 flex flex-col items-center gap-2">
                            <div class="w-full bg-[#e0f2fe] rounded-t-lg relative h-32 flex items-end"><div style="height: 40%" class="w-full bg-[#0284c7] rounded-t-lg"></div></div>
                            <span class="text-[11px] font-bold text-[#64748b]">Mon</span>
                        </div>
                        <div class="flex-1 flex flex-col items-center gap-2">
                            <div class="w-full bg-[#e0f2fe] rounded-t-lg relative h-32 flex items-end"><div style="height: 65%" class="w-full bg-[#0284c7] rounded-t-lg"></div></div>
                            <span class="text-[11px] font-bold text-[#64748b]">Tue</span>
                        </div>
                        <div class="flex-1 flex flex-col items-center gap-2">
                            <div class="w-full bg-[#e0f2fe] rounded-t-lg relative h-32 flex items-end"><div style="height: 50%" class="w-full bg-[#0284c7] rounded-t-lg"></div></div>
                            <span class="text-[11px] font-bold text-[#64748b]">Wed</span>
                        </div>
                        <div class="flex-1 flex flex-col items-center gap-2">
                            <div class="w-full bg-[#e0f2fe] rounded-t-lg relative h-32 flex items-end"><div style="height: 85%" class="w-full bg-[#0284c7] rounded-t-lg"></div></div>
                            <span class="text-[11px] font-bold text-[#64748b]">Thu</span>
                        </div>
                        <div class="flex-1 flex flex-col items-center gap-2">
                            <div class="w-full bg-[#e0f2fe] rounded-t-lg relative h-32 flex items-end"><div style="height: 100%" class="w-full bg-[#0284c7] rounded-t-lg"></div></div>
                            <span class="text-[11px] font-bold text-[#64748b]">Fri</span>
                        </div>
                        <div class="flex-1 flex flex-col items-center gap-2">
                            <div class="w-full bg-[#e0f2fe] rounded-t-lg relative h-32 flex items-end"><div style="height: 90%" class="w-full bg-[#0284c7] rounded-t-lg"></div></div>
                            <span class="text-[11px] font-bold text-[#64748b]">Sat</span>
                        </div>
                        <div class="flex-1 flex flex-col items-center gap-2">
                            <div class="w-full bg-[#e0f2fe] rounded-t-lg relative h-32 flex items-end"><div style="height: 70%" class="w-full bg-[#0284c7] rounded-t-lg"></div></div>
                            <span class="text-[11px] font-bold text-[#64748b]">Sun</span>
                        </div>
                    </div>
                </div>

                <div class="lg:col-span-5 bg-white/90 border border-[#38bdf8]/30 p-6 rounded-2xl shadow-sm space-y-4">
                    <div class="border-b pb-3">
                        <h3 class="text-[15px] font-serif font-bold text-[#0f172a] uppercase flex items-center gap-2">
                            <i data-lucide="trending-up" class="w-4 h-4 text-[#0284c7]"></i> Monthly Revenue Growth
                        </h3>
                    </div>
                    <div class="space-y-4">
                        <div>
                            <span class="text-[11px] text-[#64748b] font-bold uppercase block">TOTAL AUGUST REVENUE</span>
                            <span class="text-[28px] font-serif font-bold text-[#0284c7]">৳482,900 BDT</span>
                        </div>
                        <div class="w-full bg-[#f1f5f9] h-2.5 rounded-full overflow-hidden border">
                            <div class="bg-gradient-to-r from-[#0284c7] to-emerald-500 h-full w-[82%]"></div>
                        </div>
                        <div class="grid grid-cols-2 gap-3 pt-2 text-[12px]">
                            <div class="bg-[#f8fafc] p-3 rounded-xl border"><span class="text-[#64748b] block font-bold">Avg Order Value</span><span class="font-bold font-mono">৳3,150 BDT</span></div>
                            <div class="bg-[#f8fafc] p-3 rounded-xl border"><span class="text-[#64748b] block font-bold">Conversion Rate</span><span class="font-bold text-emerald-700 font-mono">4.82%</span></div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Dashboard Bottom Grid -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                <!-- Recent Orders Stream Table -->
                <div class="bg-white/90 border border-[#38bdf8]/30 p-6 rounded-2xl shadow-sm space-y-4">
                    <div class="flex justify-between items-center border-b pb-3">
                        <h3 class="text-[16px] font-serif font-bold text-[#0f172a] uppercase flex items-center gap-2">
                            <i data-lucide="clock" class="w-4 h-4 text-[#0284c7]"></i> Live Orders Stream
                        </h3>
                        <button onclick="switchSection('orders')" class="text-[12px] font-bold text-[#0284c7] uppercase hover:underline">View All →</button>
                    </div>

                    <div class="border rounded-xl overflow-hidden shadow-xs">
                        <table class="w-full text-left text-[13px]">
                            <thead class="bg-[#f1f5f9] font-bold uppercase text-[#475569] border-b">
                                <tr>
                                    <th class="p-3.5">ORDER ID</th>
                                    <th class="p-3.5">CLIENT</th>
                                    <th class="p-3.5 text-right">AMOUNT (BDT)</th>
                                </tr>
                            </thead>
                            <tbody id="dashStreamTableBody" class="divide-y divide-[#e2e8f0]"></tbody>
                        </table>
                    </div>
                </div>

                <!-- Inventory Stock Status Table -->
                <div class="bg-white/90 border border-[#38bdf8]/30 p-6 rounded-2xl shadow-sm space-y-4">
                    <div class="flex justify-between items-center border-b pb-3">
                        <h3 class="text-[16px] font-serif font-bold text-[#0f172a] uppercase flex items-center gap-2">
                            <i data-lucide="package" class="w-4 h-4 text-[#0284c7]"></i> Inventory Status
                        </h3>
                        <button onclick="switchSection('products')" class="text-[12px] font-bold text-[#0284c7] uppercase hover:underline">Manage Catalog →</button>
                    </div>

                    <div class="border rounded-xl overflow-hidden shadow-xs">
                        <table class="w-full text-left text-[13px]">
                            <thead class="bg-[#f1f5f9] font-bold uppercase text-[#475569] border-b">
                                <tr>
                                    <th class="p-3.5">PRODUCT NAME</th>
                                    <th class="p-3.5 text-center">STOCK</th>
                                    <th class="p-3.5 text-center">STATUS</th>
                                </tr>
                            </thead>
                            <tbody id="dashStockTableBody" class="divide-y divide-[#e2e8f0]"></tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- SECTION 2: ORDERS HUB -->
        <div id="section-orders" class="section-content hidden bg-white/90 border p-6 rounded-2xl space-y-6">
            <div class="flex justify-between items-center border-b pb-4">
                <h2 class="text-[18px] font-serif font-bold text-[#0f172a] uppercase">Customer Orders Hub (Currency: ৳ BDT)</h2>
                <div class="flex items-center gap-1.5 bg-[#f1f5f9] p-1.5 rounded-xl border">
                    <button onclick="switchOrdersSub('total')" id="osub-total" class="px-3.5 py-1.5 text-[11px] font-bold uppercase rounded-lg bg-[#0284c7] text-white">Total Orders</button>
                    <button onclick="switchOrdersSub('success')" id="osub-success" class="px-3.5 py-1.5 text-[11px] font-bold uppercase rounded-lg text-[#475569]">Success Orders</button>
                    <button onclick="switchOrdersSub('return')" id="osub-return" class="px-3.5 py-1.5 text-[11px] font-bold uppercase rounded-lg text-[#475569]">Return Orders</button>
                </div>
            </div>

            <div class="border rounded-xl overflow-visible shadow-xs">
                <table class="w-full text-left text-[13px]">
                    <thead class="bg-[#f1f5f9] font-bold uppercase">
                        <tr>
                            <th class="p-3.5 rounded-tl-xl">ORDER ID</th>
                            <th class="p-3.5">CLIENT</th>
                            <th class="p-3.5">PRODUCTS</th>
                            <th class="p-3.5">AMOUNT (BDT)</th>
                            <th class="p-3.5 text-center">STATUS</th>
                            <th class="p-3.5 text-right rounded-tr-xl">ACTION</th>
                        </tr>
                    </thead>
                    <tbody id="ordersMasterTableBody"></tbody>
                </table>
            </div>
        </div>

        <!-- SECTION 3: POS & CREATE SALE TERMINAL -->
        <div id="section-create_order" class="section-content hidden space-y-6">
            <!-- Header Bar -->
            <div class="bg-white/90 border border-[#38bdf8]/30 p-6 rounded-2xl shadow-sm flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
                <div class="flex items-center gap-3.5">
                    <div class="w-11 h-11 rounded-xl bg-gradient-to-br from-emerald-600 to-teal-700 text-white flex items-center justify-center shadow-md shadow-emerald-600/20">
                        <i data-lucide="shopping-cart" class="w-5 h-5"></i>
                    </div>
                    <div>
                        <div class="flex items-center gap-2">
                            <span class="text-[10px] font-bold uppercase tracking-widest text-emerald-700 bg-emerald-50 px-2 py-0.5 rounded-md border border-emerald-200">POINT OF SALE TERMINAL</span>
                            <span class="text-[10px] text-slate-500 font-mono">LIVE COUNTER DISPATCH</span>
                        </div>
                        <h2 class="text-[18px] font-serif font-bold text-[#0f172a] uppercase">Create New Sale / POS Order</h2>
                    </div>
                </div>
                <div class="flex items-center gap-2">
                    <button type="button" onclick="resetSaleForm()" class="px-3.5 py-2 text-[12px] font-bold text-slate-600 bg-white border border-slate-200 rounded-xl hover:bg-slate-50 transition-colors flex items-center gap-1.5 cursor-pointer">
                        <i data-lucide="rotate-ccw" class="w-3.5 h-3.5"></i> Reset Form
                    </button>
                    <button type="button" onclick="switchSection('orders')" class="px-3.5 py-2 text-[12px] font-bold text-[#0284c7] bg-sky-50 border border-sky-200 rounded-xl hover:bg-sky-100 transition-colors flex items-center gap-1.5 cursor-pointer">
                        <i data-lucide="list" class="w-3.5 h-3.5"></i> All Sales / Orders
                    </button>
                </div>
            </div>

            <!-- Main POS 2-Column Grid -->
            <form id="createSaleMasterForm" onsubmit="handleCreateOrderSubmit(event)" class="grid grid-cols-1 lg:grid-cols-12 gap-6">
                <!-- LEFT: Customer Details & Cart Item Picker (7 cols) -->
                <div class="lg:col-span-7 space-y-6">
                    <!-- 1. Customer Selection Card -->
                    <div class="bg-white/90 border border-slate-200 p-6 rounded-2xl shadow-xs space-y-4">
                        <div class="flex justify-between items-center border-b border-slate-100 pb-3">
                            <h3 class="text-[13px] font-bold text-[#0f172a] uppercase flex items-center gap-2">
                                <i data-lucide="user" class="w-4 h-4 text-emerald-600"></i> Step 1: Customer Information
                            </h3>
                            <button type="button" onclick="openAddCustomerPrompt()" class="bg-[#e0f2fe] hover:bg-[#bae6fd] text-[#0284c7] px-3 py-1 rounded-lg text-[11px] font-bold uppercase transition-colors flex items-center gap-1 cursor-pointer">
                                <i data-lucide="user-plus" class="w-3.5 h-3.5"></i> + Quick Add
                            </button>
                        </div>

                        <div class="space-y-3">
                            <div>
                                <label class="text-[11px] font-bold uppercase text-slate-600 block mb-1">Select Registered Customer or Walk-in</label>
                                <select id="coCustomerSelect" onchange="handleCustomerSelectChange(this.value)" class="w-full border border-slate-300 p-2.5 rounded-xl text-[13px] font-medium focus:border-emerald-600 focus:ring-1 focus:ring-emerald-600 outline-none bg-white text-slate-800">
                                    <option value="Walk-in Customer|01700000000|Store Counter, Dhaka">🚶 Walk-in Customer (Store Counter)</option>
                                    <option value="Shakib Al Hasan|01700112233|House 14, Road 5, Banani, Dhaka">Shakib Al Hasan (+8801700112233) — Banani, Dhaka</option>
                                    <option value="Tanvir Hossain|01822334455|Sector 7, Uttara, Dhaka">Tanvir Hossain (+8801822334455) — Uttara, Dhaka</option>
                                    <option value="Mahmudur Rahman|01711223344|GEC Circle, Chittagong">Mahmudur Rahman (+8801711223344) — Chittagong</option>
                                    <option value="Sabrina Sultana|01999887766|Dhanmondi 27, Dhaka">Sabrina Sultana (+8801999887766) — Dhanmondi, Dhaka</option>
                                </select>
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-3 pt-1">
                                <div>
                                    <label class="text-[11px] font-bold uppercase text-slate-500 block mb-1">Customer Phone Number</label>
                                    <input type="text" id="coCustomerPhone" placeholder="017xxxxxxxx" value="01700000000" class="w-full border border-slate-300 px-3 py-2 rounded-xl text-[12px] font-mono font-medium focus:border-emerald-600 outline-none bg-slate-50 text-slate-800">
                                </div>
                                <div>
                                    <label class="text-[11px] font-bold uppercase text-slate-500 block mb-1">Delivery / Counter Address</label>
                                    <input type="text" id="coCustomerAddress" placeholder="Street, Area, City" value="Store Counter, Dhaka" class="w-full border border-slate-300 px-3 py-2 rounded-xl text-[12px] font-medium focus:border-emerald-600 outline-none bg-slate-50 text-slate-800">
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- 2. Product Catalog Selection & Cart Builder Card -->
                    <div class="bg-white/90 border border-slate-200 p-6 rounded-2xl shadow-xs space-y-4">
                        <div class="flex justify-between items-center border-b border-slate-100 pb-3">
                            <h3 class="text-[13px] font-bold text-[#0f172a] uppercase flex items-center gap-2">
                                <i data-lucide="package-plus" class="w-4 h-4 text-emerald-600"></i> Step 2: Add Products to Sale Cart
                            </h3>
                            <span class="text-[11px] font-bold text-slate-500 font-mono" id="posCartCountBadge">0 Items Added</span>
                        </div>

                        <!-- Product Selector Dropdown -->
                        <div class="space-y-2">
                            <label class="text-[11px] font-bold uppercase text-slate-600 block">Choose Product From Database</label>
                            <div class="flex items-center gap-2">
                                <select id="coProductSelect" onchange="handleAddProductToCart()" class="flex-1 border border-slate-300 p-2.5 rounded-xl text-[13px] font-semibold focus:border-emerald-600 focus:ring-1 focus:ring-emerald-600 outline-none bg-white text-slate-800">
                                    <option value="">+ Click to Select & Add Product...</option>
                                    @if(isset($products) && count($products) > 0)
                                        @foreach($products as $prod)
                                            <option value="{{ $prod->name }}|{{ $prod->price }}|{{ $prod->id }}">
                                                {{ $prod->name }} — ৳{{ number_format($prod->price) }} BDT (Stock: {{ $prod->stock_quantity ?? 50 }})
                                            </option>
                                        @endforeach
                                    @else
                                        <option value="L'Ombre d'Ambre 100ml|3200|1">L'Ombre d'Ambre 100ml — ৳3,200 BDT</option>
                                        <option value="Velours de Rose 100ml|2850|2">Velours de Rose 100ml — ৳2,850 BDT</option>
                                        <option value="Cuir Noir Extrait 100ml|3800|3">Cuir Noir Extrait 100ml — ৳3,800 BDT</option>
                                        <option value="Soleil d'Or Eau de Parfum|2950|4">Soleil d'Or Eau de Parfum — ৳2,950 BDT</option>
                                    @endif
                                </select>
                                <button type="button" onclick="handleAddProductToCart()" class="px-4 py-2.5 bg-emerald-600 hover:bg-emerald-700 text-white rounded-xl text-[12px] font-bold uppercase tracking-wider flex items-center gap-1.5 transition-colors shrink-0 cursor-pointer shadow-xs">
                                    <i data-lucide="plus" class="w-4 h-4"></i> Add
                                </button>
                            </div>
                        </div>

                        <!-- Cart Items Container -->
                        <div class="space-y-2 pt-2">
                            <label class="text-[11px] font-bold uppercase text-slate-500 block">Sale Order Items</label>
                            <div id="cartItemsList" class="space-y-2 max-h-72 overflow-y-auto pr-1">
                                <!-- Empty state by default -->
                                <div id="emptyCartPlaceholder" class="p-6 text-center border-2 border-dashed border-slate-200 rounded-xl bg-slate-50 text-slate-400 text-[12px] font-medium">
                                    <i data-lucide="shopping-bag" class="w-8 h-8 mx-auto mb-2 text-slate-300"></i>
                                    No products added yet. Select a product above to add to this sale.
                                </div>
                            </div>
                        </div>

                        <!-- Order Notes -->
                        <div class="pt-2">
                            <label class="text-[11px] font-bold uppercase text-slate-600 block mb-1">Customer / Special Dispatch Notes (Optional)</label>
                            <input type="text" id="coOrderNotes" placeholder="e.g. Call before delivery, handle fragile perfume glass..." class="w-full border border-slate-300 px-3 py-2 rounded-xl text-[12px] font-medium focus:border-emerald-600 outline-none bg-white text-slate-800">
                        </div>
                    </div>
                </div>

                <!-- RIGHT: Payment, Logistics & Final Billing Receipt (5 cols) -->
                <div class="lg:col-span-5 space-y-6">
                    <!-- Payment & Courier Options -->
                    <div class="bg-white/90 border border-slate-200 p-6 rounded-2xl shadow-xs space-y-4">
                        <h3 class="text-[13px] font-bold text-[#0f172a] uppercase border-b border-slate-100 pb-3 flex items-center gap-2">
                            <i data-lucide="credit-card" class="w-4 h-4 text-emerald-600"></i> Step 3: Payment & Shipping
                        </h3>

                        <!-- Payment Method -->
                        <div class="space-y-1.5">
                            <label class="text-[11px] font-bold uppercase text-slate-600 block">Payment Method</label>
                            <select id="coPaymentMethod" class="w-full border border-slate-300 p-2.5 rounded-xl text-[12.5px] font-bold focus:border-emerald-600 outline-none bg-white text-slate-800">
                                <option value="Cash on Delivery">💵 Cash on Delivery (COD)</option>
                                <option value="SSLCommerz Gateway">💳 SSLCommerz (Cards/NetBanking/MFS)</option>
                                <option value="bKash Merchant">📱 bKash Merchant Checkout</option>
                                <option value="EPS Electronic Payment">🏦 EPS (Electronic Payment Service)</option>
                                <option value="Cash at Store Counter">🏪 Cash at Store Counter / POS</option>
                            </select>
                        </div>

                        <!-- Courier Partner -->
                        <div class="space-y-1.5">
                            <label class="text-[11px] font-bold uppercase text-slate-600 block">Courier / Dispatch Partner</label>
                            <select id="coCourierPartner" class="w-full border border-slate-300 p-2.5 rounded-xl text-[12.5px] font-bold focus:border-emerald-600 outline-none bg-white text-slate-800">
                                <option value="Pathao Courier">🚚 Pathao Courier API</option>
                                <option value="Steadfast Courier">📦 Steadfast Courier</option>
                                <option value="RedX Logistics">🚛 RedX Logistics</option>
                                <option value="Paperfly">📫 Paperfly Delivery</option>
                                <option value="In-Store Pickup">🛍️ In-Store Walk-in Pickup</option>
                            </select>
                        </div>

                        <!-- Delivery Location / Fee -->
                        <div class="grid grid-cols-2 gap-3">
                            <div>
                                <label class="text-[11px] font-bold uppercase text-slate-600 block mb-1">Delivery Region</label>
                                <select id="coDeliveryChargeSelect" onchange="handleDeliveryChargeChange(this.value)" class="w-full border border-slate-300 p-2 rounded-xl text-[12px] font-semibold focus:border-emerald-600 outline-none bg-white text-slate-800">
                                    <option value="60">Inside Dhaka (৳60)</option>
                                    <option value="120">Outside Dhaka (৳120)</option>
                                    <option value="0">Store Pickup / Free (৳0)</option>
                                </select>
                            </div>
                            <div>
                                <label class="text-[11px] font-bold uppercase text-slate-600 block mb-1">Special Discount (৳)</label>
                                <input type="number" id="coDiscountInput" value="0" min="0" oninput="renderCartUI()" placeholder="0" class="w-full border border-slate-300 px-3 py-2 rounded-xl text-[12px] font-mono font-bold focus:border-emerald-600 outline-none bg-white text-slate-800">
                            </div>
                        </div>
                    </div>

                    <!-- Billing Calculation Summary Card -->
                    <div class="bg-gradient-to-br from-slate-900 to-slate-800 text-white p-6 rounded-2xl shadow-xl space-y-4 border border-slate-700">
                        <div class="flex justify-between items-center border-b border-slate-700 pb-3">
                            <span class="text-[12px] font-bold uppercase tracking-wider text-emerald-400 flex items-center gap-1.5">
                                <i data-lucide="receipt" class="w-4 h-4"></i> Sale Bill Summary
                            </span>
                            <span class="text-[11px] text-slate-400 font-mono" id="posBillTime">08 Aug 2026</span>
                        </div>

                        <div class="space-y-2.5 text-[13px]">
                            <div class="flex justify-between text-slate-300">
                                <span>Cart Subtotal:</span>
                                <span class="font-mono font-bold text-white" id="coSubtotalDisplay">৳0 BDT</span>
                            </div>
                            <div class="flex justify-between text-slate-300">
                                <span>Shipping / Delivery:</span>
                                <span class="font-mono font-bold text-emerald-300" id="coShippingDisplay">+ ৳60 BDT</span>
                            </div>
                            <div class="flex justify-between text-slate-300">
                                <span>Discount:</span>
                                <span class="font-mono font-bold text-rose-300" id="coDiscountDisplay">- ৳0 BDT</span>
                            </div>
                            <div class="pt-3 border-t border-slate-700 flex justify-between items-baseline">
                                <span class="text-[13px] font-bold uppercase tracking-wider text-slate-200">TOTAL PAYABLE:</span>
                                <span class="text-[26px] font-serif font-bold text-emerald-400 font-mono" id="coTotalBillDisplay">৳60 BDT</span>
                            </div>
                        </div>

                        <div class="pt-2 space-y-2">
                            <button type="submit" id="posSubmitSaleBtn" class="w-full bg-emerald-500 hover:bg-emerald-400 text-slate-950 py-3.5 rounded-xl font-bold uppercase tracking-wider text-[13px] shadow-lg shadow-emerald-500/25 transition-all flex items-center justify-center gap-2 cursor-pointer active:scale-98">
                                <i data-lucide="check-circle" class="w-4 h-4"></i> Complete Sale & Generate Order
                            </button>
                            <button type="button" onclick="printDraftInvoice()" class="w-full bg-slate-800 hover:bg-slate-700 text-slate-300 hover:text-white py-2 rounded-xl text-[11px] font-bold uppercase tracking-wider transition-colors flex items-center justify-center gap-1.5 cursor-pointer border border-slate-700">
                                <i data-lucide="printer" class="w-3.5 h-3.5"></i> Print POS Receipt Draft
                            </button>
                        </div>
                    </div>
                </div>
            </form>
        </div>

        <!-- SECTION: SITE SETTING -->
        <div id="section-settings" class="section-content hidden space-y-6">
            <div class="bg-white/90 border p-6 rounded-2xl space-y-6">
                <div class="border-b pb-4">
                    <h2 class="text-[18px] font-serif font-bold text-[#0f172a] uppercase flex items-center gap-2">
                        <i data-lucide="settings" class="w-5 h-5 text-[#0284c7]"></i> Global Site Settings
                    </h2>
                    <p class="text-[12px] text-[#64748b] mt-1">Configure your storefront's core information, contact details, and display content.</p>
                </div>

                <form data-settings-form onsubmit="handleSettingsSave(event)" class="space-y-8">
                    <!-- General Settings -->
                    <div class="space-y-4">
                        <h3 class="text-[13px] font-bold text-[#0f172a] uppercase border-l-4 border-[#0284c7] pl-2">General Information</h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="text-[11px] font-bold uppercase text-[#475569] block mb-1">Company Name</label>
                                <input type="text" name="siteName" value="{{ $siteSettings['siteName'] ?? $siteSettings['site_name'] ?? 'REXXO BD' }}" placeholder="Company Name" class="w-full border border-[#cbd5e1] px-3 py-2.5 rounded-lg text-[13px] font-bold focus:border-[#0284c7] outline-none bg-white text-[#0f172a]">
                            </div>
                            <div>
                                <label class="text-[11px] font-bold uppercase text-[#475569] block mb-1">Tagline</label>
                                <input type="text" name="tagline" value="{{ $siteSettings['tagline'] ?? 'Fine Fragrance & Luxury Extraits' }}" placeholder="Luxury Brand Tagline" class="w-full border border-[#cbd5e1] px-3 py-2.5 rounded-lg text-[13px] font-bold focus:border-[#0284c7] outline-none bg-white text-[#0f172a]">
                            </div>
                        </div>
                    </div>

                    <!-- Admin Panel Appearance Theme (Default, Light, Night) -->
                    <div class="space-y-4">
                        <h3 class="text-[13px] font-bold text-[#0f172a] uppercase border-l-4 border-[#0284c7] pl-2">Admin Dashboard Theme Appearance</h3>
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 bg-[#f8fafc] p-4 rounded-xl border border-[#e2e8f0]">
                            <label class="relative flex flex-col p-4 bg-white border-2 rounded-xl cursor-pointer hover:border-[#0284c7] transition-all has-[:checked]:border-[#0284c7] has-[:checked]:bg-sky-50/40">
                                <div class="flex items-center justify-between mb-2">
                                    <span class="font-bold text-[13px] text-[#0f172a]">Sky Executive (Default)</span>
                                    <input type="radio" name="admin_theme" value="default" onchange="setAdminTheme('default')" {{ ($siteSettings['admin_theme'] ?? 'default') === 'default' ? 'checked' : '' }} class="w-4 h-4 text-[#0284c7]">
                                </div>
                                <p class="text-[11px] text-[#64748b]">Signature executive sky-blue luxury gradient backdrop for high productivity.</p>
                            </label>

                            <label class="relative flex flex-col p-4 bg-white border-2 rounded-xl cursor-pointer hover:border-[#0284c7] transition-all has-[:checked]:border-[#0284c7] has-[:checked]:bg-sky-50/40">
                                <div class="flex items-center justify-between mb-2">
                                    <span class="font-bold text-[13px] text-[#0f172a]">Clean Light Mode</span>
                                    <input type="radio" name="admin_theme" value="light" onchange="setAdminTheme('light')" {{ ($siteSettings['admin_theme'] ?? '') === 'light' ? 'checked' : '' }} class="w-4 h-4 text-[#0284c7]">
                                </div>
                                <p class="text-[11px] text-[#64748b]">Crisp white and soft slate modern clean aesthetic for daylight operations.</p>
                            </label>

                            <label class="relative flex flex-col p-4 bg-white border-2 rounded-xl cursor-pointer hover:border-[#0284c7] transition-all has-[:checked]:border-[#0284c7] has-[:checked]:bg-sky-50/40">
                                <div class="flex items-center justify-between mb-2">
                                    <span class="font-bold text-[13px] text-[#0f172a]">Obsidian Night Mode</span>
                                    <input type="radio" name="admin_theme" value="night" onchange="setAdminTheme('night')" {{ ($siteSettings['admin_theme'] ?? '') === 'night' ? 'checked' : '' }} class="w-4 h-4 text-[#0284c7]">
                                </div>
                                <p class="text-[11px] text-[#64748b]">Ultra-sleek dark theme designed for late hours and OLED contrast.</p>
                            </label>
                        </div>
                    </div>

                    <!-- Brand Assets (Logo, Favicon & Brand Identity) -->
                    <div class="space-y-4">
                        <h3 class="text-[13px] font-bold text-[#0f172a] uppercase border-l-4 border-[#0284c7] pl-2">Brand Assets (Logo & Favicon)</h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 bg-[#f8fafc] p-4 rounded-xl border border-[#e2e8f0]">
                            <!-- Site Logo Card -->
                            <div class="space-y-3 bg-white p-4 rounded-xl border border-[#cbd5e1]/60 shadow-xs">
                                <div class="flex items-center justify-between">
                                    <label class="text-[11px] font-bold uppercase text-[#0f172a] block">Site Logo (Storefront & Admin)</label>
                                    <span class="text-[10px] text-[#64748b] font-mono">PNG, SVG, WEBP, JPG</span>
                                </div>
                                <div class="flex items-center gap-4">
                                    <div class="w-20 h-16 bg-[#f1f5f9] border border-dashed border-[#94a3b8] rounded-lg flex items-center justify-center overflow-hidden shrink-0">
                                        <img id="previewLogoImg" src="{{ $siteSettings['logo_url'] ?? $siteSettings['site_logo'] ?? '' }}" alt="Logo Preview" class="max-h-14 max-w-[70px] object-contain {{ empty($siteSettings['logo_url']) && empty($siteSettings['site_logo']) ? 'hidden' : '' }}" />
                                        <i id="previewLogoPlaceholder" data-lucide="image" class="w-6 h-6 text-[#94a3b8] {{ !empty($siteSettings['logo_url']) || !empty($siteSettings['site_logo']) ? 'hidden' : '' }}"></i>
                                    </div>
                                    <div class="space-y-2 flex-1">
                                        <input type="file" name="logo_file" accept="image/png, image/svg+xml, image/jpeg, image/webp" onchange="previewSelectedFile(event, 'previewLogoImg', 'previewLogoPlaceholder')" class="w-full text-[12px] font-medium text-[#475569] file:mr-3 file:py-1.5 file:px-3 file:rounded-lg file:border-0 file:text-[11px] file:font-bold file:bg-[#e0f2fe] file:text-[#0284c7] hover:file:bg-[#bae6fd] cursor-pointer">
                                        <input type="text" name="logo_url" value="{{ $siteSettings['logo_url'] ?? $siteSettings['site_logo'] ?? '' }}" placeholder="Or paste Logo Image URL (e.g. /uploads/...)" class="w-full border border-[#cbd5e1] px-2.5 py-1.5 rounded-lg text-[11px] font-mono focus:border-[#0284c7] outline-none bg-white text-[#0f172a]" oninput="previewUrlInput(this.value, 'previewLogoImg', 'previewLogoPlaceholder')">
                                    </div>
                                </div>
                            </div>

                            <!-- Site Favicon Card -->
                            <div class="space-y-3 bg-white p-4 rounded-xl border border-[#cbd5e1]/60 shadow-xs">
                                <div class="flex items-center justify-between">
                                    <label class="text-[11px] font-bold uppercase text-[#0f172a] block">Site Favicon (Browser Tab Icon)</label>
                                    <span class="text-[10px] text-[#64748b] font-mono">ICO, PNG, SVG</span>
                                </div>
                                <div class="flex items-center gap-4">
                                    <div class="w-20 h-16 bg-[#f1f5f9] border border-dashed border-[#94a3b8] rounded-lg flex items-center justify-center overflow-hidden shrink-0">
                                        <img id="previewFaviconImg" src="{{ $adminFavicon }}" alt="Favicon Preview" class="w-8 h-8 object-contain" onerror="this.src='/uploads/settings/favicon_1785930191.ico'" />
                                    </div>
                                    <div class="space-y-2 flex-1">
                                        <input type="file" name="favicon_file" accept="image/png, image/x-icon, image/svg+xml, image/jpeg, image/webp" onchange="previewSelectedFile(event, 'previewFaviconImg')" class="w-full text-[12px] font-medium text-[#475569] file:mr-3 file:py-1.5 file:px-3 file:rounded-lg file:border-0 file:text-[11px] file:font-bold file:bg-[#e0f2fe] file:text-[#0284c7] hover:file:bg-[#bae6fd] cursor-pointer">
                                        <input type="text" name="favicon_url" value="{{ $siteSettings['favicon_url'] ?? $siteSettings['site_favicon'] ?? '' }}" placeholder="Or paste Favicon URL (e.g. /favicon.ico)" class="w-full border border-[#cbd5e1] px-2.5 py-1.5 rounded-lg text-[11px] font-mono focus:border-[#0284c7] outline-none bg-white text-[#0f172a]" oninput="previewUrlInput(this.value, 'previewFaviconImg')">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Business Settings -->
                    <div class="space-y-4">
                        <h3 class="text-[13px] font-bold text-[#0f172a] uppercase border-l-4 border-[#0284c7] pl-2">Business Settings</h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="text-[11px] font-bold uppercase text-[#475569] block mb-1">Primary Currency</label>
                                <select name="currency" class="w-full border border-[#cbd5e1] px-3 py-2.5 rounded-lg text-[13px] font-bold focus:border-[#0284c7] outline-none bg-white text-[#0f172a]">
                                    <option value="BDT (৳)" {{ ($siteSettings['currency'] ?? 'BDT (৳)') === 'BDT (৳)' ? 'selected' : '' }}>BDT (৳) - Bangladeshi Taka</option>
                                    <option value="USD ($)" {{ ($siteSettings['currency'] ?? '') === 'USD ($)' ? 'selected' : '' }}>USD ($) - US Dollar</option>
                                    <option value="EUR (€)" {{ ($siteSettings['currency'] ?? '') === 'EUR (€)' ? 'selected' : '' }}>EUR (€) - Euro</option>
                                </select>
                            </div>
                            <div>
                                <label class="text-[11px] font-bold uppercase text-[#475569] block mb-1">Default Tax Rate (%)</label>
                                <input type="number" name="tax_rate" value="{{ $siteSettings['tax_rate'] ?? '0' }}" class="w-full border border-[#cbd5e1] px-3 py-2.5 rounded-lg text-[13px] font-bold focus:border-[#0284c7] outline-none bg-white text-[#0f172a]">
                            </div>
                        </div>
                    </div>

                    <!-- Social Media Links -->
                    <div class="space-y-4">
                        <h3 class="text-[13px] font-bold text-[#0f172a] uppercase border-l-4 border-[#0284c7] pl-2">Social Media Links (Footer & Storefront)</h3>
                        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
                            <div>
                                <label class="text-[11px] font-bold uppercase text-[#475569] block mb-1">Facebook URL</label>
                                <input type="url" name="facebook_url" value="{{ $siteSettings['facebook_url'] ?? '' }}" placeholder="https://facebook.com/rexxobd" class="w-full border border-[#cbd5e1] px-3 py-2.5 rounded-lg text-[13px] font-bold focus:border-[#0284c7] outline-none bg-white text-[#0f172a]">
                            </div>
                            <div>
                                <label class="text-[11px] font-bold uppercase text-[#475569] block mb-1">Instagram URL</label>
                                <input type="url" name="instagram_url" value="{{ $siteSettings['instagram_url'] ?? '' }}" placeholder="https://instagram.com/rexxobd" class="w-full border border-[#cbd5e1] px-3 py-2.5 rounded-lg text-[13px] font-bold focus:border-[#0284c7] outline-none bg-white text-[#0f172a]">
                            </div>
                            <div>
                                <label class="text-[11px] font-bold uppercase text-[#475569] block mb-1">TikTok URL</label>
                                <input type="url" name="tiktok_url" value="{{ $siteSettings['tiktok_url'] ?? '' }}" placeholder="https://tiktok.com/@rexxobd" class="w-full border border-[#cbd5e1] px-3 py-2.5 rounded-lg text-[13px] font-bold focus:border-[#0284c7] outline-none bg-white text-[#0f172a]">
                            </div>
                            <div>
                                <label class="text-[11px] font-bold uppercase text-[#475569] block mb-1">YouTube URL</label>
                                <input type="url" name="youtube_url" value="{{ $siteSettings['youtube_url'] ?? '' }}" placeholder="https://youtube.com/@rexxobd" class="w-full border border-[#cbd5e1] px-3 py-2.5 rounded-lg text-[13px] font-bold focus:border-[#0284c7] outline-none bg-white text-[#0f172a]">
                            </div>
                            <div>
                                <label class="text-[11px] font-bold uppercase text-[#475569] block mb-1">X / Twitter URL</label>
                                <input type="url" name="twitter_url" value="{{ $siteSettings['twitter_url'] ?? $siteSettings['x_url'] ?? '' }}" placeholder="https://twitter.com/rexxobd" class="w-full border border-[#cbd5e1] px-3 py-2.5 rounded-lg text-[13px] font-bold focus:border-[#0284c7] outline-none bg-white text-[#0f172a]">
                            </div>
                        </div>
                    </div>
                    <!-- Contact Details -->
                    <div class="space-y-4">
                        <h3 class="text-[13px] font-bold text-[#0f172a] uppercase border-l-4 border-[#0284c7] pl-2">Contact Details</h3>
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                            <div>
                                <label class="text-[11px] font-bold uppercase text-[#475569] block mb-1">Phone Number</label>
                                <input type="text" name="phone" value="{{ $siteSettings['phone'] ?? '' }}" placeholder="+880 1700-000000" class="w-full border border-[#cbd5e1] px-3 py-2.5 rounded-lg text-[13px] font-bold focus:border-[#0284c7] outline-none bg-white text-[#0f172a]">
                            </div>
                            <div>
                                <label class="text-[11px] font-bold uppercase text-[#475569] block mb-1">WhatsApp Number</label>
                                <input type="text" name="whatsapp" value="{{ $siteSettings['whatsapp'] ?? '' }}" placeholder="8801700000000" class="w-full border border-[#cbd5e1] px-3 py-2.5 rounded-lg text-[13px] font-bold focus:border-[#0284c7] outline-none bg-white text-[#0f172a]">
                            </div>
                            <div>
                                <label class="text-[11px] font-bold uppercase text-[#475569] block mb-1">Support Email</label>
                                <input type="email" name="email" value="{{ $siteSettings['email'] ?? '' }}" placeholder="support@rexxo.com" class="w-full border border-[#cbd5e1] px-3 py-2.5 rounded-lg text-[13px] font-bold focus:border-[#0284c7] outline-none bg-white text-[#0f172a]">
                            </div>
                        </div>
                    </div>

                    <!-- Hero Background Video & Campaign Media -->
                    <div class="space-y-4">
                        <div class="flex items-center justify-between">
                            <h3 class="text-[13px] font-bold text-[#0f172a] uppercase border-l-4 border-[#0284c7] pl-2 flex items-center gap-2">
                                <i data-lucide="video" class="w-4 h-4 text-[#0284c7]"></i> Hero Background Video & Campaign Media
                            </h3>
                            <span class="text-[11px] bg-sky-50 text-[#0284c7] px-2.5 py-1 rounded-full font-bold uppercase tracking-wider border border-sky-200">Storefront Background Clip</span>
                        </div>

                        <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 bg-[#f8fafc] p-5 rounded-2xl border border-[#e2e8f0]">
                            <!-- Left: Live Video Player Preview -->
                            <div class="lg:col-span-5 space-y-3">
                                <label class="text-[11px] font-bold uppercase text-[#0f172a] block">Live Video Clip Preview</label>
                                <div class="relative aspect-video rounded-xl bg-[#0A0A0A] border border-[#cbd5e1] overflow-hidden shadow-sm flex items-center justify-center group">
                                    <video id="previewHeroVideo" src="{{ $siteSettings['hero_video_url'] ?? $siteSettings['hero_video'] ?? 'https://assets.mixkit.co/videos/preview/mixkit-perfume-bottle-in-a-dark-environment-42525-large.mp4' }}" autoplay muted loop playsinline class="w-full h-full object-cover"></video>
                                    <div class="absolute top-2.5 left-2.5 bg-black/75 backdrop-blur-xs text-white text-[10px] font-mono uppercase px-2 py-0.5 rounded border border-white/20 flex items-center gap-1.5 shadow-xs">
                                        <span class="w-2 h-2 rounded-full bg-emerald-400 animate-pulse"></span>
                                        ACTIVE CLIP
                                    </div>
                                    <button type="button" onclick="const v = document.getElementById('previewHeroVideo'); v.paused ? v.play() : v.pause();" class="absolute inset-0 flex items-center justify-center bg-black/40 opacity-0 group-hover:opacity-100 transition-opacity text-white text-[12px] font-bold uppercase tracking-wider gap-2">
                                        <i data-lucide="play" class="w-5 h-5"></i> Toggle Playback
                                    </button>
                                </div>
                                <p class="text-[10px] text-[#64748b] leading-tight">This video clip runs continuously in the hero background across the homepage storefront.</p>
                            </div>

                            <!-- Right: Video File Upload & URL Inputs -->
                            <div class="lg:col-span-7 space-y-4">
                                <!-- Upload Video File -->
                                <div class="bg-white p-4 rounded-xl border border-[#cbd5e1]/60 shadow-xs space-y-2">
                                    <div class="flex items-center justify-between">
                                        <label class="text-[11px] font-bold uppercase text-[#0f172a] block">1. Upload Video File (.mp4, .webm, .mov)</label>
                                        <span class="text-[10px] text-[#64748b] font-mono">Max 50MB</span>
                                    </div>
                                    <input type="file" name="hero_video_file" accept="video/mp4, video/webm, video/quicktime, video/ogg" onchange="previewSelectedVideo(event)" class="w-full text-[12px] font-medium text-[#475569] file:mr-3 file:py-1.5 file:px-3 file:rounded-lg file:border-0 file:text-[11px] file:font-bold file:bg-[#e0f2fe] file:text-[#0284c7] hover:file:bg-[#bae6fd] cursor-pointer">
                                </div>

                                <!-- Direct Video URL Input -->
                                <div class="bg-white p-4 rounded-xl border border-[#cbd5e1]/60 shadow-xs space-y-2">
                                    <label class="text-[11px] font-bold uppercase text-[#0f172a] block">2. Or Paste Direct Video URL (.mp4)</label>
                                    <input type="text" name="hero_video_url" id="heroVideoUrlInput" placeholder="e.g. https://assets.mixkit.co/videos/preview/...mp4" class="w-full border border-[#cbd5e1] px-3 py-2 rounded-lg text-[11px] font-mono focus:border-[#0284c7] outline-none bg-white text-[#0f172a]" oninput="previewVideoUrl(this.value)">
                                    
                                    <!-- Quick Presets -->
                                    <div class="flex items-center gap-1.5 pt-1 overflow-x-auto">
                                        <span class="text-[10px] font-bold uppercase text-[#64748b]">Presets:</span>
                                        <button type="button" onclick="applyVideoPreset('https://assets.mixkit.co/videos/preview/mixkit-perfume-bottle-in-a-dark-environment-42525-large.mp4')" class="text-[10px] bg-[#f1f5f9] hover:bg-[#e0f2fe] text-[#0f172a] hover:text-[#0284c7] px-2 py-0.5 rounded border border-[#cbd5e1] transition-colors cursor-pointer whitespace-nowrap">Dark Extrait</button>
                                        <button type="button" onclick="applyVideoPreset('https://commondatastorage.googleapis.com/gtv-videos-bucket/sample/TearsOfSteel.mp4')" class="text-[10px] bg-[#f1f5f9] hover:bg-[#e0f2fe] text-[#0f172a] hover:text-[#0284c7] px-2 py-0.5 rounded border border-[#cbd5e1] transition-colors cursor-pointer whitespace-nowrap">Atmospheric</button>
                                    </div>
                                </div>

                                <!-- Video Poster / Fallback Image -->
                                <div class="bg-white p-4 rounded-xl border border-[#cbd5e1]/60 shadow-xs space-y-2">
                                    <label class="text-[11px] font-bold uppercase text-[#0f172a] block">3. Video Thumbnail / Poster Image URL</label>
                                    <input type="text" name="hero_poster_url" placeholder="https://images.unsplash.com/photo-1594035910387-fea47794261f..." class="w-full border border-[#cbd5e1] px-3 py-2 rounded-lg text-[11px] font-mono focus:border-[#0284c7] outline-none bg-white text-[#0f172a]">
                                </div>
                            </div>
                        </div>

                        <!-- Hero Campaign Content & CTAs -->
                        <div class="bg-white p-5 rounded-2xl border border-[#e2e8f0] space-y-4">
                            <h4 class="text-[12px] font-bold uppercase text-[#0f172a] tracking-wider">Hero Overlay Typography & Action Links</h4>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label class="text-[11px] font-bold uppercase text-[#475569] block mb-1">Campaign Subtitle (Top Small Tag)</label>
                                    <input type="text" name="hero_subtitle" placeholder="e.g. NEW COLLECTION" class="w-full border border-[#cbd5e1] px-3 py-2.5 rounded-lg text-[13px] font-bold focus:border-[#0284c7] outline-none bg-white text-[#0f172a]">
                                </div>
                                <div>
                                    <label class="text-[11px] font-bold uppercase text-[#475569] block mb-1">Campaign Main Title</label>
                                    <input type="text" name="hero_title" placeholder="e.g. Fall Winter 2026" class="w-full border border-[#cbd5e1] px-3 py-2.5 rounded-lg text-[13px] font-bold focus:border-[#0284c7] outline-none bg-white text-[#0f172a]">
                                </div>
                                <div>
                                    <label class="text-[11px] font-bold uppercase text-[#475569] block mb-1">Primary CTA Button Text & URL</label>
                                    <div class="grid grid-cols-2 gap-2">
                                        <input type="text" name="hero_link_1_text" placeholder="FOR HER" class="border border-[#cbd5e1] px-3 py-2 rounded-lg text-[12px] font-bold focus:border-[#0284c7] outline-none bg-white text-[#0f172a]">
                                        <input type="text" name="hero_link_1_url" placeholder="/perfumes?gender=women" class="border border-[#cbd5e1] px-3 py-2 rounded-lg text-[12px] font-mono focus:border-[#0284c7] outline-none bg-white text-[#0f172a]">
                                    </div>
                                </div>
                                <div>
                                    <label class="text-[11px] font-bold uppercase text-[#475569] block mb-1">Secondary CTA Button Text & URL</label>
                                    <div class="grid grid-cols-2 gap-2">
                                        <input type="text" name="hero_link_2_text" placeholder="FOR HIM" class="border border-[#cbd5e1] px-3 py-2 rounded-lg text-[12px] font-bold focus:border-[#0284c7] outline-none bg-white text-[#0f172a]">
                                        <input type="text" name="hero_link_2_url" placeholder="/perfumes?gender=men" class="border border-[#cbd5e1] px-3 py-2 rounded-lg text-[12px] font-mono focus:border-[#0284c7] outline-none bg-white text-[#0f172a]">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Storefront Content (Announcement & Footer) -->
                    <div class="space-y-4">
                        <h3 class="text-[13px] font-bold text-[#0f172a] uppercase border-l-4 border-[#0284c7] pl-2">Header & Footer Global Text</h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="text-[11px] font-bold uppercase text-[#475569] block mb-1">Top Announcement Bar</label>
                                <input type="text" name="announcement" value="{{ $siteSettings['announcement'] ?? '' }}" placeholder="COMPLIMENTARY EXPRESS DELIVERY & LUXURY SAMPLER WITH ALL ORDERS" class="w-full border border-[#cbd5e1] px-3 py-2.5 rounded-lg text-[13px] font-bold focus:border-[#0284c7] outline-none bg-white text-[#0f172a]">
                            </div>
                            <div>
                                <label class="text-[11px] font-bold uppercase text-[#475569] block mb-1">Footer Text</label>
                                <input type="text" name="footerText" value="{{ $siteSettings['footerText'] ?? $siteSettings['footer_text'] ?? '' }}" placeholder="&copy; 2026 REXXO BD. ALL RIGHTS RESERVED." class="w-full border border-[#cbd5e1] px-3 py-2.5 rounded-lg text-[13px] font-bold focus:border-[#0284c7] outline-none bg-white text-[#0f172a]">
                            </div>
                        </div>
                    </div>

                    <!-- Save Button -->
                    <div class="pt-4 border-t border-[#e2e8f0]">
                        <button type="submit" class="bg-[#0f172a] hover:bg-[#B8712E] text-white px-8 py-3 rounded-xl text-[13px] font-bold uppercase tracking-wider transition-all flex items-center justify-center gap-2 shadow-sm cursor-pointer">
                            <i data-lucide="save" class="w-4 h-4"></i> Save Settings
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- ================================================================= -->
        <!-- SECTION: API SETTINGS (SMS, Courier, Meta Pixel, Google, etc.) -->
        <!-- ================================================================= -->
        <div id="section-api_settings" class="section-content hidden space-y-6 animate-fade-in">

            <!-- Section Header -->
            <div class="bg-white/90 border border-[#38bdf8]/30 p-6 rounded-2xl shadow-sm">
                <div class="flex items-center justify-between flex-wrap gap-4">
                    <div>
                        <h2 class="text-[18px] font-serif font-bold text-[#0f172a] uppercase flex items-center gap-2">
                            <i data-lucide="plug" class="w-5 h-5 text-[#0284c7]"></i> API Integration Settings
                        </h2>
                        <p class="text-[12px] text-[#64748b] mt-1">Configure SMS gateways, courier APIs, and marketing pixel integrations. All credentials are encrypted and stored securely.</p>
                    </div>
                    <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg text-[11px] font-bold font-mono bg-amber-50 text-amber-700 border border-amber-200">
                        <i data-lucide="shield-check" class="w-3.5 h-3.5"></i> Credentials Encrypted
                    </span>
                </div>
            </div>

            <!-- API Tabs Navigation -->
            <div class="flex items-center gap-2 overflow-x-auto bg-white/90 border border-[#e2e8f0] rounded-xl p-1.5 shadow-xs">
                <button type="button" onclick="switchApiTab('sms')" id="api-tab-sms" class="px-4 py-2 text-[12px] font-bold uppercase tracking-wider flex items-center gap-2 rounded-lg transition-all cursor-pointer whitespace-nowrap bg-[#0284c7] text-white shadow-xs">
                    <i data-lucide="message-square" class="w-3.5 h-3.5"></i> SMS Gateway
                </button>
                <button type="button" onclick="switchApiTab('courier_api')" id="api-tab-courier_api" class="px-4 py-2 text-[12px] font-bold uppercase tracking-wider flex items-center gap-2 rounded-lg transition-all cursor-pointer whitespace-nowrap text-[#475569] hover:bg-[#f1f5f9]">
                    <i data-lucide="truck" class="w-3.5 h-3.5"></i> Courier API
                </button>
                <button type="button" onclick="switchApiTab('meta')" id="api-tab-meta" class="px-4 py-2 text-[12px] font-bold uppercase tracking-wider flex items-center gap-2 rounded-lg transition-all cursor-pointer whitespace-nowrap text-[#475569] hover:bg-[#f1f5f9]">
                    <i data-lucide="facebook" class="w-3.5 h-3.5"></i> Meta / Facebook
                </button>
                <button type="button" onclick="switchApiTab('google')" id="api-tab-google" class="px-4 py-2 text-[12px] font-bold uppercase tracking-wider flex items-center gap-2 rounded-lg transition-all cursor-pointer whitespace-nowrap text-[#475569] hover:bg-[#f1f5f9]">
                    <i data-lucide="bar-chart-2" class="w-3.5 h-3.5"></i> Google Analytics
                </button>
            </div>

            <!-- ============================= -->
            <!-- TAB: SMS GATEWAY -->
            <!-- ============================= -->
            <div id="api-panel-sms" class="api-tab-panel space-y-6">

                <!-- SMS Notifications Config -->
                <div class="bg-white/90 border p-6 rounded-2xl shadow-sm space-y-6">
                    <div class="border-b pb-4 flex items-center justify-between">
                        <div>
                            <h3 class="text-[16px] font-serif font-bold text-[#0f172a] uppercase flex items-center gap-2">
                                <i data-lucide="message-square" class="w-5 h-5 text-[#0284c7]"></i> SMS Notification Settings
                            </h3>
                            <p class="text-[12px] text-[#64748b] mt-1">Automatically send SMS to customers on order placement, dispatch & delivery confirmation.</p>
                        </div>
                    </div>

                    <!-- SMS Events Config -->
                    <div class="space-y-3">
                        <h4 class="text-[12px] font-bold uppercase text-[#0f172a] border-l-4 border-[#0284c7] pl-2">Trigger Events — When to Send SMS</h4>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-3 bg-[#f8fafc] p-4 rounded-xl border">
                            <label class="flex items-center gap-3 p-3 bg-white rounded-xl border cursor-pointer hover:border-[#0284c7] transition-all">
                                <input type="checkbox" name="sms_on_new_order" id="sms_on_new_order" value="1" class="w-4 h-4 text-[#0284c7] accent-[#0284c7]" {{ ($siteSettings['sms_on_new_order'] ?? '0') === '1' ? 'checked' : '' }}>
                                <div>
                                    <span class="text-[12px] font-bold text-[#0f172a] block">New Order Placed</span>
                                    <span class="text-[11px] text-[#64748b]">Send SMS to customer when order is confirmed</span>
                                </div>
                            </label>
                            <label class="flex items-center gap-3 p-3 bg-white rounded-xl border cursor-pointer hover:border-[#0284c7] transition-all">
                                <input type="checkbox" name="sms_on_dispatch" id="sms_on_dispatch" value="1" class="w-4 h-4 text-[#0284c7] accent-[#0284c7]" {{ ($siteSettings['sms_on_dispatch'] ?? '0') === '1' ? 'checked' : '' }}>
                                <div>
                                    <span class="text-[12px] font-bold text-[#0f172a] block">Order Dispatched / Shipped</span>
                                    <span class="text-[11px] text-[#64748b]">Send SMS with courier tracking info</span>
                                </div>
                            </label>
                            <label class="flex items-center gap-3 p-3 bg-white rounded-xl border cursor-pointer hover:border-[#0284c7] transition-all">
                                <input type="checkbox" name="sms_on_delivered" id="sms_on_delivered" value="1" class="w-4 h-4 text-[#0284c7] accent-[#0284c7]" {{ ($siteSettings['sms_on_delivered'] ?? '0') === '1' ? 'checked' : '' }}>
                                <div>
                                    <span class="text-[12px] font-bold text-[#0f172a] block">Order Delivered</span>
                                    <span class="text-[11px] text-[#64748b]">Send SMS when order is delivered successfully</span>
                                </div>
                            </label>
                            <label class="flex items-center gap-3 p-3 bg-white rounded-xl border cursor-pointer hover:border-[#0284c7] transition-all">
                                <input type="checkbox" name="sms_on_cancelled" id="sms_on_cancelled" value="1" class="w-4 h-4 text-[#0284c7] accent-[#0284c7]" {{ ($siteSettings['sms_on_cancelled'] ?? '0') === '1' ? 'checked' : '' }}>
                                <div>
                                    <span class="text-[12px] font-bold text-[#0f172a] block">Order Cancelled</span>
                                    <span class="text-[11px] text-[#64748b]">Notify customer when order is cancelled</span>
                                </div>
                            </label>
                        </div>
                    </div>

                    <!-- SMS Templates -->
                    <div class="space-y-3">
                        <h4 class="text-[12px] font-bold uppercase text-[#0f172a] border-l-4 border-[#0284c7] pl-2">SMS Message Templates</h4>
                        <div class="space-y-3">
                            <div>
                                <label class="text-[11px] font-bold uppercase text-[#475569] block mb-1">Order Confirmation Template</label>
                                <textarea name="sms_template_new_order" rows="2" placeholder="e.g. Dear {name}, your order #{order_id} of ৳{amount} BDT has been confirmed! We'll notify you when it ships. — {company}" class="w-full border border-[#cbd5e1] px-3 py-2.5 rounded-lg text-[12px] font-mono focus:border-[#0284c7] outline-none bg-white text-[#0f172a] resize-none">{{ $siteSettings['sms_template_new_order'] ?? '' }}</textarea>
                                <p class="text-[10px] text-[#94a3b8] mt-1">Variables: {name} {order_id} {amount} {company} {tracking_id}</p>
                            </div>
                            <div>
                                <label class="text-[11px] font-bold uppercase text-[#475569] block mb-1">Dispatch / Shipment Template</label>
                                <textarea name="sms_template_dispatch" rows="2" placeholder="e.g. Dear {name}, order #{order_id} has been dispatched via {courier}. Track: {tracking_id} — {company}" class="w-full border border-[#cbd5e1] px-3 py-2.5 rounded-lg text-[12px] font-mono focus:border-[#0284c7] outline-none bg-white text-[#0f172a] resize-none">{{ $siteSettings['sms_template_dispatch'] ?? '' }}</textarea>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- BulkSMS BD Config Card -->
                <div class="bg-white/90 border p-6 rounded-2xl shadow-sm space-y-5">
                    <div class="flex items-center justify-between border-b pb-4">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-xl bg-blue-50 border border-blue-200 flex items-center justify-center">
                                <i data-lucide="message-square" class="w-5 h-5 text-blue-600"></i>
                            </div>
                            <div>
                                <h3 class="text-[14px] font-bold text-[#0f172a] uppercase">BulkSMS BD</h3>
                                <p class="text-[11px] text-[#64748b]">bulksmsbd.net — Bangladeshi OTP & Transactional SMS</p>
                            </div>
                        </div>
                        <label class="relative inline-flex items-center cursor-pointer">
                            <input type="checkbox" name="sms_bulksmsbd_enabled" id="sms_bulksmsbd_enabled" value="1" class="sr-only peer" {{ ($siteSettings['sms_bulksmsbd_enabled'] ?? '0') === '1' ? 'checked' : '' }}>
                            <div class="w-11 h-6 bg-[#e2e8f0] rounded-full peer peer-checked:bg-[#0284c7] after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:after:translate-x-5"></div>
                        </label>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="text-[11px] font-bold uppercase text-[#475569] block mb-1">API Key</label>
                            <input type="password" name="bulksmsbd_api_key" autocomplete="new-password" placeholder="Your BulkSMS BD API Key" value="{{ $siteSettings['bulksmsbd_api_key'] ?? '' }}" class="w-full border border-[#cbd5e1] px-3 py-2.5 rounded-lg text-[13px] font-mono focus:border-[#0284c7] outline-none bg-white text-[#0f172a]">
                            <p class="text-[10px] text-[#94a3b8] mt-1">Get from <a href="https://bulksmsbd.net" target="_blank" class="text-[#0284c7] underline">bulksmsbd.net</a> → Developer → API</p>
                        </div>
                        <div>
                            <label class="text-[11px] font-bold uppercase text-[#475569] block mb-1">Sender ID (Masking)</label>
                            <input type="text" name="bulksmsbd_sender_id" placeholder="e.g. RaaxO BD" value="{{ $siteSettings['bulksmsbd_sender_id'] ?? '' }}" class="w-full border border-[#cbd5e1] px-3 py-2.5 rounded-lg text-[13px] font-mono focus:border-[#0284c7] outline-none bg-white text-[#0f172a]">
                            <p class="text-[10px] text-[#94a3b8] mt-1">Approved Sender ID from BulkSMS BD panel</p>
                        </div>
                        <div class="md:col-span-2">
                            <label class="text-[11px] font-bold uppercase text-[#475569] block mb-1">API Base URL</label>
                            <input type="text" name="bulksmsbd_base_url" placeholder="https://bulksmsbd.net/api/smsapi" value="{{ $siteSettings['bulksmsbd_base_url'] ?? 'https://bulksmsbd.net/api/smsapi' }}" class="w-full border border-[#cbd5e1] px-3 py-2.5 rounded-lg text-[13px] font-mono focus:border-[#0284c7] outline-none bg-white text-[#0f172a]">
                        </div>
                    </div>

                    <!-- Test SMS -->
                    <div class="bg-[#f8fafc] border border-[#e2e8f0] rounded-xl p-4 space-y-3">
                        <h4 class="text-[11px] font-bold uppercase text-[#475569]">Test BulkSMS BD Connection</h4>
                        <div class="flex items-center gap-3">
                            <input type="text" id="bulksmsbd_test_phone" placeholder="e.g. 01712345678" class="flex-1 border border-[#cbd5e1] px-3 py-2 rounded-lg text-[13px] font-mono focus:border-[#0284c7] outline-none bg-white">
                            <button type="button" onclick="testSmsGateway('bulksmsbd')" class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg text-[11px] font-bold uppercase tracking-wider transition-all flex items-center gap-1.5 cursor-pointer shrink-0">
                                <i data-lucide="send" class="w-3.5 h-3.5"></i> Send Test SMS
                            </button>
                        </div>
                        <div id="bulksmsbd_test_result" class="hidden text-[12px] font-mono p-3 rounded-lg"></div>
                    </div>
                </div>

                <!-- MiM SMS Config Card -->
                <div class="bg-white/90 border p-6 rounded-2xl shadow-sm space-y-5">
                    <div class="flex items-center justify-between border-b pb-4">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-xl bg-violet-50 border border-violet-200 flex items-center justify-center">
                                <i data-lucide="send" class="w-5 h-5 text-violet-600"></i>
                            </div>
                            <div>
                                <h3 class="text-[14px] font-bold text-[#0f172a] uppercase">MiM SMS</h3>
                                <p class="text-[11px] text-[#64748b]">mimsms.com — Bangladeshi Bulk, OTP & Promotional SMS</p>
                            </div>
                        </div>
                        <label class="relative inline-flex items-center cursor-pointer">
                            <input type="checkbox" name="sms_mimsms_enabled" id="sms_mimsms_enabled" value="1" class="sr-only peer" {{ ($siteSettings['sms_mimsms_enabled'] ?? '0') === '1' ? 'checked' : '' }}>
                            <div class="w-11 h-6 bg-[#e2e8f0] rounded-full peer peer-checked:bg-[#7c3aed] after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:after:translate-x-5"></div>
                        </label>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="text-[11px] font-bold uppercase text-[#475569] block mb-1">API Key</label>
                            <input type="password" name="mimsms_api_key" autocomplete="new-password" placeholder="Your MiM SMS API Key" value="{{ $siteSettings['mimsms_api_key'] ?? '' }}" class="w-full border border-[#cbd5e1] px-3 py-2.5 rounded-lg text-[13px] font-mono focus:border-[#0284c7] outline-none bg-white text-[#0f172a]">
                            <p class="text-[10px] text-[#94a3b8] mt-1">Get from <a href="https://mimsms.com" target="_blank" class="text-violet-600 underline">mimsms.com</a> → My Profile → API</p>
                        </div>
                        <div>
                            <label class="text-[11px] font-bold uppercase text-[#475569] block mb-1">Sender ID</label>
                            <input type="text" name="mimsms_sender_id" placeholder="e.g. RAAXO" value="{{ $siteSettings['mimsms_sender_id'] ?? '' }}" class="w-full border border-[#cbd5e1] px-3 py-2.5 rounded-lg text-[13px] font-mono focus:border-[#0284c7] outline-none bg-white text-[#0f172a]">
                        </div>
                        <div>
                            <label class="text-[11px] font-bold uppercase text-[#475569] block mb-1">API Type</label>
                            <select name="mimsms_type" class="w-full border border-[#cbd5e1] px-3 py-2.5 rounded-lg text-[13px] font-bold focus:border-[#0284c7] outline-none bg-white text-[#0f172a]">
                                <option value="text" {{ ($siteSettings['mimsms_type'] ?? 'text') === 'text' ? 'selected' : '' }}>TEXT (Transactional)</option>
                                <option value="unicode" {{ ($siteSettings['mimsms_type'] ?? '') === 'unicode' ? 'selected' : '' }}>UNICODE (Bengali support)</option>
                            </select>
                        </div>
                        <div>
                            <label class="text-[11px] font-bold uppercase text-[#475569] block mb-1">API Base URL</label>
                            <input type="text" name="mimsms_base_url" placeholder="https://api.mimsms.com/api/SmSAPI" value="{{ $siteSettings['mimsms_base_url'] ?? 'https://api.mimsms.com/api/SmSAPI' }}" class="w-full border border-[#cbd5e1] px-3 py-2.5 rounded-lg text-[13px] font-mono focus:border-[#0284c7] outline-none bg-white text-[#0f172a]">
                        </div>
                    </div>

                    <!-- Test SMS -->
                    <div class="bg-[#f8fafc] border border-[#e2e8f0] rounded-xl p-4 space-y-3">
                        <h4 class="text-[11px] font-bold uppercase text-[#475569]">Test MiM SMS Connection</h4>
                        <div class="flex items-center gap-3">
                            <input type="text" id="mimsms_test_phone" placeholder="e.g. 01712345678" class="flex-1 border border-[#cbd5e1] px-3 py-2 rounded-lg text-[13px] font-mono focus:border-[#0284c7] outline-none bg-white">
                            <button type="button" onclick="testSmsGateway('mimsms')" class="px-4 py-2 bg-violet-600 hover:bg-violet-700 text-white rounded-lg text-[11px] font-bold uppercase tracking-wider transition-all flex items-center gap-1.5 cursor-pointer shrink-0">
                                <i data-lucide="send" class="w-3.5 h-3.5"></i> Send Test SMS
                            </button>
                        </div>
                        <div id="mimsms_test_result" class="hidden text-[12px] font-mono p-3 rounded-lg"></div>
                    </div>
                </div>

                <!-- Save SMS Settings -->
                <div class="flex justify-end">
                    <button type="button" onclick="saveApiSettings('sms')" class="bg-[#0f172a] hover:bg-[#0284c7] text-white px-8 py-3 rounded-xl text-[13px] font-bold uppercase tracking-wider transition-all flex items-center gap-2 shadow-sm cursor-pointer">
                        <i data-lucide="save" class="w-4 h-4"></i> Save SMS Settings
                    </button>
                </div>
            </div>

            <!-- ============================= -->
            <!-- TAB: COURIER API -->
            <!-- ============================= -->
            <div id="api-panel-courier_api" class="api-tab-panel hidden space-y-6">

                <div class="bg-amber-50 border border-amber-200 text-amber-800 px-5 py-4 rounded-xl text-[13px] font-bold flex items-start gap-3">
                    <i data-lucide="info" class="w-5 h-5 shrink-0 mt-0.5"></i>
                    <div>
                        <span class="block mb-1">Courier API credentials are managed in the dedicated <strong>Courier Hub</strong> page.</span>
                        <a href="{{ url('/admin/courier') }}" class="underline text-amber-700 font-bold">→ Go to Courier Hub & API Setup</a>
                    </div>
                </div>

                <!-- Quick Summary Cards -->
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-5">
                    @php
                        $courierList = [
                            ['key'=>'steadfast',  'name'=>'Steadfast Courier',  'icon'=>'truck', 'color'=>'sky',    'url'=>'https://steadfast.com.bd', 'doc'=>'https://developer.steadfast.com.bd'],
                            ['key'=>'pathao',     'name'=>'Pathao Courier',     'icon'=>'navigation', 'color'=>'orange', 'url'=>'https://merchant.pathao.com', 'doc'=>'https://developer.pathao.com'],
                            ['key'=>'redx',       'name'=>'RedX',               'icon'=>'package', 'color'=>'rose',   'url'=>'https://redx.com.bd', 'doc'=>'https://redx.com.bd/developers'],
                        ];
                    @endphp
                    @foreach($courierList as $c)
                    @php $isActive = !empty($siteSettings[$c['key'].'_api_key']); @endphp
                    <div class="bg-white/90 border rounded-2xl p-5 space-y-3 shadow-xs hover:border-[#0284c7] transition-all">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center gap-3">
                                <div class="w-9 h-9 rounded-xl bg-{{ $c['color'] }}-50 border border-{{ $c['color'] }}-200 flex items-center justify-center">
                                    <i data-lucide="{{ $c['icon'] }}" class="w-4 h-4 text-{{ $c['color'] }}-600"></i>
                                </div>
                                <div>
                                    <h3 class="text-[13px] font-bold text-[#0f172a]">{{ $c['name'] }}</h3>
                                    <a href="{{ $c['url'] }}" target="_blank" class="text-[10px] text-[#64748b] hover:text-[#0284c7]">{{ $c['url'] }}</a>
                                </div>
                            </div>
                            @if($isActive)
                                <span class="bg-emerald-50 text-emerald-700 border border-emerald-200 px-2.5 py-0.5 rounded-full text-[10px] font-bold uppercase">● Configured</span>
                            @else
                                <span class="bg-slate-50 text-[#94a3b8] border border-slate-200 px-2.5 py-0.5 rounded-full text-[10px] font-bold uppercase">○ Not Set</span>
                            @endif
                        </div>
                        <div class="pt-2 flex gap-2">
                            <a href="{{ url('/admin/courier') }}" class="flex-1 text-center py-2 bg-[#f1f5f9] hover:bg-[#e0f2fe] text-[#0284c7] rounded-lg text-[11px] font-bold border border-[#e2e8f0] transition-all">Configure →</a>
                            <a href="{{ $c['doc'] }}" target="_blank" class="px-3 py-2 bg-[#f1f5f9] hover:bg-[#f1f5f9] text-[#64748b] rounded-lg text-[11px] font-bold border border-[#e2e8f0]">Docs</a>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>

            <!-- ============================= -->
            <!-- TAB: META / FACEBOOK -->
            <!-- ============================= -->
            <div id="api-panel-meta" class="api-tab-panel hidden space-y-6">
                <div class="bg-white/90 border p-6 rounded-2xl shadow-sm space-y-6">
                    <div class="border-b pb-4">
                        <h3 class="text-[16px] font-serif font-bold text-[#0f172a] uppercase flex items-center gap-3">
                            <div class="w-8 h-8 rounded-lg bg-blue-50 border border-blue-200 flex items-center justify-center">
                                <i data-lucide="facebook" class="w-4 h-4 text-blue-600"></i>
                            </div>
                            Meta / Facebook Integration
                        </h3>
                        <p class="text-[12px] text-[#64748b] mt-2">Configure Facebook Pixel, Conversion API (CAPI) and Catalog for retargeting ads.</p>
                    </div>

                    <form data-settings-form onsubmit="handleSettingsSave(event)" class="space-y-6">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                            <div>
                                <label class="text-[11px] font-bold uppercase text-[#475569] block mb-1">Facebook / Meta Pixel ID</label>
                                <input type="text" name="pixel_facebook" placeholder="e.g. 123456789012345" value="{{ $siteSettings['pixel_facebook'] ?? '' }}" class="w-full border border-[#cbd5e1] px-3 py-2.5 rounded-lg text-[13px] font-bold font-mono focus:border-[#0284c7] outline-none bg-white text-[#0f172a]">
                                <p class="text-[10px] text-[#94a3b8] mt-1">Found in <strong>Events Manager → Data Sources</strong> on Facebook Business Manager</p>
                            </div>
                            <div>
                                <label class="text-[11px] font-bold uppercase text-[#475569] block mb-1">Meta CAPI Access Token (Optional)</label>
                                <input type="password" name="meta_capi_token" autocomplete="new-password" placeholder="Your CAPI server-side token" value="{{ $siteSettings['meta_capi_token'] ?? '' }}" class="w-full border border-[#cbd5e1] px-3 py-2.5 rounded-lg text-[13px] font-bold font-mono focus:border-[#0284c7] outline-none bg-white text-[#0f172a]">
                                <p class="text-[10px] text-[#94a3b8] mt-1">Server-side Conversions API for cookieless tracking</p>
                            </div>
                            <div>
                                <label class="text-[11px] font-bold uppercase text-[#475569] block mb-1">Facebook Catalog ID (Optional)</label>
                                <input type="text" name="meta_catalog_id" placeholder="e.g. 4567890123456" value="{{ $siteSettings['meta_catalog_id'] ?? '' }}" class="w-full border border-[#cbd5e1] px-3 py-2.5 rounded-lg text-[13px] font-bold font-mono focus:border-[#0284c7] outline-none bg-white text-[#0f172a]">
                                <p class="text-[10px] text-[#94a3b8] mt-1">Used for Dynamic Product Ads (DPA)</p>
                            </div>
                            <div>
                                <label class="text-[11px] font-bold uppercase text-[#475569] block mb-1">Facebook App ID (Optional)</label>
                                <input type="text" name="meta_app_id" placeholder="e.g. 9876543210" value="{{ $siteSettings['meta_app_id'] ?? '' }}" class="w-full border border-[#cbd5e1] px-3 py-2.5 rounded-lg text-[13px] font-bold font-mono focus:border-[#0284c7] outline-none bg-white text-[#0f172a]">
                            </div>
                        </div>

                        <!-- Meta Pixel Status -->
                        <div class="bg-[#f8fafc] border rounded-xl p-4 flex items-center justify-between">
                            <div>
                                <p class="text-[12px] font-bold text-[#0f172a]">Pixel Status</p>
                                @if(!empty($siteSettings['pixel_facebook']))
                                    <span class="text-[11px] text-emerald-700 font-mono">✔ Pixel ID: {{ $siteSettings['pixel_facebook'] }} — Active</span>
                                @else
                                    <span class="text-[11px] text-[#94a3b8]">Not configured. Enter Pixel ID above.</span>
                                @endif
                            </div>
                            <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-5 py-2.5 rounded-xl text-[12px] font-bold uppercase tracking-wider flex items-center gap-2 transition-all cursor-pointer">
                                <i data-lucide="save" class="w-4 h-4"></i> Save Meta Settings
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- ============================= -->
            <!-- TAB: GOOGLE ANALYTICS -->
            <!-- ============================= -->
            <div id="api-panel-google" class="api-tab-panel hidden space-y-6">
                <div class="bg-white/90 border p-6 rounded-2xl shadow-sm space-y-6">
                    <div class="border-b pb-4">
                        <h3 class="text-[16px] font-serif font-bold text-[#0f172a] uppercase flex items-center gap-3">
                            <div class="w-8 h-8 rounded-lg bg-red-50 border border-red-200 flex items-center justify-center">
                                <i data-lucide="bar-chart-2" class="w-4 h-4 text-red-500"></i>
                            </div>
                            Google Analytics & Tag Manager
                        </h3>
                        <p class="text-[12px] text-[#64748b] mt-2">Configure Google Analytics 4 (GA4), Tag Manager and Search Console verification.</p>
                    </div>

                    <form data-settings-form onsubmit="handleSettingsSave(event)" class="space-y-6">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                            <div>
                                <label class="text-[11px] font-bold uppercase text-[#475569] block mb-1">Google Analytics 4 (GA4) ID</label>
                                <input type="text" name="pixel_google" placeholder="e.g. G-XXXXXXXXXX" value="{{ $siteSettings['pixel_google'] ?? '' }}" class="w-full border border-[#cbd5e1] px-3 py-2.5 rounded-lg text-[13px] font-bold font-mono focus:border-[#0284c7] outline-none bg-white text-[#0f172a]">
                                <p class="text-[10px] text-[#94a3b8] mt-1">Found in <strong>Google Analytics → Admin → Data Streams</strong></p>
                            </div>
                            <div>
                                <label class="text-[11px] font-bold uppercase text-[#475569] block mb-1">Google Tag Manager ID</label>
                                <input type="text" name="pixel_gtm" placeholder="e.g. GTM-XXXXXXX" value="{{ $siteSettings['pixel_gtm'] ?? '' }}" class="w-full border border-[#cbd5e1] px-3 py-2.5 rounded-lg text-[13px] font-bold font-mono focus:border-[#0284c7] outline-none bg-white text-[#0f172a]">
                                <p class="text-[10px] text-[#94a3b8] mt-1">From Google Tag Manager → Admin → Container ID</p>
                            </div>
                            <div>
                                <label class="text-[11px] font-bold uppercase text-[#475569] block mb-1">Google Search Console Verification Code</label>
                                <input type="text" name="google_site_verification" placeholder="e.g. abc123XYZ..." value="{{ $siteSettings['google_site_verification'] ?? '' }}" class="w-full border border-[#cbd5e1] px-3 py-2.5 rounded-lg text-[13px] font-bold font-mono focus:border-[#0284c7] outline-none bg-white text-[#0f172a]">
                                <p class="text-[10px] text-[#94a3b8] mt-1">Paste just the content value from the meta tag, not the full tag</p>
                            </div>
                            <div>
                                <label class="text-[11px] font-bold uppercase text-[#475569] block mb-1">TikTok Pixel ID</label>
                                <input type="text" name="pixel_tiktok" placeholder="e.g. CXXXXXXXXXXXX" value="{{ $siteSettings['pixel_tiktok'] ?? '' }}" class="w-full border border-[#cbd5e1] px-3 py-2.5 rounded-lg text-[13px] font-bold font-mono focus:border-[#0284c7] outline-none bg-white text-[#0f172a]">
                                <p class="text-[10px] text-[#94a3b8] mt-1">From TikTok Ads Manager → Assets → Events</p>
                            </div>
                        </div>

                        <div class="bg-[#f8fafc] border rounded-xl p-4 flex items-center justify-between">
                            <div>
                                @if(!empty($siteSettings['pixel_google']))
                                    <p class="text-[12px] font-bold text-emerald-700">✔ GA4 Active — {{ $siteSettings['pixel_google'] }}</p>
                                @else
                                    <p class="text-[12px] text-[#94a3b8]">No Google Analytics configured yet.</p>
                                @endif
                            </div>
                            <button type="submit" class="bg-red-600 hover:bg-red-700 text-white px-5 py-2.5 rounded-xl text-[12px] font-bold uppercase tracking-wider flex items-center gap-2 transition-all cursor-pointer">
                                <i data-lucide="save" class="w-4 h-4"></i> Save Google Settings
                            </button>
                        </div>
                    </form>
                </div>
            </div>

        </div>

        <!-- SECTION: SEO & PIXELS -->
        <div id="section-seo" class="section-content hidden space-y-6">
            <div class="bg-white/90 border p-6 rounded-2xl space-y-6">
                <div class="border-b pb-4">
                    <h2 class="text-[18px] font-serif font-bold text-[#0f172a] uppercase flex items-center gap-2">
                        <i data-lucide="bar-chart-2" class="w-5 h-5 text-[#0284c7]"></i> SEO & Marketing Pixels
                    </h2>
                    <p class="text-[12px] text-[#64748b] mt-1">Configure global search engine optimization settings and marketing tracking pixels.</p>
                </div>

                <form data-settings-form onsubmit="handleSettingsSave(event)" class="space-y-8">
                    <!-- Global SEO Metadata -->
                    <div class="space-y-4">
                        <h3 class="text-[13px] font-bold text-[#0f172a] uppercase border-l-4 border-[#0284c7] pl-2">Global SEO Metadata</h3>
                        <div class="space-y-4">
                            <div>
                                <label class="text-[11px] font-bold uppercase text-[#475569] block mb-1">Global Meta Title</label>
                                <input type="text" name="seo_meta_title" class="w-full border border-[#cbd5e1] px-3 py-2.5 rounded-lg text-[13px] font-bold focus:border-[#0284c7] outline-none bg-white text-[#0f172a]">
                                <p class="text-[10px] text-[#94a3b8] mt-1">This title appears in search engine results and browser tabs.</p>
                            </div>
                            <div>
                                <label class="text-[11px] font-bold uppercase text-[#475569] block mb-1">Global Meta Description</label>
                                <textarea name="seo_meta_description" rows="2" class="w-full border border-[#cbd5e1] px-3 py-2.5 rounded-lg text-[13px] font-bold focus:border-[#0284c7] outline-none bg-white text-[#0f172a]"></textarea>
                                <p class="text-[10px] text-[#94a3b8] mt-1">Recommended length: 150-160 characters.</p>
                            </div>
                            <div>
                                <label class="text-[11px] font-bold uppercase text-[#475569] block mb-1">Meta Keywords</label>
                                <input type="text" name="seo_meta_keywords" class="w-full border border-[#cbd5e1] px-3 py-2.5 rounded-lg text-[13px] font-bold focus:border-[#0284c7] outline-none bg-white text-[#0f172a]">
                                <p class="text-[10px] text-[#94a3b8] mt-1">Separate keywords with commas.</p>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <!-- ================================================================= -->
        <!-- 1. SECTION: PAYMENT GATEWAY (SSLCommerz, EPS, bKash, COD)        -->
        <!-- ================================================================= -->
        <div id="section-api_payment" class="section-content hidden space-y-6 animate-fade-in">
            <!-- Header -->
            <div class="bg-white/90 border border-[#38bdf8]/30 p-6 rounded-2xl shadow-sm">
                <div class="flex items-center justify-between flex-wrap gap-4">
                    <div>
                        <h2 class="text-[18px] font-serif font-bold text-[#0f172a] uppercase flex items-center gap-2">
                            <i data-lucide="credit-card" class="w-5 h-5 text-[#0284c7]"></i> Payment Gateway Configuration
                        </h2>
                        <p class="text-[12px] text-[#64748b] mt-1">Configure Bangladesh payment gateways (SSLCommerz, Easy Payment System, bKash Direct Merchant) and Cash on Delivery.</p>
                    </div>
                    <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg text-[11px] font-bold font-mono bg-emerald-50 text-emerald-700 border border-emerald-200">
                        <i data-lucide="shield-check" class="w-3.5 h-3.5"></i> 256-Bit TLS Secured
                    </span>
                </div>
            </div>

            <form action="{{ url('/admin/settings') }}" method="POST" class="space-y-6">
                @csrf

                <!-- SSLCommerz -->
                <div class="bg-white/90 border p-6 rounded-2xl shadow-sm space-y-5">
                    <div class="border-b pb-4 flex items-center justify-between flex-wrap gap-3">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-xl bg-sky-50 border border-sky-200 flex items-center justify-center font-bold text-sky-700 text-[14px]">
                                SSL
                            </div>
                            <div>
                                <h3 class="text-[15px] font-serif font-bold text-[#0f172a] uppercase">SSLCommerz (Cards, MFS & Net Banking)</h3>
                                <p class="text-[11px] text-[#64748b]">Accept Visa, MasterCard, bKash, Nagad, Rocket, Upay & all local bank cards.</p>
                            </div>
                        </div>
                        <label class="relative inline-flex items-center cursor-pointer">
                            <input type="checkbox" name="sslcommerz_enabled" value="1" class="sr-only peer" {{ ($siteSettings['sslcommerz_enabled'] ?? '0') === '1' ? 'checked' : '' }}>
                            <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-[#0284c7]"></div>
                            <span class="ml-2 text-[12px] font-bold text-[#0f172a]">Enable Gateway</span>
                        </label>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="text-[11px] font-bold uppercase text-[#475569] block mb-1">Store ID <span class="text-rose-500">*</span></label>
                            <input type="text" name="sslcommerz_store_id" value="{{ $siteSettings['sslcommerz_store_id'] ?? '' }}" placeholder="e.g. testbox_live" class="w-full border border-[#cbd5e1] px-3 py-2 rounded-lg text-[13px] font-mono focus:border-[#0284c7] outline-none bg-white text-[#0f172a]">
                        </div>
                        <div>
                            <label class="text-[11px] font-bold uppercase text-[#475569] block mb-1">Store Password / Key <span class="text-rose-500">*</span></label>
                            <input type="password" name="sslcommerz_store_password" value="{{ $siteSettings['sslcommerz_store_password'] ?? '' }}" placeholder="••••••••••••••••" class="w-full border border-[#cbd5e1] px-3 py-2 rounded-lg text-[13px] font-mono focus:border-[#0284c7] outline-none bg-white text-[#0f172a]">
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="text-[11px] font-bold uppercase text-[#475569] block mb-1">Environment Mode</label>
                            <select name="sslcommerz_sandbox" class="w-full border border-[#cbd5e1] px-3 py-2 rounded-lg text-[13px] font-bold focus:border-[#0284c7] outline-none bg-white text-[#0f172a]">
                                <option value="0" {{ ($siteSettings['sslcommerz_sandbox'] ?? '0') === '0' ? 'selected' : '' }}>🟢 Live Production (Real Transactions)</option>
                                <option value="1" {{ ($siteSettings['sslcommerz_sandbox'] ?? '0') === '1' ? 'selected' : '' }}>🟡 Sandbox / Test Mode</option>
                            </select>
                        </div>
                        <div>
                            <label class="text-[11px] font-bold uppercase text-[#475569] block mb-1">Instant Payment Notification (IPN) URL</label>
                            <input type="text" readonly value="{{ url('/api/payment/sslcommerz/ipn') }}" class="w-full border border-[#cbd5e1] px-3 py-2 rounded-lg text-[12px] font-mono bg-slate-50 text-[#64748b] cursor-pointer" onclick="this.select(); navigator.clipboard.writeText(this.value); showToastNotice('IPN URL copied to clipboard!');">
                        </div>
                    </div>
                </div>

                <!-- Easy Payment System (EPS) -->
                <div class="bg-white/90 border p-6 rounded-2xl shadow-sm space-y-5">
                    <div class="border-b pb-4 flex items-center justify-between flex-wrap gap-3">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-xl bg-emerald-50 border border-emerald-200 flex items-center justify-center font-bold text-emerald-700 text-[14px]">
                                EPS
                            </div>
                            <div>
                                <h3 class="text-[15px] font-serif font-bold text-[#0f172a] uppercase">Easy Payment System (EPS)</h3>
                                <p class="text-[11px] text-[#64748b]">Bangladesh Bank approved innovative multi-channel fintech payment gateway.</p>
                            </div>
                        </div>
                        <label class="relative inline-flex items-center cursor-pointer">
                            <input type="checkbox" name="eps_enabled" value="1" class="sr-only peer" {{ ($siteSettings['eps_enabled'] ?? '0') === '1' ? 'checked' : '' }}>
                            <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-[#0284c7]"></div>
                            <span class="ml-2 text-[12px] font-bold text-[#0f172a]">Enable Gateway</span>
                        </label>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div>
                            <label class="text-[11px] font-bold uppercase text-[#475569] block mb-1">EPS Merchant ID <span class="text-rose-500">*</span></label>
                            <input type="text" name="eps_merchant_id" value="{{ $siteSettings['eps_merchant_id'] ?? '' }}" placeholder="e.g. EPS_M_10023" class="w-full border border-[#cbd5e1] px-3 py-2 rounded-lg text-[13px] font-mono focus:border-[#0284c7] outline-none bg-white text-[#0f172a]">
                        </div>
                        <div>
                            <label class="text-[11px] font-bold uppercase text-[#475569] block mb-1">EPS Username <span class="text-rose-500">*</span></label>
                            <input type="text" name="eps_username" value="{{ $siteSettings['eps_username'] ?? '' }}" placeholder="Merchant API User" class="w-full border border-[#cbd5e1] px-3 py-2 rounded-lg text-[13px] font-mono focus:border-[#0284c7] outline-none bg-white text-[#0f172a]">
                        </div>
                        <div>
                            <label class="text-[11px] font-bold uppercase text-[#475569] block mb-1">EPS Password / Secret <span class="text-rose-500">*</span></label>
                            <input type="password" name="eps_password" value="{{ $siteSettings['eps_password'] ?? '' }}" placeholder="••••••••••••••••" class="w-full border border-[#cbd5e1] px-3 py-2 rounded-lg text-[13px] font-mono focus:border-[#0284c7] outline-none bg-white text-[#0f172a]">
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="text-[11px] font-bold uppercase text-[#475569] block mb-1">Environment Mode</label>
                            <select name="eps_sandbox" class="w-full border border-[#cbd5e1] px-3 py-2 rounded-lg text-[13px] font-bold focus:border-[#0284c7] outline-none bg-white text-[#0f172a]">
                                <option value="0" {{ ($siteSettings['eps_sandbox'] ?? '0') === '0' ? 'selected' : '' }}>🟢 Live Production</option>
                                <option value="1" {{ ($siteSettings['eps_sandbox'] ?? '0') === '1' ? 'selected' : '' }}>🟡 Sandbox Test Mode</option>
                            </select>
                        </div>
                        <div>
                            <label class="text-[11px] font-bold uppercase text-[#475569] block mb-1">Return / Callback URL</label>
                            <input type="text" readonly value="{{ url('/api/payment/eps/callback') }}" class="w-full border border-[#cbd5e1] px-3 py-2 rounded-lg text-[12px] font-mono bg-slate-50 text-[#64748b] cursor-pointer" onclick="this.select(); navigator.clipboard.writeText(this.value); showToastNotice('Callback URL copied!');">
                        </div>
                    </div>
                </div>

                <!-- bKash Direct Merchant -->
                <div class="bg-white/90 border p-6 rounded-2xl shadow-sm space-y-5">
                    <div class="border-b pb-4 flex items-center justify-between flex-wrap gap-3">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-xl bg-pink-50 border border-pink-200 flex items-center justify-center font-bold text-[#e2136e] text-[14px]">
                                ৳
                            </div>
                            <div>
                                <h3 class="text-[15px] font-serif font-bold text-[#0f172a] uppercase">bKash Direct Merchant Checkout</h3>
                                <p class="text-[11px] text-[#64748b]">Direct tokenized bKash Checkout (Create, Execute & Query Payment API).</p>
                            </div>
                        </div>
                        <label class="relative inline-flex items-center cursor-pointer">
                            <input type="checkbox" name="bkash_enabled" value="1" class="sr-only peer" {{ ($siteSettings['bkash_enabled'] ?? '0') === '1' ? 'checked' : '' }}>
                            <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-[#e2136e]"></div>
                            <span class="ml-2 text-[12px] font-bold text-[#0f172a]">Enable Gateway</span>
                        </label>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="text-[11px] font-bold uppercase text-[#475569] block mb-1">bKash App Key <span class="text-rose-500">*</span></label>
                            <input type="text" name="bkash_app_key" value="{{ $siteSettings['bkash_app_key'] ?? '' }}" placeholder="e.g. 4f6xxxxxx" class="w-full border border-[#cbd5e1] px-3 py-2 rounded-lg text-[13px] font-mono focus:border-[#0284c7] outline-none bg-white text-[#0f172a]">
                        </div>
                        <div>
                            <label class="text-[11px] font-bold uppercase text-[#475569] block mb-1">bKash App Secret <span class="text-rose-500">*</span></label>
                            <input type="password" name="bkash_app_secret" value="{{ $siteSettings['bkash_app_secret'] ?? '' }}" placeholder="••••••••••••••••" class="w-full border border-[#cbd5e1] px-3 py-2 rounded-lg text-[13px] font-mono focus:border-[#0284c7] outline-none bg-white text-[#0f172a]">
                        </div>
                        <div>
                            <label class="text-[11px] font-bold uppercase text-[#475569] block mb-1">bKash Merchant Username <span class="text-rose-500">*</span></label>
                            <input type="text" name="bkash_username" value="{{ $siteSettings['bkash_username'] ?? '' }}" placeholder="e.g. 017xxxxxxxx" class="w-full border border-[#cbd5e1] px-3 py-2 rounded-lg text-[13px] font-mono focus:border-[#0284c7] outline-none bg-white text-[#0f172a]">
                        </div>
                        <div>
                            <label class="text-[11px] font-bold uppercase text-[#475569] block mb-1">bKash Merchant Password <span class="text-rose-500">*</span></label>
                            <input type="password" name="bkash_password" value="{{ $siteSettings['bkash_password'] ?? '' }}" placeholder="••••••••••••••••" class="w-full border border-[#cbd5e1] px-3 py-2 rounded-lg text-[13px] font-mono focus:border-[#0284c7] outline-none bg-white text-[#0f172a]">
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="text-[11px] font-bold uppercase text-[#475569] block mb-1">Environment Mode</label>
                            <select name="bkash_sandbox" class="w-full border border-[#cbd5e1] px-3 py-2 rounded-lg text-[13px] font-bold focus:border-[#0284c7] outline-none bg-white text-[#0f172a]">
                                <option value="0" {{ ($siteSettings['bkash_sandbox'] ?? '0') === '0' ? 'selected' : '' }}>🟢 Live Production</option>
                                <option value="1" {{ ($siteSettings['bkash_sandbox'] ?? '0') === '1' ? 'selected' : '' }}>🟡 Sandbox Test Mode</option>
                            </select>
                        </div>
                        <div>
                            <label class="text-[11px] font-bold uppercase text-[#475569] block mb-1">Callback URL</label>
                            <input type="text" readonly value="{{ url('/api/payment/bkash/callback') }}" class="w-full border border-[#cbd5e1] px-3 py-2 rounded-lg text-[12px] font-mono bg-slate-50 text-[#64748b] cursor-pointer" onclick="this.select(); navigator.clipboard.writeText(this.value); showToastNotice('bKash Callback URL copied!');">
                        </div>
                    </div>
                </div>

                <!-- Cash on Delivery (COD) -->
                <div class="bg-white/90 border p-6 rounded-2xl shadow-sm space-y-5">
                    <div class="border-b pb-4 flex items-center justify-between flex-wrap gap-3">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-xl bg-amber-50 border border-amber-200 flex items-center justify-center font-bold text-amber-700 text-[14px]">
                                COD
                            </div>
                            <div>
                                <h3 class="text-[15px] font-serif font-bold text-[#0f172a] uppercase">Cash on Delivery (Concierge Hand-Over)</h3>
                                <p class="text-[11px] text-[#64748b]">Allow customers to pay in cash upon physical package delivery and inspection.</p>
                            </div>
                        </div>
                        <label class="relative inline-flex items-center cursor-pointer">
                            <input type="checkbox" name="cod_enabled" value="1" class="sr-only peer" {{ ($siteSettings['cod_enabled'] ?? '1') === '1' ? 'checked' : '' }}>
                            <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-[#0284c7]"></div>
                            <span class="ml-2 text-[12px] font-bold text-[#0f172a]">Enable COD</span>
                        </label>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="text-[11px] font-bold uppercase text-[#475569] block mb-1">COD Extra Handling Charge (BDT)</label>
                            <input type="number" name="cod_charge" value="{{ $siteSettings['cod_charge'] ?? '0' }}" placeholder="0" class="w-full border border-[#cbd5e1] px-3 py-2 rounded-lg text-[13px] font-mono focus:border-[#0284c7] outline-none bg-white text-[#0f172a]">
                        </div>
                        <div>
                            <label class="text-[11px] font-bold uppercase text-[#475569] block mb-1">Customer Checkout Instruction Note</label>
                            <input type="text" name="cod_instruction" value="{{ $siteSettings['cod_instruction'] ?? 'Pay in cash upon delivery after inspection.' }}" class="w-full border border-[#cbd5e1] px-3 py-2 rounded-lg text-[13px] focus:border-[#0284c7] outline-none bg-white text-[#0f172a]">
                        </div>
                    </div>
                </div>

                <!-- Save Action -->
                <div class="pt-4 border-t border-[#e2e8f0] flex items-center justify-between">
                    <span class="text-[12px] text-[#64748b]">All gateway secrets are encrypted in the database.</span>
                    <button type="submit" class="bg-[#0f172a] hover:bg-[#0284c7] text-white px-8 py-3 rounded-xl text-[13px] font-bold uppercase tracking-wider transition-all flex items-center gap-2 shadow-sm cursor-pointer">
                        <i data-lucide="save" class="w-4 h-4"></i> Save Payment Settings
                    </button>
                </div>
            </form>
        </div>

        <!-- ================================================================= -->
        <!-- 2. SECTION: SMS SETTING (BulkSMS BD, MiM SMS, Templates, Tester) -->
        <!-- ================================================================= -->
        <div id="section-api_sms" class="section-content hidden space-y-6 animate-fade-in">
            <!-- Header -->
            <div class="bg-white/90 border border-[#38bdf8]/30 p-6 rounded-2xl shadow-sm">
                <div class="flex items-center justify-between flex-wrap gap-4">
                    <div>
                        <h2 class="text-[18px] font-serif font-bold text-[#0f172a] uppercase flex items-center gap-2">
                            <i data-lucide="message-square" class="w-5 h-5 text-[#0284c7]"></i> SMS Gateway & Notification Settings
                        </h2>
                        <p class="text-[12px] text-[#64748b] mt-1">Configure automated SMS alerts for new orders, courier dispatches, and delivery status updates.</p>
                    </div>
                    <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg text-[11px] font-bold font-mono bg-sky-50 text-[#0284c7] border border-sky-200">
                        <i data-lucide="send" class="w-3.5 h-3.5"></i> Live SMS Gateway
                    </span>
                </div>
            </div>

            <form action="{{ url('/admin/settings') }}" method="POST" class="space-y-6">
                @csrf

                <!-- Trigger Events -->
                <div class="bg-white/90 border p-6 rounded-2xl shadow-sm space-y-4">
                    <h3 class="text-[15px] font-serif font-bold text-[#0f172a] uppercase border-l-4 border-[#0284c7] pl-3">
                        Trigger Events — When to Send Automated SMS
                    </h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-3 bg-[#f8fafc] p-4 rounded-xl border">
                        <label class="flex items-center gap-3 p-3 bg-white rounded-xl border cursor-pointer hover:border-[#0284c7] transition-all">
                            <input type="checkbox" name="sms_on_new_order" id="sms_on_new_order" value="1" class="w-4 h-4 text-[#0284c7] accent-[#0284c7]" {{ ($siteSettings['sms_on_new_order'] ?? '0') === '1' ? 'checked' : '' }}>
                            <div>
                                <span class="text-[12px] font-bold text-[#0f172a] block">New Order Placed</span>
                                <span class="text-[11px] text-[#64748b]">Send instant confirmation SMS when order is placed</span>
                            </div>
                        </label>
                        <label class="flex items-center gap-3 p-3 bg-white rounded-xl border cursor-pointer hover:border-[#0284c7] transition-all">
                            <input type="checkbox" name="sms_on_dispatch" id="sms_on_dispatch" value="1" class="w-4 h-4 text-[#0284c7] accent-[#0284c7]" {{ ($siteSettings['sms_on_dispatch'] ?? '0') === '1' ? 'checked' : '' }}>
                            <div>
                                <span class="text-[12px] font-bold text-[#0f172a] block">Order Dispatched / Shipped</span>
                                <span class="text-[11px] text-[#64748b]">Send SMS with courier tracking code & delivery link</span>
                            </div>
                        </label>
                        <label class="flex items-center gap-3 p-3 bg-white rounded-xl border cursor-pointer hover:border-[#0284c7] transition-all">
                            <input type="checkbox" name="sms_on_delivered" id="sms_on_delivered" value="1" class="w-4 h-4 text-[#0284c7] accent-[#0284c7]" {{ ($siteSettings['sms_on_delivered'] ?? '0') === '1' ? 'checked' : '' }}>
                            <div>
                                <span class="text-[12px] font-bold text-[#0f172a] block">Order Delivered</span>
                                <span class="text-[11px] text-[#64748b]">Send thank you SMS when delivery is completed</span>
                            </div>
                        </label>
                        <label class="flex items-center gap-3 p-3 bg-white rounded-xl border cursor-pointer hover:border-[#0284c7] transition-all">
                            <input type="checkbox" name="sms_on_cancelled" id="sms_on_cancelled" value="1" class="w-4 h-4 text-[#0284c7] accent-[#0284c7]" {{ ($siteSettings['sms_on_cancelled'] ?? '0') === '1' ? 'checked' : '' }}>
                            <div>
                                <span class="text-[12px] font-bold text-[#0f172a] block">Order Cancelled</span>
                                <span class="text-[11px] text-[#64748b]">Send notification if an order is cancelled</span>
                            </div>
                        </label>
                    </div>
                </div>

                <!-- Provider 1: BulkSMS BD -->
                <div class="bg-white/90 border p-6 rounded-2xl shadow-sm space-y-4">
                    <div class="border-b pb-4 flex items-center justify-between">
                        <h3 class="text-[15px] font-serif font-bold text-[#0f172a] uppercase flex items-center gap-2">
                            <i data-lucide="radio" class="w-4 h-4 text-[#0284c7]"></i> BulkSMS BD Gateway (bulksmsbd.net)
                        </h3>
                        <label class="relative inline-flex items-center cursor-pointer">
                            <input type="checkbox" name="bulksms_enabled" value="1" class="sr-only peer" {{ ($siteSettings['bulksms_enabled'] ?? '0') === '1' ? 'checked' : '' }}>
                            <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-[#0284c7]"></div>
                        </label>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div>
                            <label class="text-[11px] font-bold uppercase text-[#475569] block mb-1">API Key</label>
                            <input type="password" name="bulksms_api_key" value="{{ $siteSettings['bulksms_api_key'] ?? '' }}" placeholder="Enter BulkSMS BD API Key" class="w-full border border-[#cbd5e1] px-3 py-2 rounded-lg text-[13px] font-mono focus:border-[#0284c7] outline-none bg-white text-[#0f172a]">
                        </div>
                        <div>
                            <label class="text-[11px] font-bold uppercase text-[#475569] block mb-1">Sender ID / Masking</label>
                            <input type="text" name="bulksms_sender_id" value="{{ $siteSettings['bulksms_sender_id'] ?? '' }}" placeholder="e.g. 8809612xxxxxx or Brand Name" class="w-full border border-[#cbd5e1] px-3 py-2 rounded-lg text-[13px] focus:border-[#0284c7] outline-none bg-white text-[#0f172a]">
                        </div>
                        <div>
                            <label class="text-[11px] font-bold uppercase text-[#475569] block mb-1">API Base URL</label>
                            <input type="text" name="bulksms_url" value="{{ $siteSettings['bulksms_url'] ?? 'http://bulksmsbd.net/api/smsapi' }}" class="w-full border border-[#cbd5e1] px-3 py-2 rounded-lg text-[13px] font-mono focus:border-[#0284c7] outline-none bg-white text-[#0f172a]">
                        </div>
                    </div>
                </div>

                <!-- Provider 2: MiM SMS -->
                <div class="bg-white/90 border p-6 rounded-2xl shadow-sm space-y-4">
                    <div class="border-b pb-4 flex items-center justify-between">
                        <h3 class="text-[15px] font-serif font-bold text-[#0f172a] uppercase flex items-center gap-2">
                            <i data-lucide="radio" class="w-4 h-4 text-emerald-600"></i> MiM SMS Gateway (mimsms.com)
                        </h3>
                        <label class="relative inline-flex items-center cursor-pointer">
                            <input type="checkbox" name="mimsms_enabled" value="1" class="sr-only peer" {{ ($siteSettings['mimsms_enabled'] ?? '0') === '1' ? 'checked' : '' }}>
                            <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-emerald-600"></div>
                        </label>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                        <div>
                            <label class="text-[11px] font-bold uppercase text-[#475569] block mb-1">API Key</label>
                            <input type="password" name="mimsms_api_key" value="{{ $siteSettings['mimsms_api_key'] ?? '' }}" placeholder="Enter MiM SMS API Key" class="w-full border border-[#cbd5e1] px-3 py-2 rounded-lg text-[13px] font-mono focus:border-[#0284c7] outline-none bg-white text-[#0f172a]">
                        </div>
                        <div>
                            <label class="text-[11px] font-bold uppercase text-[#475569] block mb-1">Sender ID</label>
                            <input type="text" name="mimsms_sender_id" value="{{ $siteSettings['mimsms_sender_id'] ?? '' }}" placeholder="e.g. 8809601xxxxxx" class="w-full border border-[#cbd5e1] px-3 py-2 rounded-lg text-[13px] focus:border-[#0284c7] outline-none bg-white text-[#0f172a]">
                        </div>
                        <div>
                            <label class="text-[11px] font-bold uppercase text-[#475569] block mb-1">Message Type</label>
                            <select name="mimsms_type" class="w-full border border-[#cbd5e1] px-3 py-2 rounded-lg text-[13px] font-bold focus:border-[#0284c7] outline-none bg-white text-[#0f172a]">
                                <option value="text" {{ ($siteSettings['mimsms_type'] ?? 'text') === 'text' ? 'selected' : '' }}>English (Text / 160 chars)</option>
                                <option value="unicode" {{ ($siteSettings['mimsms_type'] ?? 'text') === 'unicode' ? 'selected' : '' }}>Bangla (Unicode / 70 chars)</option>
                            </select>
                        </div>
                        <div>
                            <label class="text-[11px] font-bold uppercase text-[#475569] block mb-1">API URL</label>
                            <input type="text" name="mimsms_url" value="{{ $siteSettings['mimsms_url'] ?? 'https://api.mimsms.com/api/sendsms' }}" class="w-full border border-[#cbd5e1] px-3 py-2 rounded-lg text-[13px] font-mono focus:border-[#0284c7] outline-none bg-white text-[#0f172a]">
                        </div>
                    </div>
                </div>

                <!-- SMS Templates -->
                <div class="bg-white/90 border p-6 rounded-2xl shadow-sm space-y-4">
                    <h3 class="text-[15px] font-serif font-bold text-[#0f172a] uppercase border-l-4 border-[#0284c7] pl-3">
                        SMS Message Templates
                    </h3>
                    <p class="text-[12px] text-[#64748b]">Dynamic variables: <code>{order_id}</code>, <code>{customer_name}</code>, <code>{amount}</code>, <code>{tracking_code}</code>, <code>{courier_name}</code>, <code>{site_name}</code></p>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="text-[11px] font-bold uppercase text-[#475569] block mb-1">New Order SMS Template</label>
                            <textarea name="sms_template_new_order" rows="3" class="w-full border border-[#cbd5e1] p-3 rounded-lg text-[12px] font-mono focus:border-[#0284c7] outline-none bg-white text-[#0f172a]">{{ $siteSettings['sms_template_new_order'] ?? "Dear {customer_name}, your order #{order_id} for {amount} BDT has been received. Thank you for choosing {site_name}!" }}</textarea>
                        </div>
                        <div>
                            <label class="text-[11px] font-bold uppercase text-[#475569] block mb-1">Order Dispatched SMS Template</label>
                            <textarea name="sms_template_dispatch" rows="3" class="w-full border border-[#cbd5e1] p-3 rounded-lg text-[12px] font-mono focus:border-[#0284c7] outline-none bg-white text-[#0f172a]">{{ $siteSettings['sms_template_dispatch'] ?? "Dear {customer_name}, order #{order_id} has been dispatched via {courier_name}. Tracking: {tracking_code}." }}</textarea>
                        </div>
                    </div>
                </div>

                <!-- Test SMS Tool -->
                <div class="bg-white/90 border p-6 rounded-2xl shadow-sm space-y-4">
                    <h3 class="text-[15px] font-serif font-bold text-[#0f172a] uppercase flex items-center gap-2">
                        <i data-lucide="smartphone" class="w-4 h-4 text-[#0284c7]"></i> Live Test SMS Dispatcher
                    </h3>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-3">
                        <input type="text" id="test-sms-phone" placeholder="Recipient Phone (e.g. 01712345678)" class="border border-[#cbd5e1] px-3 py-2 rounded-lg text-[13px] font-mono focus:border-[#0284c7] outline-none bg-white text-[#0f172a]">
                        <input type="text" id="test-sms-message" placeholder="Test message body..." value="Test SMS from ReXxo Luxury Boutique" class="border border-[#cbd5e1] px-3 py-2 rounded-lg text-[13px] focus:border-[#0284c7] outline-none bg-white text-[#0f172a]">
                        <button type="button" onclick="sendTestSms()" class="bg-[#0284c7] hover:bg-[#0369a1] text-white px-4 py-2 rounded-lg text-[12px] font-bold uppercase tracking-wider flex items-center justify-center gap-2 transition-all cursor-pointer shadow-xs">
                            <i data-lucide="send" class="w-4 h-4"></i> Send Test SMS
                        </button>
                    </div>
                    <div id="test-sms-result" class="hidden text-[12px] font-mono p-3 rounded-lg"></div>
                </div>

                <!-- Save Action -->
                <div class="pt-4 border-t border-[#e2e8f0] flex items-center justify-between">
                    <span class="text-[12px] text-[#64748b]">SMS configurations take effect immediately upon saving.</span>
                    <button type="submit" class="bg-[#0f172a] hover:bg-[#0284c7] text-white px-8 py-3 rounded-xl text-[13px] font-bold uppercase tracking-wider transition-all flex items-center gap-2 shadow-sm cursor-pointer">
                        <i data-lucide="save" class="w-4 h-4"></i> Save SMS Settings
                    </button>
                </div>
            </form>
        </div>

        <!-- ================================================================= -->
        <!-- 3. SECTION: COURIER SETTING (Steadfast, Pathao, RedX & Dispatch) -->
        <!-- ================================================================= -->
        <div id="section-api_courier" class="section-content hidden space-y-6 animate-fade-in">
            <!-- Header -->
            <div class="bg-white/90 border border-[#38bdf8]/30 p-6 rounded-2xl shadow-sm">
                <div class="flex items-center justify-between flex-wrap gap-4">
                    <div>
                        <h2 class="text-[18px] font-serif font-bold text-[#0f172a] uppercase flex items-center gap-2">
                            <i data-lucide="truck" class="w-5 h-5 text-[#0284c7]"></i> Courier API Integration & Webhooks
                        </h2>
                        <p class="text-[12px] text-[#64748b] mt-1">Connect Steadfast, Pathao, RedX, Sundarban, eCourier, and Paperfly for 1-click parcel booking and live tracking.</p>
                    </div>
                    <a href="{{ url('/admin/courier') }}" class="inline-flex items-center gap-2 px-4 py-2 bg-[#0284c7] hover:bg-[#0369a1] text-white rounded-xl text-[12px] font-bold uppercase tracking-wider transition-all shadow-xs cursor-pointer">
                        <i data-lucide="external-link" class="w-4 h-4"></i> Open Dispatch Hub ↗
                    </a>
                </div>
            </div>

            <form action="{{ url('/admin/settings') }}" method="POST" class="space-y-6">
                @csrf

                <!-- Steadfast Courier -->
                <div class="bg-white/90 border p-6 rounded-2xl shadow-sm space-y-4">
                    <div class="border-b pb-4 flex items-center justify-between">
                        <div class="flex items-center gap-3">
                            <div class="w-9 h-9 rounded-xl bg-orange-50 border border-orange-200 flex items-center justify-center font-bold text-orange-600 text-[13px]">
                                SF
                            </div>
                            <div>
                                <h3 class="text-[15px] font-serif font-bold text-[#0f172a] uppercase">Steadfast Courier API</h3>
                                <p class="text-[11px] text-[#64748b]">Fastest parcel creation with automated invoice tracking.</p>
                            </div>
                        </div>
                        <label class="relative inline-flex items-center cursor-pointer">
                            <input type="checkbox" name="courier_steadfast_enabled" value="1" class="sr-only peer" {{ ($siteSettings['courier_steadfast_enabled'] ?? '1') === '1' ? 'checked' : '' }}>
                            <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-orange-500"></div>
                        </label>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div>
                            <label class="text-[11px] font-bold uppercase text-[#475569] block mb-1">API Key</label>
                            <input type="password" name="steadfast_api_key" value="{{ $siteSettings['steadfast_api_key'] ?? '' }}" placeholder="Enter Steadfast API Key" class="w-full border border-[#cbd5e1] px-3 py-2 rounded-lg text-[13px] font-mono focus:border-[#0284c7] outline-none bg-white text-[#0f172a]">
                        </div>
                        <div>
                            <label class="text-[11px] font-bold uppercase text-[#475569] block mb-1">Secret Key</label>
                            <input type="password" name="steadfast_secret_key" value="{{ $siteSettings['steadfast_secret_key'] ?? '' }}" placeholder="Enter Steadfast Secret Key" class="w-full border border-[#cbd5e1] px-3 py-2 rounded-lg text-[13px] font-mono focus:border-[#0284c7] outline-none bg-white text-[#0f172a]">
                        </div>
                        <div>
                            <label class="text-[11px] font-bold uppercase text-[#475569] block mb-1">Base API URL</label>
                            <input type="text" name="steadfast_base_url" value="{{ $siteSettings['steadfast_base_url'] ?? 'https://portal.steadfast.com.bd/api/v1' }}" class="w-full border border-[#cbd5e1] px-3 py-2 rounded-lg text-[13px] font-mono focus:border-[#0284c7] outline-none bg-white text-[#0f172a]">
                        </div>
                    </div>
                </div>

                <!-- Pathao Courier -->
                <div class="bg-white/90 border p-6 rounded-2xl shadow-sm space-y-4">
                    <div class="border-b pb-4 flex items-center justify-between">
                        <div class="flex items-center gap-3">
                            <div class="w-9 h-9 rounded-xl bg-red-50 border border-red-200 flex items-center justify-center font-bold text-red-600 text-[13px]">
                                PT
                            </div>
                            <div>
                                <h3 class="text-[15px] font-serif font-bold text-[#0f172a] uppercase">Pathao Courier API</h3>
                                <p class="text-[11px] text-[#64748b]">On-demand express urban dispatch & store pickup integration.</p>
                            </div>
                        </div>
                        <label class="relative inline-flex items-center cursor-pointer">
                            <input type="checkbox" name="courier_pathao_enabled" value="1" class="sr-only peer" {{ ($siteSettings['courier_pathao_enabled'] ?? '0') === '1' ? 'checked' : '' }}>
                            <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-red-500"></div>
                        </label>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                        <div>
                            <label class="text-[11px] font-bold uppercase text-[#475569] block mb-1">Client ID</label>
                            <input type="text" name="pathao_client_id" value="{{ $siteSettings['pathao_client_id'] ?? '' }}" placeholder="Pathao Client ID" class="w-full border border-[#cbd5e1] px-3 py-2 rounded-lg text-[13px] font-mono focus:border-[#0284c7] outline-none bg-white text-[#0f172a]">
                        </div>
                        <div>
                            <label class="text-[11px] font-bold uppercase text-[#475569] block mb-1">Client Secret</label>
                            <input type="password" name="pathao_client_secret" value="{{ $siteSettings['pathao_client_secret'] ?? '' }}" placeholder="••••••••" class="w-full border border-[#cbd5e1] px-3 py-2 rounded-lg text-[13px] font-mono focus:border-[#0284c7] outline-none bg-white text-[#0f172a]">
                        </div>
                        <div>
                            <label class="text-[11px] font-bold uppercase text-[#475569] block mb-1">Username / Email</label>
                            <input type="text" name="pathao_username" value="{{ $siteSettings['pathao_username'] ?? '' }}" placeholder="account@domain.com" class="w-full border border-[#cbd5e1] px-3 py-2 rounded-lg text-[13px] focus:border-[#0284c7] outline-none bg-white text-[#0f172a]">
                        </div>
                        <div>
                            <label class="text-[11px] font-bold uppercase text-[#475569] block mb-1">Password</label>
                            <input type="password" name="pathao_password" value="{{ $siteSettings['pathao_password'] ?? '' }}" placeholder="••••••••" class="w-full border border-[#cbd5e1] px-3 py-2 rounded-lg text-[13px] focus:border-[#0284c7] outline-none bg-white text-[#0f172a]">
                        </div>
                    </div>
                </div>

                <!-- RedX Courier -->
                <div class="bg-white/90 border p-6 rounded-2xl shadow-sm space-y-4">
                    <div class="border-b pb-4 flex items-center justify-between">
                        <div class="flex items-center gap-3">
                            <div class="w-9 h-9 rounded-xl bg-rose-50 border border-rose-200 flex items-center justify-center font-bold text-rose-600 text-[13px]">
                                RX
                            </div>
                            <div>
                                <h3 class="text-[15px] font-serif font-bold text-[#0f172a] uppercase">RedX Courier API</h3>
                                <p class="text-[11px] text-[#64748b]">Nationwide logistics coverage with automated parcel API dispatch.</p>
                            </div>
                        </div>
                        <label class="relative inline-flex items-center cursor-pointer">
                            <input type="checkbox" name="courier_redx_enabled" value="1" class="sr-only peer" {{ ($siteSettings['courier_redx_enabled'] ?? '0') === '1' ? 'checked' : '' }}>
                            <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-rose-500"></div>
                        </label>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="text-[11px] font-bold uppercase text-[#475569] block mb-1">RedX Access Token</label>
                            <input type="password" name="redx_access_token" value="{{ $siteSettings['redx_access_token'] ?? '' }}" placeholder="Enter RedX Bearer Token" class="w-full border border-[#cbd5e1] px-3 py-2 rounded-lg text-[13px] font-mono focus:border-[#0284c7] outline-none bg-white text-[#0f172a]">
                        </div>
                        <div>
                            <label class="text-[11px] font-bold uppercase text-[#475569] block mb-1">Base API URL</label>
                            <input type="text" name="redx_base_url" value="{{ $siteSettings['redx_base_url'] ?? 'https://openapi.redx.com.bd/v1.0.0-beta' }}" class="w-full border border-[#cbd5e1] px-3 py-2 rounded-lg text-[13px] font-mono focus:border-[#0284c7] outline-none bg-white text-[#0f172a]">
                        </div>
                    </div>
                </div>

                <!-- Save Action -->
                <div class="pt-4 border-t border-[#e2e8f0] flex items-center justify-between">
                    <span class="text-[12px] text-[#64748b]">Courier credentials securely saved.</span>
                    <button type="submit" class="bg-[#0f172a] hover:bg-[#0284c7] text-white px-8 py-3 rounded-xl text-[13px] font-bold uppercase tracking-wider transition-all flex items-center gap-2 shadow-sm cursor-pointer">
                        <i data-lucide="save" class="w-4 h-4"></i> Save Courier Settings
                    </button>
                </div>
            </form>
        </div>

        <!-- ================================================================= -->
        <!-- 4. SECTION: SEO META (Title, Description, Social Image & SERP)   -->
        <!-- ================================================================= -->
        <div id="section-seo_meta" class="section-content hidden space-y-6 animate-fade-in">
            <!-- Header -->
            <div class="bg-white/90 border border-[#38bdf8]/30 p-6 rounded-2xl shadow-sm">
                <div class="flex items-center justify-between flex-wrap gap-4">
                    <div>
                        <h2 class="text-[18px] font-serif font-bold text-[#0f172a] uppercase flex items-center gap-2">
                            <i data-lucide="search" class="w-5 h-5 text-[#0284c7]"></i> Search Engine Meta & Social Share
                        </h2>
                        <p class="text-[12px] text-[#64748b] mt-1">Optimize your search engine snippet, meta titles, descriptions, and OpenGraph social preview images.</p>
                    </div>
                    <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg text-[11px] font-bold bg-emerald-50 text-emerald-700 border border-emerald-200">
                        <i data-lucide="sparkles" class="w-3.5 h-3.5"></i> Google Search Optimized
                    </span>
                </div>
            </div>

            <!-- SERP Live Preview Simulation -->
            <div class="bg-white/90 border p-6 rounded-2xl shadow-sm space-y-3">
                <h3 class="text-[12px] font-bold uppercase text-[#64748b] tracking-wider">Google Search Result Preview</h3>
                <div class="bg-white border rounded-xl p-5 max-w-2xl shadow-xs space-y-1">
                    <div class="flex items-center gap-2 text-[12px] text-[#202124]">
                        <div class="w-4 h-4 rounded-full bg-slate-100 border flex items-center justify-center text-[9px] font-bold text-[#0284c7]">R</div>
                        <span class="text-[#202124] font-medium">{{ request()->getHost() }}</span>
                        <span class="text-[#5f6368]">› store › artisanal</span>
                    </div>
                    <h4 id="serp-preview-title" class="text-[18px] font-medium text-[#1a0dab] hover:underline cursor-pointer leading-tight">
                        {{ $siteSettings['seo_meta_title'] ?? ($siteSettings['site_name'] ?? 'ReXxo Bd') . ' | Haute Parfumerie & Artisanal Perfumes' }}
                    </h4>
                    <p id="serp-preview-desc" class="text-[13px] text-[#4d5156] leading-relaxed">
                        {{ $siteSettings['seo_meta_description'] ?? 'Discover handcrafted luxury perfume extraits infused with pure artisanal oud and bespoke essences.' }}
                    </p>
                </div>
            </div>

            <form action="{{ url('/admin/settings') }}" method="POST" class="space-y-6">
                @csrf

                <div class="bg-white/90 border p-6 rounded-2xl shadow-sm space-y-5">
                    <h3 class="text-[15px] font-serif font-bold text-[#0f172a] uppercase border-l-4 border-[#0284c7] pl-3">
                        Meta Content Tags
                    </h3>

                    <div>
                        <label class="text-[11px] font-bold uppercase text-[#475569] block mb-1">SEO Meta Title <span class="text-rose-500">*</span></label>
                        <input type="text" name="seo_meta_title" id="input_seo_title" value="{{ $siteSettings['seo_meta_title'] ?? '' }}" placeholder="e.g. ReXxo Bd | Luxury Artisanal Perfumes & Extraits" class="w-full border border-[#cbd5e1] px-4 py-2.5 rounded-lg text-[13px] font-bold focus:border-[#0284c7] outline-none bg-white text-[#0f172a]" oninput="document.getElementById('serp-preview-title').textContent = this.value || 'ReXxo Bd | Haute Parfumerie'">
                        <p class="text-[11px] text-[#64748b] mt-1">Recommended length: 50–60 characters.</p>
                    </div>

                    <div>
                        <label class="text-[11px] font-bold uppercase text-[#475569] block mb-1">SEO Meta Description <span class="text-rose-500">*</span></label>
                        <textarea name="seo_meta_description" id="input_seo_desc" rows="3" placeholder="Crafted in limited flacons using centuries-old French perfumery and Arabian oud..." class="w-full border border-[#cbd5e1] p-3 rounded-lg text-[13px] focus:border-[#0284c7] outline-none bg-white text-[#0f172a]" oninput="document.getElementById('serp-preview-desc').textContent = this.value || 'Discover handcrafted luxury perfume extraits...' ">{{ $siteSettings['seo_meta_description'] ?? '' }}</textarea>
                        <p class="text-[11px] text-[#64748b] mt-1">Recommended length: 140–160 characters for optimal SERP snippets.</p>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="text-[11px] font-bold uppercase text-[#475569] block mb-1">Meta Keywords (Comma separated)</label>
                            <input type="text" name="seo_meta_keywords" value="{{ $siteSettings['seo_meta_keywords'] ?? 'luxury perfume, artisanal extrait, oud fragrance, niche perfumery, bespoke flacon, bangladesh perfume' }}" placeholder="perfume, extrait, oud..." class="w-full border border-[#cbd5e1] px-3 py-2 rounded-lg text-[13px] focus:border-[#0284c7] outline-none bg-white text-[#0f172a]">
                        </div>
                        <div>
                            <label class="text-[11px] font-bold uppercase text-[#475569] block mb-1">Canonical Base URL</label>
                            <input type="text" name="seo_canonical_url" value="{{ $siteSettings['seo_canonical_url'] ?? url('/') }}" placeholder="https://yourdomain.com" class="w-full border border-[#cbd5e1] px-3 py-2 rounded-lg text-[13px] font-mono focus:border-[#0284c7] outline-none bg-white text-[#0f172a]">
                        </div>
                    </div>

                    <div>
                        <label class="text-[11px] font-bold uppercase text-[#475569] block mb-1">OpenGraph Social Share Image URL (og:image)</label>
                        <input type="text" name="seo_og_image" value="{{ $siteSettings['seo_og_image'] ?? '' }}" placeholder="https://yourdomain.com/og-banner.jpg" class="w-full border border-[#cbd5e1] px-3 py-2 rounded-lg text-[13px] font-mono focus:border-[#0284c7] outline-none bg-white text-[#0f172a]">
                        <p class="text-[11px] text-[#64748b] mt-1">Displayed when sharing your storefront link on Facebook, WhatsApp, Twitter/X, and LinkedIn (Recommended 1200×630 px).</p>
                    </div>
                </div>

                <!-- Save Action -->
                <div class="pt-4 border-t border-[#e2e8f0] flex items-center justify-between">
                    <span class="text-[12px] text-[#64748b]">Meta changes update in page head tags instantly.</span>
                    <button type="submit" class="bg-[#0f172a] hover:bg-[#0284c7] text-white px-8 py-3 rounded-xl text-[13px] font-bold uppercase tracking-wider transition-all flex items-center gap-2 shadow-sm cursor-pointer">
                        <i data-lucide="save" class="w-4 h-4"></i> Save Meta Settings
                    </button>
                </div>
            </form>
        </div>

        <!-- ================================================================= -->
        <!-- 5. SECTION: MARKETING & PIXELS (Meta, Google, TikTok, Clarity)   -->
        <!-- ================================================================= -->
        <div id="section-seo_marketing" class="section-content hidden space-y-6 animate-fade-in">
            <!-- Header -->
            <div class="bg-white/90 border border-[#38bdf8]/30 p-6 rounded-2xl shadow-sm">
                <div class="flex items-center justify-between flex-wrap gap-4">
                    <div>
                        <h2 class="text-[18px] font-serif font-bold text-[#0f172a] uppercase flex items-center gap-2">
                            <i data-lucide="bar-chart-2" class="w-5 h-5 text-[#0284c7]"></i> Marketing Pixels, Conversions API & Verification
                        </h2>
                        <p class="text-[12px] text-[#64748b] mt-1">Integrate Meta / Facebook Pixel, CAPI Token, Google Analytics 4, GTM, TikTok Pixel, Pinterest Tag, and Search Console verification codes.</p>
                    </div>
                    <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg text-[11px] font-bold bg-purple-50 text-purple-700 border border-purple-200">
                        <i data-lucide="trending-up" class="w-3.5 h-3.5"></i> Conversion Tracking Active
                    </span>
                </div>
            </div>

            <form action="{{ url('/admin/settings') }}" method="POST" class="space-y-6">
                @csrf

                <!-- Meta / Facebook Suite -->
                <div class="bg-white/90 border p-6 rounded-2xl shadow-sm space-y-4">
                    <div class="border-b pb-4 flex items-center justify-between">
                        <div class="flex items-center gap-3">
                            <div class="w-9 h-9 rounded-xl bg-blue-50 border border-blue-200 flex items-center justify-center font-bold text-blue-600 text-[14px]">
                                f
                            </div>
                            <div>
                                <h3 class="text-[15px] font-serif font-bold text-[#0f172a] uppercase">Meta / Facebook Pixel & Conversions API (CAPI)</h3>
                                <p class="text-[11px] text-[#64748b]">Track PageView, ViewContent, AddToCart, InitiateCheckout & Purchase events.</p>
                            </div>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="text-[11px] font-bold uppercase text-[#475569] block mb-1">Facebook Pixel ID</label>
                            <input type="text" name="pixel_facebook" value="{{ $siteSettings['pixel_facebook'] ?? '' }}" placeholder="e.g. 123456789012345" class="w-full border border-[#cbd5e1] px-3 py-2 rounded-lg text-[13px] font-mono focus:border-[#0284c7] outline-none bg-white text-[#0f172a]">
                        </div>
                        <div>
                            <label class="text-[11px] font-bold uppercase text-[#475569] block mb-1">Meta Conversions API (CAPI) Token</label>
                            <input type="password" name="meta_capi_token" value="{{ $siteSettings['meta_capi_token'] ?? '' }}" placeholder="EAAG..." class="w-full border border-[#cbd5e1] px-3 py-2 rounded-lg text-[13px] font-mono focus:border-[#0284c7] outline-none bg-white text-[#0f172a]">
                        </div>
                        <div>
                            <label class="text-[11px] font-bold uppercase text-[#475569] block mb-1">Meta Catalog ID</label>
                            <input type="text" name="meta_catalog_id" value="{{ $siteSettings['meta_catalog_id'] ?? '' }}" placeholder="e.g. 9876543210" class="w-full border border-[#cbd5e1] px-3 py-2 rounded-lg text-[13px] font-mono focus:border-[#0284c7] outline-none bg-white text-[#0f172a]">
                        </div>
                        <div>
                            <label class="text-[11px] font-bold uppercase text-[#475569] block mb-1">Facebook Domain Verification Code</label>
                            <input type="text" name="meta_domain_verification" value="{{ $siteSettings['meta_domain_verification'] ?? '' }}" placeholder="e.g. abcdef123456789" class="w-full border border-[#cbd5e1] px-3 py-2 rounded-lg text-[13px] font-mono focus:border-[#0284c7] outline-none bg-white text-[#0f172a]">
                        </div>
                    </div>
                </div>

                <!-- Google Ecosystem -->
                <div class="bg-white/90 border p-6 rounded-2xl shadow-sm space-y-4">
                    <div class="border-b pb-4 flex items-center justify-between">
                        <div class="flex items-center gap-3">
                            <div class="w-9 h-9 rounded-xl bg-amber-50 border border-amber-200 flex items-center justify-center font-bold text-amber-600 text-[14px]">
                                G
                            </div>
                            <div>
                                <h3 class="text-[15px] font-serif font-bold text-[#0f172a] uppercase">Google Analytics, GTM & Webmaster Verification</h3>
                                <p class="text-[11px] text-[#64748b]">Connect Google Analytics 4, Tag Manager Container, Search Console verification, and Ads.</p>
                            </div>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div>
                            <label class="text-[11px] font-bold uppercase text-[#475569] block mb-1">Google Analytics 4 ID (GA4)</label>
                            <input type="text" name="pixel_google" value="{{ $siteSettings['pixel_google'] ?? '' }}" placeholder="e.g. G-XXXXXXXXXX" class="w-full border border-[#cbd5e1] px-3 py-2 rounded-lg text-[13px] font-mono focus:border-[#0284c7] outline-none bg-white text-[#0f172a]">
                        </div>
                        <div>
                            <label class="text-[11px] font-bold uppercase text-[#475569] block mb-1">Google Tag Manager (GTM) ID</label>
                            <input type="text" name="pixel_gtm" value="{{ $siteSettings['pixel_gtm'] ?? '' }}" placeholder="e.g. GTM-XXXXXXX" class="w-full border border-[#cbd5e1] px-3 py-2 rounded-lg text-[13px] font-mono focus:border-[#0284c7] outline-none bg-white text-[#0f172a]">
                        </div>
                        <div>
                            <label class="text-[11px] font-bold uppercase text-[#475569] block mb-1">Google Search Console Verification</label>
                            <input type="text" name="google_site_verification" value="{{ $siteSettings['google_site_verification'] ?? '' }}" placeholder="e.g. xYzAbC123..." class="w-full border border-[#cbd5e1] px-3 py-2 rounded-lg text-[13px] font-mono focus:border-[#0284c7] outline-none bg-white text-[#0f172a]">
                        </div>
                        <div>
                            <label class="text-[11px] font-bold uppercase text-[#475569] block mb-1">Google Ads Conversion ID</label>
                            <input type="text" name="google_ads_conversion_id" value="{{ $siteSettings['google_ads_conversion_id'] ?? '' }}" placeholder="e.g. AW-123456789" class="w-full border border-[#cbd5e1] px-3 py-2 rounded-lg text-[13px] font-mono focus:border-[#0284c7] outline-none bg-white text-[#0f172a]">
                        </div>
                        <div>
                            <label class="text-[11px] font-bold uppercase text-[#475569] block mb-1">Bing Webmaster Verification</label>
                            <input type="text" name="bing_site_verification" value="{{ $siteSettings['bing_site_verification'] ?? '' }}" placeholder="e.g. BING_AUTH_CODE" class="w-full border border-[#cbd5e1] px-3 py-2 rounded-lg text-[13px] font-mono focus:border-[#0284c7] outline-none bg-white text-[#0f172a]">
                        </div>
                    </div>
                </div>

                <!-- Multi-Channel Pixels (TikTok, Pinterest, Microsoft Clarity) -->
                <div class="bg-white/90 border p-6 rounded-2xl shadow-sm space-y-4">
                    <div class="border-b pb-4 flex items-center justify-between">
                        <h3 class="text-[15px] font-serif font-bold text-[#0f172a] uppercase flex items-center gap-2">
                            <i data-lucide="share-2" class="w-4 h-4 text-[#0284c7]"></i> Additional Social & Heatmap Tracking
                        </h3>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div>
                            <label class="text-[11px] font-bold uppercase text-[#475569] block mb-1">TikTok Pixel ID</label>
                            <input type="text" name="pixel_tiktok" value="{{ $siteSettings['pixel_tiktok'] ?? '' }}" placeholder="e.g. CXXXXXXXXXXXX" class="w-full border border-[#cbd5e1] px-3 py-2 rounded-lg text-[13px] font-mono focus:border-[#0284c7] outline-none bg-white text-[#0f172a]">
                        </div>
                        <div>
                            <label class="text-[11px] font-bold uppercase text-[#475569] block mb-1">Pinterest Tag ID</label>
                            <input type="text" name="pixel_pinterest" value="{{ $siteSettings['pixel_pinterest'] ?? '' }}" placeholder="e.g. 2612345678901" class="w-full border border-[#cbd5e1] px-3 py-2 rounded-lg text-[13px] font-mono focus:border-[#0284c7] outline-none bg-white text-[#0f172a]">
                        </div>
                        <div>
                            <label class="text-[11px] font-bold uppercase text-[#475569] block mb-1">Microsoft Clarity Tracking ID</label>
                            <input type="text" name="pixel_clarity" value="{{ $siteSettings['pixel_clarity'] ?? '' }}" placeholder="e.g. k7xxxxxxxx" class="w-full border border-[#cbd5e1] px-3 py-2 rounded-lg text-[13px] font-mono focus:border-[#0284c7] outline-none bg-white text-[#0f172a]">
                        </div>
                    </div>
                </div>

                <!-- Custom Script Injector (Head / Body) -->
                <div class="bg-white/90 border p-6 rounded-2xl shadow-sm space-y-4">
                    <div class="border-b pb-4 flex items-center justify-between">
                        <h3 class="text-[15px] font-serif font-bold text-[#0f172a] uppercase flex items-center gap-2">
                            <i data-lucide="code" class="w-4 h-4 text-[#0284c7]"></i> Custom HTML / JavaScript Code Injection
                        </h3>
                        <span class="text-[11px] text-[#64748b]">Advanced: Injected directly into storefront HTML</span>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="text-[11px] font-bold uppercase text-[#475569] block mb-1">Header Scripts (Injected into &lt;head&gt;)</label>
                            <textarea name="custom_head_scripts" rows="4" placeholder="&lt;!-- Custom Header Scripts --&gt;" class="w-full border border-[#cbd5e1] p-3 rounded-lg text-[12px] font-mono focus:border-[#0284c7] outline-none bg-white text-[#0f172a]">{{ $siteSettings['custom_head_scripts'] ?? '' }}</textarea>
                        </div>
                        <div>
                            <label class="text-[11px] font-bold uppercase text-[#475569] block mb-1">Footer Scripts (Injected before &lt;/body&gt;)</label>
                            <textarea name="custom_body_scripts" rows="4" placeholder="&lt;!-- Custom Footer Scripts / Live Chat / Pixels --&gt;" class="w-full border border-[#cbd5e1] p-3 rounded-lg text-[12px] font-mono focus:border-[#0284c7] outline-none bg-white text-[#0f172a]">{{ $siteSettings['custom_body_scripts'] ?? '' }}</textarea>
                        </div>
                    </div>
                </div>

                <!-- Save Action -->
                <div class="pt-4 border-t border-[#e2e8f0] flex items-center justify-between">
                    <span class="text-[12px] text-[#64748b]">All tracking pixels and scripts render dynamically across the storefront.</span>
                    <button type="submit" class="bg-[#0f172a] hover:bg-[#0284c7] text-white px-8 py-3 rounded-xl text-[13px] font-bold uppercase tracking-wider transition-all flex items-center gap-2 shadow-sm cursor-pointer">
                        <i data-lucide="save" class="w-4 h-4"></i> Save Marketing & Pixel Settings
                    </button>
                </div>
            </form>
        </div>

        <!-- ================================================================= -->
        <!-- 6. SECTION: SITEMAP GENERATOR (sitemap.xml Builder)              -->
        <!-- ================================================================= -->
        <div id="section-sitemap" class="section-content hidden space-y-6 animate-fade-in">
            <!-- Header -->
            <div class="bg-white/90 border border-[#38bdf8]/30 p-6 rounded-2xl shadow-sm">
                <div class="flex items-center justify-between flex-wrap gap-4">
                    <div>
                        <h2 class="text-[18px] font-serif font-bold text-[#0f172a] uppercase flex items-center gap-2">
                            <i data-lucide="map" class="w-5 h-5 text-[#0284c7]"></i> Sitemap.xml Generator & Indexing
                        </h2>
                        <p class="text-[12px] text-[#64748b] mt-1">Automatically scan all products, collections, and landing pages to generate a clean Google XML Sitemap.</p>
                    </div>
                    <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg text-[11px] font-bold bg-emerald-50 text-emerald-700 border border-emerald-200">
                        <i data-lucide="globe" class="w-3.5 h-3.5"></i> Google & Bing Index Ready
                    </span>
                </div>
            </div>

            <!-- Sitemap Status Card -->
            <div class="bg-white/90 border rounded-2xl p-6 shadow-sm space-y-5">
                <div class="flex items-center justify-between flex-wrap gap-3">
                    <div class="flex items-center gap-3">
                        <div class="w-12 h-12 rounded-xl bg-sky-50 border border-sky-200 flex items-center justify-center">
                            <i data-lucide="file-code-2" class="w-6 h-6 text-[#0284c7]"></i>
                        </div>
                        <div>
                            <h3 class="text-[16px] font-serif font-bold text-[#0f172a]">sitemap.xml</h3>
                            <a href="{{ url('/sitemap.xml') }}" target="_blank" class="text-[12px] text-[#0284c7] hover:underline font-mono">{{ url('/sitemap.xml') }} ↗</a>
                        </div>
                    </div>
                    <span id="sitemap-status-badge" class="bg-slate-50 text-[#94a3b8] border border-slate-200 px-3 py-1 rounded-full text-[11px] font-bold uppercase">Checking...</span>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 bg-[#f8fafc] rounded-xl border p-4">
                    <div class="space-y-1">
                        <span class="text-[11px] text-[#64748b] font-bold uppercase block">Last Generated Timestamp</span>
                        <span id="sitemap-last-updated" class="font-mono text-[13px] font-bold text-[#0f172a]">—</span>
                    </div>
                    <div class="space-y-1">
                        <span class="text-[11px] text-[#64748b] font-bold uppercase block">Indexed URLs Count</span>
                        <span id="sitemap-entries" class="font-mono text-[14px] font-bold text-[#0284c7]">—</span>
                    </div>
                </div>

                <button type="button" onclick="generateSitemap()" id="btn-gen-sitemap" class="w-full py-3.5 bg-[#0284c7] hover:bg-[#0369a1] text-white rounded-xl text-[13px] font-bold uppercase tracking-wider flex items-center justify-center gap-2 transition-all cursor-pointer shadow-xs">
                    <i data-lucide="refresh-cw" class="w-4 h-4"></i> Generate & Save sitemap.xml
                </button>
                <div id="sitemap-result" class="hidden text-[12px] font-mono p-4 rounded-xl"></div>
            </div>

            <!-- Server Cron Job Tip -->
            <div class="bg-amber-50/70 border border-amber-200 p-5 rounded-2xl space-y-2">
                <div class="flex items-center gap-2 text-amber-800 font-bold text-[13px]">
                    <i data-lucide="clock" class="w-4 h-4 text-amber-600"></i> Automated Daily Auto-Sync (Cron Job)
                </div>
                <p class="text-[12px] text-amber-700">
                    To auto-update sitemap daily whenever new products are added, configure your server crontab to run:
                </p>
                <code class="block bg-amber-100/80 p-2.5 rounded-lg text-[11px] font-mono text-amber-900 border border-amber-300">
                    * * * * * cd {{ base_path() }} && php artisan schedule:run >> /dev/null 2>&1
                </code>
            </div>
        </div>

        <!-- ================================================================= -->
        <!-- 7. SECTION: ROBOTS.TXT MANAGER                                   -->
        <!-- ================================================================= -->
        <div id="section-robots" class="section-content hidden space-y-6 animate-fade-in">
            <!-- Header -->
            <div class="bg-white/90 border border-[#38bdf8]/30 p-6 rounded-2xl shadow-sm">
                <div class="flex items-center justify-between flex-wrap gap-4">
                    <div>
                        <h2 class="text-[18px] font-serif font-bold text-[#0f172a] uppercase flex items-center gap-2">
                            <i data-lucide="bot" class="w-5 h-5 text-violet-600"></i> Robots.txt Web Crawler Directives
                        </h2>
                        <p class="text-[12px] text-[#64748b] mt-1">Manage web crawler rules for Googlebot, Bingbot, and protect admin endpoints from unwanted crawling.</p>
                    </div>
                    <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg text-[11px] font-bold bg-violet-50 text-violet-700 border border-violet-200">
                        <i data-lucide="shield" class="w-3.5 h-3.5"></i> Crawler Protection Active
                    </span>
                </div>
            </div>

            <!-- Robots.txt Status Card -->
            <div class="bg-white/90 border rounded-2xl p-6 shadow-sm space-y-5">
                <div class="flex items-center justify-between flex-wrap gap-3">
                    <div class="flex items-center gap-3">
                        <div class="w-12 h-12 rounded-xl bg-violet-50 border border-violet-200 flex items-center justify-center">
                            <i data-lucide="bot" class="w-6 h-6 text-violet-600"></i>
                        </div>
                        <div>
                            <h3 class="text-[16px] font-serif font-bold text-[#0f172a]">robots.txt</h3>
                            <a href="{{ url('/robots.txt') }}" target="_blank" class="text-[12px] text-violet-600 hover:underline font-mono">{{ url('/robots.txt') }} ↗</a>
                        </div>
                    </div>
                    <span id="robots-status-badge" class="bg-slate-50 text-[#94a3b8] border border-slate-200 px-3 py-1 rounded-full text-[11px] font-bold uppercase">Checking...</span>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 bg-[#f8fafc] rounded-xl border p-4">
                    <div class="space-y-1">
                        <span class="text-[11px] text-[#64748b] font-bold uppercase block">Last Generated Timestamp</span>
                        <span id="robots-last-updated" class="font-mono text-[13px] font-bold text-[#0f172a]">—</span>
                    </div>
                    <div class="space-y-1">
                        <span class="text-[11px] text-[#64748b] font-bold uppercase block">Sitemap Reference</span>
                        <span class="font-mono text-[13px] font-bold text-emerald-700">Included ✔</span>
                    </div>
                </div>

                <button type="button" onclick="generateRobots()" id="btn-gen-robots" class="w-full py-3.5 bg-violet-600 hover:bg-violet-700 text-white rounded-xl text-[13px] font-bold uppercase tracking-wider flex items-center justify-center gap-2 transition-all cursor-pointer shadow-xs">
                    <i data-lucide="refresh-cw" class="w-4 h-4"></i> Generate & Save robots.txt
                </button>
                <div id="robots-result" class="hidden text-[12px] font-mono p-4 rounded-xl"></div>
            </div>
        </div>

        <!-- ================================================================= -->
        <!-- 8. SECTION: PING SEARCH ENGINES (Google / Bing)                   -->
        <!-- ================================================================= -->
        <div id="section-seo_ping" class="section-content hidden space-y-6 animate-fade-in">
            <!-- Header -->
            <div class="bg-white/90 border border-[#38bdf8]/30 p-6 rounded-2xl shadow-sm">
                <div class="flex items-center justify-between flex-wrap gap-4">
                    <div>
                        <h2 class="text-[18px] font-serif font-bold text-[#0f172a] uppercase flex items-center gap-2">
                            <i data-lucide="satellite" class="w-5 h-5 text-[#0284c7]"></i> Instant Search Engine Index Pinger
                        </h2>
                        <p class="text-[12px] text-[#64748b] mt-1">Notify Google Search Console and Bing Webmaster Tools immediately after updating products or content.</p>
                    </div>
                    <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg text-[11px] font-bold bg-sky-50 text-[#0284c7] border border-sky-200">
                        <i data-lucide="zap" class="w-3.5 h-3.5"></i> One-Click Index Notification
                    </span>
                </div>
            </div>

            <!-- Ping Action Panel -->
            <div class="bg-white/90 border rounded-2xl p-6 shadow-sm space-y-5">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <!-- Google -->
                    <div class="bg-[#f8fafc] border rounded-xl p-4 flex items-center gap-4">
                        <div class="w-12 h-12 rounded-xl bg-white border shadow-xs flex items-center justify-center shrink-0">
                            <svg viewBox="0 0 48 48" class="w-7 h-7"><path fill="#EA4335" d="M24 9.5c3.54 0 6.71 1.22 9.21 3.6l6.85-6.85C35.9 2.38 30.47 0 24 0 14.62 0 6.51 5.38 2.56 13.22l7.98 6.19C12.43 13.72 17.74 9.5 24 9.5z"/><path fill="#4285F4" d="M46.98 24.55c0-1.57-.15-3.09-.38-4.55H24v9.02h12.94c-.58 2.96-2.26 5.48-4.78 7.18l7.73 6c4.51-4.18 7.09-10.36 7.09-17.65z"/><path fill="#FBBC05" d="M10.53 28.59c-.48-1.45-.76-2.99-.76-4.59s.27-3.14.76-4.59l-7.98-6.19C.92 16.46 0 20.12 0 24c0 3.88.92 7.54 2.56 10.78l7.97-6.19z"/><path fill="#34A853" d="M24 48c6.48 0 11.93-2.13 15.89-5.81l-7.73-6c-2.18 1.48-4.97 2.31-8.16 2.31-6.26 0-11.57-4.22-13.47-9.91l-7.98 6.19C6.51 42.62 14.62 48 24 48z"/></svg>
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="text-[13px] font-bold text-[#0f172a]">Google Search Console</p>
                            <p class="text-[11px] text-[#64748b] truncate">google.com/ping?sitemap={{ url('/sitemap.xml') }}</p>
                        </div>
                        <span id="ping-google-badge" class="text-[10px] font-bold px-2.5 py-1 rounded-full bg-slate-100 text-[#94a3b8] border border-slate-200 shrink-0">Idle</span>
                    </div>

                    <!-- Bing -->
                    <div class="bg-[#f8fafc] border rounded-xl p-4 flex items-center gap-4">
                        <div class="w-12 h-12 rounded-xl bg-white border shadow-xs flex items-center justify-center shrink-0">
                            <svg viewBox="0 0 48 48" class="w-7 h-7"><path d="M10 4l8 23.5-4.5 2.7L26 39l12-7-7-4.4 2-11.6z" fill="#0078D4"/><path d="M10 4l8 23.5 5-3L10 4z" fill="#004C97"/></svg>
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="text-[13px] font-bold text-[#0f172a]">Bing Webmaster Tools</p>
                            <p class="text-[11px] text-[#64748b] truncate">bing.com/ping?sitemap={{ url('/sitemap.xml') }}</p>
                        </div>
                        <span id="ping-bing-badge" class="text-[10px] font-bold px-2.5 py-1 rounded-full bg-slate-100 text-[#94a3b8] border border-slate-200 shrink-0">Idle</span>
                    </div>
                </div>

                <button type="button" onclick="pingSeo()" id="btn-ping" class="w-full py-4 bg-gradient-to-r from-[#0284c7] to-emerald-600 hover:from-[#0369a1] hover:to-emerald-700 text-white rounded-xl text-[13px] font-bold uppercase tracking-wider flex items-center justify-center gap-2 transition-all cursor-pointer shadow-md">
                    <i data-lucide="send" class="w-4 h-4"></i> 🚀 Ping Google & Bing Now
                </button>

                <div id="ping-result" class="hidden text-[12px] font-mono p-4 rounded-lg bg-[#f8fafc] border text-[#475569]"></div>
            </div>
        </div>

        <!-- SECTION: MENU BUILDER -->
        <div id="section-menu" class="section-content hidden bg-white/90 border p-6 rounded-2xl space-y-6">
            <div class="flex justify-between items-center border-b pb-4">
                <div>
                    <h2 class="text-[18px] font-serif font-bold text-[#0f172a] uppercase flex items-center gap-2">
                        <i data-lucide="sliders" class="w-5 h-5 text-[#0284c7]"></i> Storefront Menu Builder
                    </h2>
                    <p class="text-[12px] text-[#64748b] mt-1">Manage your storefront navigation menu. Changes sync instantly to the frontend header.</p>
                </div>
                <button type="button" onclick="document.getElementById('addMenuForm').classList.toggle('hidden')" class="bg-[#0284c7] text-white px-4 py-2.5 rounded-xl text-[12px] font-bold uppercase tracking-wider flex items-center gap-2 hover:bg-[#0369a1] transition-colors cursor-pointer">
                    <i data-lucide="plus" class="w-4 h-4"></i> Add Menu Item
                </button>
            </div>

            <!-- ADD NEW MENU ITEM FORM (Hidden by default) -->
            <div id="addMenuForm" class="hidden bg-gradient-to-r from-sky-50 to-white border border-[#38bdf8]/40 p-5 rounded-xl space-y-4">
                <h3 class="text-[14px] font-bold uppercase text-[#0f172a] flex items-center gap-2 border-b pb-2">
                    <i data-lucide="plus-circle" class="w-4 h-4 text-[#0284c7]"></i> Create New Menu Item
                </h3>
                <form action="/admin/menus" method="POST" class="grid grid-cols-1 sm:grid-cols-12 gap-4 items-end">
                    @csrf
                    <div class="sm:col-span-4">
                        <label class="text-[11px] font-bold uppercase text-[#475569] block mb-1">Menu Label *</label>
                        <input type="text" name="label" required placeholder="e.g. New Arrivals" class="w-full border border-[#cbd5e1] px-3 py-2.5 rounded-xl text-[13px] font-bold focus:border-[#0284c7] focus:ring-2 focus:ring-[#0284c7]/20 outline-none transition-all">
                    </div>
                    <div class="sm:col-span-5">
                        <label class="text-[11px] font-bold uppercase text-[#475569] block mb-1">Link URL *</label>
                        <input type="text" name="url" required placeholder="e.g. /perfumes?category=new" class="w-full border border-[#cbd5e1] px-3 py-2.5 rounded-xl text-[13px] font-bold focus:border-[#0284c7] focus:ring-2 focus:ring-[#0284c7]/20 outline-none transition-all">
                    </div>
                    <div class="sm:col-span-3 flex gap-2">
                        <button type="submit" class="flex-1 bg-emerald-600 hover:bg-emerald-700 text-white px-4 py-2.5 rounded-xl text-[12px] font-bold uppercase tracking-wider transition-colors cursor-pointer">
                            ✓ Save
                        </button>
                        <button type="button" onclick="document.getElementById('addMenuForm').classList.add('hidden')" class="px-3 py-2.5 bg-[#f1f5f9] hover:bg-[#e2e8f0] text-[#475569] rounded-xl text-[12px] font-bold transition-colors cursor-pointer">
                            ✕
                        </button>
                    </div>
                </form>
            </div>

            <!-- SUCCESS FLASH MESSAGE -->
            @if(session('success'))
            <div class="bg-emerald-50 border border-emerald-300 text-emerald-800 px-4 py-3 rounded-xl text-[13px] font-bold flex items-center gap-2">
                <i data-lucide="check-circle-2" class="w-4 h-4"></i> {{ session('success') }}
            </div>
            @endif

            <!-- MENU ITEMS TABLE -->
            <div class="border rounded-xl overflow-hidden shadow-xs">
                <table class="w-full text-left text-[13px]">
                    <thead class="bg-[#f1f5f9] font-bold uppercase text-[#475569] border-b text-[11px]">
                        <tr>
                            <th class="p-3.5 w-12">#</th>
                            <th class="p-3.5">MENU LABEL</th>
                            <th class="p-3.5">LINK URL</th>
                            <th class="p-3.5 text-center w-24">STATUS</th>
                            <th class="p-3.5 text-center w-36">ACTIONS</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-[#e2e8f0]">
                        @forelse($menuItems as $item)
                        <tr class="hover:bg-sky-50/50 group" id="menu-row-{{ $item->id }}">
                            <!-- DISPLAY MODE -->
                            <td class="p-3.5 font-mono text-[#94a3b8] text-[12px] menu-display-{{ $item->id }}">{{ $item->sort_order }}</td>
                            <td class="p-3.5 font-bold text-[#0f172a] menu-display-{{ $item->id }}">
                                <div class="flex items-center gap-2">
                                    <i data-lucide="grip-vertical" class="w-3.5 h-3.5 text-[#cbd5e1]"></i>
                                    {{ $item->label }}
                                </div>
                                @if($item->children && $item->children->count() > 0)
                                <div class="mt-1 flex flex-wrap gap-1">
                                    @foreach($item->children as $child)
                                    <span class="text-[10px] bg-sky-100 text-sky-700 px-2 py-0.5 rounded-full font-bold">{{ $child->label }}</span>
                                    @endforeach
                                </div>
                                @endif
                            </td>
                            <td class="p-3.5 text-[#64748b] font-mono text-[12px] menu-display-{{ $item->id }}">
                                <code class="bg-[#f1f5f9] px-2 py-1 rounded-lg text-[11px]">{{ $item->url ?? '—' }}</code>
                            </td>
                            <td class="p-3.5 text-center menu-display-{{ $item->id }}">
                                @if($item->is_active)
                                <span class="bg-emerald-100 text-emerald-700 px-2.5 py-1 rounded-full text-[10px] font-bold uppercase">Active</span>
                                @else
                                <span class="bg-rose-100 text-rose-700 px-2.5 py-1 rounded-full text-[10px] font-bold uppercase">Hidden</span>
                                @endif
                            </td>
                            <td class="p-3.5 text-center menu-display-{{ $item->id }}">
                                <div class="flex items-center justify-center gap-1.5">
                                    <button type="button" onclick="startEditMenu({{ $item->id }})" class="px-2.5 py-1.5 bg-sky-50 hover:bg-sky-100 text-[#0284c7] rounded-lg text-[11px] font-bold border border-sky-200 transition-colors cursor-pointer" title="Edit">
                                        ✏️ Edit
                                    </button>
                                    <form action="/admin/menus/{{ $item->id }}" method="POST" onsubmit="return confirm('Delete &quot;{{ $item->label }}&quot;? This cannot be undone.')" style="display:inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="px-2.5 py-1.5 bg-rose-50 hover:bg-rose-100 text-rose-600 rounded-lg text-[11px] font-bold border border-rose-200 transition-colors cursor-pointer" title="Delete">
                                            🗑️
                                        </button>
                                    </form>
                                </div>
                            </td>

                            <!-- EDIT MODE (hidden by default) -->
                            <td colspan="5" class="p-0 hidden menu-edit-{{ $item->id }}">
                                <form action="/admin/menus/{{ $item->id }}" method="POST" class="flex items-center gap-3 p-3.5 bg-sky-50/80">
                                    @csrf
                                    @method('PUT')
                                    <span class="font-mono text-[#94a3b8] text-[12px] w-8">{{ $item->sort_order }}</span>
                                    <input type="text" name="label" value="{{ $item->label }}" required class="flex-1 border border-[#38bdf8] px-3 py-2 rounded-lg text-[13px] font-bold focus:ring-2 focus:ring-[#0284c7]/20 outline-none">
                                    <input type="text" name="url" value="{{ $item->url }}" required class="flex-1 border border-[#38bdf8] px-3 py-2 rounded-lg text-[13px] font-mono focus:ring-2 focus:ring-[#0284c7]/20 outline-none">
                                    <button type="submit" class="bg-emerald-600 hover:bg-emerald-700 text-white px-4 py-2 rounded-lg text-[11px] font-bold uppercase transition-colors cursor-pointer">
                                        ✓ Save
                                    </button>
                                    <button type="button" onclick="cancelEditMenu({{ $item->id }})" class="bg-[#f1f5f9] hover:bg-[#e2e8f0] text-[#475569] px-3 py-2 rounded-lg text-[11px] font-bold transition-colors cursor-pointer">
                                        ✕ Cancel
                                    </button>
                                </form>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="p-8 text-center text-[#94a3b8]">
                                <div class="space-y-2">
                                    <i data-lucide="menu" class="w-8 h-8 mx-auto text-[#cbd5e1]"></i>
                                    <p class="text-[14px] font-bold">No menu items yet</p>
                                    <p class="text-[12px]">Click "Add Menu Item" above to create your first storefront navigation link.</p>
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- FOOTER INFO -->
            <div class="flex items-center justify-between text-[11px] text-[#94a3b8] pt-2">
                <span class="font-bold uppercase">{{ $menuItems->count() }} Menu Items in Database</span>
                <span class="flex items-center gap-1.5">
                    <i data-lucide="info" class="w-3.5 h-3.5"></i>
                    Changes reflect on the storefront header immediately after save.
                </span>
            </div>
        </div>
        <!-- SECTION: PRODUCT LIST -->
        <div id="section-products" class="section-content hidden bg-white/90 border p-6 rounded-2xl space-y-6">
            <div class="flex justify-between items-center border-b pb-4">
                <div>
                    <h2 class="text-[18px] font-serif font-bold text-[#0f172a] uppercase flex items-center gap-2">
                        <i data-lucide="package" class="w-5 h-5 text-[#0284c7]"></i> Products Catalog
                    </h2>
                    <p class="text-[12px] text-[#64748b] mt-1">Manage and view all your fragrance listings, inventory, and pricing.</p>
                </div>
                
                <div class="flex items-center gap-3">
                    <div class="relative">
                        <i data-lucide="search" class="w-4 h-4 text-[#94a3b8] absolute left-3 top-1/2 -translate-y-1/2"></i>
                        <input type="text" id="productSearchInput" onkeyup="renderProductsTable()" placeholder="Search products..." class="pl-9 pr-3 py-2 text-[12px] font-bold border border-[#cbd5e1] rounded-lg focus:border-[#0284c7] focus:ring-1 focus:ring-[#0284c7] outline-none w-56">
                    </div>
                    <button type="button" onclick="switchSection('product_add')" class="bg-[#0f172a] hover:bg-[#B8712E] text-white px-4 py-2.5 rounded-xl text-[12px] font-bold uppercase tracking-wider flex items-center gap-2 transition-colors cursor-pointer">
                        <i data-lucide="plus" class="w-4 h-4"></i> Add Product
                    </button>
                </div>
            </div>

            <div class="border rounded-xl overflow-visible shadow-xs">
                <table class="w-full text-left text-[13px]">
                    <thead class="bg-[#f1f5f9] font-bold uppercase text-[#475569] border-b text-[11px]">
                        <tr>
                            <th class="p-3.5 rounded-tl-xl w-14">IMAGE</th>
                            <th class="p-3.5">PRODUCT INFO</th>
                            <th class="p-3.5">CATEGORY</th>
                            <th class="p-3.5">PRICE (৳)</th>
                            <th class="p-3.5">STOCK</th>
                            <th class="p-3.5 text-center">STATUS</th>
                            <th class="p-3.5 text-right rounded-tr-xl">ACTIONS</th>
                        </tr>
                    </thead>
                    <tbody id="productsTableBody" class="divide-y divide-[#e2e8f0]">
                        <!-- Rendered via JS -->
                    </tbody>
                </table>
            </div>
            
            <div class="flex items-center justify-between text-[11px] text-[#94a3b8] pt-2">
                <span class="font-bold uppercase" id="productCountLabel">0 Products Listed</span>
            </div>
        </div>
        <div id="section-product_add" class="section-content hidden bg-white/90 border p-6 rounded-2xl space-y-6">
            <div class="flex justify-between items-center border-b pb-4">
                <div>
                    <h2 class="text-[18px] font-serif font-bold text-[#0f172a] uppercase flex items-center gap-2">
                        <i data-lucide="package-plus" class="w-5 h-5 text-[#0284c7]"></i> Add New Product
                    </h2>
                    <p class="text-[12px] text-[#64748b] mt-1">Create a new fragrance product listing for your database storefront.</p>
                </div>
            </div>

            <form action="{{ url('/admin/products') }}" method="POST" enctype="multipart/form-data" id="addProdForm" class="space-y-6">
                @csrf
                <!-- Top Row: Title & Collection -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="text-[11px] font-bold uppercase text-[#475569] block mb-1.5">Product Title *</label>
                        <input type="text" name="name" required placeholder="e.g. L'Ombre d'Ambre Extrait 100ml" class="w-full border border-[#cbd5e1] px-4 py-3 rounded-xl text-[14px] font-bold focus:border-[#0284c7] focus:ring-2 focus:ring-[#0284c7]/20 outline-none transition-all">
                    </div>
                    <div>
                        <label class="text-[11px] font-bold uppercase text-[#475569] block mb-1.5">Collection / Category</label>
                        <select name="category_id" class="w-full border border-[#cbd5e1] px-4 py-3 rounded-xl text-[14px] font-bold focus:border-[#0284c7] focus:ring-2 focus:ring-[#0284c7]/20 outline-none transition-all bg-white text-[#0f172a]">
                            <option value="">Select Category...</option>
                            @php
                                $rootCats = $categories->whereNull('parent_id');
                            @endphp
                            @if($rootCats->isNotEmpty())
                                @foreach ($rootCats as $cat)
                                    <optgroup label="{{ $cat->name }}">
                                        <option value="{{ $cat->id }}">{{ $cat->name }} (Main)</option>
                                        @foreach ($categories->where('parent_id', $cat->id) as $sub)
                                            <option value="{{ $sub->id }}">&nbsp;&nbsp;↳ {{ $sub->name }} (Subcategory)</option>
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
                </div>

                <!-- Second Row: Pricing, Stock & Gender -->
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <div>
                        <label class="text-[11px] font-bold uppercase text-[#475569] block mb-1.5">Base Price (৳ / $) *</label>
                        <input type="number" step="0.01" name="price" required placeholder="e.g. 3500" class="w-full border border-[#cbd5e1] px-4 py-3 rounded-xl text-[14px] font-bold font-mono focus:border-[#0284c7] focus:ring-2 focus:ring-[#0284c7]/20 outline-none transition-all">
                    </div>
                    <div>
                        <label class="text-[11px] font-bold uppercase text-[#475569] block mb-1.5">Stock Quantity *</label>
                        <input type="number" name="stock" value="50" required placeholder="e.g. 50" class="w-full border border-[#cbd5e1] px-4 py-3 rounded-xl text-[14px] font-bold font-mono focus:border-[#0284c7] focus:ring-2 focus:ring-[#0284c7]/20 outline-none transition-all">
                    </div>
                    <div>
                        <label class="text-[11px] font-bold uppercase text-[#475569] block mb-1.5">Gender Target *</label>
                        <select name="gender" required class="w-full border border-[#cbd5e1] px-4 py-3 rounded-xl text-[14px] font-bold focus:border-[#0284c7] focus:ring-2 focus:ring-[#0284c7]/20 outline-none transition-all bg-white text-[#0f172a]">
                            <option value="unisex">Unisex / Universal</option>
                            <option value="women">Women</option>
                            <option value="men">Men</option>
                        </select>
                    </div>
                </div>

                <!-- Third Row: Image Upload & Scent Family -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="text-[11px] font-bold uppercase text-[#475569] block mb-1.5">Scent Family / Character</label>
                        <input type="text" name="scent_family" placeholder="e.g. Amber Woody, Damask Rose" class="w-full border border-[#cbd5e1] px-4 py-3 rounded-xl text-[14px] font-bold focus:border-[#0284c7] focus:ring-2 focus:ring-[#0284c7]/20 outline-none transition-all">
                    </div>
                    <div>
                        <label class="text-[11px] font-bold uppercase text-[#475569] block mb-1.5">Bottle Image Upload (Primary)</label>
                        <input type="file" name="primary_image_file" accept="image/*" class="w-full border border-[#cbd5e1] px-4 py-2.5 rounded-xl text-[13px] font-bold focus:border-[#0284c7] focus:ring-2 focus:ring-[#0284c7]/20 outline-none transition-all file:mr-4 file:py-1 file:px-3 file:rounded-lg file:border-0 file:text-[11px] file:font-bold file:bg-[#f1f5f9] file:text-[#0f172a] hover:file:bg-[#e2e8f0] cursor-pointer">
                    </div>
                </div>

                <!-- Full Width: Description -->
                <div>
                    <label class="text-[11px] font-bold uppercase text-[#475569] block mb-1.5">Product Description</label>
                    <textarea name="description" rows="4" placeholder="Describe the perfume's story, inspiration, and scent profile..." class="w-full border border-[#cbd5e1] px-4 py-3 rounded-xl text-[14px] font-medium focus:border-[#0284c7] focus:ring-2 focus:ring-[#0284c7]/20 outline-none transition-all"></textarea>
                </div>

                <!-- Per-Product SEO & Social Meta (Optional / Collapsible) -->
                <div class="bg-[#f8fafc] border border-[#cbd5e1] rounded-xl p-5 space-y-4">
                    <div class="flex items-center justify-between">
                        <span class="text-[12px] font-bold uppercase tracking-wide text-[#0f172a] flex items-center gap-2">
                            <i data-lucide="search" class="w-4 h-4 text-[#0284c7]"></i> Per-Product SEO & Social Meta (Optional)
                        </span>
                        <span class="text-[10px] bg-emerald-100 text-emerald-800 font-bold px-2 py-0.5 rounded-full">Google Rich Snippets</span>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="text-[10px] font-bold uppercase text-[#475569] block mb-1">Custom Meta Title</label>
                            <input type="text" name="meta_title" placeholder="e.g. Perfume Name — Extrait de Parfum | Brand" class="w-full border border-[#cbd5e1] px-3 py-2 rounded-lg text-[13px] bg-white focus:border-[#0284c7] outline-none">
                            <span class="text-[10px] text-[#64748b] mt-0.5 block">Leave empty to auto-generate</span>
                        </div>
                        <div>
                            <label class="text-[10px] font-bold uppercase text-[#475569] block mb-1">SEO Meta Keywords</label>
                            <input type="text" name="meta_keywords" placeholder="e.g. perfume, luxury fragrance, oud, bd" class="w-full border border-[#cbd5e1] px-3 py-2 rounded-lg text-[13px] bg-white focus:border-[#0284c7] outline-none">
                        </div>
                    </div>

                    <div>
                        <label class="text-[10px] font-bold uppercase text-[#475569] block mb-1">Custom Meta Description</label>
                        <textarea name="meta_description" rows="2" placeholder="Custom snippet for Google search results..." class="w-full border border-[#cbd5e1] px-3 py-2 rounded-lg text-[13px] bg-white focus:border-[#0284c7] outline-none"></textarea>
                    </div>

                    <div>
                        <label class="text-[10px] font-bold uppercase text-[#475569] block mb-1">Custom Social Share (OG) Image</label>
                        <input type="file" name="og_image_file" accept="image/*" class="w-full border border-[#cbd5e1] text-[11px] rounded-lg file:mr-3 file:py-1 file:px-3 file:border-0 file:text-[10px] file:font-bold file:bg-[#0284c7] file:text-white cursor-pointer bg-white">
                    </div>
                </div>

                <!-- Footer / Submit -->
                <div class="flex items-center justify-between border-t border-[#e2e8f0] pt-5 mt-2">
                    <div class="flex items-center gap-3">
                        <label class="flex items-center gap-2 cursor-pointer text-[12px] font-bold text-[#475569]">
                            <input type="checkbox" name="is_featured" value="1" class="rounded border-[#cbd5e1] text-[#0284c7]">
                            Feature on Homepage
                        </label>
                    </div>
                    
                    <button type="submit" class="bg-[#0f172a] hover:bg-[#B8712E] text-white px-8 py-3.5 rounded-xl text-[13px] font-bold uppercase tracking-wider transition-all shadow-md hover:shadow-lg flex items-center gap-2 cursor-pointer">
                        <i data-lucide="plus-circle" class="w-4 h-4"></i> Save & Add Product
                    </button>
                </div>
            </form>
        </div>
        <!-- SECTION: ADD PURCHASE -->
        <div id="section-purchase_add" class="section-content hidden bg-white/90 border p-6 rounded-2xl space-y-6">
            <div class="flex justify-between items-center border-b pb-4">
                <div>
                    <h2 class="text-[18px] font-serif font-bold text-[#0f172a] uppercase flex items-center gap-2">
                        <i data-lucide="shopping-cart" class="w-5 h-5 text-[#0284c7]"></i> Add New Purchase
                    </h2>
                    <p class="text-[12px] text-[#64748b] mt-1">Record a new purchase order from your suppliers to restock inventory.</p>
                </div>
            </div>

            <form onsubmit="event.preventDefault(); showToastNotice('Purchase Order Created! (Mock)'); document.getElementById('addPurchaseForm').reset();" id="addPurchaseForm" class="space-y-6">
                <!-- Top Row: Supplier & Reference -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="text-[11px] font-bold uppercase text-[#475569] block mb-1.5">Supplier / Vendor *</label>
                        <div class="flex gap-2">
                            <select required class="flex-1 border border-[#cbd5e1] px-4 py-3 rounded-xl text-[14px] font-bold focus:border-[#0284c7] focus:ring-2 focus:ring-[#0284c7]/20 outline-none transition-all bg-white text-[#0f172a]">
                                <option value="">Select Supplier...</option>
                                <option value="france_oils">France Fragrance Oils Ltd.</option>
                                <option value="dubai_glass">Dubai Luxury Glass Works</option>
                                <option value="local_pkg">BD Premium Packaging</option>
                            </select>
                            <button type="button" onclick="switchSection('supplier')" title="Add New Supplier" class="bg-[#e0f2fe] hover:bg-[#bae6fd] text-[#0284c7] px-4 rounded-xl flex items-center justify-center transition-colors shadow-sm border border-[#38bdf8]/30 cursor-pointer">
                                <i data-lucide="plus" class="w-5 h-5"></i>
                            </button>
                        </div>
                    </div>
                    <div>
                        <label class="text-[11px] font-bold uppercase text-[#475569] block mb-1.5">Purchase Reference No.</label>
                        <input type="text" placeholder="e.g. PO-2026-089" class="w-full border border-[#cbd5e1] px-4 py-3 rounded-xl text-[14px] font-bold font-mono focus:border-[#0284c7] focus:ring-2 focus:ring-[#0284c7]/20 outline-none transition-all">
                    </div>
                </div>

                <!-- Product Selection Row -->
                <div class="border border-[#e2e8f0] bg-[#f8fafc] rounded-xl p-4 space-y-4">
                    <div class="grid grid-cols-1 md:grid-cols-12 gap-4">
                        <div class="md:col-span-5">
                            <label class="text-[11px] font-bold uppercase text-[#475569] block mb-1.5">Item / Material *</label>
                            <div class="flex gap-2">
                                <select required class="flex-1 border border-[#cbd5e1] px-3 py-2.5 rounded-lg text-[13px] font-bold focus:border-[#0284c7] outline-none bg-white text-[#0f172a]">
                                    <option value="">Select Product...</option>
                                    <option value="1">L'Ombre d'Ambre Extrait (100ml)</option>
                                    <option value="2">Velours de Rose (100ml)</option>
                                    <option value="3">Cuir Noir Extrait (100ml)</option>
                                    <option value="4">Oud Santal Precieux (50ml)</option>
                                    <option value="5">Fleur de Vanille Gift Set</option>
                                </select>
                                <button type="button" onclick="switchSection('product_add')" title="Add New Product" class="bg-[#e0f2fe] hover:bg-[#bae6fd] text-[#0284c7] px-3 rounded-lg flex items-center justify-center transition-colors shadow-sm border border-[#38bdf8]/30 cursor-pointer">
                                    <i data-lucide="plus" class="w-4 h-4"></i>
                                </button>
                            </div>
                        </div>
                        <div class="md:col-span-3">
                            <label class="text-[11px] font-bold uppercase text-[#475569] block mb-1.5">Quantity *</label>
                            <input type="number" required placeholder="0" class="w-full border border-[#cbd5e1] px-3 py-2.5 rounded-lg text-[13px] font-bold font-mono focus:border-[#0284c7] outline-none">
                        </div>
                        <div class="md:col-span-4">
                            <label class="text-[11px] font-bold uppercase text-[#475569] block mb-1.5">Unit Cost (৳) *</label>
                            <input type="number" required placeholder="0.00" class="w-full border border-[#cbd5e1] px-3 py-2.5 rounded-lg text-[13px] font-bold font-mono focus:border-[#0284c7] outline-none">
                        </div>
                    </div>
                </div>

                <!-- Footer / Submit -->
                <div class="flex items-center justify-between border-t border-[#e2e8f0] pt-5 mt-2">
                    <div class="flex items-center gap-4">
                        <div>
                            <label class="text-[11px] font-bold uppercase text-[#475569] block mb-1">Payment Status:</label>
                            <select class="border border-[#cbd5e1] px-3 py-1.5 rounded-lg text-[12px] font-bold outline-none bg-white text-[#0f172a]">
                                <option value="paid">Paid</option>
                                <option value="due">Due / Unpaid</option>
                                <option value="partial">Partial</option>
                            </select>
                        </div>
                        <div>
                            <label class="text-[11px] font-bold uppercase text-[#475569] block mb-1">Purchase Date:</label>
                            <input type="date" value="2026-08-03" class="border border-[#cbd5e1] px-3 py-1.5 rounded-lg text-[12px] font-bold outline-none bg-white text-[#0f172a]">
                        </div>
                    </div>
                    
                    <button type="submit" class="bg-[#0f172a] hover:bg-[#B8712E] text-white px-8 py-3.5 rounded-xl text-[13px] font-bold uppercase tracking-wider transition-all shadow-md flex items-center gap-2 cursor-pointer">
                        <i data-lucide="check-square" class="w-4 h-4"></i> Save Purchase Order
                    </button>
                </div>
            </form>
        </div>

        <!-- SECTION: PURCHASE LIST -->
        <div id="section-purchase_list" class="section-content hidden bg-white/90 border p-6 rounded-2xl space-y-6">
            <div class="flex justify-between items-center border-b pb-4">
                <div>
                    <h2 class="text-[18px] font-serif font-bold text-[#0f172a] uppercase flex items-center gap-2">
                        <i data-lucide="list-ordered" class="w-5 h-5 text-[#0284c7]"></i> Purchase Orders
                    </h2>
                    <p class="text-[12px] text-[#64748b] mt-1">Review your supplier purchase history and payment statuses.</p>
                </div>
                
                <div class="flex items-center gap-3">
                    <div class="relative">
                        <i data-lucide="search" class="w-4 h-4 text-[#94a3b8] absolute left-3 top-1/2 -translate-y-1/2"></i>
                        <input type="text" id="purchaseSearchInput" onkeyup="renderPurchaseTable()" placeholder="Search Reference or Supplier..." class="pl-9 pr-3 py-2 text-[12px] font-bold border border-[#cbd5e1] rounded-lg focus:border-[#0284c7] focus:ring-1 focus:ring-[#0284c7] outline-none w-64">
                    </div>
                    <button type="button" onclick="switchSection('purchase_add')" class="bg-[#0f172a] hover:bg-[#B8712E] text-white px-4 py-2.5 rounded-xl text-[12px] font-bold uppercase tracking-wider flex items-center gap-2 transition-colors cursor-pointer">
                        <i data-lucide="plus" class="w-4 h-4"></i> Add Purchase
                    </button>
                </div>
            </div>

            <div class="border rounded-xl overflow-visible shadow-xs">
                <table class="w-full text-left text-[13px]">
                    <thead class="bg-[#f1f5f9] font-bold uppercase text-[#475569] border-b text-[11px]">
                        <tr>
                            <th class="p-3.5 rounded-tl-xl w-24">DATE</th>
                            <th class="p-3.5">REF NO.</th>
                            <th class="p-3.5">SUPPLIER</th>
                            <th class="p-3.5">TOTAL COST (৳)</th>
                            <th class="p-3.5 text-center">PAYMENT</th>
                            <th class="p-3.5 text-right rounded-tr-xl">ACTIONS</th>
                        </tr>
                    </thead>
                    <tbody id="purchaseTableBody" class="divide-y divide-[#e2e8f0]">
                        <!-- Rendered via JS -->
                    </tbody>
                </table>
            </div>
            
            <div class="flex items-center justify-between text-[11px] text-[#94a3b8] pt-2">
                <span class="font-bold uppercase" id="purchaseCountLabel">0 Purchase Orders</span>
            </div>
        </div>
        <!-- SECTION: CUSTOMERS -->
        <div id="section-customers" class="section-content hidden space-y-6">
            
            <!-- Add Customer Block -->
            <div class="bg-white/90 border p-6 rounded-2xl space-y-4">
                <div class="border-b pb-3">
                    <h2 class="text-[18px] font-serif font-bold text-[#0f172a] uppercase flex items-center gap-2">
                        <i data-lucide="user-plus" class="w-5 h-5 text-[#0284c7]"></i> Add New Customer
                    </h2>
                </div>
                <form onsubmit="event.preventDefault(); showToastNotice('Customer Added Successfully! (Mock)'); document.getElementById('addCustomerForm').reset();" id="addCustomerForm" class="grid grid-cols-1 md:grid-cols-12 gap-4 items-end">
                    <div class="md:col-span-3">
                        <label class="text-[11px] font-bold uppercase text-[#475569] block mb-1">Full Name *</label>
                        <input type="text" required placeholder="e.g. Asif Mahmud" class="w-full border border-[#cbd5e1] px-3 py-2.5 rounded-lg text-[13px] font-bold focus:border-[#0284c7] outline-none">
                    </div>
                    <div class="md:col-span-3">
                        <label class="text-[11px] font-bold uppercase text-[#475569] block mb-1">Phone Number *</label>
                        <input type="text" required placeholder="e.g. 01700123456" class="w-full border border-[#cbd5e1] px-3 py-2.5 rounded-lg text-[13px] font-bold focus:border-[#0284c7] outline-none">
                    </div>
                    <div class="md:col-span-4">
                        <label class="text-[11px] font-bold uppercase text-[#475569] block mb-1">Delivery Address *</label>
                        <input type="text" required placeholder="e.g. House 12, Road 5, Dhanmondi, Dhaka" class="w-full border border-[#cbd5e1] px-3 py-2.5 rounded-lg text-[13px] font-bold focus:border-[#0284c7] outline-none">
                    </div>
                    <div class="md:col-span-2">
                        <button type="submit" class="w-full bg-[#0f172a] hover:bg-[#B8712E] text-white px-4 py-2.5 rounded-lg text-[12px] font-bold uppercase tracking-wider transition-all flex items-center justify-center gap-1.5 shadow-sm cursor-pointer">
                            <i data-lucide="plus" class="w-4 h-4"></i> Add
                        </button>
                    </div>
                </form>
            </div>

            <!-- Customer Directory Block -->
            <div class="bg-white/90 border p-6 rounded-2xl space-y-4">
                <div class="flex justify-between items-center border-b pb-4">
                    <div>
                        <h2 class="text-[18px] font-serif font-bold text-[#0f172a] uppercase flex items-center gap-2">
                            <i data-lucide="users" class="w-5 h-5 text-[#0284c7]"></i> Customer Directory
                        </h2>
                        <p class="text-[12px] text-[#64748b] mt-1">Manage all your registered clients and view their order history.</p>
                    </div>
                    
                    <div class="relative">
                        <i data-lucide="search" class="w-4 h-4 text-[#94a3b8] absolute left-3 top-1/2 -translate-y-1/2"></i>
                        <input type="text" id="customerSearchInput" onkeyup="renderCustomersTable()" placeholder="Search by name or phone..." class="pl-9 pr-3 py-2 text-[12px] font-bold border border-[#cbd5e1] rounded-lg focus:border-[#0284c7] focus:ring-1 focus:ring-[#0284c7] outline-none w-64">
                    </div>
                </div>

                <div class="border rounded-xl overflow-visible shadow-xs">
                    <table class="w-full text-left text-[13px]">
                        <thead class="bg-[#f1f5f9] font-bold uppercase text-[#475569] border-b text-[11px]">
                            <tr>
                                <th class="p-3.5 rounded-tl-xl">CUSTOMER NAME</th>
                                <th class="p-3.5">PHONE NUMBER</th>
                                <th class="p-3.5">ADDRESS</th>
                                <th class="p-3.5 text-center">TOTAL ORDERS</th>
                                <th class="p-3.5 text-right rounded-tr-xl">ACTIONS</th>
                            </tr>
                        </thead>
                        <tbody id="customersTableBody" class="divide-y divide-[#e2e8f0]">
                            <!-- Rendered via JS -->
                        </tbody>
                    </table>
                </div>
                
                <div class="flex items-center justify-between text-[11px] text-[#94a3b8] pt-2">
                    <span class="font-bold uppercase" id="customerCountLabel">0 Customers</span>
                </div>
            </div>
        </div>
        <!-- SECTION: SUPPLIERS -->
        <div id="section-supplier" class="section-content hidden space-y-6">
            
            <!-- Add Supplier Block -->
            <div class="bg-white/90 border p-6 rounded-2xl space-y-4">
                <div class="border-b pb-3">
                    <h2 class="text-[18px] font-serif font-bold text-[#0f172a] uppercase flex items-center gap-2">
                        <i data-lucide="building-2" class="w-5 h-5 text-[#0284c7]"></i> Add New Supplier
                    </h2>
                </div>
                <form onsubmit="event.preventDefault(); showToastNotice('Supplier Added Successfully! (Mock)'); document.getElementById('addSupplierForm').reset();" id="addSupplierForm" class="grid grid-cols-1 md:grid-cols-12 gap-4 items-end">
                    <div class="md:col-span-3">
                        <label class="text-[11px] font-bold uppercase text-[#475569] block mb-1">Company Name *</label>
                        <input type="text" required placeholder="e.g. France Fragrance Oils" class="w-full border border-[#cbd5e1] px-3 py-2.5 rounded-lg text-[13px] font-bold focus:border-[#0284c7] outline-none">
                    </div>
                    <div class="md:col-span-3">
                        <label class="text-[11px] font-bold uppercase text-[#475569] block mb-1">Contact Person</label>
                        <input type="text" placeholder="e.g. Pierre Dubois" class="w-full border border-[#cbd5e1] px-3 py-2.5 rounded-lg text-[13px] font-bold focus:border-[#0284c7] outline-none">
                    </div>
                    <div class="md:col-span-2">
                        <label class="text-[11px] font-bold uppercase text-[#475569] block mb-1">Phone Number *</label>
                        <input type="text" required placeholder="e.g. +33 1 42 68 53" class="w-full border border-[#cbd5e1] px-3 py-2.5 rounded-lg text-[13px] font-bold focus:border-[#0284c7] outline-none">
                    </div>
                    <div class="md:col-span-2">
                        <label class="text-[11px] font-bold uppercase text-[#475569] block mb-1">City / Country</label>
                        <input type="text" placeholder="e.g. Grasse, France" class="w-full border border-[#cbd5e1] px-3 py-2.5 rounded-lg text-[13px] font-bold focus:border-[#0284c7] outline-none">
                    </div>
                    <div class="md:col-span-2">
                        <button type="submit" class="w-full bg-[#0f172a] hover:bg-[#B8712E] text-white px-4 py-2.5 rounded-lg text-[12px] font-bold uppercase tracking-wider transition-all flex items-center justify-center gap-1.5 shadow-sm cursor-pointer">
                            <i data-lucide="plus" class="w-4 h-4"></i> Add
                        </button>
                    </div>
                </form>
            </div>

            <!-- Supplier Directory Block -->
            <div class="bg-white/90 border p-6 rounded-2xl space-y-4">
                <div class="flex justify-between items-center border-b pb-4">
                    <div>
                        <h2 class="text-[18px] font-serif font-bold text-[#0f172a] uppercase flex items-center gap-2">
                            <i data-lucide="contact-2" class="w-5 h-5 text-[#0284c7]"></i> Supplier Directory
                        </h2>
                        <p class="text-[12px] text-[#64748b] mt-1">Manage your vendors, raw material providers, and packaging partners.</p>
                    </div>
                    
                    <div class="relative">
                        <i data-lucide="search" class="w-4 h-4 text-[#94a3b8] absolute left-3 top-1/2 -translate-y-1/2"></i>
                        <input type="text" id="supplierSearchInput" onkeyup="renderSupplierTable()" placeholder="Search company or phone..." class="pl-9 pr-3 py-2 text-[12px] font-bold border border-[#cbd5e1] rounded-lg focus:border-[#0284c7] focus:ring-1 focus:ring-[#0284c7] outline-none w-64">
                    </div>
                </div>

                <div class="border rounded-xl overflow-visible shadow-xs">
                    <table class="w-full text-left text-[13px]">
                        <thead class="bg-[#f1f5f9] font-bold uppercase text-[#475569] border-b text-[11px]">
                            <tr>
                                <th class="p-3.5 rounded-tl-xl">COMPANY NAME</th>
                                <th class="p-3.5">CONTACT PERSON</th>
                                <th class="p-3.5">PHONE NUMBER</th>
                                <th class="p-3.5 text-center">TOTAL POs</th>
                                <th class="p-3.5 text-right rounded-tr-xl">ACTIONS</th>
                            </tr>
                        </thead>
                        <tbody id="supplierTableBody" class="divide-y divide-[#e2e8f0]">
                            <!-- Rendered via JS -->
                        </tbody>
                    </table>
                </div>
                
                <div class="flex items-center justify-between text-[11px] text-[#94a3b8] pt-2">
                    <span class="font-bold uppercase" id="supplierCountLabel">0 Suppliers</span>
                </div>
            </div>
        </div>
        <div id="section-courier_setup" class="section-content hidden bg-white/90 border p-6 rounded-2xl space-y-6">
            <div class="flex justify-between items-center border-b pb-4">
                <div>
                    <h2 class="text-[18px] font-serif font-bold text-[#0f172a] uppercase flex items-center gap-2">
                        <i data-lucide="truck" class="w-5 h-5 text-[#0284c7]"></i> Courier Hub
                    </h2>
                    <p class="text-[12px] text-[#64748b] mt-1">Manage courier partners for order dispatch and delivery tracking across Bangladesh.</p>
                </div>
                <button type="button" onclick="document.getElementById('addCourierForm').classList.toggle('hidden'); lucide.createIcons();" class="bg-[#0284c7] text-white px-4 py-2.5 rounded-xl text-[12px] font-bold uppercase tracking-wider flex items-center gap-2 hover:bg-[#0369a1] transition-colors cursor-pointer">
                    <i data-lucide="plus" class="w-4 h-4"></i> Add Courier
                </button>
            </div>

            <!-- ADD NEW COURIER FORM -->
            <div id="addCourierForm" class="hidden bg-gradient-to-r from-sky-50 to-white border border-[#38bdf8]/40 p-5 rounded-xl space-y-4">
                <h3 class="text-[14px] font-bold uppercase text-[#0f172a] flex items-center gap-2 border-b pb-2">
                    <i data-lucide="plus-circle" class="w-4 h-4 text-[#0284c7]"></i> Register New Courier Partner
                </h3>
                <div class="grid grid-cols-1 sm:grid-cols-12 gap-4 items-end">
                    <div class="sm:col-span-3">
                        <label class="text-[11px] font-bold uppercase text-[#475569] block mb-1">Courier Name *</label>
                        <input type="text" id="newCourierName" placeholder="e.g. RedX Express" class="w-full border border-[#cbd5e1] px-3 py-2.5 rounded-xl text-[13px] font-bold focus:border-[#0284c7] focus:ring-2 focus:ring-[#0284c7]/20 outline-none transition-all">
                    </div>
                    <div class="sm:col-span-3">
                        <label class="text-[11px] font-bold uppercase text-[#475569] block mb-1">Contact Number</label>
                        <input type="text" id="newCourierPhone" placeholder="+880 1XXX-XXXXXX" class="w-full border border-[#cbd5e1] px-3 py-2.5 rounded-xl text-[13px] font-bold focus:border-[#0284c7] focus:ring-2 focus:ring-[#0284c7]/20 outline-none transition-all">
                    </div>
                    <div class="sm:col-span-3">
                        <label class="text-[11px] font-bold uppercase text-[#475569] block mb-1">Tracking URL</label>
                        <input type="text" id="newCourierUrl" placeholder="https://tracking.courier.com" class="w-full border border-[#cbd5e1] px-3 py-2.5 rounded-xl text-[13px] font-bold focus:border-[#0284c7] focus:ring-2 focus:ring-[#0284c7]/20 outline-none transition-all">
                    </div>
                    <div class="sm:col-span-3 flex gap-2">
                        <button type="button" onclick="addCourier()" class="flex-1 bg-emerald-600 hover:bg-emerald-700 text-white px-4 py-2.5 rounded-xl text-[12px] font-bold uppercase tracking-wider transition-colors cursor-pointer">
                            ✓ Save
                        </button>
                        <button type="button" onclick="document.getElementById('addCourierForm').classList.add('hidden')" class="px-3 py-2.5 bg-[#f1f5f9] hover:bg-[#e2e8f0] text-[#475569] rounded-xl text-[12px] font-bold transition-colors cursor-pointer">
                            ✕
                        </button>
                    </div>
                </div>
            </div>

            <!-- COURIER CARDS GRID -->
            <div id="courierCardsGrid" class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-4">
                <!-- Rendered by JS -->
            </div>

            <!-- FOOTER -->
            <div class="flex items-center justify-between text-[11px] text-[#94a3b8] pt-2">
                <span class="font-bold uppercase" id="courierCountLabel">0 Courier Partners</span>
                <span class="flex items-center gap-1.5">
                    <i data-lucide="info" class="w-3.5 h-3.5"></i>
                    Courier data is stored locally. Integrate API keys for automated dispatch.
                </span>
            </div>
        </div>

        <!-- SECTION: SEND COURIER -->
        <div id="section-courier_send" class="section-content hidden bg-white/90 border p-6 rounded-2xl space-y-6">
            <div class="flex justify-between items-center border-b pb-4">
                <div>
                    <h2 class="text-[18px] font-serif font-bold text-[#0f172a] uppercase flex items-center gap-2">
                        <i data-lucide="package-check" class="w-5 h-5 text-[#0284c7]"></i> Send Courier (Pending Dispatch)
                    </h2>
                    <p class="text-[12px] text-[#64748b] mt-1">Select orders to hand over to courier partners for delivery.</p>
                </div>
            </div>
            
            <div class="border rounded-xl overflow-hidden shadow-xs">
                <table class="w-full text-left text-[13px]">
                    <thead class="bg-[#f1f5f9] font-bold uppercase text-[#475569] border-b text-[11px]">
                        <tr>
                            <th class="p-3.5 w-12"><input type="checkbox" class="rounded border-gray-300"></th>
                            <th class="p-3.5">ORDER ID</th>
                            <th class="p-3.5">CUSTOMER</th>
                            <th class="p-3.5">AMOUNT (BDT)</th>
                            <th class="p-3.5 w-48">SELECT COURIER</th>
                            <th class="p-3.5 text-center w-36">ACTION</th>
                        </tr>
                    </thead>
                    <tbody id="sendCourierTableBody" class="divide-y divide-[#e2e8f0]">
                        <!-- Rendered by JS -->
                    </tbody>
                </table>
            </div>
            
            <div class="flex justify-end pt-2">
                <button type="button" onclick="alert('Bulk send not implemented in demo.')" class="bg-[#0284c7] text-white px-5 py-2.5 rounded-xl text-[12px] font-bold uppercase tracking-wider flex items-center gap-2 hover:bg-[#0369a1] transition-colors cursor-pointer">
                    <i data-lucide="send" class="w-4 h-4"></i> Dispatch Selected Orders
                </button>
            </div>
        </div>

        <!-- SECTION: COURIER HISTORY -->
        <div id="section-courier_history" class="section-content hidden bg-white/90 border p-6 rounded-2xl space-y-6">
            <div class="flex justify-between items-center border-b pb-4">
                <div>
                    <h2 class="text-[18px] font-serif font-bold text-[#0f172a] uppercase flex items-center gap-2">
                        <i data-lucide="history" class="w-5 h-5 text-[#0284c7]"></i> Courier History & Tracking
                    </h2>
                    <p class="text-[12px] text-[#64748b] mt-1">View dispatched orders, track statuses, and filter by courier partner.</p>
                </div>
                
                <div class="flex items-center gap-2">
                    <div class="relative">
                        <i data-lucide="search" class="w-4 h-4 text-[#94a3b8] absolute left-3 top-1/2 -translate-y-1/2"></i>
                        <input type="text" id="courierHistorySearch" onkeyup="filterCourierHistory()" placeholder="Search Order ID..." class="pl-9 pr-3 py-2 text-[12px] font-bold border border-[#cbd5e1] rounded-lg focus:border-[#0284c7] focus:ring-1 focus:ring-[#0284c7] outline-none">
                    </div>
                    <select id="courierHistoryFilter" onchange="filterCourierHistory()" class="px-3 py-2 text-[12px] font-bold border border-[#cbd5e1] rounded-lg focus:border-[#0284c7] outline-none text-[#475569] bg-white">
                        <option value="all">All Couriers</option>
                        <option value="Steadfast Courier">Steadfast Courier</option>
                        <option value="Pathao Courier">Pathao Courier</option>
                        <option value="RedX">RedX</option>
                        <option value="Sundarban Courier">Sundarban Courier</option>
                    </select>
                </div>
            </div>
            
            <div class="border rounded-xl overflow-hidden shadow-xs">
                <table class="w-full text-left text-[13px]">
                    <thead class="bg-[#f1f5f9] font-bold uppercase text-[#475569] border-b text-[11px]">
                        <tr>
                            <th class="p-3.5">DATE DISPATCHED</th>
                            <th class="p-3.5">ORDER ID</th>
                            <th class="p-3.5">CUSTOMER</th>
                            <th class="p-3.5">COURIER</th>
                            <th class="p-3.5 text-center">TRACKING ID</th>
                            <th class="p-3.5 text-center">STATUS</th>
                        </tr>
                    </thead>
                    <tbody id="courierHistoryTableBody" class="divide-y divide-[#e2e8f0]">
                        <!-- Rendered by JS -->
                    </tbody>
                </table>
            </div>
            </div>
        </div>

        <!-- ADD CUSTOMER MODAL (Create Order Page) -->
        <div id="addCustomerModal" class="hidden fixed inset-0 z-[100] flex items-center justify-center bg-[#0f172a]/50 backdrop-blur-sm">
            <div class="bg-white rounded-2xl w-full max-w-md shadow-2xl overflow-hidden border border-[#e2e8f0]">
                <div class="bg-[#f8fafc] px-5 py-4 border-b flex justify-between items-center">
                    <h3 class="text-[15px] font-bold text-[#0f172a] uppercase flex items-center gap-2">
                        <i data-lucide="user-plus" class="w-4 h-4 text-[#0284c7]"></i> Quick Add Customer
                    </h3>
                    <button type="button" onclick="closeAddCustomerModal()" class="text-[#94a3b8] hover:text-rose-600 transition-colors cursor-pointer">
                        <i data-lucide="x" class="w-5 h-5"></i>
                    </button>
                </div>
                <div class="p-5 space-y-4">
                    <div>
                        <label class="text-[11px] font-bold uppercase text-[#475569] block mb-1">Customer Full Name *</label>
                        <input type="text" id="modalCustomerName" placeholder="e.g. Shakib Al Hasan" class="w-full border border-[#cbd5e1] px-3 py-2.5 rounded-lg text-[13px] font-bold focus:border-[#0284c7] outline-none bg-white text-[#0f172a]">
                    </div>
                    <div>
                        <label class="text-[11px] font-bold uppercase text-[#475569] block mb-1">Phone Number *</label>
                        <input type="text" id="modalCustomerPhone" placeholder="e.g. 01700112233" class="w-full border border-[#cbd5e1] px-3 py-2.5 rounded-lg text-[13px] font-bold focus:border-[#0284c7] outline-none bg-white text-[#0f172a]">
                    </div>
                    <div>
                        <label class="text-[11px] font-bold uppercase text-[#475569] block mb-1">Address</label>
                        <textarea id="modalCustomerAddress" rows="2" placeholder="e.g. 123 Gulshan Ave, Dhaka" class="w-full border border-[#cbd5e1] px-3 py-2.5 rounded-lg text-[13px] font-bold focus:border-[#0284c7] outline-none bg-white text-[#0f172a]"></textarea>
                    </div>
                </div>
                <div class="p-4 border-t bg-[#f8fafc] flex justify-end gap-2">
                    <button type="button" onclick="closeAddCustomerModal()" class="px-4 py-2 text-[12px] font-bold text-[#64748b] bg-white border border-[#cbd5e1] rounded-xl hover:bg-gray-50 transition-colors cursor-pointer">Cancel</button>
                    <button type="button" onclick="submitAddCustomerModal()" class="px-4 py-2 text-[12px] font-bold text-white bg-[#0284c7] rounded-xl hover:bg-[#0369a1] shadow-sm transition-colors cursor-pointer">Save & Select</button>
                </div>
            </div>
        </div>

        <!-- Classic Minimal Admin Footer -->
        @include('admin.partials.footer')
    </main>
    </div>

    <script>
        let masterOrders = [
            { id: '#RX-8925', client: 'Shakib Al Hasan', prod: "L'Ombre d'Ambre 100ml (x1)", amt: 3200, status: 'Pending Dispatch' },
            { id: '#RX-8924', client: 'Sabrina Sultana', prod: 'Velours de Rose 100ml (x1)', amt: 2850, status: 'Pending Dispatch' },
            { id: '#RX-8921', client: 'Mahmudur Rahman', prod: "L'Ombre d'Ambre 100ml (x1)", amt: 3200, status: 'In Transit' },
            { id: '#RX-8919', client: 'Tanvir Hossain', prod: 'Velours de Rose 100ml (x1)', amt: 2850, status: 'Delivered' },
            { id: '#RX-8910', client: 'Sumiya Akhtar', prod: 'Cuir Noir Extrait 100ml (x1)', amt: 3800, status: 'Returned' }
        ];

        let cart = [];
        let ordersSubTab = 'total';

        const allSubmenus = ['orders', 'product', 'purchase', 'contact', 'courier', 'api_gateway', 'seo_sub'];

        const parentSubmenuMap = {
            'create_order': 'orders',
            'orders_list': 'orders',
            'product_add': 'product',
            'products': 'product',
            'purchase_add': 'purchase',
            'purchase_list': 'purchase',
            'customers': 'contact',
            'supplier': 'contact',
            'api_payment': 'api_gateway',
            'api_sms': 'api_gateway',
            'api_courier': 'api_gateway',
            'seo_meta': 'seo_sub',
            'seo_marketing': 'seo_sub',
            'sitemap': 'seo_sub',
            'robots': 'seo_sub',
            'seo_ping': 'seo_sub'
        };

        function toggleSubmenu(menuId) {
            // Accordion: close all OTHER submenus, then toggle the clicked one
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

        function switchOrdersSub(subType) {
            ordersSubTab = subType;
            const ordersSec = document.getElementById('section-orders');
            // Only switch section if orders isn't already visible
            if (!ordersSec || ordersSec.classList.contains('hidden')) {
                switchSection('orders');
            }
            renderOrdersTable();

            // Highlight active sub-tab button
            ['total', 'success', 'return'].forEach(t => {
                const btn = document.getElementById('osub-' + t);
                if (!btn) return;
                if (t === subType) {
                    btn.classList.add('bg-[#0284c7]', 'text-white');
                    btn.classList.remove('text-[#475569]');
                } else {
                    btn.classList.remove('bg-[#0284c7]', 'text-white');
                    btn.classList.add('text-[#475569]');
                }
            });
        }

        function showToastNotice(msg) {
            const toast = document.getElementById('toast');
            if (!toast) return;
            const toastMsg = document.getElementById('toastMsg');
            if (toastMsg) toastMsg.innerText = msg;
            toast.classList.remove('hidden');
            setTimeout(() => { toast.classList.add('hidden'); }, 3500);
        }

        function clearSystemCache() {
            showToastNotice('Clearing system cache...');
            setTimeout(() => {
                showToastNotice('Cache Cleared Successfully!');
            }, 800);
        }

        function switchSection(secId) {
            // Clean up pre-route anti-flicker style
            const preRoute = document.getElementById('pre-route-hide');
            if (preRoute) {
                preRoute.remove();
            }

            // Hide all sections
            document.querySelectorAll('.section-content').forEach(el => {
                el.classList.add('hidden');
                el.classList.remove('section-entering');
            });
            // Show target with fade-in animation
            const targetSec = document.getElementById('section-' + secId);
            if (targetSec) {
                targetSec.classList.remove('hidden');
                // Force reflow to restart animation
                void targetSec.offsetWidth;
                targetSec.classList.add('section-entering');
            }

            // Scroll main content area and window to top
            window.scrollTo({ top: 0, behavior: 'smooth' });
            const mainEl = document.querySelector('main');
            if (mainEl) mainEl.scrollTo({ top: 0, behavior: 'smooth' });
            
            // Auto open parent submenu in sidebar accordion if applicable
            const parentMenu = parentSubmenuMap[secId];
            if (parentMenu) {
                const subPanel = document.getElementById('sub-' + parentMenu);
                const subChevron = document.querySelector('[data-chevron="' + parentMenu + '"]');
                if (subPanel) subPanel.classList.add('submenu-open');
                if (subChevron) subChevron.classList.add('chevron-open');
            }

            // Sync URL Hash without triggering full reload
            if (window.location.hash !== '#' + secId) {
                history.replaceState(null, '', '#' + secId);
            }

            if (window.lucide) {
                lucide.createIcons();
            }

            // Update sidebar active button highlight
            updateSidebarHighlight(secId);
        }

        function updateSidebarHighlight(secId) {
            // Remove active style from all sidebar buttons
            document.querySelectorAll('[id^="sidebar-btn-"]').forEach(btn => {
                btn.classList.remove('bg-[#0284c7]', 'text-white', 'shadow-md', 'font-bold', 'text-[#0284c7]', 'bg-[#f0f9ff]');
                btn.classList.add('text-[#475569]');
            });
            // Apply active style to matching button
            const activeBtn = document.getElementById('sidebar-btn-' + secId);
            if (activeBtn) {
                if (activeBtn.tagName.toLowerCase() === 'button' && activeBtn.classList.contains('w-full')) {
                    // Submenu button or main button
                    activeBtn.classList.add('text-[#0284c7]', 'bg-[#f0f9ff]', 'font-bold');
                    activeBtn.classList.remove('text-[#475569]', 'text-[#64748b]');
                } else {
                    activeBtn.classList.add('bg-[#0284c7]', 'text-white', 'shadow-md');
                    activeBtn.classList.remove('text-[#475569]');
                }
            }
        }

        // Initialize on page load and hash change
        window.addEventListener('DOMContentLoaded', () => {
            const hash = window.location.hash.replace('#', '');
            if (hash && document.getElementById('section-' + hash)) {
                switchSection(hash);
            } else {
                switchSection('dashboard');
            }
            loadSeoStatus();
            renderCartUI();
        });

        window.addEventListener('hashchange', () => {
            const hash = window.location.hash.replace('#', '');
            if (hash && document.getElementById('section-' + hash)) {
                switchSection(hash);
            }
        });

        function toggleActionDropdown(id) {
            const cleanId = id.replace('#', '');
            const el = document.getElementById('act-drop-' + cleanId);
            document.querySelectorAll('.act-dropdown-menu').forEach(d => {
                if (d.id !== 'act-drop-' + cleanId) d.classList.add('hidden');
            });
            if (el) el.classList.toggle('hidden');
        }

        function renderOrdersTable() {
            const tbody = document.getElementById('ordersMasterTableBody');
            const streamBody = document.getElementById('dashStreamTableBody');

            if (streamBody) {
                streamBody.innerHTML = masterOrders.slice(0, 5).map(ord => `
                    <tr class="hover:bg-sky-50/50 transition-colors">
                        <td class="p-3 font-mono font-bold text-[#0284c7] text-[12px]">${ord.id}</td>
                        <td class="p-3 font-bold text-[#0f172a] text-[12.5px]">${ord.client}</td>
                        <td class="p-3 text-[#475569] text-[12px]">${ord.prod}</td>
                        <td class="p-3 font-mono font-bold text-[#0f172a] text-[12.5px]">৳${ord.amt.toLocaleString()} BDT</td>
                        <td class="p-3"><span class="px-2.5 py-1 bg-emerald-50 text-emerald-700 border border-emerald-200 text-[10px] font-bold uppercase rounded-md">${ord.status}</span></td>
                    </tr>
                `).join('');
            }

            if (!tbody) return;
            tbody.innerHTML = masterOrders.map(ord => {
                const cleanId = ord.id.replace('#', '');
                return `
                    <tr class="hover:bg-slate-50/80 transition-colors border-b border-slate-100">
                        <td class="p-3 font-mono font-bold text-[#0284c7] text-[12px]">${ord.id}</td>
                        <td class="p-3 font-bold text-[#0f172a] text-[12.5px]">${ord.client}</td>
                        <td class="p-3 text-[#475569] text-[12px]">${ord.prod}</td>
                        <td class="p-3 font-mono font-bold text-[#0f172a] text-[12.5px]">৳${ord.amt.toLocaleString()} BDT</td>
                        <td class="p-3"><span class="px-2.5 py-1 bg-emerald-50 text-emerald-700 border border-emerald-200 text-[10px] font-bold uppercase rounded-md">${ord.status}</span></td>
                        <td class="p-3 text-right">
                            <div class="relative inline-block text-left">
                                <button type="button" onclick="toggleActionDropdown('${ord.id}')" class="p-1.5 hover:bg-slate-200 rounded-lg text-slate-600 transition-colors cursor-pointer">
                                    <i data-lucide="more-vertical" class="w-4 h-4"></i>
                                </button>
                                <div id="act-drop-${cleanId}" class="act-dropdown-menu hidden absolute right-0 mt-1.5 w-36 bg-white border border-[#cbd5e1] rounded-xl shadow-2xl py-1 z-50 text-left">
                                    <button onclick="viewOrder('${ord.id}')" class="w-full text-left px-3.5 py-2 text-[12px] font-bold text-[#0284c7] hover:bg-sky-50">👁️ View</button>
                                    <button onclick="editOrder('${ord.id}')" class="w-full text-left px-3.5 py-2 text-[12px] font-bold text-amber-700 hover:bg-amber-50">✏️ Edit</button>
                                    <button onclick="printInvoice('${ord.id}')" class="w-full text-left px-3.5 py-2 text-[12px] font-bold text-purple-700 hover:bg-purple-50">📄 Invoice</button>
                                    <button onclick="markReturn('${ord.id}')" class="w-full text-left px-3.5 py-2 text-[12px] font-bold text-rose-700 hover:bg-rose-50 border-t">🔄 Return</button>
                                </div>
                            </div>
                        </td>
                    </tr>
                `;
            }).join('');
        }

        // ── POS / CREATE SALE TERMINAL LOGIC ──
        function handleCustomerSelectChange(val) {
            if (!val) return;
            const parts = val.split('|');
            const phone = parts[1] || '';
            const addr = parts[2] || '';
            const phoneEl = document.getElementById('coCustomerPhone');
            const addrEl = document.getElementById('coCustomerAddress');
            if (phoneEl) phoneEl.value = phone;
            if (addrEl) addrEl.value = addr;
        }

        function handleDeliveryChargeChange(fee) {
            renderCartUI();
        }

        function handleAddProductToCart() {
            const selectEl = document.getElementById('coProductSelect');
            const val = selectEl ? selectEl.value : '';
            if (!val) return;
            const parts = val.split('|');
            const name = parts[0];
            const price = Number(parts[1]) || 0;
            const prodId = parts[2] || '0';

            const existing = cart.find(i => i.name === name);
            if (existing) { 
                existing.qty += 1; 
            } else { 
                cart.push({ name, price, id: prodId, qty: 1 }); 
            }
            if (selectEl) selectEl.value = '';
            renderCartUI();
        }

        function changeCartQty(index, delta) {
            if (!cart[index]) return;
            cart[index].qty += delta;
            if (cart[index].qty <= 0) {
                cart.splice(index, 1);
            }
            renderCartUI();
        }

        function removeCartItem(index) {
            if (!cart[index]) return;
            cart.splice(index, 1);
            renderCartUI();
        }

        function renderCartUI() {
            const container = document.getElementById('cartItemsList');
            const subtotalDisplay = document.getElementById('coSubtotalDisplay');
            const shippingDisplay = document.getElementById('coShippingDisplay');
            const discountDisplay = document.getElementById('coDiscountDisplay');
            const totalDisplay = document.getElementById('coTotalBillDisplay');
            const countBadge = document.getElementById('posCartCountBadge');
            const deliverySelect = document.getElementById('coDeliveryChargeSelect');
            const discountInput = document.getElementById('coDiscountInput');

            if (!container) return;

            let subtotal = 0;
            let totalQty = 0;

            if (cart.length === 0) {
                container.innerHTML = `
                    <div id="emptyCartPlaceholder" class="p-6 text-center border-2 border-dashed border-slate-200 rounded-xl bg-slate-50 text-slate-400 text-[12px] font-medium">
                        <i data-lucide="shopping-bag" class="w-8 h-8 mx-auto mb-2 text-slate-300"></i>
                        No products added yet. Select a product above to add to this sale.
                    </div>
                `;
            } else {
                container.innerHTML = cart.map((item, idx) => {
                    const itemTotal = item.price * item.qty;
                    subtotal += itemTotal;
                    totalQty += item.qty;
                    return `
                        <div class="bg-white border border-slate-200 p-3 rounded-xl flex items-center justify-between shadow-2xs hover:border-emerald-300 transition-all">
                            <div class="flex items-center gap-3">
                                <div class="w-9 h-9 rounded-lg bg-emerald-50 text-emerald-700 font-bold flex items-center justify-center text-xs shrink-0">
                                    <i data-lucide="package" class="w-4 h-4"></i>
                                </div>
                                <div>
                                    <div class="font-bold text-[12.5px] text-slate-800">${item.name}</div>
                                    <div class="text-[11px] text-emerald-700 font-bold font-mono">৳${item.price.toLocaleString()} BDT x ${item.qty} = <span class="text-slate-900 font-extrabold">৳${itemTotal.toLocaleString()} BDT</span></div>
                                </div>
                            </div>
                            <div class="flex items-center gap-2">
                                <div class="flex items-center border border-slate-200 rounded-lg overflow-hidden bg-slate-50">
                                    <button type="button" onclick="changeCartQty(${idx}, -1)" class="w-7 h-7 bg-white hover:bg-slate-100 font-bold text-slate-700 flex items-center justify-center transition-colors cursor-pointer">-</button>
                                    <span class="w-8 text-center font-bold font-mono text-[12px] text-slate-900">${item.qty}</span>
                                    <button type="button" onclick="changeCartQty(${idx}, 1)" class="w-7 h-7 bg-emerald-600 hover:bg-emerald-700 text-white font-bold flex items-center justify-center transition-colors cursor-pointer">+</button>
                                </div>
                                <button type="button" onclick="removeCartItem(${idx})" title="Remove item" class="w-7 h-7 text-rose-500 hover:bg-rose-50 rounded-lg flex items-center justify-center transition-colors cursor-pointer">
                                    <i data-lucide="trash-2" class="w-3.5 h-3.5"></i>
                                </button>
                            </div>
                        </div>
                    `;
                }).join('');
            }

            const shippingFee = deliverySelect ? (Number(deliverySelect.value) || 0) : 60;
            const discount = discountInput ? (Math.max(0, Number(discountInput.value) || 0)) : 0;
            const finalPayable = Math.max(0, (subtotal + shippingFee - discount));

            if (subtotalDisplay) subtotalDisplay.innerText = `৳${subtotal.toLocaleString()} BDT`;
            if (shippingDisplay) shippingDisplay.innerText = `+ ৳${shippingFee.toLocaleString()} BDT`;
            if (discountDisplay) discountDisplay.innerText = `- ৳${discount.toLocaleString()} BDT`;
            if (totalDisplay) totalDisplay.innerText = `৳${finalPayable.toLocaleString()} BDT`;
            if (countBadge) countBadge.innerText = `${totalQty} Item${totalQty === 1 ? '' : 's'} (${cart.length} unique)`;

            if (window.lucide) {
                lucide.createIcons();
            }
        }

        function resetSaleForm() {
            cart = [];
            const form = document.getElementById('createSaleMasterForm');
            if (form) form.reset();
            const phoneEl = document.getElementById('coCustomerPhone');
            const addrEl = document.getElementById('coCustomerAddress');
            if (phoneEl) phoneEl.value = '01700000000';
            if (addrEl) addrEl.value = 'Store Counter, Dhaka';
            renderCartUI();
            showToastNotice('Sale form reset to default counter state.');
        }

        function printDraftInvoice() {
            if (cart.length === 0) {
                alert('Please add at least 1 product to the sale cart before printing an invoice draft.');
                return;
            }
            window.print();
        }

        function handleCreateOrderSubmit(e) {
            e.preventDefault();
            if (cart.length === 0) { 
                alert('Please select at least 1 product bottle to complete this sale.'); 
                return; 
            }

            const customerSelect = document.getElementById('coCustomerSelect');
            const customerName = customerSelect ? customerSelect.value.split('|')[0] : 'Walk-in Customer';
            const phone = document.getElementById('coCustomerPhone')?.value || 'N/A';
            const address = document.getElementById('coCustomerAddress')?.value || 'N/A';
            const paymentMethod = document.getElementById('coPaymentMethod')?.value || 'Cash on Delivery';
            const courier = document.getElementById('coCourierPartner')?.value || 'Pathao Courier';
            const deliveryFee = Number(document.getElementById('coDeliveryChargeSelect')?.value || 60);
            const discount = Number(document.getElementById('coDiscountInput')?.value || 0);

            const subtotal = cart.reduce((s, i) => s + (i.price * i.qty), 0);
            const grandTotal = Math.max(0, subtotal + deliveryFee - discount);
            const orderId = `#RX-${Math.floor(8900 + Math.random() * 900)}`;
            const prodSummary = cart.map(i => `${i.name} (x${i.qty})`).join(', ');

            // Prepend new order to masterOrders array
            masterOrders.unshift({ 
                id: orderId, 
                client: `${customerName} (${phone})`, 
                prod: prodSummary, 
                amt: grandTotal, 
                status: 'Completed (POS Paid)' 
            });

            showToastNotice(`🎉 Sale ${orderId} Created for ${customerName}! Total: ৳${grandTotal.toLocaleString()} BDT [${paymentMethod}]`);
            
            // Re-render orders and dashboard live streams
            renderOrdersTable();

            // Reset sale form
            resetSaleForm();

            // Smooth switch to orders list
            setTimeout(() => {
                switchSection('orders');
            }, 600);
        }

        function viewOrder(id) { const ord = masterOrders.find(o => o.id === id); alert(`Order ${ord.id}: ${ord.client} - ৳${ord.amt.toLocaleString()} BDT`); }
        function editOrder(id) { const ord = masterOrders.find(o => o.id === id); const n = prompt('Edit Client Name:', ord.client); if (n) { ord.client = n; renderOrdersTable(); } }
        function printInvoice(id) { window.print(); }
        function markReturn(id) { const ord = masterOrders.find(o => o.id === id); if (ord) { ord.status = 'Returned'; renderOrdersTable(); } }

        // ── MENU BUILDER: Inline Edit Toggle ──
        function startEditMenu(id) {
            // Hide display cells, show edit row
            document.querySelectorAll('.menu-display-' + id).forEach(el => el.classList.add('hidden'));
            document.querySelectorAll('.menu-edit-' + id).forEach(el => el.classList.remove('hidden'));
        }

        function cancelEditMenu(id) {
            // Show display cells, hide edit row
            document.querySelectorAll('.menu-display-' + id).forEach(el => el.classList.remove('hidden'));
            document.querySelectorAll('.menu-edit-' + id).forEach(el => el.classList.add('hidden'));
        }

        // ── LIVE PREVIEW HELPERS ──
        function previewSelectedFile(event, imgId, placeholderId) {
            const file = event.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    const img = document.getElementById(imgId);
                    if (img) {
                        img.src = e.target.result;
                        img.classList.remove('hidden');
                    }
                    if (placeholderId) {
                        const placeholder = document.getElementById(placeholderId);
                        if (placeholder) placeholder.classList.add('hidden');
                    }
                };
                reader.readAsDataURL(file);
            }
        }

        function previewUrlInput(url, imgId, placeholderId) {
            const img = document.getElementById(imgId);
            if (img && url.trim()) {
                img.src = url.trim();
                img.classList.remove('hidden');
                if (placeholderId) {
                    const placeholder = document.getElementById(placeholderId);
                    if (placeholder) placeholder.classList.add('hidden');
                }
            }
        }

        // ── HERO VIDEO PREVIEW HANDLERS ──
        function previewSelectedVideo(event) {
            const file = event.target.files[0];
            if (file) {
                const videoEl = document.getElementById('previewHeroVideo');
                if (videoEl) {
                    videoEl.src = URL.createObjectURL(file);
                    videoEl.load();
                    videoEl.play().catch(() => {});
                }
                const urlInput = document.getElementById('heroVideoUrlInput');
                if (urlInput) urlInput.value = '';
            }
        }

        function previewVideoUrl(url) {
            const videoEl = document.getElementById('previewHeroVideo');
            if (videoEl && url.trim()) {
                videoEl.src = url.trim();
                videoEl.load();
                videoEl.play().catch(() => {});
            }
        }

        function applyVideoPreset(url) {
            const urlInput = document.getElementById('heroVideoUrlInput');
            if (urlInput) {
                urlInput.value = url;
            }
            previewVideoUrl(url);
            showToastNotice('Applied video preset!');
        }

        // ── DB SETTINGS SYNC ──
        async function fetchSettingsOnLoad() {
            try {
                const res = await fetch('/api/settings');
                const data = await res.json();
                
                // Populate forms
                document.querySelectorAll('[data-settings-form] input[name], [data-settings-form] select[name], [data-settings-form] textarea[name]').forEach(el => {
                    if (el.type !== 'file' && data[el.name] !== undefined) {
                        el.value = data[el.name];
                    }
                });

                // Update live hero video preview
                const heroVideo = data.hero_video_url || data.hero_video;
                if (heroVideo) {
                    const previewVid = document.getElementById('previewHeroVideo');
                    if (previewVid) {
                        previewVid.src = heroVideo;
                        previewVid.load();
                        previewVid.play().catch(() => {});
                    }
                }

                // Update live brand previews
                const logoUrl = data.logo_url || data.site_logo;
                if (logoUrl) {
                    const previewLogo = document.getElementById('previewLogoImg');
                    if (previewLogo) {
                        previewLogo.src = logoUrl;
                        previewLogo.classList.remove('hidden');
                    }
                    const logoPlaceholder = document.getElementById('previewLogoPlaceholder');
                    if (logoPlaceholder) logoPlaceholder.classList.add('hidden');

                    const sidebarLogo = document.getElementById('sidebarLogoImg');
                    if (sidebarLogo) {
                        sidebarLogo.src = logoUrl;
                        sidebarLogo.classList.remove('hidden');
                    }
                    const sidebarIcon = document.getElementById('sidebarDefaultIcon');
                    if (sidebarIcon) sidebarIcon.classList.add('hidden');
                }

                const faviconUrl = data.favicon_url || data.site_favicon;
                if (faviconUrl) {
                    const previewFav = document.getElementById('previewFaviconImg');
                    if (previewFav) previewFav.src = faviconUrl;
                    const adminFav = document.getElementById('admin-favicon');
                    if (adminFav) adminFav.href = faviconUrl;
                }

                const siteName = data.siteName || data.site_name;
                if (siteName) {
                    const brandNameEl = document.getElementById('sidebarBrandName');
                    if (brandNameEl) brandNameEl.innerText = siteName;
                    document.title = siteName + ' — Master Admin Panel';
                }

                const currentTheme = data.admin_theme || localStorage.getItem('admin_theme') || document.documentElement.getAttribute('data-theme') || 'default';
                applyTheme(currentTheme);
            } catch(e) { console.error('Failed to load settings', e); }
        }

        function applyTheme(theme) {
            document.documentElement.setAttribute('data-theme', theme);
            document.body.classList.remove('theme-night', 'theme-light');
            if (theme === 'night') {
                document.body.classList.add('theme-night');
            } else if (theme === 'light') {
                document.body.classList.add('theme-light');
            }

            // Sync sidebar theme buttons
            ['default', 'light', 'night'].forEach(t => {
                const btn = document.getElementById('theme-btn-' + t);
                if (!btn) return;
                if (t === theme) {
                    btn.classList.add('bg-[#0284c7]', 'text-white', 'shadow-xs');
                    btn.classList.remove('text-[#64748b]', 'hover:bg-[#e2e8f0]');
                } else {
                    btn.classList.remove('bg-[#0284c7]', 'text-white', 'shadow-xs');
                    btn.classList.add('text-[#64748b]', 'hover:bg-[#e2e8f0]');
                }
            });

            // Sync radio button in Site Settings
            const radio = document.querySelector(`input[name="admin_theme"][value="${theme}"]`);
            if (radio) radio.checked = true;
        }

        async function setAdminTheme(theme) {
            applyTheme(theme);
            localStorage.setItem('admin_theme', theme);
            try {
                const formData = new FormData();
                formData.append('admin_theme', theme);
                await fetch('/api/settings', {
                    method: 'POST',
                    headers: { 'Accept': 'application/json' },
                    body: formData
                });
                showToastNotice(`Theme switched to ${theme.toUpperCase()}!`);
            } catch (e) {
                console.error('Failed to persist theme setting:', e);
            }
        }

        async function handleSettingsSave(e) {
            e.preventDefault();
            const form = e.target;
            const btn = form.querySelector('button[type="submit"]');
            const ogHtml = btn.innerHTML;
            btn.innerHTML = `<i data-lucide="loader-2" class="w-4 h-4 animate-spin"></i> Saving...`;
            lucide.createIcons();

            const formData = new FormData(form);

            try {
                const res = await fetch('/api/settings', {
                    method: 'POST',
                    headers: {
                        'Accept': 'application/json'
                    },
                    body: formData
                });
                
                const result = await res.json();

                if (res.ok) {
                    const settings = result.settings || {};
                    
                    if (settings.admin_theme) {
                        applyTheme(settings.admin_theme);
                        localStorage.setItem('admin_theme', settings.admin_theme);
                    }

                    // Instant reactive updates across admin panel
                    const heroVideo = settings.hero_video_url || settings.hero_video;
                    if (heroVideo) {
                        const previewVid = document.getElementById('previewHeroVideo');
                        if (previewVid) {
                            previewVid.src = heroVideo;
                            previewVid.load();
                            previewVid.play().catch(() => {});
                        }
                    }

                    const siteName = settings.siteName || settings.site_name;
                    if (siteName) {
                        const brandEl = document.getElementById('sidebarBrandName');
                        if (brandEl) brandEl.innerText = siteName;
                        document.title = siteName + ' — Master Admin Panel';
                    }

                    const logoUrl = settings.logo_url || settings.site_logo;
                    if (logoUrl) {
                        const sidebarLogo = document.getElementById('sidebarLogoImg');
                        if (sidebarLogo) {
                            sidebarLogo.src = logoUrl;
                            sidebarLogo.classList.remove('hidden');
                        }
                        const sidebarIcon = document.getElementById('sidebarDefaultIcon');
                        if (sidebarIcon) sidebarIcon.classList.add('hidden');

                        const previewLogo = document.getElementById('previewLogoImg');
                        if (previewLogo) {
                            previewLogo.src = logoUrl;
                            previewLogo.classList.remove('hidden');
                        }
                        const logoPlaceholder = document.getElementById('previewLogoPlaceholder');
                        if (logoPlaceholder) logoPlaceholder.classList.add('hidden');
                    }

                    const faviconUrl = settings.favicon_url || settings.site_favicon;
                    if (faviconUrl) {
                        const previewFav = document.getElementById('previewFaviconImg');
                        if (previewFav) previewFav.src = faviconUrl;
                        const adminFav = document.getElementById('admin-favicon');
                        if (adminFav) adminFav.href = faviconUrl;
                    }

                    try {
                        localStorage.setItem('rexxo_site_settings', JSON.stringify(settings));
                        window.dispatchEvent(new Event('storage'));
                    } catch(err) {}

                    showToastNotice('Settings Saved Successfully!');
                } else {
                    showToastNotice(result.message || 'Failed to save settings!');
                }
            } catch(e) {
                console.error(e);
                showToastNotice('Network Error while saving!');
            }
            
            btn.innerHTML = ogHtml;
            lucide.createIcons();
        }

        async function clearSystemCache() {
            try {
                const btn = document.querySelector('button[onclick="clearSystemCache()"]');
                const ogHtml = btn.innerHTML;
                btn.innerHTML = `<i data-lucide="loader-2" class="w-3.5 h-3.5 animate-spin"></i> Clearing...`;
                lucide.createIcons();
                
                const res = await fetch('/api/clear-cache', { method: 'POST' });
                
                if (res.ok) {
                    showToastNotice('System Cache Cleared Successfully!');
                } else {
                    showToastNotice('Failed to clear cache.');
                }
                
                btn.innerHTML = ogHtml;
                lucide.createIcons();
            } catch(e) {
                showToastNotice('Network error while clearing cache.');
            }
        }

        // ── COURIER HUB ──
        const defaultCouriers = [
            { id: 1, name: 'Steadfast Courier', phone: '09612-000000', trackUrl: 'https://steadfast.com.bd/track', zone: 'Nationwide', apiKey: '', status: 'active', rate: '70-130' },
            { id: 2, name: 'Pathao Courier', phone: '09612-300300', trackUrl: 'https://merchant.pathao.com', zone: 'Dhaka, Chittagong, Sylhet', apiKey: '', status: 'active', rate: '60-150' },
            { id: 3, name: 'RedX', phone: '09612-000033', trackUrl: 'https://redx.com.bd/track', zone: 'Nationwide', apiKey: '', status: 'active', rate: '70-120' },
            { id: 4, name: 'Sundarban Courier', phone: '02-9550052', trackUrl: 'https://sundarbancourierservice.com', zone: 'Nationwide (branch-based)', apiKey: '', status: 'active', rate: '50-200' },
            { id: 5, name: 'Paperfly', phone: '09610-000222', trackUrl: 'https://go.paperfly.com.bd', zone: 'Nationwide', apiKey: '', status: 'inactive', rate: '55-130' },
            { id: 6, name: 'eCourier', phone: '09612-100100', trackUrl: 'https://ecourier.com.bd/track', zone: 'Dhaka, Major Cities', apiKey: '', status: 'inactive', rate: '60-120' }
        ];

        let couriers = JSON.parse(localStorage.getItem('rexxo_couriers')) || defaultCouriers;

        function saveCouriers() {
            localStorage.setItem('rexxo_couriers', JSON.stringify(couriers));
        }

        function addCourier() {
            const name = document.getElementById('newCourierName').value.trim();
            const phone = document.getElementById('newCourierPhone').value.trim();
            const trackUrl = document.getElementById('newCourierUrl').value.trim();
            if (!name) { alert('Courier Name is required.'); return; }

            couriers.push({
                id: Date.now(),
                name,
                phone: phone || '—',
                trackUrl: trackUrl || '',
                zone: 'Custom',
                apiKey: '',
                status: 'active',
                rate: '—'
            });
            saveCouriers();
            document.getElementById('newCourierName').value = '';
            document.getElementById('newCourierPhone').value = '';
            document.getElementById('newCourierUrl').value = '';
            document.getElementById('addCourierForm').classList.add('hidden');
            renderCourierCards();
            showToastNotice(`Courier "${name}" added successfully!`);
        }

        function toggleCourierStatus(id) {
            const c = couriers.find(x => x.id === id);
            if (c) {
                c.status = c.status === 'active' ? 'inactive' : 'active';
                saveCouriers();
                renderCourierCards();
            }
        }

        function deleteCourier(id) {
            const c = couriers.find(x => x.id === id);
            if (!c) return;
            if (!confirm(`Delete "${c.name}"? This cannot be undone.`)) return;
            couriers = couriers.filter(x => x.id !== id);
            saveCouriers();
            renderCourierCards();
            showToastNotice(`Courier "${c.name}" removed.`);
        }

        function editCourier(id) {
            const c = couriers.find(x => x.id === id);
            if (!c) return;
            const newName = prompt('Edit Courier Name:', c.name);
            if (newName && newName.trim()) {
                c.name = newName.trim();
                const newPhone = prompt('Edit Contact Number:', c.phone);
                if (newPhone !== null) c.phone = newPhone.trim() || '—';
                const newUrl = prompt('Edit Tracking URL:', c.trackUrl);
                if (newUrl !== null) c.trackUrl = newUrl.trim();
                const newZone = prompt('Edit Delivery Zone:', c.zone);
                if (newZone !== null) c.zone = newZone.trim() || 'Custom';
                const newRate = prompt('Edit Delivery Rate (৳):', c.rate);
                if (newRate !== null) c.rate = newRate.trim() || '—';
                saveCouriers();
                renderCourierCards();
                showToastNotice(`Courier "${c.name}" updated!`);
            }
        }

        function editCourierApiKey(id) {
            const c = couriers.find(x => x.id === id);
            if (!c) return;
            const key = prompt('Enter API Key / Secret for ' + c.name + ':', c.apiKey);
            if (key !== null) {
                c.apiKey = key.trim();
                saveCouriers();
                renderCourierCards();
                showToastNotice(`API Key ${key ? 'saved' : 'cleared'} for "${c.name}".`);
            }
        }

        function renderCourierCards() {
            const grid = document.getElementById('courierCardsGrid');
            const countLabel = document.getElementById('courierCountLabel');
            if (!grid) return;

            const active = couriers.filter(c => c.status === 'active').length;
            countLabel.innerText = `${couriers.length} Courier Partners (${active} Active)`;

            grid.innerHTML = couriers.map(c => {
                const isActive = c.status === 'active';
                const statusBadge = isActive
                    ? `<span class="bg-emerald-100 text-emerald-700 px-2.5 py-1 rounded-full text-[10px] font-bold uppercase">● Active</span>`
                    : `<span class="bg-rose-100 text-rose-600 px-2.5 py-1 rounded-full text-[10px] font-bold uppercase">○ Inactive</span>`;
                const borderColor = isActive ? 'border-emerald-300/60' : 'border-[#e2e8f0]';
                const bgColor = isActive ? 'bg-gradient-to-br from-white to-emerald-50/30' : 'bg-[#fafafa]';
                const hasKey = c.apiKey && c.apiKey.length > 0;
                const keyBadge = hasKey
                    ? `<span class="text-[10px] bg-sky-100 text-sky-700 px-2 py-0.5 rounded-full font-bold">🔑 API Connected</span>`
                    : `<span class="text-[10px] bg-amber-100 text-amber-700 px-2 py-0.5 rounded-full font-bold">⚠️ No API Key</span>`;

                return `
                    <div class="${bgColor} border ${borderColor} rounded-2xl p-5 space-y-3 hover:shadow-md transition-shadow">
                        <div class="flex justify-between items-start">
                            <div>
                                <h4 class="text-[15px] font-bold text-[#0f172a] uppercase">${c.name}</h4>
                                <div class="flex items-center gap-2 mt-1">
                                    ${statusBadge}
                                    ${keyBadge}
                                </div>
                            </div>
                            <button onclick="toggleCourierStatus(${c.id})" class="text-[10px] font-bold uppercase px-2.5 py-1 rounded-lg border cursor-pointer transition-colors ${isActive ? 'bg-rose-50 text-rose-600 border-rose-200 hover:bg-rose-100' : 'bg-emerald-50 text-emerald-600 border-emerald-200 hover:bg-emerald-100'}">
                                ${isActive ? 'Deactivate' : 'Activate'}
                            </button>
                        </div>

                        <div class="space-y-1.5 text-[12px]">
                            <div class="flex items-center gap-2 text-[#475569]">
                                <span class="font-bold text-[#94a3b8] w-20 shrink-0">PHONE:</span>
                                <span class="font-bold">${c.phone}</span>
                            </div>
                            <div class="flex items-center gap-2 text-[#475569]">
                                <span class="font-bold text-[#94a3b8] w-20 shrink-0">ZONE:</span>
                                <span class="font-bold">${c.zone}</span>
                            </div>
                            <div class="flex items-center gap-2 text-[#475569]">
                                <span class="font-bold text-[#94a3b8] w-20 shrink-0">RATE (৳):</span>
                                <span class="font-bold font-mono text-[#0284c7]">৳${c.rate} BDT</span>
                            </div>
                            ${c.trackUrl ? `<div class="flex items-center gap-2 text-[#475569]">
                                <span class="font-bold text-[#94a3b8] w-20 shrink-0">TRACK:</span>
                                <a href="${c.trackUrl}" target="_blank" class="font-bold text-[#0284c7] hover:underline truncate">${c.trackUrl} ↗</a>
                            </div>` : ''}
                        </div>

                        <div class="flex items-center gap-2 pt-2 border-t border-[#e2e8f0]">
                            <button onclick="editCourier(${c.id})" class="flex-1 px-3 py-1.5 bg-sky-50 hover:bg-sky-100 text-[#0284c7] rounded-lg text-[11px] font-bold border border-sky-200 transition-colors cursor-pointer">✏️ Edit</button>
                            <button onclick="editCourierApiKey(${c.id})" class="flex-1 px-3 py-1.5 bg-amber-50 hover:bg-amber-100 text-amber-700 rounded-lg text-[11px] font-bold border border-amber-200 transition-colors cursor-pointer">🔑 API Key</button>
                            <button onclick="deleteCourier(${c.id})" class="px-3 py-1.5 bg-rose-50 hover:bg-rose-100 text-rose-600 rounded-lg text-[11px] font-bold border border-rose-200 transition-colors cursor-pointer">🗑️</button>
                        </div>
                    </div>
                `;
            }).join('');
        }

        // ── SEND COURIER / HISTORY ──
        let courierHistory = [
            { date: '2026-08-01', id: '#RX-8890', client: 'Jahid Hasan', courier: 'Steadfast Courier', trackId: 'SF-1029348', status: 'Delivered' },
            { date: '2026-08-02', id: '#RX-8902', client: 'Fatima Begum', courier: 'Pathao Courier', trackId: 'PH-9923841', status: 'In Transit' },
            { date: '2026-08-02', id: '#RX-8905', client: 'Kamrul Islam', courier: 'RedX', trackId: 'RX-774129', status: 'In Transit' },
            { date: '2026-08-03', id: '#RX-8911', client: 'Afsana Mimi', courier: 'Steadfast Courier', trackId: 'SF-1033011', status: 'Dispatched' }
        ];

        function renderSendCourierTable() {
            const tbody = document.getElementById('sendCourierTableBody');
            if (!tbody) return;
            // Filter masterOrders for pending dispatch
            const pending = masterOrders.filter(o => o.status === 'Pending Dispatch');
            const activeCouriers = couriers.filter(c => c.status === 'active');
            const courierOptions = activeCouriers.map(c => `<option value="${c.name}">${c.name}</option>`).join('');

            if (pending.length === 0) {
                tbody.innerHTML = `<tr><td colspan="6" class="p-6 text-center text-[#94a3b8] font-bold">No pending orders to dispatch.</td></tr>`;
                return;
            }

            tbody.innerHTML = pending.map(o => `
                <tr class="hover:bg-sky-50/50">
                    <td class="p-3.5"><input type="checkbox" class="rounded border-gray-300"></td>
                    <td class="p-3.5 font-mono text-[#0f172a] font-bold">${o.id}</td>
                    <td class="p-3.5 font-bold">${o.client}</td>
                    <td class="p-3.5 font-mono text-[#0284c7] font-bold">৳${o.amt}</td>
                    <td class="p-3.5">
                        <select class="w-full px-2 py-1.5 text-[11px] font-bold border border-[#cbd5e1] rounded-lg outline-none bg-white">
                            <option value="">Select Courier...</option>
                            ${courierOptions}
                        </select>
                    </td>
                    <td class="p-3.5 text-center">
                        <button onclick="dispatchSingleOrder('${o.id}')" class="px-3 py-1.5 bg-emerald-50 hover:bg-emerald-100 text-emerald-700 rounded-lg text-[11px] font-bold border border-emerald-200 transition-colors cursor-pointer">
                            Send Now
                        </button>
                    </td>
                </tr>
            `).join('');
        }

        function dispatchSingleOrder(orderId) {
            alert(`In a real app, this would send Order ${orderId} to the selected Courier's API and generate a tracking number.`);
            // Mock the action
            const orderIndex = masterOrders.findIndex(o => o.id === orderId);
            if (orderIndex > -1) {
                masterOrders[orderIndex].status = 'In Transit';
                courierHistory.unshift({
                    date: new Date().toISOString().split('T')[0],
                    id: orderId,
                    client: masterOrders[orderIndex].client,
                    courier: 'Selected Courier',
                    trackId: 'TBD-' + Math.floor(Math.random() * 100000),
                    status: 'Dispatched'
                });
                renderSendCourierTable();
                filterCourierHistory();
                renderOrdersTable();
                showToastNotice(`Order ${orderId} marked as Dispatched.`);
            }
        }

        function filterCourierHistory() {
            const tbody = document.getElementById('courierHistoryTableBody');
            if (!tbody) return;
            const search = document.getElementById('courierHistorySearch').value.toLowerCase();
            const filter = document.getElementById('courierHistoryFilter').value;

            let filtered = courierHistory.filter(h => {
                const matchesSearch = h.id.toLowerCase().includes(search) || h.client.toLowerCase().includes(search) || h.trackId.toLowerCase().includes(search);
                const matchesFilter = filter === 'all' || h.courier === filter;
                return matchesSearch && matchesFilter;
            });

            if (filtered.length === 0) {
                tbody.innerHTML = `<tr><td colspan="6" class="p-6 text-center text-[#94a3b8] font-bold">No history found matching criteria.</td></tr>`;
                return;
            }

            tbody.innerHTML = filtered.map(h => {
                let statusBadge = '';
                if (h.status === 'Delivered') statusBadge = `<span class="bg-emerald-100 text-emerald-700 px-2 py-1 rounded-full text-[10px] font-bold uppercase">${h.status}</span>`;
                else if (h.status === 'In Transit') statusBadge = `<span class="bg-sky-100 text-sky-700 px-2 py-1 rounded-full text-[10px] font-bold uppercase">${h.status}</span>`;
                else statusBadge = `<span class="bg-amber-100 text-amber-700 px-2 py-1 rounded-full text-[10px] font-bold uppercase">${h.status}</span>`;

                return `
                    <tr class="hover:bg-sky-50/50">
                        <td class="p-3.5 text-[#64748b] font-bold">${h.date}</td>
                        <td class="p-3.5 font-mono text-[#0f172a] font-bold">${h.id}</td>
                        <td class="p-3.5 font-bold">${h.client}</td>
                        <td class="p-3.5 font-bold text-[#0284c7]">${h.courier}</td>
                        <td class="p-3.5 text-center font-mono text-[#64748b] text-[11px]">${h.trackId}</td>
                        <td class="p-3.5 text-center">${statusBadge}</td>
                    </tr>
                `;
            }).join('');
        }

        // ── PRODUCT CATALOG ──
        // Real Products List from Controller
        @php
            $formattedProducts = isset($products) ? $products->map(function($p) {
                return [
                    'id' => $p->id,
                    'name' => $p->name,
                    'slug' => $p->slug,
                    'size' => is_array($p->sizes) ? implode(', ', $p->sizes) : ($p->sizes ?? '100ml'),
                    'category' => $p->category->name ?? ucfirst($p->gender ?? 'Unisex'),
                    'price' => (float)$p->price,
                    'stock' => (int)($p->stock ?? 50),
                    'status' => (($p->stock ?? 50) > 0 ? 'Active' : 'Out of Stock'),
                    'img' => $p->primary_image_url ?? ''
                ];
            })->values()->all() : [];
        @endphp
        let productsList = {!! json_encode($formattedProducts) !!};

        function deleteProduct(id, name) {
            if (!confirm(`Are you sure you want to delete "${name}" from the catalog?`)) {
                return;
            }
            const form = document.createElement('form');
            form.method = 'POST';
            form.action = `/admin/products/${id}`;
            
            const csrfInput = document.createElement('input');
            csrfInput.type = 'hidden';
            csrfInput.name = '_token';
            csrfInput.value = '{{ csrf_token() }}';
            form.appendChild(csrfInput);

            const methodInput = document.createElement('input');
            methodInput.type = 'hidden';
            methodInput.name = '_method';
            methodInput.value = 'DELETE';
            form.appendChild(methodInput);

            document.body.appendChild(form);
            form.submit();
        }

        function renderProductsTable() {
            const tbody = document.getElementById('productsTableBody');
            const dashStockBody = document.getElementById('dashStockTableBody');
            
            if (dashStockBody) {
                // Sort by lowest stock first for dashboard alert
                const sorted = [...productsList].sort((a, b) => a.stock - b.stock);
                dashStockBody.innerHTML = sorted.slice(0, 5).map(p => {
                    let statusBadge = '';
                    if (p.status === 'Active') statusBadge = `<span class="bg-emerald-100 text-emerald-700 px-2 py-0.5 rounded-full text-[10px] font-bold uppercase">Active</span>`;
                    else if (p.status === 'Draft') statusBadge = `<span class="bg-gray-100 text-gray-700 px-2 py-0.5 rounded-full text-[10px] font-bold uppercase">Draft</span>`;
                    else statusBadge = `<span class="bg-rose-100 text-rose-700 px-2 py-0.5 rounded-full text-[10px] font-bold uppercase">Out of Stock</span>`;
                    
                    const stockClass = p.stock === 0 ? 'text-rose-600' : (p.stock < 10 ? 'text-amber-600' : 'text-[#475569]');
                    
                    return `
                        <tr class="hover:bg-sky-50">
                            <td class="p-3.5 font-bold">${p.name}</td>
                            <td class="p-3.5 font-mono font-bold text-center ${stockClass}">${p.stock}</td>
                            <td class="p-3.5 text-center">${statusBadge}</td>
                        </tr>
                    `;
                }).join('');
            }

            const countLabel = document.getElementById('productCountLabel');
            if (!tbody) return;

            const searchInput = document.getElementById('productSearchInput');
            const searchTerm = searchInput ? searchInput.value.toLowerCase() : '';

            const filtered = productsList.filter(p => p.name.toLowerCase().includes(searchTerm) || p.category.toLowerCase().includes(searchTerm));
            
            if (countLabel) {
                countLabel.innerText = `${filtered.length} Products Found`;
            }

            if (filtered.length === 0) {
                tbody.innerHTML = `<tr><td colspan="7" class="p-8 text-center text-[#94a3b8] font-bold">No products match your search.</td></tr>`;
                return;
            }

            tbody.innerHTML = filtered.map(p => {
                let statusBadge = '';
                if (p.status === 'Active') statusBadge = `<span class="bg-emerald-100 text-emerald-700 px-2 py-1 rounded-full text-[10px] font-bold uppercase">Active</span>`;
                else if (p.status === 'Draft') statusBadge = `<span class="bg-gray-100 text-gray-700 px-2 py-1 rounded-full text-[10px] font-bold uppercase">Draft</span>`;
                else statusBadge = `<span class="bg-rose-100 text-rose-700 px-2 py-1 rounded-full text-[10px] font-bold uppercase">Out of Stock</span>`;

                return `
                    <tr class="hover:bg-[#f8fafc] group">
                        <td class="p-3.5">
                            <div class="w-10 h-10 bg-[#e2e8f0] rounded-lg flex items-center justify-center text-[#94a3b8] overflow-hidden">
                                ${p.img ? `<img src="${p.img}" class="w-full h-full object-contain" />` : `<i data-lucide="image" class="w-4 h-4"></i>`}
                            </div>
                        </td>
                        <td class="p-3.5">
                            <div class="font-bold text-[#0f172a]">${p.name}</div>
                            <div class="text-[11px] text-[#64748b]">${p.size}</div>
                        </td>
                        <td class="p-3.5 text-[#64748b] font-bold uppercase text-[11px]">${p.category}</td>
                        <td class="p-3.5 font-bold font-mono text-[#0284c7]">৳${p.price.toLocaleString()}</td>
                        <td class="p-3.5 font-mono font-bold text-[#475569]">${p.stock}</td>
                        <td class="p-3.5 text-center">${statusBadge}</td>
                        <td class="p-3.5 text-right space-x-1 whitespace-nowrap">
                            <a href="/admin/products/${p.id}/edit" class="px-3 py-1.5 bg-[#0284c7]/10 hover:bg-[#0284c7] border border-[#0284c7]/30 hover:border-[#0284c7] text-[#0284c7] hover:text-white rounded-lg text-[11px] font-bold transition-all shadow-xs inline-flex items-center gap-1">
                                <i data-lucide="edit-3" class="w-3 h-3"></i> Edit
                            </a>
                            <a href="/product/${p.slug || p.id}" target="_blank" class="px-2 py-1.5 bg-white border border-[#cbd5e1] text-[#64748b] hover:text-[#0284c7] rounded-lg text-[11px] font-bold transition-colors inline-flex items-center" title="View live">
                                <i data-lucide="external-link" class="w-3 h-3"></i>
                            </a>
                            <button onclick="deleteProduct(${p.id}, '${p.name.replace(/'/g, "\\'")}')" class="px-2 py-1.5 bg-white border border-rose-200 text-rose-600 hover:bg-rose-50 rounded-lg text-[11px] font-bold transition-colors shadow-xs cursor-pointer inline-flex items-center" title="Delete">
                                <i data-lucide="trash-2" class="w-3 h-3"></i>
                            </button>
                        </td>
                    </tr>
                `;
            }).join('');
            
            // Re-render lucide icons inside the new table HTML
            lucide.createIcons();
        }

        // ── PURCHASE HUB ──
        let purchaseData = [
            { id: 'PO-2026-085', date: '2026-07-28', supplier: 'France Fragrance Oils Ltd.', items: 'Amber Essential Oil (1L)', cost: 125000, status: 'Paid' },
            { id: 'PO-2026-086', date: '2026-07-30', supplier: 'BD Premium Packaging', items: 'Custom Glass Bottles (500x)', cost: 45000, status: 'Paid' },
            { id: 'PO-2026-087', date: '2026-08-01', supplier: 'Dubai Luxury Glass Works', items: 'Gold Plated Caps (200x)', cost: 32000, status: 'Due' },
            { id: 'PO-2026-088', date: '2026-08-02', supplier: 'France Fragrance Oils Ltd.', items: 'Rose Absolute (500ml)', cost: 85000, status: 'Partial' }
        ];

        function renderPurchaseTable() {
            const tbody = document.getElementById('purchaseTableBody');
            const countLabel = document.getElementById('purchaseCountLabel');
            if (!tbody) return;

            const searchInput = document.getElementById('purchaseSearchInput');
            const searchTerm = searchInput ? searchInput.value.toLowerCase() : '';

            const filtered = purchaseData.filter(p => p.id.toLowerCase().includes(searchTerm) || p.supplier.toLowerCase().includes(searchTerm));
            
            if (countLabel) {
                countLabel.innerText = `${filtered.length} Purchase Orders`;
            }

            if (filtered.length === 0) {
                tbody.innerHTML = `<tr><td colspan="6" class="p-8 text-center text-[#94a3b8] font-bold">No purchases match your search.</td></tr>`;
                return;
            }

            tbody.innerHTML = filtered.map(p => {
                let statusBadge = '';
                if (p.status === 'Paid') statusBadge = `<span class="bg-emerald-100 text-emerald-700 px-2 py-1 rounded-full text-[10px] font-bold uppercase">Paid</span>`;
                else if (p.status === 'Due') statusBadge = `<span class="bg-rose-100 text-rose-700 px-2 py-1 rounded-full text-[10px] font-bold uppercase">Due</span>`;
                else statusBadge = `<span class="bg-amber-100 text-amber-700 px-2 py-1 rounded-full text-[10px] font-bold uppercase">Partial</span>`;

                return `
                    <tr class="hover:bg-[#f8fafc] group">
                        <td class="p-3.5 text-[#64748b] font-bold">${p.date}</td>
                        <td class="p-3.5 font-bold font-mono text-[#0f172a]">${p.id}</td>
                        <td class="p-3.5 font-bold text-[#0284c7]">${p.supplier}</td>
                        <td class="p-3.5 font-bold font-mono text-[#475569]">৳${p.cost.toLocaleString()}</td>
                        <td class="p-3.5 text-center">${statusBadge}</td>
                        <td class="p-3.5 text-right space-x-1">
                            <button onclick="alert('View Purchase ${p.id}')" class="px-3 py-1.5 bg-white border border-[#cbd5e1] text-[#475569] hover:text-[#0284c7] hover:border-[#0284c7] rounded-lg text-[11px] font-bold transition-colors shadow-xs cursor-pointer">View</button>
                            <button onclick="alert('Edit Purchase ${p.id}')" class="px-3 py-1.5 bg-white border border-[#cbd5e1] text-[#475569] hover:text-[#0284c7] hover:border-[#0284c7] rounded-lg text-[11px] font-bold transition-colors shadow-xs cursor-pointer">Edit</button>
                        </td>
                    </tr>
                `;
            }).join('');
            
            // Re-render lucide icons inside the new table HTML
            lucide.createIcons();
        }

        // ── CUSTOMERS DIRECTORY ──
        let customerData = [
            { id: 1, name: 'Asif Mahmud', phone: '01700123456', address: 'House 12, Road 5, Dhanmondi, Dhaka', orders: 12 },
            { id: 2, name: 'Jahid Hasan', phone: '01811223344', address: 'Sector 4, Uttara, Dhaka', orders: 4 },
            { id: 3, name: 'Fatima Begum', phone: '01922334455', address: 'Block C, Bashundhara R/A, Dhaka', orders: 8 },
            { id: 4, name: 'Kamrul Islam', phone: '01633445566', address: 'Agrabad, Chattogram', orders: 2 },
            { id: 5, name: 'Afsana Mimi', phone: '01544556677', address: 'Zindabazar, Sylhet', orders: 1 }
        ];

        function renderCustomersTable() {
            const tbody = document.getElementById('customersTableBody');
            const countLabel = document.getElementById('customerCountLabel');
            if (!tbody) return;

            const searchInput = document.getElementById('customerSearchInput');
            const searchTerm = searchInput ? searchInput.value.toLowerCase() : '';

            const filtered = customerData.filter(c => c.name.toLowerCase().includes(searchTerm) || c.phone.includes(searchTerm));
            
            if (countLabel) {
                countLabel.innerText = `${filtered.length} Customers`;
            }

            if (filtered.length === 0) {
                tbody.innerHTML = `<tr><td colspan="5" class="p-8 text-center text-[#94a3b8] font-bold">No customers match your search.</td></tr>`;
                return;
            }

            tbody.innerHTML = filtered.map(c => {
                return `
                    <tr class="hover:bg-[#f8fafc] group">
                        <td class="p-3.5">
                            <div class="flex items-center gap-3">
                                <div class="w-8 h-8 rounded-full bg-gradient-to-br from-sky-100 to-sky-200 text-sky-700 flex items-center justify-center font-bold text-[12px]">
                                    ${c.name.charAt(0)}
                                </div>
                                <div class="font-bold text-[#0f172a]">${c.name}</div>
                            </div>
                        </td>
                        <td class="p-3.5 font-bold font-mono text-[#475569]">${c.phone}</td>
                        <td class="p-3.5 text-[#64748b] text-[12px]">${c.address}</td>
                        <td class="p-3.5 text-center font-bold font-mono text-[#0284c7]">${c.orders}</td>
                        <td class="p-3.5 text-right space-x-1">
                            <button onclick="alert('View Customer ${c.id}')" class="px-3 py-1.5 bg-white border border-[#cbd5e1] text-[#475569] hover:text-[#0284c7] hover:border-[#0284c7] rounded-lg text-[11px] font-bold transition-colors shadow-xs cursor-pointer">View History</button>
                        </td>
                    </tr>
                `;
            }).join('');
            
            // Re-render lucide icons inside the new table HTML
            lucide.createIcons();
        }

        // ── SUPPLIER DIRECTORY ──
        let supplierData = [
            { id: 1, company: 'France Fragrance Oils Ltd.', contact: 'Pierre Dubois', phone: '+33 1 42 68 53', country: 'France', pos: 24 },
            { id: 2, company: 'Dubai Luxury Glass Works', contact: 'Ahmed Al-Fayed', phone: '+971 4 223 4567', country: 'UAE', pos: 15 },
            { id: 3, capName: 'BD Premium Packaging', contact: 'Shafiqur Rahman', phone: '+880 171 234 5678', country: 'Bangladesh', pos: 32 },
            { id: 4, company: 'Swiss Aromatics Group', contact: 'Helena Mueller', phone: '+41 44 123 4567', country: 'Switzerland', pos: 8 },
            { id: 5, company: 'Local Labels & Print', contact: 'Rafiqul Islam', phone: '+880 182 345 6789', country: 'Bangladesh', pos: 45 }
        ];

        function renderSupplierTable() {
            const tbody = document.getElementById('supplierTableBody');
            const countLabel = document.getElementById('supplierCountLabel');
            if (!tbody) return;

            const searchInput = document.getElementById('supplierSearchInput');
            const searchTerm = searchInput ? searchInput.value.toLowerCase() : '';

            const filtered = supplierData.filter(s => 
                (s.company && s.company.toLowerCase().includes(searchTerm)) || 
                (s.capName && s.capName.toLowerCase().includes(searchTerm)) ||
                s.phone.includes(searchTerm)
            );
            
            if (countLabel) {
                countLabel.innerText = `${filtered.length} Suppliers`;
            }

            if (filtered.length === 0) {
                tbody.innerHTML = `<tr><td colspan="5" class="p-8 text-center text-[#94a3b8] font-bold">No suppliers match your search.</td></tr>`;
                return;
            }

            tbody.innerHTML = filtered.map(s => {
                const displayName = s.company || s.capName;
                return `
                    <tr class="hover:bg-[#f8fafc] group">
                        <td class="p-3.5">
                            <div class="flex items-center gap-3">
                                <div class="w-8 h-8 rounded-full bg-gradient-to-br from-indigo-100 to-indigo-200 text-indigo-700 flex items-center justify-center font-bold text-[12px]">
                                    ${displayName.charAt(0)}
                                </div>
                                <div>
                                    <div class="font-bold text-[#0f172a]">${displayName}</div>
                                    <div class="text-[11px] text-[#64748b]">${s.country}</div>
                                </div>
                            </div>
                        </td>
                        <td class="p-3.5 font-bold text-[#475569]">${s.contact}</td>
                        <td class="p-3.5 font-bold font-mono text-[#0284c7]">${s.phone}</td>
                        <td class="p-3.5 text-center font-bold font-mono text-[#475569]">${s.pos}</td>
                        <td class="p-3.5 text-right space-x-1">
                            <button onclick="alert('Edit Supplier ${s.id}')" class="px-3 py-1.5 bg-white border border-[#cbd5e1] text-[#475569] hover:text-[#0284c7] hover:border-[#0284c7] rounded-lg text-[11px] font-bold transition-colors shadow-xs cursor-pointer">Edit</button>
                        </td>
                    </tr>
                `;
            }).join('');
            
            // Re-render lucide icons inside the new table HTML
            lucide.createIcons();
        }

        window.addEventListener('DOMContentLoaded', () => {
            lucide.createIcons();
            renderOrdersTable();
            renderCourierCards();
            renderSendCourierTable();
            filterCourierHistory();
            renderProductsTable();
            renderPurchaseTable();
            renderCustomersTable();
            renderSupplierTable();
            fetchSettingsOnLoad();

            // Auto-switch to Menu Builder if a success flash is present (after add/edit/delete)
            @if(session('success'))
            switchSection('menu');
            @endif
        });

        // ── API SETTINGS: TAB SWITCHING ──
        function switchApiTab(tabId) {
            // Hide all api panels
            document.querySelectorAll('.api-tab-panel').forEach(p => p.classList.add('hidden'));
            // Deactivate all api tab buttons
            document.querySelectorAll('[id^="api-tab-"]').forEach(btn => {
                btn.classList.remove('bg-[#0284c7]', 'text-white', 'shadow-xs');
                btn.classList.add('text-[#475569]', 'hover:bg-[#f1f5f9]');
            });
            // Show selected panel
            const panel = document.getElementById('api-panel-' + tabId);
            if (panel) panel.classList.remove('hidden');
            // Activate selected tab button
            const btn = document.getElementById('api-tab-' + tabId);
            if (btn) {
                btn.classList.add('bg-[#0284c7]', 'text-white', 'shadow-xs');
                btn.classList.remove('text-[#475569]', 'hover:bg-[#f1f5f9]');
            }
            lucide.createIcons();
        }

        // ── SMS: SAVE SETTINGS ──
        async function saveApiSettings(section) {
            // Collect all SMS-related inputs
            const fields = [
                'sms_on_new_order', 'sms_on_dispatch', 'sms_on_delivered', 'sms_on_cancelled',
                'sms_bulksmsbd_enabled', 'sms_mimsms_enabled',
                'bulksmsbd_api_key', 'bulksmsbd_sender_id', 'bulksmsbd_base_url',
                'mimsms_api_key', 'mimsms_sender_id', 'mimsms_base_url', 'mimsms_type',
                'sms_template_new_order', 'sms_template_dispatch'
            ];
            const formData = new FormData();
            fields.forEach(f => {
                const el = document.querySelector(`[name="${f}"]`);
                if (!el) return;
                if (el.type === 'checkbox') {
                    formData.append(f, el.checked ? '1' : '0');
                } else {
                    formData.append(f, el.value);
                }
            });
            try {
                const res = await fetch('/api/settings', {
                    method: 'POST',
                    headers: { 'Accept': 'application/json' },
                    body: formData
                });
                const result = await res.json();
                if (res.ok) {
                    showToastNotice('SMS Settings saved successfully!');
                } else {
                    showToastNotice(result.message || 'Failed to save!');
                }
            } catch (e) {
                showToastNotice('Network error while saving SMS settings.');
            }
        }

        // ── SMS: TEST GATEWAY ──
        async function testSmsGateway(gateway) {
            const phoneInput = document.getElementById(gateway + '_test_phone');
            const resultDiv = document.getElementById(gateway + '_test_result');
            const phone = phoneInput ? phoneInput.value.trim() : '';

            if (!phone || phone.length < 11) {
                showToastNotice('Please enter a valid Bangladeshi phone number (11 digits)');
                return;
            }

            resultDiv.className = 'text-[12px] font-mono p-3 rounded-lg bg-[#f8fafc] border border-[#e2e8f0] text-[#475569]';
            resultDiv.classList.remove('hidden');
            resultDiv.innerHTML = '<span class="animate-pulse">⏳ Sending test SMS...</span>';

            try {
                const formData = new FormData();
                formData.append('gateway', gateway);
                formData.append('phone', phone);
                formData.append('message', `TEST: API connection from {{ $siteSettings['siteName'] ?? 'RaaxO Admin' }} is working! — {{ now()->format('d M Y H:i') }}`);

                const res = await fetch('/admin/sms/test', {
                    method: 'POST',
                    headers: {
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || ''
                    },
                    body: formData
                });
                const data = await res.json();
                if (res.ok && data.success) {
                    resultDiv.className = 'text-[12px] font-mono p-3 rounded-lg bg-emerald-50 border border-emerald-200 text-emerald-800';
                    resultDiv.innerHTML = `✔ ${data.message || 'SMS sent successfully!'}`;
                    showToastNotice('Test SMS sent successfully!');
                } else {
                    resultDiv.className = 'text-[12px] font-mono p-3 rounded-lg bg-rose-50 border border-rose-200 text-rose-800';
                    resultDiv.innerHTML = `✗ ${data.message || 'Failed to send SMS. Check credentials.'}`;
                    showToastNotice('SMS test failed. Check credentials.');
                }
            } catch (e) {
                resultDiv.className = 'text-[12px] font-mono p-3 rounded-lg bg-rose-50 border border-rose-200 text-rose-800';
                resultDiv.innerHTML = `✗ Network error: ${e.message}`;
            }
        }

        // ── SEO: LOAD STATUS ON SECTION OPEN ──
        const _origSwitchSection = switchSection;
        switchSection = function(secId) {
            _origSwitchSection(secId);
            if (secId === 'sitemap') loadSeoStatus();
        };

        async function loadSeoStatus() {
            try {
                const r = await fetch('/admin/seo/status', { headers: { 'Accept': 'application/json' } });
                const d = await r.json();

                // Sitemap
                const sBadge = document.getElementById('sitemap-status-badge');
                const sUpdated = document.getElementById('sitemap-last-updated');
                const sEntries = document.getElementById('sitemap-entries');
                const sCheck = document.getElementById('sitemap-check-card');
                if (d.sitemap?.exists) {
                    sBadge.className = 'bg-emerald-50 text-emerald-700 border border-emerald-200 px-2.5 py-0.5 rounded-full text-[10px] font-bold uppercase';
                    sBadge.textContent = '✔ Exists';
                    if (sUpdated) sUpdated.textContent = d.sitemap.last_updated ?? '—';
                    if (sEntries) sEntries.textContent = (d.sitemap.entries ?? 0) + ' URLs';
                    if (sCheck) { sCheck.className = 'flex items-start gap-2.5 p-3 rounded-xl bg-emerald-50 border border-emerald-200'; sCheck.querySelector('i').setAttribute('data-lucide','check-circle-2'); sCheck.querySelector('i').className='w-4 h-4 text-emerald-600 mt-0.5 shrink-0'; sCheck.querySelector('p:first-child').className='text-[12px] font-bold text-emerald-800'; sCheck.querySelectorAll('p')[1].className='text-[10px] text-emerald-700'; sCheck.querySelectorAll('p')[1].textContent='Sitemap exists — '+d.sitemap.entries+' URLs indexed'; lucide.createIcons(); }
                } else {
                    sBadge.className = 'bg-amber-50 text-amber-700 border border-amber-200 px-2.5 py-0.5 rounded-full text-[10px] font-bold uppercase';
                    sBadge.textContent = '⚠ Not Found';
                    if (sUpdated) sUpdated.textContent = 'Never';
                    if (sEntries) sEntries.textContent = '0';
                }

                // Robots
                const rBadge = document.getElementById('robots-status-badge');
                const rUpdated = document.getElementById('robots-last-updated');
                const rCheck = document.getElementById('robots-check-card');
                if (d.robots?.exists) {
                    rBadge.className = 'bg-emerald-50 text-emerald-700 border border-emerald-200 px-2.5 py-0.5 rounded-full text-[10px] font-bold uppercase';
                    rBadge.textContent = '✔ Exists';
                    if (rUpdated) rUpdated.textContent = d.robots.last_updated ?? '—';
                    if (rCheck) { rCheck.className = 'flex items-start gap-2.5 p-3 rounded-xl bg-emerald-50 border border-emerald-200'; rCheck.querySelector('i').setAttribute('data-lucide','check-circle-2'); rCheck.querySelector('i').className='w-4 h-4 text-emerald-600 mt-0.5 shrink-0'; rCheck.querySelector('p:first-child').className='text-[12px] font-bold text-emerald-800'; rCheck.querySelectorAll('p')[1].className='text-[10px] text-emerald-700'; rCheck.querySelectorAll('p')[1].textContent='robots.txt is live and guides crawlers'; lucide.createIcons(); }
                } else {
                    rBadge.className = 'bg-amber-50 text-amber-700 border border-amber-200 px-2.5 py-0.5 rounded-full text-[10px] font-bold uppercase';
                    rBadge.textContent = '⚠ Not Found';
                    if (rUpdated) rUpdated.textContent = 'Never';
                }
                lucide.createIcons();
            } catch(e) { console.warn('SEO status load error:', e); }
        }

        // ── SEO: GENERATE SITEMAP ──
        async function generateSitemap() {
            const btn = document.getElementById('btn-gen-sitemap');
            const res = document.getElementById('sitemap-result');
            btn.disabled = true;
            btn.innerHTML = '<svg class="animate-spin w-4 h-4 mr-2" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path></svg> Generating...';
            res.className = 'text-[12px] font-mono p-3 rounded-lg bg-[#f8fafc] border text-[#475569]';
            res.classList.remove('hidden');
            res.innerHTML = '⏳ Building sitemap from products and pages...';
            try {
                const r = await fetch('/admin/seo/generate-sitemap', {
                    method: 'POST',
                    headers: { 'Accept': 'application/json', 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '' }
                });
                const d = await r.json();
                if (r.ok && d.success) {
                    res.className = 'text-[12px] font-mono p-3 rounded-lg bg-emerald-50 border border-emerald-200 text-emerald-800';
                    res.innerHTML = `✔ ${d.message}<br>📄 ${d.entries} URLs · Generated: ${d.generated_at}<br><a href="/sitemap.xml" target="_blank" class="underline">View sitemap.xml ↗</a>`;
                    showToastNotice('sitemap.xml generated successfully!');
                    loadSeoStatus();
                } else {
                    res.className = 'text-[12px] font-mono p-3 rounded-lg bg-rose-50 border border-rose-200 text-rose-800';
                    res.innerHTML = `✗ ${d.message}`;
                }
            } catch(e) {
                res.className = 'text-[12px] font-mono p-3 rounded-lg bg-rose-50 border border-rose-200 text-rose-800';
                res.innerHTML = `✗ Network error: ${e.message}`;
            }
            btn.disabled = false;
            btn.innerHTML = '<i data-lucide="refresh-cw" class="w-4 h-4"></i> Generate & Save sitemap.xml';
            lucide.createIcons();
        }

        // ── SEO: GENERATE ROBOTS.TXT ──
        async function generateRobots() {
            const btn = document.getElementById('btn-gen-robots');
            const res = document.getElementById('robots-result');
            btn.disabled = true;
            btn.innerHTML = '<svg class="animate-spin w-4 h-4 mr-2" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path></svg> Generating...';
            res.className = 'text-[12px] font-mono p-3 rounded-lg bg-[#f8fafc] border text-[#475569]';
            res.classList.remove('hidden');
            res.innerHTML = '⏳ Building robots.txt with sitemap reference...';
            try {
                const r = await fetch('/admin/seo/generate-robots', {
                    method: 'POST',
                    headers: { 'Accept': 'application/json', 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '' }
                });
                const d = await r.json();
                if (r.ok && d.success) {
                    res.className = 'text-[12px] font-mono p-3 rounded-lg bg-emerald-50 border border-emerald-200 text-emerald-800';
                    res.innerHTML = `✔ ${d.message}<br>Generated: ${d.generated_at}<br><a href="/robots.txt" target="_blank" class="underline">View robots.txt ↗</a>`;
                    showToastNotice('robots.txt generated successfully!');
                    loadSeoStatus();
                } else {
                    res.className = 'text-[12px] font-mono p-3 rounded-lg bg-rose-50 border border-rose-200 text-rose-800';
                    res.innerHTML = `✗ ${d.message}`;
                }
            } catch(e) {
                res.className = 'text-[12px] font-mono p-3 rounded-lg bg-rose-50 border border-rose-200 text-rose-800';
                res.innerHTML = `✗ Network error: ${e.message}`;
            }
            btn.disabled = false;
            btn.innerHTML = '<i data-lucide="refresh-cw" class="w-4 h-4"></i> Generate & Save robots.txt';
            lucide.createIcons();
        }

        // ── SEO: PING GOOGLE & BING ──
        async function pingSeo() {
            const btn = document.getElementById('btn-ping');
            const res = document.getElementById('ping-result');
            const gBadge = document.getElementById('ping-google-badge');
            const bBadge = document.getElementById('ping-bing-badge');
            btn.disabled = true;
            btn.innerHTML = '<svg class="animate-spin w-4 h-4 mr-2" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path></svg> Pinging...';
            if(gBadge) { gBadge.className='text-[10px] font-bold px-2.5 py-1 rounded-full bg-amber-50 text-amber-700 border border-amber-200 shrink-0 animate-pulse'; gBadge.textContent='Pinging...'; }
            if(bBadge) { bBadge.className='text-[10px] font-bold px-2.5 py-1 rounded-full bg-amber-50 text-amber-700 border border-amber-200 shrink-0 animate-pulse'; bBadge.textContent='Pinging...'; }
            res.className = 'text-[12px] font-mono p-4 rounded-lg bg-[#f8fafc] border text-[#475569]';
            res.classList.remove('hidden');
            res.innerHTML = '⏳ Sending ping to Google & Bing...';
            try {
                const r = await fetch('/admin/seo/ping-search-engines', {
                    method: 'POST',
                    headers: { 'Accept': 'application/json', 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '' }
                });
                const d = await r.json();
                if(gBadge) {
                    const gOk = d.results?.Google?.success;
                    gBadge.className = gOk ? 'text-[10px] font-bold px-2.5 py-1 rounded-full bg-emerald-50 text-emerald-700 border border-emerald-200 shrink-0' : 'text-[10px] font-bold px-2.5 py-1 rounded-full bg-amber-50 text-amber-700 border border-amber-200 shrink-0';
                    gBadge.textContent = gOk ? '✔ Pinged' : '⚠ Check';
                }
                if(bBadge) {
                    const bOk = d.results?.Bing?.success;
                    bBadge.className = bOk ? 'text-[10px] font-bold px-2.5 py-1 rounded-full bg-emerald-50 text-emerald-700 border border-emerald-200 shrink-0' : 'text-[10px] font-bold px-2.5 py-1 rounded-full bg-amber-50 text-amber-700 border border-amber-200 shrink-0';
                    bBadge.textContent = bOk ? '✔ Pinged' : '⚠ Check';
                }
                res.className = d.success
                    ? 'text-[12px] font-mono p-4 rounded-lg bg-emerald-50 border border-emerald-200 text-emerald-800'
                    : 'text-[12px] font-mono p-4 rounded-lg bg-amber-50 border border-amber-200 text-amber-800';
                res.innerHTML = `${d.success ? '✔' : '⚠'} ${d.message}`;
                showToastNotice(d.message);
            } catch(e) {
                res.className = 'text-[12px] font-mono p-4 rounded-lg bg-rose-50 border border-rose-200 text-rose-800';
                res.innerHTML = `✗ Network error: ${e.message}`;
            }
            btn.disabled = false;
            btn.innerHTML = '<i data-lucide="send" class="w-4 h-4"></i> 🚀 Ping Google & Bing Now';
            lucide.createIcons();
        }
    </script>
</body>
</html>
