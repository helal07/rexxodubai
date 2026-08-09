import React from 'react';
import { Head, Link, router } from '@inertiajs/react';
import AdminLayout from '@/Layouts/AdminLayout';
import {
    ShoppingBag,
    PlusCircle,
    Trash2,
    CheckCircle2,
    Briefcase,
    Calendar
} from 'lucide-react';

interface Supplier {
    id: number;
    company_name: string;
}

interface Purchase {
    id: number;
    reference_no: string;
    purchase_date: string;
    total_amount: number;
    payment_status: string;
    supplier?: Supplier;
}

interface PurchasesProps {
    purchases: Purchase[];
}

export default function PurchasesIndex({ purchases = [] }: PurchasesProps) {
    const handleDelete = (id: number, ref: string) => {
        if (confirm(`Are you sure you want to delete purchase order ${ref}?`)) {
            router.delete(`/admin/api/purchases/${id}`);
        }
    };

    return (
        <AdminLayout
            activePage="purchase_list"
            pageTitle="Supplier Purchase Orders"
            pageSubtitle="Track stock acquisition orders, costs, and supplier payments."
            headerActions={
                <Link
                    href="/admin/purchases/add"
                    className="px-4 py-2 bg-[#0284c7] hover:bg-[#0369a1] text-white text-[12px] font-bold uppercase tracking-wider rounded-xl transition-all shadow-md shadow-[#0284c7]/20 flex items-center gap-1.5"
                >
                    <PlusCircle className="w-4 h-4" /> Add Purchase Order
                </Link>
            }
        >
            <Head title="Purchases — Admin" />

            <div className="bg-white/90 backdrop-blur-xl border border-[#38bdf8]/30 p-6 rounded-2xl space-y-4 shadow-sm">
                <div className="flex items-center justify-between border-b border-[#e2e8f0] pb-4">
                    <div>
                        <h3 className="text-base font-serif font-bold text-[#0f172a] uppercase tracking-wide">
                            Purchase Order History
                        </h3>
                        <p className="text-[11px] text-[#64748b]">All inventory stock purchase transactions</p>
                    </div>
                </div>

                <div className="overflow-x-auto">
                    <table className="w-full text-left border-collapse text-[13px]">
                        <thead>
                            <tr className="bg-[#f8fafc] border-b border-[#e2e8f0] text-[11px] font-bold uppercase tracking-wider text-[#64748b]">
                                <th className="p-3.5 rounded-tl-xl w-28">Date</th>
                                <th className="p-3.5">Ref No.</th>
                                <th className="p-3.5">Supplier</th>
                                <th className="p-3.5">Total Cost (৳)</th>
                                <th className="p-3.5 text-center">Payment Status</th>
                                <th className="p-3.5 text-right rounded-tr-xl">Actions</th>
                            </tr>
                        </thead>
                        <tbody className="divide-y divide-[#e2e8f0]">
                            {purchases.length > 0 ? (
                                purchases.map(pur => (
                                    <tr key={pur.id} className="hover:bg-[#f8fafc] transition-colors">
                                        <td className="p-3.5 font-medium text-[#475569]">
                                            {pur.purchase_date ? new Date(pur.purchase_date).toLocaleDateString() : '-'}
                                        </td>
                                        <td className="p-3.5 font-bold text-[#0f172a]">{pur.reference_no}</td>
                                        <td className="p-3.5 text-[#0284c7] font-semibold">
                                            {pur.supplier?.company_name || 'N/A'}
                                        </td>
                                        <td className="p-3.5 font-mono font-bold text-[#0f172a]">
                                            ৳ {Number(pur.total_amount).toFixed(2)}
                                        </td>
                                        <td className="p-3.5 text-center">
                                            <span
                                                className={`px-2.5 py-1 rounded-full text-[10px] font-bold uppercase tracking-wider ${
                                                    pur.payment_status === 'Paid'
                                                        ? 'bg-emerald-100 text-emerald-800'
                                                        : 'bg-amber-100 text-amber-800'
                                                }`}
                                            >
                                                {pur.payment_status || 'Pending'}
                                            </span>
                                        </td>
                                        <td className="p-3.5 text-right">
                                            <button
                                                type="button"
                                                onClick={() => handleDelete(pur.id, pur.reference_no)}
                                                className="p-1.5 text-rose-600 hover:bg-rose-50 rounded-lg transition-colors"
                                                title="Delete Purchase"
                                            >
                                                <Trash2 className="w-4 h-4" />
                                            </button>
                                        </td>
                                    </tr>
                                ))
                            ) : (
                                <tr>
                                    <td colSpan={6} className="p-8 text-center text-[#94a3b8]">
                                        No purchase orders found.
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
