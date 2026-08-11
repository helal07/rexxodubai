import React, { useState } from 'react';
import { Head, router, useForm } from '@inertiajs/react';
import AdminLayout from '@/Layouts/AdminLayout';
import { 
    Tags, PlusCircle, Edit3, Trash2, X
} from 'lucide-react';

export default function Index({ variants }: any) {
    
    const [isEditing, setIsEditing] = useState<number | null>(null);
    
    const { data, setData, post, put, delete: destroy, processing, reset, errors } = useForm({
        name: ''
    });

    const handleDelete = (id: number) => {
        if (confirm('Are you sure you want to delete this variant? It may be removed from products using it.')) {
            destroy(`/admin/variants/${id}`);
        }
    };

    const handleEdit = (variant: any) => {
        setIsEditing(variant.id);
        setData('name', variant.name);
    };

    const handleCancelEdit = () => {
        setIsEditing(null);
        reset();
    };

    const submit = (e: React.FormEvent) => {
        e.preventDefault();
        if (isEditing) {
            put(`/admin/variants/${isEditing}`, {
                onSuccess: () => {
                    setIsEditing(null);
                    reset();
                }
            });
        } else {
            post('/admin/variants', {
                onSuccess: () => reset()
            });
        }
    };

    return (
        <AdminLayout>
            <Head title="Product Variants" />
            
            <div className="max-w-4xl mx-auto space-y-6">
                
                {/* Header */}
                <div className="flex items-center justify-between">
                    <div>
                        <h1 className="text-2xl font-bold tracking-tight text-[#0f172a] flex items-center gap-2">
                            <Tags className="w-6 h-6 text-indigo-600" />
                            Product Variants Dictionary
                        </h1>
                        <p className="text-[13px] text-slate-500 mt-1">
                            Create global variant names (e.g. 50ml, Large, Red) to use across products.
                        </p>
                    </div>
                </div>

                <div className="grid grid-cols-1 md:grid-cols-3 gap-6">
                    {/* Form Section */}
                    <div className="md:col-span-1">
                        <div className="bg-white rounded-xl shadow-sm border border-slate-200 p-5 sticky top-24">
                            <h2 className="text-[14px] font-bold text-slate-800 mb-4 uppercase tracking-wider border-b border-slate-100 pb-2">
                                {isEditing ? 'Edit Variant' : 'Add New Variant'}
                            </h2>
                            <form onSubmit={submit} className="space-y-4">
                                <div>
                                    <label className="block text-[12px] font-semibold text-slate-600 mb-1.5">
                                        Variant Name
                                    </label>
                                    <input
                                        type="text"
                                        value={data.name}
                                        onChange={e => setData('name', e.target.value)}
                                        placeholder="e.g. 100ml or Red"
                                        required
                                        className="w-full px-3 py-2 border border-slate-300 rounded-lg text-[13px] focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500"
                                    />
                                    {errors.name && <p className="text-red-500 text-[11px] mt-1">{errors.name}</p>}
                                </div>
                                
                                <div className="flex gap-2">
                                    <button
                                        type="submit"
                                        disabled={processing}
                                        className="flex-1 bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2 rounded-lg text-[13px] font-semibold transition-colors flex items-center justify-center gap-1.5 disabled:opacity-50"
                                    >
                                        {isEditing ? <Edit3 className="w-4 h-4" /> : <PlusCircle className="w-4 h-4" />}
                                        {isEditing ? 'Update' : 'Add'}
                                    </button>
                                    {isEditing && (
                                        <button
                                            type="button"
                                            onClick={handleCancelEdit}
                                            className="px-3 py-2 bg-slate-100 hover:bg-slate-200 text-slate-600 rounded-lg transition-colors"
                                        >
                                            <X className="w-4 h-4" />
                                        </button>
                                    )}
                                </div>
                            </form>
                        </div>
                    </div>

                    {/* Table Section */}
                    <div className="md:col-span-2">
                        <div className="bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden">
                            <table className="w-full text-left border-collapse">
                                <thead>
                                    <tr className="border-b border-slate-200 bg-slate-50/50">
                                        <th className="px-5 py-4 text-[12px] font-semibold text-slate-500 uppercase tracking-wider">Variant Name</th>
                                        <th className="px-5 py-4 text-[12px] font-semibold text-slate-500 uppercase tracking-wider w-[120px]">Actions</th>
                                    </tr>
                                </thead>
                                <tbody className="divide-y divide-slate-200">
                                    {variants.length === 0 ? (
                                        <tr>
                                            <td colSpan={2} className="px-5 py-8 text-center text-slate-500 text-[13px]">
                                                No variants found.
                                            </td>
                                        </tr>
                                    ) : (
                                        variants.map((variant: any) => (
                                            <tr key={variant.id} className={`transition-colors ${isEditing === variant.id ? 'bg-indigo-50/30' : 'hover:bg-slate-50/50'}`}>
                                                <td className="px-5 py-3">
                                                    <div className="font-semibold text-[14px] text-slate-900">{variant.name}</div>
                                                </td>
                                                <td className="px-5 py-3 space-x-2">
                                                    <button
                                                        onClick={() => handleEdit(variant)}
                                                        className="inline-flex items-center justify-center w-8 h-8 rounded-lg bg-indigo-50 text-indigo-600 hover:bg-indigo-100 transition-colors"
                                                        title="Edit"
                                                    >
                                                        <Edit3 className="w-4 h-4" />
                                                    </button>
                                                    <button
                                                        onClick={() => handleDelete(variant.id)}
                                                        className="inline-flex items-center justify-center w-8 h-8 rounded-lg bg-red-50 text-red-600 hover:bg-red-100 transition-colors"
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
        </AdminLayout>
    );
}
