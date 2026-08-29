import React, { useState, useEffect } from 'react';
import { Head } from '@inertiajs/react';
import axios from 'axios';
import AdminLayout from '@/Layouts/AdminLayout';
import { Network, RefreshCw, FileText, Globe, CheckCircle2, XCircle } from 'lucide-react';

export default function Ping() {
    const [status, setStatus] = useState<any>(null);
    const [loading, setLoading] = useState({
        sitemap: false,
        robots: false,
        ping: false,
        init: true,
    });
    const [message, setMessage] = useState<{ type: 'success' | 'error'; text: string } | null>(null);

    const loadStatus = async () => {
        try {
            const res = await axios.get('/admin/seo/status', {
                headers: { 'Accept': 'application/json' }
            });
            setStatus(res.data);
        } catch (e) {
            console.error('Error loading SEO status:', e);
        } finally {
            setLoading(prev => ({ ...prev, init: false }));
        }
    };

    useEffect(() => {
        loadStatus();
    }, []);

    const handleAction = async (action: 'sitemap' | 'robots' | 'ping', endpoint: string) => {
        setLoading(prev => ({ ...prev, [action]: true }));
        setMessage(null);
        try {
            const res = await axios.post(endpoint, {}, {
                headers: {
                    'Accept': 'application/json',
                    'Content-Type': 'application/json'
                }
            });
            const data = res.data;
            if (data.success) {
                setMessage({ type: 'success', text: data.message });
                loadStatus();
            } else {
                setMessage({ type: 'error', text: data.message || 'Action failed.' });
            }
        } catch (e: any) {
            setMessage({ type: 'error', text: e.message || 'An error occurred.' });
        } finally {
            setLoading(prev => ({ ...prev, [action]: false }));
        }
    };

    return (
        <AdminLayout
            activePage="seo_ping"
            pageTitle="Search Engine Tools"
            pageSubtitle="Generate Sitemap.xml, Robots.txt, and Ping Google/Bing."
        >
            <Head title="Search Engine Tools — Admin" />

            {message && (
                <div className={`p-4 rounded-xl text-[13px] font-semibold flex items-center gap-3 mb-6 ${message.type === 'success' ? 'bg-emerald-50 border border-emerald-200 text-emerald-800' : 'bg-rose-50 border border-rose-200 text-rose-800'}`}>
                    {message.type === 'success' ? <CheckCircle2 className="w-5 h-5" /> : <XCircle className="w-5 h-5" />}
                    <span>{message.text}</span>
                </div>
            )}

            <div className="grid grid-cols-1 md:grid-cols-3 gap-6">
                
                {/* Sitemap Generator */}
                <div className="bg-white/90 backdrop-blur-xl border border-[#38bdf8]/30 p-7 rounded-2xl shadow-sm flex flex-col justify-between">
                    <div>
                        <div className="w-12 h-12 bg-blue-50 text-blue-600 rounded-2xl flex items-center justify-center mb-4">
                            <Network className="w-6 h-6" />
                        </div>
                        <h3 className="text-[16px] font-serif font-bold text-[#0f172a] mb-1">Generate Sitemap</h3>
                        <p className="text-[12px] text-[#64748b] leading-relaxed mb-4">
                            Automatically build an XML sitemap of all your public products, categories, and pages for search engines.
                        </p>
                        
                        {status && (
                            <div className="bg-[#f8fafc] p-4 rounded-xl text-[11px] mb-6 border border-[#e2e8f0] space-y-2">
                                <div className="flex justify-between">
                                    <span className="text-[#64748b]">Status</span>
                                    <span className={status.sitemap.exists ? 'text-emerald-600 font-bold' : 'text-rose-500 font-bold'}>
                                        {status.sitemap.exists ? 'File Found' : 'Missing'}
                                    </span>
                                </div>
                                <div className="flex justify-between">
                                    <span className="text-[#64748b]">Last Updated</span>
                                    <span className="font-mono text-[#0f172a]">{status.sitemap.last_updated || 'N/A'}</span>
                                </div>
                                <div className="flex justify-between">
                                    <span className="text-[#64748b]">Total URLs</span>
                                    <span className="font-mono font-bold text-[#0f172a]">{status.sitemap.entries}</span>
                                </div>
                            </div>
                        )}
                    </div>
                    
                    <button
                        onClick={() => handleAction('sitemap', '/admin/seo/generate-sitemap')}
                        disabled={loading.sitemap}
                        className="w-full py-3 bg-[#0f172a] hover:bg-[#1e293b] text-white rounded-xl text-[12px] font-bold uppercase tracking-wider transition-all flex items-center justify-center gap-2"
                    >
                        <RefreshCw className={`w-4 h-4 ${loading.sitemap ? 'animate-spin' : ''}`} />
                        {loading.sitemap ? 'Generating...' : 'Build Sitemap.xml'}
                    </button>
                </div>

                {/* Robots Generator */}
                <div className="bg-white/90 backdrop-blur-xl border border-[#38bdf8]/30 p-7 rounded-2xl shadow-sm flex flex-col justify-between">
                    <div>
                        <div className="w-12 h-12 bg-amber-50 text-amber-600 rounded-2xl flex items-center justify-center mb-4">
                            <FileText className="w-6 h-6" />
                        </div>
                        <h3 className="text-[16px] font-serif font-bold text-[#0f172a] mb-1">Generate Robots.txt</h3>
                        <p className="text-[12px] text-[#64748b] leading-relaxed mb-4">
                            Create a standard robots.txt file to guide search engine crawlers and point them to your sitemap.
                        </p>

                        {status && (
                            <div className="bg-[#f8fafc] p-4 rounded-xl text-[11px] mb-6 border border-[#e2e8f0] space-y-2">
                                <div className="flex justify-between">
                                    <span className="text-[#64748b]">Status</span>
                                    <span className={status.robots.exists ? 'text-emerald-600 font-bold' : 'text-rose-500 font-bold'}>
                                        {status.robots.exists ? 'File Found' : 'Missing'}
                                    </span>
                                </div>
                                <div className="flex justify-between">
                                    <span className="text-[#64748b]">Last Updated</span>
                                    <span className="font-mono text-[#0f172a]">{status.robots.last_updated || 'N/A'}</span>
                                </div>
                            </div>
                        )}
                    </div>
                    
                    <button
                        onClick={() => handleAction('robots', '/admin/seo/generate-robots')}
                        disabled={loading.robots}
                        className="w-full py-3 bg-[#0f172a] hover:bg-[#1e293b] text-white rounded-xl text-[12px] font-bold uppercase tracking-wider transition-all flex items-center justify-center gap-2"
                    >
                        <RefreshCw className={`w-4 h-4 ${loading.robots ? 'animate-spin' : ''}`} />
                        {loading.robots ? 'Generating...' : 'Build Robots.txt'}
                    </button>
                </div>

                {/* Search Engine Ping */}
                <div className="bg-white/90 backdrop-blur-xl border border-[#38bdf8]/30 p-7 rounded-2xl shadow-sm flex flex-col justify-between">
                    <div>
                        <div className="w-12 h-12 bg-emerald-50 text-emerald-600 rounded-2xl flex items-center justify-center mb-4">
                            <Globe className="w-6 h-6" />
                        </div>
                        <h3 className="text-[16px] font-serif font-bold text-[#0f172a] mb-1">Ping Google & Bing</h3>
                        <p className="text-[12px] text-[#64748b] leading-relaxed mb-4">
                            Notify Google and Bing immediately after generating a new sitemap to index your updated products faster.
                        </p>
                    </div>
                    
                    <button
                        onClick={() => handleAction('ping', '/admin/seo/ping-search-engines')}
                        disabled={loading.ping || (status && !status.sitemap.exists)}
                        className="w-full py-3 bg-[#0284c7] hover:bg-[#0369a1] text-white rounded-xl text-[12px] font-bold uppercase tracking-wider shadow-md shadow-[#0284c7]/20 transition-all flex items-center justify-center gap-2 disabled:opacity-50 disabled:cursor-not-allowed"
                    >
                        <Network className={`w-4 h-4 ${loading.ping ? 'animate-pulse' : ''}`} />
                        {loading.ping ? 'Pinging Engines...' : 'Ping Search Engines'}
                    </button>
                </div>
            </div>
        </AdminLayout>
    );
}
