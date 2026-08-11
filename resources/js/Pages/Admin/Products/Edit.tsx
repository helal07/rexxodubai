import React from 'react';
import { Head, useForm, Link } from '@inertiajs/react';
import AdminLayout from '@/Layouts/AdminLayout';
import {
    Package,
    Tag,
    Sparkles,
    Image as ImageIcon,
    FileText,
    Globe,
    Check,
    Plus,
    Trash2,
    ArrowLeft,
    ExternalLink,
    AlertCircle
} from 'lucide-react';

interface Category {
    id: number;
    name: string;
    parent_id?: number | null;
}

interface ProductData {
    id?: number;
    name?: string;
    slug?: string;
    category_id?: number | null;
    gender?: string;
    price?: number | string;
    stock?: number;
    concentration?: string;
    sizes?: string | string[];
    scent_family?: string;
    notes_top?: string;
    notes_heart?: string;
    notes_base?: string;
    short_description?: string;
    description?: string;
    primary_image_url?: string;
    secondary_image_url?: string;
    og_image_url?: string;
    is_featured?: boolean;
    is_new_arrival?: boolean;
    meta_title?: string;
    meta_description?: string;
    meta_keywords?: string;
    updated_at?: string;
}

interface ProductEditProps {
    product?: ProductData;
    categories: Category[];
    variants?: any[];
}

