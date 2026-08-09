import React, { useState } from 'react';
import { Head, useForm, router } from '@inertiajs/react';
import AdminLayout from '@/Layouts/AdminLayout';
import {
    Truck,
    CheckCircle2,
    Zap,
    Save,
    AlertCircle,
    PackageCheck,
    Send
} from 'lucide-react';

interface CourierProps {
    courierSettings?: Record<string, any>;
}

export default function Courier({ courierSettings = {} }: CourierProps) {
    const { data, setData, post, processing } = useForm({
        pathao_client_id: courierSettings.pathao_client_id || '',
        pathao_client_secret: courierSettings.pathao_client_secret || '',
        pathao_username: courierSettings.pathao_username || '',
        pathao_password: courierSettings.pathao_password || '',
        pathao_store_id: courierSettings.pathao_store_id || '',
        steadfast_api_key: courierSettings.steadfast_api_key || '',
        steadfast_secret_key: courierSettings.steadfast_secret_key || '',
        redx_api_token: courierSettings.redx_api_token || '',
        paperfly_username: courierSettings.paperfly_username || '',
        paperfly_password: courierSettings.paperfly_password || '',
        paperfly_key: courierSettings.paperfly_key || '',
    });

    const handleSubmit = (e: React.FormEvent) => {
        e.preventDefault();
        post('/admin/courier/settings', {
            preserveScroll: true,
        });
    };

    return (
        <AdminLayout
            activePage="courier"
            pageTitle="Courier Integrations & API Hub"
            pageSubtitle="Configure API credentials for Pathao, Steadfast, RedX, and Paperfly dispatch networks."
        >
            <Head title="Courier Integrations — Admin" />

            <form onSubmit={handleSubmit} className="space-y-8">
                {/* 1. PATHAO COURIER API */}
                <div className="bg-white/90 backdrop-blur-xl border border-[#38bdf8]/30 p-7 rounded-2xl space-y-6 shadow-sm">
                    <div className="flex items-center gap-3 border-b border-[#e2e8f0] pb-4">
                        <div className="p-2.5 bg-emerald-50 text-emerald-600 rounded-xl">
                            <Truck className="w-5 h-5" />
                        </div>
                        <div>
                            <h3 className="text-base font-serif font-bold text-[#0f172a] uppercase tracking-wide">
                                Pathao Courier API Credentials
                            </h3>
                            <p className="text-[11px] text-[#64748b]">Live automated parcel booking & tracking via Pathao API</p>
                        </div>
                    </div>

                    <div className="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label className="text-[11px] uppercase font-bold text-[#475569] tracking-wider block mb-1.5">
                                Client ID
                            </label>
                            <input
                                type="text"
                                value={data.pathao_client_id}
                                onChange={e => setData('pathao_client_id', e.target.value)}
                                placeholder="Pathao Client ID"
                                className="w-full border border-[#cbd5e1] px-4 py-2.5 rounded-xl text-[13px] font-mono outline-none focus:border-[#0284c7] bg-white shadow-2xs"
                            />
                        </div>

                        <div>
                            <label className="text-[11px] uppercase font-bold text-[#475569] tracking-wider block mb-1.5">
                                Client Secret
                            </label>
                            <input
                                type="password"
                                value={data.pathao_client_secret}
                                onChange={e => setData('pathao_client_secret', e.target.value)}
                                placeholder="Pathao Client Secret"
                                className="w-full border border-[#cbd5e1] px-4 py-2.5 rounded-xl text-[13px] font-mono outline-none focus:border-[#0284c7] bg-white shadow-2xs"
                            />
                        </div>

                        <div>
                            <label className="text-[11px] uppercase font-bold text-[#475569] tracking-wider block mb-1.5">
                                Account Username / Email
                            </label>
                            <input
                                type="text"
                                value={data.pathao_username}
                                onChange={e => setData('pathao_username', e.target.value)}
                                placeholder="merchant@email.com"
                                className="w-full border border-[#cbd5e1] px-4 py-2.5 rounded-xl text-[13px] font-medium outline-none focus:border-[#0284c7] bg-white shadow-2xs"
                            />
                        </div>

                        <div>
                            <label className="text-[11px] uppercase font-bold text-[#475569] tracking-wider block mb-1.5">
                                Store ID
                            </label>
                            <input
                                type="text"
                                value={data.pathao_store_id}
                                onChange={e => setData('pathao_store_id', e.target.value)}
                                placeholder="Pathao Merchant Store ID"
                                className="w-full border border-[#cbd5e1] px-4 py-2.5 rounded-xl text-[13px] font-mono outline-none focus:border-[#0284c7] bg-white shadow-2xs"
                            />
                        </div>
                    </div>
                </div>

                {/* 2. STEADFAST COURIER API */}
                <div className="bg-white/90 backdrop-blur-xl border border-[#38bdf8]/30 p-7 rounded-2xl space-y-6 shadow-sm">
                    <div className="flex items-center gap-3 border-b border-[#e2e8f0] pb-4">
                        <div className="p-2.5 bg-[#e0f2fe] text-[#0284c7] rounded-xl">
                            <Zap className="w-5 h-5" />
                        </div>
                        <div>
                            <h3 className="text-base font-serif font-bold text-[#0f172a] uppercase tracking-wide">
                                Steadfast Courier API
                            </h3>
                            <p className="text-[11px] text-[#64748b]">Steadfast nationwide parcel booking API integration</p>
                        </div>
                    </div>

                    <div className="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label className="text-[11px] uppercase font-bold text-[#475569] tracking-wider block mb-1.5">
                                API Key
                            </label>
                            <input
                                type="text"
                                value={data.steadfast_api_key}
                                onChange={e => setData('steadfast_api_key', e.target.value)}
                                placeholder="Steadfast API Key"
                                className="w-full border border-[#cbd5e1] px-4 py-2.5 rounded-xl text-[13px] font-mono outline-none focus:border-[#0284c7] bg-white shadow-2xs"
                            />
                        </div>

                        <div>
                            <label className="text-[11px] uppercase font-bold text-[#475569] tracking-wider block mb-1.5">
                                Secret Key
                            </label>
                            <input
                                type="password"
                                value={data.steadfast_secret_key}
                                onChange={e => setData('steadfast_secret_key', e.target.value)}
                                placeholder="Steadfast Secret Key"
                                className="w-full border border-[#cbd5e1] px-4 py-2.5 rounded-xl text-[13px] font-mono outline-none focus:border-[#0284c7] bg-white shadow-2xs"
                            />
                        </div>
                    </div>
                </div>

                {/* SUBMIT BAR */}
                <div className="sticky bottom-6 bg-white/95 backdrop-blur-xl border border-[#38bdf8]/40 p-5 rounded-2xl shadow-xl flex items-center justify-between z-30">
                    <span className="text-[12px] text-[#64748b]">Credentials are encrypted before transmission.</span>
                    <button
                        type="submit"
                        disabled={processing}
                        className="px-8 py-3 bg-[#0284c7] hover:bg-[#0369a1] text-white rounded-xl text-[12px] font-bold uppercase tracking-wider shadow-md shadow-[#0284c7]/20 flex items-center gap-2"
                    >
                        <Save className="w-4 h-4" /> Save Courier Credentials
                    </button>
                </div>
            </form>
        </AdminLayout>
    );
}
