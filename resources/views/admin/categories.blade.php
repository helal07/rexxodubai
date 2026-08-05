<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $siteSettings['siteName'] ?? 'REXXO BD' }} — Categories & Subcategories Manager</title>
    
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
                        <i data-lucide="layers" class="w-5 h-5"></i>
                    </div>
                @endif
                <div>
                    <span class="text-[10px] text-[#B8712E] uppercase font-bold tracking-[0.2em] font-mono block">
                        {{ $siteSettings['siteName'] ?? 'REXXO BD' }} TAXONOMY ARCHITECTURE
                    </span>
                    <h1 class="text-[22px] font-serif font-bold text-white uppercase tracking-tight">
                        Categories & Subcategories
                    </h1>
                </div>
            </div>
            <div class="flex items-center gap-3">
                <a href="{{ url('/admin/menus') }}" class="inline-flex items-center gap-2 bg-[#131A2B] hover:bg-[#1c263d] border border-[#1E283D] text-white px-4 py-2.5 rounded-xl text-[12px] font-bold uppercase tracking-wider transition-all shadow-sm">
                    <i data-lucide="menu" class="w-4 h-4 text-slate-400"></i> Menu Builder
                </a>
                <a href="{{ url('/admin') }}" class="inline-flex items-center gap-2 bg-[#131A2B] hover:bg-[#1c263d] border border-[#1E283D] text-white px-4 py-2.5 rounded-xl text-[12px] font-bold uppercase tracking-wider transition-all shadow-sm">
                    <i data-lucide="arrow-left" class="w-4 h-4 text-slate-400"></i> Dashboard
                </a>
                <a href="{{ url('/perfumes') }}" target="_blank" class="inline-flex items-center gap-2 bg-emerald-600 hover:bg-emerald-500 text-white px-4 py-2.5 rounded-xl text-[12px] font-bold uppercase tracking-wider transition-all shadow-md">
                    <i data-lucide="external-link" class="w-4 h-4"></i> Preview Catalog
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
            <!-- Add New Category / Subcategory Form -->
            <div class="lg:col-span-5 bg-[#0D121F]/90 backdrop-blur-xl border border-[#1E283D] p-7 rounded-2xl space-y-6 shadow-xl">
                <div class="flex items-center gap-3 border-b border-[#1E283D] pb-4">
                    <div class="p-2 bg-[#B8712E]/10 border border-[#B8712E]/30 text-[#B8712E] rounded-lg">
                        <i data-lucide="folder-plus" class="w-5 h-5"></i>
                    </div>
                    <h2 class="text-[16px] font-serif font-bold text-white uppercase tracking-wide">
                        Create Category / Subcategory
                    </h2>
                </div>

                <form action="{{ url('/admin/categories') }}" method="POST" class="space-y-4">
                    @csrf
                    <div>
                        <label class="text-[10px] uppercase font-bold text-slate-400 tracking-wider block mb-1.5 font-mono">
                            PARENT CATEGORY (LEAVE EMPTY FOR MAIN CATEGORY)
                        </label>
                        <select name="parent_id" class="w-full bg-[#131A2B] border border-[#1E283D] text-white px-4 py-3 rounded-xl text-[13px] focus:outline-none focus:border-[#B8712E] transition-all">
                            <option value="">None (Top-Level Main Category)</option>
                            @foreach ($parentCategories as $pCat)
                                <option value="{{ $pCat->id }}">📁 {{ $pCat->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="text-[10px] uppercase font-bold text-slate-400 tracking-wider block mb-1.5 font-mono">
                            CATEGORY NAME
                        </label>
                        <input type="text" name="name" required placeholder="e.g. Eau de Parfum, Rare Oud, Gifts" class="w-full bg-[#131A2B] border border-[#1E283D] text-white px-4 py-3 rounded-xl text-[13px] focus:outline-none focus:border-[#B8712E] transition-all">
                    </div>

                    <div>
                        <label class="text-[10px] uppercase font-bold text-slate-400 tracking-wider block mb-1.5 font-mono">
                            URL SLUG (OPTIONAL - AUTO GENERATED)
                        </label>
                        <input type="text" name="slug" placeholder="e.g. eau-de-parfum" class="w-full bg-[#131A2B] border border-[#1E283D] text-white px-4 py-3 rounded-xl text-[13px] font-mono focus:outline-none focus:border-[#B8712E] transition-all">
                    </div>

                    <div>
                        <label class="text-[10px] uppercase font-bold text-slate-400 tracking-wider block mb-1.5 font-mono">
                            DESCRIPTION (OPTIONAL)
                        </label>
                        <textarea name="description" rows="2" placeholder="Brief fragrance family or edition note..." class="w-full bg-[#131A2B] border border-[#1E283D] text-white px-4 py-3 rounded-xl text-[13px] focus:outline-none focus:border-[#B8712E] transition-all"></textarea>
                    </div>

                    <div>
                        <label class="text-[10px] uppercase font-bold text-slate-400 tracking-wider block mb-1.5 font-mono">
                            SORT ORDER
                        </label>
                        <input type="number" name="sort_order" value="1" class="w-full bg-[#131A2B] border border-[#1E283D] text-white px-4 py-3 rounded-xl text-[13px] focus:outline-none focus:border-[#B8712E] transition-all">
                    </div>

                    <button type="submit" class="w-full bg-gradient-to-r from-[#B8712E] to-[#9a5b20] hover:from-[#a66324] hover:to-[#844c18] text-white py-3.5 rounded-xl text-[12px] font-bold uppercase tracking-[0.14em] cursor-pointer transition-all shadow-lg shadow-[#B8712E]/20 flex items-center justify-center gap-2">
                        <i data-lucide="save" class="w-4 h-4"></i> Save Category
                    </button>
                </form>
            </div>

            <!-- Categories Tree & Hierarchy List -->
            <div class="lg:col-span-7 bg-[#0D121F]/90 backdrop-blur-xl border border-[#1E283D] p-7 rounded-2xl space-y-6 shadow-xl">
                <div class="flex items-center justify-between border-b border-[#1E283D] pb-4">
                    <div class="flex items-center gap-3">
                        <div class="p-2 bg-slate-800 border border-slate-700 text-slate-300 rounded-lg">
                            <i data-lucide="folder-tree" class="w-5 h-5"></i>
                        </div>
                        <h2 class="text-[16px] font-serif font-bold text-white uppercase tracking-wide">
                            Category Hierarchy
                        </h2>
                    </div>
                    <span class="text-[11px] font-mono text-slate-400 bg-[#131A2B] px-3 py-1 rounded-full border border-[#1E283D]">
                        {{ count($categories) }} TOTAL CATEGORIES
                    </span>
                </div>

                <div class="space-y-4">
                    @forelse ($parentCategories as $parent)
                        <div class="bg-[#131A2B] border border-[#1E283D] rounded-xl p-4.5 space-y-3 hover:border-slate-600 transition-all">
                            <!-- Main Parent Category Header -->
                            <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-3 pb-3 border-b border-[#1E283D]/60">
                                <div class="flex items-center gap-2.5">
                                    <div class="p-1.5 bg-[#B8712E]/20 text-[#B8712E] rounded-lg">
                                        <i data-lucide="folder" class="w-4 h-4"></i>
                                    </div>
                                    <div>
                                        <span class="font-bold text-[14px] text-white">{{ $parent->name }}</span>
                                        <span class="text-[11px] font-mono text-slate-400 ml-2">/perfumes?category={{ $parent->slug }}</span>
                                    </div>
                                </div>

                                <div class="flex items-center gap-2">
                                    <a href="{{ url('/perfumes?category=' . $parent->slug) }}" target="_blank" class="text-slate-400 hover:text-white p-1.5 rounded-lg bg-[#0D121F] border border-[#1E283D] text-[11px] flex items-center gap-1">
                                        <i data-lucide="external-link" class="w-3.5 h-3.5"></i>
                                    </a>
                                    <form action="{{ url('/admin/categories/' . $parent->id) }}" method="POST" onsubmit="return confirm('Delete parent category and all subcategories?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="bg-rose-500/10 hover:bg-rose-500 border border-rose-500/30 text-rose-400 hover:text-white text-[11px] uppercase font-bold p-1.5 rounded-lg transition-all cursor-pointer">
                                            <i data-lucide="trash-2" class="w-3.5 h-3.5"></i>
                                        </button>
                                    </form>
                                </div>
                            </div>

                            <!-- Subcategories List -->
                            @if ($parent->children && $parent->children->count() > 0)
                                <div class="pl-4 sm:pl-6 space-y-2 border-l-2 border-[#1E283D] ml-2">
                                    <span class="text-[10px] uppercase font-bold tracking-wider text-slate-500 font-mono block">Subcategories:</span>
                                    @foreach ($parent->children as $child)
                                        <div class="flex items-center justify-between bg-[#0D121F]/80 p-2.5 rounded-lg border border-[#1E283D]/60 hover:border-[#B8712E]/40 transition-all">
                                            <div class="flex items-center gap-2">
                                                <i data-lucide="corner-down-right" class="w-3.5 h-3.5 text-slate-500"></i>
                                                <span class="text-[12px] font-medium text-slate-200">{{ $child->name }}</span>
                                                <span class="text-[10px] font-mono text-slate-500">({{ $child->slug }})</span>
                                            </div>

                                            <div class="flex items-center gap-2">
                                                <a href="{{ url('/perfumes?category=' . $child->slug) }}" target="_blank" class="text-slate-400 hover:text-white text-[10px] font-mono">
                                                    View
                                                </a>
                                                <form action="{{ url('/admin/categories/' . $child->id) }}" method="POST" onsubmit="return confirm('Delete this subcategory?');">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="text-rose-400 hover:text-rose-300 p-1 cursor-pointer">
                                                        <i data-lucide="trash-2" class="w-3 h-3"></i>
                                                    </button>
                                                </form>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            @else
                                <div class="text-[11px] text-slate-500 pl-4 italic">No subcategories under this main category.</div>
                            @endif
                        </div>
                    @empty
                        <div class="text-center py-10 text-slate-500 text-[13px] border border-dashed border-[#1E283D] rounded-xl font-mono">
                            No categories created yet. Run migrations or add a category on the left.
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
