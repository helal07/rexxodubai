<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $siteSettings['siteName'] ?? 'REXXO BD' }} — Orders & Fulfillment Console</title>
    
    <!-- Dynamic Favicon -->
    @php
        $adminFavicon = !empty($siteSettings['favicon_url']) ? $siteSettings['favicon_url'] : (!empty($siteSettings['site_favicon']) ? $siteSettings['site_favicon'] : '/uploads/settings/favicon_1785930191.ico');
    @endphp
    <link rel="icon" id="admin-favicon" href="{{ $adminFavicon }}">

    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://unpkg.com/lucide@latest"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=Fraunces:wght@600;700&family=JetBrains+Mono:wght@400;500;600&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Inter', sans-serif; }
        .font-serif { font-family: 'Fraunces', Georgia, serif; }
        .font-mono { font-family: 'JetBrains Mono', monospace; }
        
        ::-webkit-scrollbar { width: 6px; height: 6px; }
        ::-webkit-scrollbar-track { background: #0c0f17; }
        ::-webkit-scrollbar-thumb { background: #1e293b; border-radius: 4px; }
        ::-webkit-scrollbar-thumb:hover { background: #B8712E; }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(6px); }
            to { opacity: 1; transform: translateY(0); }
        }
        .animate-fade-in { animation: fadeIn 0.35s cubic-bezier(0.16, 1, 0.3, 1) forwards; }
    </style>
</head>
<body class="bg-[#07090E] text-slate-100 min-h-screen p-6 md:p-10 relative selection:bg-[#B8712E] selection:text-white">
    <!-- Ambient Amber Glow Background -->
    <div class="fixed top-0 right-1/4 w-[700px] h-[450px] bg-[radial-gradient(ellipse_at_center,rgba(184,113,46,0.12),transparent_70%)] pointer-events-none z-0"></div>

    <div class="max-w-7xl mx-auto space-y-8 relative z-10 animate-fade-in">
        <!-- Top Navigation Header -->
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 bg-[#0D121F]/90 backdrop-blur-xl border border-[#1E283D] p-6 rounded-2xl shadow-xl">
            <div class="flex items-center gap-4">
                @if(!empty($siteSettings['logo_url']) || !empty($siteSettings['site_logo']))
                    <img src="{{ $siteSettings['logo_url'] ?? $siteSettings['site_logo'] }}" alt="Logo" class="h-10 w-auto max-w-[140px] object-contain rounded-lg shadow-sm" />
                @else
                    <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-[#B8712E] to-[#8d4f18] text-white flex items-center justify-center shadow-lg">
                        <i data-lucide="shopping-bag" class="w-5 h-5"></i>
                    </div>
                @endif
                <div>
                    <span class="text-[10px] text-[#B8712E] uppercase font-bold tracking-[0.2em] font-mono block">
                        {{ $siteSettings['siteName'] ?? 'REXXO BD' }} COMMERCE DISPATCH
                    </span>
                    <h1 class="text-[22px] font-serif font-bold text-white uppercase tracking-tight">
                        Client Orders & Fulfillment
                    </h1>
                </div>
            </div>
            <div class="flex items-center gap-3">
                <a href="{{ url('/admin/dashboard') }}" class="inline-flex items-center gap-2 bg-[#131A2B] hover:bg-[#1c263d] border border-[#1E283D] text-white px-4 py-2.5 rounded-xl text-[12px] font-bold uppercase tracking-wider transition-all shadow-sm">
                    <i data-lucide="arrow-left" class="w-4 h-4 text-slate-400"></i> Dashboard
                </a>
                <a href="{{ url('/admin/courier') }}" class="inline-flex items-center gap-2 bg-[#131A2B] hover:bg-[#1c263d] border border-[#1E283D] text-white px-4 py-2.5 rounded-xl text-[12px] font-bold uppercase tracking-wider transition-all shadow-sm">
                    <i data-lucide="truck" class="w-4 h-4 text-[#B8712E]"></i> Courier Hub
                </a>
                <a href="{{ url('/admin/products') }}" class="inline-flex items-center gap-2 bg-[#131A2B] hover:bg-[#1c263d] border border-[#1E283D] text-white px-4 py-2.5 rounded-xl text-[12px] font-bold uppercase tracking-wider transition-all shadow-sm">
                    <i data-lucide="package" class="w-4 h-4 text-[#B8712E]"></i> Products
                </a>
                <a href="{{ url('/perfumes') }}" target="_blank" class="inline-flex items-center gap-2 bg-emerald-600 hover:bg-emerald-500 text-white px-4 py-2.5 rounded-xl text-[12px] font-bold uppercase tracking-wider transition-all shadow-md">
                    <i data-lucide="external-link" class="w-4 h-4"></i> Storefront
                </a>
            </div>
        </div>

        <!-- Feedback Alert -->
        @if (session('success'))
            <div class="p-4 bg-emerald-500/10 border border-emerald-500/30 text-emerald-400 text-[13px] rounded-xl flex items-center gap-2.5 font-medium animate-fade-in">
                <i data-lucide="check-circle-2" class="w-4 h-4 text-emerald-400 shrink-0"></i>
                <span>{{ session('success') }}</span>
            </div>
        @endif

        <!-- Metrics Overview Grid -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5">
            <!-- Total Orders -->
            <div class="bg-[#0D121F]/90 backdrop-blur-xl border border-[#1E283D] p-5 rounded-2xl space-y-2">
                <div class="flex justify-between items-center text-slate-400">
                    <span class="text-[11px] uppercase font-bold tracking-wider font-mono">TOTAL ORDERS</span>
                    <i data-lucide="shopping-cart" class="w-4 h-4 text-[#B8712E]"></i>
                </div>
                <div class="text-3xl font-bold font-mono text-white">{{ $totalOrdersCount }}</div>
                <span class="text-[11px] text-slate-400">All registered purchases</span>
            </div>

            <!-- Total Revenue -->
            <div class="bg-[#0D121F]/90 backdrop-blur-xl border border-[#1E283D] p-5 rounded-2xl space-y-2">
                <div class="flex justify-between items-center text-slate-400">
                    <span class="text-[11px] uppercase font-bold tracking-wider font-mono">GROSS REVENUE</span>
                    <i data-lucide="dollar-sign" class="w-4 h-4 text-emerald-400"></i>
                </div>
                <div class="text-3xl font-bold font-mono text-emerald-400">${{ number_format($totalRevenue, 2) }} <span class="text-xs text-slate-400">USD</span></div>
                <span class="text-[11px] text-slate-400">Excluding cancelled orders</span>
            </div>

            <!-- Pending Dispatch -->
            <div class="bg-[#0D121F]/90 backdrop-blur-xl border border-[#1E283D] p-5 rounded-2xl space-y-2">
                <div class="flex justify-between items-center text-slate-400">
                    <span class="text-[11px] uppercase font-bold tracking-wider font-mono">PENDING DISPATCH</span>
                    <i data-lucide="clock" class="w-4 h-4 text-amber-400"></i>
                </div>
                <div class="text-3xl font-bold font-mono text-amber-400">{{ $pendingOrdersCount }}</div>
                <span class="text-[11px] text-slate-400">Awaiting concierge packing</span>
            </div>

            <!-- Completed -->
            <div class="bg-[#0D121F]/90 backdrop-blur-xl border border-[#1E283D] p-5 rounded-2xl space-y-2">
                <div class="flex justify-between items-center text-slate-400">
                    <span class="text-[11px] uppercase font-bold tracking-wider font-mono">FULFILLED</span>
                    <i data-lucide="check-check" class="w-4 h-4 text-cyan-400"></i>
                </div>
                <div class="text-3xl font-bold font-mono text-cyan-400">{{ $completedOrdersCount }}</div>
                <span class="text-[11px] text-slate-400">Delivered to clients</span>
            </div>
        </div>

        <!-- Filter & Search Controls -->
        <div class="bg-[#0D121F]/90 backdrop-blur-xl border border-[#1E283D] p-5 rounded-2xl shadow-xl">
            <form action="{{ url('/admin/orders') }}" method="GET" class="flex flex-col md:flex-row gap-4 justify-between items-stretch md:items-center">
                <!-- Search Input -->
                <div class="relative flex-1 max-w-md">
                    <i data-lucide="search" class="w-4 h-4 absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-400"></i>
                    <input
                        type="text"
                        name="search"
                        value="{{ request('search') }}"
                        placeholder="Search Order #, Client name, phone, city..."
                        class="w-full bg-[#131A2B] border border-[#1E283D] text-white pl-10 pr-4 py-2.5 rounded-xl text-[13px] focus:outline-none focus:border-[#B8712E] focus:ring-1 focus:ring-[#B8712E] transition-all placeholder-slate-500"
                    />
                </div>

                <!-- Status Filter Pills -->
                <div class="flex flex-wrap gap-2 items-center">
                    <span class="text-[11px] uppercase font-bold text-slate-400 font-mono mr-1">STATUS:</span>
                    
                    <a href="{{ request()->fullUrlWithQuery(['status' => 'all']) }}"
                       class="px-3 py-1.5 rounded-lg text-[11px] font-bold uppercase tracking-wider transition-all {{ (!request('status') || request('status') === 'all') ? 'bg-[#B8712E] text-white' : 'bg-[#131A2B] text-slate-300 hover:bg-[#1E283D]' }}">
                        All
                    </a>

                    <a href="{{ request()->fullUrlWithQuery(['status' => 'pending']) }}"
                       class="px-3 py-1.5 rounded-lg text-[11px] font-bold uppercase tracking-wider transition-all {{ request('status') === 'pending' ? 'bg-amber-500 text-black' : 'bg-[#131A2B] text-amber-300 hover:bg-[#1E283D]' }}">
                        Pending
                    </a>

                    <a href="{{ request()->fullUrlWithQuery(['status' => 'processing']) }}"
                       class="px-3 py-1.5 rounded-lg text-[11px] font-bold uppercase tracking-wider transition-all {{ request('status') === 'processing' ? 'bg-blue-500 text-white' : 'bg-[#131A2B] text-blue-300 hover:bg-[#1E283D]' }}">
                        Processing
                    </a>

                    <a href="{{ request()->fullUrlWithQuery(['status' => 'completed']) }}"
                       class="px-3 py-1.5 rounded-lg text-[11px] font-bold uppercase tracking-wider transition-all {{ request('status') === 'completed' ? 'bg-emerald-500 text-white' : 'bg-[#131A2B] text-emerald-300 hover:bg-[#1E283D]' }}">
                        Completed
                    </a>

                    <a href="{{ request()->fullUrlWithQuery(['status' => 'cancelled']) }}"
                       class="px-3 py-1.5 rounded-lg text-[11px] font-bold uppercase tracking-wider transition-all {{ request('status') === 'cancelled' ? 'bg-rose-500 text-white' : 'bg-[#131A2B] text-rose-300 hover:bg-[#1E283D]' }}">
                        Cancelled
                    </a>
                </div>

                <div class="flex gap-2">
                    <button type="submit" class="bg-[#131A2B] hover:bg-[#1c263d] border border-[#1E283D] text-white px-4 py-2.5 rounded-xl text-[12px] font-bold uppercase tracking-wider transition-all">
                        Apply Filter
                    </button>
                    @if(request('search') || request('status') || request('payment_status'))
                        <a href="{{ url('/admin/orders') }}" class="bg-slate-800 hover:bg-slate-700 text-slate-300 px-3 py-2.5 rounded-xl text-[12px] transition-all flex items-center justify-center">
                            <i data-lucide="x" class="w-4 h-4"></i>
                        </a>
                    @endif
                </div>
            </form>
        </div>

        <!-- Orders Table Card -->
        <div class="bg-[#0D121F]/90 backdrop-blur-xl border border-[#1E283D] rounded-2xl shadow-xl overflow-hidden">
            <div class="p-6 border-b border-[#1E283D] flex justify-between items-center">
                <h2 class="text-[16px] font-serif font-bold text-white uppercase tracking-wide flex items-center gap-2">
                    <i data-lucide="list-ordered" class="w-5 h-5 text-[#B8712E]"></i>
                    Order Manifest
                </h2>
                <span class="text-[11px] font-mono text-slate-400">Showing {{ $orders->count() }} of {{ $orders->total() }} Records</span>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left text-[13px]">
                    <thead class="bg-[#090D17] text-slate-400 uppercase text-[10px] font-mono tracking-wider border-b border-[#1E283D]">
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
                    <tbody class="divide-y divide-[#1E283D]">
                        @forelse($orders as $order)
                            <tr class="hover:bg-[#131A2B]/40 transition-colors">
                                <!-- Order Reference -->
                                <td class="py-4 px-6">
                                    <div class="font-mono font-bold text-[#B8712E] tracking-wider">{{ $order->order_number }}</div>
                                    <div class="text-[11px] text-slate-400 flex items-center gap-1 mt-0.5">
                                        <i data-lucide="calendar" class="w-3 h-3"></i>
                                        {{ $order->created_at->format('M d, Y · h:i A') }}
                                    </div>
                                </td>

                                <!-- Customer Details -->
                                <td class="py-4 px-6">
                                    <div class="font-semibold text-white">{{ $order->customer_name }}</div>
                                    <div class="text-[11px] text-slate-400 font-mono">{{ $order->customer_phone }}</div>
                                    <div class="text-[11px] text-slate-500 truncate max-w-[180px]" title="{{ $order->customer_email }}">{{ $order->customer_email }}</div>
                                    <div class="text-[11px] text-slate-400 mt-1 flex items-center gap-1">
                                        <i data-lucide="map-pin" class="w-3 h-3 text-slate-500"></i>
                                        {{ $order->city }}
                                    </div>
                                </td>

                                <!-- Items List -->
                                <td class="py-4 px-6">
                                    <div class="space-y-1 max-w-xs">
                                        @foreach($order->items as $item)
                                            <div class="text-[12px] flex items-center justify-between gap-2">
                                                <span class="text-slate-200 truncate">
                                                    <strong class="text-amber-400 font-mono">{{ $item->quantity }}x</strong> {{ $item->product_name }}
                                                </span>
                                                <span class="text-[10px] uppercase font-mono px-1.5 py-0.5 rounded bg-[#131A2B] text-slate-400 border border-[#1E283D] shrink-0">
                                                    {{ $item->size }}
                                                </span>
                                            </div>
                                        @endforeach
                                    </div>
                                </td>

                                <!-- Total Amount -->
                                <td class="py-4 px-6">
                                    <div class="font-mono font-bold text-white text-[14px]">
                                        ${{ number_format($order->total_amount, 2) }}
                                    </div>
                                    <span class="text-[10px] text-emerald-400 font-mono uppercase">COMPLIMENTARY SHIP</span>
                                </td>

                                <!-- Payment Status & Method -->
                                <td class="py-4 px-6">
                                    <div class="space-y-1">
                                        <span class="inline-block text-[10px] uppercase font-bold tracking-wider px-2 py-0.5 rounded border {{ 
                                            $order->payment_status === 'paid' ? 'bg-emerald-500/10 text-emerald-400 border-emerald-500/30' :
                                            ($order->payment_status === 'refunded' ? 'bg-rose-500/10 text-rose-400 border-rose-500/30' : 'bg-amber-500/10 text-amber-400 border-amber-500/30')
                                        }}">
                                            {{ strtoupper($order->payment_status ?? 'UNPAID') }}
                                        </span>
                                        <div class="text-[11px] text-slate-400 uppercase font-mono">
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
                                            class="bg-[#131A2B] border border-[#1E283D] text-white text-[12px] font-semibold rounded-lg px-2.5 py-1.5 focus:outline-none focus:border-[#B8712E] transition-all cursor-pointer {{
                                                $order->status === 'completed' ? 'text-emerald-400' :
                                                ($order->status === 'processing' ? 'text-blue-400' :
                                                ($order->status === 'cancelled' ? 'text-rose-400' : 'text-amber-400'))
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
                                            href="https://wa.me/{{ $cleanPhone }}?text=Hello%20{{ urlencode($order->customer_name) }},%20this%20is%20ReXxo%20Bd%20regarding%20your%20perfume%20order%20%23{{ $order->order_number }}."
                                            target="_blank"
                                            title="WhatsApp Client"
                                            class="p-2 bg-emerald-600/10 hover:bg-emerald-600/20 text-emerald-400 border border-emerald-500/20 rounded-lg transition-all"
                                        >
                                            <i data-lucide="message-circle" class="w-4 h-4"></i>
                                        </a>

                                        <!-- Delete Order -->
                                        <form action="{{ url('/admin/orders/' . $order->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to permanently delete order #{{ $order->order_number }}?');" class="inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" title="Delete Record" class="p-2 bg-rose-600/10 hover:bg-rose-600/20 text-rose-400 border border-rose-500/20 rounded-lg transition-all">
                                                <i data-lucide="trash-2" class="w-4 h-4"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="py-12 text-center text-slate-400">
                                    <div class="w-12 h-12 rounded-full bg-[#131A2B] border border-[#1E283D] flex items-center justify-center mx-auto mb-3 text-slate-500">
                                        <i data-lucide="inbox" class="w-6 h-6"></i>
                                    </div>
                                    <p class="text-[14px] font-medium text-slate-300">No orders found matching your criteria</p>
                                    <p class="text-[12px] text-slate-500 mt-1">Orders placed through the store checkout will appear here instantly.</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination Bar -->
            @if($orders->hasPages())
                <div class="p-4 border-t border-[#1E283D] bg-[#090D17] flex justify-between items-center">
                    {{ $orders->links() }}
                </div>
            @endif
        </div>
    </div>

    <script>
        lucide.createIcons();
    </script>
</body>
</html>
