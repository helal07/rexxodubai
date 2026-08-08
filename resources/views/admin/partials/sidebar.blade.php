@php
    $activePage = $activePage ?? '';
    $isDashboard = ($activePage === 'dashboard' || request()->is('admin/dashboard') || request()->is('admin'));
@endphp

<!-- UNIFIED MASTER ADMIN SIDEBAR (POSITIONED UNDERNEATH FULL-WIDTH TOP BAR) -->
<aside id="mainAdminSidebar" class="w-64 bg-white border-r border-slate-200 min-h-[calc(100vh-3.5rem)] max-h-[calc(100vh-3.5rem)] p-4 flex flex-col justify-between shrink-0 relative z-20 overflow-y-auto select-none transition-all duration-300">
    <div class="space-y-4">
        <!-- 1. Search Menu Input (Matching Screenshot #2) -->
        <div class="relative">
            <i data-lucide="search" class="w-4 h-4 text-slate-400 absolute left-3 top-1/2 -translate-y-1/2"></i>
            <input type="text" id="sidebarMenuSearch" placeholder="Search menu..." onkeyup="filterSidebarMenu(this.value)" class="w-full pl-9 pr-3 py-2 text-[12px] bg-slate-50 border border-slate-200 rounded-xl focus:outline-none focus:border-[#4338ca] focus:bg-white text-slate-800 placeholder-slate-400 transition-all font-medium">
        </div>

        <!-- 2. Navigation Menu List -->
        <div class="space-y-1" id="sidebarMenuList">
            
            <!-- 1. HOME / DASHBOARD -->
            <div class="menu-search-item">
                @if($isDashboard)
                    <button type="button" onclick="switchSection('dashboard')" id="sidebar-btn-dashboard" class="w-full px-3.5 py-2.5 text-[12.5px] font-bold flex items-center gap-3 rounded-xl transition-all cursor-pointer bg-[#ede9fe] text-[#4338ca] shadow-2xs">
                        <i data-lucide="home" class="w-4 h-4 text-[#4338ca]"></i>
                        <span class="menu-text">Home / Dashboard</span>
                    </button>
                @else
                    <a href="{{ url('/admin/dashboard') }}" id="sidebar-btn-dashboard" class="w-full px-3.5 py-2.5 text-[12.5px] font-medium flex items-center gap-3 rounded-xl transition-all cursor-pointer text-slate-700 hover:bg-slate-50 hover:text-[#4338ca]">
                        <i data-lucide="home" class="w-4 h-4 text-slate-500"></i>
                        <span class="menu-text">Home / Dashboard</span>
                    </a>
                @endif
            </div>

            <!-- 2. SALES & ORDERS -->
            <div class="menu-search-item">
                <button type="button" onclick="toggleSubmenu('orders')" class="w-full px-3.5 py-2.5 text-[12.5px] font-medium flex items-center justify-between rounded-xl transition-all cursor-pointer {{ $activePage === 'orders' ? 'bg-[#ede9fe] text-[#4338ca] font-bold' : 'text-slate-700 hover:bg-slate-50 hover:text-[#4338ca]' }}">
                    <div class="flex items-center gap-3">
                        <i data-lucide="shopping-bag" class="w-4 h-4 {{ $activePage === 'orders' ? 'text-[#4338ca]' : 'text-slate-500' }}"></i>
                        <span class="menu-text">Sales & Orders</span>
                    </div>
                    <span data-chevron="orders" class="submenu-chevron {{ $activePage === 'orders' ? 'chevron-open' : '' }}"><i data-lucide="chevron-down" class="w-4 h-4"></i></span>
                </button>
                <div id="sub-orders" class="submenu-panel ml-4 pl-3 border-l-2 border-indigo-100 space-y-1 mt-1 {{ $activePage === 'orders' ? 'submenu-open' : '' }}">
                    @if($isDashboard)
                        <button type="button" id="sidebar-btn-create_order" onclick="switchSection('create_order')" class="w-full text-left px-3 py-1.5 text-[12px] font-bold text-emerald-700 hover:text-emerald-800 hover:bg-emerald-50 rounded-lg">• Create Sale / POS 🛍️</button>
                    @else
                        <a href="{{ url('/admin/dashboard#create_order') }}" id="sidebar-btn-create_order" class="block w-full text-left px-3 py-1.5 text-[12px] font-bold text-emerald-700 hover:text-emerald-800 hover:bg-emerald-50 rounded-lg">• Create Sale / POS 🛍️</a>
                    @endif
                    <a href="{{ url('/admin/orders') }}" class="block w-full text-left px-3 py-1.5 text-[12px] font-medium {{ $activePage === 'orders' && (!request('status') || request('status') === 'all') ? 'text-[#4338ca] font-bold bg-indigo-50/60' : 'text-slate-600 hover:text-[#4338ca] hover:bg-slate-50' }} rounded-lg">• Total Orders / All Sales</a>
                    <a href="{{ url('/admin/orders?status=completed') }}" class="block w-full text-left px-3 py-1.5 text-[12px] font-medium {{ $activePage === 'orders' && request('status') === 'completed' ? 'text-emerald-700 bg-emerald-50 font-bold' : 'text-slate-600 hover:text-emerald-700 hover:bg-slate-50' }} rounded-lg">• Success Orders</a>
                    <a href="{{ url('/admin/orders?status=cancelled') }}" class="block w-full text-left px-3 py-1.5 text-[12px] font-medium {{ $activePage === 'orders' && request('status') === 'cancelled' ? 'text-rose-700 bg-rose-50 font-bold' : 'text-slate-600 hover:text-rose-700 hover:bg-slate-50' }} rounded-lg">• Return / Cancelled</a>
                </div>
            </div>

            <!-- 3. PRODUCTS -->
            <div class="menu-search-item">
                <button type="button" onclick="toggleSubmenu('product')" class="w-full px-3.5 py-2.5 text-[12.5px] font-medium flex items-center justify-between rounded-xl transition-all cursor-pointer {{ in_array($activePage, ['products', 'categories', 'product_edit']) ? 'bg-[#ede9fe] text-[#4338ca] font-bold' : 'text-slate-700 hover:bg-slate-50 hover:text-[#4338ca]' }}">
                    <div class="flex items-center gap-3">
                        <i data-lucide="package" class="w-4 h-4 {{ in_array($activePage, ['products', 'categories', 'product_edit']) ? 'text-[#4338ca]' : 'text-slate-500' }}"></i>
                        <span class="menu-text">Products</span>
                    </div>
                    <span data-chevron="product" class="submenu-chevron {{ in_array($activePage, ['products', 'categories', 'product_edit']) ? 'chevron-open' : '' }}"><i data-lucide="chevron-down" class="w-4 h-4"></i></span>
                </button>
                <div id="sub-product" class="submenu-panel ml-4 pl-3 border-l-2 border-indigo-100 space-y-1 mt-1 {{ in_array($activePage, ['products', 'categories', 'product_edit']) ? 'submenu-open' : '' }}">
                    @if($isDashboard)
                        <button type="button" onclick="switchSection('products')" class="w-full text-left px-3 py-1.5 text-[12px] font-medium text-slate-600 hover:text-[#4338ca] hover:bg-slate-50 rounded-lg">• List Products</button>
                        <button type="button" onclick="switchSection('product_add')" class="w-full text-left px-3 py-1.5 text-[12px] font-medium text-slate-600 hover:text-[#4338ca] hover:bg-slate-50 rounded-lg">• Add Product</button>
                    @else
                        <a href="{{ url('/admin/products') }}" class="block w-full text-left px-3 py-1.5 text-[12px] font-medium {{ $activePage === 'products' ? 'text-[#4338ca] font-bold bg-indigo-50/60' : 'text-slate-600 hover:text-[#4338ca] hover:bg-slate-50' }} rounded-lg">• List Products</a>
                        <a href="{{ url('/admin/products#addProductForm') }}" class="block w-full text-left px-3 py-1.5 text-[12px] font-medium text-slate-600 hover:text-[#4338ca] hover:bg-slate-50 rounded-lg">• Add Product</a>
                    @endif
                    <a href="{{ url('/admin/categories') }}" class="block w-full text-left px-3 py-1.5 text-[12px] font-medium {{ $activePage === 'categories' ? 'text-[#4338ca] font-bold bg-indigo-50/60' : 'text-slate-600 hover:text-[#4338ca] hover:bg-slate-50' }} rounded-lg">• Category & Sub Category</a>
                </div>
            </div>

            <!-- 4. PURCHASE -->
            <div class="menu-search-item">
                <button type="button" onclick="toggleSubmenu('purchase')" class="w-full px-3.5 py-2.5 text-[12.5px] font-medium flex items-center justify-between rounded-xl transition-all cursor-pointer text-slate-700 hover:bg-slate-50 hover:text-[#4338ca]">
                    <div class="flex items-center gap-3">
                        <i data-lucide="shopping-cart" class="w-4 h-4 text-slate-500"></i>
                        <span class="menu-text">Purchase</span>
                    </div>
                    <span data-chevron="purchase" class="submenu-chevron"><i data-lucide="chevron-down" class="w-4 h-4"></i></span>
                </button>
                <div id="sub-purchase" class="submenu-panel ml-4 pl-3 border-l-2 border-indigo-100 space-y-1 mt-1">
                    @if($isDashboard)
                        <button type="button" onclick="switchSection('purchase_list')" class="w-full text-left px-3 py-1.5 text-[12px] font-medium text-slate-600 hover:text-[#4338ca] hover:bg-slate-50 rounded-lg">• Purchase List</button>
                        <button type="button" onclick="switchSection('purchase_add')" class="w-full text-left px-3 py-1.5 text-[12px] font-medium text-slate-600 hover:text-[#4338ca] hover:bg-slate-50 rounded-lg">• Add Purchase</button>
                    @else
                        <a href="{{ url('/admin/dashboard#purchase_list') }}" class="block w-full text-left px-3 py-1.5 text-[12px] font-medium text-slate-600 hover:text-[#4338ca] hover:bg-slate-50 rounded-lg">• Purchase List</a>
                        <a href="{{ url('/admin/dashboard#purchase_add') }}" class="block w-full text-left px-3 py-1.5 text-[12px] font-medium text-slate-600 hover:text-[#4338ca] hover:bg-slate-50 rounded-lg">• Add Purchase</a>
                    @endif
                </div>
            </div>

            <!-- 5. CONTACTS -->
            <div class="menu-search-item">
                <button type="button" onclick="toggleSubmenu('contact')" class="w-full px-3.5 py-2.5 text-[12.5px] font-medium flex items-center justify-between rounded-xl transition-all cursor-pointer text-slate-700 hover:bg-slate-50 hover:text-[#4338ca]">
                    <div class="flex items-center gap-3">
                        <i data-lucide="users" class="w-4 h-4 text-slate-500"></i>
                        <span class="menu-text">Contacts</span>
                    </div>
                    <span data-chevron="contact" class="submenu-chevron"><i data-lucide="chevron-down" class="w-4 h-4"></i></span>
                </button>
                <div id="sub-contact" class="submenu-panel ml-4 pl-3 border-l-2 border-indigo-100 space-y-1 mt-1">
                    @if($isDashboard)
                        <button type="button" onclick="switchSection('customers')" class="w-full text-left px-3 py-1.5 text-[12px] font-medium text-slate-600 hover:text-[#4338ca] hover:bg-slate-50 rounded-lg">• Customers</button>
                        <button type="button" onclick="switchSection('supplier')" class="w-full text-left px-3 py-1.5 text-[12px] font-medium text-slate-600 hover:text-[#4338ca] hover:bg-slate-50 rounded-lg">• Suppliers</button>
                    @else
                        <a href="{{ url('/admin/dashboard#customers') }}" class="block w-full text-left px-3 py-1.5 text-[12px] font-medium text-slate-600 hover:text-[#4338ca] hover:bg-slate-50 rounded-lg">• Customers</a>
                        <a href="{{ url('/admin/dashboard#supplier') }}" class="block w-full text-left px-3 py-1.5 text-[12px] font-medium text-slate-600 hover:text-[#4338ca] hover:bg-slate-50 rounded-lg">• Suppliers</a>
                    @endif
                </div>
            </div>

            <!-- 6. MENU BUILDER -->
            <div class="menu-search-item">
                <a href="{{ url('/admin/menus') }}" class="w-full px-3.5 py-2.5 text-[12.5px] font-medium flex items-center gap-3 rounded-xl transition-all cursor-pointer {{ $activePage === 'menus' ? 'bg-[#ede9fe] text-[#4338ca] font-bold' : 'text-slate-700 hover:bg-slate-50 hover:text-[#4338ca]' }}">
                    <i data-lucide="menu" class="w-4 h-4 {{ $activePage === 'menus' ? 'text-[#4338ca]' : 'text-slate-500' }}"></i>
                    <span class="menu-text">Menu Builder</span>
                </a>
            </div>

            <!-- 7. API & GATEWAY -->
            <div class="menu-search-item">
                <button type="button" onclick="toggleSubmenu('api_gateway')" class="w-full px-3.5 py-2.5 text-[12.5px] font-medium flex items-center justify-between rounded-xl transition-all cursor-pointer {{ in_array($activePage, ['courier', 'api_gateway']) ? 'bg-[#ede9fe] text-[#4338ca] font-bold' : 'text-slate-700 hover:bg-slate-50 hover:text-[#4338ca]' }}">
                    <div class="flex items-center gap-3">
                        <i data-lucide="plug-zap" class="w-4 h-4 {{ in_array($activePage, ['courier', 'api_gateway']) ? 'text-[#4338ca]' : 'text-slate-500' }}"></i>
                        <span class="menu-text">API & Gateway</span>
                    </div>
                    <span data-chevron="api_gateway" class="submenu-chevron {{ in_array($activePage, ['courier', 'api_gateway']) ? 'chevron-open' : '' }}"><i data-lucide="chevron-down" class="w-4 h-4"></i></span>
                </button>
                <div id="sub-api_gateway" class="submenu-panel ml-4 pl-3 border-l-2 border-indigo-100 space-y-1 mt-1 {{ in_array($activePage, ['courier', 'api_gateway']) ? 'submenu-open' : '' }}">
                    @if($isDashboard)
                        <button type="button" id="sidebar-btn-api_payment" onclick="switchSection('api_payment')" class="w-full text-left px-3 py-1.5 text-[12px] font-medium text-slate-600 hover:text-[#4338ca] hover:bg-slate-50 rounded-lg">• Payment Gateway 💳</button>
                        <button type="button" id="sidebar-btn-api_sms" onclick="switchSection('api_sms')" class="w-full text-left px-3 py-1.5 text-[12px] font-medium text-slate-600 hover:text-[#4338ca] hover:bg-slate-50 rounded-lg">• SMS Setting 💬</button>
                        <button type="button" id="sidebar-btn-api_courier" onclick="switchSection('api_courier')" class="w-full text-left px-3 py-1.5 text-[12px] font-medium text-slate-600 hover:text-[#4338ca] hover:bg-slate-50 rounded-lg">• Courier Setting 🚚</button>
                    @else
                        <a href="{{ url('/admin/dashboard#api_payment') }}" id="sidebar-btn-api_payment" class="block w-full text-left px-3 py-1.5 text-[12px] font-medium text-slate-600 hover:text-[#4338ca] hover:bg-slate-50 rounded-lg">• Payment Gateway 💳</a>
                        <a href="{{ url('/admin/dashboard#api_sms') }}" id="sidebar-btn-api_sms" class="block w-full text-left px-3 py-1.5 text-[12px] font-medium text-slate-600 hover:text-[#4338ca] hover:bg-slate-50 rounded-lg">• SMS Setting 💬</a>
                        <a href="{{ url('/admin/dashboard#api_courier') }}" id="sidebar-btn-api_courier" class="block w-full text-left px-3 py-1.5 text-[12px] font-medium {{ $activePage === 'courier' ? 'text-[#4338ca] font-bold bg-indigo-50/60' : 'text-slate-600 hover:text-[#4338ca] hover:bg-slate-50' }} rounded-lg">• Courier Setting 🚚</a>
                    @endif
                </div>
            </div>

            <!-- 8. SEO & SITEMAP -->
            <div class="menu-search-item">
                <button type="button" onclick="toggleSubmenu('seo_sub')" class="w-full px-3.5 py-2.5 text-[12.5px] font-medium flex items-center justify-between rounded-xl transition-all cursor-pointer text-slate-700 hover:bg-slate-50 hover:text-[#4338ca]">
                    <div class="flex items-center gap-3">
                        <i data-lucide="search" class="w-4 h-4 text-slate-500"></i>
                        <span class="menu-text">SEO & Sitemap</span>
                    </div>
                    <span data-chevron="seo_sub" class="submenu-chevron"><i data-lucide="chevron-down" class="w-4 h-4"></i></span>
                </button>
                <div id="sub-seo_sub" class="submenu-panel ml-4 pl-3 border-l-2 border-indigo-100 space-y-1 mt-1">
                    @if($isDashboard)
                        <button type="button" id="sidebar-btn-seo_meta" onclick="switchSection('seo_meta')" class="w-full text-left px-3 py-1.5 text-[12px] font-medium text-slate-600 hover:text-[#4338ca] hover:bg-slate-50 rounded-lg">• SEO Meta Settings</button>
                        <button type="button" id="sidebar-btn-seo_marketing" onclick="switchSection('seo_marketing')" class="w-full text-left px-3 py-1.5 text-[12px] font-medium text-slate-600 hover:text-[#4338ca] hover:bg-slate-50 rounded-lg">• Marketing & Pixels</button>
                        <button type="button" id="sidebar-btn-sitemap" onclick="switchSection('sitemap')" class="w-full text-left px-3 py-1.5 text-[12px] font-medium text-slate-600 hover:text-[#4338ca] hover:bg-slate-50 rounded-lg">• Sitemap Generator</button>
                        <button type="button" id="sidebar-btn-robots" onclick="switchSection('robots')" class="w-full text-left px-3 py-1.5 text-[12px] font-medium text-slate-600 hover:text-[#4338ca] hover:bg-slate-50 rounded-lg">• Robots.txt Manager</button>
                        <button type="button" id="sidebar-btn-seo_ping" onclick="switchSection('seo_ping')" class="w-full text-left px-3 py-1.5 text-[12px] font-medium text-slate-600 hover:text-[#4338ca] hover:bg-slate-50 rounded-lg">• Ping Google / Bing</button>
                    @else
                        <a href="{{ url('/admin/dashboard#seo_meta') }}" id="sidebar-btn-seo_meta" class="block w-full text-left px-3 py-1.5 text-[12px] font-medium text-slate-600 hover:text-[#4338ca] hover:bg-slate-50 rounded-lg">• SEO Meta Settings</a>
                        <a href="{{ url('/admin/dashboard#seo_marketing') }}" id="sidebar-btn-seo_marketing" class="block w-full text-left px-3 py-1.5 text-[12px] font-medium text-slate-600 hover:text-[#4338ca] hover:bg-slate-50 rounded-lg">• Marketing & Pixels</a>
                        <a href="{{ url('/admin/dashboard#sitemap') }}" id="sidebar-btn-sitemap" class="block w-full text-left px-3 py-1.5 text-[12px] font-medium text-slate-600 hover:text-[#4338ca] hover:bg-slate-50 rounded-lg">• Sitemap Generator</a>
                        <a href="{{ url('/admin/dashboard#robots') }}" id="sidebar-btn-robots" class="block w-full text-left px-3 py-1.5 text-[12px] font-medium text-slate-600 hover:text-[#4338ca] hover:bg-slate-50 rounded-lg">• Robots.txt Manager</a>
                        <a href="{{ url('/admin/dashboard#seo_ping') }}" id="sidebar-btn-seo_ping" class="block w-full text-left px-3 py-1.5 text-[12px] font-medium text-slate-600 hover:text-[#4338ca] hover:bg-slate-50 rounded-lg">• Ping Google / Bing</a>
                    @endif
                </div>
            </div>

            <!-- DIVIDER: SYSTEM -->
            <div class="pt-3 mt-3 border-t border-slate-100">
                <span class="text-[10px] font-bold uppercase tracking-wider text-slate-400 px-3 block mb-1.5">Settings & Configuration</span>
            </div>

            <!-- 9. SITE SETTING -->
            <div class="menu-search-item">
                @if($isDashboard)
                    <button type="button" onclick="switchSection('settings')" id="sidebar-btn-settings" class="w-full px-3.5 py-2.5 text-[12.5px] font-medium flex items-center gap-3 rounded-xl transition-all cursor-pointer text-slate-700 hover:bg-slate-50 hover:text-[#4338ca]">
                        <i data-lucide="settings" class="w-4 h-4 text-slate-500"></i>
                        <span class="menu-text">Business Settings</span>
                    </button>
                @else
                    <a href="{{ url('/admin/dashboard#settings') }}" id="sidebar-btn-settings" class="w-full px-3.5 py-2.5 text-[12.5px] font-medium flex items-center gap-3 rounded-xl transition-all cursor-pointer text-slate-700 hover:bg-slate-50 hover:text-[#4338ca]">
                        <i data-lucide="settings" class="w-4 h-4 text-slate-500"></i>
                        <span class="menu-text">Business Settings</span>
                    </a>
                @endif
            </div>
        </div>
    </div>

    <!-- Quick Footer Brand -->
    <div class="pt-4 mt-4 border-t border-slate-100 text-center">
        <p class="text-[10.5px] font-semibold text-slate-400 uppercase tracking-widest">
            v2.6 • CONTROL HUB
        </p>
    </div>
</aside>

<script>
    function filterSidebarMenu(query) {
        query = query.toLowerCase().trim();
        const items = document.querySelectorAll('#sidebarMenuList .menu-search-item');
        items.forEach(item => {
            const text = item.innerText.toLowerCase();
            if (!query || text.includes(query)) {
                item.style.display = '';
                // If matched inside a submenu, open the submenu
                if (query) {
                    const panel = item.querySelector('.submenu-panel');
                    if (panel) panel.classList.add('submenu-open');
                }
            } else {
                item.style.display = 'none';
            }
        });
    }
</script>
