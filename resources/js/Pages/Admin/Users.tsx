import React, { useState, useRef } from 'react';
import { Head, useForm, router } from '@inertiajs/react';
import AdminLayout from '@/Layouts/AdminLayout';
import { Users as UsersIcon, UserPlus, Trash2, Edit, X, Camera, Mail } from 'lucide-react';

interface Role {
    id: number;
    name: string;
}

interface UserItem {
    id: number;
    name: string;
    email: string;
    role: string;
    avatar_url?: string;
    created_at?: string;
}

interface UsersProps {
    users: UserItem[];
    roles: Role[];
}

export default function UsersPage({ users = [], roles = [] }: UsersProps) {
    const [isEditing, setIsEditing] = useState(false);
    const [editingUserId, setEditingUserId] = useState<number | null>(null);
    const [previewAvatar, setPreviewAvatar] = useState<string | null>(null);
    
    const fileInputRef = useRef<HTMLInputElement>(null);

    const { data, setData, post, processing, reset, errors, clearErrors } = useForm({
        name: '',
        email: '',
        password: '',
        role: '',
        avatar_file: null as File | null,
        _method: 'post',
    });

    const resetForm = () => {
        reset();
        clearErrors();
        setIsEditing(false);
        setEditingUserId(null);
        setPreviewAvatar(null);
        if (fileInputRef.current) {
            fileInputRef.current.value = '';
        }
    };

    const handleCreate = (e: React.FormEvent) => {
        e.preventDefault();
        setData('_method', 'post');
        post('/admin/users', {
            onSuccess: () => resetForm(),
        });
    };

    const handleUpdate = (e: React.FormEvent) => {
        e.preventDefault();
        // For file uploads on PUT requests in Laravel, we use POST and spoof the method
        post(`/admin/users/${editingUserId}`, {
            onSuccess: () => resetForm(),
        });
    };

    const editUser = (user: UserItem) => {
        setIsEditing(true);
        setEditingUserId(user.id);
        setPreviewAvatar(user.avatar_url || null);
        setData({
            name: user.name,
            email: user.email,
            password: '',
            role: user.role || '',
            avatar_file: null,
            _method: 'put',
        });
    };

    const handleDelete = (id: number, name: string) => {
        if (confirm(`Are you sure you want to remove user "${name}"?`)) {
            router.delete(`/admin/users/${id}`);
        }
    };

    const handleFileChange = (e: React.ChangeEvent<HTMLInputElement>) => {
        const file = e.target.files?.[0];
        if (file) {
            setData('avatar_file', file);
            setPreviewAvatar(URL.createObjectURL(file));
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
                {/* LEFT: CREATE/EDIT USER FORM (4 COLS) */}
                <div className="lg:col-span-4 bg-white/90 backdrop-blur-xl border border-[#38bdf8]/30 p-7 rounded-2xl space-y-6 shadow-sm h-fit">
                    <div className="flex items-center justify-between border-b border-[#e2e8f0] pb-4">
                        <div className="flex items-center gap-3">
                            <div className="p-2.5 bg-[#e0f2fe] text-[#0284c7] rounded-xl">
                                {isEditing ? <Edit className="w-5 h-5" /> : <UserPlus className="w-5 h-5" />}
                            </div>
                            <div>
                                <h3 className="text-base font-serif font-bold text-[#0f172a] uppercase tracking-wide">
                                    {isEditing ? 'Edit Staff Member' : 'Add Staff Member'}
                                </h3>
                                <p className="text-[11px] text-[#64748b]">
                                    {isEditing ? 'Update user details' : 'Create admin account'}
                                </p>
                            </div>
                        </div>
                        {isEditing && (
                            <button type="button" onClick={resetForm} className="p-2 text-rose-500 hover:bg-rose-50 rounded-full transition">
                                <X className="w-4 h-4" />
                            </button>
                        )}
                    </div>

                    <form onSubmit={isEditing ? handleUpdate : handleCreate} className="space-y-4">
                        {/* Avatar Upload */}
                        <div className="flex flex-col items-center justify-center space-y-2 mb-6">
                            <div className="relative group cursor-pointer" onClick={() => fileInputRef.current?.click()}>
                                <div className="w-24 h-24 rounded-full overflow-hidden border-2 border-[#e2e8f0] bg-gray-50 flex items-center justify-center group-hover:border-[#0284c7] transition-all">
                                    {previewAvatar ? (
                                        <img src={previewAvatar} alt="Preview" className="w-full h-full object-cover" />
                                    ) : (
                                        <Camera className="w-8 h-8 text-gray-300 group-hover:text-[#0284c7] transition-colors" />
                                    )}
                                </div>
                                <div className="absolute inset-0 bg-black/40 rounded-full flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity">
                                    <span className="text-white text-[10px] font-bold uppercase">Change</span>
                                </div>
                            </div>
                            <input 
                                type="file" 
                                className="hidden" 
                                ref={fileInputRef}
                                onChange={handleFileChange}
                                accept="image/*"
                            />
                            {errors.avatar_file && <p className="text-rose-500 text-[11px]">{errors.avatar_file}</p>}
                        </div>

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
                            {errors.name && <p className="text-rose-500 text-[11px]">{errors.name}</p>}
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
                            {errors.email && <p className="text-rose-500 text-[11px]">{errors.email}</p>}
                        </div>

                        <div>
                            <label className="text-[11px] uppercase font-bold text-[#475569] tracking-wider block mb-1.5">
                                Assign Role *
                            </label>
                            <select
                                value={data.role}
                                onChange={e => setData('role', e.target.value)}
                                required
                                className="w-full border border-[#cbd5e1] px-4 py-2.5 rounded-xl text-[13px] font-medium outline-none focus:border-[#0284c7] bg-white shadow-2xs bg-transparent"
                            >
                                <option value="" disabled>Select a role</option>
                                {roles.map(role => (
                                    <option key={role.id} value={role.name}>{role.name}</option>
                                ))}
                            </select>
                            {errors.role && <p className="text-rose-500 text-[11px]">{errors.role}</p>}
                        </div>

                        <div>
                            <label className="text-[11px] uppercase font-bold text-[#475569] tracking-wider block mb-1.5">
                                {isEditing ? 'New Password (Optional)' : 'Password *'}
                            </label>
                            <input
                                type="password"
                                value={data.password}
                                onChange={e => setData('password', e.target.value)}
                                required={!isEditing}
                                placeholder="••••••••"
                                className="w-full border border-[#cbd5e1] px-4 py-2.5 rounded-xl text-[13px] font-mono outline-none focus:border-[#0284c7] bg-white shadow-2xs"
                            />
                            {errors.password && <p className="text-rose-500 text-[11px]">{errors.password}</p>}
                        </div>

                        <button
                            type="submit"
                            disabled={processing}
                            className={`w-full text-white py-3 rounded-xl text-[12px] font-bold uppercase tracking-wider transition-all shadow-md flex items-center justify-center gap-2 ${
                                isEditing 
                                    ? 'bg-emerald-600 hover:bg-emerald-700 shadow-emerald-600/20' 
                                    : 'bg-[#0284c7] hover:bg-[#0369a1] shadow-[#0284c7]/20'
                            }`}
                        >
                            {isEditing ? <Edit className="w-4 h-4" /> : <UserPlus className="w-4 h-4" />} 
                            {isEditing ? 'Update Staff User' : 'Save Staff User'}
                        </button>
                    </form>
                </div>

                {/* RIGHT: USERS LIST TABLE (8 COLS) */}
                <div className="lg:col-span-8 bg-white/90 backdrop-blur-xl border border-[#38bdf8]/30 p-7 rounded-2xl space-y-5 shadow-sm h-fit">
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
                                    <th className="p-3.5 rounded-tl-xl w-12">Avatar</th>
                                    <th className="p-3.5">User Details</th>
                                    <th className="p-3.5">Role</th>
                                    <th className="p-3.5 text-right rounded-tr-xl">Action</th>
                                </tr>
                            </thead>
                            <tbody className="divide-y divide-[#e2e8f0]">
                                {users.map(u => (
                                    <tr key={u.id} className="hover:bg-[#f8fafc] transition-colors">
                                        <td className="p-3.5">
                                            {u.avatar_url ? (
                                                <img src={u.avatar_url} alt={u.name} className="w-9 h-9 rounded-full object-cover border border-[#e2e8f0]" />
                                            ) : (
                                                <div className="w-9 h-9 rounded-full bg-slate-200 flex items-center justify-center">
                                                    <UsersIcon className="w-4 h-4 text-slate-500" />
                                                </div>
                                            )}
                                        </td>
                                        <td className="p-3.5">
                                            <div className="font-bold text-[#0f172a]">{u.name}</div>
                                            <div className="text-[#0284c7] font-mono text-xs flex items-center gap-1">
                                                <Mail className="w-3 h-3" /> {u.email}
                                            </div>
                                        </td>
                                        <td className="p-3.5">
                                            <span className="px-2 py-1 bg-slate-100 text-slate-700 rounded-md text-xs font-bold uppercase tracking-wider">
                                                {u.role || 'N/A'}
                                            </span>
                                        </td>
                                        <td className="p-3.5 text-right space-x-1">
                                            <button
                                                type="button"
                                                onClick={() => editUser(u)}
                                                className="p-1.5 text-emerald-600 hover:bg-emerald-50 rounded-lg transition-colors"
                                                title="Edit User"
                                            >
                                                <Edit className="w-4 h-4" />
                                            </button>
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
