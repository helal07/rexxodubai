import React, { useState } from 'react';
import { Head, useForm, router } from '@inertiajs/react';
import AdminLayout from '@/Layouts/AdminLayout';
import {
    Truck,
    Plus,
    Search,
    Edit2,
    Trash2,
    Save,
    MapPin,
    CheckCircle2,
    Sliders
} from 'lucide-react';

interface ChargeItem {
    id: number;
    district_name: string;
    charge: number;
    zone_type: string;
    is_active: boolean;
}

interface CourierChargesProps {
    charges: ChargeItem[];
    insideDhakaCount?: number;
    outsideDhakaCount?: number;
    customCount?: number;
    activeCount?: number;
}

export default function CourierCharges({
    charges = [],
    insideDhakaCount = 0,
    outsideDhakaCount = 0,
    customCount = 0,
    activeCount = 0,
}: CourierChargesProps) {
    const [search, setSearch] = useState('');
    const [zoneFilter, setZoneFilter] = useState('all');

    const { data, setData, post, processing, reset, errors } = useForm({
        district_name: '',
        charge: '',
        zone_type: 'outside_dhaka',
        is_active: true,
    });

    const filteredCharges = charges.filter(c => {
        const matchesSearch = c.district_name.toLowerCase().includes(search.toLowerCase());
        const matchesZone = zoneFilter === 'all' || c.zone_type === zoneFilter;
        return matchesSearch && matchesZone;
    });

    const handleCreate = (e: React.FormEvent) => {
        e.preventDefault();
        post('/admin/courier-charges', {
            onSuccess: () => reset(),
        });
    };

    const handleDelete = (id: number, name: string) => {
        if (confirm(`Are you sure you want to delete delivery charge for "${name}"?`)) {
            router.delete(`/admin/courier-charges/${id}`);
        }
    };

    const handleToggleActive = (chargeItem: ChargeItem) => {
        router.put(`/admin/courier-charges/${chargeItem.id}`, {
            district_name: chargeItem.district_name,
            charge: chargeItem.charge,
            zone_type: chargeItem.zone_type,
            is_active: !chargeItem.is_active,
        });
    };

    return (
        <AdminLayout
            activePage="courier_charges"
            pageTitle="District Delivery Charges & Shipping Rates"
            pageSubtitle="Configure regional delivery rates across all 64 districts in Bangladesh."
        >
            <Head title="Courier Charges — Admin" />

            {/* STATS RIBBON */}
            <div className="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                <div className="bg-white/90 backdrop-blur-xl border border-[#38bdf8]/30 p-5 rounded-2xl shadow-sm">
                    <span className="text-[11px] font-bold uppercase tracking-widest text-[#64748b] block">Total Districts</span>
                    <h3 className="text-2xl font-serif font-bold text-[#0f172a] mt-1">{charges.length} Zones</h3>
                </div>

                <div className="bg-white/90 backdrop-blur-xl border border-[#38bdf8]/30 p-5 rounded-2xl shadow-sm">
                    <span className="text-[11px] font-bold uppercase tracking-widest text-[#64748b] block">Inside Dhaka (৳60)</span>
                    <h3 className="text-2xl font-serif font-bold text-emerald-600 mt-1">{insideDhakaCount} Districts</h3>
                </div>

                <div className="bg-white/90 backdrop-blur-xl border border-[#38bdf8]/30 p-5 rounded-2xl shadow-sm">
                    <span className="text-[11px] font-bold uppercase tracking-widest text-[#64748b] block">Outside Dhaka (৳120)</span>
                    <h3 className="text-2xl font-serif font-bold text-[#0284c7] mt-1">{outsideDhakaCount} Districts</h3>
                </div>

                <div className="bg-white/90 backdrop-blur-xl border border-[#38bdf8]/30 p-5 rounded-2xl shadow-sm">
                    <span className="text-[11px] font-bold uppercase tracking-widest text-[#64748b] block">Active Shipping Zones</span>
                    <h3 className="text-2xl font-serif font-bold text-purple-700 mt-1">{activeCount} Active</h3>
                </div>
            </div>

            <div className="grid grid-cols-1 lg:grid-cols-12 gap-8">
                {/* LEFT: ADD NEW DISTRICT CHARGE FORM (4 COLS) */}
                <div className="lg:col-span-4 bg-white/90 backdrop-blur-xl border border-[#38bdf8]/30 p-7 rounded-2xl space-y-6 shadow-sm">
                    <div className="flex items-center gap-3 border-b border-[#e2e8f0] pb-4">
                        <div className="p-2.5 bg-[#e0f2fe] text-[#0284c7] rounded-xl">
                            <Plus className="w-5 h-5" />
                        </div>
                        <div>
                            <h3 className="text-base font-serif font-bold text-[#0f172a] uppercase tracking-wide">
                                Add District Rate
                            </h3>
                            <p className="text-[11px] text-[#64748b]">Configure custom delivery fee</p>
                        </div>
                    </div>

                    <form onSubmit={handleCreate} className="space-y-4">
                        <div>
                            <label className="text-[11px] uppercase font-bold text-[#475569] tracking-wider block mb-1.5">
                                District / City Name *
                            </label>
                            <input
                                type="text"
                                value={data.district_name}
                                onChange={e => setData('district_name', e.target.value)}
                                required
                                placeholder="e.g. Chittagong, Sylhet, Gazipur"
                                className="w-full border border-[#cbd5e1] px-4 py-2.5 rounded-xl text-[13px] font-medium outline-none focus:border-[#0284c7] bg-white shadow-2xs"
                            />
                            {errors.district_name && <p className="text-rose-600 text-xs mt-1">{errors.district_name}</p>}
                        </div>

                        <div>
                            <label className="text-[11px] uppercase font-bold text-[#475569] tracking-wider block mb-1.5">
                                Delivery Charge (৳) *
                            </label>
                            <input
                                type="number"
                                step="0.01"
                                value={data.charge}
                                onChange={e => setData('charge', e.target.value)}
                                required
                                placeholder="120.00"
                                className="w-full border border-[#cbd5e1] px-4 py-2.5 rounded-xl text-[13px] font-bold font-mono outline-none focus:border-[#0284c7] bg-white shadow-2xs text-[#0284c7]"
                            />
                            {errors.charge && <p className="text-rose-600 text-xs mt-1">{errors.charge}</p>}
                        </div>

                        <div>
                            <label className="text-[11px] uppercase font-bold text-[#475569] tracking-wider block mb-1.5">
                                Region Category
                            </label>
                            <select
                                value={data.zone_type}
                                onChange={e => setData('zone_type', e.target.value)}
                                className="w-full border border-[#cbd5e1] px-3.5 py-2.5 rounded-xl text-[12px] font-medium outline-none focus:border-[#0284c7] bg-white shadow-2xs"
                            >
                                <option value="inside_dhaka">Inside Dhaka</option>
                                <option value="outside_dhaka">Outside Dhaka</option>
                                <option value="custom">Custom Special Zone</option>
                            </select>
                        </div>

                        <button
                            type="submit"
                            disabled={processing}
                            className="w-full bg-[#0284c7] hover:bg-[#0369a1] text-white py-3 rounded-xl text-[12px] font-bold uppercase tracking-wider transition-all shadow-md shadow-[#0284c7]/20 flex items-center justify-center gap-2"
                        >
                            <Plus className="w-4 h-4" /> Save District Charge
                        </button>
                    </form>
                </div>

                {/* RIGHT: DISTRICT RATES TABLE (8 COLS) */}
                <div className="lg:col-span-8 bg-white/90 backdrop-blur-xl border border-[#38bdf8]/30 p-7 rounded-2xl space-y-5 shadow-sm">
                    <div className="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 border-b border-[#e2e8f0] pb-4">
                        <div className="relative w-full sm:w-64">
                            <Search className="w-4 h-4 absolute left-3 top-1/2 -translate-y-1/2 text-[#94a3b8]" />
                            <input
                                type="text"
                                value={search}
                                onChange={e => setSearch(e.target.value)}
                                placeholder="Search district name..."
                                className="w-full pl-9 pr-4 py-2 text-[12px] border border-[#cbd5e1] rounded-xl outline-none focus:border-[#0284c7] bg-white"
                            />
                        </div>

                        <div className="flex items-center gap-2">
                            {['all', 'inside_dhaka', 'outside_dhaka', 'custom'].map(z => (
                                <button
                                    key={z}
                                    type="button"
                                    onClick={() => setZoneFilter(z)}
                                    className={`px-3 py-1.5 rounded-lg text-[11px] font-bold uppercase tracking-wider transition-all ${
                                        zoneFilter === z
                                            ? 'bg-[#0284c7] text-white'
                                            : 'bg-white border border-[#cbd5e1] text-[#475569] hover:bg-[#f8fafc]'
                                    }`}
                                >
                                    {z.replace('_', ' ')}
                                </button>
                            ))}
                        </div>
                    </div>

                    <div className="overflow-x-auto">
                        <table className="w-full text-left border-collapse text-[13px]">
                            <thead>
                                <tr className="bg-[#f8fafc] border-b border-[#e2e8f0] text-[11px] font-bold uppercase tracking-wider text-[#64748b]">
                                    <th className="p-3.5 rounded-tl-xl">District / City</th>
                                    <th className="p-3.5">Zone Type</th>
                                    <th className="p-3.5">Delivery Rate</th>
                                    <th className="p-3.5">Status</th>
                                    <th className="p-3.5 text-right rounded-tr-xl">Action</th>
                                </tr>
                            </thead>
                            <tbody className="divide-y divide-[#e2e8f0]">
                                {filteredCharges.length > 0 ? (
                                    filteredCharges.map(item => (
                                        <tr key={item.id} className="hover:bg-[#f8fafc] transition-colors">
                                            <td className="p-3.5 font-bold text-[#0f172a]">{item.district_name}</td>
                                            <td className="p-3.5 text-xs font-semibold capitalize text-[#64748b]">
                                                {item.zone_type.replace('_', ' ')}
                                            </td>
                                            <td className="p-3.5 font-mono font-bold text-[#0284c7]">
                                                ৳ {Number(item.charge).toFixed(2)}
                                            </td>
                                            <td className="p-3.5">
                                                <button
                                                    type="button"
                                                    onClick={() => handleToggleActive(item)}
                                                    className={`px-2.5 py-0.5 rounded-full text-[10px] font-bold uppercase ${
                                                        item.is_active
                                                            ? 'bg-emerald-100 text-emerald-800'
                                                            : 'bg-slate-100 text-slate-600'
                                                    }`}
                                                >
                                                    {item.is_active ? 'Active' : 'Disabled'}
                                                </button>
                                            </td>
                                            <td className="p-3.5 text-right">
                                                <button
                                                    type="button"
                                                    onClick={() => handleDelete(item.id, item.district_name)}
                                                    className="p-1.5 text-rose-600 hover:bg-rose-50 rounded-lg transition-colors"
                                                    title="Delete District Rate"
                                                >
                                                    <Trash2 className="w-4 h-4" />
                                                </button>
                                            </td>
                                        </tr>
                                    ))
                                ) : (
                                    <tr>
                                        <td colSpan={5} className="p-8 text-center text-[#94a3b8]">
                                            No delivery charges found.
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
