<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $siteSettings['siteName'] ?? 'REXXO BD' }} — Navigation Menu Builder</title>
    
    <!-- Dynamic Favicon -->
    @php
        $adminFavicon = !empty($siteSettings['favicon_url']) ? $siteSettings['favicon_url'] : (!empty($siteSettings['site_favicon']) ? $siteSettings['site_favicon'] : '/uploads/settings/favicon_1785930191.ico');
    @endphp
    <link rel="icon" id="admin-favicon" href="{{ $adminFavicon }}">

    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://unpkg.com/lucide@latest"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=Fraunces:wght@600;700&family=JetBrains+Mono:wght@400;500&display=swap" rel="stylesheet">
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
    <!-- Ambient Glow Background -->
    <div class="fixed top-0 right-1/4 w-[600px] h-[400px] bg-[radial-gradient(ellipse_at_center,rgba(184,113,46,0.12),transparent_70%)] pointer-events-none z-0"></div>

    <div class="max-w-7xl mx-auto space-y-8 relative z-10 animate-fade-in">
        <!-- Top Navigation Header -->
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 bg-[#0D121F]/90 backdrop-blur-xl border border-[#1E283D] p-6 rounded-2xl shadow-xl">
            <div class="flex items-center gap-4">
                @if(!empty($siteSettings['logo_url']) || !empty($siteSettings['site_logo']))
                    <img src="{{ $siteSettings['logo_url'] ?? $siteSettings['site_logo'] }}" alt="Logo" class="h-10 w-auto max-w-[140px] object-contain rounded-lg shadow-sm" />
                @else
                    <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-[#B8712E] to-[#8d4f18] text-white flex items-center justify-center shadow-lg">
                        <i data-lucide="menu" class="w-5 h-5"></i>
                    </div>
                @endif
                <div>
                    <span class="text-[10px] text-[#B8712E] uppercase font-bold tracking-[0.2em] font-mono block">
                        {{ $siteSettings['siteName'] ?? 'REXXO BD' }} NAVIGATION ARCHITECTURE
                    </span>
                    <h1 class="text-[22px] font-serif font-bold text-white uppercase tracking-tight">
                        Navigation Menu Builder
                    </h1>
                </div>
            </div>
            <div class="flex items-center gap-3">
                <a href="{{ url('/admin') }}" class="inline-flex items-center gap-2 bg-[#131A2B] hover:bg-[#1c263d] border border-[#1E283D] text-white px-4 py-2.5 rounded-xl text-[12px] font-bold uppercase tracking-wider transition-all shadow-sm">
                    <i data-lucide="arrow-left" class="w-4 h-4 text-slate-400"></i> Dashboard
                </a>
                <a href="{{ url('/') }}" target="_blank" class="inline-flex items-center gap-2 bg-emerald-600 hover:bg-emerald-500 text-white px-4 py-2.5 rounded-xl text-[12px] font-bold uppercase tracking-wider transition-all shadow-md">
                    <i data-lucide="external-link" class="w-4 h-4"></i> Preview Live
                </a>
            </div>
        </div>

        @if (session('success'))
            <div class="p-4 bg-emerald-500/10 border border-emerald-500/30 text-emerald-400 text-[13px] rounded-xl flex items-center gap-2.5 font-medium animate-fade-in">
                <i data-lucide="check-circle-2" class="w-4 h-4 text-emerald-400 shrink-0"></i>
                <span>{{ session('success') }}</span>
            </div>
        @endif

        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
            <!-- Add New Menu Item Form -->
            <div class="lg:col-span-5 bg-[#0D121F]/90 backdrop-blur-xl border border-[#1E283D] p-7 rounded-2xl space-y-6 shadow-xl">
                <div class="flex items-center gap-3 border-b border-[#1E283D] pb-4">
                    <div class="p-2 bg-[#B8712E]/10 border border-[#B8712E]/30 text-[#B8712E] rounded-lg">
                        <i data-lucide="plus-circle" class="w-5 h-5"></i>
                    </div>
                    <h2 class="text-[16px] font-serif font-bold text-white uppercase tracking-wide">
                        Create Navigation Item
                    </h2>
                </div>

                <form action="{{ url('/admin/menus') }}" method="POST" class="space-y-4">
                    @csrf
                    <div>
                        <label class="text-[10px] uppercase font-bold text-slate-400 tracking-wider block mb-1.5 font-mono">
                            MENU LABEL NAME
                        </label>
                        <input type="text" name="label" required placeholder="e.g. Rare Extraits, Gifts, Discovery Set" class="w-full bg-[#131A2B] border border-[#1E283D] text-white px-4 py-3 rounded-xl text-[13px] focus:outline-none focus:border-[#B8712E] focus:ring-1 focus:ring-[#B8712E] transition-all">
                    </div>

                    <div>
                        <label class="text-[10px] uppercase font-bold text-slate-400 tracking-wider block mb-1.5 font-mono">
                            TARGET URL LINK
                        </label>
                        <input type="text" name="url" required placeholder="/perfumes?category=gifts" class="w-full bg-[#131A2B] border border-[#1E283D] text-white px-4 py-3 rounded-xl text-[13px] focus:outline-none focus:border-[#B8712E] focus:ring-1 focus:ring-[#B8712E] transition-all">
                    </div>

                    <div>
                        <label class="text-[10px] uppercase font-bold text-slate-400 tracking-wider block mb-1.5 font-mono">
                            SORT ORDER POSITION
                        </label>
                        <input type="number" name="sort_order" value="1" class="w-full bg-[#131A2B] border border-[#1E283D] text-white px-4 py-3 rounded-xl text-[13px] focus:outline-none focus:border-[#B8712E] focus:ring-1 focus:ring-[#B8712E] transition-all">
                    </div>

                    <button type="submit" class="w-full bg-gradient-to-r from-[#B8712E] to-[#9a5b20] hover:from-[#a66324] hover:to-[#844c18] text-white py-3.5 rounded-xl text-[12px] font-bold uppercase tracking-[0.14em] cursor-pointer transition-all shadow-lg shadow-[#B8712E]/20 flex items-center justify-center gap-2">
                        <i data-lucide="save" class="w-4 h-4"></i> Save Navigation Item
                    </button>
                </form>
            </div>

            <!-- Existing Menu Items List & Inline Editors -->
            <div class="lg:col-span-7 bg-[#0D121F]/90 backdrop-blur-xl border border-[#1E283D] p-7 rounded-2xl space-y-6 shadow-xl">
                <div class="flex items-center justify-between border-b border-[#1E283D] pb-4">
                    <div class="flex items-center gap-3">
                        <div class="p-2 bg-slate-800 border border-slate-700 text-slate-300 rounded-lg">
                            <i data-lucide="list-tree" class="w-5 h-5"></i>
                        </div>
                        <h2 class="text-[16px] font-serif font-bold text-white uppercase tracking-wide">
                            Active Menu Tree
                        </h2>
                    </div>
                    @php
                        $itemsList = $items ?? ($menuItems ?? collect([]));
                    @endphp
                    <span class="text-[11px] font-mono text-slate-400 bg-[#131A2B] px-3 py-1 rounded-full border border-[#1E283D]">
                        {{ count($itemsList) }} ACTIVE ITEMS
                    </span>
                </div>

                <div class="space-y-3">
                    @forelse ($itemsList as $item)
                        <div class="bg-[#131A2B] border border-[#1E283D] p-4 rounded-xl flex flex-col md:flex-row justify-between items-start md:items-center gap-3 hover:border-slate-600 transition-all">
                            <form action="{{ url('/admin/menus/' . $item->id) }}" method="POST" class="flex-1 flex flex-col sm:flex-row items-center gap-2.5 w-full">
                                @csrf
                                @method('PUT')
                                <input type="text" name="label" value="{{ $item->label }}" class="bg-[#0D121F] border border-[#1E283D] text-white px-3.5 py-2 text-[12px] font-bold rounded-lg w-full sm:w-44 focus:outline-none focus:border-[#B8712E]">
                                <input type="text" name="url" value="{{ $item->url }}" class="bg-[#0D121F] border border-[#1E283D] text-slate-400 font-mono px-3.5 py-2 text-[11px] rounded-lg w-full sm:w-56 focus:outline-none focus:border-[#B8712E]">
                                <button type="submit" class="bg-emerald-600/20 hover:bg-emerald-600 border border-emerald-500/40 text-emerald-300 hover:text-white text-[11px] uppercase font-bold px-3.5 py-2 rounded-lg transition-all shrink-0">
                                    Update
                                </button>
                            </form>

                            <form action="{{ url('/admin/menus/' . $item->id) }}" method="POST" onsubmit="return confirm('Delete this menu item?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="bg-rose-500/10 hover:bg-rose-500 border border-rose-500/30 text-rose-400 hover:text-white text-[11px] uppercase font-bold px-3.5 py-2 rounded-lg transition-all shrink-0 cursor-pointer">
                                    Delete
                                </button>
                            </form>
                        </div>
                    @empty
                        <div class="text-center py-10 text-slate-500 text-[13px] border border-dashed border-[#1E283D] rounded-xl font-mono">
                            No custom menu items yet. Create one on the left.
                        </div>
                    @endforelse
                </div>
            </div>
        </div>

        <!-- Classic Minimal Admin Footer -->
        @include('admin.partials.footer')
    </div>

    <script>
        lucide.createIcons();
    </script>
</body>
</html>
