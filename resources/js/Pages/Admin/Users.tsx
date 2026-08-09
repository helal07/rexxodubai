import React from 'react';
import { Head, useForm, router } from '@inertiajs/react';
import AdminLayout from '@/Layouts/AdminLayout';
import { Users as UsersIcon, UserPlus, Trash2, ShieldCheck, Mail } from 'lucide-react';

interface UserItem {
    id: number;
    name: string;
    email: string;
    created_at?: string;
}

interface UsersProps {
    users: UserItem[];
}

export default function UsersPage({ users = [] }: UsersProps) {
    const { data, setData, post, processing, reset, errors } = useForm({
        name: '',
        email: '',
        password: '',
    });

    const handleCreate = (e: React.FormEvent) => {
        e.preventDefault();
        post('/admin/users', {
            onSuccess: () => reset(),
        });
    };

    const handleDelete = (id: number, name: string) => {
        if (confirm(`Are you sure you want to remove user "${name}"?`)) {
            router.delete(`/admin/users/${id}`);
        }
    };

    return (
        <AdminLayout
            activePage="users"
            pageTitle="Admin Team & Staff Management"
            pageSubtitle="Manage administrative team members, access privileges, and staff accounts."
        >
            <Head title="Admin Users — Admin" />

            <div className="grid grid-cols-1 lg:grid-cols-12 gap-8">
                {/* LEFT: CREATE USER FORM (4 COLS) */}
                <div className="lg:col-span-4 bg-white/90 backdrop-blur-xl border border-[#38bdf8]/30 p-7 rounded-2xl space-y-6 shadow-sm">
                    <div className="flex items-center gap-3 border-b border-[#e2e8f0] pb-4">
                        <div className="p-2.5 bg-[#e0f2fe] text-[#0284c7] rounded-xl">
                            <UserPlus className="w-5 h-5" />
                        </div>
                        <div>
                            <h3 className="text-base font-serif font-bold text-[#0f172a] uppercase tracking-wide">
                                Add Staff Member
                            </h3>
                            <p className="text-[11px] text-[#64748b]">Create admin account</p>
                        </div>
                    </div>

                    <form onSubmit={handleCreate} className="space-y-4">
                        <div>
                            <label className="text-[11px] uppercase font-bold text-[#475569] tracking-wider block mb-1.5">
                                Full Name *
                            </label>
                            <input
                                type="text"
                                value={data.name}
                                onChange={e => setData('name', e.target.value)}
                                required
                                placeholder="e.g. Md Al Helal"
                                className="w-full border border-[#cbd5e1] px-4 py-2.5 rounded-xl text-[13px] font-medium outline-none focus:border-[#0284c7] bg-white shadow-2xs"
                            />
                        </div>

                        <div>
                            <label className="text-[11px] uppercase font-bold text-[#475569] tracking-wider block mb-1.5">
                                Email Address *
                            </label>
                            <input
                                type="email"
                                value={data.email}
                                onChange={e => setData('email', e.target.value)}
                                required
                                placeholder="staff@store.com"
                                className="w-full border border-[#cbd5e1] px-4 py-2.5 rounded-xl text-[13px] font-medium outline-none focus:border-[#0284c7] bg-white shadow-2xs"
                            />
                        </div>

                        <div>
                            <label className="text-[11px] uppercase font-bold text-[#475569] tracking-wider block mb-1.5">
                                Password *
                            </label>
                            <input
                                type="password"
                                value={data.password}
                                onChange={e => setData('password', e.target.value)}
                                required
                                placeholder="••••••••"
                                className="w-full border border-[#cbd5e1] px-4 py-2.5 rounded-xl text-[13px] font-mono outline-none focus:border-[#0284c7] bg-white shadow-2xs"
                            />
                        </div>

                        <button
                            type="submit"
                            disabled={processing}
                            className="w-full bg-[#0284c7] hover:bg-[#0369a1] text-white py-3 rounded-xl text-[12px] font-bold uppercase tracking-wider transition-all shadow-md shadow-[#0284c7]/20 flex items-center justify-center gap-2"
                        >
                            <UserPlus className="w-4 h-4" /> Save Staff User
                        </button>
                    </form>
                </div>

                {/* RIGHT: USERS LIST TABLE (8 COLS) */}
                <div className="lg:col-span-8 bg-white/90 backdrop-blur-xl border border-[#38bdf8]/30 p-7 rounded-2xl space-y-5 shadow-sm">
                    <div className="flex items-center gap-3 border-b border-[#e2e8f0] pb-4">
                        <div className="p-2.5 bg-[#e0f2fe] text-[#0284c7] rounded-xl">
                            <UsersIcon className="w-5 h-5" />
                        </div>
                        <div>
                            <h3 className="text-base font-serif font-bold text-[#0f172a] uppercase tracking-wide">
                                Administrative Team
                            </h3>
                            <p className="text-[11px] text-[#64748b]">Active admin users and login access</p>
                        </div>
                    </div>

                    <div className="overflow-x-auto">
                        <table className="w-full text-left border-collapse text-[13px]">
                            <thead>
                                <tr className="bg-[#f8fafc] border-b border-[#e2e8f0] text-[11px] font-bold uppercase tracking-wider text-[#64748b]">
                                    <th className="p-3.5 rounded-tl-xl">User Name</th>
                                    <th className="p-3.5">Email Address</th>
                                    <th className="p-3.5 text-right rounded-tr-xl">Action</th>
                                </tr>
                            </thead>
                            <tbody className="divide-y divide-[#e2e8f0]">
                                {users.map(u => (
                                    <tr key={u.id} className="hover:bg-[#f8fafc] transition-colors">
                                        <td className="p-3.5 font-bold text-[#0f172a]">{u.name}</td>
                                        <td className="p-3.5 text-[#0284c7] font-mono text-xs">{u.email}</td>
                                        <td className="p-3.5 text-right">
                                            <button
                                                type="button"
                                                onClick={() => handleDelete(u.id, u.name)}
                                                className="p-1.5 text-rose-600 hover:bg-rose-50 rounded-lg transition-colors"
                                                title="Delete User"
                                            >
                                                <Trash2 className="w-4 h-4" />
                                            </button>
                                        </td>
                                    </tr>
                                ))}
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </AdminLayout>
    );
}
