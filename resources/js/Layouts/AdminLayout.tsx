import React, { useState, useEffect } from 'react';
import { Link, usePage, router } from '@inertiajs/react';
import {
    LayoutDashboard,
    Boxes,
    Layers,
    PlusCircle,
    ShoppingCart,
    ShoppingBag,
    Truck,
    CreditCard,
    MessageSquare,
    Users,
    UserCheck,
    ShieldCheck,
    Settings,
    FileText,
    Globe,
    RefreshCw,
    LogOut,
    Sun,
    Moon,
    ChevronDown,
    ChevronRight,
    CheckCircle2,
    AlertTriangle,
    Menu,
    X,
    ExternalLink,
    Search,
    SlidersHorizontal,
    Briefcase,
    Zap,
    Flag,
    LayoutTemplate
} from 'lucide-react';

interface SharedProps {
    auth?: {
        user?: {
            id: number;
            name: string;
            email: string;
            avatar_url?: string;
            is_admin?: boolean;
            roles?: string[];
            permissions?: string[];
        } | null;
    };
    flash?: {
        success?: string | null;
        error?: string | null;
    };
    siteSettings?: Record<string, any>;
}

interface AdminLayoutProps {
    children: React.ReactNode;
    activePage?: string;
    pageTitle?: string;
    pageSubtitle?: string;
    headerActions?: React.ReactNode;
}

