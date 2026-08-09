import React from 'react';
import { Head, useForm, router } from '@inertiajs/react';
import AdminLayout from '@/Layouts/AdminLayout';
import {
    Briefcase,
    Plus,
    Trash2,
    Building,
    Phone,
    MapPin
} from 'lucide-react';

interface Supplier {
    id: number;
    company_name: string;
    contact_person?: string;
    phone?: string;
    address?: string;
}

interface SuppliersProps {
    suppliers: Supplier[];
}

export default function Suppliers({ suppliers = [] }: SuppliersProps) {
    const { data, setData, post, processing, reset, errors } = useForm({
        company_name: '',
        contact_person: '',
        phone: '',
        address: '',
    });

    const handleCreate = (e: React.FormEvent) => {
        e.preventDefault();
        post('/admin/api/suppliers', {
            onSuccess: () => reset(),
        });
    };

    const handleDelete = (id: number, name: string) => {
        if (confirm(`Are you sure you want to delete supplier "${name}"?`)) {
            router.delete(`/admin/api/suppliers/${id}`);
        }
    };

    return (
        <AdminLayout
            activePage="suppliers"
            pageTitle="Supplier Directory"
            pageSubtitle="Manage raw material & stock vendors and supplier contact details."
        >
            <Head title="Suppliers — Admin" />

            <div className="grid grid-cols-1 lg:grid-cols-12 gap-8">
                {/* LEFT: ADD NEW SUPPLIER FORM (4 COLS) */}
                <div className="lg:col-span-4 bg-white/90 backdrop-blur-xl border border-[#38bdf8]/30 p-7 rounded-2xl space-y-6 shadow-sm">
                    <div className="flex items-center gap-3 border-b border-[#e2e8f0] pb-4">
                        <div className="p-2.5 bg-[#e0f2fe] text-[#0284c7] rounded-xl">
                            <Building className="w-5 h-5" />
                        </div>
                        <div>
                            <h3 className="text-base font-serif font-bold text-[#0f172a] uppercase tracking-wide">
                                Add New Supplier
                            </h3>
                            <p className="text-[11px] text-[#64748b]">Save vendor for purchase orders</p>
                        </div>
                    </div>

                    <form onSubmit={handleCreate} className="space-y-4">
                        <div>
                            <label className="text-[11px] uppercase font-bold text-[#475569] tracking-wider block mb-1.5">
                                Company / Vendor Name *
                            </label>
                            <input
                                type="text"
                                value={data.company_name}
                                onChange={e => setData('company_name', e.target.value)}
                                required
                                placeholder="e.g. Royal Fragrance Supplies Ltd"
                                className="w-full border border-[#cbd5e1] px-4 py-2.5 rounded-xl text-[13px] font-medium outline-none focus:border-[#0284c7] bg-white shadow-2xs"
                            />
                            {errors.company_name && <p className="text-rose-600 text-xs mt-1">{errors.company_name}</p>}
                        </div>

                        <div>
                            <label className="text-[11px] uppercase font-bold text-[#475569] tracking-wider block mb-1.5">
                                Contact Person
                            </label>
                            <input
                                type="text"
                                value={data.contact_person}
                                onChange={e => setData('contact_person', e.target.value)}
                                placeholder="Contact Manager Name"
                                className="w-full border border-[#cbd5e1] px-4 py-2.5 rounded-xl text-[13px] font-medium outline-none focus:border-[#0284c7] bg-white shadow-2xs"
                            />
                        </div>

                        <div>
                            <label className="text-[11px] uppercase font-bold text-[#475569] tracking-wider block mb-1.5">
                                Phone Number
                            </label>
                            <input
                                type="text"
                                value={data.phone}
                                onChange={e => setData('phone', e.target.value)}
                                placeholder="017xxxxxxxx"
                                className="w-full border border-[#cbd5e1] px-4 py-2.5 rounded-xl text-[13px] font-mono font-medium outline-none focus:border-[#0284c7] bg-white shadow-2xs"
                            />
                        </div>

                        <div>
                            <label className="text-[11px] uppercase font-bold text-[#475569] tracking-wider block mb-1.5">
                                Office / Factory Address
                            </label>
                            <textarea
                                rows={2}
                                value={data.address}
                                onChange={e => setData('address', e.target.value)}
                                placeholder="Address details..."
                                className="w-full border border-[#cbd5e1] px-3.5 py-2 rounded-xl text-[12px] outline-none focus:border-[#0284c7] bg-white shadow-2xs"
                            />
                        </div>

                        <button
                            type="submit"
                            disabled={processing}
                            className="w-full bg-[#0284c7] hover:bg-[#0369a1] text-white py-3 rounded-xl text-[12px] font-bold uppercase tracking-wider transition-all shadow-md shadow-[#0284c7]/20 flex items-center justify-center gap-2"
                        >
                            <Plus className="w-4 h-4" /> Save Supplier
                        </button>
                    </form>
                </div>

                {/* RIGHT: SUPPLIER DIRECTORY TABLE (8 COLS) */}
                <div className="lg:col-span-8 bg-white/90 backdrop-blur-xl border border-[#38bdf8]/30 p-7 rounded-2xl space-y-5 shadow-sm">
                    <div className="flex items-center gap-3 border-b border-[#e2e8f0] pb-4">
                        <div className="p-2.5 bg-[#e0f2fe] text-[#0284c7] rounded-xl">
                            <Briefcase className="w-5 h-5" />
                        </div>
                        <div>
                            <h3 className="text-base font-serif font-bold text-[#0f172a] uppercase tracking-wide">
                                Supplier Directory
                            </h3>
                            <p className="text-[11px] text-[#64748b]">All registered vendors & raw material suppliers</p>
                        </div>
                    </div>

                    <div className="overflow-x-auto">
                        <table className="w-full text-left border-collapse text-[13px]">
                            <thead>
                                <tr className="bg-[#f8fafc] border-b border-[#e2e8f0] text-[11px] font-bold uppercase tracking-wider text-[#64748b]">
                                    <th className="p-3.5 rounded-tl-xl">Company Name</th>
                                    <th className="p-3.5">Contact Person</th>
                                    <th className="p-3.5">Phone Number</th>
                                    <th className="p-3.5">Address</th>
                                    <th className="p-3.5 text-right rounded-tr-xl">Action</th>
                                </tr>
                            </thead>
                            <tbody className="divide-y divide-[#e2e8f0]">
                                {suppliers.length > 0 ? (
                                    suppliers.map(sup => (
                                        <tr key={sup.id} className="hover:bg-[#f8fafc] transition-colors">
                                            <td className="p-3.5 font-bold text-[#0f172a]">{sup.company_name}</td>
                                            <td className="p-3.5 font-medium text-[#475569]">{sup.contact_person || '-'}</td>
                                            <td className="p-3.5 font-mono text-[#0284c7] font-semibold">{sup.phone || '-'}</td>
                                            <td className="p-3.5 text-[#64748b] text-xs truncate max-w-xs">{sup.address || '-'}</td>
                                            <td className="p-3.5 text-right">
                                                <button
                                                    type="button"
                                                    onClick={() => handleDelete(sup.id, sup.company_name)}
                                                    className="p-1.5 text-rose-600 hover:bg-rose-50 rounded-lg transition-colors"
                                                    title="Delete Supplier"
                                                >
                                                    <Trash2 className="w-4 h-4" />
                                                </button>
                                            </td>
                                        </tr>
                                    ))
                                ) : (
                                    <tr>
                                        <td colSpan={5} className="p-8 text-center text-[#94a3b8]">
                                            No suppliers found.
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
