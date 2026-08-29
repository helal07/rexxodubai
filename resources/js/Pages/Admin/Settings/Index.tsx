import React from 'react';
import { Head, useForm } from '@inertiajs/react';
import AdminLayout from '@/Layouts/AdminLayout';
import { Settings as SettingsIcon, Save, Globe, Image as ImageIcon, Phone, Mail } from 'lucide-react';

interface SettingsProps {
    siteSettings?: Record<string, any>;
}

export default function SettingsPage({ siteSettings = {} }: SettingsProps) {
    const { data, setData, post, processing } = useForm({
        siteName: siteSettings.siteName || 'RaaxO BD',
        tagline: siteSettings.tagline || 'Luxury Fragrances',
        email: siteSettings.email || siteSettings.contactEmail || '',
        phone: siteSettings.phone || siteSettings.contactPhone || '',
        whatsapp: siteSettings.whatsapp || '',
        address: siteSettings.address || '',
        currency: siteSettings.currency || 'USD ($)',
        logo_url: siteSettings.logo_url || '',
        favicon_url: siteSettings.favicon_url || '',
    });

    const handleSubmit = (e: React.FormEvent) => {
        e.preventDefault();
        post('/api/settings', {
            preserveScroll: true,
        });
    };

    return (
        <AdminLayout
            activePage="settings"
            pageTitle="General System Settings"
            pageSubtitle="Configure site title, contact info, branding logos, and global defaults."
        >
            <Head title="System Settings — Admin" />

            <form onSubmit={handleSubmit} className="bg-white/90 backdrop-blur-xl border border-[#38bdf8]/30 p-7 rounded-2xl space-y-6 shadow-sm">
                <div className="flex items-center gap-3 border-b border-[#e2e8f0] pb-4">
                    <div className="p-2.5 bg-[#e0f2fe] text-[#0284c7] rounded-xl">
                        <SettingsIcon className="w-5 h-5" />
                    </div>
                    <div>
                        <h3 className="text-base font-serif font-bold text-[#0f172a] uppercase tracking-wide">
                            Branding & Business Details
                        </h3>
                        <p className="text-[11px] text-[#64748b]">Update storefront identity and contact information</p>
                    </div>
                </div>

                <div className="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label className="text-[11px] uppercase font-bold text-[#475569] tracking-wider block mb-1.5">
                            Site Name / Brand Title *
                        </label>
                        <input
                            type="text"
                            value={data.siteName}
                            onChange={e => setData('siteName', e.target.value)}
                            required
                            className="w-full border border-[#cbd5e1] px-4 py-2.5 rounded-xl text-[13px] font-bold outline-none focus:border-[#0284c7] bg-white shadow-2xs"
                        />
                    </div>

                    <div>
                        <label className="text-[11px] uppercase font-bold text-[#475569] tracking-wider block mb-1.5">
                            Tagline / Subtitle
                        </label>
                        <input
                            type="text"
                            value={data.tagline}
                            onChange={e => setData('tagline', e.target.value)}
                            className="w-full border border-[#cbd5e1] px-4 py-2.5 rounded-xl text-[13px] font-medium outline-none focus:border-[#0284c7] bg-white shadow-2xs"
                        />
                    </div>

                    <div>
                        <label className="text-[11px] uppercase font-bold text-[#475569] tracking-wider block mb-1.5">
                            Store Currency Symbol
                        </label>
                        <input
                            type="text"
                            value={data.currency}
                            onChange={e => setData('currency', e.target.value)}
                            placeholder="e.g. BDT (৳) or USD ($)"
                            className="w-full border border-[#cbd5e1] px-4 py-2.5 rounded-xl text-[13px] font-bold outline-none focus:border-[#0284c7] bg-white shadow-2xs"
                        />
                    </div>

                    <div>
                        <label className="text-[11px] uppercase font-bold text-[#475569] tracking-wider block mb-1.5">
                            Contact Email
                        </label>
                        <input
                            type="email"
                            value={data.email}
                            onChange={e => setData('email', e.target.value)}
                            className="w-full border border-[#cbd5e1] px-4 py-2.5 rounded-xl text-[13px] font-medium outline-none focus:border-[#0284c7] bg-white shadow-2xs"
                        />
                    </div>

                    <div>
                        <label className="text-[11px] uppercase font-bold text-[#475569] tracking-wider block mb-1.5">
                            Support Phone Number
                        </label>
                        <input
                            type="text"
                            value={data.phone}
                            onChange={e => setData('phone', e.target.value)}
                            className="w-full border border-[#cbd5e1] px-4 py-2.5 rounded-xl text-[13px] font-mono outline-none focus:border-[#0284c7] bg-white shadow-2xs"
                        />
                    </div>

                    <div className="md:col-span-2">
                        <label className="text-[11px] uppercase font-bold text-[#475569] tracking-wider block mb-1.5">
                            WhatsApp VIP Chat (Numbers Only)
                        </label>
                        <input
                            type="text"
                            value={data.whatsapp}
                            onChange={e => setData('whatsapp', e.target.value)}
                            placeholder="e.g. 8801700000000"
                            className="w-full border border-[#cbd5e1] px-4 py-2.5 rounded-xl text-[13px] font-mono outline-none focus:border-[#25D366] bg-white shadow-2xs"
                        />
                    </div>

                    <div className="md:col-span-2">
                        <label className="text-[11px] uppercase font-bold text-[#475569] tracking-wider block mb-1.5">
                            Physical Store / Office Address
                        </label>
                        <input
                            type="text"
                            value={data.address}
                            onChange={e => setData('address', e.target.value)}
                            className="w-full border border-[#cbd5e1] px-4 py-2.5 rounded-xl text-[13px] font-medium outline-none focus:border-[#0284c7] bg-white shadow-2xs"
                        />
                    </div>
                </div>

                <div className="flex justify-end border-t border-[#e2e8f0] pt-6">
                    <button
                        type="submit"
                        disabled={processing}
                        className="px-8 py-3 bg-[#0284c7] hover:bg-[#0369a1] text-white rounded-xl text-[12px] font-bold uppercase tracking-wider shadow-md shadow-[#0284c7]/20 flex items-center gap-2"
                    >
                        <Save className="w-4 h-4" /> Save System Settings
                    </button>
                </div>
            </form>
        </AdminLayout>
    );
}
