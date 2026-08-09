import React, { useState } from 'react';
import { Head, useForm, router } from '@inertiajs/react';
import AdminLayout from '@/Layouts/AdminLayout';
import { SlidersHorizontal, Plus, Edit, Trash2, ListTree, Save, X } from 'lucide-react';

interface MenuItem {
    id: number;
    parent_id: number | null;
    label: string;
    url: string;
    sort_order: number;
    children?: MenuItem[];
    parent?: MenuItem;
}

interface MenusProps {
    items: MenuItem[];
    parentItems: MenuItem[];
}

export default function Menus({ items, parentItems }: MenusProps) {
    const [isEditing, setIsEditing] = useState(false);
    const [editId, setEditId] = useState<number | null>(null);

    const { data, setData, post, put, delete: destroy, processing, reset, errors } = useForm({
        label: '',
        url: '',
        parent_id: '',
        sort_order: '',
    });

    const handleEdit = (item: MenuItem) => {
        setIsEditing(true);
        setEditId(item.id);
        setData({
            label: item.label,
            url: item.url,
            parent_id: item.parent_id ? item.parent_id.toString() : '',
            sort_order: item.sort_order.toString(),
        });
        window.scrollTo({ top: 0, behavior: 'smooth' });
    };

    const handleCancelEdit = () => {
        setIsEditing(false);
        setEditId(null);
        reset();
    };

    const handleSubmit = (e: React.FormEvent) => {
        e.preventDefault();
        if (isEditing && editId) {
            put(`/admin/menus/${editId}`, {
                preserveScroll: true,
                onSuccess: () => {
                    handleCancelEdit();
                }
            });
        } else {
            post('/admin/menus', {
                preserveScroll: true,
                onSuccess: () => {
                    reset();
                }
            });
        }
    };

    const handleDelete = (id: number) => {
        if (confirm('Are you sure you want to delete this menu item?')) {
            destroy(`/admin/menus/${id}`, {
                preserveScroll: true,
            });
        }
    };

    // Filter to only show parent items in the table, children will be shown underneath them
    const rootItems = items.filter(item => item.parent_id === null).sort((a, b) => a.sort_order - b.sort_order);

    return (
        <AdminLayout
            activePage="menus"
            pageTitle="Navigation Menu Builder"
            pageSubtitle="Manage header and footer navigation links."
        >
            <Head title="Menu Builder — Admin" />

            <div className="grid grid-cols-1 lg:grid-cols-3 gap-6 items-start">
                {/* Form Section */}
                <div className="lg:col-span-1 bg-white/90 backdrop-blur-xl border border-[#38bdf8]/30 p-6 rounded-2xl shadow-sm sticky top-24">
                    <h3 className="text-[15px] font-serif font-bold text-[#0f172a] uppercase border-b border-[#e2e8f0] pb-3 mb-5 flex items-center gap-2">
                        {isEditing ? <Edit className="w-4 h-4 text-[#0284c7]" /> : <Plus className="w-4 h-4 text-[#0284c7]" />}
                        {isEditing ? 'Edit Menu Item' : 'Add New Menu Item'}
                    </h3>

                    <form onSubmit={handleSubmit} className="space-y-4">
                        <div>
                            <label className="text-[11px] uppercase font-bold text-[#475569] tracking-wider block mb-1.5">
                                Label / Title <span className="text-rose-500">*</span>
                            </label>
                            <input
                                type="text"
                                value={data.label}
                                onChange={e => setData('label', e.target.value)}
                                placeholder="e.g. Perfumes"
                                required
                                className="w-full border border-[#cbd5e1] px-4 py-2.5 rounded-xl text-[13px] outline-none focus:border-[#0284c7] bg-white shadow-2xs"
                            />
                            {errors.label && <div className="text-rose-500 text-xs mt-1">{errors.label}</div>}
                        </div>

                        <div>
                            <label className="text-[11px] uppercase font-bold text-[#475569] tracking-wider block mb-1.5">
                                Target URL <span className="text-rose-500">*</span>
                            </label>
                            <input
                                type="text"
                                value={data.url}
                                onChange={e => setData('url', e.target.value)}
                                placeholder="e.g. /perfumes"
                                required
                                className="w-full border border-[#cbd5e1] px-4 py-2.5 rounded-xl text-[13px] outline-none focus:border-[#0284c7] bg-white shadow-2xs"
                            />
                            {errors.url && <div className="text-rose-500 text-xs mt-1">{errors.url}</div>}
                        </div>

                        <div>
                            <label className="text-[11px] uppercase font-bold text-[#475569] tracking-wider block mb-1.5">
                                Parent Menu (Optional)
                            </label>
                            <select
                                value={data.parent_id}
                                onChange={e => setData('parent_id', e.target.value)}
                                className="w-full border border-[#cbd5e1] px-4 py-2.5 rounded-xl text-[13px] outline-none focus:border-[#0284c7] bg-white shadow-2xs appearance-none"
                            >
                                <option value="">-- No Parent (Root Level) --</option>
                                {parentItems.map(parent => (
                                    <option key={parent.id} value={parent.id}>
                                        {parent.label}
                                    </option>
                                ))}
                            </select>
                            {errors.parent_id && <div className="text-rose-500 text-xs mt-1">{errors.parent_id}</div>}
                        </div>

                        <div>
                            <label className="text-[11px] uppercase font-bold text-[#475569] tracking-wider block mb-1.5">
                                Sort Order
                            </label>
                            <input
                                type="number"
                                value={data.sort_order}
                                onChange={e => setData('sort_order', e.target.value)}
                                placeholder="e.g. 1"
                                className="w-full border border-[#cbd5e1] px-4 py-2.5 rounded-xl text-[13px] outline-none focus:border-[#0284c7] bg-white shadow-2xs"
                            />
                        </div>

                        <div className="pt-4 flex items-center gap-3">
                            <button
                                type="submit"
                                disabled={processing}
                                className="flex-1 py-2.5 bg-[#0284c7] hover:bg-[#0369a1] text-white rounded-xl text-[12px] font-bold uppercase tracking-wider shadow-md shadow-[#0284c7]/20 flex items-center justify-center gap-2"
                            >
                                <Save className="w-4 h-4" />
                                {isEditing ? 'Update Item' : 'Add Item'}
                            </button>
                            {isEditing && (
                                <button
                                    type="button"
                                    onClick={handleCancelEdit}
                                    className="px-4 py-2.5 bg-gray-100 hover:bg-gray-200 text-gray-700 rounded-xl text-[12px] font-bold uppercase tracking-wider flex items-center gap-2"
                                >
                                    <X className="w-4 h-4" /> Cancel
                                </button>
                            )}
                        </div>
                    </form>
                </div>

                {/* Table Section */}
                <div className="lg:col-span-2 bg-white/90 backdrop-blur-xl border border-[#38bdf8]/30 rounded-2xl shadow-sm overflow-hidden">
                    <div className="p-5 border-b border-[#e2e8f0] bg-[#f8fafc] flex justify-between items-center">
                        <h3 className="text-[15px] font-serif font-bold text-[#0f172a] uppercase flex items-center gap-2">
                            <ListTree className="w-5 h-5 text-[#0284c7]" /> Current Menus
                        </h3>
                    </div>
                    <div className="p-0 overflow-x-auto">
                        <table className="w-full text-left border-collapse">
                            <thead>
                                <tr className="bg-[#f1f5f9] border-b border-[#e2e8f0]">
                                    <th className="p-3 text-[10px] font-bold text-[#64748b] uppercase tracking-wider pl-5">Label</th>
                                    <th className="p-3 text-[10px] font-bold text-[#64748b] uppercase tracking-wider">URL</th>
                                    <th className="p-3 text-[10px] font-bold text-[#64748b] uppercase tracking-wider">Sort</th>
                                    <th className="p-3 text-[10px] font-bold text-[#64748b] uppercase tracking-wider text-right pr-5">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                {rootItems.length === 0 ? (
                                    <tr>
                                        <td colSpan={4} className="p-8 text-center text-[13px] text-[#64748b]">
                                            No menu items found. Create one to get started.
                                        </td>
                                    </tr>
                                ) : (
                                    rootItems.map((item) => (
                                        <React.Fragment key={item.id}>
                                            <tr className="border-b border-[#e2e8f0] hover:bg-[#f8fafc] transition-colors group">
                                                <td className="p-3 pl-5">
                                                    <span className="font-bold text-[#0f172a] text-[13px]">{item.label}</span>
                                                </td>
                                                <td className="p-3">
                                                    <span className="text-[12px] text-[#0284c7] font-mono bg-[#e0f2fe] px-2 py-0.5 rounded-md">{item.url}</span>
                                                </td>
                                                <td className="p-3">
                                                    <span className="text-[12px] text-[#64748b] font-mono">{item.sort_order}</span>
                                                </td>
                                                <td className="p-3 pr-5 text-right space-x-2">
                                                    <button
                                                        onClick={() => handleEdit(item)}
                                                        className="p-1.5 text-blue-600 bg-blue-50 hover:bg-blue-100 rounded-lg transition-colors"
                                                        title="Edit"
                                                    >
                                                        <Edit className="w-4 h-4" />
                                                    </button>
                                                    <button
                                                        onClick={() => handleDelete(item.id)}
                                                        className="p-1.5 text-rose-600 bg-rose-50 hover:bg-rose-100 rounded-lg transition-colors"
                                                        title="Delete"
                                                    >
                                                        <Trash2 className="w-4 h-4" />
                                                    </button>
                                                </td>
                                            </tr>
                                            {/* Render Children */}
                                            {item.children && item.children.sort((a, b) => a.sort_order - b.sort_order).map(child => (
                                                <tr key={child.id} className="border-b border-[#e2e8f0] hover:bg-[#f8fafc] bg-[#fafafa] transition-colors group">
                                                    <td className="p-3 pl-10 flex items-center gap-2">
                                                        <div className="w-3 h-[1px] bg-[#cbd5e1]"></div>
                                                        <span className="font-semibold text-[#334155] text-[12px]">{child.label}</span>
                                                    </td>
                                                    <td className="p-3">
                                                        <span className="text-[12px] text-[#0369a1] font-mono bg-[#e0f2fe]/50 px-2 py-0.5 rounded-md">{child.url}</span>
                                                    </td>
                                                    <td className="p-3">
                                                        <span className="text-[12px] text-[#64748b] font-mono">{child.sort_order}</span>
                                                    </td>
                                                    <td className="p-3 pr-5 text-right space-x-2">
                                                        <button
                                                            onClick={() => handleEdit(child)}
                                                            className="p-1.5 text-blue-600 bg-blue-50 hover:bg-blue-100 rounded-lg transition-colors"
                                                            title="Edit"
                                                        >
                                                            <Edit className="w-4 h-4" />
                                                        </button>
                                                        <button
                                                            onClick={() => handleDelete(child.id)}
                                                            className="p-1.5 text-rose-600 bg-rose-50 hover:bg-rose-100 rounded-lg transition-colors"
                                                            title="Delete"
                                                        >
                                                            <Trash2 className="w-4 h-4" />
                                                        </button>
                                                    </td>
                                                </tr>
                                            ))}
                                        </React.Fragment>
                                    ))
                                )}
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </AdminLayout>
    );
}
