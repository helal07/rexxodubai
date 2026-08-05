<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $siteSettings['siteName'] ?? 'RaaxO BD' }} — Master Admin Panel</title>
    
    <!-- Dynamic Favicon -->
    @php
        $adminFavicon = !empty($siteSettings['favicon_url']) ? $siteSettings['favicon_url'] : (!empty($siteSettings['site_favicon']) ? $siteSettings['site_favicon'] : '/uploads/settings/favicon_1785930191.ico');
    @endphp
    <link rel="icon" id="admin-favicon" href="{{ $adminFavicon }}">

    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://unpkg.com/lucide@latest"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&family=Fraunces:wght@600;700&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Inter', system-ui, -apple-system, sans-serif; }
        .font-serif { font-family: 'Fraunces', Georgia, serif; }
        
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
    </style>
</head>
<body class="bg-gradient-to-br from-[#e0f2fe] via-[#f0f9ff] to-[#bae6fd] text-[#0f172a] font-sans flex min-h-screen relative overflow-x-hidden selection:bg-[#0284c7] selection:text-white">
    
    <div id="toast" class="hidden fixed top-6 right-6 z-50 bg-[#0284c7] text-white px-5 py-3 rounded-xl shadow-2xl border border-white/30 flex items-center gap-3 animate-fade-in text-[13px] font-bold">
        <i data-lucide="check-circle-2" class="w-5 h-5"></i>
        <span id="toastMsg">Action completed successfully!</span>
    </div>

    <!-- 1. LEFT SIDEBAR MENU BAR -->
    <aside class="w-64 lg:w-72 bg-white/90 backdrop-blur-xl border-r border-[#38bdf8]/30 min-h-screen p-6 flex flex-col justify-between shrink-0 relative z-20 shadow-sm">
        <div class="space-y-8">
            <div class="flex items-center gap-3 border-b border-[#e2e8f0] pb-6">
                <div id="sidebarLogoContainer" class="flex items-center justify-center">
                    @if(!empty($siteSettings['logo_url']) || !empty($siteSettings['site_logo']))
                        <img id="sidebarLogoImg" src="{{ $siteSettings['logo_url'] ?? $siteSettings['site_logo'] }}" alt="Logo" class="max-h-10 max-w-[120px] object-contain rounded-lg shadow-sm" />
                        <div id="sidebarDefaultIcon" class="hidden w-10 h-10 rounded-xl bg-[#0284c7] text-white flex items-center justify-center shadow-md">
                            <i data-lucide="shield-check" class="w-6 h-6"></i>
                        </div>
                    @else
                        <img id="sidebarLogoImg" src="" alt="Logo" class="hidden max-h-10 max-w-[120px] object-contain rounded-lg shadow-sm" />
                        <div id="sidebarDefaultIcon" class="w-10 h-10 rounded-xl bg-[#0284c7] text-white flex items-center justify-center shadow-md">
                            <i data-lucide="shield-check" class="w-6 h-6"></i>
                        </div>
                    @endif
                </div>
                <div>
                    <h1 class="text-[16px] font-serif font-bold text-[#0f172a] uppercase tracking-wide leading-none" id="sidebarBrandName">
                        {{ $siteSettings['siteName'] ?? 'REXXO BD' }}
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
                <button type="button" onclick="switchSection('dashboard')" id="sidebar-btn-dashboard" class="w-full px-4 py-3 text-[13px] font-bold uppercase tracking-wider flex items-center gap-3 rounded-xl transition-all cursor-pointer bg-[#0284c7] text-white shadow-md">
                    <i data-lucide="layout-dashboard" class="w-4 h-4"></i> Dashboard
                </button>

                <!-- 2. ORDERS (SUBMENU: TOTAL ORDERS, SUCCESS ORDERS, RETURN ORDERS) -->
                <div>
                    <button type="button" onclick="toggleSubmenu('orders')" class="w-full px-4 py-3 text-[13px] font-bold uppercase tracking-wider flex items-center justify-between rounded-xl transition-all cursor-pointer text-[#475569] hover:bg-[#f1f5f9] hover:text-[#0284c7]">
                        <div class="flex items-center gap-3">
                            <i data-lucide="shopping-bag" class="w-4 h-4"></i> Orders
                        </div>
                        <span data-chevron="orders" class="submenu-chevron"><i data-lucide="chevron-down" class="w-4 h-4"></i></span>
                    </button>
                    <div id="sub-orders" class="submenu-panel ml-4 pl-3 border-l-2 border-[#38bdf8]/40 space-y-1">
                        <button type="button" onclick="switchOrdersSub('total')" class="w-full text-left px-3 py-1.5 text-[12px] font-bold uppercase text-[#64748b] hover:text-[#0284c7] hover:bg-[#f8fafc] rounded-lg">• Total Orders</button>
                        <button type="button" onclick="switchOrdersSub('success')" class="w-full text-left px-3 py-1.5 text-[12px] font-bold uppercase text-[#64748b] hover:text-emerald-700 hover:bg-[#f8fafc] rounded-lg">• Success Orders</button>
                        <button type="button" onclick="switchOrdersSub('return')" class="w-full text-left px-3 py-1.5 text-[12px] font-bold uppercase text-[#64748b] hover:text-rose-700 hover:bg-[#f8fafc] rounded-lg">• Return Orders</button>
                    </div>
                </div>

                <!-- 3. PRODUCT (SUBMENU: ADD PRODUCT, LIST PRODUCTS) -->
                <div>
                    <button type="button" onclick="toggleSubmenu('product')" class="w-full px-4 py-3 text-[13px] font-bold uppercase tracking-wider flex items-center justify-between rounded-xl transition-all cursor-pointer text-[#475569] hover:bg-[#f1f5f9] hover:text-[#0284c7]">
                        <div class="flex items-center gap-3">
                            <i data-lucide="package" class="w-4 h-4"></i> Product
                        </div>
                        <span data-chevron="product" class="submenu-chevron"><i data-lucide="chevron-down" class="w-4 h-4"></i></span>
                    </button>
                    <div id="sub-product" class="submenu-panel ml-4 pl-3 border-l-2 border-[#38bdf8]/40 space-y-1">
                        <button type="button" onclick="switchSection('product_add')" class="w-full text-left px-3 py-1.5 text-[12px] font-bold uppercase text-[#64748b] hover:text-[#0284c7] hover:bg-[#f8fafc] rounded-lg">• Add Product</button>
                        <button type="button" onclick="switchSection('products')" class="w-full text-left px-3 py-1.5 text-[12px] font-bold uppercase text-[#64748b] hover:text-[#0284c7] hover:bg-[#f8fafc] rounded-lg">• List Products</button>
                    </div>
                </div>

                <!-- 4. PURCHASE (SUBMENU: ADD PURCHASE, PURCHASE LIST) -->
                <div>
                    <button type="button" onclick="toggleSubmenu('purchase')" class="w-full px-4 py-3 text-[13px] font-bold uppercase tracking-wider flex items-center justify-between rounded-xl transition-all cursor-pointer text-[#475569] hover:bg-[#f1f5f9] hover:text-[#0284c7]">
                        <div class="flex items-center gap-3">
                            <i data-lucide="shopping-cart" class="w-4 h-4"></i> Purchase
                        </div>
                        <span data-chevron="purchase" class="submenu-chevron"><i data-lucide="chevron-down" class="w-4 h-4"></i></span>
                    </button>
                    <div id="sub-purchase" class="submenu-panel ml-4 pl-3 border-l-2 border-[#38bdf8]/40 space-y-1">
                        <button type="button" onclick="switchSection('purchase_add')" class="w-full text-left px-3 py-1.5 text-[12px] font-bold uppercase text-[#64748b] hover:text-[#0284c7] hover:bg-[#f8fafc] rounded-lg">• Add Purchase</button>
                        <button type="button" onclick="switchSection('purchase_list')" class="w-full text-left px-3 py-1.5 text-[12px] font-bold uppercase text-[#64748b] hover:text-[#0284c7] hover:bg-[#f8fafc] rounded-lg">• Purchase List</button>
                    </div>
                </div>

                <!-- 5. CONTACT (SUBMENU: CUSTOMERS, SUPPLIER) -->
                <div>
                    <button type="button" onclick="toggleSubmenu('contact')" class="w-full px-4 py-3 text-[13px] font-bold uppercase tracking-wider flex items-center justify-between rounded-xl transition-all cursor-pointer text-[#475569] hover:bg-[#f1f5f9] hover:text-[#0284c7]">
                        <div class="flex items-center gap-3">
                            <i data-lucide="users" class="w-4 h-4"></i> Contact
                        </div>
                        <span data-chevron="contact" class="submenu-chevron"><i data-lucide="chevron-down" class="w-4 h-4"></i></span>
                    </button>
                    <div id="sub-contact" class="submenu-panel ml-4 pl-3 border-l-2 border-[#38bdf8]/40 space-y-1">
                        <button type="button" onclick="switchSection('customers')" class="w-full text-left px-3 py-1.5 text-[12px] font-bold uppercase text-[#64748b] hover:text-[#0284c7] hover:bg-[#f8fafc] rounded-lg">• Customers</button>
                        <button type="button" onclick="switchSection('supplier')" class="w-full text-left px-3 py-1.5 text-[12px] font-bold uppercase text-[#64748b] hover:text-[#0284c7] hover:bg-[#f8fafc] rounded-lg">• Supplier</button>
                    </div>
                </div>

                <!-- 6. COURIER (SUBMENU: COURIER SET UP, SEND COURIER, COURIER HISTORY) -->
                <div>
                    <button type="button" onclick="toggleSubmenu('courier')" class="w-full px-4 py-3 text-[13px] font-bold uppercase tracking-wider flex items-center justify-between rounded-xl transition-all cursor-pointer text-[#475569] hover:bg-[#f1f5f9] hover:text-[#0284c7]">
                        <div class="flex items-center gap-3">
                            <i data-lucide="truck" class="w-4 h-4"></i> Courier
                        </div>
                        <span data-chevron="courier" class="submenu-chevron"><i data-lucide="chevron-down" class="w-4 h-4"></i></span>
                    </button>
                    <div id="sub-courier" class="submenu-panel ml-4 pl-3 border-l-2 border-[#38bdf8]/40 space-y-1">
                        <button type="button" onclick="switchSection('courier_setup')" class="w-full text-left px-3 py-1.5 text-[12px] font-bold uppercase text-[#64748b] hover:text-[#0284c7] hover:bg-[#f8fafc] rounded-lg">• Courier Set Up</button>
                        <button type="button" onclick="switchSection('courier_send')" class="w-full text-left px-3 py-1.5 text-[12px] font-bold uppercase text-[#64748b] hover:text-[#0284c7] hover:bg-[#f8fafc] rounded-lg">• Send Courier</button>
                        <button type="button" onclick="switchSection('courier_history')" class="w-full text-left px-3 py-1.5 text-[12px] font-bold uppercase text-[#64748b] hover:text-[#0284c7] hover:bg-[#f8fafc] rounded-lg">• Courier History</button>
                    </div>
                </div>

                <!-- 6. SITE SETTING -->
                <button type="button" onclick="switchSection('settings')" id="sidebar-btn-settings" class="w-full px-4 py-3 text-[13px] font-bold uppercase tracking-wider flex items-center gap-3 rounded-xl transition-all cursor-pointer text-[#475569] hover:bg-[#f1f5f9] hover:text-[#0284c7]">
                    <i data-lucide="settings" class="w-4 h-4"></i> Site Setting
                </button>

                <!-- 7. SEO & PIXELS -->
                <button type="button" onclick="switchSection('seo')" id="sidebar-btn-seo" class="w-full px-4 py-3 text-[13px] font-bold uppercase tracking-wider flex items-center gap-3 rounded-xl transition-all cursor-pointer text-[#475569] hover:bg-[#f1f5f9] hover:text-[#0284c7]">
                    <i data-lucide="bar-chart-2" class="w-4 h-4"></i> SEO & Pixels
                </button>
            </div>
        </div>

        <div class="pt-6 border-t border-[#e2e8f0]">
            <a href="{{ url('/admin/logout') }}" class="w-full bg-rose-500/10 hover:bg-rose-500/20 text-rose-700 border border-rose-500/30 py-2.5 rounded-xl text-[11px] font-bold uppercase tracking-wider flex items-center justify-center gap-2 text-center font-bold">
                <i data-lucide="log-out" class="w-4 h-4"></i> Logout
            </a>
        </div>
    </aside>

    <!-- 2. MAIN CONTENT AREA -->
    <main class="flex-1 p-6 space-y-6 relative z-10 overflow-y-auto max-h-screen">
        
        <header class="bg-white/80 backdrop-blur-xl border border-[#38bdf8]/40 rounded-2xl shadow-sm px-6 py-3.5 flex flex-col sm:flex-row items-center justify-between gap-4">
            <div class="flex items-center gap-2 overflow-x-auto bg-[#f1f5f9]/80 p-1.5 rounded-xl border border-[#cbd5e1]/60">
                <button type="button" onclick="switchSection('menu')" id="top-btn-menu" class="px-4 py-2 text-[12px] font-bold uppercase tracking-wider flex items-center gap-2 rounded-lg transition-all cursor-pointer text-[#475569] hover:bg-white hover:text-[#0284c7] whitespace-nowrap">
                    <i data-lucide="sliders" class="w-4 h-4"></i> MENU BUILDER
                </button>

                <button type="button" onclick="switchSection('create_order')" id="top-btn-create_order" class="px-4 py-2 text-[12px] font-bold uppercase tracking-wider flex items-center gap-2 rounded-lg transition-all cursor-pointer bg-emerald-600/10 text-emerald-700 border border-emerald-600/30 whitespace-nowrap">
                    <i data-lucide="plus" class="w-4 h-4"></i> CREATE ORDER 🛍️
                </button>
            </div>

            <div class="flex items-center gap-2 shrink-0">
                <button type="button" onclick="clearSystemCache()" class="bg-rose-50 hover:bg-rose-100 text-rose-600 border border-rose-200 px-4 py-2 rounded-xl text-[11px] font-bold uppercase tracking-wider flex items-center gap-1.5 shadow-xs transition-colors cursor-pointer">
                    <i data-lucide="refresh-cw" class="w-3.5 h-3.5"></i> Clear Cache
                </button>
                <a href="{{ url('/') }}" target="_blank" class="bg-white hover:bg-[#f1f5f9] text-[#0f172a] border border-[#cbd5e1] px-4 py-2 rounded-xl text-[11px] font-bold uppercase tracking-wider flex items-center gap-1.5 shadow-xs transition-colors">
                    <i data-lucide="external-link" class="w-3.5 h-3.5"></i> Storefront ↗
                </a>
            </div>
        </header>

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

        <!-- SECTION 3: CREATE ORDERS TERMINAL -->
        <div id="section-create_order" class="section-content hidden bg-white/90 border p-6 rounded-2xl space-y-6 max-w-2xl mx-auto">
            <h2 class="text-[18px] font-serif font-bold text-[#0f172a] uppercase">Create Orders</h2>
            
            <form onsubmit="handleCreateOrderSubmit(event)" class="space-y-6 bg-[#f8fafc] border p-6 rounded-2xl">
                <div class="space-y-3">
                    <div class="flex justify-between items-center border-b pb-2">
                        <label class="text-[12px] font-bold uppercase text-[#0f172a]">FIELD 1: SELECT CUSTOMER</label>
                        <button type="button" onclick="openAddCustomerPrompt()" class="bg-[#0284c7] text-white px-3 py-1 rounded text-[11px] font-bold uppercase">+ Add Customer</button>
                    </div>
                    <select id="coCustomerSelect" class="w-full border p-3 rounded-xl text-[13px] font-bold">
                        <option value="">-- Choose Customer --</option>
                        <option value="Shakib Al Hasan">Shakib Al Hasan (+8801700112233)</option>
                        <option value="Tanvir Hossain">Tanvir Hossain (+8801822334455)</option>
                        <option value="Mahmudur Rahman">Mahmudur Rahman (+8801711223344)</option>
                    </select>
                </div>

                <div class="space-y-3">
                    <label class="text-[12px] font-bold uppercase text-[#0f172a] border-b pb-2 block">FIELD 2: SELECT MULTI-PRODUCTS & QUANTITY</label>
                    <select id="coProductSelect" onchange="handleAddProductToCart()" class="w-full border p-3 rounded-xl text-[13px] font-bold">
                        <option value="">+ Choose Product to Add...</option>
                        <option value="L'Ombre d'Ambre 100ml|3200">L'Ombre d'Ambre 100ml — ৳3,200 BDT</option>
                        <option value="Velours de Rose 100ml|2850">Velours de Rose 100ml — ৳2,850 BDT</option>
                        <option value="Cuir Noir Extrait 100ml|3800">Cuir Noir Extrait 100ml — ৳3,800 BDT</option>
                    </select>
                    <div id="cartItemsList" class="space-y-2 pt-2"></div>
                </div>

                <div class="bg-[#e0f2fe] border p-4 rounded-xl flex justify-between items-center">
                    <div>
                        <span class="text-[11px] font-bold uppercase text-[#0284c7] block">CALCULATED TOTAL BILL:</span>
                        <span class="text-[22px] font-bold text-[#0f172a] font-serif" id="coTotalBillDisplay">৳0 BDT</span>
                    </div>
                    <button type="submit" class="bg-[#0284c7] text-white px-6 py-3 rounded-xl font-bold uppercase text-[12px]">SUBMIT & CREATE ORDER</button>
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
                                <input type="text" name="siteName" class="w-full border border-[#cbd5e1] px-3 py-2.5 rounded-lg text-[13px] font-bold focus:border-[#0284c7] outline-none bg-white text-[#0f172a]">
                            </div>
                            <div>
                                <label class="text-[11px] font-bold uppercase text-[#475569] block mb-1">Tagline</label>
                                <input type="text" name="tagline" class="w-full border border-[#cbd5e1] px-3 py-2.5 rounded-lg text-[13px] font-bold focus:border-[#0284c7] outline-none bg-white text-[#0f172a]">
                            </div>
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
                                        <input type="text" name="logo_url" placeholder="Or paste Logo Image URL (e.g. /uploads/...)" class="w-full border border-[#cbd5e1] px-2.5 py-1.5 rounded-lg text-[11px] font-mono focus:border-[#0284c7] outline-none bg-white text-[#0f172a]" oninput="previewUrlInput(this.value, 'previewLogoImg', 'previewLogoPlaceholder')">
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
                                        <input type="text" name="favicon_url" placeholder="Or paste Favicon URL (e.g. /favicon.ico)" class="w-full border border-[#cbd5e1] px-2.5 py-1.5 rounded-lg text-[11px] font-mono focus:border-[#0284c7] outline-none bg-white text-[#0f172a]" oninput="previewUrlInput(this.value, 'previewFaviconImg')">
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
                                    <option value="BDT (৳)">BDT (৳) - Bangladeshi Taka</option>
                                    <option value="USD ($)">USD ($) - US Dollar</option>
                                    <option value="EUR (€)">EUR (€) - Euro</option>
                                </select>
                            </div>
                            <div>
                                <label class="text-[11px] font-bold uppercase text-[#475569] block mb-1">Default Tax Rate (%)</label>
                                <input type="number" name="tax_rate" class="w-full border border-[#cbd5e1] px-3 py-2.5 rounded-lg text-[13px] font-bold focus:border-[#0284c7] outline-none bg-white text-[#0f172a]">
                            </div>
                        </div>
                    </div>

                    <!-- Social Media Links -->
                    <div class="space-y-4">
                        <h3 class="text-[13px] font-bold text-[#0f172a] uppercase border-l-4 border-[#0284c7] pl-2">Social Media Links</h3>
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                            <div>
                                <label class="text-[11px] font-bold uppercase text-[#475569] block mb-1">Facebook URL</label>
                                <input type="url" name="facebook_url" placeholder="https://facebook.com/rexxobd" class="w-full border border-[#cbd5e1] px-3 py-2.5 rounded-lg text-[13px] font-bold focus:border-[#0284c7] outline-none bg-white text-[#0f172a]">
                            </div>
                            <div>
                                <label class="text-[11px] font-bold uppercase text-[#475569] block mb-1">Instagram URL</label>
                                <input type="url" name="instagram_url" placeholder="https://instagram.com/rexxobd" class="w-full border border-[#cbd5e1] px-3 py-2.5 rounded-lg text-[13px] font-bold focus:border-[#0284c7] outline-none bg-white text-[#0f172a]">
                            </div>
                            <div>
                                <label class="text-[11px] font-bold uppercase text-[#475569] block mb-1">TikTok URL</label>
                                <input type="url" name="tiktok_url" placeholder="https://tiktok.com/@rexxobd" class="w-full border border-[#cbd5e1] px-3 py-2.5 rounded-lg text-[13px] font-bold focus:border-[#0284c7] outline-none bg-white text-[#0f172a]">
                            </div>
                        </div>
                    </div>
                    <!-- Contact Details -->
                    <div class="space-y-4">
                        <h3 class="text-[13px] font-bold text-[#0f172a] uppercase border-l-4 border-[#0284c7] pl-2">Contact Details</h3>
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                            <div>
                                <label class="text-[11px] font-bold uppercase text-[#475569] block mb-1">Phone Number</label>
                                <input type="text" name="phone" class="w-full border border-[#cbd5e1] px-3 py-2.5 rounded-lg text-[13px] font-bold focus:border-[#0284c7] outline-none bg-white text-[#0f172a]">
                            </div>
                            <div>
                                <label class="text-[11px] font-bold uppercase text-[#475569] block mb-1">WhatsApp Number</label>
                                <input type="text" name="whatsapp" class="w-full border border-[#cbd5e1] px-3 py-2.5 rounded-lg text-[13px] font-bold focus:border-[#0284c7] outline-none bg-white text-[#0f172a]">
                            </div>
                            <div>
                                <label class="text-[11px] font-bold uppercase text-[#475569] block mb-1">Support Email</label>
                                <input type="email" name="email" class="w-full border border-[#cbd5e1] px-3 py-2.5 rounded-lg text-[13px] font-bold focus:border-[#0284c7] outline-none bg-white text-[#0f172a]">
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
                                <input type="text" name="announcement" class="w-full border border-[#cbd5e1] px-3 py-2.5 rounded-lg text-[13px] font-bold focus:border-[#0284c7] outline-none bg-white text-[#0f172a]">
                            </div>
                            <div>
                                <label class="text-[11px] font-bold uppercase text-[#475569] block mb-1">Footer Text</label>
                                <input type="text" name="footerText" class="w-full border border-[#cbd5e1] px-3 py-2.5 rounded-lg text-[13px] font-bold focus:border-[#0284c7] outline-none bg-white text-[#0f172a]">
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

                    <!-- Tracking Pixels -->
                    <div class="space-y-4">
                        <h3 class="text-[13px] font-bold text-[#0f172a] uppercase border-l-4 border-[#0284c7] pl-2">Marketing & Tracking Pixels</h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="text-[11px] font-bold uppercase text-[#475569] block mb-1">Facebook / Meta Pixel ID</label>
                                <input type="text" name="pixel_facebook" placeholder="e.g. 123456789012345" class="w-full border border-[#cbd5e1] px-3 py-2.5 rounded-lg text-[13px] font-bold font-mono focus:border-[#0284c7] outline-none bg-white text-[#0f172a]">
                            </div>
                            <div>
                                <label class="text-[11px] font-bold uppercase text-[#475569] block mb-1">Google Analytics ID (GA4)</label>
                                <input type="text" name="pixel_google" placeholder="e.g. G-XXXXXXX" class="w-full border border-[#cbd5e1] px-3 py-2.5 rounded-lg text-[13px] font-bold font-mono focus:border-[#0284c7] outline-none bg-white text-[#0f172a]">
                            </div>
                            <div>
                                <label class="text-[11px] font-bold uppercase text-[#475569] block mb-1">TikTok Pixel ID</label>
                                <input type="text" name="pixel_tiktok" placeholder="e.g. CXXXXXXXXXXXX" class="w-full border border-[#cbd5e1] px-3 py-2.5 rounded-lg text-[13px] font-bold font-mono focus:border-[#0284c7] outline-none bg-white text-[#0f172a]">
                            </div>
                            <div>
                                <label class="text-[11px] font-bold uppercase text-[#475569] block mb-1">Google Tag Manager (GTM) ID</label>
                                <input type="text" name="pixel_gtm" placeholder="e.g. GTM-XXXXXXX" class="w-full border border-[#cbd5e1] px-3 py-2.5 rounded-lg text-[13px] font-bold font-mono focus:border-[#0284c7] outline-none bg-white text-[#0f172a]">
                            </div>
                        </div>
                    </div>

                    <!-- Save Button -->
                    <div class="pt-4 border-t border-[#e2e8f0]">
                        <button type="submit" class="bg-[#0f172a] hover:bg-[#B8712E] text-white px-8 py-3 rounded-xl text-[13px] font-bold uppercase tracking-wider transition-all flex items-center justify-center gap-2 shadow-sm cursor-pointer">
                            <i data-lucide="save" class="w-4 h-4"></i> Save SEO Settings
                        </button>
                    </div>
                </form>
            </div>
        </div>
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
                            @foreach ($categories as $cat)
                                <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                            @endforeach
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

        const allSubmenus = ['orders', 'product', 'purchase', 'contact', 'courier'];

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
            document.getElementById('toastMsg').innerText = msg;
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
            lucide.createIcons();

            // Update sidebar active button highlight
            updateSidebarHighlight(secId);
        }

        function updateSidebarHighlight(secId) {
            // Remove active style from all sidebar buttons
            document.querySelectorAll('[id^="sidebar-btn-"]').forEach(btn => {
                btn.classList.remove('bg-[#0284c7]', 'text-white', 'shadow-md');
                btn.classList.add('text-[#475569]', 'hover:bg-[#f1f5f9]', 'hover:text-[#0284c7]');
            });
            // Apply active style to matching button
            const activeBtn = document.getElementById('sidebar-btn-' + secId);
            if (activeBtn) {
                activeBtn.classList.add('bg-[#0284c7]', 'text-white', 'shadow-md');
                activeBtn.classList.remove('text-[#475569]', 'hover:bg-[#f1f5f9]', 'hover:text-[#0284c7]');
            }
        }

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
                    <tr class="hover:bg-sky-50">
                        <td class="p-3.5 font-bold font-mono text-[#0284c7]">${ord.id}</td>
                        <td class="p-3.5 font-bold">${ord.client}</td>
                        <td class="p-3.5 font-bold text-[#0284c7] font-mono text-right">৳${ord.amt.toLocaleString()} BDT</td>
                    </tr>
                `).join('');
            }

            if (!tbody) return;

            const filtered = masterOrders.filter(o => {
                if (ordersSubTab === 'success' && o.status !== 'Delivered') return false;
                if (ordersSubTab === 'return' && o.status !== 'Returned') return false;
                return true;
            });

            tbody.innerHTML = filtered.map(ord => {
                const cleanId = ord.id.replace('#', '');
                return `
                    <tr class="hover:bg-sky-50">
                        <td class="p-3.5 font-bold font-mono text-[#0284c7]">${ord.id}</td>
                        <td class="p-3.5 font-bold">${ord.client}</td>
                        <td class="p-3.5">${ord.prod}</td>
                        <td class="p-3.5 font-bold text-[#0284c7] font-mono">৳${ord.amt.toLocaleString()} BDT</td>
                        <td class="p-3.5 text-center"><span class="bg-sky-500/10 text-sky-700 px-2 py-0.5 rounded-full text-[10px] font-bold uppercase">${ord.status}</span></td>
                        <td class="p-3.5 text-right relative">
                            <div class="relative inline-block text-left">
                                <button type="button" onclick="toggleActionDropdown('${ord.id}')" class="px-4 py-1.5 bg-white border border-[#38bdf8] text-[#0284c7] hover:bg-sky-50 rounded-xl text-[12px] font-bold shadow-xs flex items-center gap-1.5 ml-auto cursor-pointer">
                                    Actions ▼
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

        function handleAddProductToCart() {
            const val = document.getElementById('coProductSelect').value;
            if (!val) return;
            const parts = val.split('|');
            const name = parts[0];
            const price = Number(parts[1]);

            const existing = cart.find(i => i.name === name);
            if (existing) { existing.qty += 1; } else { cart.push({ name, price, qty: 1 }); }
            document.getElementById('coProductSelect').value = '';
            renderCartUI();
        }

        function changeCartQty(index, delta) {
            cart[index].qty += delta;
            if (cart[index].qty <= 0) cart.splice(index, 1);
            renderCartUI();
        }

        function renderCartUI() {
            const container = document.getElementById('cartItemsList');
            const totalDisplay = document.getElementById('coTotalBillDisplay');
            if (!container) return;

            let total = 0;
            container.innerHTML = cart.map((item, idx) => {
                const itemTotal = item.price * item.qty;
                total += itemTotal;
                return `
                    <div class="bg-[#f8fafc] border p-3 rounded-xl flex items-center justify-between">
                        <div>
                            <div class="font-bold text-[13px]">${item.name}</div>
                            <div class="text-[11px] text-[#0284c7] font-bold font-mono">৳${item.price.toLocaleString()} BDT x ${item.qty} = ৳${itemTotal.toLocaleString()} BDT</div>
                        </div>
                        <div class="flex items-center gap-2">
                            <button type="button" onclick="changeCartQty(${idx}, -1)" class="w-6 h-6 bg-gray-100 font-bold border rounded">-</button>
                            <span class="font-bold font-mono text-[13px]">${item.qty}</span>
                            <button type="button" onclick="changeCartQty(${idx}, 1)" class="w-6 h-6 bg-[#0284c7] text-white font-bold rounded">+</button>
                        </div>
                    </div>
                `;
            }).join('');

            totalDisplay.innerText = `৳${total.toLocaleString()} BDT`;
        }

        function openAddCustomerPrompt() {
            document.getElementById('addCustomerModal').classList.remove('hidden');
            document.getElementById('modalCustomerName').value = '';
            document.getElementById('modalCustomerPhone').value = '';
            document.getElementById('modalCustomerAddress').value = '';
            lucide.createIcons();
        }

        function closeAddCustomerModal() {
            document.getElementById('addCustomerModal').classList.add('hidden');
        }

        function submitAddCustomerModal() {
            const name = document.getElementById('modalCustomerName').value.trim();
            const phone = document.getElementById('modalCustomerPhone').value.trim();
            if (!name || !phone) {
                alert('Name and Phone are required!');
                return;
            }
            
            const select = document.getElementById('coCustomerSelect');
            const opt = document.createElement('option');
            opt.value = name;
            opt.innerText = `${name} (${phone})`;
            opt.selected = true;
            select.appendChild(opt);
            
            showToastNotice(`Added customer "${name}"!`);
            closeAddCustomerModal();
        }

        function handleCreateOrderSubmit(e) {
            e.preventDefault();
            const client = document.getElementById('coCustomerSelect').value || 'Guest Customer';
            if (cart.length === 0) { alert('Please select at least 1 product bottle.'); return; }
            const id = `#RX-${Math.floor(8900 + Math.random() * 900)}`;
            const total = cart.reduce((s, i) => s + (i.price * i.qty), 0);
            const prod = cart.map(i => `${i.name} (x${i.qty})`).join(', ');

            masterOrders.unshift({ id, client, prod, amt: total, status: 'Pending Dispatch' });
            showToastNotice(`Order ${id} Created for ${client}! Total: ৳${total.toLocaleString()} BDT`);
            cart = [];
            renderCartUI();
            switchSection('orders');
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
            } catch(e) { console.error('Failed to load settings', e); }
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
                
                const res = await fetch('http://localhost:8000/api/clear-cache', { method: 'POST' });
                
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
    </script>
</body>
</html>
