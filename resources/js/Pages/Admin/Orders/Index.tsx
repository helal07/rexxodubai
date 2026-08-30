import React, { useState, useEffect } from 'react';
import { Head, Link, router, usePage } from '@inertiajs/react';
import AdminLayout from '@/Layouts/AdminLayout';
import {
    Search,
    Download,
    Filter as FilterIcon,
    ChevronDown,
    ChevronRight,
    RotateCcw,
    Mail,
    Phone,
    MapPin,
    Package,
    Truck,
    CheckCircle2,
    Clock,
    XCircle,
    FileText,
    Printer,
    MessageCircle,
    ExternalLink,
    Edit,
    Trash2,
    PlusCircle,
    X,
    Copy,
    Check
} from 'lucide-react';

interface OrderItem {
    id: number;
    product_id?: number;
    product_name: string;
    size: string | null;
    quantity: number;
    unit_price: string | number;
    total_price?: string | number;
}

interface Order {
    id: number;
    order_number: string;
    customer_name: string;
    customer_email: string;
    customer_phone: string;
    shipping_address: string;
    city: string;
    postal_code?: string;
    subtotal: string | number;
    shipping_cost: string | number;
    discount_amount: string | number;
    total_amount: string | number;
    status: string;
    payment_status: string;
    payment_method: string;
    courier_name?: string | null;
    courier_tracking_id?: string | null;
    courier_status?: string | null;
    created_at: string;
    items?: OrderItem[];
}

interface Courier {
    key: string;
    name: string;
    status: string;
}

interface OrdersProps {
    orders: {
        data: Order[];
        total: number;
        current_page: number;
        last_page: number;
        links: any[];
    } | Order[];
    activeCouriers: Courier[];
    siteSettings?: Record<string, string>;
    filters?: Record<string, string>;
    stats?: {
        total_count: number;
        shown_count: number;
        paid_count: number;
        filtered_total: number;
        all_paid_count: number;
        total_revenue: number;
    };
}