export default function ProductEdit({ product = {}, categories = [], variants = [] }: ProductEditProps) {
    const isEdit = Boolean(product.id);

    const initialSizes = Array.isArray(product.sizes) ? product.sizes.join(', ') : (product.sizes || '');
    
    // Map existing product variants
    const initialVariants = (product as any).variants ? (product as any).variants.map((v: any) => ({
        variant_id: v.id,
        price: v.pivot.price,
        stock: v.pivot.stock
    })) : [];

    const { data, setData, post, put, processing, errors } = useForm({
        name: product.name || '',
        slug: product.slug || '',
        category_id: product.category_id || '',
        gender: product.gender || 'unisex',
        price: product.price || '',
        stock: product.stock !== undefined ? product.stock : 50,
        concentration: product.concentration || '',
        sizes: initialSizes,
        variants: initialVariants,
        scent_family: product.scent_family || '',
        notes_top: product.notes_top || '',
        notes_heart: product.notes_heart || '',
        notes_base: product.notes_base || '',
        short_description: product.short_description || '',
        description: product.description || '',
        primary_image_url: product.primary_image_url || '',
        primary_image_file: null as File | null,
        secondary_image_url: product.secondary_image_url || '',
        secondary_image_file: null as File | null,
        og_image_url: product.og_image_url || '',
        og_image_file: null as File | null,
        is_featured: product.is_featured || false,
        is_new_arrival: product.is_new_arrival || true,
        meta_title: product.meta_title || '',
        meta_description: product.meta_description || '',
        meta_keywords: product.meta_keywords || '',
    });

    const handleSubmit = (e: React.FormEvent) => {
        e.preventDefault();
        if (isEdit && product.id) {
            post(`/admin/products/${product.id}`, {
                preserveScroll: true,
            });
        } else {
            post('/admin/products', {
                preserveScroll: true,
            });
        }
    };

    const rootCats = categories.filter(c => !c.parent_id);

    return (
        <AdminLayout
            activePage={isEdit ? 'products' : 'product_add'}
            pageTitle={isEdit ? `Edit: ${product.name}` : 'Add New Product'}
            pageSubtitle={isEdit ? `Product ID: #${product.id}` : 'Create a new catalog item.'}
            headerActions={
                <>
                    {isEdit && product.slug && (
                        <a
                            href={`/product/${product.slug}`}
                            target="_blank"
                            rel="noreferrer"
                            className="px-4 py-2 bg-white border border-[#cbd5e1] text-[#475569] hover:text-[#0284c7] rounded-xl text-[12px] font-bold uppercase tracking-wider flex items-center gap-1.5 shadow-2xs"
                        >
                            <ExternalLink className="w-3.5 h-3.5" /> View Live
                        </a>
                    )}
                    <Link
                        href="/admin/products"
                        className="px-4 py-2 bg-[#0284c7] hover:bg-[#0369a1] text-white text-[12px] font-bold uppercase tracking-wider rounded-xl transition-all shadow-md shadow-[#0284c7]/20 flex items-center gap-1.5"
                    >
                        <ArrowLeft className="w-4 h-4" /> Back to Catalog
                    </Link>
                </>
            }
        >
            <Head title={isEdit ? `Edit ${product.name}` : 'Add Product — Admin'} />

            {/* FORM */}
            <form onSubmit={handleSubmit} className="space-y-8">
                {/* SECTION 1: ESSENTIAL IDENTITY */}
                <div className="bg-white/90 backdrop-blur-xl border border-[#38bdf8]/30 p-7 rounded-2xl space-y-6 shadow-sm">
                    <div className="flex items-center gap-3 border-b border-[#e2e8f0] pb-4">
                        <div className="p-2.5 bg-[#e0f2fe] text-[#0284c7] rounded-xl">
                            <Package className="w-5 h-5" />
                        </div>
                        <div>
                            <h2 className="text-base font-serif font-bold text-[#0f172a] uppercase tracking-wide">
                                Essential Product Information
                            </h2>
                            <p className="text-[11px] text-[#64748b]">Configure title, category, gender, and public URL slug.</p>
                        </div>
                    </div>

                    <div className="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label className="text-[11px] uppercase font-bold text-[#475569] tracking-wider block mb-2">
                                Product Title / Name *
                            </label>
                            <input
                                type="text"
                                value={data.name}
                                onChange={e => setData('name', e.target.value)}
                                required
                                placeholder="e.g. Leather Travel Bag, Running Shoes, Amber Nuit"
                                className="w-full border border-[#cbd5e1] px-4 py-3 rounded-xl text-[14px] font-semibold text-[#0f172a] bg-white focus:outline-none focus:border-[#0284c7] shadow-2xs"
                            />
                            {errors.name && <p className="text-rose-600 text-xs mt-1">{errors.name}</p>}
                        </div>

                        <div>
                            <label className="text-[11px] uppercase font-bold text-[#475569] tracking-wider block mb-2">
                                URL Slug (Identifier)
                            </label>
                            <input
                                type="text"
                                value={data.slug}
                                onChange={e => setData('slug', e.target.value)}
                                placeholder="e.g. leather-travel-bag"
                                className="w-full border border-[#cbd5e1] px-4 py-3 rounded-xl text-[14px] font-mono text-[#0f172a] bg-white focus:outline-none focus:border-[#0284c7] shadow-2xs"
                            />
                            <span className="text-[11px] text-[#64748b] mt-1 block">Leave blank to auto-generate from title</span>
                        </div>

                        <div>
                            <label className="text-[11px] uppercase font-bold text-[#475569] tracking-wider block mb-2">
                                Category / Collection
                            </label>
                            <select
                                value={data.category_id}
                                onChange={e => setData('category_id', e.target.value)}
                                className="w-full border border-[#cbd5e1] px-4 py-3 rounded-xl text-[14px] font-medium text-[#0f172a] bg-white focus:outline-none focus:border-[#0284c7] shadow-2xs"
                            >
                                <option value="">No Specific Category</option>
                                {rootCats.length > 0 ? (
                                    rootCats.map(cat => (
                                        <optgroup key={cat.id} label={cat.name}>
                                            <option value={cat.id}>{cat.name} (Main Category)</option>
                                            {categories.filter(sub => sub.parent_id === cat.id).map(sub => (
                                                <option key={sub.id} value={sub.id}>
                                                    &nbsp;&nbsp;↳ {sub.name} (Subcategory)
                                                </option>
                                            ))}
                                        </optgroup>
                                    ))
                                ) : (
                                    categories.map(cat => (
                                        <option key={cat.id} value={cat.id}>{cat.name}</option>
                                    ))
                                )}
                            </select>
                        </div>

                        <div>
                            <label className="text-[11px] uppercase font-bold text-[#475569] tracking-wider block mb-2">
                                Gender Target *
                            </label>
                            <select
                                value={data.gender}
                                onChange={e => setData('gender', e.target.value)}
                                required
                                className="w-full border border-[#cbd5e1] px-4 py-3 rounded-xl text-[14px] font-medium text-[#0f172a] bg-white focus:outline-none focus:border-[#0284c7] shadow-2xs"
                            >
                                <option value="unisex">Unisex / Universal</option>
                                <option value="women">Women</option>
                                <option value="men">Men</option>
                            </select>
                        </div>
                    </div>
                </div>

                {/* SECTION 2: PRICING & INVENTORY */}
                <div className="bg-white/90 backdrop-blur-xl border border-[#38bdf8]/30 p-7 rounded-2xl space-y-6 shadow-sm">
                    <div class="flex items-center gap-3 border-b border-[#e2e8f0] pb-4">
                        <div className="p-2.5 bg-emerald-50 text-emerald-600 rounded-xl">
                            <Tag className="w-5 h-5" />
                        </div>
                        <div>
                            <h2 className="text-base font-serif font-bold text-[#0f172a] uppercase tracking-wide">
                                Pricing, Inventory & Variants
                            </h2>
                            <p className="text-[11px] text-[#64748b]">Manage price, inventory stock, variant options, and formats.</p>
                        </div>
                    </div>

                    <div className="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-6">
                        <div>
                            <label className="text-[11px] uppercase font-bold text-[#475569] tracking-wider block mb-2">
                                Price (৳ / USD) *
                            </label>
                            <input
                                type="number"
                                step="0.01"
                                value={data.price}
                                onChange={e => setData('price', e.target.value)}
                                required
                                placeholder="3200.00"
                                className="w-full border border-[#cbd5e1] px-4 py-3 rounded-xl text-[14px] font-bold text-[#0284c7] bg-white focus:outline-none focus:border-[#0284c7] shadow-2xs"
                            />
                            {errors.price && <p className="text-rose-600 text-xs mt-1">{errors.price}</p>}
                        </div>

                        <div>
                            <label className="text-[11px] uppercase font-bold text-[#475569] tracking-wider block mb-2">
                                Stock Quantity *
                            </label>
                            <input
                                type="number"
                                value={data.stock}
                                onChange={e => setData('stock', Number(e.target.value))}
                                required
                                placeholder="50"
                                className="w-full border border-[#cbd5e1] px-4 py-3 rounded-xl text-[14px] font-bold text-[#0f172a] bg-white focus:outline-none focus:border-[#0284c7] shadow-2xs"
                            />
                        </div>

                        <div>
                            <label className="text-[11px] uppercase font-bold text-[#475569] tracking-wider block mb-2">
                                Type / Variant Format
                            </label>
                            <input
                                type="text"
                                value={data.concentration}
                                onChange={e => setData('concentration', e.target.value)}
                                placeholder="e.g. Eau de Parfum, Leather, Standard"
                                className="w-full border border-[#cbd5e1] px-4 py-3 rounded-xl text-[14px] font-medium text-[#0f172a] bg-white focus:outline-none focus:border-[#0284c7] shadow-2xs"
                            />
                        </div>

                        <div className="col-span-1 sm:col-span-2 md:col-span-4 mt-4 bg-slate-50 border border-slate-200 rounded-xl p-5">
                            <div className="flex items-center justify-between mb-4">
                                <div>
                                    <h3 className="text-[13px] font-bold text-slate-800 uppercase tracking-wider">Specific Variants & Pricing</h3>
                                    <p className="text-[11px] text-slate-500">Assign specific prices and stock to global variants.</p>
                                </div>
                                <button
                                    type="button"
                                    onClick={() => setData('variants', [...data.variants, { variant_id: '', price: data.price, stock: data.stock }])}
                                    className="px-3 py-1.5 bg-indigo-50 text-indigo-700 border border-indigo-200 rounded-lg text-[11px] font-bold uppercase hover:bg-indigo-100 transition-colors flex items-center gap-1"
                                >
                                    <Plus className="w-3.5 h-3.5" /> Add Variant
                                </button>
                            </div>

                            {data.variants.length === 0 ? (
                                <div className="text-center py-6 text-slate-400 text-[12px]">
                                    No variants assigned. Base price and stock will be used.
                                </div>
                            ) : (
                                <div className="space-y-3">
                                    {data.variants.map((v: any, index: number) => (
                                        <div key={index} className="flex flex-col sm:flex-row items-end gap-3 bg-white p-3 rounded-lg border border-slate-200 shadow-sm">
                                            <div className="flex-1 w-full">
                                                <label className="text-[10px] uppercase font-bold text-slate-500 block mb-1">Variant Name</label>
                                                <select
                                                    value={v.variant_id}
                                                    onChange={e => {
                                                        const newVariants = [...data.variants];
                                                        newVariants[index].variant_id = e.target.value;
                                                        setData('variants', newVariants);
                                                    }}
                                                    required
                                                    className="w-full border border-slate-300 px-3 py-2 rounded-lg text-[13px] focus:ring-1 focus:ring-indigo-500 focus:border-indigo-500"
                                                >
                                                    <option value="">Select Variant</option>
                                                    {variants.map(gv => (
                                                        <option key={gv.id} value={gv.id}>{gv.name}</option>
                                                    ))}
                                                </select>
                                            </div>
                                            <div className="w-full sm:w-32">
                                                <label className="text-[10px] uppercase font-bold text-slate-500 block mb-1">Price</label>
                                                <input
                                                    type="number"
                                                    step="0.01"
                                                    value={v.price}
                                                    onChange={e => {
                                                        const newVariants = [...data.variants];
                                                        newVariants[index].price = e.target.value;
                                                        setData('variants', newVariants);
                                                    }}
                                                    required
                                                    className="w-full border border-slate-300 px-3 py-2 rounded-lg text-[13px] font-bold focus:ring-1 focus:ring-indigo-500 focus:border-indigo-500"
                                                />
                                            </div>
                                            <div className="w-full sm:w-24">
                                                <label className="text-[10px] uppercase font-bold text-slate-500 block mb-1">Stock</label>
                                                <input
                                                    type="number"
                                                    value={v.stock}
                                                    onChange={e => {
                                                        const newVariants = [...data.variants];
                                                        newVariants[index].stock = e.target.value;
                                                        setData('variants', newVariants);
                                                    }}
                                                    required
                                                    className="w-full border border-slate-300 px-3 py-2 rounded-lg text-[13px] focus:ring-1 focus:ring-indigo-500 focus:border-indigo-500"
                                                />
                                            </div>
                                            <button
                                                type="button"
                                                onClick={() => {
                                                    const newVariants = data.variants.filter((_: any, i: number) => i !== index);
                                                    setData('variants', newVariants);
                                                }}
                                                className="w-full sm:w-auto px-3 py-2 bg-red-50 text-red-600 rounded-lg hover:bg-red-100 transition-colors"
                                            >
                                                <Trash2 className="w-4 h-4 mx-auto" />
                                            </button>
                                        </div>
                                    ))}
                                </div>
                            )}
                        </div>
                    </div>
                </div>

                {/* SECTION 3: SPECIFICATIONS & CATEGORY ATTRIBUTES */}
                <div className="bg-white/90 backdrop-blur-xl border border-[#38bdf8]/30 p-7 rounded-2xl space-y-6 shadow-sm">
                    <div className="flex items-center gap-3 border-b border-[#e2e8f0] pb-4">
                        <div className="p-2.5 bg-purple-50 text-purple-600 rounded-xl">
                            <Sparkles className="w-5 h-5" />
                        </div>
                        <div>
                            <h2 className="text-base font-serif font-bold text-[#0f172a] uppercase tracking-wide">
                                Specifications & Category Attributes
                            </h2>
                            <p className="text-[11px] text-[#64748b]">Describe materials, scent notes, or key features.</p>
                        </div>
                    </div>

                    <div className="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label className="text-[11px] uppercase font-bold text-[#475569] tracking-wider block mb-2">
                                Specification / Scent Family / Material
                            </label>
                            <input
                                type="text"
                                value={data.scent_family}
                                onChange={e => setData('scent_family', e.target.value)}
                                placeholder="e.g. Amber Woody, Genuine Leather, Cotton"
                                className="w-full border border-[#cbd5e1] px-4 py-3 rounded-xl text-[14px] font-medium text-[#0f172a] bg-white focus:outline-none focus:border-[#0284c7] shadow-2xs"
                            />
                        </div>

                        <div>
                            <label className="text-[11px] uppercase font-bold text-[#475569] tracking-wider block mb-2">
                                Top Notes / Primary Feature
                            </label>
                            <input
                                type="text"
                                value={data.notes_top}
                                onChange={e => setData('notes_top', e.target.value)}
                                placeholder="e.g. Bergamot, Water-resistant, Breathable"
                                className="w-full border border-[#cbd5e1] px-4 py-3 rounded-xl text-[14px] font-medium text-[#0f172a] bg-white focus:outline-none focus:border-[#0284c7] shadow-2xs"
                            />
                        </div>

                        <div>
                            <label className="text-[11px] uppercase font-bold text-[#475569] tracking-wider block mb-2">
                                Heart Notes / Secondary Feature
                            </label>
                            <input
                                type="text"
                                value={data.notes_heart}
                                onChange={e => setData('notes_heart', e.target.value)}
                                placeholder="e.g. Rose, Cushioned Sole, Dual Compartment"
                                className="w-full border border-[#cbd5e1] px-4 py-3 rounded-xl text-[14px] font-medium text-[#0f172a] bg-white focus:outline-none focus:border-[#0284c7] shadow-2xs"
                            />
                        </div>

                        <div>
                            <label className="text-[11px] uppercase font-bold text-[#475569] tracking-wider block mb-2">
                                Base Notes / Additional Specs
                            </label>
                            <input
                                type="text"
                                value={data.notes_base}
                                onChange={e => setData('notes_base', e.target.value)}
                                placeholder="e.g. Vanilla, Slip-resistant, 1-Year Warranty"
                                className="w-full border border-[#cbd5e1] px-4 py-3 rounded-xl text-[14px] font-medium text-[#0f172a] bg-white focus:outline-none focus:border-[#0284c7] shadow-2xs"
                            />
                        </div>

                    </div>
                </div>



                {/* SECTION 4: MEDIA & IMAGES */}
                <div className="bg-white/90 backdrop-blur-xl border border-[#38bdf8]/30 p-7 rounded-2xl space-y-6 shadow-sm">
                    <div className="flex items-center gap-3 border-b border-[#e2e8f0] pb-4">
                        <div className="p-2.5 bg-[#e0f2fe] text-[#0284c7] rounded-xl">
                            <ImageIcon className="w-5 h-5" />
                        </div>
                        <div>
                            <h2 className="text-base font-serif font-bold text-[#0f172a] uppercase tracking-wide">
                                Product Media & Photography
                            </h2>
                            <p className="text-[11px] text-[#64748b]">Upload high-res product imagery or enter direct image URLs.</p>
                        </div>
                    </div>

                    <div className="grid grid-cols-1 md:grid-cols-2 gap-8">
                        <div className="bg-[#f8fafc] p-6 rounded-2xl border border-[#e2e8f0] space-y-4">
                            <label className="text-[11px] uppercase font-bold text-[#475569] tracking-wider block">
                                Primary Product Image (Main)
                            </label>
                            {data.primary_image_url && (
                                <img
                                    src={data.primary_image_url}
                                    alt="Primary"
                                    className="w-24 h-24 object-cover rounded-xl border border-[#cbd5e1]"
                                />
                            )}
                            <div>
                                <span className="text-[11px] text-[#475569] block mb-1.5 font-bold">Upload File:</span>
                                <input
                                    type="file"
                                    accept="image/*"
                                    onChange={e => setData('primary_image_file', e.target.files ? e.target.files[0] : null)}
                                    className="w-full border border-[#cbd5e1] text-[12px] rounded-xl file:mr-3 file:py-2 file:px-3 file:border-0 file:text-[11px] file:font-bold file:bg-[#0284c7] file:text-white bg-white"
                                />
                            </div>
                            <div>
                                <span className="text-[11px] text-[#475569] block mb-1.5 font-bold">Or Image URL:</span>
                                <input
                                    type="text"
                                    value={data.primary_image_url}
                                    onChange={e => setData('primary_image_url', e.target.value)}
                                    placeholder="https://..."
                                    className="w-full border border-[#cbd5e1] px-4 py-2.5 rounded-xl text-[13px] bg-white outline-none focus:border-[#0284c7]"
                                />
                            </div>
                        </div>

                        <div className="bg-[#f8fafc] p-6 rounded-2xl border border-[#e2e8f0] space-y-4">
                            <label className="text-[11px] uppercase font-bold text-[#475569] tracking-wider block">
                                Secondary Image (Hover / Gallery)
                            </label>
                            {data.secondary_image_url && (
                                <img
                                    src={data.secondary_image_url}
                                    alt="Secondary"
                                    className="w-24 h-24 object-cover rounded-xl border border-[#cbd5e1]"
                                />
                            )}
                            <div>
                                <span className="text-[11px] text-[#475569] block mb-1.5 font-bold">Upload File:</span>
                                <input
                                    type="file"
                                    accept="image/*"
                                    onChange={e => setData('secondary_image_file', e.target.files ? e.target.files[0] : null)}
                                    className="w-full border border-[#cbd5e1] text-[12px] rounded-xl file:mr-3 file:py-2 file:px-3 file:border-0 file:text-[11px] file:font-bold file:bg-[#0284c7] file:text-white bg-white"
                                />
                            </div>
                            <div>
                                <span className="text-[11px] text-[#475569] block mb-1.5 font-bold">Or Image URL:</span>
                                <input
                                    type="text"
                                    value={data.secondary_image_url}
                                    onChange={e => setData('secondary_image_url', e.target.value)}
                                    placeholder="https://..."
                                    className="w-full border border-[#cbd5e1] px-4 py-2.5 rounded-xl text-[13px] bg-white outline-none focus:border-[#0284c7]"
                                />
                            </div>
                        </div>
                    </div>
                </div>

                {/* SECTION 5: DESCRIPTIONS & BADGES */}
                <div className="bg-white/90 backdrop-blur-xl border border-[#38bdf8]/30 p-7 rounded-2xl space-y-6 shadow-sm">
                    <div className="flex items-center gap-3 border-b border-[#e2e8f0] pb-4">
                        <div className="p-2.5 bg-amber-50 text-amber-600 rounded-xl">
                            <FileText className="w-5 h-5" />
                        </div>
                        <div>
                            <h2 className="text-base font-serif font-bold text-[#0f172a] uppercase tracking-wide">
                                Descriptions & Showcase Badges
                            </h2>
                            <p className="text-[11px] text-[#64748b]">Write customer-facing copy and toggle homepage badges.</p>
                        </div>
                    </div>

                    <div className="space-y-6">
                        <div>
                            <label className="text-[11px] uppercase font-bold text-[#475569] tracking-wider block mb-2">
                                Short Summary Description
                            </label>
                            <input
                                type="text"
                                value={data.short_description}
                                onChange={e => setData('short_description', e.target.value)}
                                placeholder="Brief overview of product features and finish..."
                                className="w-full border border-[#cbd5e1] px-4 py-3 rounded-xl text-[14px] text-[#0f172a] bg-white outline-none focus:border-[#0284c7]"
                            />
                        </div>

                        <div>
                            <label className="text-[11px] uppercase font-bold text-[#475569] tracking-wider block mb-2">
                                Full Story & Detailed Description
                            </label>
                            <textarea
                                rows={4}
                                value={data.description}
                                onChange={e => setData('description', e.target.value)}
                                placeholder="Detailed story, specifications, dimensions, care instructions..."
                                className="w-full border border-[#cbd5e1] px-4 py-3 rounded-xl text-[13px] text-[#0f172a] bg-white outline-none focus:border-[#0284c7]"
                            />
                        </div>

                        <div className="flex items-center gap-8 pt-2">
                            <label className="flex items-center gap-2.5 cursor-pointer text-[13px] font-bold text-[#0f172a]">
                                <input
                                    type="checkbox"
                                    checked={data.is_featured}
                                    onChange={e => setData('is_featured', e.target.checked)}
                                    className="w-4 h-4 rounded text-[#0284c7] focus:ring-[#0284c7]"
                                />
                                <span>Feature on Storefront Homepage</span>
                            </label>

                            <label className="flex items-center gap-2.5 cursor-pointer text-[13px] font-bold text-[#0f172a]">
                                <input
                                    type="checkbox"
                                    checked={data.is_new_arrival}
                                    onChange={e => setData('is_new_arrival', e.target.checked)}
                                    className="w-4 h-4 rounded text-[#0284c7] focus:ring-[#0284c7]"
                                />
                                <span>Mark as New Arrival</span>
                            </label>
                        </div>
                    </div>
                </div>

                {/* BOTTOM ACTION BAR */}
                <div className="sticky bottom-6 bg-white/95 backdrop-blur-xl border border-[#38bdf8]/40 p-5 rounded-2xl shadow-xl flex flex-col sm:flex-row items-center justify-between gap-4 z-30">
                    <div className="text-[12px] text-[#64748b]">
                        {isEdit ? `Last updated: ${product.updated_at || 'Recently'}` : 'Status: New Product Entry'}
                    </div>

                    <div className="flex items-center gap-3 w-full sm:w-auto justify-end">
                        <Link
                            href="/admin/products"
                            className="px-6 py-3 bg-[#f8fafc] hover:bg-[#f1f5f9] border border-[#cbd5e1] text-[#475569] rounded-xl text-[12px] font-bold uppercase tracking-wider"
                        >
                            Cancel
                        </Link>

                        <button
                            type="submit"
                            disabled={processing}
                            className="px-8 py-3 bg-[#0284c7] hover:bg-[#0369a1] text-white rounded-xl text-[12px] font-bold uppercase tracking-wider shadow-md shadow-[#0284c7]/25 flex items-center gap-2"
                        >
                            {isEdit ? <Check className="w-4 h-4" /> : <Plus className="w-4 h-4" />}
                            {processing ? 'Saving...' : isEdit ? 'Save Changes' : 'Create Product'}
                        </button>
                    </div>
                </div>
            </form>
        </AdminLayout>
    );
}
