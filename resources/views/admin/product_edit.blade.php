<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit {{ $product->name }} — {{ $siteSettings['siteName'] ?? 'REXXO BD' }} Admin</title>
    
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
<body class="bg-[#07090E] text-slate-100 min-h-screen p-4 sm:p-6 md:p-10 relative selection:bg-[#B8712E] selection:text-white">
    <!-- Ambient Glow Background -->
    <div class="fixed top-0 right-1/4 w-[600px] h-[400px] bg-[radial-gradient(ellipse_at_center,rgba(184,113,46,0.12),transparent_70%)] pointer-events-none z-0"></div>

    <div class="max-w-6xl mx-auto space-y-8 relative z-10 animate-fade-in">
        <!-- Top Navigation Bar -->
        <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4 bg-[#0D121F]/90 backdrop-blur-xl border border-[#1E283D] p-6 rounded-2xl shadow-xl">
            <div class="flex items-center gap-4">
                <a href="{{ url('/admin/products') }}" class="p-2.5 bg-[#131A2B] hover:bg-[#1c263d] border border-[#1E283D] text-slate-300 hover:text-white rounded-xl transition-all shadow-sm">
                    <i data-lucide="arrow-left" class="w-5 h-5"></i>
                </a>
                <div>
                    <div class="flex items-center gap-2">
                        <span class="text-[10px] text-[#B8712E] uppercase font-bold tracking-[0.2em] font-mono">
                            PRODUCT MANAGEMENT
                        </span>
                        <span class="text-[11px] font-mono text-slate-400 bg-[#131A2B] px-2 py-0.5 rounded-md border border-[#1E283D]">
                            ID: #{{ $product->id }}
                        </span>
                    </div>
                    <h1 class="text-[20px] sm:text-[24px] font-serif font-bold text-white uppercase tracking-tight truncate max-w-xl">
                        Edit: {{ $product->name }}
                    </h1>
                </div>
            </div>

            <div class="flex items-center flex-wrap gap-3">
                <a href="{{ url('/product/' . $product->slug) }}" target="_blank" class="inline-flex items-center gap-2 bg-[#131A2B] hover:bg-[#1c263d] border border-[#1E283D] text-slate-200 px-4 py-2.5 rounded-xl text-[12px] font-bold uppercase tracking-wider transition-all shadow-sm">
                    <i data-lucide="external-link" class="w-4 h-4 text-slate-400"></i> View Live
                </a>
                <a href="{{ url('/admin/dashboard') }}" class="inline-flex items-center gap-2 bg-[#131A2B] hover:bg-[#1c263d] border border-[#1E283D] text-slate-200 px-4 py-2.5 rounded-xl text-[12px] font-bold uppercase tracking-wider transition-all shadow-sm">
                    <i data-lucide="layout-dashboard" class="w-4 h-4 text-slate-400"></i> Dashboard
                </a>
            </div>
        </div>

        <!-- Success & Error Banners -->
        @if (session('success'))
            <div class="p-4 bg-emerald-500/10 border border-emerald-500/30 text-emerald-400 text-[13px] rounded-xl flex items-center gap-2.5 font-medium animate-fade-in">
                <i data-lucide="check-circle-2" class="w-4 h-4 text-emerald-400 shrink-0"></i>
                <span>{{ session('success') }}</span>
            </div>
        @endif

        @if (isset($errors) && $errors->any())
            <div class="bg-rose-500/10 border border-rose-500/30 p-4 rounded-xl text-rose-300 text-[13px] space-y-1">
                <div class="font-bold flex items-center gap-2">
                    <i data-lucide="alert-circle" class="w-4 h-4 text-rose-400"></i> Please correct the following errors:
                </div>
                <ul class="list-disc list-inside text-[12px] space-y-0.5 text-rose-200">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <!-- Main Product Edit Form -->
        <form action="{{ url('/admin/products/' . $product->id) }}" method="POST" enctype="multipart/form-data" class="space-y-8">
            @csrf
            @method('PUT')

            <!-- SECTION 1: Product Essential Identity -->
            <div class="bg-[#0D121F]/90 backdrop-blur-xl border border-[#1E283D] p-6 sm:p-8 rounded-2xl space-y-6 shadow-xl">
                <div class="flex items-center gap-3 border-b border-[#1E283D] pb-4">
                    <div class="p-2 bg-[#B8712E]/10 border border-[#B8712E]/30 text-[#B8712E] rounded-lg">
                        <i data-lucide="package" class="w-5 h-5"></i>
                    </div>
                    <div>
                        <h2 class="text-[16px] font-serif font-bold text-white uppercase tracking-wide">
                            Essential Product Information
                        </h2>
                        <p class="text-[11px] text-slate-400">Configure title, brand collection category, gender, and public URL slug.</p>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="text-[11px] uppercase font-bold text-slate-300 tracking-wider block mb-2 font-mono">
                            Product Title / Name *
                        </label>
                        <input 
                            type="text" 
                            name="name" 
                            value="{{ old('name', $product->name) }}" 
                            required 
                            placeholder="e.g. L'Ombre d'Ambre Extrait"
                            class="w-full bg-[#131A2B] border border-[#1E283D] text-white px-4 py-3 rounded-xl text-[14px] font-medium focus:outline-none focus:border-[#B8712E] focus:ring-1 focus:ring-[#B8712E] transition-all"
                        >
                    </div>

                    <div>
                        <label class="text-[11px] uppercase font-bold text-slate-300 tracking-wider block mb-2 font-mono">
                            URL Slug (Identifier)
                        </label>
                        <input 
                            type="text" 
                            name="slug" 
                            value="{{ old('slug', $product->slug) }}" 
                            placeholder="e.g. lombre-dambre-extrait"
                            class="w-full bg-[#131A2B] border border-[#1E283D] text-white px-4 py-3 rounded-xl text-[14px] font-mono focus:outline-none focus:border-[#B8712E] focus:ring-1 focus:ring-[#B8712E] transition-all"
                        >
                        <span class="text-[10px] text-slate-500 mt-1 block">Live link: /product/{{ $product->slug }}</span>
                    </div>

                    <div>
                        <label class="text-[11px] uppercase font-bold text-slate-300 tracking-wider block mb-2 font-mono">
                            Category / Collection
                        </label>
                        <select 
                            name="category_id" 
                            class="w-full bg-[#131A2B] border border-[#1E283D] text-white px-4 py-3 rounded-xl text-[14px] font-medium focus:outline-none focus:border-[#B8712E]"
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
                        <label class="text-[11px] uppercase font-bold text-slate-300 tracking-wider block mb-2 font-mono">
                            Gender Target *
                        </label>
                        <select 
                            name="gender" 
                            required
                            class="w-full bg-[#131A2B] border border-[#1E283D] text-white px-4 py-3 rounded-xl text-[14px] font-medium focus:outline-none focus:border-[#B8712E]"
                        >
                            <option value="unisex" {{ old('gender', $product->gender) === 'unisex' ? 'selected' : '' }}>Unisex / Universal</option>
                            <option value="women" {{ old('gender', $product->gender) === 'women' ? 'selected' : '' }}>Women</option>
                            <option value="men" {{ old('gender', $product->gender) === 'men' ? 'selected' : '' }}>Men</option>
                        </select>
                    </div>
                </div>
            </div>

            <!-- SECTION 2: Pricing, Stock & Sizes -->
            <div class="bg-[#0D121F]/90 backdrop-blur-xl border border-[#1E283D] p-6 sm:p-8 rounded-2xl space-y-6 shadow-xl">
                <div class="flex items-center gap-3 border-b border-[#1E283D] pb-4">
                    <div class="p-2 bg-emerald-500/10 border border-emerald-500/30 text-emerald-400 rounded-lg">
                        <i data-lucide="tag" class="w-5 h-5"></i>
                    </div>
                    <div>
                        <h2 class="text-[16px] font-serif font-bold text-white uppercase tracking-wide">
                            Pricing, Inventory & Bottle Sizes
                        </h2>
                        <p class="text-[11px] text-slate-400">Manage base price, stock inventory level, concentration, and volume formats.</p>
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-6">
                    <div>
                        <label class="text-[11px] uppercase font-bold text-slate-300 tracking-wider block mb-2 font-mono">
                            Price (৳ / USD) *
                        </label>
                        <input 
                            type="number" 
                            step="0.01" 
                            name="price" 
                            value="{{ old('price', $product->price) }}" 
                            required
                            placeholder="3200.00"
                            class="w-full bg-[#131A2B] border border-[#1E283D] text-white px-4 py-3 rounded-xl text-[14px] font-mono focus:outline-none focus:border-[#B8712E] focus:ring-1 focus:ring-[#B8712E] transition-all"
                        >
                    </div>

                    <div>
                        <label class="text-[11px] uppercase font-bold text-slate-300 tracking-wider block mb-2 font-mono">
                            Stock Quantity *
                        </label>
                        <input 
                            type="number" 
                            name="stock" 
                            value="{{ old('stock', $product->stock ?? 50) }}" 
                            required
                            placeholder="50"
                            class="w-full bg-[#131A2B] border border-[#1E283D] text-white px-4 py-3 rounded-xl text-[14px] font-mono focus:outline-none focus:border-[#B8712E] focus:ring-1 focus:ring-[#B8712E] transition-all"
                        >
                    </div>

                    <div>
                        <label class="text-[11px] uppercase font-bold text-slate-300 tracking-wider block mb-2 font-mono">
                            Concentration
                        </label>
                        <input 
                            type="text" 
                            name="concentration" 
                            value="{{ old('concentration', $product->concentration ?? 'Eau de Parfum') }}" 
                            placeholder="e.g. Extrait de Parfum"
                            class="w-full bg-[#131A2B] border border-[#1E283D] text-white px-4 py-3 rounded-xl text-[14px] font-medium focus:outline-none focus:border-[#B8712E] transition-all"
                        >
                    </div>

                    <div>
                        <label class="text-[11px] uppercase font-bold text-slate-300 tracking-wider block mb-2 font-mono">
                            Sizes (Comma Separated)
                        </label>
                        <input 
                            type="text" 
                            name="sizes" 
                            value="{{ old('sizes', is_array($product->sizes) ? implode(', ', $product->sizes) : $product->sizes) }}" 
                            placeholder="50ml, 100ml"
                            class="w-full bg-[#131A2B] border border-[#1E283D] text-white px-4 py-3 rounded-xl text-[14px] font-medium focus:outline-none focus:border-[#B8712E] transition-all"
                        >
                    </div>
                </div>
            </div>

            <!-- SECTION 3: Olfactory Architecture (Fragrance Notes) -->
            <div class="bg-[#0D121F]/90 backdrop-blur-xl border border-[#1E283D] p-6 sm:p-8 rounded-2xl space-y-6 shadow-xl">
                <div class="flex items-center gap-3 border-b border-[#1E283D] pb-4">
                    <div class="p-2 bg-purple-500/10 border border-purple-500/30 text-purple-400 rounded-lg">
                        <i data-lucide="sparkles" class="w-5 h-5"></i>
                    </div>
                    <div>
                        <h2 class="text-[16px] font-serif font-bold text-white uppercase tracking-wide">
                            Olfactory Pyramid & Scent Architecture
                        </h2>
                        <p class="text-[11px] text-slate-400">Detail the scent family classification and top, heart, and base notes.</p>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="text-[11px] uppercase font-bold text-slate-300 tracking-wider block mb-2 font-mono">
                            Scent Family / Character
                        </label>
                        <input 
                            type="text" 
                            name="scent_family" 
                            value="{{ old('scent_family', $product->scent_family) }}" 
                            placeholder="e.g. Amber Woody / Oriental Rose"
                            class="w-full bg-[#131A2B] border border-[#1E283D] text-white px-4 py-3 rounded-xl text-[14px] font-medium focus:outline-none focus:border-[#B8712E] transition-all"
                        >
                    </div>

                    <div>
                        <label class="text-[11px] uppercase font-bold text-slate-300 tracking-wider block mb-2 font-mono">
                            Top Notes (Initial Impact)
                        </label>
                        <input 
                            type="text" 
                            name="notes_top" 
                            value="{{ old('notes_top', $product->notes_top) }}" 
                            placeholder="e.g. Calabrian Bergamot, Pink Pepper"
                            class="w-full bg-[#131A2B] border border-[#1E283D] text-white px-4 py-3 rounded-xl text-[14px] font-medium focus:outline-none focus:border-[#B8712E] transition-all"
                        >
                    </div>

                    <div>
                        <label class="text-[11px] uppercase font-bold text-slate-300 tracking-wider block mb-2 font-mono">
                            Heart Notes (The Core Body)
                        </label>
                        <input 
                            type="text" 
                            name="notes_heart" 
                            value="{{ old('notes_heart', $product->notes_heart) }}" 
                            placeholder="e.g. Damascena Rose, Rare Iris, Saffron"
                            class="w-full bg-[#131A2B] border border-[#1E283D] text-white px-4 py-3 rounded-xl text-[14px] font-medium focus:outline-none focus:border-[#B8712E] transition-all"
                        >
                    </div>

                    <div>
                        <label class="text-[11px] uppercase font-bold text-slate-300 tracking-wider block mb-2 font-mono">
                            Base Notes (Lingering Trail)
                        </label>
                        <input 
                            type="text" 
                            name="notes_base" 
                            value="{{ old('notes_base', $product->notes_base) }}" 
                            placeholder="e.g. Precious Amber, Cambodian Oud, Vanilla Bean"
                            class="w-full bg-[#131A2B] border border-[#1E283D] text-white px-4 py-3 rounded-xl text-[14px] font-medium focus:outline-none focus:border-[#B8712E] transition-all"
                        >
                    </div>
                </div>
            </div>

            <!-- SECTION 4: Visuals & Bottle Photography -->
            <div class="bg-[#0D121F]/90 backdrop-blur-xl border border-[#1E283D] p-6 sm:p-8 rounded-2xl space-y-6 shadow-xl">
                <div class="flex items-center gap-3 border-b border-[#1E283D] pb-4">
                    <div class="p-2 bg-sky-500/10 border border-sky-500/30 text-sky-400 rounded-lg">
                        <i data-lucide="image" class="w-5 h-5"></i>
                    </div>
                    <div>
                        <h2 class="text-[16px] font-serif font-bold text-white uppercase tracking-wide">
                            Bottle Photography & Media
                        </h2>
                        <p class="text-[11px] text-slate-400">Upload high-resolution bottle imagery or specify direct image URLs.</p>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                    <!-- Primary Bottle Image -->
                    <div class="bg-[#131A2B]/60 p-5 rounded-xl border border-[#1E283D] space-y-4">
                        <div class="flex items-center justify-between">
                            <label class="text-[11px] uppercase font-bold text-slate-300 tracking-wider font-mono">
                                Primary Bottle Image (Main)
                            </label>
                            @if ($product->primary_image_url)
                                <span class="text-[10px] text-emerald-400 bg-emerald-500/10 px-2 py-0.5 rounded border border-emerald-500/20">Active</span>
                            @endif
                        </div>

                        @if ($product->primary_image_url)
                            <div class="w-full h-48 bg-[#0D121F] rounded-xl overflow-hidden border border-[#1E283D] flex items-center justify-center p-2">
                                <img src="{{ $product->primary_image_url }}" alt="Primary Bottle" class="h-full w-auto object-contain rounded-lg">
                            </div>
                        @endif

                        <div>
                            <span class="text-[11px] text-slate-400 block mb-1 font-medium">Upload New Primary Image File:</span>
                            <input 
                                type="file" 
                                name="primary_image_file" 
                                accept="image/*"
                                class="w-full bg-[#0D121F] border border-[#1E283D] text-slate-300 px-3 py-2 rounded-xl text-[12px] file:mr-4 file:py-1 file:px-3 file:rounded-lg file:border-0 file:text-[11px] file:font-bold file:bg-[#B8712E] file:text-white hover:file:bg-[#9a5b20] cursor-pointer"
                            >
                        </div>

                        <div>
                            <span class="text-[11px] text-slate-400 block mb-1 font-medium">Or Image URL:</span>
                            <input 
                                type="text" 
                                name="primary_image_url" 
                                value="{{ old('primary_image_url', $product->primary_image_url) }}" 
                                placeholder="https://..."
                                class="w-full bg-[#0D121F] border border-[#1E283D] text-white px-3 py-2 rounded-xl text-[12px] font-mono focus:outline-none focus:border-[#B8712E]"
                            >
                        </div>
                    </div>

                    <!-- Secondary / Hover Image -->
                    <div class="bg-[#131A2B]/60 p-5 rounded-xl border border-[#1E283D] space-y-4">
                        <div class="flex items-center justify-between">
                            <label class="text-[11px] uppercase font-bold text-slate-300 tracking-wider font-mono">
                                Secondary / Cross-Fade Image (Hover)
                            </label>
                            @if ($product->secondary_image_url)
                                <span class="text-[10px] text-emerald-400 bg-emerald-500/10 px-2 py-0.5 rounded border border-emerald-500/20">Active</span>
                            @endif
                        </div>

                        @if ($product->secondary_image_url)
                            <div class="w-full h-48 bg-[#0D121F] rounded-xl overflow-hidden border border-[#1E283D] flex items-center justify-center p-2">
                                <img src="{{ $product->secondary_image_url }}" alt="Secondary Bottle" class="h-full w-auto object-contain rounded-lg">
                            </div>
                        @endif

                        <div>
                            <span class="text-[11px] text-slate-400 block mb-1 font-medium">Upload New Secondary Image File:</span>
                            <input 
                                type="file" 
                                name="secondary_image_file" 
                                accept="image/*"
                                class="w-full bg-[#0D121F] border border-[#1E283D] text-slate-300 px-3 py-2 rounded-xl text-[12px] file:mr-4 file:py-1 file:px-3 file:rounded-lg file:border-0 file:text-[11px] file:font-bold file:bg-[#B8712E] file:text-white hover:file:bg-[#9a5b20] cursor-pointer"
                            >
                        </div>

                        <div>
                            <span class="text-[11px] text-slate-400 block mb-1 font-medium">Or Image URL:</span>
                            <input 
                                type="text" 
                                name="secondary_image_url" 
                                value="{{ old('secondary_image_url', $product->secondary_image_url) }}" 
                                placeholder="https://..."
                                class="w-full bg-[#0D121F] border border-[#1E283D] text-white px-3 py-2 rounded-xl text-[12px] font-mono focus:outline-none focus:border-[#B8712E]"
                            >
                        </div>
                    </div>
                </div>
            </div>

            <!-- SECTION 5: Editorial Descriptions & Showcase Badges -->
            <div class="bg-[#0D121F]/90 backdrop-blur-xl border border-[#1E283D] p-6 sm:p-8 rounded-2xl space-y-6 shadow-xl">
                <div class="flex items-center gap-3 border-b border-[#1E283D] pb-4">
                    <div class="p-2 bg-amber-500/10 border border-amber-500/30 text-amber-400 rounded-lg">
                        <i data-lucide="file-text" class="w-5 h-5"></i>
                    </div>
                    <div>
                        <h2 class="text-[16px] font-serif font-bold text-white uppercase tracking-wide">
                            Story, Descriptions & Curated Badges
                        </h2>
                        <p class="text-[11px] text-slate-400">Refine the luxury story text and homepage feature badges.</p>
                    </div>
                </div>

                <div class="space-y-6">
                    <div>
                        <label class="text-[11px] uppercase font-bold text-slate-300 tracking-wider block mb-2 font-mono">
                            Short Editorial Excerpt / Tagline
                        </label>
                        <input 
                            type="text" 
                            name="short_description" 
                            value="{{ old('short_description', $product->short_description) }}" 
                            placeholder="e.g. A sensual tribute to amber and rare woods, bottled in sculpted crystal."
                            class="w-full bg-[#131A2B] border border-[#1E283D] text-white px-4 py-3 rounded-xl text-[14px] font-medium focus:outline-none focus:border-[#B8712E] transition-all"
                        >
                    </div>

                    <div>
                        <label class="text-[11px] uppercase font-bold text-slate-300 tracking-wider block mb-2 font-mono">
                            Full Story & Perfume Description
                        </label>
                        <textarea 
                            name="description" 
                            rows="5" 
                            placeholder="Crafted in Grasse with the purest essential absolutes..."
                            class="w-full bg-[#131A2B] border border-[#1E283D] text-white px-4 py-3 rounded-xl text-[14px] font-medium focus:outline-none focus:border-[#B8712E] transition-all leading-relaxed"
                        >{{ old('description', $product->description) }}</textarea>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 pt-2">
                        <label class="flex items-center gap-3 p-4 bg-[#131A2B]/60 border border-[#1E283D] rounded-xl cursor-pointer hover:border-[#B8712E]/50 transition-colors">
                            <input 
                                type="checkbox" 
                                name="is_featured" 
                                value="1" 
                                {{ old('is_featured', $product->is_featured) ? 'checked' : '' }}
                                class="w-5 h-5 text-[#B8712E] rounded border-slate-700 bg-[#0D121F] focus:ring-[#B8712E]"
                            >
                            <div>
                                <span class="text-[13px] font-bold text-white block">Feature on Homepage</span>
                                <span class="text-[11px] text-slate-400">Display this perfume in curated homepage sections</span>
                            </div>
                        </label>

                        <label class="flex items-center gap-3 p-4 bg-[#131A2B]/60 border border-[#1E283D] rounded-xl cursor-pointer hover:border-[#B8712E]/50 transition-colors">
                            <input 
                                type="checkbox" 
                                name="is_new_arrival" 
                                value="1" 
                                {{ old('is_new_arrival', $product->is_new_arrival) ? 'checked' : '' }}
                                class="w-5 h-5 text-[#B8712E] rounded border-slate-700 bg-[#0D121F] focus:ring-[#B8712E]"
                            >
                            <div>
                                <span class="text-[13px] font-bold text-white block">New Arrival Badge</span>
                                <span class="text-[11px] text-slate-400">Highlight with special NEW release label</span>
                            </div>
                        </label>
                    </div>
                </div>
            </div>

            <!-- Sticky Bottom Form Actions Bar -->
            <div class="sticky bottom-6 bg-[#0D121F]/95 backdrop-blur-xl border border-[#1E283D] p-5 rounded-2xl shadow-2xl flex flex-col sm:flex-row items-center justify-between gap-4 z-30">
                <div class="flex items-center gap-2 text-[12px] text-slate-400">
                    <i data-lucide="clock" class="w-4 h-4 text-slate-500"></i>
                    <span>Last updated: {{ $product->updated_at ? $product->updated_at->diffForHumans() : 'Recently' }}</span>
                </div>

                <div class="flex items-center gap-3 w-full sm:w-auto justify-end">
                    <a 
                        href="{{ url('/admin/products') }}" 
                        class="px-6 py-3 bg-[#131A2B] hover:bg-[#1c263d] border border-[#1E283D] text-slate-300 hover:text-white rounded-xl text-[12px] font-bold uppercase tracking-wider transition-all"
                    >
                        Cancel
                    </a>

                    <button 
                        type="submit" 
                        class="px-8 py-3 bg-gradient-to-r from-[#B8712E] to-[#9a5b20] hover:from-[#a66324] hover:to-[#844c18] text-white rounded-xl text-[12px] font-bold uppercase tracking-[0.14em] cursor-pointer transition-all shadow-lg shadow-[#B8712E]/25 flex items-center gap-2"
                    >
                        <i data-lucide="check" class="w-4 h-4"></i> Save Changes
                    </button>
                </div>
            </div>
        </form>

        <!-- Danger Zone: Delete Product -->
        <div class="bg-rose-500/5 border border-rose-500/20 p-6 sm:p-8 rounded-2xl space-y-4">
            <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
                <div>
                    <h3 class="text-[15px] font-bold text-rose-400 uppercase tracking-wide flex items-center gap-2">
                        <i data-lucide="trash-2" class="w-4 h-4"></i> Delete Product from Catalog
                    </h3>
                    <p class="text-[12px] text-slate-400 mt-1">
                        Permanently remove this perfume bottle from your database and catalog storefront.
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
                        class="px-5 py-2.5 bg-rose-600/20 hover:bg-rose-600 border border-rose-500/40 hover:border-rose-500 text-rose-300 hover:text-white rounded-xl text-[12px] font-bold uppercase tracking-wider transition-all cursor-pointer shadow-sm"
                    >
                        Delete Product
                    </button>
                </form>
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
