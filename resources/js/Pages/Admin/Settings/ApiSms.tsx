import React from 'react';
import { Head, useForm } from '@inertiajs/react';
import AdminLayout from '@/Layouts/AdminLayout';
import { MessageSquare, Save, Send, Radio } from 'lucide-react';

interface ApiSmsProps {
    siteSettings?: Record<string, any>;
}

export default function ApiSms({ siteSettings = {} }: ApiSmsProps) {
    const { data, setData, post, processing } = useForm({
        sms_on_new_order: siteSettings.sms_on_new_order || '0',
        sms_on_dispatch: siteSettings.sms_on_dispatch || '0',
        sms_on_delivered: siteSettings.sms_on_delivered || '0',
        sms_on_cancelled: siteSettings.sms_on_cancelled || '0',
        
        bulksms_enabled: siteSettings.bulksms_enabled || '0',
        bulksms_api_key: siteSettings.bulksms_api_key || '',
        bulksms_sender_id: siteSettings.bulksms_sender_id || '',
        bulksms_url: siteSettings.bulksms_url || 'http://bulksmsbd.net/api/smsapi',

        mimsms_enabled: siteSettings.mimsms_enabled || '0',
        mimsms_api_key: siteSettings.mimsms_api_key || '',
        mimsms_sender_id: siteSettings.mimsms_sender_id || '',
        mimsms_url: siteSettings.mimsms_url || 'https://esms.mimsms.com/smsapi',
    });

    const handleCheckboxChange = (field: keyof typeof data) => (e: React.ChangeEvent<HTMLInputElement>) => {
        setData(field, e.target.checked ? '1' : '0');
    };

    const handleSubmit = (e: React.FormEvent) => {
        e.preventDefault();
        post('/api/settings', {
            preserveScroll: true,
        });
    };

    return (
        <AdminLayout
            activePage="api_sms"
            pageTitle="SMS Gateway Configuration"
            pageSubtitle="Configure automated SMS alerts for orders, dispatch tracking, and delivery updates."
        >
            <Head title="SMS Gateways — Admin" />

            <form onSubmit={handleSubmit} className="space-y-6">
                
                {/* Trigger Events */}
                <div className="bg-white/90 backdrop-blur-xl border border-[#38bdf8]/30 p-7 rounded-2xl space-y-4 shadow-sm">
                    <h3 className="text-[15px] font-serif font-bold text-[#0f172a] uppercase border-l-4 border-[#0284c7] pl-3 flex items-center gap-2">
                        <Send className="w-4 h-4 text-[#0284c7]" /> Trigger Events — When to Send Automated SMS
                    </h3>
                    <div className="grid grid-cols-1 md:grid-cols-2 gap-3 bg-[#f8fafc] p-4 rounded-xl border border-[#e2e8f0]">
                        <label className="flex items-center gap-3 p-3 bg-white rounded-xl border border-transparent cursor-pointer hover:border-[#0284c7] transition-all shadow-sm">
                            <input
                                type="checkbox"
                                checked={data.sms_on_new_order === '1'}
                                onChange={handleCheckboxChange('sms_on_new_order')}
                                className="w-4 h-4 text-[#0284c7] accent-[#0284c7] rounded border-gray-300"
                            />
                            <div>
                                <span className="text-[12px] font-bold text-[#0f172a] block">New Order Placed</span>
                                <span className="text-[11px] text-[#64748b]">Send instant confirmation SMS when order is placed</span>
                            </div>
                        </label>

                        <label className="flex items-center gap-3 p-3 bg-white rounded-xl border border-transparent cursor-pointer hover:border-[#0284c7] transition-all shadow-sm">
                            <input
                                type="checkbox"
                                checked={data.sms_on_dispatch === '1'}
                                onChange={handleCheckboxChange('sms_on_dispatch')}
                                className="w-4 h-4 text-[#0284c7] accent-[#0284c7] rounded border-gray-300"
                            />
                            <div>
                                <span className="text-[12px] font-bold text-[#0f172a] block">Order Dispatched / Shipped</span>
                                <span className="text-[11px] text-[#64748b]">Send SMS with courier tracking code & delivery link</span>
                            </div>
                        </label>

                        <label className="flex items-center gap-3 p-3 bg-white rounded-xl border border-transparent cursor-pointer hover:border-[#0284c7] transition-all shadow-sm">
                            <input
                                type="checkbox"
                                checked={data.sms_on_delivered === '1'}
                                onChange={handleCheckboxChange('sms_on_delivered')}
                                className="w-4 h-4 text-[#0284c7] accent-[#0284c7] rounded border-gray-300"
                            />
                            <div>
                                <span className="text-[12px] font-bold text-[#0f172a] block">Order Delivered</span>
                                <span className="text-[11px] text-[#64748b]">Send thank you SMS when delivery is completed</span>
                            </div>
                        </label>

                        <label className="flex items-center gap-3 p-3 bg-white rounded-xl border border-transparent cursor-pointer hover:border-[#0284c7] transition-all shadow-sm">
                            <input
                                type="checkbox"
                                checked={data.sms_on_cancelled === '1'}
                                onChange={handleCheckboxChange('sms_on_cancelled')}
                                className="w-4 h-4 text-[#0284c7] accent-[#0284c7] rounded border-gray-300"
                            />
                            <div>
                                <span className="text-[12px] font-bold text-[#0f172a] block">Order Cancelled</span>
                                <span className="text-[11px] text-[#64748b]">Send notification if an order is cancelled</span>
                            </div>
                        </label>
                    </div>
                </div>

                {/* BulkSMS BD Gateway */}
                <div className="bg-white/90 backdrop-blur-xl border border-[#38bdf8]/30 p-7 rounded-2xl space-y-6 shadow-sm">
                    <div className="flex items-center justify-between border-b border-[#e2e8f0] pb-4">
                        <div className="flex items-center gap-3">
                            <div className="p-2.5 bg-[#e0f2fe] text-[#0284c7] rounded-xl">
                                <Radio className="w-5 h-5" />
                            </div>
                            <div>
                                <h3 className="text-base font-serif font-bold text-[#0f172a] uppercase tracking-wide">
                                    BulkSMS BD Gateway
                                </h3>
                                <p className="text-[11px] text-[#64748b]">bulksmsbd.net</p>
                            </div>
                        </div>
                        <label className="relative inline-flex items-center cursor-pointer">
                            <input
                                type="checkbox"
                                checked={data.bulksms_enabled === '1'}
                                onChange={handleCheckboxChange('bulksms_enabled')}
                                className="sr-only peer"
                            />
                            <div className="w-11 h-6 bg-gray-200 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-[#0284c7]"></div>
                        </label>
                    </div>

                    <div className="grid grid-cols-1 md:grid-cols-3 gap-6">
                        <div>
                            <label className="text-[11px] uppercase font-bold text-[#475569] tracking-wider block mb-1.5">
                                API Key
                            </label>
                            <input
                                type="password"
                                value={data.bulksms_api_key}
                                onChange={e => setData('bulksms_api_key', e.target.value)}
                                placeholder="Enter BulkSMS BD API Key"
                                className="w-full border border-[#cbd5e1] px-4 py-2.5 rounded-xl text-[13px] font-mono outline-none focus:border-[#0284c7] bg-white shadow-2xs"
                            />
                        </div>

                        <div>
                            <label className="text-[11px] uppercase font-bold text-[#475569] tracking-wider block mb-1.5">
                                Sender ID / Masking
                            </label>
                            <input
                                type="text"
                                value={data.bulksms_sender_id}
                                onChange={e => setData('bulksms_sender_id', e.target.value)}
                                placeholder="e.g. 8809612xxxxxx or Brand Name"
                                className="w-full border border-[#cbd5e1] px-4 py-2.5 rounded-xl text-[13px] font-medium outline-none focus:border-[#0284c7] bg-white shadow-2xs"
                            />
                        </div>

                        <div>
                            <label className="text-[11px] uppercase font-bold text-[#475569] tracking-wider block mb-1.5">
                                API Base URL
                            </label>
                            <input
                                type="text"
                                value={data.bulksms_url}
                                onChange={e => setData('bulksms_url', e.target.value)}
                                placeholder="http://bulksmsbd.net/api/smsapi"
                                className="w-full border border-[#cbd5e1] px-4 py-2.5 rounded-xl text-[13px] font-mono outline-none focus:border-[#0284c7] bg-white shadow-2xs"
                            />
                        </div>
                    </div>
                </div>

                {/* MiM SMS Gateway */}
                <div className="bg-white/90 backdrop-blur-xl border border-[#38bdf8]/30 p-7 rounded-2xl space-y-6 shadow-sm">
                    <div className="flex items-center justify-between border-b border-[#e2e8f0] pb-4">
                        <div className="flex items-center gap-3">
                            <div className="p-2.5 bg-emerald-50 text-emerald-600 rounded-xl">
                                <Radio className="w-5 h-5" />
                            </div>
                            <div>
                                <h3 className="text-base font-serif font-bold text-[#0f172a] uppercase tracking-wide">
                                    MiM SMS Gateway
                                </h3>
                                <p className="text-[11px] text-[#64748b]">mimsms.com</p>
                            </div>
                        </div>
                        <label className="relative inline-flex items-center cursor-pointer">
                            <input
                                type="checkbox"
                                checked={data.mimsms_enabled === '1'}
                                onChange={handleCheckboxChange('mimsms_enabled')}
                                className="sr-only peer"
                            />
                            <div className="w-11 h-6 bg-gray-200 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-emerald-600"></div>
                        </label>
                    </div>

                    <div className="grid grid-cols-1 md:grid-cols-3 gap-6">
                        <div>
                            <label className="text-[11px] uppercase font-bold text-[#475569] tracking-wider block mb-1.5">
                                API Key
                            </label>
                            <input
                                type="password"
                                value={data.mimsms_api_key}
                                onChange={e => setData('mimsms_api_key', e.target.value)}
                                placeholder="Enter MiM SMS API Key"
                                className="w-full border border-[#cbd5e1] px-4 py-2.5 rounded-xl text-[13px] font-mono outline-none focus:border-[#0284c7] bg-white shadow-2xs"
                            />
                        </div>

                        <div>
                            <label className="text-[11px] uppercase font-bold text-[#475569] tracking-wider block mb-1.5">
                                Sender ID / Masking
                            </label>
                            <input
                                type="text"
                                value={data.mimsms_sender_id}
                                onChange={e => setData('mimsms_sender_id', e.target.value)}
                                placeholder="e.g. 8809612xxxxxx or Brand Name"
                                className="w-full border border-[#cbd5e1] px-4 py-2.5 rounded-xl text-[13px] font-medium outline-none focus:border-[#0284c7] bg-white shadow-2xs"
                            />
                        </div>

                        <div>
                            <label className="text-[11px] uppercase font-bold text-[#475569] tracking-wider block mb-1.5">
                                API Base URL
                            </label>
                            <input
                                type="text"
                                value={data.mimsms_url}
                                onChange={e => setData('mimsms_url', e.target.value)}
                                placeholder="https://esms.mimsms.com/smsapi"
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
                        <Save className="w-4 h-4" /> Save SMS Settings
                    </button>
                </div>
            </form>
        </AdminLayout>
    );
}
