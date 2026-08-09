import React from 'react';
import { Head, useForm } from '@inertiajs/react';
import AdminLayout from '@/Layouts/AdminLayout';
import { Save, Activity, Code, Target, Share2 } from 'lucide-react';

interface MarketingProps {
    siteSettings?: Record<string, any>;
}

export default function Marketing({ siteSettings = {} }: MarketingProps) {
    const { data, setData, post, processing } = useForm({
        fb_pixel_id: siteSettings.fb_pixel_id || '',
        google_analytics_id: siteSettings.google_analytics_id || '',
        gtm_id: siteSettings.gtm_id || '',
    });

    const handleSubmit = (e: React.FormEvent) => {
        e.preventDefault();
        post('/admin/settings', {
            preserveScroll: true,
        });
    };

    return (
        <AdminLayout
            activePage="seo_marketing"
            pageTitle="Pixels & Tracking"
            pageSubtitle="Configure Facebook Meta Pixel, Google Analytics, and Tag Manager IDs."
        >
            <Head title="Pixels & Tracking — Admin" />

            <form onSubmit={handleSubmit} className="space-y-6">
                
                {/* Meta / Facebook Pixel */}
                <div className="bg-white/90 backdrop-blur-xl border border-[#38bdf8]/30 p-7 rounded-2xl shadow-sm space-y-6">
                    <div className="flex items-center gap-3 border-b border-[#e2e8f0] pb-4">
                        <div className="p-2.5 bg-blue-50 text-blue-600 rounded-xl">
                            <Share2 className="w-5 h-5" />
                        </div>
                        <div>
                            <h3 className="text-base font-serif font-bold text-[#0f172a] uppercase tracking-wide">
                                Meta (Facebook) Pixel
                            </h3>
                            <p className="text-[11px] text-[#64748b]">Track conversions from Facebook ads</p>
                        </div>
                    </div>

                    <div>
                        <label className="text-[11px] uppercase font-bold text-[#475569] tracking-wider block mb-1.5 flex items-center gap-1.5">
                            <Code className="w-3.5 h-3.5" /> Facebook Pixel ID
                        </label>
                        <input
                            type="text"
                            value={data.fb_pixel_id}
                            onChange={e => setData('fb_pixel_id', e.target.value)}
                            placeholder="e.g. 1010101010101010"
                            className="w-full md:w-1/2 border border-[#cbd5e1] px-4 py-2.5 rounded-xl text-[13px] font-mono outline-none focus:border-[#0284c7] bg-white shadow-2xs"
                        />
                    </div>
                </div>

                {/* Google Analytics */}
                <div className="bg-white/90 backdrop-blur-xl border border-[#38bdf8]/30 p-7 rounded-2xl shadow-sm space-y-6">
                    <div className="flex items-center gap-3 border-b border-[#e2e8f0] pb-4">
                        <div className="p-2.5 bg-rose-50 text-rose-600 rounded-xl">
                            <Activity className="w-5 h-5" />
                        </div>
                        <div>
                            <h3 className="text-base font-serif font-bold text-[#0f172a] uppercase tracking-wide">
                                Google Analytics (GA4)
                            </h3>
                            <p className="text-[11px] text-[#64748b]">Measure traffic and engagement</p>
                        </div>
                    </div>

                    <div>
                        <label className="text-[11px] uppercase font-bold text-[#475569] tracking-wider block mb-1.5 flex items-center gap-1.5">
                            <Code className="w-3.5 h-3.5" /> GA4 Measurement ID
                        </label>
                        <input
                            type="text"
                            value={data.google_analytics_id}
                            onChange={e => setData('google_analytics_id', e.target.value)}
                            placeholder="e.g. G-XXXXXXX"
                            className="w-full md:w-1/2 border border-[#cbd5e1] px-4 py-2.5 rounded-xl text-[13px] font-mono outline-none focus:border-[#0284c7] bg-white shadow-2xs"
                        />
                    </div>
                </div>

                {/* Google Tag Manager */}
                <div className="bg-white/90 backdrop-blur-xl border border-[#38bdf8]/30 p-7 rounded-2xl shadow-sm space-y-6">
                    <div className="flex items-center gap-3 border-b border-[#e2e8f0] pb-4">
                        <div className="p-2.5 bg-amber-50 text-amber-600 rounded-xl">
                            <Target className="w-5 h-5" />
                        </div>
                        <div>
                            <h3 className="text-base font-serif font-bold text-[#0f172a] uppercase tracking-wide">
                                Google Tag Manager
                            </h3>
                            <p className="text-[11px] text-[#64748b]">Manage all your website tags</p>
                        </div>
                    </div>

                    <div>
                        <label className="text-[11px] uppercase font-bold text-[#475569] tracking-wider block mb-1.5 flex items-center gap-1.5">
                            <Code className="w-3.5 h-3.5" /> GTM Container ID
                        </label>
                        <input
                            type="text"
                            value={data.gtm_id}
                            onChange={e => setData('gtm_id', e.target.value)}
                            placeholder="e.g. GTM-XXXXXXX"
                            className="w-full md:w-1/2 border border-[#cbd5e1] px-4 py-2.5 rounded-xl text-[13px] font-mono outline-none focus:border-[#0284c7] bg-white shadow-2xs"
                        />
                    </div>
                </div>

                <div className="sticky bottom-6 bg-white/95 backdrop-blur-xl border border-[#38bdf8]/40 p-5 rounded-2xl shadow-xl flex items-center justify-between z-30">
                    <span className="text-[12px] text-[#64748b]">Pixel & Tracking IDs.</span>
                    <button
                        type="submit"
                        disabled={processing}
                        className="px-8 py-3 bg-[#0284c7] hover:bg-[#0369a1] text-white rounded-xl text-[12px] font-bold uppercase tracking-wider shadow-md shadow-[#0284c7]/20 flex items-center gap-2"
                    >
                        <Save className="w-4 h-4" /> Save Tracking IDs
                    </button>
                </div>
            </form>
        </AdminLayout>
    );
}
