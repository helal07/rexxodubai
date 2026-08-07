<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $siteSettings['siteName'] ?? 'RaaxO BD' }} — Courier Logistics & API Hub</title>
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

                <!-- 3. PRODUCT -->
                <div>
                    <button type="button" onclick="toggleSubmenu('product')" class="w-full px-4 py-3 text-[13px] font-bold uppercase tracking-wider flex items-center justify-between rounded-xl transition-all cursor-pointer text-[#475569] hover:bg-[#f1f5f9] hover:text-[#0284c7]">
                        <div class="flex items-center gap-3">
                            <i data-lucide="package" class="w-4 h-4"></i> Product
                        </div>
                        <span data-chevron="product" class="submenu-chevron"><i data-lucide="chevron-down" class="w-4 h-4"></i></span>
                    </button>
                    <div id="sub-product" class="submenu-panel ml-4 pl-3 border-l-2 border-[#38bdf8]/40 space-y-1">
                        <a href="{{ url('/admin/products') }}" class="block w-full text-left px-3 py-1.5 text-[12px] font-bold uppercase text-[#64748b] hover:text-[#0284c7] hover:bg-[#f8fafc] rounded-lg">• List Products</a>
                        <a href="{{ url('/admin/categories') }}" class="block w-full text-left px-3 py-1.5 text-[12px] font-bold uppercase text-[#64748b] hover:text-[#0284c7] hover:bg-[#f8fafc] rounded-lg">• Category & Sub Category</a>
                    </div>
                </div>

                <!-- 4. COURIER (ACTIVE) -->
                <div>
                    <button type="button" onclick="toggleSubmenu('courier')" class="w-full px-4 py-3 text-[13px] font-bold uppercase tracking-wider flex items-center justify-between rounded-xl transition-all cursor-pointer bg-[#0284c7] text-white shadow-md">
                        <div class="flex items-center gap-3">
                            <i data-lucide="truck" class="w-4 h-4"></i> Courier
                        </div>
                        <span data-chevron="courier" class="submenu-chevron chevron-open"><i data-lucide="chevron-down" class="w-4 h-4"></i></span>
                    </button>
                    <div id="sub-courier" class="submenu-panel submenu-open ml-4 pl-3 border-l-2 border-[#38bdf8]/40 space-y-1">
                        <button type="button" onclick="switchCourierTab('partners')" id="subnav-partners" class="w-full text-left px-3 py-1.5 text-[12px] font-bold uppercase text-[#0284c7] bg-[#f0f9ff] rounded-lg font-mono">• Courier Set Up</button>
                        <button type="button" onclick="switchCourierTab('dispatch')" id="subnav-dispatch" class="w-full text-left px-3 py-1.5 text-[12px] font-bold uppercase text-[#64748b] hover:text-[#0284c7] hover:bg-[#f8fafc] rounded-lg font-mono">• Send Courier ({{ $pendingDispatchCount }})</button>
                        <button type="button" onclick="switchCourierTab('history')" id="subnav-history" class="w-full text-left px-3 py-1.5 text-[12px] font-bold uppercase text-[#64748b] hover:text-[#0284c7] hover:bg-[#f8fafc] rounded-lg font-mono">• Courier History</button>
                    </div>
                </div>

                <!-- 5. SITE SETTINGS & MENUS -->
                <a href="{{ url('/admin/menus') }}" class="w-full px-4 py-3 text-[13px] font-bold uppercase tracking-wider flex items-center gap-3 rounded-xl transition-all cursor-pointer text-[#475569] hover:bg-[#f1f5f9] hover:text-[#0284c7]">
                    <i data-lucide="menu" class="w-4 h-4"></i> Menu Builder
                </a>
                <a href="{{ url('/admin/dashboard') }}" class="w-full px-4 py-3 text-[13px] font-bold uppercase tracking-wider flex items-center gap-3 rounded-xl transition-all cursor-pointer text-[#475569] hover:bg-[#f1f5f9] hover:text-[#0284c7]">
                    <i data-lucide="settings" class="w-4 h-4"></i> Site Setting
                </a>
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
        
        <!-- Header Bar -->
        <header class="bg-white/80 backdrop-blur-xl border border-[#38bdf8]/40 rounded-2xl shadow-sm px-6 py-3.5 flex flex-col sm:flex-row items-center justify-between gap-4">
            <div class="flex items-center gap-2 overflow-x-auto bg-[#f1f5f9]/80 p-1.5 rounded-xl border border-[#cbd5e1]/60">
                <a href="{{ url('/admin/menus') }}" class="px-4 py-2 text-[12px] font-bold uppercase tracking-wider flex items-center gap-2 rounded-lg transition-all cursor-pointer text-[#475569] hover:bg-white hover:text-[#0284c7] whitespace-nowrap">
                    <i data-lucide="sliders" class="w-4 h-4"></i> MENU BUILDER
                </a>
                <a href="{{ url('/admin/categories') }}" class="px-4 py-2 text-[12px] font-bold uppercase tracking-wider flex items-center gap-2 rounded-lg transition-all cursor-pointer text-[#475569] hover:bg-white hover:text-[#0284c7] whitespace-nowrap">
                    <i data-lucide="folder-tree" class="w-4 h-4"></i> CATEGORIES & SUB
                </a>
                <a href="{{ url('/admin/orders') }}" class="px-4 py-2 text-[12px] font-bold uppercase tracking-wider flex items-center gap-2 rounded-lg transition-all cursor-pointer text-[#475569] hover:bg-white hover:text-[#0284c7] whitespace-nowrap">
                    <i data-lucide="shopping-bag" class="w-4 h-4"></i> ALL ORDERS
                </a>
            </div>

            <div class="flex items-center gap-2 shrink-0">
                <a href="{{ url('/perfumes') }}" target="_blank" class="bg-white hover:bg-[#f1f5f9] text-[#0f172a] border border-[#cbd5e1] px-4 py-2 rounded-xl text-[11px] font-bold uppercase tracking-wider flex items-center gap-1.5 shadow-xs transition-colors">
                    <i data-lucide="external-link" class="w-3.5 h-3.5"></i> Storefront ↗
                </a>
            </div>
        </header>

        <!-- Feedback Alert Messages -->
        @if (session('success'))
            <div class="p-4 bg-emerald-50 border border-emerald-300 text-emerald-800 text-[13px] rounded-2xl flex items-center gap-2.5 font-medium animate-fade-in shadow-sm">
                <i data-lucide="check-circle-2" class="w-4 h-4 text-emerald-600 shrink-0"></i>
                <span>{{ session('success') }}</span>
            </div>
        @endif
        @if (session('error'))
            <div class="p-4 bg-rose-50 border border-rose-300 text-rose-800 text-[13px] rounded-2xl flex items-center gap-2.5 font-medium animate-fade-in shadow-sm">
                <i data-lucide="alert-triangle" class="w-4 h-4 text-rose-600 shrink-0"></i>
                <span>{{ session('error') }}</span>
            </div>
        @endif

        <!-- Metrics Overview Grid -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
            <div class="bg-white/90 border border-[#38bdf8]/30 p-5 rounded-2xl shadow-sm space-y-1 card-elevated">
                <div class="flex justify-between items-center text-[#64748b]">
                    <span class="text-[11px] font-bold uppercase tracking-wider">COURIER PARTNERS</span>
                    <i data-lucide="truck" class="w-4 h-4 text-[#0284c7]"></i>
                </div>
                <div class="text-2xl font-bold font-serif text-[#0f172a]">{{ $activeCouriers }} <span class="text-xs text-[#64748b] font-normal">/ {{ $totalCouriers }} Active</span></div>
                <span class="text-[11px] text-[#64748b]">Integrated delivery networks</span>
            </div>

            <div class="bg-white/90 border border-amber-300/40 p-5 rounded-2xl shadow-sm space-y-1 bg-gradient-to-br from-white to-amber-50 card-elevated">
                <div class="flex justify-between items-center text-amber-700">
                    <span class="text-[11px] font-bold uppercase tracking-wider">PENDING DISPATCH</span>
                    <i data-lucide="clock" class="w-4 h-4"></i>
                </div>
                <div class="text-2xl font-bold font-serif text-amber-900">{{ $pendingDispatchCount }} Orders</div>
                <span class="text-[11px] text-amber-700">Ready for courier booking</span>
            </div>

            <div class="bg-white/90 border border-emerald-300/40 p-5 rounded-2xl shadow-sm space-y-1 bg-gradient-to-br from-white to-emerald-50 card-elevated">
                <div class="flex justify-between items-center text-emerald-700">
                    <span class="text-[11px] font-bold uppercase tracking-wider">DISPATCHED TODAY</span>
                    <i data-lucide="send" class="w-4 h-4"></i>
                </div>
                <div class="text-2xl font-bold font-serif text-emerald-900">{{ $dispatchedTodayCount }} Parcels</div>
                <span class="text-[11px] text-emerald-700">Packages handed over today</span>
            </div>

            <div class="bg-white/90 border border-sky-300/40 p-5 rounded-2xl shadow-sm space-y-1 bg-gradient-to-br from-white to-sky-50 card-elevated">
                <div class="flex justify-between items-center text-sky-700">
                    <span class="text-[11px] font-bold uppercase tracking-wider">TOTAL DISPATCHED</span>
                    <i data-lucide="check-check" class="w-4 h-4"></i>
                </div>
                <div class="text-2xl font-bold font-serif text-sky-900">{{ $totalDispatchedCount }} Total</div>
                <span class="text-[11px] text-sky-700">Historical consignments</span>
            </div>
        </div>

        <!-- Navigation Tabs -->
        <div class="flex items-center gap-2 bg-white/80 p-1.5 rounded-2xl border border-[#38bdf8]/30 shadow-sm w-fit">
            <button onclick="switchCourierTab('partners')" id="tab-partners" class="tab-btn px-5 py-2.5 rounded-xl text-[12px] font-bold uppercase tracking-wider transition-all bg-[#0284c7] text-white shadow-sm cursor-pointer">
                <i data-lucide="settings-2" class="w-4 h-4 inline-block mr-1.5 -mt-0.5"></i> Courier Setup & API
            </button>
            <button onclick="switchCourierTab('dispatch')" id="tab-dispatch" class="tab-btn px-5 py-2.5 rounded-xl text-[12px] font-bold uppercase tracking-wider transition-all text-[#64748b] hover:text-[#0284c7] hover:bg-white cursor-pointer">
                <i data-lucide="send" class="w-4 h-4 inline-block mr-1.5 -mt-0.5"></i> Send Courier ({{ $pendingDispatchCount }})
            </button>
            <button onclick="switchCourierTab('history')" id="tab-history" class="tab-btn px-5 py-2.5 rounded-xl text-[12px] font-bold uppercase tracking-wider transition-all text-[#64748b] hover:text-[#0284c7] hover:bg-white cursor-pointer">
                <i data-lucide="history" class="w-4 h-4 inline-block mr-1.5 -mt-0.5"></i> Courier History
            </button>
        </div>

        <!-- ================================================================= -->
        <!-- TAB 1: COURIER PARTNERS & API SETUP -->
        <!-- ================================================================= -->
        <div id="section-partners" class="space-y-6">
            <div class="bg-white/90 backdrop-blur-xl border border-[#38bdf8]/30 p-6 rounded-2xl shadow-sm flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
                <div>
                    <h2 class="text-lg font-serif font-bold text-[#0f172a] uppercase tracking-tight">Active Courier Configurations</h2>
                    <p class="text-[12px] text-[#64748b]">Configure Bangladeshi courier API credentials, test connections, and manage service zones.</p>
                </div>
                <div class="flex items-center gap-2">
                    <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg text-[11px] font-bold font-mono bg-emerald-50 text-emerald-700 border border-emerald-200">
                        <span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></span>
                        Database Synchronized
                    </span>
                </div>
            </div>

            <!-- Courier Cards Grid -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach ($couriers as $key => $courier)
                    @php
                        $isActive = ($courier['status'] ?? '') === 'active';
                        $hasCreds = false;
                        if (!empty($courier['credentials'])) {
                            foreach ($courier['credentials'] as $ck => $cv) {
                                if ($ck !== 'base_url' && !empty($cv)) {
                                    $hasCreds = true;
                                    break;
                                }
                            }
                        }
                    @endphp
                    <div class="bg-white/90 backdrop-blur-xl border {{ $isActive ? 'border-[#38bdf8]/60 shadow-md' : 'border-[#e2e8f0]' }} rounded-2xl p-6 space-y-4 hover:border-[#0284c7] transition-all flex flex-col justify-between group shadow-sm">
                        <div class="space-y-3">
                            <div class="flex justify-between items-start">
                                <div>
                                    <div class="flex items-center gap-2">
                                        <h3 class="text-base font-bold text-[#0f172a] uppercase tracking-wide">{{ $courier['name'] ?? ucfirst($key) }}</h3>
                                    </div>
                                    <div class="flex items-center gap-2 mt-1.5">
                                        @if ($isActive)
                                            <span class="bg-emerald-50 text-emerald-700 border border-emerald-200 px-2.5 py-0.5 rounded-full text-[10px] font-bold uppercase font-mono">● Active</span>
                                        @else
                                            <span class="bg-slate-100 text-slate-500 border border-slate-200 px-2.5 py-0.5 rounded-full text-[10px] font-bold uppercase font-mono">○ Inactive</span>
                                        @endif

                                        @if (($courier['mode'] ?? '') === 'sandbox')
                                            <span class="bg-amber-50 text-amber-700 border border-amber-200 px-2 py-0.5 rounded-full text-[10px] font-bold uppercase font-mono">Sandbox</span>
                                        @elseif (($courier['mode'] ?? '') === 'manual')
                                            <span class="bg-sky-50 text-sky-700 border border-sky-200 px-2 py-0.5 rounded-full text-[10px] font-bold uppercase font-mono">Manual</span>
                                        @else
                                            <span class="bg-emerald-50 text-emerald-700 border border-emerald-200 px-2 py-0.5 rounded-full text-[10px] font-bold uppercase font-mono">Live API</span>
                                        @endif
                                    </div>
                                </div>

                                <div class="w-10 h-10 rounded-xl bg-sky-50 border border-sky-100 flex items-center justify-center text-[#0284c7] group-hover:scale-105 transition-transform shadow-xs">
                                    <i data-lucide="truck" class="w-5 h-5"></i>
                                </div>
                            </div>

                            <div class="space-y-2 text-[12px] pt-1">
                                <div class="flex items-center justify-between text-[#64748b]">
                                    <span class="text-[11px] font-bold uppercase text-[#64748b]">Zone:</span>
                                    <span class="font-medium text-[#0f172a] text-right truncate max-w-[180px]">{{ $courier['zone'] ?? 'Nationwide' }}</span>
                                </div>
                                <div class="flex items-center justify-between text-[#64748b]">
                                    <span class="text-[11px] font-bold uppercase text-[#64748b]">Rate:</span>
                                    <span class="font-bold text-[#0284c7] font-mono">{{ $courier['rate'] ?? '70 - 130 ৳' }}</span>
                                </div>
                                <div class="flex items-center justify-between text-[#64748b]">
                                    <span class="text-[11px] font-bold uppercase text-[#64748b]">Support:</span>
                                    <span class="font-medium text-[#0f172a] font-mono">{{ $courier['phone'] ?? 'N/A' }}</span>
                                </div>
                                <div class="flex items-center justify-between text-[#64748b]">
                                    <span class="text-[11px] font-bold uppercase text-[#64748b]">API Status:</span>
                                    @if ($hasCreds)
                                        <span class="text-emerald-700 font-bold text-[11px] flex items-center gap-1">
                                            <i data-lucide="key" class="w-3.5 h-3.5 text-emerald-600"></i> Configured
                                        </span>
                                    @else
                                        <span class="text-amber-700 font-bold text-[11px] flex items-center gap-1">
                                            <i data-lucide="alert-circle" class="w-3.5 h-3.5 text-amber-600"></i> Pending Credentials
                                        </span>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <div class="pt-4 border-t border-[#e2e8f0] flex items-center gap-2">
                            <button onclick="openConfigModal('{{ $key }}')" class="flex-1 px-3 py-2 bg-white hover:bg-[#f1f5f9] text-[#0f172a] rounded-xl text-[11px] font-bold uppercase tracking-wider border border-[#cbd5e1] transition-all cursor-pointer flex items-center justify-center gap-1.5 shadow-xs">
                                <i data-lucide="sliders" class="w-3.5 h-3.5 text-[#0284c7]"></i> Configure
                            </button>
                            <button onclick="testCourierApi('{{ $key }}')" id="test-btn-{{ $key }}" class="px-3 py-2 bg-emerald-50 hover:bg-emerald-100 text-emerald-700 rounded-xl text-[11px] font-bold uppercase tracking-wider border border-emerald-300 transition-all cursor-pointer flex items-center justify-center gap-1.5 shadow-xs">
                                <i data-lucide="activity" class="w-3.5 h-3.5"></i> Test
                            </button>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

        <!-- ================================================================= -->
        <!-- TAB 2: READY TO DISPATCH (QUEUE) -->
        <!-- ================================================================= -->
        <div id="section-dispatch" class="hidden space-y-6">
            <div class="bg-white/90 backdrop-blur-xl border border-[#38bdf8]/30 p-6 rounded-2xl shadow-sm flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
                <div>
                    <h2 class="text-lg font-serif font-bold text-[#0f172a] uppercase tracking-tight">Pending Orders For Dispatch</h2>
                    <p class="text-[12px] text-[#64748b]">Select pending client orders, choose delivery courier, and generate consignment tracking numbers.</p>
                </div>
                <div class="text-[12px] font-mono font-bold text-amber-800 bg-amber-50 border border-amber-300 px-3 py-1.5 rounded-xl">
                    {{ $pendingOrders->count() }} Orders Ready for Shipment
                </div>
            </div>

            <!-- Orders Dispatch Table -->
            <div class="bg-white/90 backdrop-blur-xl border border-[#38bdf8]/30 rounded-2xl overflow-hidden shadow-sm">
                <div class="overflow-x-auto">
                    <table class="w-full text-left text-[13px] text-[#0f172a]">
                        <thead class="bg-[#f8fafc] text-[#64748b] uppercase text-[10px] font-bold tracking-widest font-mono border-b border-[#e2e8f0]">
                            <tr>
                                <th class="p-4">Order #</th>
                                <th class="p-4">Client / Contact</th>
                                <th class="p-4">Delivery Address</th>
                                <th class="p-4 text-right">COD Amount</th>
                                <th class="p-4">Courier Partner</th>
                                <th class="p-4 text-center">Action</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-[#e2e8f0]">
                            @forelse ($pendingOrders as $order)
                                <tr class="hover:bg-sky-50/40 transition-colors">
                                    <td class="p-4 font-mono font-bold text-[#0f172a]">
                                        {{ $order->order_number }}
                                        <span class="block text-[10px] text-[#64748b] font-normal">{{ $order->created_at->format('M d, Y') }}</span>
                                    </td>
                                    <td class="p-4">
                                        <div class="font-bold text-[#0f172a]">{{ $order->customer_name }}</div>
                                        <div class="text-[11px] text-[#64748b] font-mono">{{ $order->customer_phone ?? 'N/A' }}</div>
                                    </td>
                                    <td class="p-4 max-w-xs truncate text-[12px] text-[#475569]">
                                        {{ $order->shipping_address }}{{ $order->city ? ', ' . $order->city : '' }}
                                    </td>
                                    <td class="p-4 text-right font-mono font-bold text-emerald-700">
                                        ৳{{ number_format($order->total_amount, 2) }}
                                        <span class="block text-[10px] text-[#64748b] uppercase">{{ $order->payment_method ?? 'COD' }}</span>
                                    </td>
                                    <td class="p-4">
                                        <select id="courier-select-{{ $order->id }}" class="bg-white border border-[#cbd5e1] text-[#0f172a] text-[12px] rounded-xl px-3 py-2 outline-none focus:border-[#0284c7] w-full shadow-xs">
                                            @foreach ($couriers as $k => $c)
                                                @if (($c['status'] ?? '') === 'active')
                                                    <option value="{{ $k }}">{{ $c['name'] }}</option>
                                                @endif
                                            @endforeach
                                        </select>
                                    </td>
                                    <td class="p-4 text-center">
                                        <button onclick="dispatchOrder({{ $order->id }})" id="dispatch-btn-{{ $order->id }}" class="px-4 py-2 bg-emerald-600 hover:bg-emerald-700 text-white rounded-xl text-[11px] font-bold uppercase tracking-wider transition-all shadow-sm cursor-pointer flex items-center justify-center gap-1.5 mx-auto">
                                            <i data-lucide="send" class="w-3.5 h-3.5"></i> Dispatch Now
                                        </button>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="p-8 text-center text-[#64748b] font-medium">
                                        <i data-lucide="package-check" class="w-10 h-10 text-slate-400 mx-auto mb-2"></i>
                                        No pending orders require dispatch at this time.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- ================================================================= -->
        <!-- TAB 3: DISPATCH HISTORY & LIVE TRACKING -->
        <!-- ================================================================= -->
        <div id="section-history" class="hidden space-y-6">
            <div class="bg-white/90 backdrop-blur-xl border border-[#38bdf8]/30 p-6 rounded-2xl shadow-sm flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
                <div>
                    <h2 class="text-lg font-serif font-bold text-[#0f172a] uppercase tracking-tight">Dispatched Consignment Records</h2>
                    <p class="text-[12px] text-[#64748b]">Track all booked parcels, courier partners, tracking IDs, and delivery statuses.</p>
                </div>

                <!-- Search & Filters -->
                <form action="{{ url('/admin/courier') }}" method="GET" class="flex flex-wrap items-center gap-3 w-full sm:w-auto">
                    <div class="relative flex-1 sm:w-64">
                        <i data-lucide="search" class="w-4 h-4 absolute left-3 top-3 text-[#94a3b8]"></i>
                        <input type="text" name="search" value="{{ request('search') }}" placeholder="Search Invoice / Tracking..." class="w-full bg-white border border-[#cbd5e1] text-[#0f172a] text-[12px] rounded-xl pl-9 pr-3 py-2 outline-none focus:border-[#0284c7] shadow-xs">
                    </div>
                    <button type="submit" class="px-4 py-2 bg-white hover:bg-[#f1f5f9] border border-[#cbd5e1] text-[#0f172a] rounded-xl text-[12px] font-bold uppercase tracking-wider transition-all shadow-xs">
                        Filter
                    </button>
                    @if (request()->hasAny(['search', 'courier']))
                        <a href="{{ url('/admin/courier') }}" class="px-3 py-2 bg-rose-50 text-rose-700 border border-rose-200 rounded-xl text-[12px] font-bold">Clear</a>
                    @endif
                </form>
            </div>

            <!-- History Table -->
            <div class="bg-white/90 backdrop-blur-xl border border-[#38bdf8]/30 rounded-2xl overflow-hidden shadow-sm">
                <div class="overflow-x-auto">
                    <table class="w-full text-left text-[13px] text-[#0f172a]">
                        <thead class="bg-[#f8fafc] text-[#64748b] uppercase text-[10px] font-bold tracking-widest font-mono border-b border-[#e2e8f0]">
                            <tr>
                                <th class="p-4">Dispatched Date</th>
                                <th class="p-4">Order #</th>
                                <th class="p-4">Client Name</th>
                                <th class="p-4">Courier Network</th>
                                <th class="p-4">Tracking Number</th>
                                <th class="p-4 text-center">Status</th>
                                <th class="p-4 text-center">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-[#e2e8f0]">
                            @forelse ($historyOrders as $order)
                                <tr class="hover:bg-sky-50/40 transition-colors">
                                    <td class="p-4 font-mono text-[11px] text-[#64748b]">
                                        {{ $order->dispatched_at ? $order->dispatched_at->format('M d, Y h:i A') : ($order->updated_at ? $order->updated_at->format('M d, Y') : 'N/A') }}
                                    </td>
                                    <td class="p-4 font-mono font-bold text-[#0f172a]">
                                        {{ $order->order_number }}
                                    </td>
                                    <td class="p-4">
                                        <div class="font-bold text-[#0f172a]">{{ $order->customer_name }}</div>
                                        <div class="text-[11px] text-[#64748b]">{{ $order->customer_phone ?? 'N/A' }}</div>
                                    </td>
                                    <td class="p-4">
                                        <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-lg text-[11px] font-bold font-mono bg-sky-50 text-sky-800 border border-sky-200">
                                            <i data-lucide="truck" class="w-3 h-3 text-[#0284c7]"></i>
                                            {{ $order->courier_name ?? 'Standard Courier' }}
                                        </span>
                                    </td>
                                    <td class="p-4 font-mono text-[12px] text-[#0284c7] font-bold">
                                        {{ $order->courier_tracking_id ?? 'N/A' }}
                                    </td>
                                    <td class="p-4 text-center">
                                        <span class="bg-emerald-50 text-emerald-700 border border-emerald-200 px-2.5 py-1 rounded-full text-[10px] font-bold uppercase font-mono">
                                            {{ $order->courier_status ?? 'Dispatched' }}
                                        </span>
                                    </td>
                                    <td class="p-4 text-center">
                                        <button onclick="trackLiveOrder({{ $order->id }})" class="px-3 py-1.5 bg-white hover:bg-[#f1f5f9] text-[#0f172a] rounded-lg text-[11px] font-bold uppercase tracking-wider border border-[#cbd5e1] transition-all cursor-pointer inline-flex items-center gap-1 shadow-xs">
                                            <i data-lucide="search" class="w-3 h-3 text-[#0284c7]"></i> Track
                                        </button>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="p-8 text-center text-[#64748b] font-medium">
                                        <i data-lucide="history" class="w-10 h-10 text-slate-400 mx-auto mb-2"></i>
                                        No dispatch history found.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                @if ($historyOrders->hasPages())
                    <div class="p-4 border-t border-[#e2e8f0] flex justify-end">
                        {{ $historyOrders->links() }}
                    </div>
                @endif
            </div>
        </div>
    </main>

    <!-- ===================================================================== -->
    <!-- MODAL: COURIER PARTNER CONFIGURATION -->
    <!-- ===================================================================== -->
    <div id="courierConfigModal" class="fixed inset-0 z-50 bg-slate-900/60 backdrop-blur-sm hidden flex items-center justify-center p-4">
        <div class="bg-white border border-[#38bdf8]/40 rounded-3xl max-w-xl w-full p-6 sm:p-8 space-y-6 shadow-2xl relative max-h-[90vh] overflow-y-auto">
            <div class="flex justify-between items-start border-b border-[#e2e8f0] pb-4">
                <div>
                    <span class="text-[10px] text-[#0284c7] uppercase font-bold tracking-[0.2em] font-mono block">INTEGRATION CREDENTIALS</span>
                    <h3 id="modalCourierTitle" class="text-xl font-serif font-bold text-[#0f172a] uppercase tracking-tight">Configure Courier</h3>
                </div>
                <button onclick="closeConfigModal()" class="w-8 h-8 rounded-full bg-slate-100 border border-slate-200 text-slate-500 hover:text-[#0f172a] flex items-center justify-center cursor-pointer">
                    <i data-lucide="x" class="w-4 h-4"></i>
                </button>
            </div>

            <form id="courierConfigForm" onsubmit="saveCourierConfigSubmit(event)" class="space-y-4 text-[13px]">
                <input type="hidden" id="modalCourierKey" name="courier_key">

                <!-- Partner Status & Environment -->
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-[11px] uppercase font-bold text-[#64748b] font-mono mb-1.5">Status</label>
                        <select id="modalStatus" name="status" class="w-full bg-slate-50 border border-[#cbd5e1] text-[#0f172a] rounded-xl px-3.5 py-2.5 outline-none focus:border-[#0284c7] shadow-xs">
                            <option value="active">Active (Enabled)</option>
                            <option value="inactive">Inactive (Disabled)</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-[11px] uppercase font-bold text-[#64748b] font-mono mb-1.5">Environment</label>
                        <select id="modalMode" name="mode" class="w-full bg-slate-50 border border-[#cbd5e1] text-[#0f172a] rounded-xl px-3.5 py-2.5 outline-none focus:border-[#0284c7] shadow-xs">
                            <option value="live">Production / Live API</option>
                            <option value="sandbox">Sandbox / Test Mode</option>
                            <option value="manual">Manual Booking</option>
                        </select>
                    </div>
                </div>

                <!-- General Info -->
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-[11px] uppercase font-bold text-[#64748b] font-mono mb-1.5">Support Phone</label>
                        <input type="text" id="modalPhone" name="phone" placeholder="e.g. 09612-000000" class="w-full bg-white border border-[#cbd5e1] text-[#0f172a] rounded-xl px-3.5 py-2.5 outline-none focus:border-[#0284c7] shadow-xs">
                    </div>
                    <div>
                        <label class="block text-[11px] uppercase font-bold text-[#64748b] font-mono mb-1.5">Delivery Rate</label>
                        <input type="text" id="modalRate" name="rate" placeholder="e.g. 70 - 130 ৳" class="w-full bg-white border border-[#cbd5e1] text-[#0f172a] rounded-xl px-3.5 py-2.5 outline-none focus:border-[#0284c7] shadow-xs">
                    </div>
                </div>

                <div>
                    <label class="block text-[11px] uppercase font-bold text-[#64748b] font-mono mb-1.5">Coverage Zone</label>
                    <input type="text" id="modalZone" name="zone" placeholder="e.g. Nationwide (All 64 Districts)" class="w-full bg-white border border-[#cbd5e1] text-[#0f172a] rounded-xl px-3.5 py-2.5 outline-none focus:border-[#0284c7] shadow-xs">
                </div>

                <!-- Dynamic Credentials Section -->
                <div class="pt-3 border-t border-[#e2e8f0] space-y-3">
                    <h4 class="text-[12px] uppercase font-bold text-[#0284c7] font-mono">API Authentication Keys</h4>
                    
                    <div id="dynamicCredFields" class="space-y-3">
                        <!-- Populated by JavaScript according to provider -->
                    </div>
                </div>

                <!-- Modal Actions -->
                <div class="pt-4 border-t border-[#e2e8f0] flex items-center justify-between gap-3">
                    <button type="button" onclick="testModalApiConnection()" id="modalTestBtn" class="px-4 py-2.5 bg-emerald-50 hover:bg-emerald-100 text-emerald-700 border border-emerald-300 rounded-xl text-[12px] font-bold uppercase tracking-wider transition-all cursor-pointer flex items-center gap-1.5 shadow-xs">
                        <i data-lucide="activity" class="w-4 h-4"></i> Test Connection
                    </button>
                    <div class="flex items-center gap-2">
                        <button type="button" onclick="closeConfigModal()" class="px-4 py-2.5 bg-slate-100 hover:bg-slate-200 text-[#475569] rounded-xl text-[12px] font-bold uppercase tracking-wider transition-all cursor-pointer">
                            Cancel
                        </button>
                        <button type="submit" id="modalSaveBtn" class="px-5 py-2.5 bg-[#0284c7] hover:bg-[#0369a1] text-white rounded-xl text-[12px] font-bold uppercase tracking-wider transition-all shadow-md cursor-pointer flex items-center gap-1.5">
                            <i data-lucide="save" class="w-4 h-4"></i> Save Settings
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- ===================================================================== -->
    <!-- MODAL: LIVE TRACKING INQUIRY -->
    <!-- ===================================================================== -->
    <div id="trackingModal" class="fixed inset-0 z-50 bg-slate-900/60 backdrop-blur-sm hidden flex items-center justify-center p-4">
        <div class="bg-white border border-[#38bdf8]/40 rounded-3xl max-w-md w-full p-6 space-y-5 shadow-2xl relative">
            <div class="flex justify-between items-start border-b border-[#e2e8f0] pb-3">
                <div>
                    <span class="text-[10px] text-[#0284c7] uppercase font-bold tracking-[0.2em] font-mono block">CONSIGNMENT TRACKER</span>
                    <h3 id="trackingModalTitle" class="text-xl font-serif font-bold text-[#0f172a] uppercase tracking-tight">Order Tracking</h3>
                </div>
                <button onclick="closeTrackingModal()" class="w-8 h-8 rounded-full bg-slate-100 border border-slate-200 text-slate-500 hover:text-[#0f172a] flex items-center justify-center cursor-pointer">
                    <i data-lucide="x" class="w-4 h-4"></i>
                </button>
            </div>

            <div id="trackingModalBody" class="space-y-3 text-[13px]">
                <!-- Populated by JS -->
            </div>

            <div class="pt-3 border-t border-[#e2e8f0] flex justify-end">
                <button onclick="closeTrackingModal()" class="px-4 py-2 bg-slate-100 hover:bg-slate-200 text-[#0f172a] rounded-xl text-[12px] font-bold uppercase tracking-wider">
                    Close
                </button>
            </div>
        </div>
    </div>

    <!-- JavaScript Interactive Logic -->
    <script>
        const couriersData = @json($couriers);
        const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

        // Sidebar Submenu Accordion
        function toggleSubmenu(id) {
            const panel = document.getElementById('sub-' + id);
            const chevron = document.querySelector(`[data-chevron="${id}"]`);
            if (panel) {
                panel.classList.toggle('submenu-open');
                if (chevron) chevron.classList.toggle('chevron-open');
            }
        }

        // Tab Switching
        function switchCourierTab(tab) {
            document.getElementById('section-partners').classList.add('hidden');
            document.getElementById('section-dispatch').classList.add('hidden');
            document.getElementById('section-history').classList.add('hidden');

            document.querySelectorAll('.tab-btn').forEach(btn => {
                btn.className = 'tab-btn px-5 py-2.5 rounded-xl text-[12px] font-bold uppercase tracking-wider transition-all text-[#64748b] hover:text-[#0284c7] hover:bg-white cursor-pointer';
            });

            document.getElementById('section-' + tab).classList.remove('hidden');
            const activeBtn = document.getElementById('tab-' + tab);
            activeBtn.className = 'tab-btn px-5 py-2.5 rounded-xl text-[12px] font-bold uppercase tracking-wider transition-all bg-[#0284c7] text-white shadow-sm cursor-pointer';

            // Sidebar highlight
            ['partners', 'dispatch', 'history'].forEach(t => {
                const subnav = document.getElementById('subnav-' + t);
                if (subnav) {
                    if (t === tab) {
                        subnav.className = 'w-full text-left px-3 py-1.5 text-[12px] font-bold uppercase text-[#0284c7] bg-[#f0f9ff] rounded-lg font-mono';
                    } else {
                        subnav.className = 'w-full text-left px-3 py-1.5 text-[12px] font-bold uppercase text-[#64748b] hover:text-[#0284c7] hover:bg-[#f8fafc] rounded-lg font-mono';
                    }
                }
            });

            lucide.createIcons();
        }

        // Open Configuration Modal
        function openConfigModal(key) {
            const courier = couriersData[key];
            if (!courier) return;

            document.getElementById('modalCourierKey').value = key;
            document.getElementById('modalCourierTitle').innerText = 'Configure ' + (courier.name || key);
            document.getElementById('modalStatus').value = courier.status || 'active';
            document.getElementById('modalMode').value = courier.mode || 'live';
            document.getElementById('modalPhone').value = courier.phone || '';
            document.getElementById('modalRate').value = courier.rate || '';
            document.getElementById('modalZone').value = courier.zone || '';

            const credContainer = document.getElementById('dynamicCredFields');
            credContainer.innerHTML = '';

            const creds = courier.credentials || {};

            if (key === 'steadfast') {
                credContainer.innerHTML = `
                    <div>
                        <label class="block text-[11px] uppercase font-bold text-[#64748b] font-mono mb-1">API Key (api-key header)</label>
                        <input type="text" name="credentials[api_key]" value="${creds.api_key || ''}" placeholder="Enter Steadfast API Key" class="w-full bg-slate-50 border border-[#cbd5e1] text-[#0f172a] rounded-xl px-3.5 py-2 outline-none focus:border-[#0284c7]">
                    </div>
                    <div>
                        <label class="block text-[11px] uppercase font-bold text-[#64748b] font-mono mb-1">Secret Key (secret-key header)</label>
                        <input type="password" name="credentials[secret_key]" value="${creds.secret_key || ''}" placeholder="Enter Steadfast Secret Key" class="w-full bg-slate-50 border border-[#cbd5e1] text-[#0f172a] rounded-xl px-3.5 py-2 outline-none focus:border-[#0284c7]">
                    </div>
                    <div>
                        <label class="block text-[11px] uppercase font-bold text-[#64748b] font-mono mb-1">Base Endpoint URL</label>
                        <input type="text" name="credentials[base_url]" value="${creds.base_url || 'https://portal.steadfast.com.bd/api/v1'}" class="w-full bg-slate-50 border border-[#cbd5e1] text-[#64748b] rounded-xl px-3.5 py-2 outline-none">
                    </div>
                `;
            } else if (key === 'pathao') {
                credContainer.innerHTML = `
                    <div class="grid grid-cols-2 gap-3">
                        <div>
                            <label class="block text-[11px] uppercase font-bold text-[#64748b] font-mono mb-1">Client ID</label>
                            <input type="text" name="credentials[client_id]" value="${creds.client_id || ''}" placeholder="Pathao Client ID" class="w-full bg-slate-50 border border-[#cbd5e1] text-[#0f172a] rounded-xl px-3.5 py-2 outline-none focus:border-[#0284c7]">
                        </div>
                        <div>
                            <label class="block text-[11px] uppercase font-bold text-[#64748b] font-mono mb-1">Client Secret</label>
                            <input type="password" name="credentials[client_secret]" value="${creds.client_secret || ''}" placeholder="Pathao Client Secret" class="w-full bg-slate-50 border border-[#cbd5e1] text-[#0f172a] rounded-xl px-3.5 py-2 outline-none focus:border-[#0284c7]">
                        </div>
                    </div>
                    <div class="grid grid-cols-2 gap-3">
                        <div>
                            <label class="block text-[11px] uppercase font-bold text-[#64748b] font-mono mb-1">Username / Email</label>
                            <input type="email" name="credentials[username]" value="${creds.username || ''}" placeholder="Merchant email" class="w-full bg-slate-50 border border-[#cbd5e1] text-[#0f172a] rounded-xl px-3.5 py-2 outline-none focus:border-[#0284c7]">
                        </div>
                        <div>
                            <label class="block text-[11px] uppercase font-bold text-[#64748b] font-mono mb-1">Password</label>
                            <input type="password" name="credentials[password]" value="${creds.password || ''}" placeholder="Merchant password" class="w-full bg-slate-50 border border-[#cbd5e1] text-[#0f172a] rounded-xl px-3.5 py-2 outline-none focus:border-[#0284c7]">
                        </div>
                    </div>
                    <div>
                        <label class="block text-[11px] uppercase font-bold text-[#64748b] font-mono mb-1">Pickup Store ID</label>
                        <input type="text" name="credentials[store_id]" value="${creds.store_id || ''}" placeholder="Pathao Store ID (Numeric)" class="w-full bg-slate-50 border border-[#cbd5e1] text-[#0f172a] rounded-xl px-3.5 py-2 outline-none focus:border-[#0284c7]">
                    </div>
                `;
            } else if (key === 'redx') {
                credContainer.innerHTML = `
                    <div>
                        <label class="block text-[11px] uppercase font-bold text-[#64748b] font-mono mb-1">Bearer API Token</label>
                        <input type="password" name="credentials[api_token]" value="${creds.api_token || ''}" placeholder="Enter RedX Bearer Token" class="w-full bg-slate-50 border border-[#cbd5e1] text-[#0f172a] rounded-xl px-3.5 py-2 outline-none focus:border-[#0284c7]">
                    </div>
                    <div>
                        <label class="block text-[11px] uppercase font-bold text-[#64748b] font-mono mb-1">Store / Pickup ID (Optional)</label>
                        <input type="text" name="credentials[store_id]" value="${creds.store_id || ''}" placeholder="Store ID" class="w-full bg-slate-50 border border-[#cbd5e1] text-[#0f172a] rounded-xl px-3.5 py-2 outline-none focus:border-[#0284c7]">
                    </div>
                `;
            } else if (key === 'paperfly') {
                credContainer.innerHTML = `
                    <div class="grid grid-cols-2 gap-3">
                        <div>
                            <label class="block text-[11px] uppercase font-bold text-[#64748b] font-mono mb-1">Username</label>
                            <input type="text" name="credentials[username]" value="${creds.username || ''}" class="w-full bg-slate-50 border border-[#cbd5e1] text-[#0f172a] rounded-xl px-3.5 py-2 outline-none focus:border-[#0284c7]">
                        </div>
                        <div>
                            <label class="block text-[11px] uppercase font-bold text-[#64748b] font-mono mb-1">Password</label>
                            <input type="password" name="credentials[password]" value="${creds.password || ''}" class="w-full bg-slate-50 border border-[#cbd5e1] text-[#0f172a] rounded-xl px-3.5 py-2 outline-none focus:border-[#0284c7]">
                        </div>
                    </div>
                    <div>
                        <label class="block text-[11px] uppercase font-bold text-[#64748b] font-mono mb-1">Paperfly API Key</label>
                        <input type="password" name="credentials[api_key]" value="${creds.api_key || ''}" class="w-full bg-slate-50 border border-[#cbd5e1] text-[#0f172a] rounded-xl px-3.5 py-2 outline-none focus:border-[#0284c7]">
                    </div>
                `;
            } else if (key === 'ecourier') {
                credContainer.innerHTML = `
                    <div class="grid grid-cols-2 gap-3">
                        <div>
                            <label class="block text-[11px] uppercase font-bold text-[#64748b] font-mono mb-1">API Key</label>
                            <input type="text" name="credentials[api_key]" value="${creds.api_key || ''}" class="w-full bg-slate-50 border border-[#cbd5e1] text-[#0f172a] rounded-xl px-3.5 py-2 outline-none focus:border-[#0284c7]">
                        </div>
                        <div>
                            <label class="block text-[11px] uppercase font-bold text-[#64748b] font-mono mb-1">API Secret</label>
                            <input type="password" name="credentials[api_secret]" value="${creds.api_secret || ''}" class="w-full bg-slate-50 border border-[#cbd5e1] text-[#0f172a] rounded-xl px-3.5 py-2 outline-none focus:border-[#0284c7]">
                        </div>
                    </div>
                    <div>
                        <label class="block text-[11px] uppercase font-bold text-[#64748b] font-mono mb-1">User ID</label>
                        <input type="text" name="credentials[user_id]" value="${creds.user_id || ''}" class="w-full bg-slate-50 border border-[#cbd5e1] text-[#0f172a] rounded-xl px-3.5 py-2 outline-none focus:border-[#0284c7]">
                    </div>
                `;
            } else {
                credContainer.innerHTML = `
                    <p class="text-[12px] text-[#64748b]">Manual / Standard Courier partner. No API Key required for standard order fulfillment.</p>
                `;
            }

            document.getElementById('courierConfigModal').classList.remove('hidden');
            lucide.createIcons();
        }

        function closeConfigModal() {
            document.getElementById('courierConfigModal').classList.add('hidden');
        }

        // Save Courier Configuration via AJAX
        async function saveCourierConfigSubmit(e) {
            e.preventDefault();
            const form = document.getElementById('courierConfigForm');
            const formData = new FormData(form);
            const key = document.getElementById('modalCourierKey').value;
            const saveBtn = document.getElementById('modalSaveBtn');

            saveBtn.disabled = true;
            saveBtn.innerHTML = `<i data-lucide="loader-2" class="w-4 h-4 animate-spin"></i> Saving...`;
            lucide.createIcons();

            try {
                const res = await fetch(`{{ url('/admin/courier/save') }}/${key}`, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': csrfToken,
                        'Accept': 'application/json',
                    },
                    body: formData,
                });

                const data = await res.json();
                if (data.success) {
                    alert('Configuration saved to database successfully.');
                    window.location.reload();
                } else {
                    alert('Error saving configuration: ' + (data.message || 'Unknown error'));
                }
            } catch (err) {
                alert('Request failed: ' + err.message);
            } finally {
                saveBtn.disabled = false;
                saveBtn.innerHTML = `<i data-lucide="save" class="w-4 h-4"></i> Save Settings`;
                lucide.createIcons();
            }
        }

        // Test API Connection
        async function testCourierApi(key) {
            const btn = document.getElementById('test-btn-' + key);
            if (btn) {
                btn.disabled = true;
                btn.innerHTML = `<i data-lucide="loader-2" class="w-3.5 h-3.5 animate-spin"></i>`;
                lucide.createIcons();
            }

            try {
                const res = await fetch(`{{ url('/admin/courier/test-connection') }}`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': csrfToken,
                        'Accept': 'application/json',
                    },
                    body: JSON.stringify({ provider: key }),
                });

                const data = await res.json();
                alert((data.success ? '✅ ' : '⚠️ ') + data.message);
            } catch (err) {
                alert('Connection test failed: ' + err.message);
            } finally {
                if (btn) {
                    btn.disabled = false;
                    btn.innerHTML = `<i data-lucide="activity" class="w-3.5 h-3.5"></i> Test`;
                    lucide.createIcons();
                }
            }
        }

        async function testModalApiConnection() {
            const form = document.getElementById('courierConfigForm');
            const formData = new FormData(form);
            const key = document.getElementById('modalCourierKey').value;
            const btn = document.getElementById('modalTestBtn');

            btn.disabled = true;
            btn.innerHTML = `<i data-lucide="loader-2" class="w-4 h-4 animate-spin"></i> Pinging...`;
            lucide.createIcons();

            const creds = {};
            for (let [name, val] of formData.entries()) {
                if (name.startsWith('credentials[')) {
                    const field = name.replace('credentials[', '').replace(']', '');
                    creds[field] = val;
                }
            }

            try {
                const res = await fetch(`{{ url('/admin/courier/test-connection') }}`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': csrfToken,
                        'Accept': 'application/json',
                    },
                    body: JSON.stringify({
                        provider: key,
                        credentials: creds,
                        mode: document.getElementById('modalMode').value,
                    }),
                });

                const data = await res.json();
                alert((data.success ? '✅ SUCCESS:\n' : '⚠️ NOTICE:\n') + data.message);
            } catch (err) {
                alert('Test request error: ' + err.message);
            } finally {
                btn.disabled = false;
                btn.innerHTML = `<i data-lucide="activity" class="w-4 h-4"></i> Test Connection`;
                lucide.createIcons();
            }
        }

        // Dispatch Order
        async function dispatchOrder(orderId) {
            const courierSelect = document.getElementById('courier-select-' + orderId);
            const provider = courierSelect ? courierSelect.value : 'steadfast';
            const btn = document.getElementById('dispatch-btn-' + orderId);

            if (!confirm(`Dispatch Order #${orderId} via ${provider.toUpperCase()}?`)) return;

            btn.disabled = true;
            btn.innerHTML = `<i data-lucide="loader-2" class="w-3.5 h-3.5 animate-spin"></i> Dispatching...`;
            lucide.createIcons();

            try {
                const res = await fetch(`{{ url('/admin/courier/dispatch') }}`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': csrfToken,
                        'Accept': 'application/json',
                    },
                    body: JSON.stringify({
                        order_id: orderId,
                        provider: provider,
                    }),
                });

                const data = await res.json();
                if (data.success) {
                    alert(`✅ ${data.message}\nTracking Code: ${data.tracking_id}`);
                    window.location.reload();
                } else {
                    alert('❌ Dispatch error: ' + (data.message || 'Unknown failure'));
                    btn.disabled = false;
                    btn.innerHTML = `<i data-lucide="send" class="w-3.5 h-3.5"></i> Dispatch Now`;
                    lucide.createIcons();
                }
            } catch (err) {
                alert('Dispatch request failed: ' + err.message);
                btn.disabled = false;
                btn.innerHTML = `<i data-lucide="send" class="w-3.5 h-3.5"></i> Dispatch Now`;
                lucide.createIcons();
            }
        }

        // Live Order Tracking
        async function trackLiveOrder(orderId) {
            document.getElementById('trackingModal').classList.remove('hidden');
            const body = document.getElementById('trackingModalBody');
            body.innerHTML = `<div class="p-6 text-center text-[#64748b]"><i data-lucide="loader-2" class="w-6 h-6 animate-spin mx-auto mb-2 text-[#0284c7]"></i> Fetching tracking telemetry...</div>`;
            lucide.createIcons();

            try {
                const res = await fetch(`{{ url('/admin/courier/track') }}/${orderId}`, {
                    headers: { 'Accept': 'application/json' },
                });
                const data = await res.json();

                if (data.success) {
                    body.innerHTML = `
                        <div class="bg-[#f8fafc] p-4 rounded-2xl border border-[#e2e8f0] space-y-2.5 font-mono text-[12px]">
                            <div class="flex justify-between">
                                <span class="text-[#64748b] uppercase">Order Number:</span>
                                <span class="text-[#0f172a] font-bold">${data.order_number}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-[#64748b] uppercase">Courier Network:</span>
                                <span class="text-[#0284c7] font-bold">${data.courier_name}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-[#64748b] uppercase">Tracking ID:</span>
                                <span class="text-emerald-700 font-bold">${data.tracking_id}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-[#64748b] uppercase">Consignment:</span>
                                <span class="text-[#475569]">${data.consignment_id || 'N/A'}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-[#64748b] uppercase">Dispatched At:</span>
                                <span class="text-[#475569]">${data.dispatched_at || 'Just now'}</span>
                            </div>
                            <div class="flex justify-between items-center pt-2 border-t border-[#e2e8f0]">
                                <span class="text-[#64748b] uppercase">Live Status:</span>
                                <span class="bg-emerald-50 text-emerald-700 border border-emerald-200 px-2.5 py-0.5 rounded-full text-[10px] font-bold uppercase">
                                    ${data.status}
                                </span>
                            </div>
                        </div>

                        ${data.tracking_url ? `
                            <a href="${data.tracking_url}" target="_blank" class="w-full py-2.5 bg-emerald-600 hover:bg-emerald-700 text-white rounded-xl text-[12px] font-bold uppercase tracking-wider transition-all flex items-center justify-center gap-1.5 shadow-sm">
                                <i data-lucide="external-link" class="w-4 h-4"></i> Open Official Courier Tracking Portal
                            </a>
                        ` : ''}
                    `;
                } else {
                    body.innerHTML = `<div class="p-4 text-center text-amber-800 bg-amber-50 border border-amber-200 rounded-xl">${data.message}</div>`;
                }
            } catch (err) {
                body.innerHTML = `<div class="p-4 text-center text-rose-800 bg-rose-50 border border-rose-200 rounded-xl">Error: ${err.message}</div>`;
            }
            lucide.createIcons();
        }

        function closeTrackingModal() {
            document.getElementById('trackingModal').classList.add('hidden');
        }

        document.addEventListener('DOMContentLoaded', () => {
            lucide.createIcons();
        });
    </script>
</body>
</html>