export default function AdminLayout({
    children,
    activePage = 'dashboard',
    pageTitle,
    pageSubtitle,
    headerActions,
}: AdminLayoutProps) {
    const { auth, flash, siteSettings = {}, cmsData = {} } = usePage<SharedProps>().props;
    const currentUser = auth?.user;

    const [openSubmenu, setOpenSubmenu] = useState<string | null>(() => {
        if (['orders', 'create_order'].includes(activePage)) return 'orders';
        if (['products', 'product_add', 'categories'].includes(activePage)) return 'product';
        if (['purchase_list', 'purchase_add'].includes(activePage)) return 'purchase';
        if (['courier_charges', 'courier'].includes(activePage)) return 'courier';
        if (['api_payment', 'api_sms', 'api_courier'].includes(activePage)) return 'api_gateway';
        if (['seo_meta', 'seo_marketing', 'seo_ping', 'sitemap', 'robots'].includes(activePage)) return 'seo_sub';
        if (['users', 'roles'].includes(activePage)) return 'user_mgmt';
        return null;
    });

    const [mobileSidebarOpen, setMobileSidebarOpen] = useState(false);
    const [profileMenuOpen, setProfileMenuOpen] = useState(false);
    const [themeMode, setThemeMode] = useState<string>('default');
    const [clearingCache, setClearingCache] = useState(false);

    // Close profile menu when clicking outside
    useEffect(() => {
        const handleClickOutside = (e: MouseEvent) => {
            if (profileMenuOpen && !(e.target as Element).closest('#user-profile-menu')) {
                setProfileMenuOpen(false);
            }
        };
        document.addEventListener('mousedown', handleClickOutside);
        return () => document.removeEventListener('mousedown', handleClickOutside);
    }, [profileMenuOpen]);

    useEffect(() => {
        const saved = localStorage.getItem('admin_theme') || siteSettings.admin_theme || 'default';
        setThemeMode(saved);
        applyThemeClass(saved);
    }, [siteSettings.admin_theme]);

    const applyThemeClass = (mode: string) => {
        document.body.classList.remove('theme-night', 'theme-light');
        if (mode === 'night') {
            document.body.classList.add('theme-night');
        } else if (mode === 'light') {
            document.body.classList.add('theme-light');
        }
    };

    const handleThemeChange = (mode: string) => {
        setThemeMode(mode);
        localStorage.setItem('admin_theme', mode);
        applyThemeClass(mode);

        axios.post('/api/settings', {
            settings: { admin_theme: mode }
        }).catch(err => console.log('Theme sync note:', err));
    };

    const toggleSubmenu = (menuId: string) => {
        setOpenSubmenu(prev => (prev === menuId ? null : menuId));
    };

    const handleClearCache = async () => {
        setClearingCache(true);
        try {
            const token = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '';
            const res = await fetch('/api/clear-cache', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': token,
                    'Accept': 'application/json',
                },
            });
            if (res.ok) {
                alert('System Cache Cleared Successfully!');
            } else {
                alert('Failed to clear cache.');
            }
        } catch (e) {
            alert('Network error while clearing cache.');
        } finally {
            setClearingCache(false);
        }
    };

    const handleLogout = (e: React.FormEvent) => {
        e.preventDefault();
        router.post('/logout');
    };

    const siteName = cmsData?.global?.site_name || siteSettings.siteName || 'RaaxO BD';
    const logoUrl = cmsData?.global?.logo_url || siteSettings.logo_url || siteSettings.site_logo || '';

    return (
        <div className="bg-[#f8fafc] text-[#0f172a] font-sans flex flex-col min-h-screen relative overflow-x-hidden selection:bg-[#4338ca] selection:text-white">
            {/* 1. TOP HEADER BAR */}
            <header className="bg-white/90 backdrop-blur-xl border-b border-[#e2e8f0] sticky top-0 z-40 px-4 sm:px-6 py-3 flex items-center justify-between shadow-xs">
                <div className="flex items-center gap-3">
                    <button
                        type="button"
                        onClick={() => setMobileSidebarOpen(!mobileSidebarOpen)}
                        className="lg:hidden p-2 rounded-xl text-[#475569] hover:bg-[#f1f5f9]"
                    >
                        {mobileSidebarOpen ? <X className="w-5 h-5" /> : <Menu className="w-5 h-5" />}
                    </button>

                    <Link href="/admin/dashboard" className="flex items-center gap-3">
                        {logoUrl ? (
                            <img src={logoUrl} alt={siteName} className="h-8 max-w-[120px] object-contain" />
                        ) : (
                            <div className="w-8 h-8 rounded-xl bg-gradient-to-br from-[#0284c7] to-[#0369a1] text-white flex items-center justify-center font-bold text-sm shadow-sm">
                                {siteName.charAt(0)}
                            </div>
                        )}
                        <span className="font-serif font-bold text-lg text-[#0f172a] hidden sm:inline-block tracking-tight">
                            {siteName}
                        </span>
                        <span className="text-[10px] font-bold uppercase tracking-widest bg-[#e0f2fe] text-[#0284c7] px-2 py-0.5 rounded-full border border-[#bae6fd]">
                            Admin Panel
                        </span>
                    </Link>
                </div>

                <div className="flex items-center gap-2 sm:gap-4">
                    {/* Storefront Link */}
                    <a
                        href="/perfumes"
                        target="_blank"
                        rel="noreferrer"
                        className="hidden md:inline-flex items-center gap-1.5 px-3 py-1.5 bg-[#f8fafc] hover:bg-[#f1f5f9] border border-[#cbd5e1] text-[#475569] hover:text-[#0284c7] rounded-xl text-[11px] font-bold uppercase tracking-wider transition-all shadow-2xs"
                    >
                        <ExternalLink className="w-3.5 h-3.5" /> Storefront ↗
                    </a>

                    {/* Theme Switcher */}
                    <div className="bg-[#f1f5f9] p-1 rounded-xl flex items-center gap-1 border border-[#e2e8f0]">
                        <button
                            type="button"
                            onClick={() => handleThemeChange('default')}
                            className={`px-2 py-1 rounded-lg text-[10px] font-bold uppercase tracking-wider transition-all ${themeMode === 'default'
                                ? 'bg-[#0284c7] text-white shadow-xs'
                                : 'text-[#64748b] hover:text-[#0f172a]'
                                }`}
                        >
                            Auto
                        </button>
                        <button
                            type="button"
                            onClick={() => handleThemeChange('light')}
                            className={`p-1 rounded-lg transition-all ${themeMode === 'light'
                                ? 'bg-[#0284c7] text-white shadow-xs'
                                : 'text-[#64748b] hover:text-[#0f172a]'
                                }`}
                            title="Light Mode"
                        >
                            <Sun className="w-3.5 h-3.5" />
                        </button>
                        <button
                            type="button"
                            onClick={() => handleThemeChange('night')}
                            className={`p-1 rounded-lg transition-all ${themeMode === 'night'
                                ? 'bg-[#0284c7] text-white shadow-xs'
                                : 'text-[#64748b] hover:text-[#0f172a]'
                                }`}
                            title="Night Mode"
                        >
                            <Moon className="w-3.5 h-3.5" />
                        </button>
                    </div>

                    {/* Clear Cache Button */}
                    <button
                        type="button"
                        onClick={handleClearCache}
                        disabled={clearingCache}
                        className="px-3 py-1.5 bg-white hover:bg-[#f8fafc] border border-[#cbd5e1] text-[#475569] hover:text-[#0284c7] rounded-xl text-[11px] font-bold uppercase tracking-wider transition-all shadow-2xs flex items-center gap-1.5"
                    >
                        <RefreshCw className={`w-3.5 h-3.5 ${clearingCache ? 'animate-spin' : ''}`} />
                        <span className="hidden sm:inline">Clear Cache</span>
                    </button>

                    {/* User Profile / Logout */}
                    {currentUser && (
                        <div className="relative pl-2 border-l border-[#e2e8f0]" id="user-profile-menu">
                            <button
                                type="button"
                                onClick={() => setProfileMenuOpen(!profileMenuOpen)}
                                className="flex items-center gap-2 hover:bg-[#f8fafc] p-1 rounded-xl transition-colors"
                            >
                                {currentUser.avatar_url ? (
                                    <img
                                        src={currentUser.avatar_url}
                                        alt={currentUser.name}
                                        className="w-8 h-8 rounded-full object-cover border border-[#e2e8f0]"
                                    />
                                ) : (
                                    <div className="w-8 h-8 rounded-full bg-[#0284c7]/10 text-[#0284c7] font-bold text-xs flex items-center justify-center border border-[#0284c7]/20">
                                        {currentUser.name ? currentUser.name.charAt(0).toUpperCase() : 'A'}
                                    </div>
                                )}
                                <div className="hidden md:flex flex-col items-start text-left">
                                    <span className="text-[12px] font-bold text-[#0f172a] leading-tight">{currentUser.name}</span>
                                    <span className="text-[10px] text-[#64748b] leading-tight font-medium">Administrator</span>
                                </div>
                                <ChevronDown className={`w-3.5 h-3.5 text-[#64748b] transition-transform ${profileMenuOpen ? 'rotate-180' : ''}`} />
                            </button>

                            {/* Dropdown Menu */}
                            {profileMenuOpen && (
                                <div className="absolute right-0 top-full mt-2 w-48 bg-white rounded-xl shadow-lg border border-[#e2e8f0] overflow-hidden animate-fade-in z-50">
                                    <div className="p-3 border-b border-[#e2e8f0] bg-[#f8fafc]">
                                        <p className="text-[12px] font-bold text-[#0f172a] truncate">{currentUser.name}</p>
                                        <p className="text-[10px] text-[#64748b] truncate">{currentUser.email}</p>
                                    </div>
                                    <div className="p-1.5">
                                        <Link
                                            href="/admin/profile"
                                            onClick={() => setProfileMenuOpen(false)}
                                            className="flex items-center gap-2 w-full px-3 py-2 text-[12px] font-semibold text-[#475569] hover:text-[#0284c7] hover:bg-[#e0f2fe] rounded-lg transition-colors"
                                        >
                                            <UserCheck className="w-4 h-4" /> My Profile
                                        </Link>
                                        <button
                                            type="button"
                                            onClick={handleLogout}
                                            className="flex items-center gap-2 w-full px-3 py-2 text-[12px] font-semibold text-rose-600 hover:bg-rose-50 rounded-lg transition-colors"
                                        >
                                            <LogOut className="w-4 h-4" /> Sign Out
                                        </button>
                                    </div>
                                </div>
                            )}
                        </div>
                    )}
                </div>
            </header>

            {/* 2. APP WORKSPACE CONTAINER */}
            <div className="flex flex-1 w-full min-h-0 relative overflow-hidden">
                {/* SIDEBAR NAVIGATION */}
                <aside
                    className={`fixed lg:static inset-y-0 left-0 z-30 w-64 bg-white/90 backdrop-blur-xl border-r border-[#e2e8f0] flex flex-col justify-between transition-transform duration-300 transform ${mobileSidebarOpen ? 'translate-x-0' : '-translate-x-full lg:translate-x-0'
                        }`}
                >
                    <div className="p-4 space-y-1 overflow-y-auto max-h-[calc(100vh-4rem)]">
                        <div className="px-3 py-2 text-[10px] font-bold uppercase tracking-widest text-[#94a3b8]">
                            Core Navigation
                        </div>

                        {/* Dashboard */}
                        <Link
                            href="/admin/dashboard"
                            className={`flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-[13px] font-bold transition-all ${activePage === 'dashboard'
                                ? 'bg-[#0284c7] text-white shadow-md shadow-[#0284c7]/20'
                                : 'text-[#475569] hover:bg-[#f1f5f9] hover:text-[#0f172a]'
                                }`}
                        >
                            <LayoutDashboard className="w-4 h-4" />
                            <span>Dashboard</span>
                        </Link>

                        {/* Orders Submenu */}
                        <div>
                            <button
                                type="button"
                                onClick={() => toggleSubmenu('orders')}
                                className={`w-full flex items-center justify-between px-3.5 py-2.5 rounded-xl text-[13px] font-bold transition-all ${['orders', 'create_order'].includes(activePage)
                                    ? 'bg-[#e0f2fe] text-[#0284c7]'
                                    : 'text-[#475569] hover:bg-[#f1f5f9]'
                                    }`}
                            >
                                <div className="flex items-center gap-3">
                                    <ShoppingCart className="w-4 h-4" />
                                    <span>Orders Management</span>
                                </div>
                                {openSubmenu === 'orders' ? (
                                    <ChevronDown className="w-4 h-4" />
                                ) : (
                                    <ChevronRight className="w-4 h-4" />
                                )}
                            </button>
                            {openSubmenu === 'orders' && (
                                <div className="pl-9 pr-2 py-1 space-y-1">
                                    <Link
                                        href="/admin/orders"
                                        className={`block px-3 py-2 rounded-lg text-[12px] font-semibold transition-all ${activePage === 'orders'
                                            ? 'bg-[#0284c7] text-white font-bold'
                                            : 'text-[#64748b] hover:bg-[#f8fafc] hover:text-[#0f172a]'
                                            }`}
                                    >
                                        Order List
                                    </Link>
                                    <Link
                                        href="/admin/create-order"
                                        className={`block px-3 py-2 rounded-lg text-[12px] font-semibold transition-all ${activePage === 'create_order'
                                            ? 'bg-[#0284c7] text-white font-bold'
                                            : 'text-[#64748b] hover:bg-[#f8fafc] hover:text-[#0f172a]'
                                            }`}
                                    >
                                        Create Order (POS)
                                    </Link>
                                </div>
                            )}
                        </div>

                        {/* Product Catalog Submenu */}
                        <div>
                            <button
                                type="button"
                                onClick={() => toggleSubmenu('product')}
                                className={`w-full flex items-center justify-between px-3.5 py-2.5 rounded-xl text-[13px] font-bold transition-all ${['products', 'product_add', 'categories', 'variants'].includes(activePage) || window.location.pathname.includes('/admin/variants')
                                    ? 'bg-[#e0f2fe] text-[#0284c7]'
                                    : 'text-[#475569] hover:bg-[#f1f5f9]'
                                    }`}
                            >
                                <div className="flex items-center gap-3">
                                    <Boxes className="w-4 h-4" />
                                    <span>Product Catalog</span>
                                </div>
                                {openSubmenu === 'product' ? (
                                    <ChevronDown className="w-4 h-4" />
                                ) : (
                                    <ChevronRight className="w-4 h-4" />
                                )}
                            </button>
                            {openSubmenu === 'product' && (
                                <div className="pl-9 pr-2 py-1 space-y-1">
                                    <Link
                                        href="/admin/products"
                                        className={`block px-3 py-2 rounded-lg text-[12px] font-semibold transition-all ${activePage === 'products'
                                            ? 'bg-[#0284c7] text-white font-bold'
                                            : 'text-[#64748b] hover:bg-[#f8fafc] hover:text-[#0f172a]'
                                            }`}
                                    >
                                        All Products
                                    </Link>
                                    <Link
                                        href="/admin/products/add"
                                        className={`block px-3 py-2 rounded-lg text-[12px] font-semibold transition-all ${activePage === 'product_add'
                                            ? 'bg-[#0284c7] text-white font-bold'
                                            : 'text-[#64748b] hover:bg-[#f8fafc] hover:text-[#0f172a]'
                                            }`}
                                    >
                                        Add New Product
                                    </Link>
                                    <Link
                                        href="/admin/categories"
                                        className={`block px-3 py-2 rounded-lg text-[12px] font-semibold transition-all ${activePage === 'categories'
                                            ? 'bg-[#0284c7] text-white font-bold'
                                            : 'text-[#64748b] hover:bg-[#f8fafc] hover:text-[#0f172a]'
                                            }`}
                                    >
                                        Categories & Subs
                                    </Link>
                                    <Link
                                        href="/admin/variants"
                                        className={`block px-3 py-2 rounded-lg text-[12px] font-semibold transition-all ${activePage === 'variants' || window.location.pathname.includes('/admin/variants')
                                            ? 'bg-[#0284c7] text-white font-bold'
                                            : 'text-[#64748b] hover:bg-[#f8fafc] hover:text-[#0f172a]'
                                            }`}
                                    >
                                        Product Variants
                                    </Link>
                                </div>
                            )}
                        </div>

                        {/* Inventory Purchases Submenu */}
                        <div>
                            <button
                                type="button"
                                onClick={() => toggleSubmenu('purchase')}
                                className={`w-full flex items-center justify-between px-3.5 py-2.5 rounded-xl text-[13px] font-bold transition-all ${['purchase_list', 'purchase_add'].includes(activePage)
                                    ? 'bg-[#e0f2fe] text-[#0284c7]'
                                    : 'text-[#475569] hover:bg-[#f1f5f9]'
                                    }`}
                            >
                                <div className="flex items-center gap-3">
                                    <ShoppingBag className="w-4 h-4" />
                                    <span>Purchases & Stock</span>
                                </div>
                                {openSubmenu === 'purchase' ? (
                                    <ChevronDown className="w-4 h-4" />
                                ) : (
                                    <ChevronRight className="w-4 h-4" />
                                )}
                            </button>
                            {openSubmenu === 'purchase' && (
                                <div className="pl-9 pr-2 py-1 space-y-1">
                                    <Link
                                        href="/admin/purchases/list"
                                        className={`block px-3 py-2 rounded-lg text-[12px] font-semibold transition-all ${activePage === 'purchase_list'
                                            ? 'bg-[#0284c7] text-white font-bold'
                                            : 'text-[#64748b] hover:bg-[#f8fafc] hover:text-[#0f172a]'
                                            }`}
                                    >
                                        Purchase History
                                    </Link>
                                    <Link
                                        href="/admin/purchases/add"
                                        className={`block px-3 py-2 rounded-lg text-[12px] font-semibold transition-all ${activePage === 'purchase_add'
                                            ? 'bg-[#0284c7] text-white font-bold'
                                            : 'text-[#64748b] hover:bg-[#f8fafc] hover:text-[#0f172a]'
                                            }`}
                                    >
                                        Add Purchase
                                    </Link>
                                </div>
                            )}
                        </div>

                        {/* CRM Section */}
                        <div className="pt-3 px-3 pb-1 text-[10px] font-bold uppercase tracking-widest text-[#94a3b8]">
                            CRM & Directory
                        </div>
                        <Link
                            href="/admin/customers"
                            className={`flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-[13px] font-bold transition-all ${activePage === 'customers'
                                ? 'bg-[#0284c7] text-white shadow-md shadow-[#0284c7]/20'
                                : 'text-[#475569] hover:bg-[#f1f5f9]'
                                }`}
                        >
                            <Users className="w-4 h-4" />
                            <span>Customers</span>
                        </Link>
                        <Link
                            href="/admin/suppliers"
                            className={`flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-[13px] font-bold transition-all ${activePage === 'suppliers'
                                ? 'bg-[#0284c7] text-white shadow-md shadow-[#0284c7]/20'
                                : 'text-[#475569] hover:bg-[#f1f5f9]'
                                }`}
                        >
                            <Briefcase className="w-4 h-4" />
                            <span>Suppliers</span>
                        </Link>

                        {/* Logistics / Courier Submenu */}
                        <div>
                            <button
                                type="button"
                                onClick={() => toggleSubmenu('courier')}
                                className={`w-full flex items-center justify-between px-3.5 py-2.5 rounded-xl text-[13px] font-bold transition-all ${['courier_charges', 'courier'].includes(activePage)
                                    ? 'bg-[#e0f2fe] text-[#0284c7]'
                                    : 'text-[#475569] hover:bg-[#f1f5f9]'
                                    }`}
                            >
                                <div className="flex items-center gap-3">
                                    <Truck className="w-4 h-4" />
                                    <span>Courier & Rates</span>
                                </div>
                                {openSubmenu === 'courier' ? (
                                    <ChevronDown className="w-4 h-4" />
                                ) : (
                                    <ChevronRight className="w-4 h-4" />
                                )}
                            </button>
                            {openSubmenu === 'courier' && (
                                <div className="pl-9 pr-2 py-1 space-y-1">
                                    <Link
                                        href="/admin/courier-charges"
                                        className={`block px-3 py-2 rounded-lg text-[12px] font-semibold transition-all ${activePage === 'courier_charges'
                                            ? 'bg-[#0284c7] text-white font-bold'
                                            : 'text-[#64748b] hover:bg-[#f8fafc] hover:text-[#0f172a]'
                                            }`}
                                    >
                                        Delivery Charges
                                    </Link>
                                    <Link
                                        href="/admin/courier"
                                        className={`block px-3 py-2 rounded-lg text-[12px] font-semibold transition-all ${activePage === 'courier'
                                            ? 'bg-[#0284c7] text-white font-bold'
                                            : 'text-[#64748b] hover:bg-[#f8fafc] hover:text-[#0f172a]'
                                            }`}
                                    >
                                        Courier Integrations
                                    </Link>
                                </div>
                            )}
                        </div>

                        {/* API Gateways Submenu */}
                        <div>
                            <button
                                type="button"
                                onClick={() => toggleSubmenu('api_gateway')}
                                className={`w-full flex items-center justify-between px-3.5 py-2.5 rounded-xl text-[13px] font-bold transition-all ${['api_payment', 'api_sms', 'api_courier'].includes(activePage)
                                    ? 'bg-[#e0f2fe] text-[#0284c7]'
                                    : 'text-[#475569] hover:bg-[#f1f5f9]'
                                    }`}
                            >
                                <div className="flex items-center gap-3">
                                    <Zap className="w-4 h-4" />
                                    <span>API Gateways</span>
                                </div>
                                {openSubmenu === 'api_gateway' ? (
                                    <ChevronDown className="w-4 h-4" />
                                ) : (
                                    <ChevronRight className="w-4 h-4" />
                                )}
                            </button>
                            {openSubmenu === 'api_gateway' && (
                                <div className="pl-9 pr-2 py-1 space-y-1">
                                    <Link
                                        href="/admin/api-settings/payment"
                                        className={`block px-3 py-2 rounded-lg text-[12px] font-semibold transition-all ${activePage === 'api_payment'
                                            ? 'bg-[#0284c7] text-white font-bold'
                                            : 'text-[#64748b] hover:bg-[#f8fafc] hover:text-[#0f172a]'
                                            }`}
                                    >
                                        Payment Gateways
                                    </Link>
                                    <Link
                                        href="/admin/api-settings/sms"
                                        className={`block px-3 py-2 rounded-lg text-[12px] font-semibold transition-all ${activePage === 'api_sms'
                                            ? 'bg-[#0284c7] text-white font-bold'
                                            : 'text-[#64748b] hover:bg-[#f8fafc] hover:text-[#0f172a]'
                                            }`}
                                    >
                                        SMS Gateways
                                    </Link>
                                </div>
                            )}
                        </div>

                        {/* SEO Submenu */}
                        <div>
                            <button
                                type="button"
                                onClick={() => toggleSubmenu('seo_sub')}
                                className={`w-full flex items-center justify-between px-3.5 py-2.5 rounded-xl text-[13px] font-bold transition-all ${['seo_meta', 'seo_marketing', 'seo_ping', 'sitemap', 'robots'].includes(activePage)
                                    ? 'bg-[#e0f2fe] text-[#0284c7]'
                                    : 'text-[#475569] hover:bg-[#f1f5f9]'
                                    }`}
                            >
                                <div className="flex items-center gap-3">
                                    <Globe className="w-4 h-4" />
                                    <span>SEO & Marketing</span>
                                </div>
                                {openSubmenu === 'seo_sub' ? (
                                    <ChevronDown className="w-4 h-4" />
                                ) : (
                                    <ChevronRight className="w-4 h-4" />
                                )}
                            </button>
                            {openSubmenu === 'seo_sub' && (
                                <div className="pl-9 pr-2 py-1 space-y-1">
                                    <Link
                                        href="/admin/seo/meta"
                                        className={`block px-3 py-2 rounded-lg text-[12px] font-semibold transition-all ${activePage === 'seo_meta'
                                            ? 'bg-[#0284c7] text-white font-bold'
                                            : 'text-[#64748b] hover:bg-[#f8fafc] hover:text-[#0f172a]'
                                            }`}
                                    >
                                        Meta & Social OpenGraph
                                    </Link>
                                    <Link
                                        href="/admin/seo/marketing"
                                        className={`block px-3 py-2 rounded-lg text-[12px] font-semibold transition-all ${activePage === 'seo_marketing'
                                            ? 'bg-[#0284c7] text-white font-bold'
                                            : 'text-[#64748b] hover:bg-[#f8fafc] hover:text-[#0f172a]'
                                            }`}
                                    >
                                        Pixels & Tracking
                                    </Link>
                                    <Link
                                        href="/admin/seo/ping"
                                        className={`block px-3 py-2 rounded-lg text-[12px] font-semibold transition-all ${activePage === 'seo_ping'
                                            ? 'bg-[#0284c7] text-white font-bold'
                                            : 'text-[#64748b] hover:bg-[#f8fafc] hover:text-[#0f172a]'
                                            }`}
                                    >
                                        Search Engine Pinger
                                    </Link>
                                </div>
                            )}
                        </div>

                        {/* RBAC & System Management */}
                        <div className="pt-3 px-3 pb-1 text-[10px] font-bold uppercase tracking-widest text-[#94a3b8]">
                            Administration & RBAC
                        </div>

                        <div>
                            <button
                                type="button"
                                onClick={() => toggleSubmenu('user_mgmt')}
                                className={`w-full flex items-center justify-between px-3.5 py-2.5 rounded-xl text-[13px] font-bold transition-all ${['users', 'roles'].includes(activePage)
                                    ? 'bg-[#e0f2fe] text-[#0284c7]'
                                    : 'text-[#475569] hover:bg-[#f1f5f9]'
                                    }`}
                            >
                                <div className="flex items-center gap-3">
                                    <ShieldCheck className="w-4 h-4" />
                                    <span>User & Role Access</span>
                                </div>
                                {openSubmenu === 'user_mgmt' ? (
                                    <ChevronDown className="w-4 h-4" />
                                ) : (
                                    <ChevronRight className="w-4 h-4" />
                                )}
                            </button>
                            {openSubmenu === 'user_mgmt' && (
                                <div className="pl-9 pr-2 py-1 space-y-1">
                                    <Link
                                        href="/admin/users"
                                        className={`block px-3 py-2 rounded-lg text-[12px] font-semibold transition-all ${activePage === 'users'
                                            ? 'bg-[#0284c7] text-white font-bold'
                                            : 'text-[#64748b] hover:bg-[#f8fafc] hover:text-[#0f172a]'
                                            }`}
                                    >
                                        Admin Users
                                    </Link>
                                    <Link
                                        href="/admin/roles"
                                        className={`block px-3 py-2 rounded-lg text-[12px] font-semibold transition-all ${activePage === 'roles'
                                            ? 'bg-[#0284c7] text-white font-bold'
                                            : 'text-[#64748b] hover:bg-[#f8fafc] hover:text-[#0f172a]'
                                            }`}
                                    >
                                        Roles & Permissions
                                    </Link>
                                </div>
                            )}
                        </div>

                        <div className="pt-3 px-3 pb-1 text-[10px] font-bold uppercase tracking-widest text-[#94a3b8]">
                            Storefront & CMS
                        </div>
                        <div>
                            <button
                                type="button"
                                onClick={() => toggleSubmenu('frontend_cms')}
                                className={`w-full flex items-center justify-between px-3.5 py-2.5 rounded-xl text-[13px] font-bold transition-all ${['cms', 'campaigns'].includes(activePage) || window.location.pathname.includes('/admin/campaigns')
                                    ? 'bg-[#e0f2fe] text-[#0284c7]'
                                    : 'text-[#475569] hover:bg-[#f1f5f9]'
                                    }`}
                            >
                                <div className="flex items-center gap-3">
                                    <LayoutTemplate className="w-4 h-4" />
                                    <span>Frontend CMS</span>
                                </div>
                                {openSubmenu === 'frontend_cms' ? (
                                    <ChevronDown className="w-4 h-4" />
                                ) : (
                                    <ChevronRight className="w-4 h-4" />
                                )}
                            </button>
                            {openSubmenu === 'frontend_cms' && (
                                <div className="pl-9 pr-2 py-1 space-y-1">
                                    <Link
                                        href="/admin/cms"
                                        className={`block px-3 py-2 rounded-lg text-[12px] font-semibold transition-all ${activePage === 'cms' || window.location.pathname === '/admin/cms'
                                            ? 'bg-[#0284c7] text-white font-bold'
                                            : 'text-[#64748b] hover:bg-[#f8fafc] hover:text-[#0f172a]'
                                            }`}
                                    >
                                        CMS Settings
                                    </Link>
                                    <Link
                                        href="/admin/campaigns"
                                        className={`block px-3 py-2 rounded-lg text-[12px] font-semibold transition-all ${activePage === 'campaigns' || window.location.pathname.includes('/admin/campaigns')
                                            ? 'bg-[#0284c7] text-white font-bold'
                                            : 'text-[#64748b] hover:bg-[#f8fafc] hover:text-[#0f172a]'
                                            }`}
                                    >
                                        Campaigns
                                    </Link>
                                </div>
                            )}
                        </div>
                        <Link
                            href="/admin/settings"
                            className={`flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-[13px] font-bold transition-all ${activePage === 'settings'
                                ? 'bg-[#0284c7] text-white shadow-md shadow-[#0284c7]/20'
                                : 'text-[#475569] hover:bg-[#f1f5f9]'
                                }`}
                        >
                            <Settings className="w-4 h-4" />
                            <span>System Settings</span>
                        </Link>
                    </div>
                </aside>

                {/* MAIN CONTENT AREA */}
                <main className="flex-1 p-6 lg:p-8 w-full space-y-6 relative z-10 overflow-y-auto max-h-[calc(100vh-3.5rem)]">
                    {/* Header Banner if Page Title Provided */}
                    {pageTitle && (
                        <header className="bg-white/90 backdrop-blur-xl border border-[#38bdf8]/30 rounded-2xl p-6 flex flex-col md:flex-row justify-between items-start md:items-center gap-4 shadow-sm">
                            <div>
                                <div className="flex items-center gap-2">
                                    <span className="text-[11px] font-bold uppercase tracking-widest text-[#0284c7]">
                                        ADMIN PANEL
                                    </span>
                                </div>
                                <h1 className="text-[22px] font-serif font-bold text-[#0f172a] uppercase tracking-tight">
                                    {pageTitle}
                                </h1>
                                {pageSubtitle && (
                                    <p className="text-[12px] text-[#64748b] mt-0.5">{pageSubtitle}</p>
                                )}
                            </div>
                            {headerActions && (
                                <div className="flex items-center gap-3">{headerActions}</div>
                            )}
                        </header>
                    )}

                    {/* FLASH MESSAGES */}
                    {flash?.success && (
                        <div className="p-4 bg-emerald-50 border border-emerald-300 text-emerald-800 text-[13px] rounded-xl flex items-center gap-3 font-semibold shadow-xs animate-fade-in">
                            <CheckCircle2 className="w-5 h-5 text-emerald-600 shrink-0" />
                            <span>{flash.success}</span>
                        </div>
                    )}
                    {flash?.error && (
                        <div className="p-4 bg-rose-50 border border-rose-300 text-rose-800 text-[13px] rounded-xl flex items-center gap-3 font-semibold shadow-xs animate-fade-in">
                            <AlertTriangle className="w-5 h-5 text-rose-600 shrink-0" />
                            <span>{flash.error}</span>
                        </div>
                    )}

                    {/* PAGE CONTENT */}
                    {children}

                    {/* FOOTER */}
                    <footer className="mt-auto pt-6 pb-8 border-t border-[#e2e8f0] text-[#64748b] text-[11px] flex flex-col sm:flex-row items-center justify-between gap-4">
                        <div className="flex items-center gap-2">
                            <span className="font-medium uppercase tracking-wider text-[#475569]">Developed By:</span>
                            <a href="https://www.itsolution.bd" target="_blank" rel="noreferrer" className="font-bold text-[#0f172a] hover:text-[#0284c7] transition-colors uppercase tracking-wider border-b border-[#cbd5e1] hover:border-[#0284c7] pb-0.5">
                                IT Solution
                            </a>
                        </div>

                        <div className="flex items-center gap-5 flex-wrap justify-center">


                            <a href="https://api.whatsapp.com/send/?phone=1682000977" target="_blank" rel="noreferrer" className="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-emerald-50 text-emerald-700 hover:bg-emerald-600 hover:text-white border border-emerald-200 transition-all font-bold text-[10.5px] uppercase tracking-wider shadow-2xs">
                                <svg className="w-3.5 h-3.5 fill-current" viewBox="0 0 24 24">
                                    <path d="M12.031 0C5.397 0 0 5.397 0 12.031c0 2.118.552 4.184 1.599 6.002L.062 24l6.126-1.503c1.765.962 3.76 1.47 5.843 1.47 6.634 0 12.031-5.397 12.031-12.031C24.062 5.397 18.665 0 12.031 0zm.007 22.029c-1.802 0-3.57-.484-5.112-1.401l-.367-.218-3.642.894.97-3.414-.239-.381A9.972 9.972 0 0 1 2.054 12.03c0-5.502 4.478-9.98 9.984-9.98 5.506 0 9.984 4.478 9.984 9.98 0 5.507-4.478 9.999-9.984 9.999zm5.474-7.489c-.3-.15-1.774-.875-2.049-.975-.275-.1-.475-.15-.675.15s-.775.975-.95 1.175-.35.225-.65.075c-.3-.15-1.267-.467-2.414-1.489-.892-.796-1.494-1.78-1.669-2.08-.175-.3-.019-.462.131-.611.135-.134.3-.35.45-.525.15-.175.2-.3.3-.5.1-.2.05-.375-.025-.525s-.675-1.625-.925-2.225c-.244-.583-.492-.504-.675-.513-.175-.008-.375-.01-.575-.01s-.525.075-.8.375c-.275.3-1.05 1.025-1.05 2.5s1.075 2.899 1.225 3.1c.15.2 2.115 3.23 5.124 4.53 3.009 1.3 3.009.867 3.559.817.55-.05 1.774-.725 2.024-1.425.25-.7.25-1.3.175-1.425-.075-.125-.275-.2-.575-.35z" />
                                </svg>
                                <span>WhatsApp</span>
                            </a>
                        </div>
                    </footer>

                </main>
            </div>
        </div>
    );
}
