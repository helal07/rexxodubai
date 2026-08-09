import React from 'react';
import { Head, useForm } from '@inertiajs/react';
import AdminLayout from '@/Layouts/AdminLayout';
import { CreditCard, Save, Smartphone, Zap } from 'lucide-react';

interface ApiPaymentProps {
    siteSettings?: Record<string, any>;
}

export default function ApiPayment({ siteSettings = {} }: ApiPaymentProps) {
    const { data, setData, post, processing } = useForm({
        ssl_store_id: siteSettings.ssl_store_id || '',
        ssl_store_passwd: siteSettings.ssl_store_passwd || '',
        bkash_app_key: siteSettings.bkash_app_key || '',
        bkash_app_secret: siteSettings.bkash_app_secret || '',
        bkash_username: siteSettings.bkash_username || '',
        bkash_password: siteSettings.bkash_password || '',
        eps_merchant_id: siteSettings.eps_merchant_id || '',
        eps_password: siteSettings.eps_password || '',
    });

    const handleSubmit = (e: React.FormEvent) => {
        e.preventDefault();
        post('/api/settings', {
            preserveScroll: true,
        });
    };

    return (
        <AdminLayout
            activePage="api_payment"
            pageTitle="Payment Gateway Integrations"
            pageSubtitle="Configure API credentials for SSLCommerz, bKash, and EPS Payment Gateway."
        >
            <Head title="Payment Gateways — Admin" />

            <form onSubmit={handleSubmit} className="space-y-6">
                
                {/* SSLCommerz Gateway */}
                <div className="bg-white/90 backdrop-blur-xl border border-[#38bdf8]/30 p-7 rounded-2xl space-y-6 shadow-sm">
                    <div className="flex items-center gap-3 border-b border-[#e2e8f0] pb-4">
                        <div className="p-2.5 bg-[#e0f2fe] text-[#0284c7] rounded-xl">
                            <CreditCard className="w-5 h-5" />
                        </div>
                        <div>
                            <h3 className="text-base font-serif font-bold text-[#0f172a] uppercase tracking-wide">
                                SSLCommerz Merchant Gateway
                            </h3>
                            <p className="text-[11px] text-[#64748b]">Accept Visa, Mastercard, and Mobile Banking</p>
                        </div>
                    </div>

                    <div className="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label className="text-[11px] uppercase font-bold text-[#475569] tracking-wider block mb-1.5">
                                SSL Store ID
                            </label>
                            <input
                                type="text"
                                value={data.ssl_store_id}
                                onChange={e => setData('ssl_store_id', e.target.value)}
                                placeholder="Store ID"
                                className="w-full border border-[#cbd5e1] px-4 py-2.5 rounded-xl text-[13px] font-mono outline-none focus:border-[#0284c7] bg-white shadow-2xs"
                            />
                        </div>

                        <div>
                            <label className="text-[11px] uppercase font-bold text-[#475569] tracking-wider block mb-1.5">
                                SSL Store Password
                            </label>
                            <input
                                type="password"
                                value={data.ssl_store_passwd}
                                onChange={e => setData('ssl_store_passwd', e.target.value)}
                                placeholder="Store Password"
                                className="w-full border border-[#cbd5e1] px-4 py-2.5 rounded-xl text-[13px] font-mono outline-none focus:border-[#0284c7] bg-white shadow-2xs"
                            />
                        </div>
                    </div>
                </div>

                {/* bKash Payment Gateway */}
                <div className="bg-white/90 backdrop-blur-xl border border-[#38bdf8]/30 p-7 rounded-2xl space-y-6 shadow-sm">
                    <div className="flex items-center gap-3 border-b border-[#e2e8f0] pb-4">
                        <div className="p-2.5 bg-pink-50 text-pink-600 rounded-xl">
                            <Smartphone className="w-5 h-5" />
                        </div>
                        <div>
                            <h3 className="text-base font-serif font-bold text-[#0f172a] uppercase tracking-wide">
                                bKash API Gateway
                            </h3>
                            <p className="text-[11px] text-[#64748b]">Direct bKash automated payment integration</p>
                        </div>
                    </div>

                    <div className="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label className="text-[11px] uppercase font-bold text-[#475569] tracking-wider block mb-1.5">
                                bKash App Key
                            </label>
                            <input
                                type="text"
                                value={data.bkash_app_key}
                                onChange={e => setData('bkash_app_key', e.target.value)}
                                placeholder="App Key"
                                className="w-full border border-[#cbd5e1] px-4 py-2.5 rounded-xl text-[13px] font-mono outline-none focus:border-[#0284c7] bg-white shadow-2xs"
                            />
                        </div>

                        <div>
                            <label className="text-[11px] uppercase font-bold text-[#475569] tracking-wider block mb-1.5">
                                bKash App Secret
                            </label>
                            <input
                                type="password"
                                value={data.bkash_app_secret}
                                onChange={e => setData('bkash_app_secret', e.target.value)}
                                placeholder="App Secret"
                                className="w-full border border-[#cbd5e1] px-4 py-2.5 rounded-xl text-[13px] font-mono outline-none focus:border-[#0284c7] bg-white shadow-2xs"
                            />
                        </div>

                        <div>
                            <label className="text-[11px] uppercase font-bold text-[#475569] tracking-wider block mb-1.5">
                                bKash Username
                            </label>
                            <input
                                type="text"
                                value={data.bkash_username}
                                onChange={e => setData('bkash_username', e.target.value)}
                                placeholder="Merchant Username"
                                className="w-full border border-[#cbd5e1] px-4 py-2.5 rounded-xl text-[13px] font-mono outline-none focus:border-[#0284c7] bg-white shadow-2xs"
                            />
                        </div>

                        <div>
                            <label className="text-[11px] uppercase font-bold text-[#475569] tracking-wider block mb-1.5">
                                bKash Password
                            </label>
                            <input
                                type="password"
                                value={data.bkash_password}
                                onChange={e => setData('bkash_password', e.target.value)}
                                placeholder="Merchant Password"
                                className="w-full border border-[#cbd5e1] px-4 py-2.5 rounded-xl text-[13px] font-mono outline-none focus:border-[#0284c7] bg-white shadow-2xs"
                            />
                        </div>
                    </div>
                </div>

                {/* EPS (Easy Payment System) Gateway */}
                <div className="bg-white/90 backdrop-blur-xl border border-[#38bdf8]/30 p-7 rounded-2xl space-y-6 shadow-sm">
                    <div className="flex items-center gap-3 border-b border-[#e2e8f0] pb-4">
                        <div className="p-2.5 bg-emerald-50 text-emerald-600 rounded-xl">
                            <Zap className="w-5 h-5" />
                        </div>
                        <div>
                            <h3 className="text-base font-serif font-bold text-[#0f172a] uppercase tracking-wide">
                                EPS (Easy Payment System)
                            </h3>
                            <p className="text-[11px] text-[#64748b]">Enable EPS multi-payment network</p>
                        </div>
                    </div>

                    <div className="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label className="text-[11px] uppercase font-bold text-[#475569] tracking-wider block mb-1.5">
                                EPS Merchant ID
                            </label>
                            <input
                                type="text"
                                value={data.eps_merchant_id}
                                onChange={e => setData('eps_merchant_id', e.target.value)}
                                placeholder="Merchant ID"
                                className="w-full border border-[#cbd5e1] px-4 py-2.5 rounded-xl text-[13px] font-mono outline-none focus:border-[#0284c7] bg-white shadow-2xs"
                            />
                        </div>

                        <div>
                            <label className="text-[11px] uppercase font-bold text-[#475569] tracking-wider block mb-1.5">
                                EPS Password / Secret Key
                            </label>
                            <input
                                type="password"
                                value={data.eps_password}
                                onChange={e => setData('eps_password', e.target.value)}
                                placeholder="EPS Password or Secret Key"
                                className="w-full border border-[#cbd5e1] px-4 py-2.5 rounded-xl text-[13px] font-mono outline-none focus:border-[#0284c7] bg-white shadow-2xs"
                            />
                        </div>
                    </div>
                </div>

                <div className="sticky bottom-6 bg-white/95 backdrop-blur-xl border border-[#38bdf8]/40 p-5 rounded-2xl shadow-xl flex items-center justify-between z-30">
                    <span className="text-[12px] text-[#64748b]">All API credentials are encrypted securely.</span>
                    <button
                        type="submit"
                        disabled={processing}
                        className="px-8 py-3 bg-[#0284c7] hover:bg-[#0369a1] text-white rounded-xl text-[12px] font-bold uppercase tracking-wider shadow-md shadow-[#0284c7]/20 flex items-center gap-2"
                    >
                        <Save className="w-4 h-4" /> Save Payment Gateways
                    </button>
                </div>
            </form>
        </AdminLayout>
    );
}
