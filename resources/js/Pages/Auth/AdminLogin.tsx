import React, { useState } from 'react';
import { Head, useForm } from '@inertiajs/react';
import { ShieldCheck, Database, Lock, ArrowLeft, KeyRound, Sparkles, CheckCircle2, AlertTriangle, AlertCircle, Mail, Eye, EyeOff } from 'lucide-react';

export default function AdminLogin() {
    const { data, setData, post, processing, errors } = useForm({
        email: '',
        password: '',
        remember: false,
    });

    const [showPassword, setShowPassword] = useState(false);

    const submit = (e: React.FormEvent) => {
        e.preventDefault();
        post('/portal/secure-login');
    };

    const fillDemoCredentials = () => {
        setData({
            ...data,
            email: 'admin@rexxobd.com',
            password: 'password'
        });
    };

    return (
        <div className="bg-gradient-to-br from-[#e0f2fe] via-[#f0f9ff] to-[#bae6fd] text-[#0f172a] min-h-screen flex flex-col justify-between relative overflow-x-hidden selection:bg-[#0284c7] selection:text-white">
            <Head title="Admin Verification Portal" />
            
            {/* Background Atmosphere */}
            <div className="absolute top-0 left-1/2 -translate-x-1/2 w-[900px] h-[500px] bg-[radial-gradient(ellipse_at_center,rgba(56,189,248,0.25),transparent_70%)] pointer-events-none z-0"></div>
            <div className="absolute bottom-0 right-0 w-[600px] h-[600px] bg-[radial-gradient(circle,rgba(2,132,199,0.18),transparent_70%)] pointer-events-none z-0"></div>
            <div className="absolute inset-0 bg-[linear-gradient(to_right,#0284c715_1px,transparent_1px),linear-gradient(to_bottom,#0284c715_1px,transparent_1px)] bg-[size:3.5rem_3.5rem] pointer-events-none z-0"></div>

            {/* Header */}
            <header className="relative z-10 w-full max-w-7xl mx-auto px-6 py-5 flex justify-between items-center border-b border-[#0284c7]/20 backdrop-blur-md bg-white/40">
                <div className="flex items-center gap-3">
                    <div className="w-10 h-10 rounded-lg bg-[#0284c7] text-white flex items-center justify-center shadow-md">
                        <ShieldCheck className="w-6 h-6" />
                    </div>
                    <div>
                        <div className="flex items-center gap-2">
                            <span className="text-[#0f172a] font-bold text-[16px] tracking-wider uppercase font-display">REXXO BD</span>
                            <span className="text-[#0284c7] text-[10px] font-bold bg-[#0284c7]/10 px-2 py-0.5 border border-[#0284c7]/30 uppercase rounded-full">ADMIN PORTAL</span>
                        </div>
                        <span className="text-[11px] text-[#475569] block font-mono">Master Security Authentication System</span>
                    </div>
                </div>

                <div className="flex items-center gap-4 text-[12px]">
                    <div className="hidden sm:flex items-center gap-4 text-[#475569] border-r border-[#0284c7]/20 pr-4 font-mono">
                        <span className="flex items-center gap-1.5"><Database className="w-3.5 h-3.5 text-[#0284c7]" /> SYSTEM ACTIVE</span>
                        <span className="flex items-center gap-1.5"><Lock className="w-3.5 h-3.5 text-emerald-600" /> TLS 1.3 ENCRYPTED</span>
                    </div>

                    <a href="/" target="_blank" className="flex items-center gap-1.5 bg-[#0f172a] hover:bg-[#1e293b] text-white px-4 py-2 rounded-lg transition-all shadow-md text-[12px] uppercase font-bold tracking-wider">
                        <ArrowLeft className="w-3.5 h-3.5" /> Storefront
                    </a>
                </div>
            </header>

            {/* Main Form Area */}
            <main className="relative z-10 max-w-md mx-auto w-full px-4 my-10 animate-fade-in">
                <div className="bg-white/90 backdrop-blur-xl border border-[#38bdf8]/40 shadow-[0_10px_35px_rgba(2,132,199,0.15)] p-7 md:p-9 rounded-2xl relative">
                    
                    <div className="flex items-center justify-between border-b border-[#e2e8f0] pb-5 mb-6">
                        <div className="flex items-center gap-3">
                            <div className="p-2.5 bg-[#e0f2fe] border border-[#38bdf8]/40 text-[#0284c7] rounded-xl">
                                <KeyRound className="w-6 h-6" />
                            </div>
                            <div>
                                <h2 className="text-[18px] font-bold text-[#0f172a] tracking-wider uppercase font-display">
                                    REXXO BD ADMIN PORTAL
                                </h2>
                                <span className="text-[11px] text-[#64748b] block">Authenticate to access admin controls</span>
                            </div>
                        </div>
                        
                        <button type="button" onClick={fillDemoCredentials} className="text-[10px] bg-[#e0f2fe] hover:bg-[#bae6fd] text-[#0284c7] border border-[#38bdf8]/50 px-2.5 py-1.5 font-bold uppercase transition-all rounded-lg cursor-pointer flex items-center gap-1">
                            <Sparkles className="w-3 h-3" /> Auto-fill Demo
                        </button>
                    </div>

                    {errors.email && (
                        <div className="mb-6 p-3.5 bg-rose-500/10 border border-rose-500/40 text-rose-700 text-[12px] flex items-center gap-2 font-bold rounded-xl font-mono">
                            <AlertCircle className="w-4 h-4 shrink-0" />
                            <span>{errors.email}</span>
                        </div>
                    )}

                    <form onSubmit={submit} className="space-y-5">
                        
                        <div>
                            <label className="text-[11px] uppercase font-bold tracking-wider text-[#475569] block mb-1.5">
                                ENGINEER EMAIL / USER ID
                            </label>
                            <div className="flex items-center bg-[#f8fafc] border border-[#cbd5e1] rounded-xl px-3.5 py-3 focus-within:border-[#0284c7] focus-within:ring-2 focus-within:ring-[#0284c7]/20 transition-all">
                                <Mail className="w-4 h-4 text-[#64748b] mr-3 shrink-0" />
                                <input 
                                    type="email" 
                                    name="email" 
                                    value={data.email}
                                    onChange={(e) => setData('email', e.target.value)}
                                    required 
                                    className="w-full bg-transparent text-[14px] text-[#0f172a] border-none ring-0 outline-none focus:ring-0 focus:border-none focus:outline-none focus-visible:outline-none placeholder:text-[#94a3b8] font-medium" 
                                />
                            </div>
                        </div>

                        <div>
                            <label className="text-[11px] uppercase font-bold tracking-wider text-[#475569] block mb-1.5">
                                CRYPTOGRAPHIC PASSPHRASE
                            </label>
                            <div className="flex items-center bg-[#f8fafc] border border-[#cbd5e1] rounded-xl px-3.5 py-3 focus-within:border-[#0284c7] focus-within:ring-2 focus-within:ring-[#0284c7]/20 transition-all">
                                <Lock className="w-4 h-4 text-[#64748b] mr-3 shrink-0" />
                                <input 
                                    type={showPassword ? 'text' : 'password'} 
                                    name="password" 
                                    value={data.password}
                                    onChange={(e) => setData('password', e.target.value)}
                                    required 
                                    className="w-full bg-transparent text-[14px] text-[#0f172a] border-none ring-0 outline-none focus:ring-0 focus:border-none focus:outline-none focus-visible:outline-none font-medium" 
                                />
                                <button type="button" onClick={() => setShowPassword(!showPassword)} className="text-[#64748b] hover:text-[#0284c7] transition-colors ml-2 p-1">
                                    {showPassword ? <EyeOff className="w-4 h-4" /> : <Eye className="w-4 h-4" />}
                                </button>
                            </div>
                        </div>

                        <button 
                            type="submit" 
                            disabled={processing}
                            className="w-full bg-gradient-to-r from-[#0284c7] to-[#0ea5e9] hover:from-[#0369a1] hover:to-[#0284c7] text-white py-4 text-[13px] font-bold tracking-[0.16em] uppercase transition-all duration-200 shadow-lg shadow-[#0284c7]/25 rounded-xl flex items-center justify-center gap-2 cursor-pointer mt-3 disabled:opacity-75"
                        >
                            <KeyRound className="w-4 h-4" />
                            VERIFY AUTH →
                        </button>
                    </form>

                    <div className="mt-6 pt-5 border-t border-[#e2e8f0] flex justify-between items-center text-[11px] text-[#64748b] font-mono">
                        <span className="flex items-center gap-1.5">
                            <CheckCircle2 className="w-3.5 h-3.5 text-emerald-600" /> AES-256 ENCRYPTED
                        </span>
                        <span className="flex items-center gap-1">
                            <ShieldCheck className="w-3.5 h-3.5 text-[#0284c7]" /> IP LOGGING ACTIVE
                        </span>
                    </div>
                </div>
            </main>

            <footer className="relative z-10 max-w-7xl mx-auto w-full px-6 py-4 border-t border-[#0284c7]/20 text-center text-[11px] text-[#64748b] flex flex-col sm:flex-row justify-between items-center gap-2 font-mono bg-white/30 backdrop-blur-sm">
                <div>ReXxo Core OS © 2026 ReXxo Bd Enterprise Systems. All Rights Reserved.</div>
                <div className="flex items-center gap-3 text-[10px]">
                    <span>SYSTEM LATENCY: 1.2ms</span>
                    <span>•</span>
                    <span className="text-emerald-600 font-bold">STATUS: OPTIMAL</span>
                </div>
            </footer>
        </div>
    );
}

AdminLogin.layout = (page: React.ReactNode) => <>{page}</>;
