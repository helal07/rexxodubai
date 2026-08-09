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
                    <a href="{{ url('/admin') }}" id="sidebar-btn-dashboard" class="w-full px-3.5 py-2.5 text-[12.5px] font-medium flex items-center gap-3 rounded-xl transition-all cursor-pointer text-slate-700 hover:bg-slate-50 hover:text-[#4338ca]">
                        <i data-lucide="home" class="w-4 h-4 text-slate-500"></i>
                        <span class="menu-text">Home / Dashboard</span>
                    </a>
            </div>

            <!-- 2. SALES & ORDERS -->
            <div class="menu-search-item">
                <button type="button" onclick="toggleSubmenu('orders')" class="w-full px-3.5 py-2.5 text-[12.5px] font-medium flex items-center justify-between rounded-xl transition-all cursor-pointer {{ in_array($activePage, ['orders', 'create_order']) ? 'bg-[#ede9fe] text-[#4338ca] font-bold' : 'text-slate-700 hover:bg-slate-50 hover:text-[#4338ca]' }}">
                    <div class="flex items-center gap-3">
                        <i data-lucide="shopping-bag" class="w-4 h-4 {{ in_array($activePage, ['orders', 'create_order']) ? 'text-[#4338ca]' : 'text-slate-500' }}"></i>
                        <span class="menu-text">Sales & Orders</span>
                    </div>
                    <span data-chevron="orders" class="submenu-chevron {{ in_array($activePage, ['orders', 'create_order']) ? 'chevron-open' : '' }}"><i data-lucide="chevron-down" class="w-4 h-4"></i></span>
                </button>
                <div id="sub-orders" class="submenu-panel ml-4 pl-3 border-l-2 border-indigo-100 space-y-1 mt-1 {{ in_array($activePage, ['orders', 'create_order']) ? 'submenu-open' : '' }}">
                    <a href="{{ url('/admin/create-order') }}" id="sidebar-btn-create_order" class="block w-full text-left px-3 py-1.5 text-[12px] font-bold {{ $activePage === 'create_order' ? 'text-[#4338ca] bg-indigo-50/60' : 'text-emerald-700 hover:text-emerald-800 hover:bg-emerald-50' }} rounded-lg">• Create Sale / POS 🛍️</a>
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
                        <a href="{{ url('/admin/products/list') }}" id="sidebar-btn-products" class="block w-full text-left px-3 py-1.5 text-[12px] font-medium {{ $activePage === 'products' ? 'text-[#4338ca] font-bold bg-indigo-50/60' : 'text-slate-600 hover:text-[#4338ca] hover:bg-slate-50' }} rounded-lg">• List Products</a>
                        <a href="{{ url('/admin/products/add') }}" id="sidebar-btn-product_add" class="block w-full text-left px-3 py-1.5 text-[12px] font-medium text-slate-600 hover:text-[#4338ca] hover:bg-slate-50 rounded-lg">• Add Product</a>
                    <a href="{{ url('/admin/categories') }}" class="block w-full text-left px-3 py-1.5 text-[12px] font-medium {{ $activePage === 'categories' ? 'text-[#4338ca] font-bold bg-indigo-50/60' : 'text-slate-600 hover:text-[#4338ca] hover:bg-slate-50' }} rounded-lg">• Category & Sub Category</a>
                </div>
            </div>

            <!-- 4. PURCHASE -->
            <div class="menu-search-item">
                <button type="button" onclick="toggleSubmenu('purchase')" class="w-full px-3.5 py-2.5 text-[12.5px] font-medium flex items-center justify-between rounded-xl transition-all cursor-pointer {{ in_array($activePage, ['purchase_list', 'purchase_add']) ? 'bg-[#ede9fe] text-[#4338ca] font-bold' : 'text-slate-700 hover:bg-slate-50 hover:text-[#4338ca]' }}">
                    <div class="flex items-center gap-3">
                        <i data-lucide="shopping-cart" class="w-4 h-4 {{ in_array($activePage, ['purchase_list', 'purchase_add']) ? 'text-[#4338ca]' : 'text-slate-500' }}"></i>
                        <span class="menu-text">Purchase</span>
                    </div>
                    <span data-chevron="purchase" class="submenu-chevron {{ in_array($activePage, ['purchase_list', 'purchase_add']) ? 'chevron-open' : '' }}"><i data-lucide="chevron-down" class="w-4 h-4"></i></span>
                </button>
                <div id="sub-purchase" class="submenu-panel ml-4 pl-3 border-l-2 border-indigo-100 space-y-1 mt-1 {{ in_array($activePage, ['purchase_list', 'purchase_add']) ? 'submenu-open' : '' }}">
                        <a href="{{ url('/admin/purchases/list') }}" id="sidebar-btn-purchase_list" class="block w-full text-left px-3 py-1.5 text-[12px] font-medium {{ $activePage === 'purchase_list' ? 'text-[#4338ca] font-bold bg-indigo-50/60' : 'text-slate-600 hover:text-[#4338ca] hover:bg-slate-50' }} rounded-lg">• Purchase List</a>
                        <a href="{{ url('/admin/purchases/add') }}" id="sidebar-btn-purchase_add" class="block w-full text-left px-3 py-1.5 text-[12px] font-medium {{ $activePage === 'purchase_add' ? 'text-[#4338ca] font-bold bg-indigo-50/60' : 'text-slate-600 hover:text-[#4338ca] hover:bg-slate-50' }} rounded-lg">• Add Purchase</a>
                </div>
            </div>

            <!-- 5. CONTACTS -->
            <div class="menu-search-item">
                <button type="button" onclick="toggleSubmenu('contact')" class="w-full px-3.5 py-2.5 text-[12.5px] font-medium flex items-center justify-between rounded-xl transition-all cursor-pointer {{ in_array($activePage, ['customers', 'supplier']) ? 'bg-[#ede9fe] text-[#4338ca] font-bold' : 'text-slate-700 hover:bg-slate-50 hover:text-[#4338ca]' }}">
                    <div class="flex items-center gap-3">
                        <i data-lucide="users" class="w-4 h-4 {{ in_array($activePage, ['customers', 'supplier']) ? 'text-[#4338ca]' : 'text-slate-500' }}"></i>
                        <span class="menu-text">Contacts</span>
                    </div>
                    <span data-chevron="contact" class="submenu-chevron {{ in_array($activePage, ['customers', 'supplier']) ? 'chevron-open' : '' }}"><i data-lucide="chevron-down" class="w-4 h-4"></i></span>
                </button>
                <div id="sub-contact" class="submenu-panel ml-4 pl-3 border-l-2 border-indigo-100 space-y-1 mt-1 {{ in_array($activePage, ['customers', 'supplier']) ? 'submenu-open' : '' }}">
                        <a href="{{ url('/admin/customers') }}" id="sidebar-btn-customers" class="block w-full text-left px-3 py-1.5 text-[12px] font-medium {{ $activePage === 'customers' ? 'text-[#4338ca] font-bold bg-indigo-50/60' : 'text-slate-600 hover:text-[#4338ca] hover:bg-slate-50' }} rounded-lg">• Customers</a>
                        <a href="{{ url('/admin/suppliers') }}" id="sidebar-btn-supplier" class="block w-full text-left px-3 py-1.5 text-[12px] font-medium {{ $activePage === 'supplier' ? 'text-[#4338ca] font-bold bg-indigo-50/60' : 'text-slate-600 hover:text-[#4338ca] hover:bg-slate-50' }} rounded-lg">• Suppliers</a>
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
                <button type="button" onclick="toggleSubmenu('api_gateway')" class="w-full px-3.5 py-2.5 text-[12.5px] font-medium flex items-center justify-between rounded-xl transition-all cursor-pointer {{ in_array($activePage, ['courier', 'api_gateway', 'courier_charges']) ? 'bg-[#ede9fe] text-[#4338ca] font-bold' : 'text-slate-700 hover:bg-slate-50 hover:text-[#4338ca]' }}">
                    <div class="flex items-center gap-3">
                        <i data-lucide="plug-zap" class="w-4 h-4 {{ in_array($activePage, ['courier', 'api_gateway', 'courier_charges']) ? 'text-[#4338ca]' : 'text-slate-500' }}"></i>
                        <span class="menu-text">API & Gateway</span>
                    </div>
                    <span data-chevron="api_gateway" class="submenu-chevron {{ in_array($activePage, ['courier', 'api_gateway', 'courier_charges']) ? 'chevron-open' : '' }}"><i data-lucide="chevron-down" class="w-4 h-4"></i></span>
                </button>
                <div id="sub-api_gateway" class="submenu-panel ml-4 pl-3 border-l-2 border-indigo-100 space-y-1 mt-1 {{ in_array($activePage, ['courier', 'api_gateway', 'courier_charges']) ? 'submenu-open' : '' }}">
                        <a href="{{ url('/admin/api-settings/payment') }}" id="sidebar-btn-api_payment" class="block w-full text-left px-3 py-1.5 text-[12px] font-medium text-slate-600 hover:text-[#4338ca] hover:bg-slate-50 rounded-lg">• Payment Gateway 💳</a>
                        <a href="{{ url('/admin/api-settings/sms') }}" id="sidebar-btn-api_sms" class="block w-full text-left px-3 py-1.5 text-[12px] font-medium text-slate-600 hover:text-[#4338ca] hover:bg-slate-50 rounded-lg">• SMS Setting 💬</a>
                        <a href="{{ url('/admin/api-settings/courier') }}" id="sidebar-btn-api_courier" class="block w-full text-left px-3 py-1.5 text-[12px] font-medium {{ $activePage === 'courier' ? 'text-[#4338ca] font-bold bg-indigo-50/60' : 'text-slate-600 hover:text-[#4338ca] hover:bg-slate-50' }} rounded-lg">• Courier Setting 🚚</a>
                        <a href="{{ url('/admin/courier-charges') }}" id="sidebar-btn-courier_charges" class="block w-full text-left px-3 py-1.5 text-[12px] font-medium {{ $activePage === 'courier_charges' ? 'text-[#4338ca] font-bold bg-indigo-50/60' : 'text-slate-600 hover:text-[#4338ca] hover:bg-slate-50' }} rounded-lg">• Courier Charge 📦</a>
                </div>
            </div>

            <!-- 8. SEO & SITEMAP -->
            <div class="menu-search-item">
                <button type="button" onclick="toggleSubmenu('seo_sub')" class="w-full px-3.5 py-2.5 text-[12.5px] font-medium flex items-center justify-between rounded-xl transition-all cursor-pointer {{ $activePage === 'seo_sub' ? 'bg-[#ede9fe] text-[#4338ca] font-bold' : 'text-slate-700 hover:bg-slate-50 hover:text-[#4338ca]' }}">
                    <div class="flex items-center gap-3">
                        <i data-lucide="search" class="w-4 h-4 {{ $activePage === 'seo_sub' ? 'text-[#4338ca]' : 'text-slate-500' }}"></i>
                        <span class="menu-text">SEO & Sitemap</span>
                    </div>
                    <span data-chevron="seo_sub" class="submenu-chevron {{ $activePage === 'seo_sub' ? 'chevron-open' : '' }}"><i data-lucide="chevron-down" class="w-4 h-4"></i></span>
                </button>
                <div id="sub-seo_sub" class="submenu-panel ml-4 pl-3 border-l-2 border-indigo-100 space-y-1 mt-1 {{ $activePage === 'seo_sub' ? 'submenu-open' : '' }}">
                        <a href="{{ url('/admin/seo-settings/meta') }}" id="sidebar-btn-seo_meta" class="block w-full text-left px-3 py-1.5 text-[12px] font-medium text-slate-600 hover:text-[#4338ca] hover:bg-slate-50 rounded-lg">• SEO Meta Settings</a>
                        <a href="{{ url('/admin/seo-settings/marketing') }}" id="sidebar-btn-seo_marketing" class="block w-full text-left px-3 py-1.5 text-[12px] font-medium text-slate-600 hover:text-[#4338ca] hover:bg-slate-50 rounded-lg">• Marketing & Pixels</a>
                        <a href="{{ url('/admin/seo-settings/sitemap') }}" id="sidebar-btn-sitemap" class="block w-full text-left px-3 py-1.5 text-[12px] font-medium text-slate-600 hover:text-[#4338ca] hover:bg-slate-50 rounded-lg">• Sitemap Generator</a>
                        <a href="{{ url('/admin/seo-settings/robots') }}" id="sidebar-btn-robots" class="block w-full text-left px-3 py-1.5 text-[12px] font-medium text-slate-600 hover:text-[#4338ca] hover:bg-slate-50 rounded-lg">• Robots.txt Manager</a>
                        <a href="{{ url('/admin/seo-settings/ping') }}" id="sidebar-btn-seo_ping" class="block w-full text-left px-3 py-1.5 text-[12px] font-medium text-slate-600 hover:text-[#4338ca] hover:bg-slate-50 rounded-lg">• Ping Google / Bing</a>
                </div>
            </div>

            <!-- DIVIDER: SYSTEM & ACCOUNTS -->
            <div class="pt-3 mt-3 border-t border-slate-100">
                <span class="text-[10px] font-bold uppercase tracking-wider text-slate-400 px-3 block mb-1.5">Settings & Accounts</span>
            </div>

            <!-- 9. USER & STAFF MANAGEMENT -->
            <div class="menu-search-item">
                <button type="button" onclick="toggleSubmenu('user_mgmt')" class="w-full px-3.5 py-2.5 text-[12.5px] font-medium flex items-center justify-between rounded-xl transition-all cursor-pointer {{ in_array($activePage, ['profile', 'users', 'roles']) ? 'bg-[#ede9fe] text-[#4338ca] font-bold' : 'text-slate-700 hover:bg-slate-50 hover:text-[#4338ca]' }}">
                    <div class="flex items-center gap-3">
                        <i data-lucide="user-check" class="w-4 h-4 {{ in_array($activePage, ['profile', 'users', 'roles']) ? 'text-[#4338ca]' : 'text-slate-500' }}"></i>
                        <span class="menu-text">User & Profile</span>
                    </div>
                    <span data-chevron="user_mgmt" class="submenu-chevron {{ in_array($activePage, ['profile', 'users', 'roles']) ? 'chevron-open' : '' }}"><i data-lucide="chevron-down" class="w-4 h-4"></i></span>
                </button>
                <div id="sub-user_mgmt" class="submenu-panel ml-4 pl-3 border-l-2 border-indigo-100 space-y-1 mt-1 {{ in_array($activePage, ['profile', 'users', 'roles']) ? 'submenu-open' : '' }}">
                        <a href="{{ url('/admin/profile') }}" class="block w-full text-left px-3 py-1.5 text-[12px] font-medium {{ $activePage === 'profile' ? 'text-[#4338ca] font-bold bg-indigo-50/60' : 'text-slate-600 hover:text-[#4338ca] hover:bg-slate-50' }} rounded-lg">• My Profile & Photo</a>
                        <a href="{{ url('/admin/profile/password') }}" class="block w-full text-left px-3 py-1.5 text-[12px] font-medium text-slate-600 hover:text-[#4338ca] hover:bg-slate-50 rounded-lg">• Change Password</a>
                        <a href="{{ url('/admin/profile/users') }}" class="block w-full text-left px-3 py-1.5 text-[12px] font-medium {{ $activePage === 'users' ? 'text-[#4338ca] font-bold bg-indigo-50/60' : 'text-slate-600 hover:text-[#4338ca] hover:bg-slate-50' }} rounded-lg">• Admin Users & Staff</a>
                        <a href="{{ url('/admin/roles') }}" class="block w-full text-left px-3 py-1.5 text-[12px] font-medium {{ $activePage === 'roles' ? 'text-[#4338ca] font-bold bg-indigo-50/60' : 'text-slate-600 hover:text-[#4338ca] hover:bg-slate-50' }} rounded-lg">• Roles & Permissions</a>
                </div>
            </div>

            <!-- 10. SITE SETTING -->
            <div class="menu-search-item">
                    <a href="{{ url('/admin/settings') }}" id="sidebar-btn-settings" class="w-full px-3.5 py-2.5 text-[12.5px] font-medium flex items-center gap-3 rounded-xl transition-all cursor-pointer text-slate-700 hover:bg-slate-50 hover:text-[#4338ca]">
                        <i data-lucide="settings" class="w-4 h-4 text-slate-500"></i>
                        <span class="menu-text">Business Settings</span>
                    </a>
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
                if (query) {
                    const panel = item.querySelector('.submenu-panel');
                    if (panel) panel.classList.add('submenu-open');
                }
            } else {
                item.style.display = 'none';
            }
        });
    }

    async function loadSeoStatus() {
        try {
            const r = await fetch('/admin/seo/status', { headers: { 'Accept': 'application/json' } });
            const d = await r.json();

            const sBadge = document.getElementById('sitemap-status-badge');
            const sUpdated = document.getElementById('sitemap-last-updated');
            const sEntries = document.getElementById('sitemap-entries');
            const sCheck = document.getElementById('sitemap-check-card');
            if (d.sitemap?.exists) {
                if (sBadge) { sBadge.className = 'bg-emerald-50 text-emerald-700 border border-emerald-200 px-2.5 py-0.5 rounded-full text-[10px] font-bold uppercase'; sBadge.textContent = '✔ Exists'; }
                if (sUpdated) sUpdated.textContent = d.sitemap.last_updated ?? '-';
                if (sEntries) sEntries.textContent = (d.sitemap.entries ?? 0) + ' URLs';
                if (sCheck) { sCheck.className = 'flex items-start gap-2.5 p-3 rounded-xl bg-emerald-50 border border-emerald-200'; sCheck.querySelector('i').setAttribute('data-lucide','check-circle-2'); sCheck.querySelector('i').className='w-4 h-4 text-emerald-600 mt-0.5 shrink-0'; sCheck.querySelector('p:first-child').className='text-[12px] font-bold text-emerald-800'; sCheck.querySelectorAll('p')[1].className='text-[10px] text-emerald-700'; sCheck.querySelectorAll('p')[1].textContent='Sitemap exists - '+d.sitemap.entries+' URLs indexed'; if(window.lucide) lucide.createIcons(); }
            } else {
                if (sBadge) { sBadge.className = 'bg-amber-50 text-amber-700 border border-amber-200 px-2.5 py-0.5 rounded-full text-[10px] font-bold uppercase'; sBadge.textContent = '⚠ Not Found'; }
                if (sUpdated) sUpdated.textContent = 'Never';
                if (sEntries) sEntries.textContent = '0';
                if (sCheck) { sCheck.className = 'flex items-start gap-2.5 p-3 rounded-xl bg-amber-50 border border-amber-200'; sCheck.querySelector('i').setAttribute('data-lucide','alert-triangle'); sCheck.querySelector('i').className='w-4 h-4 text-amber-600 mt-0.5 shrink-0'; sCheck.querySelector('p:first-child').className='text-[12px] font-bold text-amber-800'; sCheck.querySelectorAll('p')[1].className='text-[10px] text-amber-700'; sCheck.querySelectorAll('p')[1].textContent='Sitemap missing - indexing issue'; if(window.lucide) lucide.createIcons(); }
            }

            const rBadge = document.getElementById('robots-status-badge');
            const rUpdated = document.getElementById('robots-last-updated');
            const rCheck = document.getElementById('robots-check-card');
            if (d.robots?.exists) {
                if (rBadge) { rBadge.className = 'bg-emerald-50 text-emerald-700 border border-emerald-200 px-2.5 py-0.5 rounded-full text-[10px] font-bold uppercase'; rBadge.textContent = '✔ Configured'; }
                if (rUpdated) rUpdated.textContent = d.robots.last_updated ?? '-';
                if (rCheck) { rCheck.className = 'flex items-start gap-2.5 p-3 rounded-xl bg-emerald-50 border border-emerald-200'; rCheck.querySelector('i').setAttribute('data-lucide','check-circle-2'); rCheck.querySelector('i').className='w-4 h-4 text-emerald-600 mt-0.5 shrink-0'; rCheck.querySelector('p:first-child').className='text-[12px] font-bold text-emerald-800'; rCheck.querySelectorAll('p')[1].className='text-[10px] text-emerald-700'; rCheck.querySelectorAll('p')[1].textContent='robots.txt configured securely'; if(window.lucide) lucide.createIcons(); }
            } else {
                if (rBadge) { rBadge.className = 'bg-rose-50 text-rose-700 border border-rose-200 px-2.5 py-0.5 rounded-full text-[10px] font-bold uppercase'; rBadge.textContent = '⚠ Missing'; }
                if (rUpdated) rUpdated.textContent = 'Never';
                if (rCheck) { rCheck.className = 'flex items-start gap-2.5 p-3 rounded-xl bg-rose-50 border border-rose-200'; rCheck.querySelector('i').setAttribute('data-lucide','x-circle'); rCheck.querySelector('i').className='w-4 h-4 text-rose-600 mt-0.5 shrink-0'; rCheck.querySelector('p:first-child').className='text-[12px] font-bold text-rose-800'; rCheck.querySelectorAll('p')[1].className='text-[10px] text-rose-700'; rCheck.querySelectorAll('p')[1].textContent='robots.txt missing - critical SEO issue'; if(window.lucide) lucide.createIcons(); }
            }
        } catch(e) {}
    }

    async function generateSitemap() {
        const btn = document.getElementById('btn-gen-sitemap');
        const res = document.getElementById('sitemap-result');
        if (!btn || !res) return;
        btn.disabled = true;
        const origHtml = btn.innerHTML;
        btn.innerHTML = 'Generating...';
        res.className = 'text-[12px] font-mono p-3 rounded-lg bg-[#f8fafc] border text-[#475569]';
        res.classList.remove('hidden');
        res.innerHTML = 'Building sitemap from products and pages...';
        try {
            const csrfMeta = document.querySelector('meta[name="csrf-token"]');
            const csrfToken = csrfMeta ? csrfMeta.getAttribute('content') : '';
            const r = await fetch('/admin/seo/generate-sitemap', {
                method: 'POST',
                headers: { 'Accept': 'application/json', 'X-CSRF-TOKEN': csrfToken }
            });
            const d = await r.json();
            if (r.ok && d.success) {
                res.className = 'text-[12px] font-mono p-3 rounded-lg bg-emerald-50 border border-emerald-200 text-emerald-800';
                res.innerHTML = d.message + '<br>' + d.entries + ' URLs Generated: ' + d.generated_at;
                if(typeof loadSeoStatus === 'function') loadSeoStatus();
            } else {
                res.className = 'text-[12px] font-mono p-3 rounded-lg bg-rose-50 border border-rose-200 text-rose-800';
                res.innerHTML = d.message;
            }
        } catch(e) {
            res.className = 'text-[12px] font-mono p-3 rounded-lg bg-rose-50 border border-rose-200 text-rose-800';
            res.innerHTML = 'Server connection failed.';
        } finally {
            btn.disabled = false;
            btn.innerHTML = origHtml;
        }
    }

    async function generateRobots() {
        const btn = document.getElementById('btn-gen-robots');
        const res = document.getElementById('robots-result');
        if (!btn || !res) return;
        btn.disabled = true;
        const origHtml = btn.innerHTML;
        btn.innerHTML = 'Updating...';
        res.className = 'text-[12px] font-mono p-3 rounded-lg bg-[#f8fafc] border text-[#475569]';
        res.classList.remove('hidden');
        res.innerHTML = 'Building robots.txt with sitemap reference...';
        try {
            const csrfMeta = document.querySelector('meta[name="csrf-token"]');
            const csrfToken = csrfMeta ? csrfMeta.getAttribute('content') : '';
            const r = await fetch('/admin/seo/generate-robots', {
                method: 'POST',
                headers: { 'Accept': 'application/json', 'X-CSRF-TOKEN': csrfToken }
            });
            const d = await r.json();
            if (r.ok && d.success) {
                res.className = 'text-[12px] font-mono p-3 rounded-lg bg-emerald-50 border border-emerald-200 text-emerald-800';
                res.innerHTML = d.message + '<br>Generated: ' + d.generated_at;
                if(typeof loadSeoStatus === 'function') loadSeoStatus();
            } else {
                res.className = 'text-[12px] font-mono p-3 rounded-lg bg-rose-50 border border-rose-200 text-rose-800';
                res.innerHTML = d.message;
            }
        } catch(e) {
            res.className = 'text-[12px] font-mono p-3 rounded-lg bg-rose-50 border border-rose-200 text-rose-800';
            res.innerHTML = 'Server connection failed.';
        } finally {
            btn.disabled = false;
            btn.innerHTML = origHtml;
        }
    }

    async function pingSeo() {
        const btn = document.getElementById('btn-ping');
        const res = document.getElementById('ping-result');
        const gBadge = document.getElementById('ping-google-badge');
        const bBadge = document.getElementById('ping-bing-badge');
        if (!btn || !res) return;
        btn.disabled = true;
        const origHtml = btn.innerHTML;
        btn.innerHTML = 'Pinging...';
        if(gBadge) { gBadge.className='text-[10px] font-bold px-2.5 py-1 rounded-full bg-amber-50 text-amber-700 border border-amber-200 shrink-0 animate-pulse'; gBadge.textContent='Pinging...'; }
        if(bBadge) { bBadge.className='text-[10px] font-bold px-2.5 py-1 rounded-full bg-amber-50 text-amber-700 border border-amber-200 shrink-0 animate-pulse'; bBadge.textContent='Pinging...'; }
        res.className = 'text-[12px] font-mono p-4 rounded-lg bg-[#f8fafc] border text-[#475569]';
        res.classList.remove('hidden');
        res.innerHTML = 'Sending ping to Google & Bing...';
        try {
            const csrfMeta = document.querySelector('meta[name="csrf-token"]');
            const csrfToken = csrfMeta ? csrfMeta.getAttribute('content') : '';
            const r = await fetch('/admin/seo/ping-search-engines', {
                method: 'POST',
                headers: { 'Accept': 'application/json', 'X-CSRF-TOKEN': csrfToken }
            });
            const d = await r.json();
            if(gBadge) {
                const gOk = d.results?.Google?.success;
                gBadge.className = gOk ? 'text-[10px] font-bold px-2.5 py-1 rounded-full bg-emerald-50 text-emerald-700 border border-emerald-200 shrink-0' : 'text-[10px] font-bold px-2.5 py-1 rounded-full bg-amber-50 text-amber-700 border border-amber-200 shrink-0';
                gBadge.textContent = gOk ? '✔ Accepted' : '⚠ Failed';
            }
            if(bBadge) {
                const bOk = d.results?.Bing?.success;
                bBadge.className = bOk ? 'text-[10px] font-bold px-2.5 py-1 rounded-full bg-emerald-50 text-emerald-700 border border-emerald-200 shrink-0' : 'text-[10px] font-bold px-2.5 py-1 rounded-full bg-amber-50 text-amber-700 border border-amber-200 shrink-0';
                bBadge.textContent = bOk ? '✔ Accepted' : '⚠ Failed';
            }
            if (r.ok && d.success) {
                res.className = 'text-[12px] font-mono p-4 rounded-lg bg-emerald-50 border border-emerald-200 text-emerald-800 space-y-1';
                res.innerHTML = d.message;
            } else {
                res.className = 'text-[12px] font-mono p-4 rounded-lg bg-rose-50 border border-rose-200 text-rose-800';
                res.innerHTML = d.message;
            }
        } catch(e) {
            res.className = 'text-[12px] font-mono p-4 rounded-lg bg-rose-50 border border-rose-200 text-rose-800';
            res.innerHTML = 'Server connection failed.';
        } finally {
            btn.disabled = false;
            btn.innerHTML = origHtml;
        }
    }
    
    document.addEventListener('DOMContentLoaded', loadSeoStatus);
</script>
