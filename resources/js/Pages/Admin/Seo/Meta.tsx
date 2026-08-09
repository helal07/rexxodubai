import React from 'react';
import { Head, useForm } from '@inertiajs/react';
import AdminLayout from '@/Layouts/AdminLayout';
import { Save, Globe, Search, Type, Hash, Image as ImageIcon } from 'lucide-react';

interface MetaProps {
    siteSettings?: Record<string, any>;
}

export default function Meta({ siteSettings = {} }: MetaProps) {
    const { data, setData, post, processing } = useForm({
        seo_meta_title: siteSettings.seo_meta_title || '',
        seo_meta_description: siteSettings.seo_meta_description || '',
        seo_meta_keywords: siteSettings.seo_meta_keywords || '',
        tagline: siteSettings.tagline || '',
    });

    const handleSubmit = (e: React.FormEvent) => {
        e.preventDefault();
        post('/admin/settings', {
            preserveScroll: true,
        });
    };

    return (
        <AdminLayout
            activePage="seo_meta"
            pageTitle="Global SEO Meta"
            pageSubtitle="Configure site-wide search engine optimization titles, tags, and OpenGraph data."
        >
            <Head title="SEO Meta — Admin" />

            <form onSubmit={handleSubmit} className="space-y-6">
                <div className="bg-white/90 backdrop-blur-xl border border-[#38bdf8]/30 p-7 rounded-2xl shadow-sm space-y-6">
                    <div className="flex items-center gap-3 border-b border-[#e2e8f0] pb-4">
                        <div className="p-2.5 bg-[#e0f2fe] text-[#0284c7] rounded-xl">
                            <Search className="w-5 h-5" />
                        </div>
                        <div>
                            <h3 className="text-base font-serif font-bold text-[#0f172a] uppercase tracking-wide">
                                Search Engine Appearance
                            </h3>
                            <p className="text-[11px] text-[#64748b]">How your homepage appears on Google</p>
                        </div>
                    </div>

                    <div className="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div className="space-y-4">
                            <div>
                                <label className="text-[11px] uppercase font-bold text-[#475569] tracking-wider block mb-1.5 flex items-center gap-1.5">
                                    <Type className="w-3.5 h-3.5" /> Homepage Meta Title
                                </label>
                                <input
                                    type="text"
                                    value={data.seo_meta_title}
                                    onChange={e => setData('seo_meta_title', e.target.value)}
                                    placeholder="e.g. Best Luxury Perfumes in BD"
                                    className="w-full border border-[#cbd5e1] px-4 py-2.5 rounded-xl text-[13px] outline-none focus:border-[#0284c7] bg-white shadow-2xs"
                                />
                                <span className="text-[10px] text-[#64748b] mt-1 block">Optimal length: 50-60 chars</span>
                            </div>

                            <div>
                                <label className="text-[11px] uppercase font-bold text-[#475569] tracking-wider block mb-1.5 flex items-center gap-1.5">
                                    <Type className="w-3.5 h-3.5" /> Homepage Tagline
                                </label>
                                <input
                                    type="text"
                                    value={data.tagline}
                                    onChange={e => setData('tagline', e.target.value)}
                                    placeholder="e.g. Your Complete Tech Destination"
                                    className="w-full border border-[#cbd5e1] px-4 py-2.5 rounded-xl text-[13px] outline-none focus:border-[#0284c7] bg-white shadow-2xs"
                                />
                            </div>

                            <div>
                                <label className="text-[11px] uppercase font-bold text-[#475569] tracking-wider block mb-1.5 flex items-center gap-1.5">
                                    <Hash className="w-3.5 h-3.5" /> Meta Keywords
                                </label>
                                <input
                                    type="text"
                                    value={data.seo_meta_keywords}
                                    onChange={e => setData('seo_meta_keywords', e.target.value)}
                                    placeholder="perfume, luxury, fragrance..."
                                    className="w-full border border-[#cbd5e1] px-4 py-2.5 rounded-xl text-[13px] outline-none focus:border-[#0284c7] bg-white shadow-2xs"
                                />
                                <span className="text-[10px] text-[#64748b] mt-1 block">Comma separated values</span>
                            </div>
                        </div>

                        <div>
                            <label className="text-[11px] uppercase font-bold text-[#475569] tracking-wider block mb-1.5">
                                Meta Description
                            </label>
                            <textarea
                                value={data.seo_meta_description}
                                onChange={e => setData('seo_meta_description', e.target.value)}
                                placeholder="Describe your store..."
                                rows={5}
                                className="w-full border border-[#cbd5e1] px-4 py-3 rounded-xl text-[13px] outline-none focus:border-[#0284c7] bg-white shadow-2xs resize-none"
                            ></textarea>
                            <span className="text-[10px] text-[#64748b] mt-1 block">Optimal length: 150-160 chars</span>
                            
                            {/* SERP PREVIEW */}
                            <div className="mt-4 p-4 border border-[#e2e8f0] rounded-xl bg-[#f8fafc]">
                                <h4 className="text-[11px] font-bold uppercase text-[#94a3b8] mb-2 tracking-wider">Live SERP Preview</h4>
                                <div className="space-y-1">
                                    <div className="text-[12px] text-[#1a0dab] font-semibold truncate hover:underline cursor-pointer">
                                        {data.seo_meta_title || 'RaaxO BD - Luxury Perfumes'}
                                    </div>
                                    <div className="text-[11px] text-[#006621] truncate">https://raaxobd.com/</div>
                                    <div className="text-[12px] text-[#545454] line-clamp-2">
                                        {data.seo_meta_description || 'Shop the finest handcrafted perfumes and pure parfums online.'}
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div className="sticky bottom-6 bg-white/95 backdrop-blur-xl border border-[#38bdf8]/40 p-5 rounded-2xl shadow-xl flex items-center justify-between z-30">
                    <span className="text-[12px] text-[#64748b]">Global SEO Meta configurations.</span>
                    <button
                        type="submit"
                        disabled={processing}
                        className="px-8 py-3 bg-[#0284c7] hover:bg-[#0369a1] text-white rounded-xl text-[12px] font-bold uppercase tracking-wider shadow-md shadow-[#0284c7]/20 flex items-center gap-2"
                    >
                        <Save className="w-4 h-4" /> Save Meta Tags
                    </button>
                </div>
            </form>
        </AdminLayout>
    );
}
