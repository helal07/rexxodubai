import React, { useState } from 'react';
import { useForm, router } from '@inertiajs/react';
import { Plus, Edit, Trash2, Save, X, FileText } from 'lucide-react';

interface Page {
    id: number;
    title: string;
    slug: string;
    content: string | null;
    meta_title: string | null;
    meta_description: string | null;
}

interface PageBuilderProps {
    pages: Page[];
}

export default function PageBuilder({ pages }: PageBuilderProps) {
    const [isEditing, setIsEditing] = useState(false);
    const [editId, setEditId] = useState<number | null>(null);

    const { data, setData, post, put, delete: destroy, processing, reset, errors } = useForm({
        title: '',
        slug: '',
        content: '',
        meta_title: '',
        meta_description: '',
    });

    const handleEdit = (page: Page) => {
        setIsEditing(true);
        setEditId(page.id);
        setData({
            title: page.title,
            slug: page.slug,
            content: page.content || '',
            meta_title: page.meta_title || '',
            meta_description: page.meta_description || '',
        });
        window.scrollTo({ top: 0, behavior: 'smooth' });
    };

    const handleCancelEdit = () => {
        setIsEditing(false);
        setEditId(null);
        reset();
    };

    const handleSubmit = (e: React.FormEvent) => {
        e.preventDefault();
        if (isEditing && editId) {
            put(`/admin/pages/${editId}`, {
                preserveScroll: true,
                onSuccess: () => {
                    handleCancelEdit();
                }
            });
        } else {
            post('/admin/pages', {
                preserveScroll: true,
                onSuccess: () => {
                    reset();
                }
            });
        }
    };

    const handleDelete = (id: number) => {
        if (confirm('Are you sure you want to delete this custom page?')) {
            destroy(`/admin/pages/${id}`, {
                preserveScroll: true,
            });
        }
    };

    return (
        <div>
            <div className="mb-6 space-y-1">
                <h2 className="text-xl font-bold font-serif uppercase tracking-wide text-[#0f172a]">Custom Pages</h2>
                <p className="text-[13px] text-[#64748b]">Manage dynamic page contents like About Us, Privacy Policy, Terms, etc.</p>
            </div>

            <div className="grid grid-cols-1 lg:grid-cols-12 gap-6 items-start">
                {/* Form Section */}
                <div className="lg:col-span-5 bg-white/90 backdrop-blur-xl border border-[#38bdf8]/30 p-6 rounded-2xl shadow-sm sticky top-24">
                    <h3 className="text-[15px] font-serif font-bold text-[#0f172a] uppercase border-b border-[#e2e8f0] pb-3 mb-5 flex items-center gap-2">
                        {isEditing ? <Edit className="w-4 h-4 text-[#0284c7]" /> : <Plus className="w-4 h-4 text-[#0284c7]" />}
                        {isEditing ? 'Edit Page' : 'Create New Page'}
                    </h3>

                    <div className="space-y-4">
                        <div>
                            <label className="text-[11px] uppercase font-bold text-[#475569] tracking-wider block mb-1.5">
                                Page Title <span className="text-rose-500">*</span>
                            </label>
                            <input
                                type="text"
                                value={data.title}
                                onChange={e => {
                                    setData('title', e.target.value);
                                    if (!isEditing) {
                                        setData('slug', e.target.value.toLowerCase().replace(/[^a-z0-9]+/g, '-').replace(/(^-|-$)+/g, ''));
                                    }
                                }}
                                placeholder="e.g. Privacy Policy"
                                required
                                className="w-full border border-[#cbd5e1] px-4 py-2.5 rounded-xl text-[13px] outline-none focus:border-[#0284c7] bg-white shadow-2xs"
                            />
                            {errors.title && <div className="text-rose-500 text-xs mt-1">{errors.title}</div>}
                        </div>

                        <div>
                            <label className="text-[11px] uppercase font-bold text-[#475569] tracking-wider block mb-1.5">
                                URL Slug <span className="text-rose-500">*</span>
                            </label>
                            <input
                                type="text"
                                value={data.slug}
                                onChange={e => setData('slug', e.target.value.toLowerCase().replace(/[^a-z0-9]+/g, '-').replace(/(^-|-$)+/g, ''))}
                                placeholder="e.g. privacy-policy"
                                required
                                className="w-full border border-[#cbd5e1] px-4 py-2.5 rounded-xl text-[13px] outline-none focus:border-[#0284c7] bg-white shadow-2xs"
                            />
                            {errors.slug && <div className="text-rose-500 text-xs mt-1">{errors.slug}</div>}
                        </div>

                        <div>
                            <label className="text-[11px] uppercase font-bold text-[#475569] tracking-wider block mb-1.5">
                                Page Content
                            </label>
                            <textarea
                                value={data.content}
                                onChange={e => setData('content', e.target.value)}
                                placeholder="HTML or text content..."
                                rows={10}
                                className="w-full border border-[#cbd5e1] px-4 py-2.5 rounded-xl text-[13px] outline-none focus:border-[#0284c7] bg-white shadow-2xs"
                            ></textarea>
                            <p className="text-[11px] text-gray-500 mt-1">You can use basic HTML tags for formatting.</p>
                            {errors.content && <div className="text-rose-500 text-xs mt-1">{errors.content}</div>}
                        </div>

                        <div className="pt-4 flex items-center gap-3">
                            <button
                                type="button"
                                onClick={(e: any) => handleSubmit(e)}
                                disabled={processing}
                                className="flex-1 py-2.5 bg-[#0284c7] hover:bg-[#0369a1] text-white rounded-xl text-[12px] font-bold uppercase tracking-wider shadow-md shadow-[#0284c7]/20 flex items-center justify-center gap-2"
                            >
                                <Save className="w-4 h-4" />
                                {isEditing ? 'Update Page' : 'Create Page'}
                            </button>
                            {isEditing && (
                                <button
                                    type="button"
                                    onClick={handleCancelEdit}
                                    className="px-4 py-2.5 bg-gray-100 hover:bg-gray-200 text-gray-700 rounded-xl text-[12px] font-bold uppercase tracking-wider flex items-center gap-2"
                                >
                                    <X className="w-4 h-4" /> Cancel
                                </button>
                            )}
                        </div>
                    </div>
                </div>

                {/* Table Section */}
                <div className="lg:col-span-7 bg-white/90 backdrop-blur-xl border border-[#38bdf8]/30 rounded-2xl shadow-sm overflow-hidden">
                    <div className="p-5 border-b border-[#e2e8f0] bg-[#f8fafc] flex justify-between items-center">
                        <h3 className="text-[15px] font-serif font-bold text-[#0f172a] uppercase flex items-center gap-2">
                            <FileText className="w-5 h-5 text-[#0284c7]" /> Existing Pages
                        </h3>
                    </div>
                    <div className="p-0 overflow-x-auto">
                        <table className="w-full text-left border-collapse">
                            <thead>
                                <tr className="bg-[#f1f5f9] border-b border-[#e2e8f0]">
                                    <th className="p-3 text-[10px] font-bold text-[#64748b] uppercase tracking-wider pl-5">Title</th>
                                    <th className="p-3 text-[10px] font-bold text-[#64748b] uppercase tracking-wider">URL Path</th>
                                    <th className="p-3 text-[10px] font-bold text-[#64748b] uppercase tracking-wider text-right pr-5">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                {pages.length === 0 ? (
                                    <tr>
                                        <td colSpan={3} className="p-8 text-center text-[13px] text-[#64748b]">
                                            No custom pages found. Create one to get started.
                                        </td>
                                    </tr>
                                ) : (
                                    pages.map((page) => (
                                        <tr key={page.id} className="border-b border-[#e2e8f0] hover:bg-[#f8fafc] transition-colors group">
                                            <td className="p-3 pl-5">
                                                <span className="font-bold text-[#0f172a] text-[13px]">{page.title}</span>
                                            </td>
                                            <td className="p-3">
                                                <span className="text-[12px] text-[#0284c7] font-mono bg-[#e0f2fe] px-2 py-0.5 rounded-md">/pages/{page.slug}</span>
                                            </td>
                                            <td className="p-3 pr-5 text-right space-x-2">
                                                <button
                                                    onClick={() => handleEdit(page)}
                                                    className="p-1.5 text-blue-600 bg-blue-50 hover:bg-blue-100 rounded-lg transition-colors"
                                                    title="Edit"
                                                >
                                                    <Edit className="w-4 h-4" />
                                                </button>
                                                <button
                                                    onClick={() => handleDelete(page.id)}
                                                    className="p-1.5 text-rose-600 bg-rose-50 hover:bg-rose-100 rounded-lg transition-colors"
                                                    title="Delete"
                                                >
                                                    <Trash2 className="w-4 h-4" />
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
        </div>
    );
}
