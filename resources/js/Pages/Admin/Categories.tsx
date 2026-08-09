import React, { useState } from 'react';
import { Head, useForm, router } from '@inertiajs/react';
import AdminLayout from '@/Layouts/AdminLayout';
import {
    Layers,
    Plus,
    Edit2,
    Trash2,
    Check,
    X,
    FolderPlus,
    Tag
} from 'lucide-react';

interface Category {
    id: number;
    parent_id?: number | null;
    name: string;
    slug?: string;
    description?: string;
    image_url?: string;
    sort_order?: number;
    is_active?: boolean;
    children?: Category[];
}

interface CategoriesProps {
    categories: Category[];
}

export default function Categories({ categories = [] }: CategoriesProps) {
    const { data, setData, post, put, processing, reset, errors } = useForm({
        name: '',
        parent_id: '',
        description: '',
        sort_order: '',
    });

    const [editingId, setEditingId] = useState<number | null>(null);
    const [editData, setEditData] = useState<{ name: string; parent_id: string; description: string }>({
        name: '',
        parent_id: '',
        description: ''
    });

    const rootCategories = categories.filter(c => !c.parent_id);

    const handleCreate = (e: React.FormEvent) => {
        e.preventDefault();
        post('/admin/categories', {
            onSuccess: () => reset(),
        });
    };

    const startEditing = (cat: Category) => {
        setEditingId(cat.id);
        setEditData({
            name: cat.name,
            parent_id: cat.parent_id ? String(cat.parent_id) : '',
            description: cat.description || ''
        });
    };

    const handleUpdate = (e: React.FormEvent, id: number) => {
        e.preventDefault();
        router.put(`/admin/categories/${id}`, editData, {
            onSuccess: () => setEditingId(null),
        });
    };

    const handleDelete = (id: number, name: string) => {
        if (confirm(`Are you sure you want to delete category "${name}"?`)) {
            router.delete(`/admin/categories/${id}`);
        }
    };

    return (
        <AdminLayout
            activePage="categories"
            pageTitle="Category & Subcategory Hierarchy"
            pageSubtitle="Organize products into main categories (Bags, Shoes, Perfumes) and subcategories."
        >
            <Head title="Categories — Admin" />

            <div className="grid grid-cols-1 lg:grid-cols-12 gap-8">
                {/* LEFT: ADD NEW CATEGORY FORM (4 COLS) */}
                <div className="lg:col-span-4 bg-white/90 backdrop-blur-xl border border-[#38bdf8]/30 p-7 rounded-2xl space-y-6 shadow-sm">
                    <div className="flex items-center gap-3 border-b border-[#e2e8f0] pb-4">
                        <div className="p-2.5 bg-[#e0f2fe] text-[#0284c7] rounded-xl">
                            <FolderPlus className="w-5 h-5" />
                        </div>
                        <div>
                            <h3 className="text-base font-serif font-bold text-[#0f172a] uppercase tracking-wide">
                                Add New Category
                            </h3>
                            <p className="text-[11px] text-[#64748b]">Create main category or subcategory</p>
                        </div>
                    </div>

                    <form onSubmit={handleCreate} className="space-y-4">
                        <div>
                            <label className="text-[11px] uppercase font-bold text-[#475569] tracking-wider block mb-1.5">
                                Category Name *
                            </label>
                            <input
                                type="text"
                                value={data.name}
                                onChange={e => setData('name', e.target.value)}
                                required
                                placeholder="e.g. Leather Bags, Footwear, Perfumes"
                                className="w-full border border-[#cbd5e1] px-4 py-2.5 rounded-xl text-[13px] font-medium outline-none focus:border-[#0284c7] bg-white shadow-2xs"
                            />
                            {errors.name && <p className="text-rose-600 text-xs mt-1">{errors.name}</p>}
                        </div>

                        <div>
                            <label className="text-[11px] uppercase font-bold text-[#475569] tracking-wider block mb-1.5">
                                Parent Category (Optional)
                            </label>
                            <select
                                value={data.parent_id}
                                onChange={e => setData('parent_id', e.target.value)}
                                className="w-full border border-[#cbd5e1] px-3.5 py-2.5 rounded-xl text-[12px] font-medium outline-none focus:border-[#0284c7] bg-white shadow-2xs"
                            >
                                <option value="">None (Main Root Category)</option>
                                {rootCategories.map(cat => (
                                    <option key={cat.id} value={cat.id}>
                                        {cat.name} (Main)
                                    </option>
                                ))}
                            </select>
                        </div>

                        <div>
                            <label className="text-[11px] uppercase font-bold text-[#475569] tracking-wider block mb-1.5">
                                Description (Optional)
                            </label>
                            <textarea
                                rows={2}
                                value={data.description}
                                onChange={e => setData('description', e.target.value)}
                                placeholder="Short description of items in this category..."
                                className="w-full border border-[#cbd5e1] px-3.5 py-2 rounded-xl text-[12px] outline-none focus:border-[#0284c7] bg-white shadow-2xs"
                            />
                        </div>

                        <button
                            type="submit"
                            disabled={processing}
                            className="w-full bg-[#0284c7] hover:bg-[#0369a1] text-white py-3 rounded-xl text-[12px] font-bold uppercase tracking-wider transition-all shadow-md shadow-[#0284c7]/20 flex items-center justify-center gap-2"
                        >
                            <Plus className="w-4 h-4" /> Save Category
                        </button>
                    </form>
                </div>

                {/* RIGHT: MASTER CATEGORY TREE TABLE (8 COLS) */}
                <div className="lg:col-span-8 bg-white/90 backdrop-blur-xl border border-[#38bdf8]/30 p-7 rounded-2xl space-y-5 shadow-sm">
                    <div className="flex items-center gap-3 border-b border-[#e2e8f0] pb-4">
                        <div className="p-2.5 bg-[#e0f2fe] text-[#0284c7] rounded-xl">
                            <Layers className="w-5 h-5" />
                        </div>
                        <div>
                            <h3 className="text-base font-serif font-bold text-[#0f172a] uppercase tracking-wide">
                                Master Category Tree
                            </h3>
                            <p className="text-[11px] text-[#64748b]">All main store categories and nested subcategories</p>
                        </div>
                    </div>

                    <div className="space-y-4">
                        {rootCategories.length > 0 ? (
                            rootCategories.map(cat => {
                                const subcats = categories.filter(sub => sub.parent_id === cat.id);
                                return (
                                    <div key={cat.id} className="border border-[#e2e8f0] rounded-2xl p-4 bg-[#f8fafc] space-y-3">
                                        {editingId === cat.id ? (
                                            <form onSubmit={e => handleUpdate(e, cat.id)} className="flex items-center gap-2">
                                                <input
                                                    type="text"
                                                    value={editData.name}
                                                    onChange={e => setEditData({ ...editData, name: e.target.value })}
                                                    className="px-3 py-1.5 border rounded-lg text-xs font-bold flex-1"
                                                />
                                                <button type="submit" className="p-1.5 bg-emerald-600 text-white rounded-lg">
                                                    <Check className="w-4 h-4" />
                                                </button>
                                                <button type="button" onClick={() => setEditingId(null)} className="p-1.5 bg-slate-200 text-slate-700 rounded-lg">
                                                    <X className="w-4 h-4" />
                                                </button>
                                            </form>
                                        ) : (
                                            <div className="flex items-center justify-between">
                                                <div className="flex items-center gap-2">
                                                    <Tag className="w-4 h-4 text-[#0284c7]" />
                                                    <span className="font-bold text-sm text-[#0f172a]">{cat.name}</span>
                                                    <span className="text-[10px] font-bold uppercase bg-[#e0f2fe] text-[#0284c7] px-2 py-0.5 rounded-full">
                                                        Main Category
                                                    </span>
                                                </div>
                                                <div className="flex items-center gap-1">
                                                    <button
                                                        type="button"
                                                        onClick={() => startEditing(cat)}
                                                        className="p-1.5 text-[#0284c7] hover:bg-[#e0f2fe] rounded-lg transition-colors"
                                                    >
                                                        <Edit2 className="w-3.5 h-3.5" />
                                                    </button>
                                                    <button
                                                        type="button"
                                                        onClick={() => handleDelete(cat.id, cat.name)}
                                                        className="p-1.5 text-rose-600 hover:bg-rose-50 rounded-lg transition-colors"
                                                    >
                                                        <Trash2 className="w-3.5 h-3.5" />
                                                    </button>
                                                </div>
                                            </div>
                                        )}

                                        {/* SUBCATEGORIES */}
                                        {subcats.length > 0 && (
                                            <div className="pl-6 space-y-2 border-l-2 border-[#cbd5e1] pt-1">
                                                {subcats.map(sub => (
                                                    <div key={sub.id} className="flex items-center justify-between bg-white p-2.5 rounded-xl border border-[#e2e8f0]">
                                                        {editingId === sub.id ? (
                                                            <form onSubmit={e => handleUpdate(e, sub.id)} className="flex items-center gap-2 w-full">
                                                                <input
                                                                    type="text"
                                                                    value={editData.name}
                                                                    onChange={e => setEditData({ ...editData, name: e.target.value })}
                                                                    className="px-3 py-1 border rounded-lg text-xs font-bold flex-1"
                                                                />
                                                                <button type="submit" className="p-1.5 bg-emerald-600 text-white rounded-lg">
                                                                    <Check className="w-4 h-4" />
                                                                </button>
                                                                <button type="button" onClick={() => setEditingId(null)} className="p-1.5 bg-slate-200 text-slate-700 rounded-lg">
                                                                    <X className="w-4 h-4" />
                                                                </button>
                                                            </form>
                                                        ) : (
                                                            <>
                                                                <span className="text-xs font-semibold text-[#334155]">↳ {sub.name}</span>
                                                                <div className="flex items-center gap-1">
                                                                    <button
                                                                        type="button"
                                                                        onClick={() => startEditing(sub)}
                                                                        className="p-1 text-[#0284c7] hover:bg-[#e0f2fe] rounded-lg"
                                                                    >
                                                                        <Edit2 className="w-3.5 h-3.5" />
                                                                    </button>
                                                                    <button
                                                                        type="button"
                                                                        onClick={() => handleDelete(sub.id, sub.name)}
                                                                        className="p-1 text-rose-600 hover:bg-rose-50 rounded-lg"
                                                                    >
                                                                        <Trash2 className="w-3.5 h-3.5" />
                                                                    </button>
                                                                </div>
                                                            </>
                                                        )}
                                                    </div>
                                                ))}
                                            </div>
                                        )}
                                    </div>
                                );
                            })
                        ) : (
                            <div className="p-8 text-center text-[#94a3b8]">
                                No categories created yet.
                            </div>
                        )}
                    </div>
                </div>
            </div>
        </AdminLayout>
    );
}