export default function OrdersIndex({
    orders,
    activeCouriers = [],
    siteSettings = {},
    filters = {},
    stats = { total_count: 0, shown_count: 0, paid_count: 0, filtered_total: 0, all_paid_count: 0, total_revenue: 0 }
}: OrdersProps) {
    const pageProps = usePage().props as any;
    const mergedSettings = {
        ...(pageProps.apiSettings || {}),
        ...(pageProps.siteSettings || {}),
        ...(siteSettings || {}),
    };

    const rawLogo = pageProps.cmsData?.global?.logo_url 
        || mergedSettings['site_logo'] 
        || mergedSettings['logo_url'] 
        || mergedSettings['logo'] 
        || mergedSettings['header_logo'] 
        || null;

    const formatImgSrc = (src: string | null | undefined) => {
        if (!src) return null;
        if (src.startsWith('http://') || src.startsWith('https://') || src.startsWith('data:')) {
            return src;
        }
        return src.startsWith('/') ? src : `/${src}`;
    };

    const storeLogoUrl = formatImgSrc(rawLogo);
    const storeName = mergedSettings['site_name'] || mergedSettings['siteName'] || 'RaxxO BD';
    const storeAddress = mergedSettings['address'] || mergedSettings['site_address'] || '';
    const storePhone = mergedSettings['phone'] || mergedSettings['site_phone'] || '';

    // Extract orders list whether paginated object or direct array
    const orderList: Order[] = Array.isArray(orders) ? orders : (orders?.data || []);
    const paginationLinks = !Array.isArray(orders) ? (orders?.links || []) : [];

    // Filter states
    const [search, setSearch] = useState(filters.search || '');
    const [status, setStatus] = useState(filters.status || 'all');
    const [paymentStatus, setPaymentStatus] = useState(filters.payment_status || 'all');
    const [paymentMethod, setPaymentMethod] = useState(filters.payment_method || 'all');
    const [courier, setCourier] = useState(filters.courier || 'all');
    const [dateRange, setDateRange] = useState(filters.date_range || 'all');
    const [minPrice, setMinPrice] = useState(filters.min_price || '');
    const [maxPrice, setMaxPrice] = useState(filters.max_price || '');

    const [showFilters, setShowFilters] = useState(true);
    const [selectedOrderIds, setSelectedOrderIds] = useState<number[]>([]);
    const [copiedPhone, setCopiedPhone] = useState<string | null>(null);

    // Modal states
    const [invoiceModalOrder, setInvoiceModalOrder] = useState<Order | null>(null);
    const [dispatchModalOrder, setDispatchModalOrder] = useState<Order | null>(null);
    const [selectedCourier, setSelectedCourier] = useState('');
    const [isDispatching, setIsDispatching] = useState(false);

    // Currency Formatting
    const currency = mergedSettings?.currency || siteSettings?.currency || 'BDT (৳)';
    const symbolMatch = currency.match(/\((.*?)\)/);
    const symbol = symbolMatch ? symbolMatch[1] : (currency.split(' ')[0] || '৳');

    // Debounced filter submission
    const applyFilters = () => {
        router.get('/admin/orders', {
            search: search || undefined,
            status: status !== 'all' ? status : undefined,
            payment_status: paymentStatus !== 'all' ? paymentStatus : undefined,
            payment_method: paymentMethod !== 'all' ? paymentMethod : undefined,
            courier: courier !== 'all' ? courier : undefined,
            date_range: dateRange !== 'all' ? dateRange : undefined,
            min_price: minPrice || undefined,
            max_price: maxPrice || undefined,
        }, {
            preserveState: true,
            preserveScroll: true,
        });
    };

    const resetFilters = () => {
        setSearch('');
        setStatus('all');
        setPaymentStatus('all');
        setPaymentMethod('all');
        setCourier('all');
        setDateRange('all');
        setMinPrice('');
        setMaxPrice('');
        router.get('/admin/orders', {}, { preserveState: true, preserveScroll: true });
    };

    // Quick Status Update
    const handleStatusUpdate = (id: number, newStatus: string) => {
        router.post(`/admin/orders/${id}/status`, { status: newStatus }, {
            preserveScroll: true,
            onSuccess: () => {
                if (invoiceModalOrder && invoiceModalOrder.id === id) {
                    setInvoiceModalOrder({ ...invoiceModalOrder, status: newStatus });
                }
            }
        });
    };

    // Single order delete
    const handleDeleteOrder = (id: number, orderNumber: string) => {
        if (confirm(`Are you sure you want to delete Order #${orderNumber}?`)) {
            router.delete(`/admin/orders/${id}`, { preserveScroll: true });
        }
    };

    // Dispatch to courier
    const handleCourierDispatch = (e: React.FormEvent) => {
        e.preventDefault();
        if (!dispatchModalOrder || !selectedCourier) return;

        setIsDispatching(true);
        router.post('/admin/courier/dispatch', {
            order_id: dispatchModalOrder.id,
            provider: selectedCourier,
        }, {
            preserveScroll: true,
            onFinish: () => {
                setIsDispatching(false);
                setDispatchModalOrder(null);
                setSelectedCourier('');
            }
        });
    };

    // Copy to clipboard helper
    const handleCopy = (text: string) => {
        navigator.clipboard.writeText(text);
        setCopiedPhone(text);
        setTimeout(() => setCopiedPhone(null), 2000);
    };

    // Export to CSV
    const exportToCSV = () => {
        const headers = ['Order Number', 'Date', 'Customer Name', 'Phone', 'Email', 'Address', 'City', 'Items', 'Total Amount', 'Status', 'Payment Status', 'Payment Method', 'Courier', 'Tracking ID'];
        
        const rows = orderList.map(o => [
            o.order_number,
            new Date(o.created_at).toLocaleDateString(),
            `"${o.customer_name?.replace(/"/g, '""') || ''}"`,
            `"${o.customer_phone || ''}"`,
            `"${o.customer_email || ''}"`,
            `"${o.shipping_address?.replace(/"/g, '""') || ''}"`,
            `"${o.city || ''}"`,
            `"${(o.items || []).map(i => `${i.product_name} x ${i.quantity}`).join('; ')}"`,
            o.total_amount,
            o.status,
            o.payment_status,
            o.payment_method,
            o.courier_name || '',
            o.courier_tracking_id || ''
        ]);

        const csvContent = 'data:text/csv;charset=utf-8,' + [headers.join(','), ...rows.map(r => r.join(','))].join('\n');
        const encodedUri = encodeURI(csvContent);
        const link = document.createElement('a');
        link.setAttribute('href', encodedUri);
        link.setAttribute('download', `orders_export_${new Date().toISOString().slice(0, 10)}.csv`);
        document.body.appendChild(link);
        link.click();
        document.body.removeChild(link);
    };

    // Status Badge Helpers
    const getStatusBadge = (orderStatus: string) => {
        const s = (orderStatus || '').toLowerCase();
        switch (s) {
            case 'pending':
                return { bg: 'bg-amber-50 text-amber-700 border-amber-200', dot: 'bg-amber-500', label: 'Pending' };
            case 'confirmed':
                return { bg: 'bg-blue-50 text-blue-700 border-blue-200', dot: 'bg-blue-500', label: 'Confirmed' };
            case 'processing':
                return { bg: 'bg-indigo-50 text-indigo-700 border-indigo-200', dot: 'bg-indigo-500', label: 'Processing' };
            case 'dispatched':
            case 'in courier':
            case 'on the way':
                return { bg: 'bg-purple-50 text-purple-700 border-purple-200', dot: 'bg-purple-500', label: 'In Courier' };
            case 'completed':
            case 'delivered':
            case 'successful':
                return { bg: 'bg-emerald-50 text-emerald-700 border-emerald-200', dot: 'bg-emerald-500', label: 'Delivered' };
            case 'cancelled':
                return { bg: 'bg-rose-50 text-rose-700 border-rose-200', dot: 'bg-rose-500', label: 'Cancelled' };
            case 'returned':
                return { bg: 'bg-slate-100 text-slate-700 border-slate-300', dot: 'bg-slate-500', label: 'Returned' };
            default:
                return { bg: 'bg-slate-50 text-slate-700 border-slate-200', dot: 'bg-slate-400', label: orderStatus };
        }
    };

    const getPaymentBadge = (pStatus: string) => {
        const s = (pStatus || '').toLowerCase();
        switch (s) {
            case 'paid':
                return 'bg-emerald-50 text-emerald-700 border-emerald-200';
            case 'cod_pending':
            case 'pending':
                return 'bg-amber-50 text-amber-700 border-amber-200';
            case 'failed':
                return 'bg-rose-50 text-rose-700 border-rose-200';
            default:
                return 'bg-slate-50 text-slate-700 border-slate-200';
        }
    };

    // WhatsApp Invoice helper
    const sendWhatsApp = (order: Order) => {
        let phone = (order.customer_phone || '').replace(/\D/g, '');
        if (phone.length === 11 && phone.startsWith('01')) {
            phone = '88' + phone;
        }
        const storeName = siteSettings['site_name'] || 'Our Store';
        const msg = `Hello ${order.customer_name},\n\nYour order #${order.order_number} from *${storeName}* is confirmed!\n\n*Total Amount:* ${symbol}${Number(order.total_amount).toLocaleString()}\n*Payment Method:* ${order.payment_method?.toUpperCase()}\n*Status:* ${order.status?.toUpperCase()}\n\nThank you for shopping with us!`;
        window.open(`https://wa.me/${phone}?text=${encodeURIComponent(msg)}`, '_blank');
    };

    // Calculate displayed statistics
    const totalShownCount = stats?.shown_count ?? orderList.length;
    const totalPaidCount = stats?.paid_count ?? orderList.filter(o => o.payment_status?.toLowerCase() === 'paid').length;
    const totalRevenueAmount = stats?.filtered_total ?? orderList.reduce((sum, o) => sum + Number(o.total_amount || 0), 0);

    return (
        <AdminLayout activePage="orders">
            <Head title="Orders · All orders — Admin" />

            {/* TOP HEADER SECTION */}
            <div className="flex flex-col md:flex-row justify-between items-start md:items-center gap-4 mb-6">
                <div>
                    <h1 className="text-2xl md:text-3xl font-black text-slate-900 tracking-tight flex items-center gap-2">
                        <span>Orders</span>
                        <span className="text-slate-400 font-normal">·</span>
                        <span className="text-slate-700 capitalize">{status === 'all' ? 'All orders' : status}</span>
                    </h1>
                    <p className="text-sm font-medium text-slate-500 mt-1">
                        <strong className="text-slate-800">{totalShownCount} shown</strong> ·{' '}
                        <span className="text-emerald-600 font-bold">{totalPaidCount} paid</span> ·{' '}
                        <strong className="text-slate-900">{symbol}{Number(totalRevenueAmount).toLocaleString(undefined, { minimumFractionDigits: 2, maximumFractionDigits: 2 })} total</strong>
                    </p>
                </div>

                <div className="flex items-center gap-2.5 flex-wrap">
                    <button
                        type="button"
                        onClick={exportToCSV}
                        className="px-3.5 py-2 bg-white hover:bg-slate-50 text-slate-700 border border-slate-300 rounded-xl text-xs font-bold transition-all shadow-sm flex items-center gap-1.5"
                    >
                        <Download size={14} className="text-slate-500" />
                        Export CSV
                    </button>
                    <button
                        type="button"
                        onClick={() => setShowFilters(!showFilters)}
                        className={`px-3.5 py-2 rounded-xl text-xs font-bold transition-all shadow-sm flex items-center gap-1.5 border ${
                            showFilters ? 'bg-slate-900 text-white border-slate-900' : 'bg-white text-slate-700 border-slate-300 hover:bg-slate-50'
                        }`}
                    >
                        <FilterIcon size={14} />
                        Filters
                    </button>
                    <Link
                        href="/admin/create-order"
                        className="px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white rounded-xl text-xs font-bold transition-all shadow-sm flex items-center gap-1.5"
                    >
                        <PlusCircle size={15} />
                        Create Order
                    </Link>
                </div>
            </div>

            {/* SEARCH AND FILTER BAR */}
            {showFilters && (
                <div className="bg-white border border-slate-200 rounded-2xl p-4 shadow-sm mb-6 space-y-4">
                    {/* Main Search Input */}
                    <div className="relative">
                        <Search className="absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-400 w-4 h-4" />
                        <input
                            type="text"
                            placeholder="Search by order #, customer name, email, phone, city, tracking..."
                            value={search}
                            onChange={e => setSearch(e.target.value)}
                            onKeyDown={e => e.key === 'Enter' && applyFilters()}
                            className="w-full pl-10 pr-24 py-2.5 bg-slate-50 hover:bg-white focus:bg-white border border-slate-200 rounded-xl text-xs font-medium focus:border-indigo-600 focus:ring-1 focus:ring-indigo-600 outline-none transition-all placeholder:text-slate-400"
                        />
                        <button
                            type="button"
                            onClick={applyFilters}
                            className="absolute right-2 top-1/2 -translate-y-1/2 px-3 py-1.5 bg-indigo-600 text-white rounded-lg text-xs font-bold hover:bg-indigo-700 transition-colors"
                        >
                            Search
                        </button>
                    </div>

                    {/* Filter Dropdowns Grid */}
                    <div className="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-6 gap-3 pt-1">
                        {/* Status Filter */}
                        <div>
                            <label className="block text-[10px] font-extrabold uppercase tracking-wider text-slate-400 mb-1">Status</label>
                            <select
                                value={status}
                                onChange={e => { setStatus(e.target.value); }}
                                className="w-full border border-slate-200 rounded-xl px-2.5 py-1.5 text-xs bg-white text-slate-700 font-semibold focus:border-indigo-600 outline-none cursor-pointer"
                            >
                                <option value="all">All statuses</option>
                                <option value="pending">Pending</option>
                                <option value="confirmed">Confirmed</option>
                                <option value="processing">Processing</option>
                                <option value="dispatched">Dispatched</option>
                                <option value="completed">Completed / Delivered</option>
                                <option value="cancelled">Cancelled</option>
                                <option value="returned">Returned</option>
                            </select>
                        </div>

                        {/* Payment Status Filter */}
                        <div>
                            <label className="block text-[10px] font-extrabold uppercase tracking-wider text-slate-400 mb-1">Payment</label>
                            <select
                                value={paymentStatus}
                                onChange={e => { setPaymentStatus(e.target.value); }}
                                className="w-full border border-slate-200 rounded-xl px-2.5 py-1.5 text-xs bg-white text-slate-700 font-semibold focus:border-indigo-600 outline-none cursor-pointer"
                            >
                                <option value="all">All payments</option>
                                <option value="paid">Paid</option>
                                <option value="pending">Pending</option>
                                <option value="cod_pending">COD Pending</option>
                                <option value="failed">Failed</option>
                            </select>
                        </div>

                        {/* Payment Method Filter */}
                        <div>
                            <label className="block text-[10px] font-extrabold uppercase tracking-wider text-slate-400 mb-1">Method</label>
                            <select
                                value={paymentMethod}
                                onChange={e => { setPaymentMethod(e.target.value); }}
                                className="w-full border border-slate-200 rounded-xl px-2.5 py-1.5 text-xs bg-white text-slate-700 font-semibold focus:border-indigo-600 outline-none cursor-pointer"
                            >
                                <option value="all">All methods</option>
                                <option value="cod">Cash on Delivery (COD)</option>
                                <option value="bkash">bKash</option>
                                <option value="sslcommerz">SSLCommerz</option>
                                <option value="nagad">Nagad</option>
                            </select>
                        </div>

                        {/* Courier Filter */}
                        <div>
                            <label className="block text-[10px] font-extrabold uppercase tracking-wider text-slate-400 mb-1">Courier</label>
                            <select
                                value={courier}
                                onChange={e => { setCourier(e.target.value); }}
                                className="w-full border border-slate-200 rounded-xl px-2.5 py-1.5 text-xs bg-white text-slate-700 font-semibold focus:border-indigo-600 outline-none cursor-pointer capitalize"
                            >
                                <option value="all">All couriers</option>
                                {activeCouriers.map(c => (
                                    <option key={c.key} value={c.name}>{c.name}</option>
                                ))}
                            </select>
                        </div>

                        {/* Date Range Filter */}
                        <div>
                            <label className="block text-[10px] font-extrabold uppercase tracking-wider text-slate-400 mb-1">Date</label>
                            <select
                                value={dateRange}
                                onChange={e => { setDateRange(e.target.value); }}
                                className="w-full border border-slate-200 rounded-xl px-2.5 py-1.5 text-xs bg-white text-slate-700 font-semibold focus:border-indigo-600 outline-none cursor-pointer"
                            >
                                <option value="all">All time</option>
                                <option value="today">Today</option>
                                <option value="yesterday">Yesterday</option>
                                <option value="7days">Last 7 days</option>
                                <option value="30days">Last 30 days</option>
                                <option value="this_month">This Month</option>
                            </select>
                        </div>

                        {/* Min / Max Price Range */}
                        <div className="flex items-center gap-1.5">
                            <div className="flex-1">
                                <label className="block text-[10px] font-extrabold uppercase tracking-wider text-slate-400 mb-1">Min {symbol}</label>
                                <input
                                    type="number"
                                    placeholder="0"
                                    value={minPrice}
                                    onChange={e => setMinPrice(e.target.value)}
                                    className="w-full border border-slate-200 rounded-xl px-2 py-1.5 text-xs bg-white focus:border-indigo-600 outline-none"
                                />
                            </div>
                            <div className="flex-1">
                                <label className="block text-[10px] font-extrabold uppercase tracking-wider text-slate-400 mb-1">Max {symbol}</label>
                                <input
                                    type="number"
                                    placeholder="∞"
                                    value={maxPrice}
                                    onChange={e => setMaxPrice(e.target.value)}
                                    className="w-full border border-slate-200 rounded-xl px-2 py-1.5 text-xs bg-white focus:border-indigo-600 outline-none"
                                />
                            </div>
                        </div>
                    </div>

                    {/* Filter Actions */}
                    <div className="flex justify-between items-center pt-2 border-t border-slate-100">
                        <button
                            type="button"
                            onClick={applyFilters}
                            className="px-4 py-1.5 bg-indigo-600 hover:bg-indigo-700 text-white rounded-lg text-xs font-bold transition-all shadow-sm"
                        >
                            Apply Filters
                        </button>
                        <button
                            type="button"
                            onClick={resetFilters}
                            className="text-xs text-slate-500 hover:text-rose-600 font-semibold flex items-center gap-1 transition-colors"
                        >
                            <RotateCcw size={12} />
                            Reset filters
                        </button>
                    </div>
                </div>
            )}

            {/* ORDERS TABLE SECTION */}
            <div className="bg-white border border-slate-200 rounded-2xl shadow-sm overflow-hidden mb-6">
                <div className="overflow-x-auto">
                    <table className="w-full text-left border-collapse">
                        <thead>
                            <tr className="bg-slate-50/80 border-b border-slate-200 text-slate-500 text-[11px] font-extrabold uppercase tracking-wider">
                                <th className="px-4 py-3.5 w-10 text-center">
                                    <input
                                        type="checkbox"
                                        checked={selectedOrderIds.length > 0 && selectedOrderIds.length === orderList.length}
                                        onChange={e => {
                                            if (e.target.checked) {
                                                setSelectedOrderIds(orderList.map(o => o.id));
                                            } else {
                                                setSelectedOrderIds([]);
                                            }
                                        }}
                                        className="rounded border-slate-300 text-indigo-600 focus:ring-indigo-500 w-4 h-4 cursor-pointer"
                                    />
                                </th>
                                <th className="px-4 py-3.5 font-bold">Order / Customer</th>
                                <th className="px-4 py-3.5 font-bold">Items</th>
                                <th className="px-4 py-3.5 font-bold">Status</th>
                                <th className="px-4 py-3.5 font-bold">Payment</th>
                                <th className="px-4 py-3.5 font-bold text-right">Amount</th>
                                <th className="px-4 py-3.5 font-bold text-right">Actions</th>
                            </tr>
                        </thead>

                        <tbody className="divide-y divide-slate-100">
                            {orderList.map((order) => {
                                const isSelected = selectedOrderIds.includes(order.id);
                                const statusBadge = getStatusBadge(order.status);
                                const paymentBadge = getPaymentBadge(order.payment_status);

                                return (
                                    <tr
                                        key={order.id}
                                        className={`group hover:bg-slate-50/70 transition-colors ${isSelected ? 'bg-indigo-50/30' : ''}`}
                                    >
                                        {/* Multi-select Checkbox */}
                                        <td className="px-4 py-4 text-center">
                                            <input
                                                type="checkbox"
                                                checked={isSelected}
                                                onChange={e => {
                                                    if (e.target.checked) {
                                                        setSelectedOrderIds([...selectedOrderIds, order.id]);
                                                    } else {
                                                        setSelectedOrderIds(selectedOrderIds.filter(id => id !== order.id));
                                                    }
                                                }}
                                                className="rounded border-slate-300 text-indigo-600 focus:ring-indigo-500 w-4 h-4 cursor-pointer"
                                            />
                                        </td>

                                        {/* ORDER / CUSTOMER INFO */}
                                        <td className="px-4 py-4 min-w-[280px]">
                                            <div className="space-y-1">
                                                <div className="flex items-center gap-2">
                                                    <span 
                                                        onClick={() => setInvoiceModalOrder(order)}
                                                        className="font-mono text-xs font-bold text-slate-900 hover:text-indigo-600 cursor-pointer flex items-center gap-1 group/ord"
                                                    >
                                                        #{order.order_number}
                                                        <FileText size={12} className="opacity-0 group-hover/ord:opacity-100 text-indigo-600 transition-opacity" />
                                                    </span>
                                                    <span className="text-[11px] text-slate-400 font-medium">
                                                        {new Date(order.created_at).toLocaleDateString(undefined, { month: 'numeric', day: 'numeric', year: 'numeric' })}
                                                    </span>
                                                </div>

                                                <div className="font-bold text-sm text-slate-900 line-clamp-1">
                                                    {order.customer_name}
                                                </div>

                                                <div className="flex items-center gap-3 text-xs text-slate-500 flex-wrap">
                                                    {order.customer_email && (
                                                        <span className="flex items-center gap-1 text-[11px] text-slate-500 truncate max-w-[160px]">
                                                            <Mail size={12} className="text-slate-400 flex-shrink-0" />
                                                            {order.customer_email}
                                                        </span>
                                                    )}
                                                    {order.customer_phone && (
                                                        <button
                                                            type="button"
                                                            onClick={() => handleCopy(order.customer_phone)}
                                                            className="flex items-center gap-1 text-[11px] font-mono text-slate-600 hover:text-indigo-600 transition-colors"
                                                            title="Click to copy phone number"
                                                        >
                                                            <Phone size={12} className="text-slate-400 flex-shrink-0" />
                                                            {order.customer_phone}
                                                            {copiedPhone === order.customer_phone ? (
                                                                <Check size={10} className="text-emerald-600" />
                                                            ) : (
                                                                <Copy size={10} className="opacity-40" />
                                                            )}
                                                        </button>
                                                    )}
                                                </div>

                                                {order.shipping_address && (
                                                    <div className="flex items-start gap-1 text-[11px] text-slate-500 line-clamp-1">
                                                        <MapPin size={12} className="text-slate-400 flex-shrink-0 mt-0.5" />
                                                        <span>{order.shipping_address}{order.city ? `, ${order.city}` : ''}</span>
                                                    </div>
                                                )}
                                            </div>
                                        </td>

                                        {/* ITEMS LIST */}
                                        <td className="px-4 py-4 min-w-[220px]">
                                            <div className="space-y-1.5">
                                                {(order.items || []).slice(0, 3).map((item, idx) => (
                                                    <div key={idx} className="flex items-center gap-2 text-xs text-slate-700">
                                                        <Package size={13} className="text-slate-400 flex-shrink-0" />
                                                        <span className="font-medium truncate max-w-[180px]">
                                                            {item.product_name}
                                                        </span>
                                                        {item.size && item.size !== 'Default' && (
                                                            <span className="text-[10px] bg-slate-100 text-slate-600 px-1.5 py-0.5 rounded">
                                                                {item.size}
                                                            </span>
                                                        )}
                                                        <span className="text-slate-400 font-bold text-[11px]">
                                                            × {item.quantity}
                                                        </span>
                                                    </div>
                                                ))}
                                                {(order.items || []).length > 3 && (
                                                    <div className="text-[11px] text-indigo-600 font-semibold pl-5">
                                                        +{(order.items || []).length - 3} more items
                                                    </div>
                                                )}
                                                {(!order.items || order.items.length === 0) && (
                                                    <span className="text-xs text-slate-400 italic">No item details</span>
                                                )}
                                            </div>
                                        </td>

                                        {/* STATUS DROPDOWN & BADGE */}
                                        <td className="px-4 py-4 min-w-[140px]">
                                            <div className="space-y-1.5">
                                                <div className="relative inline-block">
                                                    <select
                                                        value={order.status}
                                                        onChange={e => handleStatusUpdate(order.id, e.target.value)}
                                                        className={`text-xs font-bold px-2.5 py-1 rounded-lg border appearance-none pr-6 cursor-pointer outline-none transition-all ${statusBadge.bg}`}
                                                    >
                                                        <option value="pending">Pending</option>
                                                        <option value="confirmed">Confirmed</option>
                                                        <option value="processing">Processing</option>
                                                        <option value="dispatched">Dispatched</option>
                                                        <option value="completed">Delivered</option>
                                                        <option value="cancelled">Cancelled</option>
                                                        <option value="returned">Returned</option>
                                                    </select>
                                                    <ChevronDown size={12} className="absolute right-1.5 top-1/2 -translate-y-1/2 pointer-events-none opacity-60" />
                                                </div>

                                                {order.status?.toLowerCase() === 'processing' && (
                                                    <div className="flex items-center gap-1 text-[10px] font-extrabold uppercase text-indigo-600 tracking-wider">
                                                        <Clock size={10} />
                                                        <span>Processing &gt;</span>
                                                    </div>
                                                )}

                                                {order.courier_name && (
                                                    <div className="flex items-center gap-1 text-[10px] font-bold text-slate-600">
                                                        <Truck size={10} className="text-purple-600" />
                                                        <span>{order.courier_name}</span>
                                                        {order.courier_tracking_id && (
                                                            <span className="font-mono text-[9px] text-slate-400">({order.courier_tracking_id})</span>
                                                        )}
                                                    </div>
                                                )}
                                            </div>
                                        </td>

                                        {/* PAYMENT STATUS & METHOD */}
                                        <td className="px-4 py-4 min-w-[130px]">
                                            <div className="space-y-1">
                                                <span className={`inline-block text-[11px] font-bold px-2.5 py-0.5 rounded-md border uppercase ${paymentBadge}`}>
                                                    {order.payment_status || 'Pending'}
                                                </span>
                                                <div className="text-[10px] font-extrabold text-slate-500 uppercase tracking-wider">
                                                    {order.payment_method || 'COD'}
                                                </div>
                                            </div>
                                        </td>

                                        {/* AMOUNT */}
                                        <td className="px-4 py-4 text-right whitespace-nowrap">
                                            <div className="font-black text-sm text-slate-900 font-mono">
                                                {symbol}{Number(order.total_amount).toLocaleString(undefined, { minimumFractionDigits: 2, maximumFractionDigits: 2 })}
                                            </div>
                                        </td>

                                        {/* ACTIONS & INVOICE BUTTON */}
                                        <td className="px-4 py-4 text-right whitespace-nowrap">
                                            <div className="flex items-center justify-end gap-1.5">
                                                {/* Assign Courier button */}
                                                {!order.courier_tracking_id ? (
                                                    <button
                                                        type="button"
                                                        onClick={() => {
                                                            setDispatchModalOrder(order);
                                                            if (activeCouriers.length > 0) setSelectedCourier(activeCouriers[0].key);
                                                        }}
                                                        className="px-2.5 py-1.5 bg-slate-100 hover:bg-purple-50 hover:text-purple-700 text-slate-700 border border-slate-200 rounded-lg text-[11px] font-bold flex items-center gap-1 transition-all"
                                                        title="Assign to Courier"
                                                    >
                                                        <Truck size={12} />
                                                        <span className="hidden xl:inline">Assign Courier</span>
                                                    </button>
                                                ) : (
                                                    <span className="px-2 py-1 bg-purple-50 text-purple-700 border border-purple-200 rounded-lg text-[10px] font-bold flex items-center gap-1">
                                                        <Truck size={10} /> Dispatched
                                                    </span>
                                                )}

                                                {/* On click invoice show modal */}
                                                <button
                                                    type="button"
                                                    onClick={() => setInvoiceModalOrder(order)}
                                                    className="p-1.5 text-indigo-600 hover:text-indigo-800 hover:bg-indigo-50 rounded-lg border border-indigo-100 transition-colors"
                                                    title="Quick Invoice Preview"
                                                >
                                                    <FileText size={15} />
                                                </button>

                                                {/* Direct link to invoice page */}
                                                <Link
                                                    href={`/admin/orders/${order.id}/invoice`}
                                                    className="p-1.5 text-slate-500 hover:text-slate-800 hover:bg-slate-100 rounded-lg transition-colors"
                                                    title="Full Invoice Page"
                                                >
                                                    <ExternalLink size={14} />
                                                </Link>

                                                {/* Edit order */}
                                                <Link
                                                    href={`/admin/orders/${order.id}/edit`}
                                                    className="p-1.5 text-slate-500 hover:text-slate-800 hover:bg-slate-100 rounded-lg transition-colors"
                                                    title="Edit Order"
                                                >
                                                    <Edit size={14} />
                                                </Link>

                                                {/* Delete order */}
                                                <button
                                                    type="button"
                                                    onClick={() => handleDeleteOrder(order.id, order.order_number)}
                                                    className="p-1.5 text-rose-400 hover:text-rose-600 hover:bg-rose-50 rounded-lg transition-colors"
                                                    title="Delete Order"
                                                >
                                                    <Trash2 size={14} />
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                );
                            })}

                            {orderList.length === 0 && (
                                <tr>
                                    <td colSpan={7} className="px-6 py-12 text-center text-slate-400 text-sm">
                                        <Package className="w-10 h-10 mx-auto text-slate-300 mb-2" />
                                        No orders found matching your search or filters.
                                    </td>
                                </tr>
                            )}
                        </tbody>
                    </table>
                </div>
            </div>

            {/* PAGINATION */}
            {paginationLinks && paginationLinks.length > 3 && (
                <div className="flex justify-between items-center flex-wrap gap-4 pb-12">
                    <div className="text-xs text-slate-500 font-medium">
                        Showing page {orders && !Array.isArray(orders) ? orders.current_page : 1} of {orders && !Array.isArray(orders) ? orders.last_page : 1}
                    </div>
                    <div className="flex gap-1">
                        {paginationLinks.map((link: any, idx: number) => (
                            <Link
                                key={idx}
                                href={link.url || '#'}
                                className={`px-3 py-1.5 text-xs rounded-xl border font-bold transition-all ${
                                    link.active
                                        ? 'bg-slate-900 text-white border-slate-900 shadow-sm'
                                        : 'bg-white text-slate-700 border-slate-200 hover:bg-slate-50'
                                } ${!link.url ? 'opacity-40 cursor-not-allowed pointer-events-none' : ''}`}
                                dangerouslySetInnerHTML={{ __html: link.label }}
                            />
                        ))}
                    </div>
                </div>
            )}

            {/* ON-CLICK INVOICE POPUP MODAL */}
            {invoiceModalOrder && (
                <div className="fixed inset-0 bg-black/60 backdrop-blur-sm z-50 flex items-center justify-center p-4 overflow-y-auto animate-in fade-in duration-200">
                    <div className="bg-white rounded-3xl shadow-2xl max-w-2xl w-full max-h-[90vh] overflow-y-auto border border-slate-200 flex flex-col">
                        {/* Modal Header */}
                        <div className="p-6 border-b border-slate-100 flex justify-between items-center sticky top-0 bg-white/95 backdrop-blur z-10">
                            <div>
                                <h3 className="text-lg font-black text-slate-900 flex items-center gap-2">
                                    <span>Invoice</span>
                                    <span className="text-indigo-600 font-mono">#{invoiceModalOrder.order_number}</span>
                                </h3>
                                <p className="text-xs text-slate-500 mt-0.5">
                                    Placed on {new Date(invoiceModalOrder.created_at).toLocaleString()}
                                </p>
                            </div>
                            <div className="flex items-center gap-2">
                                <button
                                    type="button"
                                    onClick={() => sendWhatsApp(invoiceModalOrder)}
                                    className="p-2 bg-emerald-50 text-emerald-600 hover:bg-emerald-100 rounded-xl transition-colors"
                                    title="Send Invoice on WhatsApp"
                                >
                                    <MessageCircle size={16} />
                                </button>
                                <Link
                                    href={`/admin/orders/${invoiceModalOrder.id}/invoice`}
                                    target="_blank"
                                    className="p-2 bg-slate-100 hover:bg-slate-200 text-slate-700 rounded-xl transition-colors"
                                    title="Printable Full Invoice"
                                >
                                    <Printer size={16} />
                                </Link>
                                <button
                                    type="button"
                                    onClick={() => setInvoiceModalOrder(null)}
                                    className="p-2 hover:bg-slate-100 text-slate-400 hover:text-slate-700 rounded-xl transition-colors"
                                >
                                    <X size={18} />
                                </button>
                            </div>
                        </div>

                        {/* Modal Body: Complete Invoice Details */}
                        <div className="p-6 space-y-6 flex-1 text-slate-800">
                            {/* Store Header / Dynamic Logo */}
                            <div className="flex justify-between items-start border-b border-slate-100 pb-5">
                                <div>
                                    {storeLogoUrl ? (
                                        <img src={storeLogoUrl} alt={storeName} className="h-10 object-contain mb-2" />
                                    ) : (
                                        <div className="text-xl font-black text-slate-900 mb-1 flex items-center gap-2">
                                            <Package className="w-5 h-5 text-indigo-600" />
                                            {storeName}
                                        </div>
                                    )}
                                    {storeAddress && (
                                        <div className="flex items-center gap-1 text-xs text-slate-500 max-w-sm">
                                            <MapPin size={12} className="text-slate-400 shrink-0" />
                                            <span>{storeAddress}</span>
                                        </div>
                                    )}
                                    {storePhone && (
                                        <div className="flex items-center gap-1 text-xs text-slate-500 mt-0.5">
                                            <Phone size={12} className="text-slate-400 shrink-0" />
                                            <span>{storePhone}</span>
                                        </div>
                                    )}
                                </div>
                                <div className="text-right">
                                    <div className="text-xs font-extrabold uppercase tracking-widest text-indigo-600">INVOICE</div>
                                    <div className="text-sm font-mono font-black text-slate-900 mt-0.5">#{invoiceModalOrder.order_number}</div>
                                    <div className="text-[11px] text-slate-400 mt-0.5">
                                        {new Date(invoiceModalOrder.created_at).toLocaleDateString('en-US', { year: 'numeric', month: 'short', day: 'numeric' })}
                                    </div>
                                </div>
                            </div>

                            {/* Customer & Shipping Details Grid */}
                            <div className="grid grid-cols-1 md:grid-cols-2 gap-4 bg-slate-50 p-4 rounded-2xl border border-slate-200/70">
                                <div>
                                    <h4 className="text-xs font-extrabold uppercase tracking-wider text-slate-400 mb-2">Customer Details</h4>
                                    <div className="font-bold text-sm text-slate-900">{invoiceModalOrder.customer_name}</div>
                                    <div className="text-xs text-slate-600 mt-1 flex items-center gap-1.5">
                                        <Phone size={12} className="text-slate-400" />
                                        <span className="font-mono">{invoiceModalOrder.customer_phone}</span>
                                    </div>
                                    {invoiceModalOrder.customer_email && (
                                        <div className="text-xs text-slate-600 mt-1 flex items-center gap-1.5">
                                            <Mail size={12} className="text-slate-400" />
                                            <span>{invoiceModalOrder.customer_email}</span>
                                        </div>
                                    )}
                                </div>

                                <div>
                                    <h4 className="text-xs font-extrabold uppercase tracking-wider text-slate-400 mb-2">Shipping Information</h4>
                                    <div className="text-xs text-slate-700 leading-relaxed flex items-start gap-1.5">
                                        <MapPin size={14} className="text-slate-400 flex-shrink-0 mt-0.5" />
                                        <span>{invoiceModalOrder.shipping_address}{invoiceModalOrder.city ? `, ${invoiceModalOrder.city}` : ''}</span>
                                    </div>
                                    <div className="mt-3 flex items-center gap-2">
                                        <span className="text-[11px] font-bold px-2 py-0.5 rounded bg-white border border-slate-200 text-slate-700">
                                            Method: {invoiceModalOrder.payment_method?.toUpperCase()}
                                        </span>
                                        <span className={`text-[11px] font-bold px-2 py-0.5 rounded border uppercase ${getPaymentBadge(invoiceModalOrder.payment_status)}`}>
                                            {invoiceModalOrder.payment_status}
                                        </span>
                                    </div>
                                </div>
                            </div>

                            {/* Order Items Table */}
                            <div>
                                <h4 className="text-xs font-extrabold uppercase tracking-wider text-slate-400 mb-3">Order Items</h4>
                                <div className="border border-slate-200 rounded-xl overflow-hidden">
                                    <table className="w-full text-left text-xs">
                                        <thead>
                                            <tr className="bg-slate-50 border-b border-slate-200 text-slate-500 font-bold">
                                                <th className="p-3">Product</th>
                                                <th className="p-3 text-center">Qty</th>
                                                <th className="p-3 text-right">Price</th>
                                                <th className="p-3 text-right">Total</th>
                                            </tr>
                                        </thead>
                                        <tbody className="divide-y divide-slate-100">
                                            {(invoiceModalOrder.items || []).map((item, i) => {
                                                const unitPrice = Number(item.unit_price || 0);
                                                const itemTotal = Number(item.total_price || (unitPrice * item.quantity));
                                                return (
                                                    <tr key={i}>
                                                        <td className="p-3 font-semibold text-slate-800">
                                                            {item.product_name}
                                                            {item.size && item.size !== 'Default' && (
                                                                <span className="ml-2 text-[10px] bg-slate-100 text-slate-600 px-1.5 py-0.5 rounded font-normal">
                                                                    {item.size}
                                                                </span>
                                                            )}
                                                        </td>
                                                        <td className="p-3 text-center font-bold">{item.quantity}</td>
                                                        <td className="p-3 text-right font-mono text-slate-600">{symbol}{unitPrice.toLocaleString()}</td>
                                                        <td className="p-3 text-right font-mono font-bold text-slate-900">{symbol}{itemTotal.toLocaleString()}</td>
                                                    </tr>
                                                );
                                            })}
                                        </tbody>
                                    </table>
                                </div>
                            </div>

                            {/* Summary Calculation */}
                            <div className="bg-slate-50 rounded-2xl p-4 border border-slate-200/70 space-y-2 text-xs">
                                <div className="flex justify-between text-slate-600">
                                    <span>Subtotal:</span>
                                    <span className="font-mono font-bold">{symbol}{Number(invoiceModalOrder.subtotal || 0).toLocaleString()}</span>
                                </div>
                                <div className="flex justify-between text-slate-600">
                                    <span>Shipping Delivery Fee:</span>
                                    <span className="font-mono font-bold">{symbol}{Number(invoiceModalOrder.shipping_cost || 0).toLocaleString()}</span>
                                </div>
                                {Number(invoiceModalOrder.discount_amount || 0) > 0 && (
                                    <div className="flex justify-between text-emerald-600">
                                        <span>Discount:</span>
                                        <span className="font-mono font-bold">-{symbol}{Number(invoiceModalOrder.discount_amount).toLocaleString()}</span>
                                    </div>
                                )}
                                <div className="pt-2 border-t border-slate-200 flex justify-between text-sm font-black text-slate-900">
                                    <span>Grand Total:</span>
                                    <span className="font-mono text-base text-indigo-600">{symbol}{Number(invoiceModalOrder.total_amount || 0).toLocaleString()}</span>
                                </div>
                            </div>
                        </div>

                        {/* Modal Footer Actions */}
                        <div className="p-4 border-t border-slate-100 bg-slate-50/70 flex justify-between items-center rounded-b-3xl">
                            <div className="flex items-center gap-2">
                                <span className="text-xs text-slate-500 font-medium">Status:</span>
                                <select
                                    value={invoiceModalOrder.status}
                                    onChange={e => handleStatusUpdate(invoiceModalOrder.id, e.target.value)}
                                    className="text-xs font-bold px-2 py-1 rounded-lg border border-slate-300 bg-white"
                                >
                                    <option value="pending">Pending</option>
                                    <option value="confirmed">Confirmed</option>
                                    <option value="processing">Processing</option>
                                    <option value="dispatched">Dispatched</option>
                                    <option value="completed">Delivered</option>
                                    <option value="cancelled">Cancelled</option>
                                    <option value="returned">Returned</option>
                                </select>
                            </div>

                            <div className="flex items-center gap-2">
                                <Link
                                    href={`/admin/orders/${invoiceModalOrder.id}/edit`}
                                    className="px-3 py-1.5 bg-white border border-slate-200 hover:bg-slate-100 text-slate-700 rounded-xl text-xs font-bold flex items-center gap-1 transition-colors"
                                >
                                    <Edit size={13} />
                                    Edit
                                </Link>
                                <button
                                    type="button"
                                    onClick={() => setInvoiceModalOrder(null)}
                                    className="px-4 py-1.5 bg-slate-900 hover:bg-slate-800 text-white rounded-xl text-xs font-bold transition-colors"
                                >
                                    Close
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            )}

            {/* ASSIGN COURIER MODAL */}
            {dispatchModalOrder && (
                <div className="fixed inset-0 bg-black/60 backdrop-blur-sm z-50 flex items-center justify-center p-4 animate-in fade-in duration-200">
                    <div className="bg-white rounded-3xl shadow-2xl max-w-md w-full p-6 border border-slate-200">
                        <div className="flex justify-between items-center mb-4">
                            <h3 className="text-base font-black text-slate-900 flex items-center gap-2">
                                <Truck className="text-indigo-600" size={18} />
                                Assign Courier
                            </h3>
                            <button
                                type="button"
                                onClick={() => setDispatchModalOrder(null)}
                                className="p-1 hover:bg-slate-100 text-slate-400 rounded-lg"
                            >
                                <X size={18} />
                            </button>
                        </div>

                        <p className="text-xs text-slate-500 mb-4">
                            Dispatch Order <strong className="text-slate-800">#{dispatchModalOrder.order_number}</strong> to courier partner for delivery to <strong className="text-slate-800">{dispatchModalOrder.city || 'Customer'}</strong>.
                        </p>

                        <form onSubmit={handleCourierDispatch} className="space-y-4">
                            <div>
                                <label className="block text-xs font-bold text-slate-700 mb-1">Select Courier Partner</label>
                                <select
                                    value={selectedCourier}
                                    onChange={e => setSelectedCourier(e.target.value)}
                                    required
                                    className="w-full border border-slate-300 rounded-xl px-3 py-2 text-xs bg-white focus:border-indigo-600 outline-none font-medium"
                                >
                                    <option value="">-- Choose Courier Provider --</option>
                                    {activeCouriers.map(c => (
                                        <option key={c.key} value={c.key}>{c.name}</option>
                                    ))}
                                </select>
                            </div>

                            <div className="flex justify-end gap-2 pt-2">
                                <button
                                    type="button"
                                    onClick={() => setDispatchModalOrder(null)}
                                    className="px-4 py-2 bg-slate-100 hover:bg-slate-200 text-slate-700 rounded-xl text-xs font-bold transition-colors"
                                >
                                    Cancel
                                </button>
                                <button
                                    type="submit"
                                    disabled={isDispatching || !selectedCourier}
                                    className="px-5 py-2 bg-indigo-600 hover:bg-indigo-700 disabled:opacity-50 text-white rounded-xl text-xs font-bold shadow-md shadow-indigo-600/20 transition-all flex items-center gap-1.5"
                                >
                                    {isDispatching ? 'Dispatching...' : 'Dispatch Now'}
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            )}
        </AdminLayout>
    );
}
