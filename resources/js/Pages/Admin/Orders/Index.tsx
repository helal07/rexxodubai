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
    Filter
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

interface OrdersProps {
    orders: Order[];
}

export default function OrdersIndex({ orders = [] }: OrdersProps) {
    const [statusFilter, setStatusFilter] = useState('All');
    const [search, setSearch] = useState('');
    const [selectedOrder, setSelectedOrder] = useState<Order | null>(null);

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

    const handleDeleteOrder = (id: number, orderNum: string) => {
        if (confirm(`Are you sure you want to delete Order #${orderNum}?`)) {
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
                                            <button
                                                type="button"
                                                onClick={() => setSelectedOrder(order)}
                                                className="p-1.5 text-[#0284c7] hover:bg-[#e0f2fe] rounded-lg transition-colors"
                                                title="Quick View Details"
                                            >
                                                <Eye className="w-4 h-4" />
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

            {/* ORDER DETAILS MODAL */}
            {selectedOrder && (
                <div className="fixed inset-0 z-50 bg-black/50 backdrop-blur-xs flex items-center justify-center p-4">
                    <div className="bg-white rounded-2xl max-w-lg w-full p-6 space-y-4 shadow-xl">
                        <div className="flex items-center justify-between border-b pb-3">
                            <h3 className="text-base font-serif font-bold text-[#0f172a]">
                                Order #{selectedOrder.order_number} Details
                            </h3>
                            <button
                                type="button"
                                onClick={() => setSelectedOrder(null)}
                                className="text-slate-400 hover:text-slate-600 font-bold"
                            >
                                ✕
                            </button>
                        </div>
                        <div className="space-y-2 text-[13px]">
                            <div><strong>Customer:</strong> {selectedOrder.customer_name} ({selectedOrder.customer_phone})</div>
                            <div><strong>Status:</strong> {selectedOrder.status}</div>
                            <div><strong>Total:</strong> ৳ {Number(selectedOrder.total_amount).toFixed(2)}</div>
                            <div className="border-t pt-2 mt-2">
                                <strong className="block mb-1">Purchased Items:</strong>
                                <ul className="list-disc list-inside text-xs text-slate-700 space-y-1">
                                    {selectedOrder.items?.map(item => (
                                        <li key={item.id}>
                                            {item.product_name} x {item.quantity} (৳{Number(item.unit_price).toFixed(2)} ea)
                                        </li>
                                    )) || <li>No items breakdown available</li>}
                                </ul>
                            </div>
                        </div>
                        <div className="text-right pt-2">
                            <button
                                type="button"
                                onClick={() => setSelectedOrder(null)}
                                className="px-4 py-2 bg-slate-100 hover:bg-slate-200 text-slate-700 rounded-xl text-xs font-bold"
                            >
                                Close
                            </button>
                        </div>
                    </div>
                </div>
            )}
        </AdminLayout>
    );
}
