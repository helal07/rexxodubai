import React from 'react';
import { Head, Link, router } from '@inertiajs/react';
import AdminLayout from '@/Layouts/AdminLayout';
import { PlusCircle, Edit, Trash2, ExternalLink } from 'lucide-react';

export default function LandingPageIndex({ landingPages }) {
    const handleDelete = (id) => {
        if (confirm('Are you sure you want to delete this landing page?')) {
            router.delete(`/admin/landing-pages/${id}`);
        }
    };

    return (
        <AdminLayout activePage="landing-pages" pageTitle="Landing Pages">
            <Head title="Landing Pages — Admin" />
            
            <div className="flex justify-between items-center mb-6">
                <h2 className="text-xl font-bold text-[#0f172a]">All Landing Pages</h2>
                <Link 
                    href="/admin/landing-pages/create" 
                    className="flex items-center gap-2 bg-[#0f172a] hover:bg-[#1e293b] text-white px-4 py-2 rounded-xl text-sm font-bold transition-all shadow-sm"
                >
                    <PlusCircle className="w-4 h-4" />
                    Create Landing Page
                </Link>
            </div>

            <div className="bg-white/90 backdrop-blur-xl border border-[#e2e8f0] rounded-2xl shadow-sm overflow-hidden">
                <div className="overflow-x-auto">
                    <table className="w-full text-left border-collapse">
                        <thead>
                            <tr className="bg-[#f8fafc] border-b border-[#e2e8f0] text-[#475569] text-[11px] uppercase tracking-wider">
                                <th className="px-6 py-4 font-bold">ID</th>
                                <th className="px-6 py-4 font-bold">Title</th>
                                <th className="px-6 py-4 font-bold">Status</th>
                                <th className="px-6 py-4 font-bold">Link</th>
                                <th className="px-6 py-4 font-bold text-right">Actions</th>
                            </tr>
                        </thead>
                        <tbody className="divide-y divide-[#e2e8f0]">
                            {landingPages.data.map(page => (
                                <tr key={page.id} className="hover:bg-[#f8fafc] transition-colors">
                                    <td className="px-6 py-4 text-sm font-mono text-[#64748b]">#{page.id}</td>
                                    <td className="px-6 py-4">
                                        <div className="font-bold text-[#0f172a] text-sm">{page.title}</div>
                                        <div className="text-xs text-[#64748b]">/{page.slug}</div>
                                    </td>
                                    <td className="px-6 py-4">
                                        <span className={`inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium ${page.is_active ? 'bg-emerald-100 text-emerald-800' : 'bg-rose-100 text-rose-800'}`}>
                                            {page.is_active ? 'Active' : 'Inactive'}
                                        </span>
                                    </td>
                                    <td className="px-6 py-4">
                                        {page.slug && (
                                            <a href={`/landing-page/${page.slug}`} target="_blank" rel="noreferrer" className="text-blue-600 hover:text-blue-800 flex items-center gap-1 text-xs font-medium">
                                                <ExternalLink className="w-3 h-3" />
                                                View Live
                                            </a>
                                        )}
                                    </td>
                                    <td className="px-6 py-4 text-right">
                                        <div className="flex items-center justify-end gap-2">
                                            <Link href={`/admin/landing-pages/${page.id}/edit`} className="p-2 text-[#64748b] hover:text-[#0f172a] hover:bg-[#e2e8f0] rounded-lg transition-colors">
                                                <Edit className="w-4 h-4" />
                                            </Link>
                                            <button onClick={() => handleDelete(page.id)} className="p-2 text-rose-500 hover:text-rose-700 hover:bg-rose-50 rounded-lg transition-colors">
                                                <Trash2 className="w-4 h-4" />
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            ))}
                            {landingPages.data.length === 0 && (
                                <tr>
                                    <td colSpan={5} className="px-6 py-8 text-center text-[#64748b] text-sm">
                                        No landing pages found.
                                    </td>
                                </tr>
                            )}
                        </tbody>
                    </table>
                </div>
            </div>
            
            {/* Pagination */}
            {landingPages.links && landingPages.links.length > 3 && (
                <div className="mt-4 flex gap-1 justify-end">
                    {landingPages.links.map((link, k) => (
                        <Link 
                            key={k} 
                            href={link.url || '#'} 
                            className={`px-3 py-1 text-sm rounded border ${link.active ? 'bg-[#0f172a] text-white border-[#0f172a]' : 'bg-white text-[#475569] border-[#e2e8f0] hover:bg-[#f8fafc]'} ${!link.url ? 'opacity-50 cursor-not-allowed' : ''}`}
                            dangerouslySetInnerHTML={{ __html: link.label }}
                        />
                    ))}
                </div>
            )}
        </AdminLayout>
    );
}
