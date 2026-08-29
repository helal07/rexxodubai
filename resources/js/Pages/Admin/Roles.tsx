import React, { useState } from 'react';
import { Head, useForm, router } from '@inertiajs/react';
import AdminLayout from '@/Layouts/AdminLayout';
import {
    ShieldCheck,
    Plus,
    Check,
    Lock,
    Users,
    Key,
    Trash2
} from 'lucide-react';

interface Permission {
    id: number;
    name: string;
}

interface Role {
    id: number;
    name: string;
    permissions?: Permission[];
}

interface RolesProps {
    roles: Role[];
    permissions: Permission[];
}

export default function RolesIndex({ roles = [], permissions = [] }: RolesProps) {
    const { data, setData, post, processing, reset, errors } = useForm({
        name: '',
    });

    const [selectedRole, setSelectedRole] = useState<Role | null>(roles[0] || null);

    const handleCreateRole = (e: React.FormEvent) => {
        e.preventDefault();
        post('/admin/roles', {
            onSuccess: () => reset(),
        });
    };

    const togglePermission = (roleId: number, permissionName: string) => {
        router.post(`/admin/roles/${roleId}/sync-permissions`, {
            permission: permissionName,
        });
    };

    const handleDeleteRole = (roleId: number) => {
        if (confirm('Are you sure you want to delete this role? This action cannot be undone.')) {
            router.delete(`/admin/api/roles/${roleId}`, {
                onSuccess: () => {
                    if (selectedRole?.id === roleId) {
                        setSelectedRole(roles.find(r => r.id !== roleId) || null);
                    }
                }
            });
        }
    };

    return (
        <AdminLayout
            activePage="roles"
            pageTitle="Roles & Module Permissions (RBAC)"
            pageSubtitle="Define role capabilities and assign module permissions across your administration team."
        >
            <Head title="Roles & Permissions — Admin" />

            <div className="grid grid-cols-1 lg:grid-cols-12 gap-8">
                {/* LEFT: CREATE ROLE & ROLE LIST (4 COLS) */}
                <div className="lg:col-span-4 space-y-6">
                    {/* CREATE ROLE FORM */}
                    <div className="bg-white/90 backdrop-blur-xl border border-[#38bdf8]/30 p-6 rounded-2xl space-y-4 shadow-sm">
                        <div className="flex items-center gap-3 border-b border-[#e2e8f0] pb-3">
                            <div className="p-2 bg-[#e0f2fe] text-[#0284c7] rounded-xl">
                                <Plus className="w-4 h-4" />
                            </div>
                            <h3 className="text-sm font-serif font-bold text-[#0f172a] uppercase">Create New Role</h3>
                        </div>

                        <form onSubmit={handleCreateRole} className="space-y-3">
                            <div>
                                <label className="text-[11px] uppercase font-bold text-[#475569] tracking-wider block mb-1">
                                    Role Name *
                                </label>
                                <input
                                    type="text"
                                    value={data.name}
                                    onChange={e => setData('name', e.target.value)}
                                    required
                                    placeholder="e.g. Inventory Manager, Accountant"
                                    className="w-full border border-[#cbd5e1] px-3.5 py-2.5 rounded-xl text-[13px] font-medium outline-none focus:border-[#0284c7] bg-white shadow-2xs"
                                />
                                {errors.name && <p className="text-rose-600 text-xs mt-1">{errors.name}</p>}
                            </div>

                            <button
                                type="submit"
                                disabled={processing}
                                className="w-full bg-[#0284c7] hover:bg-[#0369a1] text-white py-2.5 rounded-xl text-[12px] font-bold uppercase tracking-wider shadow-xs flex items-center justify-center gap-2"
                            >
                                <Plus className="w-4 h-4" /> Add Role
                            </button>
                        </form>
                    </div>

                    {/* ROLES SELECTOR */}
                    <div className="bg-white/90 backdrop-blur-xl border border-[#38bdf8]/30 p-6 rounded-2xl space-y-3 shadow-sm">
                        <span className="text-[11px] font-bold uppercase tracking-widest text-[#64748b] block">
                            Select Role to Manage
                        </span>
                        <div className="space-y-2">
                            {roles.map(role => {
                                const isSelected = selectedRole?.id === role.id;
                                return (
                                    <button
                                        key={role.id}
                                        type="button"
                                        onClick={() => setSelectedRole(role)}
                                        className={`w-full flex items-center justify-between p-3 rounded-xl border text-left transition-all ${
                                            isSelected
                                                ? 'bg-[#0284c7] text-white border-[#0284c7] shadow-md shadow-[#0284c7]/20 font-bold'
                                                : 'bg-white border-[#e2e8f0] text-[#475569] hover:bg-[#f8fafc]'
                                        }`}
                                    >
                                        <div className="flex items-center gap-2.5">
                                            <ShieldCheck className="w-4 h-4" />
                                            <span className="text-xs">{role.name}</span>
                                        </div>
                                        <span className="text-[10px] opacity-80 uppercase tracking-wider">
                                            {role.permissions?.length || 0} Perms
                                        </span>
                                    </button>
                                );
                            })}
                        </div>
                    </div>
                </div>

                {/* RIGHT: PERMISSIONS MATRIX (8 COLS) */}
                <div className="lg:col-span-8 bg-white/90 backdrop-blur-xl border border-[#38bdf8]/30 p-7 rounded-2xl space-y-6 shadow-sm">
                    {selectedRole ? (
                        <>
                            <div className="flex items-center justify-between border-b border-[#e2e8f0] pb-4">
                                <div>
                                    <div className="flex items-center gap-2">
                                        <span className="text-[11px] font-bold uppercase tracking-widest text-[#0284c7]">
                                            PERMISSIONS MATRIX
                                        </span>
                                    </div>
                                    <h2 className="text-lg font-serif font-bold text-[#0f172a] uppercase">
                                        Configuring: {selectedRole.name}
                                    </h2>
                                </div>
                                {selectedRole.name === 'Super Admin' ? (
                                    <span className="px-3 py-1 bg-amber-100 text-amber-800 rounded-full text-xs font-bold uppercase flex items-center gap-1">
                                        <Lock className="w-3.5 h-3.5" /> Full Bypass Active
                                    </span>
                                ) : (
                                    <button
                                        onClick={() => handleDeleteRole(selectedRole.id)}
                                        className="px-3 py-1.5 bg-rose-100 text-rose-700 hover:bg-rose-600 hover:text-white rounded-full text-[10px] font-bold uppercase tracking-wider flex items-center gap-1.5 transition-colors cursor-pointer"
                                    >
                                        <Trash2 className="w-3.5 h-3.5" /> Delete Role
                                    </button>
                                )}
                            </div>

                            <div className="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                {permissions.map(perm => {
                                    const hasPerm = selectedRole.permissions?.some(p => p.name === perm.name);
                                    return (
                                        <label
                                            key={perm.id}
                                            className={`p-4 rounded-xl border flex items-center justify-between cursor-pointer transition-all ${
                                                hasPerm
                                                    ? 'bg-emerald-50/70 border-emerald-300 text-emerald-900'
                                                    : 'bg-[#f8fafc] border-[#e2e8f0] text-[#64748b] hover:bg-white'
                                            }`}
                                        >
                                            <div className="flex items-center gap-3">
                                                <Key className={`w-4 h-4 ${hasPerm ? 'text-emerald-600' : 'text-slate-400'}`} />
                                                <span className="text-xs font-bold capitalize">{perm.name.replace(/_/g, ' ')}</span>
                                            </div>
                                            <input
                                                type="checkbox"
                                                checked={hasPerm || false}
                                                onChange={() => togglePermission(selectedRole.id, perm.name)}
                                                className="w-4 h-4 rounded text-[#0284c7] focus:ring-[#0284c7] cursor-pointer"
                                            />
                                        </label>
                                    );
                                })}
                            </div>
                        </>
                    ) : (
                        <div className="p-12 text-center text-[#94a3b8]">
                            Select a role on the left to configure permissions.
                        </div>
                    )}
                </div>
            </div>
        </AdminLayout>
    );
}
