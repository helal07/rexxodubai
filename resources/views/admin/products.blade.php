<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $siteSettings['siteName'] ?? 'REXXO BD' }} — Product Catalog Inventory</title>
    
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
    <div class="fixed top-0 right-1/3 w-[600px] h-[400px] bg-[radial-gradient(ellipse_at_center,rgba(184,113,46,0.12),transparent_70%)] pointer-events-none z-0"></div>

    <div class="max-w-7xl mx-auto space-y-8 relative z-10 animate-fade-in">
        <!-- Top Navigation Header -->
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 bg-[#0D121F]/90 backdrop-blur-xl border border-[#1E283D] p-6 rounded-2xl shadow-xl">
            <div class="flex items-center gap-4">
                @if(!empty($siteSettings['logo_url']) || !empty($siteSettings['site_logo']))
                    <img src="{{ $siteSettings['logo_url'] ?? $siteSettings['site_logo'] }}" alt="Logo" class="h-10 w-auto max-w-[140px] object-contain rounded-lg shadow-sm" />
                @else
                    <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-[#B8712E] to-[#8d4f18] text-white flex items-center justify-center shadow-lg">
                        <i data-lucide="package" class="w-5 h-5"></i>
                    </div>
                @endif
                <div>
                    <span class="text-[10px] text-[#B8712E] uppercase font-bold tracking-[0.2em] font-mono block">
                        {{ $siteSettings['siteName'] ?? 'REXXO BD' }} INVENTORY ENGINE
                    </span>
                    <h1 class="text-[22px] font-serif font-bold text-white uppercase tracking-tight">
                        Product Catalog Inventory
                    </h1>
                </div>
            </div>
            <div class="flex items-center gap-3">
                <a href="{{ url('/admin') }}" class="inline-flex items-center gap-2 bg-[#131A2B] hover:bg-[#1c263d] border border-[#1E283D] text-white px-4 py-2.5 rounded-xl text-[12px] font-bold uppercase tracking-wider transition-all shadow-sm">
                    <i data-lucide="arrow-left" class="w-4 h-4 text-slate-400"></i> Dashboard
                </a>
                <a href="{{ url('/perfumes') }}" target="_blank" class="inline-flex items-center gap-2 bg-emerald-600 hover:bg-emerald-500 text-white px-4 py-2.5 rounded-xl text-[12px] font-bold uppercase tracking-wider transition-all shadow-md">
                    <i data-lucide="external-link" class="w-4 h-4"></i> Catalog Live
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
            <!-- Add New Product Form -->
            <div class="lg:col-span-4 bg-[#0D121F]/90 backdrop-blur-xl border border-[#1E283D] p-7 rounded-2xl space-y-5 shadow-xl">
                <div class="flex items-center gap-3 border-b border-[#1E283D] pb-4">
                    <div class="p-2 bg-[#B8712E]/10 border border-[#B8712E]/30 text-[#B8712E] rounded-lg">
                        <i data-lucide="sparkles" class="w-5 h-5"></i>
                    </div>
                    <h2 class="text-[16px] font-serif font-bold text-white uppercase tracking-wide">
                        Add New Perfume Bottle
                    </h2>
                </div>

                <form action="{{ url('/admin/products') }}" method="POST" enctype="multipart/form-data" class="space-y-4">
                    @csrf
                    <div>
                        <label class="text-[10px] uppercase font-bold text-slate-400 tracking-wider block mb-1.5 font-mono">PERFUME NAME *</label>
                        <input type="text" name="name" required placeholder="e.g. Amber Nuit Extrait" class="w-full bg-[#131A2B] border border-[#1E283D] text-white px-4 py-2.5 rounded-xl text-[13px] focus:outline-none focus:border-[#B8712E] focus:ring-1 focus:ring-[#B8712E] transition-all">
                    </div>

                    <div class="grid grid-cols-2 gap-3">
                        <div>
                            <label class="text-[10px] uppercase font-bold text-slate-400 tracking-wider block mb-1.5 font-mono">CATEGORY</label>
                            <select name="category_id" class="w-full bg-[#131A2B] border border-[#1E283D] text-white px-3 py-2.5 rounded-xl text-[12px] focus:outline-none focus:border-[#B8712E]">
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
                            <label class="text-[10px] uppercase font-bold text-slate-400 tracking-wider block mb-1.5 font-mono">GENDER TARGET</label>
                            <select name="gender" class="w-full bg-[#131A2B] border border-[#1E283D] text-white px-3 py-2.5 rounded-xl text-[12px] focus:outline-none focus:border-[#B8712E]">
                                <option value="unisex">Unisex</option>
                                <option value="women">Women</option>
                                <option value="men">Men</option>
                            </select>
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-3">
                        <div>
                            <label class="text-[10px] uppercase font-bold text-slate-400 tracking-wider block mb-1.5 font-mono">PRICE (৳ / $)</label>
                            <input type="number" step="0.01" name="price" value="3200.00" required class="w-full bg-[#131A2B] border border-[#1E283D] text-white px-3 py-2.5 rounded-xl text-[13px] font-mono focus:outline-none focus:border-[#B8712E] transition-all">
                        </div>
                        <div>
                            <label class="text-[10px] uppercase font-bold text-slate-400 tracking-wider block mb-1.5 font-mono">STOCK UNITS</label>
                            <input type="number" name="stock" value="50" required class="w-full bg-[#131A2B] border border-[#1E283D] text-white px-3 py-2.5 rounded-xl text-[13px] font-mono focus:outline-none focus:border-[#B8712E] transition-all">
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-3">
                        <div>
                            <label class="text-[10px] uppercase font-bold text-slate-400 tracking-wider block mb-1.5 font-mono">SCENT FAMILY</label>
                            <input type="text" name="scent_family" placeholder="Floral Amber" class="w-full bg-[#131A2B] border border-[#1E283D] text-white px-3 py-2.5 rounded-xl text-[12px] focus:outline-none focus:border-[#B8712E] transition-all">
                        </div>
                        <div>
                            <label class="text-[10px] uppercase font-bold text-slate-400 tracking-wider block mb-1.5 font-mono">CONCENTRATION</label>
                            <input type="text" name="concentration" value="Eau de Parfum" class="w-full bg-[#131A2B] border border-[#1E283D] text-white px-3 py-2.5 rounded-xl text-[12px] focus:outline-none focus:border-[#B8712E] transition-all">
                        </div>
                    </div>

                    <div>
                        <label class="text-[10px] uppercase font-bold text-slate-400 tracking-wider block mb-1.5 font-mono">UPLOAD BOTTLE IMAGE</label>
                        <input type="file" name="primary_image_file" accept="image/*" class="w-full bg-[#131A2B] border border-[#1E283D] text-slate-300 px-3 py-2 rounded-xl text-[12px] file:mr-3 file:py-1 file:px-2.5 file:rounded-lg file:border-0 file:text-[10px] file:font-bold file:bg-[#B8712E] file:text-white cursor-pointer">
                    </div>

                    <div>
                        <label class="text-[10px] uppercase font-bold text-slate-400 tracking-wider block mb-1.5 font-mono">OR IMAGE URL</label>
                        <input type="text" name="primary_image_url" placeholder="https://images.unsplash.com/..." class="w-full bg-[#131A2B] border border-[#1E283D] text-white px-3 py-2 rounded-xl text-[12px] font-mono focus:outline-none focus:border-[#B8712E]">
                    </div>

                    <div>
                        <label class="text-[10px] uppercase font-bold text-slate-400 tracking-wider block mb-1.5 font-mono">SHORT DESCRIPTION</label>
                        <textarea name="short_description" rows="2" placeholder="Brief fragrance story..." class="w-full bg-[#131A2B] border border-[#1E283D] text-white px-3 py-2 rounded-xl text-[12px] focus:outline-none focus:border-[#B8712E]"></textarea>
                    </div>

                    <button type="submit" class="w-full bg-gradient-to-r from-[#B8712E] to-[#9a5b20] hover:from-[#a66324] hover:to-[#844c18] text-white py-3.5 rounded-xl text-[12px] font-bold uppercase tracking-[0.14em] cursor-pointer transition-all shadow-lg shadow-[#B8712E]/20 flex items-center justify-center gap-2 mt-2">
                        <i data-lucide="plus" class="w-4 h-4"></i> Create Product in MySQL
                    </button>
                </form>
            </div>

            <!-- Existing Products Inventory Table -->
            <div class="lg:col-span-8 bg-[#0D121F]/90 backdrop-blur-xl border border-[#1E283D] p-7 rounded-2xl space-y-5 shadow-xl">
                <div class="flex items-center justify-between border-b border-[#1E283D] pb-4">
                    <div class="flex items-center gap-3">
                        <div class="p-2 bg-slate-800 border border-slate-700 text-slate-300 rounded-lg">
                            <i data-lucide="boxes" class="w-5 h-5"></i>
                        </div>
                        <h2 class="text-[16px] font-serif font-bold text-white uppercase tracking-wide">
                            Master Inventory Catalog
                        </h2>
                    </div>
                    <span class="text-[11px] font-mono text-slate-400 bg-[#131A2B] px-3 py-1 rounded-full border border-[#1E283D]">
                        {{ count($products) }} PRODUCTS
                    </span>
                </div>

                <div class="overflow-x-auto rounded-xl border border-[#1E283D]">
                    <table class="w-full text-left text-[12px]">
                        <thead class="bg-[#131A2B] text-slate-400 text-[10px] uppercase font-bold tracking-wider font-mono border-b border-[#1E283D]">
                            <tr>
                                <th class="p-3.5">BOTTLE</th>
                                <th class="p-3.5">PRODUCT NAME</th>
                                <th class="p-3.5">CATEGORY</th>
                                <th class="p-3.5">PRICE</th>
                                <th class="p-3.5">STOCK</th>
                                <th class="p-3.5 text-right">ACTIONS</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-[#1E283D] bg-[#0D121F]">
                            @forelse ($products as $p)
                                <tr class="hover:bg-[#131A2B]/60 transition-colors">
                                    <td class="p-3.5 w-14">
                                        @if(!empty($p->primary_image_url))
                                            <img src="{{ $p->primary_image_url }}" alt="" class="w-10 h-10 rounded-lg object-contain bg-slate-800 border border-slate-700 p-0.5">
                                        @else
                                            <div class="w-10 h-10 rounded-lg bg-slate-800 border border-slate-700 flex items-center justify-center text-slate-500">
                                                <i data-lucide="image" class="w-4 h-4"></i>
                                            </div>
                                        @endif
                                    </td>
                                    <td class="p-3.5">
                                        <div class="font-bold text-white">{{ $p->name }}</div>
                                        <div class="text-[11px] text-slate-400">{{ $p->scent_family ?? $p->concentration ?? 'Fragrance' }}</div>
                                    </td>
                                    <td class="p-3.5">
                                        <div class="text-slate-300 font-medium">{{ $p->category->name ?? 'Fragrance' }}</div>
                                        <span class="inline-block mt-0.5 px-2 py-0.2 rounded text-[9px] font-bold uppercase tracking-wider {{ $p->gender === 'women' ? 'text-pink-400' : ($p->gender === 'men' ? 'text-sky-400' : 'text-emerald-400') }}">
                                            {{ $p->gender }}
                                        </span>
                                    </td>
                                    <td class="p-3.5 text-[#B8712E] font-bold font-mono text-[13px]">
                                        ৳{{ number_format((float)$p->price, 0) }}
                                    </td>
                                    <td class="p-3.5 font-mono text-slate-300 font-medium">
                                        {{ $p->stock ?? 50 }}
                                    </td>
                                    <td class="p-3.5 text-right space-x-1 whitespace-nowrap">
                                        <a href="{{ url('/admin/products/' . $p->id . '/edit') }}" class="inline-flex items-center gap-1 px-3 py-1.5 bg-[#B8712E]/10 hover:bg-[#B8712E] border border-[#B8712E]/40 hover:border-[#B8712E] text-[#B8712E] hover:text-white rounded-lg text-[11px] font-bold uppercase tracking-wider transition-all shadow-xs">
                                            <i data-lucide="edit-3" class="w-3.5 h-3.5"></i> Edit
                                        </a>
                                        <a href="{{ url('/product/' . $p->slug) }}" target="_blank" class="inline-flex items-center p-1.5 bg-slate-800 hover:bg-slate-700 border border-slate-700 text-slate-300 hover:text-white rounded-lg transition-all" title="View on storefront">
                                            <i data-lucide="external-link" class="w-3.5 h-3.5"></i>
                                        </a>
                                        <form action="{{ url('/admin/products/' . $p->id) }}" method="POST" class="inline-block" onsubmit="return confirm('Delete {{ addslashes($p->name) }}?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="p-1.5 bg-rose-500/10 hover:bg-rose-600 border border-rose-500/30 hover:border-rose-500 text-rose-400 hover:text-white rounded-lg transition-all cursor-pointer" title="Delete product">
                                                <i data-lucide="trash-2" class="w-3.5 h-3.5"></i>
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="p-8 text-center text-slate-500 font-mono">
                                        No products in database yet.
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
    </div>

    <script>
        lucide.createIcons();
    </script>
</body>
</html>
