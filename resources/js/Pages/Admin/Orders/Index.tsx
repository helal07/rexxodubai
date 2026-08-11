import React, { useState } from 'react';
import { Head, Link, router } from '@inertiajs/react';
import AdminLayout from '@/Layouts/AdminLayout';
import {
    ShoppingCart,
    PlusCircle,
    Search,
    Eye,
    Trash2,
    CheckCircle2,
    Truck,
    Clock,
    XCircle,
    Filter,
    PenSquare
} from 'lucide-react';

interface OrderItem {
    id: number;
    product_name: string;
    quantity: number;
    unit_price: number;
}

interface Order {
    id: number;
    order_number: string;
    customer_name: string;
    customer_phone: string;
    total_amount: number;
    status: string;
    created_at: string;
    items?: OrderItem[];
}

interface Courier {
    key: string;
    name: string;
    status: string;
}

interface OrdersProps {
    orders: Order[];
    activeCouriers: Courier[];
}

export default function OrdersIndex({ orders = [], activeCouriers = [] }: OrdersProps) {
    const [statusFilter, setStatusFilter] = useState('All');
    const [search, setSearch] = useState('');
    const [dispatchingOrder, setDispatchingOrder] = useState<number | null>(null);
    const [dispatchModalOrder, setDispatchModalOrder] = useState<{ id: number, order_number: string } | null>(null);
    const [selectedCourier, setSelectedCourier] = useState('');

    const filteredOrders = orders.filter(o => {
        const matchesSearch = o.order_number.toLowerCase().includes(search.toLowerCase()) ||
            o.customer_name.toLowerCase().includes(search.toLowerCase()) ||
            o.customer_phone.includes(search);
        const matchesStatus = statusFilter === 'All' || o.status.toLowerCase() === statusFilter.toLowerCase();
        return matchesSearch && matchesStatus;
    });

    const handleStatusUpdate = (id: number, status: string) => {
        router.post(`/admin/orders/${id}/status`, { status });
    };

    const handleDispatchCourier = (e: React.FormEvent) => {
        e.preventDefault();
        if (!dispatchModalOrder || !selectedCourier) return;

        setDispatchingOrder(dispatchModalOrder.id);
        
        router.post('/admin/courier/dispatch', {
            order_id: dispatchModalOrder.id,
            provider: selectedCourier,
        }, {
            preserveScroll: true,
            onFinish: () => {
                setDispatchingOrder(null);
                setDispatchModalOrder(null);
                setSelectedCourier('');
            },
        });
    };

    const handleDeleteOrder = (id: number, order_number: string) => {
        if (confirm(`Are you sure you want to delete Order #${order_number}?`)) {
            router.delete(`/admin/orders/${id}`);
        }
    };

    return (
        <AdminLayout
            activePage="orders"
            pageTitle="Storefront Order Management"
            pageSubtitle="Track customer purchases, update fulfillment statuses, and inspect orders."
            headerActions={
                <Link
                    href="/admin/create-order"
                    className="px-4 py-2 bg-[#0284c7] hover:bg-[#0369a1] text-white text-[12px] font-bold uppercase tracking-wider rounded-xl transition-all shadow-md shadow-[#0284c7]/20 flex items-center gap-1.5"
                >
                    <PlusCircle className="w-4 h-4" /> Create Manual Order (POS)
                </Link>
            }
        >
            <Head title="Orders — Admin" />

            {/* STATUS FILTER TABS */}
            <div className="flex items-center gap-2 overflow-x-auto pb-1">
                {['All', 'pending', 'processing', 'dispatched', 'completed', 'cancelled'].map(st => (
                    <button
                        key={st}
                        type="button"
                        onClick={() => setStatusFilter(st)}
                        className={`px-4 py-2 rounded-xl text-[12px] font-bold uppercase tracking-wider transition-all whitespace-nowrap ${
                            statusFilter === st
                                ? 'bg-[#0284c7] text-white shadow-xs'
                                : 'bg-white border border-[#cbd5e1] text-[#475569] hover:bg-[#f8fafc]'
                        }`}
                    >
                        {st}
                    </button>
                ))}
            </div>

            {/* ORDERS TABLE CONTAINER */}
            <div className="bg-white/90 backdrop-blur-xl border border-[#38bdf8]/30 p-6 rounded-2xl space-y-4 shadow-sm">
                <div className="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 border-b border-[#e2e8f0] pb-4">
                    <div className="relative w-full sm:w-72">
                        <Search className="w-4 h-4 absolute left-3 top-1/2 -translate-y-1/2 text-[#94a3b8]" />
                        <input
                            type="text"
                            value={search}
                            onChange={e => setSearch(e.target.value)}
                            placeholder="Search by order # or customer..."
                            className="w-full pl-9 pr-4 py-2 text-[12px] border border-[#cbd5e1] rounded-xl outline-none focus:border-[#0284c7] bg-white text-[#0f172a]"
                        />
                    </div>
                </div>

                <div className="overflow-x-auto">
                    <table className="w-full text-left border-collapse text-[13px]">
                        <thead>
                            <tr className="bg-[#f8fafc] border-b border-[#e2e8f0] text-[11px] font-bold uppercase tracking-wider text-[#64748b]">
                                <th className="p-3.5 rounded-tl-xl">Order #</th>
                                <th className="p-3.5">Customer Name & Phone</th>
                                <th className="p-3.5">Total Amount</th>
                                <th className="p-3.5">Status</th>
                                <th className="p-3.5 text-right rounded-tr-xl">Actions</th>
                            </tr>
                        </thead>
                        <tbody className="divide-y divide-[#e2e8f0]">
                            {filteredOrders.length > 0 ? (
                                filteredOrders.map(order => (
                                    <tr key={order.id} className="hover:bg-[#f8fafc] transition-colors">
                                        <td className="p-3.5 font-bold text-[#0284c7]">#{order.order_number}</td>
                                        <td className="p-3.5">
                                            <div className="font-semibold text-[#0f172a]">{order.customer_name}</div>
                                            <div className="text-[11px] text-[#64748b]">{order.customer_phone}</div>
                                        </td>
                                        <td className="p-3.5 font-mono font-bold text-[#0f172a]">
                                            ৳ {Number(order.total_amount).toFixed(2)}
                                        </td>
                                        <td className="p-3.5">
                                            <select
                                                value={order.status}
                                                onChange={e => handleStatusUpdate(order.id, e.target.value)}
                                                className={`px-2.5 py-1 rounded-full text-[11px] font-bold uppercase tracking-wider outline-none cursor-pointer border ${
                                                    order.status === 'completed'
                                                        ? 'bg-emerald-50 text-emerald-800 border-emerald-300'
                                                        : order.status === 'processing' || order.status === 'dispatched'
                                                        ? 'bg-amber-50 text-amber-800 border-amber-300'
                                                        : 'bg-slate-100 text-slate-700 border-slate-300'
                                                }`}
                                            >
                                                <option value="pending">Pending</option>
                                                <option value="processing">Processing</option>
                                                <option value="dispatched">Dispatched</option>
                                                <option value="completed">Completed</option>
                                                <option value="cancelled">Cancelled</option>
                                            </select>
                                        </td>
                                        <td className="p-3.5 text-right space-x-1.5">
                                            <Link
                                                href={`/admin/orders/${order.id}/invoice`}
                                                className="p-1.5 text-[#0284c7] hover:bg-[#e0f2fe] rounded-lg transition-colors inline-block"
                                                title="View Full Invoice"
                                            >
                                                <Eye className="w-4 h-4" />
                                            </Link>
                                            <Link
                                                href={`/admin/orders/${order.id}/edit`}
                                                className="p-1.5 text-emerald-600 hover:bg-emerald-50 rounded-lg transition-colors inline-block"
                                                title="Edit Order"
                                            >
                                                <PenSquare className="w-4 h-4" />
                                            </Link>
                                            <button
                                                type="button"
                                                onClick={() => setDispatchModalOrder({ id: order.id, order_number: order.order_number })}
                                                disabled={dispatchingOrder === order.id}
                                                className="p-1.5 text-indigo-600 hover:bg-indigo-50 rounded-lg transition-colors disabled:opacity-50 inline-block"
                                                title="Dispatch to Courier"
                                            >
                                                <Truck className={`w-4 h-4 ${dispatchingOrder === order.id ? 'animate-pulse' : ''}`} />
                                            </button>
                                            <button
                                                type="button"
                                                onClick={() => handleDeleteOrder(order.id, order.order_number)}
                                                className="p-1.5 text-rose-600 hover:bg-rose-50 rounded-lg transition-colors"
                                                title="Delete Order"
                                            >
                                                <Trash2 className="w-4 h-4" />
                                            </button>
                                        </td>
                                    </tr>
                                ))
                            ) : (
                                <tr>
                                    <td colSpan={5} className="p-8 text-center text-[#94a3b8]">
                                        No orders found.
                                    </td>
                                </tr>
                            )}
                        </tbody>
                    </table>
                </div>
            </div>

            {/* DISPATCH COURIER MODAL */}
            {dispatchModalOrder && (
                <div className="fixed inset-0 z-50 bg-black/50 backdrop-blur-xs flex items-center justify-center p-4">
                    <form onSubmit={handleDispatchCourier} className="bg-white rounded-2xl max-w-md w-full p-6 space-y-4 shadow-xl">
                        <div className="flex items-center justify-between border-b pb-3">
                            <h3 className="text-base font-serif font-bold text-[#0f172a]">
                                Dispatch Order #{dispatchModalOrder.order_number}
                            </h3>
                            <button
                                type="button"
                                onClick={() => setDispatchModalOrder(null)}
                                className="text-slate-400 hover:text-slate-600 font-bold"
                            >
                                <XCircle className="w-5 h-5" />
                            </button>
                        </div>
                        <div className="space-y-4 py-2">
                            <div>
                                <label className="text-[12px] font-bold uppercase text-slate-500 block mb-1.5">
                                    Select Courier Service
                                </label>
                                <select
                                    required
                                    value={selectedCourier}
                                    onChange={(e) => setSelectedCourier(e.target.value)}
                                    className="w-full border border-slate-300 p-2.5 rounded-xl text-[13px] font-medium focus:border-indigo-600 focus:ring-1 focus:ring-indigo-600 outline-none"
                                >
                                    <option value="" disabled>-- Choose Active Courier --</option>
                                    {activeCouriers.map((c) => (
                                        <option key={c.key} value={c.key}>
                                            {c.name}
                                        </option>
                                    ))}
                                </select>
                            </div>
                        </div>
                        <div className="flex justify-end gap-2 pt-3 border-t">
                            <button
                                type="button"
                                onClick={() => setDispatchModalOrder(null)}
                                className="px-4 py-2 bg-slate-100 hover:bg-slate-200 text-slate-700 rounded-xl text-xs font-bold transition-colors"
                            >
                                Cancel
                            </button>
                            <button
                                type="submit"
                                disabled={!selectedCourier || dispatchingOrder === dispatchModalOrder.id}
                                className="px-4 py-2 bg-indigo-600 hover:bg-indigo-700 disabled:bg-indigo-400 text-white rounded-xl text-xs font-bold transition-colors flex items-center gap-1.5"
                            >
                                <Truck className="w-3.5 h-3.5" /> Dispatch
                            </button>
                        </div>
                    </form>
                </div>
            )}
        </AdminLayout>
    );
}
