import React, { useState } from 'react';
import { Head, Link, router } from '@inertiajs/react';
import AdminLayout from '@/Layouts/AdminLayout';
import {
    Boxes,
    PlusCircle,
    Layers,
    Search,
    Edit3,
    Trash2,
    ExternalLink,
    Filter,
    CheckCircle2,
    AlertTriangle,
    Tag
} from 'lucide-react';

interface Category {
    id: number;
    name: string;
}

interface Product {
    id: number;
    name: string;
    slug: string;
    price: number;
    stock: number;
    gender: string;
    category?: Category;
    primary_image_url?: string;
    is_featured?: boolean;
    is_new_arrival?: boolean;
    created_at?: string;
}

interface ProductsIndexProps {
    products: Product[];
    categories: Category[];
}

export default function ProductsIndex({ products = [], categories = [] }: ProductsIndexProps) {
    const [search, setSearch] = useState('');
    const [selectedCategory, setSelectedCategory] = useState<string>('All');
    const [selectedGender, setSelectedGender] = useState<string>('All');

    const totalStock = products.reduce((acc, p) => acc + (p.stock || 0), 0);
    const lowStockCount = products.filter(p => p.stock <= 10).length;
    const featuredCount = products.filter(p => p.is_featured).length;

    const filteredProducts = products.filter(p => {
        const matchesSearch = p.name.toLowerCase().includes(search.toLowerCase()) ||
            (p.category?.name && p.category.name.toLowerCase().includes(search.toLowerCase()));
        const matchesCategory = selectedCategory === 'All' || p.category?.name === selectedCategory;
        const matchesGender = selectedGender === 'All' || p.gender === selectedGender;
        return matchesSearch && matchesCategory && matchesGender;
    });

    const handleDelete = (id: number, name: string) => {
        if (confirm(`Are you sure you want to delete "${name}"? This action cannot be undone.`)) {
            router.delete(`/admin/products/${id}`);
        }
    };

    return (
        <AdminLayout
            activePage="products"
            pageTitle="Product Catalog Inventory"
            pageSubtitle="Live master database catalog and inventory stock tracker."
            headerActions={
                <>
                    <Link
                        href="/admin/products/add"
                        className="px-4 py-2 bg-[#0284c7] hover:bg-[#0369a1] text-white text-[12px] font-bold uppercase tracking-wider rounded-xl transition-all shadow-md shadow-[#0284c7]/20 flex items-center gap-1.5"
                    >
                        <PlusCircle className="w-4 h-4" /> Add New Product
                    </Link>
                    <Link
                        href="/admin/categories"
                        className="px-4 py-2 bg-white hover:bg-[#f8fafc] border border-[#cbd5e1] text-[#475569] text-[12px] font-bold uppercase tracking-wider rounded-xl transition-all shadow-2xs flex items-center gap-1.5"
                    >
                        <Layers className="w-4 h-4" /> Categories
                    </Link>
                </>
            }
        >
            <Head title="Product Catalog — Admin" />

            {/* METRICS RIBBON */}
            <div className="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                <div className="bg-white/90 backdrop-blur-xl border border-[#38bdf8]/30 p-5 rounded-2xl shadow-sm">
                    <span className="text-[11px] font-bold uppercase tracking-widest text-[#64748b] block">TOTAL PRODUCTS</span>
                    <div className="flex items-baseline justify-between mt-2">
                        <span className="text-2xl font-serif font-bold text-[#0f172a]">{products.length}</span>
                        <span className="text-xs font-bold text-[#0284c7] bg-[#e0f2fe] px-2.5 py-1 rounded-lg">Catalog Live</span>
                    </div>
                </div>

                <div className="bg-white/90 backdrop-blur-xl border border-[#38bdf8]/30 p-5 rounded-2xl shadow-sm">
                    <span className="text-[11px] font-bold uppercase tracking-widest text-[#64748b] block">TOTAL UNITS IN STOCK</span>
                    <div className="flex items-baseline justify-between mt-2">
                        <span className="text-2xl font-serif font-bold text-emerald-700">{totalStock.toLocaleString()}</span>
                        <span className="text-xs font-bold text-emerald-700 bg-emerald-50 px-2.5 py-1 rounded-lg">Available</span>
                    </div>
                </div>

                <div className="bg-white/90 backdrop-blur-xl border border-[#38bdf8]/30 p-5 rounded-2xl shadow-sm">
                    <span className="text-[11px] font-bold uppercase tracking-widest text-[#64748b] block">LOW STOCK ALERTS</span>
                    <div className="flex items-baseline justify-between mt-2">
                        <span className={`text-2xl font-serif font-bold ${lowStockCount > 0 ? 'text-amber-600' : 'text-slate-700'}`}>
                            {lowStockCount}
                        </span>
                        <span className={`text-xs font-bold px-2.5 py-1 rounded-lg ${lowStockCount > 0 ? 'text-amber-700 bg-amber-50' : 'text-slate-600 bg-slate-100'}`}>
                            ≤ 10 units
                        </span>
                    </div>
                </div>

                <div className="bg-white/90 backdrop-blur-xl border border-[#38bdf8]/30 p-5 rounded-2xl shadow-sm">
                    <span className="text-[11px] font-bold uppercase tracking-widest text-[#64748b] block">FEATURED CATALOG</span>
                    <div className="flex items-baseline justify-between mt-2">
                        <span className="text-2xl font-serif font-bold text-purple-700">{featuredCount}</span>
                        <span className="text-xs font-bold text-purple-700 bg-purple-50 px-2.5 py-1 rounded-lg">Homepage Hero</span>
                    </div>
                </div>
            </div>

            {/* CATALOG TABLE CARD */}
            <div className="bg-white/90 backdrop-blur-xl border border-[#38bdf8]/30 p-6 rounded-2xl space-y-5 shadow-sm">
                {/* SEARCH AND FILTERS */}
                <div className="flex flex-col md:flex-row justify-between items-start md:items-center gap-4 border-b border-[#e2e8f0] pb-4">
                    <div className="relative w-full md:w-80">
                        <Search className="w-4 h-4 absolute left-3 top-1/2 -translate-y-1/2 text-[#94a3b8]" />
                        <input
                            type="text"
                            value={search}
                            onChange={e => setSearch(e.target.value)}
                            placeholder="Search products by title or category..."
                            className="w-full pl-9 pr-4 py-2.5 text-[13px] border border-[#cbd5e1] rounded-xl outline-none focus:border-[#0284c7] bg-white text-[#0f172a] shadow-2xs"
                        />
                    </div>

                    <div className="flex flex-wrap items-center gap-3 w-full md:w-auto">
                        <select
                            value={selectedCategory}
                            onChange={e => setSelectedCategory(e.target.value)}
                            className="px-3.5 py-2.5 text-[12px] border border-[#cbd5e1] rounded-xl outline-none focus:border-[#0284c7] bg-white text-[#0f172a] font-semibold shadow-2xs"
                        >
                            <option value="All">All Categories</option>
                            {categories.map(cat => (
                                <option key={cat.id} value={cat.name}>
                                    {cat.name}
                                </option>
                            ))}
                        </select>

                        <select
                            value={selectedGender}
                            onChange={e => setSelectedGender(e.target.value)}
                            className="px-3.5 py-2.5 text-[12px] border border-[#cbd5e1] rounded-xl outline-none focus:border-[#0284c7] bg-white text-[#0f172a] font-semibold shadow-2xs"
                        >
                            <option value="All">All Gender Targets</option>
                            <option value="unisex">Unisex</option>
                            <option value="women">Women</option>
                            <option value="men">Men</option>
                        </select>
                    </div>
                </div>

                {/* TABLE */}
                <div className="overflow-x-auto">
                    <table className="w-full text-left border-collapse text-[13px]">
                        <thead>
                            <tr className="bg-[#f8fafc] border-b border-[#e2e8f0] text-[11px] font-bold uppercase tracking-wider text-[#64748b]">
                                <th className="p-3.5 rounded-tl-xl w-16">Item</th>
                                <th className="p-3.5">Product Title</th>
                                <th className="p-3.5">Category</th>
                                <th className="p-3.5">Gender</th>
                                <th className="p-3.5">Price</th>
                                <th className="p-3.5">Stock</th>
                                <th className="p-3.5">Badges</th>
                                <th className="p-3.5 text-right rounded-tr-xl">Actions</th>
                            </tr>
                        </thead>
                        <tbody className="divide-y divide-[#e2e8f0]">
                            {filteredProducts.length > 0 ? (
                                filteredProducts.map(prod => (
                                    <tr key={prod.id} className="hover:bg-[#f8fafc] transition-colors">
                                        <td className="p-3.5">
                                            {prod.primary_image_url ? (
                                                <img
                                                    src={prod.primary_image_url}
                                                    alt={prod.name}
                                                    className="w-10 h-10 object-cover rounded-lg border border-[#e2e8f0]"
                                                />
                                            ) : (
                                                <div className="w-10 h-10 rounded-lg bg-[#f1f5f9] flex items-center justify-center text-[#94a3b8]">
                                                    <Boxes className="w-5 h-5" />
                                                </div>
                                            )}
                                        </td>
                                        <td className="p-3.5">
                                            <div className="font-bold text-[#0f172a]">{prod.name}</div>
                                            <div className="text-[11px] font-mono text-[#64748b]">{prod.slug}</div>
                                        </td>
                                        <td className="p-3.5 font-medium text-[#0284c7]">
                                            {prod.category?.name || 'Unassigned'}
                                        </td>
                                        <td className="p-3.5 capitalize text-[#475569] font-medium">
                                            {prod.gender || 'unisex'}
                                        </td>
                                        <td className="p-3.5 font-mono font-bold text-[#0284c7]">
                                            ৳ {Number(prod.price).toFixed(2)}
                                        </td>
                                        <td className="p-3.5 font-mono">
                                            <span
                                                className={`px-2.5 py-1 rounded-full text-[11px] font-bold ${
                                                    prod.stock > 10
                                                        ? 'bg-emerald-50 text-emerald-700 border border-emerald-200'
                                                        : prod.stock > 0
                                                        ? 'bg-amber-50 text-amber-700 border border-amber-200'
                                                        : 'bg-rose-50 text-rose-700 border border-rose-200'
                                                }`}
                                            >
                                                {prod.stock} units
                                            </span>
                                        </td>
                                        <td className="p-3.5">
                                            <div className="flex items-center gap-1.5 flex-wrap">
                                                {prod.is_featured && (
                                                    <span className="px-2 py-0.5 rounded text-[10px] font-bold uppercase bg-purple-100 text-purple-800">
                                                        Featured
                                                    </span>
                                                )}
                                                {prod.is_new_arrival && (
                                                    <span className="px-2 py-0.5 rounded text-[10px] font-bold uppercase bg-blue-100 text-blue-800">
                                                        New
                                                    </span>
                                                )}
                                            </div>
                                        </td>
                                        <td className="p-3.5 text-right space-x-1.5">
                                            <Link
                                                href={`/admin/products/${prod.id}/edit`}
                                                className="p-1.5 inline-flex text-[#0284c7] hover:bg-[#e0f2fe] rounded-lg transition-colors"
                                                title="Edit Product"
                                            >
                                                <Edit3 className="w-4 h-4" />
                                            </Link>
                                            <button
                                                type="button"
                                                onClick={() => handleDelete(prod.id, prod.name)}
                                                className="p-1.5 text-rose-600 hover:bg-rose-50 rounded-lg transition-colors"
                                                title="Delete Product"
                                            >
                                                <Trash2 className="w-4 h-4" />
                                            </button>
                                        </td>
                                    </tr>
                                ))
                            ) : (
                                <tr>
                                    <td colSpan={8} className="p-8 text-center text-[#94a3b8]">
                                        No matching products found in catalog.
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
