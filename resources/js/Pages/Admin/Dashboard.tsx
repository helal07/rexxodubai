import React from 'react';
import { Head, Link } from '@inertiajs/react';
import AdminLayout from '@/Layouts/AdminLayout';
import {
    ShoppingBag,
    Truck,
    CheckCircle2,
    RotateCcw,
    Users,
    DollarSign,
    TrendingUp,
    Boxes,
    ArrowUpRight,
    Eye,
    PlusCircle
} from 'lucide-react';

interface OrderSummary {
    id: string;
    client: string;
    prod: string;
    amt: number;
    status: string;
}

interface DashboardProps {
    totalOrders?: number;
    inWayOrders?: number;
    successOrders?: number;
    returnOrders?: number;
    totalCustomers?: number;
    monthlyRevenue?: number;
    avgOrderValue?: number;
    recentOrders?: OrderSummary[];
    productCount?: number;
}

export default function Dashboard({
    totalOrders = 0,
    inWayOrders = 0,
    successOrders = 0,
    returnOrders = 0,
    totalCustomers = 0,
    monthlyRevenue = 0,
    avgOrderValue = 0,
    recentOrders = [],
    productCount = 0,
}: DashboardProps) {
    return (
        <AdminLayout
            activePage="dashboard"
            pageTitle="Executive Dashboard"
            pageSubtitle="Live operational summary, sales performance & inventory status."
            headerActions={
                <>
                    <Link
                        href="/admin/products/add"
                        className="px-4 py-2 bg-[#0284c7] hover:bg-[#0369a1] text-white text-[12px] font-bold uppercase tracking-wider rounded-xl transition-all shadow-md shadow-[#0284c7]/20 flex items-center gap-1.5"
                    >
                        <PlusCircle className="w-4 h-4" /> Add Product
                    </Link>
                    <Link
                        href="/admin/create-order"
                        className="px-4 py-2 bg-emerald-600 hover:bg-emerald-700 text-white text-[12px] font-bold uppercase tracking-wider rounded-xl transition-all shadow-md shadow-emerald-600/20 flex items-center gap-1.5"
                    >
                        <ShoppingBag className="w-4 h-4" /> Create Order
                    </Link>
                </>
            }
        >
            <Head title="Admin Dashboard" />

            {/* METRICS GRID */}
            <div className="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                <div className="bg-white/90 backdrop-blur-xl border border-[#38bdf8]/30 p-5 rounded-2xl shadow-sm">
                    <div className="flex items-center justify-between text-[#64748b]">
                        <span className="text-[11px] font-bold uppercase tracking-wider">Total Orders</span>
                        <div className="p-2 bg-[#e0f2fe] text-[#0284c7] rounded-xl">
                            <ShoppingBag className="w-4 h-4" />
                        </div>
                    </div>
                    <div className="mt-3">
                        <span className="text-2xl font-serif font-bold text-[#0f172a]">{totalOrders}</span>
                        <span className="text-[11px] text-[#64748b] block mt-0.5">All time processed</span>
                    </div>
                </div>

                <div className="bg-white/90 backdrop-blur-xl border border-[#38bdf8]/30 p-5 rounded-2xl shadow-sm">
                    <div className="flex items-center justify-between text-[#64748b]">
                        <span className="text-[11px] font-bold uppercase tracking-wider">In-Transit / Processing</span>
                        <div className="p-2 bg-amber-50 text-amber-600 rounded-xl">
                            <Truck className="w-4 h-4" />
                        </div>
                    </div>
                    <div className="mt-3">
                        <span className="text-2xl font-serif font-bold text-amber-600">{inWayOrders}</span>
                        <span className="text-[11px] text-[#64748b] block mt-0.5">Dispatched & Transit</span>
                    </div>
                </div>

                <div className="bg-white/90 backdrop-blur-xl border border-[#38bdf8]/30 p-5 rounded-2xl shadow-sm">
                    <div className="flex items-center justify-between text-[#64748b]">
                        <span className="text-[11px] font-bold uppercase tracking-wider">Delivered / Completed</span>
                        <div className="p-2 bg-emerald-50 text-emerald-600 rounded-xl">
                            <CheckCircle2 className="w-4 h-4" />
                        </div>
                    </div>
                    <div className="mt-3">
                        <span className="text-2xl font-serif font-bold text-emerald-600">{successOrders}</span>
                        <span className="text-[11px] text-[#64748b] block mt-0.5">Successfully fulfilled</span>
                    </div>
                </div>

                <div className="bg-white/90 backdrop-blur-xl border border-[#38bdf8]/30 p-5 rounded-2xl shadow-sm">
                    <div className="flex items-center justify-between text-[#64748b]">
                        <span className="text-[11px] font-bold uppercase tracking-wider">Monthly Revenue</span>
                        <div className="p-2 bg-purple-50 text-purple-600 rounded-xl">
                            <DollarSign className="w-4 h-4" />
                        </div>
                    </div>
                    <div className="mt-3">
                        <span className="text-2xl font-serif font-bold text-purple-700">
                            ৳ {monthlyRevenue.toLocaleString(undefined, { minimumFractionDigits: 2 })}
                        </span>
                        <span className="text-[11px] text-[#64748b] block mt-0.5">Completed this month</span>
                    </div>
                </div>
            </div>

            {/* SECONDARY STATS */}
            <div className="grid grid-cols-1 sm:grid-cols-3 gap-4">
                <div className="bg-white/90 backdrop-blur-xl border border-[#e2e8f0] p-5 rounded-2xl flex items-center justify-between shadow-xs">
                    <div>
                        <span className="text-[11px] font-bold uppercase tracking-wider text-[#64748b]">Unique Customers</span>
                        <h4 className="text-xl font-serif font-bold text-[#0f172a] mt-1">{totalCustomers}</h4>
                    </div>
                    <div className="p-3 bg-[#f1f5f9] text-[#475569] rounded-xl">
                        <Users className="w-5 h-5" />
                    </div>
                </div>

                <div className="bg-white/90 backdrop-blur-xl border border-[#e2e8f0] p-5 rounded-2xl flex items-center justify-between shadow-xs">
                    <div>
                        <span className="text-[11px] font-bold uppercase tracking-wider text-[#64748b]">Avg Order Value</span>
                        <h4 className="text-xl font-serif font-bold text-[#0f172a] mt-1">
                            ৳ {avgOrderValue.toFixed(2)}
                        </h4>
                    </div>
                    <div className="p-3 bg-[#f1f5f9] text-[#475569] rounded-xl">
                        <TrendingUp className="w-5 h-5" />
                    </div>
                </div>

                <div className="bg-white/90 backdrop-blur-xl border border-[#e2e8f0] p-5 rounded-2xl flex items-center justify-between shadow-xs">
                    <div>
                        <span className="text-[11px] font-bold uppercase tracking-wider text-[#64748b]">Live Products</span>
                        <h4 className="text-xl font-serif font-bold text-[#0f172a] mt-1">{productCount}</h4>
                    </div>
                    <div className="p-3 bg-[#f1f5f9] text-[#475569] rounded-xl">
                        <Boxes className="w-5 h-5" />
                    </div>
                </div>
            </div>

            {/* RECENT ORDERS TABLE */}
            <div className="bg-white/90 backdrop-blur-xl border border-[#38bdf8]/30 rounded-2xl p-6 space-y-4 shadow-sm">
                <div className="flex items-center justify-between border-b border-[#e2e8f0] pb-4">
                    <div>
                        <h3 className="text-base font-serif font-bold text-[#0f172a] uppercase tracking-wide">
                            Recent Storefront Orders
                        </h3>
                        <p className="text-[11px] text-[#64748b]">Latest incoming transactions and fulfillment status</p>
                    </div>
                    <Link
                        href="/admin/orders"
                        className="text-[12px] font-bold text-[#0284c7] hover:underline flex items-center gap-1 uppercase tracking-wider"
                    >
                        View All Orders <ArrowUpRight className="w-3.5 h-3.5" />
                    </Link>
                </div>

                <div className="overflow-x-auto">
                    <table className="w-full text-left border-collapse text-[13px]">
                        <thead>
                            <tr className="bg-[#f8fafc] border-b border-[#e2e8f0] text-[11px] font-bold uppercase tracking-wider text-[#64748b]">
                                <th className="p-3.5 rounded-tl-xl">Order #</th>
                                <th className="p-3.5">Customer</th>
                                <th className="p-3.5">Items</th>
                                <th className="p-3.5">Amount</th>
                                <th className="p-3.5">Status</th>
                                <th className="p-3.5 text-right rounded-tr-xl">Action</th>
                            </tr>
                        </thead>
                        <tbody className="divide-y divide-[#e2e8f0]">
                            {recentOrders.length > 0 ? (
                                recentOrders.map(order => (
                                    <tr key={order.id} className="hover:bg-[#f8fafc] transition-colors">
                                        <td className="p-3.5 font-bold text-[#0284c7]">#{order.id}</td>
                                        <td className="p-3.5 font-medium text-[#0f172a]">{order.client}</td>
                                        <td className="p-3.5 text-[#64748b] max-w-xs truncate">{order.prod}</td>
                                        <td className="p-3.5 font-mono font-bold text-[#0f172a]">
                                            ৳ {order.amt.toFixed(2)}
                                        </td>
                                        <td className="p-3.5">
                                            <span
                                                className={`px-2.5 py-1 rounded-full text-[10px] font-bold uppercase tracking-wider ${order.status === 'Completed'
                                                        ? 'bg-emerald-100 text-emerald-800'
                                                        : order.status === 'Processing' || order.status === 'Dispatched'
                                                            ? 'bg-amber-100 text-amber-800'
                                                            : 'bg-slate-100 text-slate-700'
                                                    }`}
                                            >
                                                {order.status}
                                            </span>
                                        </td>
                                        <td className="p-3.5 text-right">
                                            <Link
                                                href="/admin/orders"
                                                className="p-1.5 inline-flex text-[#0284c7] hover:bg-[#e0f2fe] rounded-lg transition-colors"
                                                title="View Orders"
                                            >
                                                <Eye className="w-4 h-4" />
                                            </Link>
                                        </td>
                                    </tr>
                                ))
                            ) : (
                                <tr>
                                    <td colSpan={6} className="p-8 text-center text-[#94a3b8]">
                                        No recent orders found.
                                    </td>
                                </tr>
                            )}
                        </tbody>
                    </table>
                </div>
            </div>
        </AdminLayout>
    );
}
