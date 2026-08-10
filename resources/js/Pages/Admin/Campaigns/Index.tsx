import React from 'react';
import { Head, Link, router } from '@inertiajs/react';
import AdminLayout from '@/Layouts/AdminLayout';
import { 
    Flag, PlusCircle, Edit3, Trash2, CheckCircle2, XCircle
} from 'lucide-react';

export default function Index({ campaigns }: any) {
    
    const deleteCampaign = (id: number) => {
        if (confirm('Are you sure you want to delete this campaign?')) {
            router.delete(`/admin/campaigns/${id}`);
        }
    };

    const toggleActive = (id: number) => {
        router.post(`/admin/campaigns/${id}/toggle`);
    };

    return (
        <AdminLayout>
            <Head title="Campaigns" />
            
            <div className="max-w-7xl mx-auto space-y-6">
                
                {/* Header */}
                <div className="flex items-center justify-between">
                    <div>
                        <h1 className="text-2xl font-bold tracking-tight text-[#0f172a] flex items-center gap-2">
                            <Flag className="w-6 h-6 text-indigo-600" />
                            Campaigns
                        </h1>
                        <p className="text-[13px] text-slate-500 mt-1">
                            Manage home page campaigns and featured products
                        </p>
                    </div>
                    <Link
                        href="/admin/campaigns/create"
                        className="inline-flex items-center gap-2 px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white text-[13px] font-semibold rounded-lg shadow-sm transition-all"
                    >
                        <PlusCircle className="w-4 h-4" />
                        Create Campaign
                    </Link>
                </div>

                {/* Table */}
                <div className="bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden">
                    <div className="overflow-x-auto">
                        <table className="w-full text-left border-collapse">
                            <thead>
                                <tr className="border-b border-slate-200 bg-slate-50/50">
                                    <th className="px-5 py-4 text-[12px] font-semibold text-slate-500 uppercase tracking-wider">Banner</th>
                                    <th className="px-5 py-4 text-[12px] font-semibold text-slate-500 uppercase tracking-wider">Name / Title</th>
                                    <th className="px-5 py-4 text-[12px] font-semibold text-slate-500 uppercase tracking-wider">Products</th>
                                    <th className="px-5 py-4 text-[12px] font-semibold text-slate-500 uppercase tracking-wider">Status</th>
                                    <th className="px-5 py-4 text-[12px] font-semibold text-slate-500 uppercase tracking-wider text-right">Actions</th>
                                </tr>
                            </thead>
                            <tbody className="divide-y divide-slate-200">
                                {campaigns.length === 0 ? (
                                    <tr>
                                        <td colSpan={5} className="px-5 py-8 text-center text-slate-500 text-[13px]">
                                            No campaigns found. Create one to get started!
                                        </td>
                                    </tr>
                                ) : (
                                    campaigns.map((campaign: any) => (
                                        <tr key={campaign.id} className="hover:bg-slate-50/50 transition-colors">
                                            <td className="px-5 py-4">
                                                {campaign.banner_image_url ? (
                                                    <img src={campaign.banner_image_url} alt="Banner" className="h-12 w-20 object-cover rounded-md border border-slate-200" />
                                                ) : (
                                                    <div className="h-12 w-20 bg-slate-100 rounded-md flex items-center justify-center text-[10px] text-slate-400 border border-slate-200">
                                                        No Image
                                                    </div>
                                                )}
                                            </td>
                                            <td className="px-5 py-4">
                                                <div className="font-semibold text-[14px] text-slate-900">{campaign.name}</div>
                                                <div className="text-[12px] text-slate-500">{campaign.title || 'No Title'}</div>
                                            </td>
                                            <td className="px-5 py-4 text-[13px] font-medium text-slate-600">
                                                {campaign.products_count} assigned
                                            </td>
                                            <td className="px-5 py-4">
                                                <button
                                                    onClick={() => toggleActive(campaign.id)}
                                                    className={`inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-[11px] font-bold tracking-wide uppercase transition-colors ${
                                                        campaign.is_active 
                                                            ? 'bg-emerald-100 text-emerald-700 hover:bg-emerald-200' 
                                                            : 'bg-slate-100 text-slate-600 hover:bg-slate-200'
                                                    }`}
                                                >
                                                    {campaign.is_active ? (
                                                        <><CheckCircle2 className="w-3.5 h-3.5" /> Active</>
                                                    ) : (
                                                        <><XCircle className="w-3.5 h-3.5" /> Inactive</>
                                                    )}
                                                </button>
                                            </td>
                                            <td className="px-5 py-4 text-right space-x-3">
                                                <Link
                                                    href={`/admin/campaigns/${campaign.id}/edit`}
                                                    className="inline-flex items-center gap-1 text-[13px] font-semibold text-indigo-600 hover:text-indigo-800 transition-colors"
                                                >
                                                    <Edit3 className="w-4 h-4" />
                                                    Edit
                                                </Link>
                                                <button
                                                    onClick={() => deleteCampaign(campaign.id)}
                                                    className="inline-flex items-center gap-1 text-[13px] font-semibold text-red-600 hover:text-red-800 transition-colors"
                                                >
                                                    <Trash2 className="w-4 h-4" />
                                                    Delete
                                                </button>
                                            </td>
                                        </tr>
                                    ))
                                )}
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </AdminLayout>
    );
}
