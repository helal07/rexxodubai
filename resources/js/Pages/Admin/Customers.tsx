import React from 'react';
import { Head, useForm, router } from '@inertiajs/react';
import AdminLayout from '@/Layouts/AdminLayout';
import {
    Users,
    UserPlus,
    Trash2,
    Edit3,
    Plus,
    Phone,
    MapPin
} from 'lucide-react';

interface Customer {
    id: number;
    name: string;
    phone: string;
    email?: string;
    address?: string;
    created_at?: string;
}

interface CustomersProps {
    customers: Customer[];
}

export default function Customers({ customers = [] }: CustomersProps) {
    const { data, setData, post, processing, reset, errors } = useForm({
        name: '',
        phone: '',
        address: '',
    });

    const handleCreate = (e: React.FormEvent) => {
        e.preventDefault();
        post('/admin/api/customers', {
            onSuccess: () => reset(),
        });
    };

    const handleDelete = (id: number, name: string) => {
        if (confirm(`Are you sure you want to delete customer "${name}"?`)) {
            router.delete(`/admin/api/customers/${id}`);
        }
    };

    return (
        <AdminLayout
            activePage="customers"
            pageTitle="Customer Directory & CRM"
            pageSubtitle="Manage registered client database, contact information, and delivery addresses."
        >
            <Head title="Customers — Admin" />

            <div className="grid grid-cols-1 lg:grid-cols-12 gap-8">
                {/* LEFT: ADD NEW CUSTOMER FORM (4 COLS) */}
                <div className="lg:col-span-4 bg-white/90 backdrop-blur-xl border border-[#38bdf8]/30 p-7 rounded-2xl space-y-6 shadow-sm">
                    <div className="flex items-center gap-3 border-b border-[#e2e8f0] pb-4">
                        <div className="p-2.5 bg-[#e0f2fe] text-[#0284c7] rounded-xl">
                            <UserPlus className="w-5 h-5" />
                        </div>
                        <div>
                            <h3 className="text-base font-serif font-bold text-[#0f172a] uppercase tracking-wide">
                                Add New Customer
                            </h3>
                            <p className="text-[11px] text-[#64748b]">Save client for POS & orders</p>
                        </div>
                    </div>

                    <form onSubmit={handleCreate} className="space-y-4">
                        <div>
                            <label className="text-[11px] uppercase font-bold text-[#475569] tracking-wider block mb-1.5">
                                Full Name *
                            </label>
                            <input
                                type="text"
                                value={data.name}
                                onChange={e => setData('name', e.target.value)}
                                required
                                placeholder="e.g. Md Al Helal"
                                className="w-full border border-[#cbd5e1] px-4 py-2.5 rounded-xl text-[13px] font-medium outline-none focus:border-[#0284c7] bg-white shadow-2xs"
                            />
                            {errors.name && <p className="text-rose-600 text-xs mt-1">{errors.name}</p>}
                        </div>

                        <div>
                            <label className="text-[11px] uppercase font-bold text-[#475569] tracking-wider block mb-1.5">
                                Phone Number *
                            </label>
                            <input
                                type="text"
                                value={data.phone}
                                onChange={e => setData('phone', e.target.value)}
                                required
                                placeholder="017xxxxxxxx"
                                className="w-full border border-[#cbd5e1] px-4 py-2.5 rounded-xl text-[13px] font-mono font-medium outline-none focus:border-[#0284c7] bg-white shadow-2xs"
                            />
                        </div>

                        <div>
                            <label className="text-[11px] uppercase font-bold text-[#475569] tracking-wider block mb-1.5">
                                Delivery Address
                            </label>
                            <textarea
                                rows={2}
                                value={data.address}
                                onChange={e => setData('address', e.target.value)}
                                placeholder="Street, Area, City..."
                                className="w-full border border-[#cbd5e1] px-3.5 py-2 rounded-xl text-[12px] outline-none focus:border-[#0284c7] bg-white shadow-2xs"
                            />
                        </div>

                        <button
                            type="submit"
                            disabled={processing}
                            className="w-full bg-[#0284c7] hover:bg-[#0369a1] text-white py-3 rounded-xl text-[12px] font-bold uppercase tracking-wider transition-all shadow-md shadow-[#0284c7]/20 flex items-center justify-center gap-2"
                        >
                            <Plus className="w-4 h-4" /> Save Customer
                        </button>
                    </form>
                </div>

                {/* RIGHT: CUSTOMER DIRECTORY TABLE (8 COLS) */}
                <div className="lg:col-span-8 bg-white/90 backdrop-blur-xl border border-[#38bdf8]/30 p-7 rounded-2xl space-y-5 shadow-sm">
                    <div className="flex items-center gap-3 border-b border-[#e2e8f0] pb-4">
                        <div className="p-2.5 bg-[#e0f2fe] text-[#0284c7] rounded-xl">
                            <Users className="w-5 h-5" />
                        </div>
                        <div>
                            <h3 className="text-base font-serif font-bold text-[#0f172a] uppercase tracking-wide">
                                Customer Directory
                            </h3>
                            <p className="text-[11px] text-[#64748b]">Manage registered clients and order records</p>
                        </div>
                    </div>

                    <div className="overflow-x-auto">
                        <table className="w-full text-left border-collapse text-[13px]">
                            <thead>
                                <tr className="bg-[#f8fafc] border-b border-[#e2e8f0] text-[11px] font-bold uppercase tracking-wider text-[#64748b]">
                                    <th className="p-3.5 rounded-tl-xl">Customer Name</th>
                                    <th className="p-3.5">Phone Number</th>
                                    <th className="p-3.5">Address</th>
                                    <th className="p-3.5 text-right rounded-tr-xl">Action</th>
                                </tr>
                            </thead>
                            <tbody className="divide-y divide-[#e2e8f0]">
                                {customers.length > 0 ? (
                                    customers.map(cust => (
                                        <tr key={cust.id} className="hover:bg-[#f8fafc] transition-colors">
                                            <td className="p-3.5 font-bold text-[#0f172a]">{cust.name}</td>
                                            <td className="p-3.5 font-mono text-[#0284c7] font-semibold">{cust.phone}</td>
                                            <td className="p-3.5 text-[#64748b] text-xs truncate max-w-xs">{cust.address || '-'}</td>
                                            <td className="p-3.5 text-right">
                                                <button
                                                    type="button"
                                                    onClick={() => handleDelete(cust.id, cust.name)}
                                                    className="p-1.5 text-rose-600 hover:bg-rose-50 rounded-lg transition-colors"
                                                    title="Delete Customer"
                                                >
                                                    <Trash2 className="w-4 h-4" />
                                                </button>
                                            </td>
                                        </tr>
                                    ))
                                ) : (
                                    <tr>
                                        <td colSpan={4} className="p-8 text-center text-[#94a3b8]">
                                            No customers found.
                                        </td>
                                    </tr>
                                )}
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </AdminLayout>
    );
}
