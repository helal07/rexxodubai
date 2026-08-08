<!DOCTYPE html>
<html lang="en" data-theme="{{ $siteSettings['admin_theme'] ?? 'default' }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $siteSettings['siteName'] ?? 'RaaxO BD' }} — Orders & Fulfillment</title>
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
        
        /* Custom Scrollbar */
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
        body.theme-night select {
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
        @include('admin.partials.sidebar', ['activePage' => 'orders', 'siteSettings' => $siteSettings])

        <!-- 2. MAIN CONTENT WRAPPER -->
        <main class="flex-1 p-6 lg:p-8 w-full space-y-6 relative z-10 overflow-y-auto max-h-[calc(100vh-3.5rem)]">

        <!-- Top Navigation Header -->
        <header class="theme-card bg-white/90 backdrop-blur-xl border border-[#38bdf8]/30 rounded-2xl p-6 flex flex-col md:flex-row justify-between items-start md:items-center gap-4 shadow-sm animate-fade-in">
            <div class="flex items-center gap-4">
                <div class="w-12 h-12 rounded-2xl bg-gradient-to-br from-[#0284c7] to-[#0369a1] text-white flex items-center justify-center shadow-lg shadow-[#0284c7]/20">
                    <i data-lucide="shopping-bag" class="w-6 h-6"></i>
                </div>
                <div>
                    <div class="flex items-center gap-2">
                        <span class="text-[11px] font-bold uppercase tracking-widest text-[#0284c7]">COMMERCE DISPATCH</span>
                        <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold uppercase bg-[#e0f2fe] text-[#0284c7] border border-[#bae6fd]">
                            {{ $totalOrdersCount }} TOTAL ORDERS
                        </span>
                        @if(request('status'))
                            <span class="px-2 py-0.5 rounded-full text-[10px] font-bold uppercase bg-amber-50 text-amber-700 border border-amber-200">
                                FILTER: {{ strtoupper(request('status')) }}
                            </span>
                        @endif
                    </div>
                    <h2 class="text-[22px] font-serif font-bold text-[#0f172a] uppercase tracking-tight">
                        Client Orders & Fulfillment Console
                    </h2>
                </div>
            </div>

            <div class="flex items-center flex-wrap gap-2.5">
                <a href="{{ url('/admin/dashboard#create_order') }}" class="px-4 py-2.5 bg-emerald-600 hover:bg-emerald-700 text-white text-[12px] font-bold uppercase tracking-wider rounded-xl transition-all shadow-md shadow-emerald-600/20 flex items-center gap-2">
                    <i data-lucide="plus-circle" class="w-4 h-4"></i> Create Sale / POS
                </a>
                <a href="{{ url('/admin/courier') }}" class="px-4 py-2.5 bg-[#0284c7] hover:bg-[#0369a1] text-white text-[12px] font-bold uppercase tracking-wider rounded-xl transition-all shadow-md shadow-[#0284c7]/20 flex items-center gap-2">
                    <i data-lucide="truck" class="w-4 h-4"></i> Courier Hub
                </a>
                <a href="{{ url('/admin/products') }}" class="px-4 py-2.5 bg-white hover:bg-[#f8fafc] border border-[#cbd5e1] text-[#475569] text-[12px] font-bold uppercase tracking-wider rounded-xl transition-all shadow-xs flex items-center gap-2">
                    <i data-lucide="package" class="w-4 h-4"></i> Products
                </a>
                <a href="{{ url('/') }}" target="_blank" class="px-4 py-2.5 bg-slate-800 hover:bg-slate-900 text-white text-[12px] font-bold uppercase tracking-wider rounded-xl transition-all shadow-xs flex items-center gap-2">
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

        <!-- Metrics Overview Grid -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5 animate-fade-in">
            <!-- Total Orders -->
            <a href="{{ url('/admin/orders') }}" class="theme-card bg-white/90 backdrop-blur-xl border border-[#38bdf8]/30 p-5 rounded-2xl space-y-2 card-elevated shadow-xs block">
                <div class="flex justify-between items-center text-[#64748b]">
                    <span class="text-[11px] uppercase font-bold tracking-wider">TOTAL ORDERS</span>
                    <div class="p-2 bg-[#e0f2fe] text-[#0284c7] rounded-lg">
                        <i data-lucide="shopping-cart" class="w-4 h-4"></i>
                    </div>
                </div>
                <div class="text-3xl font-bold font-mono text-[#0f172a]">{{ $totalOrdersCount }}</div>
                <span class="text-[11px] text-[#64748b]">All client transactions</span>
            </a>

            <!-- Total Revenue -->
            <div class="theme-card bg-white/90 backdrop-blur-xl border border-[#38bdf8]/30 p-5 rounded-2xl space-y-2 card-elevated shadow-xs">
                <div class="flex justify-between items-center text-[#64748b]">
                    <span class="text-[11px] uppercase font-bold tracking-wider">GROSS REVENUE</span>
                    <div class="p-2 bg-emerald-50 text-emerald-600 rounded-lg">
                        <i data-lucide="dollar-sign" class="w-4 h-4"></i>
                    </div>
                </div>
                <div class="text-3xl font-bold font-mono text-emerald-600">৳{{ number_format($totalRevenue, 2) }}</div>
                <span class="text-[11px] text-[#64748b]">Excluding cancelled orders</span>
            </div>

            <!-- Pending Dispatch -->
            <a href="{{ url('/admin/orders?status=pending') }}" class="theme-card bg-white/90 backdrop-blur-xl border border-[#38bdf8]/30 p-5 rounded-2xl space-y-2 card-elevated shadow-xs block">
                <div class="flex justify-between items-center text-[#64748b]">
                    <span class="text-[11px] uppercase font-bold tracking-wider">PENDING DISPATCH</span>
                    <div class="p-2 bg-amber-50 text-amber-600 rounded-lg">
                        <i data-lucide="clock" class="w-4 h-4"></i>
                    </div>
                </div>
                <div class="text-3xl font-bold font-mono text-amber-600">{{ $pendingOrdersCount }}</div>
                <span class="text-[11px] text-[#64748b]">Awaiting fulfillment</span>
            </a>

            <!-- Fulfilled -->
            <a href="{{ url('/admin/orders?status=completed') }}" class="theme-card bg-white/90 backdrop-blur-xl border border-[#38bdf8]/30 p-5 rounded-2xl space-y-2 card-elevated shadow-xs block">
                <div class="flex justify-between items-center text-[#64748b]">
                    <span class="text-[11px] uppercase font-bold tracking-wider">SUCCESS FULFILLED</span>
                    <div class="p-2 bg-emerald-50 text-emerald-600 rounded-lg">
                        <i data-lucide="check-check" class="w-4 h-4"></i>
                    </div>
                </div>
                <div class="text-3xl font-bold font-mono text-emerald-600">{{ $completedOrdersCount }}</div>
                <span class="text-[11px] text-[#64748b]">Delivered to clients</span>
            </a>
        </div>

        <!-- Filter & Search Controls -->
        <div class="theme-card bg-white/90 backdrop-blur-xl border border-[#38bdf8]/30 p-5 rounded-2xl shadow-sm animate-fade-in">
            <form action="{{ url('/admin/orders') }}" method="GET" class="flex flex-col lg:flex-row gap-4 justify-between items-stretch lg:items-center">
                <!-- Search Input -->
                <div class="relative flex-1 max-w-md">
                    <i data-lucide="search" class="w-4 h-4 absolute left-3.5 top-1/2 -translate-y-1/2 text-[#94a3b8]"></i>
                    <input
                        type="text"
                        name="search"
                        value="{{ request('search') }}"
                        placeholder="Search Order #, Client name, phone, city..."
                        class="w-full border border-[#cbd5e1] pl-10 pr-4 py-2.5 rounded-xl text-[13px] focus:outline-none focus:border-[#0284c7] focus:ring-1 focus:ring-[#0284c7] transition-all bg-white text-[#0f172a] shadow-xs"
                    />
                </div>

                <!-- Status Filter Pills -->
                <div class="flex flex-wrap gap-2 items-center">
                    <span class="text-[11px] uppercase font-bold text-[#64748b] mr-1">STATUS:</span>
                    
                    <a href="{{ request()->fullUrlWithQuery(['status' => 'all']) }}"
                       class="px-3.5 py-1.5 rounded-xl text-[11px] font-bold uppercase tracking-wider transition-all {{ (!request('status') || request('status') === 'all') ? 'bg-[#0284c7] text-white shadow-xs' : 'bg-[#f1f5f9] text-[#475569] hover:bg-[#e2e8f0]' }}">
                        All ({{ $totalOrdersCount }})
                    </a>

                    <a href="{{ request()->fullUrlWithQuery(['status' => 'pending']) }}"
                       class="px-3.5 py-1.5 rounded-xl text-[11px] font-bold uppercase tracking-wider transition-all {{ request('status') === 'pending' ? 'bg-amber-500 text-white shadow-xs' : 'bg-amber-50 text-amber-700 hover:bg-amber-100' }}">
                        Pending ({{ $pendingOrdersCount }})
                    </a>

                    <a href="{{ request()->fullUrlWithQuery(['status' => 'processing']) }}"
                       class="px-3.5 py-1.5 rounded-xl text-[11px] font-bold uppercase tracking-wider transition-all {{ request('status') === 'processing' ? 'bg-blue-600 text-white shadow-xs' : 'bg-blue-50 text-blue-700 hover:bg-blue-100' }}">
                        Processing ({{ $processingOrdersCount }})
                    </a>

                    <a href="{{ request()->fullUrlWithQuery(['status' => 'completed']) }}"
                       class="px-3.5 py-1.5 rounded-xl text-[11px] font-bold uppercase tracking-wider transition-all {{ request('status') === 'completed' ? 'bg-emerald-600 text-white shadow-xs' : 'bg-emerald-50 text-emerald-700 hover:bg-emerald-100' }}">
                        Completed ({{ $completedOrdersCount }})
                    </a>

                    <a href="{{ request()->fullUrlWithQuery(['status' => 'cancelled']) }}"
                       class="px-3.5 py-1.5 rounded-xl text-[11px] font-bold uppercase tracking-wider transition-all {{ request('status') === 'cancelled' ? 'bg-rose-600 text-white shadow-xs' : 'bg-rose-50 text-rose-700 hover:bg-rose-100' }}">
                        Cancelled
                    </a>
                </div>

                <div class="flex gap-2">
                    <button type="submit" class="bg-[#0284c7] hover:bg-[#0369a1] text-white px-5 py-2.5 rounded-xl text-[12px] font-bold uppercase tracking-wider transition-all shadow-md shadow-[#0284c7]/20">
                        Apply Filter
                    </button>
                    @if(request('search') || request('status') || request('payment_status'))
                        <a href="{{ url('/admin/orders') }}" class="bg-[#f1f5f9] hover:bg-[#e2e8f0] text-[#475569] px-3.5 py-2.5 rounded-xl text-[12px] transition-all flex items-center justify-center border border-[#cbd5e1]" title="Clear Filters">
                            <i data-lucide="x" class="w-4 h-4"></i>
                        </a>
                    @endif
                </div>
            </form>
        </div>

        <!-- Orders Table Card -->
        <div class="theme-card bg-white/90 backdrop-blur-xl border border-[#38bdf8]/30 rounded-2xl shadow-sm overflow-hidden animate-fade-in">
            <div class="p-6 border-b border-[#e2e8f0] flex flex-col sm:flex-row justify-between items-start sm:items-center gap-3">
                <h3 class="text-[16px] font-serif font-bold text-[#0f172a] uppercase tracking-wide flex items-center gap-2">
                    <i data-lucide="list-ordered" class="w-5 h-5 text-[#0284c7]"></i>
                    Order Manifest Records
                </h3>
                <span class="text-[11px] font-mono text-[#0284c7] bg-[#f0f9ff] px-3 py-1 rounded-full border border-[#bae6fd]">
                    Showing {{ $orders->count() }} of {{ $orders->total() }} Records
                </span>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left text-[13px]">
                    <thead class="bg-[#f8fafc] text-[#64748b] uppercase text-[10px] font-mono tracking-wider border-b border-[#e2e8f0]">
                        <tr>
                            <th class="py-4 px-6">Order Ref & Date</th>
                            <th class="py-4 px-6">Client / Recipient</th>
                            <th class="py-4 px-6">Items & Formula</th>
                            <th class="py-4 px-6">Total Amount</th>
                            <th class="py-4 px-6">Payment</th>
                            <th class="py-4 px-6">Fulfillment Status</th>
                            <th class="py-4 px-6 text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-[#e2e8f0]">
                        @forelse($orders as $order)
                            <tr class="hover:bg-[#f0f9ff]/50 transition-colors">
                                <!-- Order Reference -->
                                <td class="py-4 px-6">
                                    <div class="font-mono font-bold text-[#0284c7] tracking-wider">{{ $order->order_number }}</div>
                                    <div class="text-[11px] text-[#64748b] flex items-center gap-1 mt-0.5">
                                        <i data-lucide="calendar" class="w-3 h-3 text-[#94a3b8]"></i>
                                        {{ $order->created_at ? $order->created_at->format('M d, Y · h:i A') : 'N/A' }}
                                    </div>
                                </td>

                                <!-- Customer Details -->
                                <td class="py-4 px-6">
                                    <div class="font-semibold text-[#0f172a]">{{ $order->customer_name }}</div>
                                    <div class="text-[11px] text-[#64748b] font-mono">{{ $order->customer_phone }}</div>
                                    @if($order->customer_email)
                                        <div class="text-[11px] text-[#94a3b8] truncate max-w-[180px]" title="{{ $order->customer_email }}">{{ $order->customer_email }}</div>
                                    @endif
                                    <div class="text-[11px] text-[#64748b] mt-1 flex items-center gap-1">
                                        <i data-lucide="map-pin" class="w-3 h-3 text-[#94a3b8]"></i>
                                        {{ $order->city }}
                                    </div>
                                </td>

                                <!-- Items List -->
                                <td class="py-4 px-6">
                                    <div class="space-y-1 max-w-xs">
                                        @if($order->items && $order->items->isNotEmpty())
                                            @foreach($order->items as $item)
                                                <div class="text-[12px] flex items-center justify-between gap-2">
                                                    <span class="text-[#334155] truncate font-medium">
                                                        <strong class="text-[#0284c7] font-mono">{{ $item->quantity }}x</strong> {{ $item->product_name }}
                                                    </span>
                                                    @if($item->size)
                                                        <span class="text-[10px] uppercase font-mono px-1.5 py-0.5 rounded bg-[#f1f5f9] text-[#64748b] border border-[#e2e8f0] shrink-0">
                                                            {{ $item->size }}
                                                        </span>
                                                    @endif
                                                </div>
                                            @endforeach
                                        @else
                                            <span class="text-[12px] text-[#94a3b8] italic">Standard perfume order</span>
                                        @endif
                                    </div>
                                </td>

                                <!-- Total Amount -->
                                <td class="py-4 px-6">
                                    <div class="font-mono font-bold text-[#0f172a] text-[15px]">
                                        ৳{{ number_format($order->total_amount, 2) }}
                                    </div>
                                    <span class="text-[10px] text-emerald-700 font-mono uppercase bg-emerald-50 px-1.5 py-0.5 rounded border border-emerald-200">EXPRESS SHIPPING</span>
                                </td>

                                <!-- Payment Status & Method -->
                                <td class="py-4 px-6">
                                    <div class="space-y-1">
                                        <span class="inline-block text-[10px] uppercase font-bold tracking-wider px-2 py-0.5 rounded border {{ 
                                            $order->payment_status === 'paid' ? 'bg-emerald-50 text-emerald-700 border-emerald-200' :
                                            ($order->payment_status === 'refunded' ? 'bg-rose-50 text-rose-700 border-rose-200' : 'bg-amber-50 text-amber-700 border-amber-200')
                                        }}">
                                            {{ strtoupper($order->payment_status ?? 'UNPAID') }}
                                        </span>
                                        <div class="text-[11px] text-[#64748b] uppercase font-mono">
                                            {{ $order->payment_method === 'cod' ? 'Cash on Delivery' : ($order->payment_method === 'bkash' ? 'bKash' : 'Card') }}
                                        </div>
                                    </div>
                                </td>

                                <!-- Fulfillment Status (Inline Updater) -->
                                <td class="py-4 px-6">
                                    <form action="{{ url('/admin/orders/' . $order->id . '/status') }}" method="POST" class="flex items-center gap-2">
                                        @csrf
                                        <select 
                                            name="status" 
                                            onchange="this.form.submit()"
                                            class="border border-[#cbd5e1] text-[12px] font-bold rounded-xl px-3 py-1.5 focus:outline-none focus:border-[#0284c7] transition-all cursor-pointer bg-white shadow-2xs {{
                                                $order->status === 'completed' ? 'text-emerald-700 border-emerald-300' :
                                                ($order->status === 'processing' ? 'text-blue-700 border-blue-300' :
                                                ($order->status === 'cancelled' ? 'text-rose-700 border-rose-300' : 'text-amber-700 border-amber-300'))
                                            }}"
                                        >
                                            <option value="pending" {{ $order->status === 'pending' ? 'selected' : '' }}>⏳ Pending</option>
                                            <option value="processing" {{ $order->status === 'processing' ? 'selected' : '' }}>📦 Processing</option>
                                            <option value="completed" {{ $order->status === 'completed' ? 'selected' : '' }}>✅ Completed</option>
                                            <option value="cancelled" {{ $order->status === 'cancelled' ? 'selected' : '' }}>❌ Cancelled</option>
                                        </select>
                                    </form>
                                </td>

                                <!-- Actions -->
                                <td class="py-4 px-6 text-right">
                                    <div class="flex items-center justify-end gap-2">
                                        <!-- WhatsApp Chat Link -->
                                        @php
                                            $cleanPhone = preg_replace('/[^0-9]/', '', $order->customer_phone);
                                        @endphp
                                        <a
                                            href="https://wa.me/{{ $cleanPhone }}?text=Hello%20{{ urlencode($order->customer_name) }},%20this%20is%20{{ urlencode($siteSettings['siteName'] ?? 'RaaxO BD') }}%20regarding%20your%20perfume%20order%20%23{{ $order->order_number }}."
                                            target="_blank"
                                            title="WhatsApp Client"
                                            class="p-2 bg-emerald-50 hover:bg-emerald-600 text-emerald-600 hover:text-white border border-emerald-200 rounded-xl transition-all shadow-2xs"
                                        >
                                            <i data-lucide="message-circle" class="w-4 h-4"></i>
                                        </a>

                                        <!-- Delete Order -->
                                        <form action="{{ url('/admin/orders/' . $order->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to permanently delete order #{{ $order->order_number }}?');" class="inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" title="Delete Record" class="p-2 bg-rose-50 hover:bg-rose-600 text-rose-600 hover:text-white border border-rose-200 rounded-xl transition-all cursor-pointer shadow-2xs">
                                                <i data-lucide="trash-2" class="w-4 h-4"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="py-12 text-center text-[#64748b]">
                                    <div class="w-12 h-12 rounded-full bg-[#f0f9ff] border border-[#bae6fd] flex items-center justify-center mx-auto mb-3 text-[#0284c7]">
                                        <i data-lucide="inbox" class="w-6 h-6"></i>
                                    </div>
                                    <p class="text-[14px] font-semibold text-[#0f172a]">No orders found matching your criteria</p>
                                    <p class="text-[12px] text-[#64748b] mt-1">Orders placed through the store checkout will appear here instantly.</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination Bar -->
            @if($orders->hasPages())
                <div class="p-4 border-t border-[#e2e8f0] bg-[#f8fafc] flex justify-between items-center">
                    {{ $orders->links() }}
                </div>
            @endif
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
            // Also asynchronously persist to backend settings if desired
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
