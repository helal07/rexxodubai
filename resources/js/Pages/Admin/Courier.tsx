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
    couriers?: Record<string, any>;
}

export default function Courier({ couriers = {} }: CourierProps) {
    const { data, setData, post, processing } = useForm({
        pathao_client_id: couriers.pathao?.credentials?.client_id || '',
        pathao_client_secret: couriers.pathao?.credentials?.client_secret || '',
        pathao_username: couriers.pathao?.credentials?.username || '',
        pathao_password: couriers.pathao?.credentials?.password || '',
        pathao_store_id: couriers.pathao?.credentials?.store_id || '',
        steadfast_api_key: couriers.steadfast?.credentials?.api_key || '',
        steadfast_secret_key: couriers.steadfast?.credentials?.secret_key || '',
        redx_api_token: couriers.redx?.credentials?.api_token || '',
        paperfly_username: couriers.paperfly?.credentials?.username || '',
        paperfly_password: couriers.paperfly?.credentials?.password || '',
        paperfly_key: couriers.paperfly?.credentials?.key || '',
        sundarban_branch_code: couriers.sundarban?.credentials?.branch_code || '',
        sundarban_booking_phone: couriers.sundarban?.credentials?.booking_phone || '',
    });

    const handleSubmit = (e: React.FormEvent) => {
        e.preventDefault();
        
        // Map flat data into the expected couriers array structure for AdminCourierController
        const couriersPayload = {
            pathao: {
                credentials: {
                    client_id: data.pathao_client_id,
                    client_secret: data.pathao_client_secret,
                    username: data.pathao_username,
                    password: data.pathao_password,
                    store_id: data.pathao_store_id
                },
                status: data.pathao_client_id ? 'active' : 'inactive'
            },
            steadfast: {
                credentials: {
                    api_key: data.steadfast_api_key,
                    secret_key: data.steadfast_secret_key
                },
                status: data.steadfast_api_key ? 'active' : 'inactive'
            },
            redx: {
                credentials: {
                    api_token: data.redx_api_token
                },
                status: data.redx_api_token ? 'active' : 'inactive'
            },
            paperfly: {
                credentials: {
                    username: data.paperfly_username,
                    password: data.paperfly_password,
                    key: data.paperfly_key
                },
                status: data.paperfly_username ? 'active' : 'inactive'
            },
            sundarban: {
                credentials: {
                    branch_code: data.sundarban_branch_code,
                    booking_phone: data.sundarban_booking_phone
                },
                status: (data.sundarban_branch_code || data.sundarban_booking_phone) ? 'active' : 'inactive'
            }
        };

        router.post('/admin/courier/settings', {
            couriers: couriersPayload
        }, {
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

                {/* 3. REDX COURIER API */}
                <div className="bg-white/90 backdrop-blur-xl border border-red-500/30 p-7 rounded-2xl space-y-6 shadow-sm">
                    <div className="flex items-center gap-3 border-b border-[#e2e8f0] pb-4">
                        <div className="p-2.5 bg-red-50 text-red-600 rounded-xl">
                            <Truck className="w-5 h-5" />
                        </div>
                        <div>
                            <h3 className="text-base font-serif font-bold text-[#0f172a] uppercase tracking-wide">
                                RedX Courier API Credentials
                            </h3>
                            <p className="text-[11px] text-[#64748b]">Automated booking & tracking via RedX API</p>
                        </div>
                    </div>
                    <div className="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label className="text-[11px] uppercase font-bold text-[#475569] tracking-wider block mb-1.5">
                                API Token
                            </label>
                            <input
                                type="text"
                                value={data.redx_api_token}
                                onChange={e => setData('redx_api_token', e.target.value)}
                                placeholder="RedX API Token"
                                className="w-full border border-[#cbd5e1] px-4 py-2.5 rounded-xl text-[13px] font-mono outline-none focus:border-red-600 bg-white shadow-2xs"
                            />
                        </div>
                    </div>
                </div>

                {/* 4. SUNDARBAN COURIER */}
                <div className="bg-white/90 backdrop-blur-xl border border-orange-500/30 p-7 rounded-2xl space-y-6 shadow-sm">
                    <div className="flex items-center gap-3 border-b border-[#e2e8f0] pb-4">
                        <div className="p-2.5 bg-orange-50 text-orange-600 rounded-xl">
                            <Truck className="w-5 h-5" />
                        </div>
                        <div>
                            <h3 className="text-base font-serif font-bold text-[#0f172a] uppercase tracking-wide">
                                Sundarban Courier Configuration
                            </h3>
                            <p className="text-[11px] text-[#64748b]">Configure Sundarban courier branch details</p>
                        </div>
                    </div>
                    <div className="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label className="text-[11px] uppercase font-bold text-[#475569] tracking-wider block mb-1.5">
                                Branch Code
                            </label>
                            <input
                                type="text"
                                value={data.sundarban_branch_code}
                                onChange={e => setData('sundarban_branch_code', e.target.value)}
                                placeholder="Branch Code"
                                className="w-full border border-[#cbd5e1] px-4 py-2.5 rounded-xl text-[13px] font-mono outline-none focus:border-orange-600 bg-white shadow-2xs"
                            />
                        </div>
                        <div>
                            <label className="text-[11px] uppercase font-bold text-[#475569] tracking-wider block mb-1.5">
                                Booking Phone
                            </label>
                            <input
                                type="text"
                                value={data.sundarban_booking_phone}
                                onChange={e => setData('sundarban_booking_phone', e.target.value)}
                                placeholder="Booking Phone"
                                className="w-full border border-[#cbd5e1] px-4 py-2.5 rounded-xl text-[13px] font-mono outline-none focus:border-orange-600 bg-white shadow-2xs"
                            />
                        </div>
                    </div>
                </div>

                <div className="flex justify-end pt-4 border-t border-slate-200">
                    <button
                        type="submit"
                        disabled={processing}
                        className="bg-[#0f172a] text-white px-8 py-3 rounded-xl font-bold uppercase tracking-wide text-[12px] flex items-center gap-2 hover:bg-[#1e293b] transition-colors disabled:opacity-50"
                    >
                        <Save className="w-4 h-4" /> Save Configuration
                    </button>
                </div>
            </form>
        </AdminLayout>
    );
}
