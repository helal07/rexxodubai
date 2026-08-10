import React, { useState } from 'react';
import { Head, Link, useForm } from '@inertiajs/react';
import AdminLayout from '@/Layouts/AdminLayout';
import { ArrowLeft, Save, Image as ImageIcon } from 'lucide-react';

export default function Form({ campaign, products, selectedProducts }: any) {
    const isEditing = !!campaign.id;
    
    const { data, setData, post, processing, errors, progress } = useForm({
        name: campaign.name || '',
        title: campaign.title || '',
        subtitle: campaign.subtitle || '',
        button_text: campaign.button_text || 'DISCOVER',
        button_link: campaign.button_link || '/perfumes',
        is_active: campaign.is_active ?? true,
        product_ids: selectedProducts || [],
        banner_image: null as File | null,
        _method: isEditing ? 'put' : 'post',
    });

    const handleSubmit = (e: React.FormEvent) => {
        e.preventDefault();
        
        const url = isEditing ? `/admin/campaigns/${campaign.id}` : '/admin/campaigns';
        post(url, {
            preserveScroll: true,
            forceFormData: true,
        });
    };

    const handleProductToggle = (productId: number) => {
        if (data.product_ids.includes(productId)) {
            setData('product_ids', data.product_ids.filter((id: number) => id !== productId));
        } else {
            setData('product_ids', [...data.product_ids, productId]);
        }
    };

    return (
        <AdminLayout>
            <Head title={isEditing ? 'Edit Campaign' : 'Create Campaign'} />
            
            <form onSubmit={handleSubmit} className="max-w-4xl mx-auto space-y-6">
                
                {/* Header */}
                <div className="flex items-center justify-between">
                    <div className="flex items-center gap-4">
                        <Link
                            href="/admin/campaigns"
                            className="p-2 text-slate-400 hover:text-slate-600 hover:bg-slate-100 rounded-full transition-colors"
                        >
                            <ArrowLeft className="w-5 h-5" />
                        </Link>
                        <div>
                            <h1 className="text-2xl font-bold tracking-tight text-[#0f172a]">
                                {isEditing ? 'Edit Campaign' : 'Create Campaign'}
                            </h1>
                        </div>
                    </div>
                    <button
                        type="submit"
                        disabled={processing}
                        className="inline-flex items-center gap-2 px-6 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white text-[14px] font-semibold rounded-lg shadow-sm transition-all disabled:opacity-50"
                    >
                        <Save className="w-4 h-4" />
                        Save Campaign
                    </button>
                </div>

                <div className="grid grid-cols-1 md:grid-cols-3 gap-6">
                    
                    {/* Main Details */}
                    <div className="md:col-span-2 space-y-6">
                        <div className="bg-white rounded-xl shadow-sm border border-slate-200 p-6 space-y-5">
                            <h2 className="text-[16px] font-semibold text-slate-900 border-b border-slate-100 pb-3">Campaign Details</h2>
                            
                            <div>
                                <label className="block text-[13px] font-medium text-slate-700 mb-1.5">Administrative Name *</label>
                                <input
                                    type="text"
                                    required
                                    value={data.name}
                                    onChange={e => setData('name', e.target.value)}
                                    className="w-full px-3 py-2 bg-white border border-slate-300 rounded-lg text-[14px] focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 outline-none transition-all"
                                    placeholder="e.g. Summer Sale 2026"
                                />
                                {errors.name && <div className="text-red-500 text-xs mt-1">{errors.name}</div>}
                            </div>

                            <div className="grid grid-cols-1 sm:grid-cols-2 gap-5">
                                <div>
                                    <label className="block text-[13px] font-medium text-slate-700 mb-1.5">Display Title</label>
                                    <input
                                        type="text"
                                        value={data.title}
                                        onChange={e => setData('title', e.target.value)}
                                        className="w-full px-3 py-2 bg-white border border-slate-300 rounded-lg text-[14px] focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 outline-none transition-all"
                                        placeholder="e.g. The Signature Collection"
                                    />
                                </div>
                                <div>
                                    <label className="block text-[13px] font-medium text-slate-700 mb-1.5">Display Subtitle</label>
                                    <input
                                        type="text"
                                        value={data.subtitle}
                                        onChange={e => setData('subtitle', e.target.value)}
                                        className="w-full px-3 py-2 bg-white border border-slate-300 rounded-lg text-[14px] focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 outline-none transition-all"
                                        placeholder="e.g. Silhouettes redefined"
                                    />
                                </div>
                            </div>

                            <div className="grid grid-cols-1 sm:grid-cols-2 gap-5">
                                <div>
                                    <label className="block text-[13px] font-medium text-slate-700 mb-1.5">Button Text</label>
                                    <input
                                        type="text"
                                        value={data.button_text}
                                        onChange={e => setData('button_text', e.target.value)}
                                        className="w-full px-3 py-2 bg-white border border-slate-300 rounded-lg text-[14px] focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 outline-none transition-all"
                                        placeholder="DISCOVER"
                                    />
                                </div>
                                <div>
                                    <label className="block text-[13px] font-medium text-slate-700 mb-1.5">Button Link</label>
                                    <input
                                        type="text"
                                        value={data.button_link}
                                        onChange={e => setData('button_link', e.target.value)}
                                        className="w-full px-3 py-2 bg-white border border-slate-300 rounded-lg text-[14px] focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 outline-none transition-all"
                                        placeholder="/perfumes"
                                    />
                                </div>
                            </div>
                            
                            <div>
                                <label className="block text-[13px] font-medium text-slate-700 mb-1.5">Banner Image</label>
                                <div className="mt-1 flex justify-center px-6 pt-5 pb-6 border-2 border-slate-300 border-dashed rounded-lg hover:bg-slate-50 transition-colors">
                                    <div className="space-y-1 text-center">
                                        <ImageIcon className="mx-auto h-12 w-12 text-slate-400" />
                                        <div className="flex text-sm text-slate-600 justify-center">
                                            <label htmlFor="file-upload" className="relative cursor-pointer bg-white rounded-md font-medium text-indigo-600 hover:text-indigo-500 focus-within:outline-none">
                                                <span>Upload a file</span>
                                                <input
                                                    id="file-upload"
                                                    type="file"
                                                    className="sr-only"
                                                    accept="image/*"
                                                    onChange={e => setData('banner_image', e.target.files?.[0] || null)}
                                                />
                                            </label>
                                            <p className="pl-1">or drag and drop</p>
                                        </div>
                                        <p className="text-xs text-slate-500">PNG, JPG, WEBP up to 10MB</p>
                                        <p className="text-xs text-slate-500 font-medium">Recommended: 2400x1200 or similar wide aspect ratio</p>
                                        {data.banner_image && (
                                            <p className="text-sm font-semibold text-indigo-600 mt-2">{data.banner_image.name}</p>
                                        )}
                                        {campaign.banner_image_url && !data.banner_image && (
                                            <div className="mt-4">
                                                <p className="text-xs text-slate-500 mb-2">Current Image:</p>
                                                <img src={campaign.banner_image_url} alt="Current banner" className="h-20 object-contain mx-auto rounded" />
                                            </div>
                                        )}
                                    </div>
                                </div>
                                {progress && (
                                    <div className="w-full bg-slate-200 rounded-full h-2 mt-2">
                                        <div className="bg-indigo-600 h-2 rounded-full" style={{ width: `${progress.percentage}%` }}></div>
                                    </div>
                                )}
                            </div>
                        </div>
                    </div>

                    {/* Sidebar Details */}
                    <div className="space-y-6">
                        <div className="bg-white rounded-xl shadow-sm border border-slate-200 p-6 space-y-5">
                            <h2 className="text-[16px] font-semibold text-slate-900 border-b border-slate-100 pb-3">Status</h2>
                            
                            <label className="flex items-center gap-3 cursor-pointer">
                                <input
                                    type="checkbox"
                                    checked={data.is_active}
                                    onChange={e => setData('is_active', e.target.checked)}
                                    className="w-5 h-5 rounded border-slate-300 text-indigo-600 focus:ring-indigo-600"
                                />
                                <span className="text-[14px] font-medium text-slate-700">Active</span>
                            </label>
                            <p className="text-[12px] text-slate-500">
                                If inactive, this campaign will not be shown on the home page.
                            </p>
                        </div>

                        <div className="bg-white rounded-xl shadow-sm border border-slate-200 p-6 space-y-5">
                            <h2 className="text-[16px] font-semibold text-slate-900 border-b border-slate-100 pb-3">Featured Products</h2>
                            <p className="text-[12px] text-slate-500">
                                Select products to feature under this campaign on the home page.
                            </p>
                            
                            <div className="space-y-3 max-h-[300px] overflow-y-auto pr-2 custom-scrollbar">
                                {products.map((product: any) => (
                                    <label key={product.id} className="flex items-center gap-3 cursor-pointer p-2 hover:bg-slate-50 rounded-lg transition-colors border border-transparent hover:border-slate-200">
                                        <input
                                            type="checkbox"
                                            checked={data.product_ids.includes(product.id)}
                                            onChange={() => handleProductToggle(product.id)}
                                            className="w-4 h-4 rounded border-slate-300 text-indigo-600 focus:ring-indigo-600"
                                        />
                                        {product.primary_image_url && (
                                            <img src={product.primary_image_url} alt={product.name} className="w-8 h-8 object-cover rounded bg-slate-100" />
                                        )}
                                        <span className="text-[13px] font-medium text-slate-700 leading-tight">
                                            {product.name}
                                        </span>
                                    </label>
                                ))}
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </AdminLayout>
    );
}
