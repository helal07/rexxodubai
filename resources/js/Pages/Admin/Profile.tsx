import React, { useState } from 'react';
import { Head, useForm, router } from '@inertiajs/react';
import AdminLayout from '@/Layouts/AdminLayout';
import { 
    User, 
    Mail, 
    Phone, 
    MapPin, 
    Building, 
    Save, 
    KeyRound, 
    Lock,
    Trash2,
    UploadCloud,
    CheckCircle2
} from 'lucide-react';

interface ProfileProps {
    user: {
        name: string;
        email: string;
        phone: string | null;
        address: string | null;
        city: string | null;
        country: string | null;
        designation: string | null;
        avatar_url: string;
    };
    flash?: {
        success?: string | null;
        error?: string | null;
    };
}

export default function Profile({ user, flash }: ProfileProps) {
    const { data: profileData, setData: setProfileData, post: postProfile, processing: profileProcessing, errors: profileErrors } = useForm({
        name: user.name || '',
        email: user.email || '',
        phone: user.phone || '',
        address: user.address || '',
        city: user.city || '',
        country: user.country || 'Bangladesh',
        designation: user.designation || '',
        avatar_file: null as File | null,
    });

    const { data: passwordData, setData: setPasswordData, post: postPassword, processing: passwordProcessing, errors: passwordErrors, reset: resetPassword } = useForm({
        current_password: '',
        password: '',
        password_confirmation: '',
    });

    const [avatarPreview, setAvatarPreview] = useState<string | null>(null);

    const handleProfileSubmit = (e: React.FormEvent) => {
        e.preventDefault();
        postProfile('/admin/profile/update', {
            preserveScroll: true,
            forceFormData: true,
        });
    };

    const handlePasswordSubmit = (e: React.FormEvent) => {
        e.preventDefault();
        postPassword('/admin/profile/password', {
            preserveScroll: true,
            onSuccess: () => resetPassword(),
        });
    };

    const handleAvatarChange = (e: React.ChangeEvent<HTMLInputElement>) => {
        if (e.target.files && e.target.files[0]) {
            const file = e.target.files[0];
            setProfileData('avatar_file', file);
            setAvatarPreview(URL.createObjectURL(file));
        }
    };

    const removeAvatar = () => {
        if (confirm('Are you sure you want to remove your profile photo?')) {
            router.post('/admin/profile/remove-avatar', {}, {
                preserveScroll: true,
                onSuccess: () => {
                    setAvatarPreview(null);
                    setProfileData('avatar_file', null);
                }
            });
        }
    };

    return (
        <AdminLayout activePage="users" pageTitle="My Profile" pageSubtitle="Manage your account settings and preferences.">
            <Head title="My Profile — Admin" />

            <div className="grid grid-cols-1 xl:grid-cols-3 gap-6">
                
                {/* Left Column: Avatar & Quick Info */}
                <div className="xl:col-span-1 space-y-6">
                    <div className="bg-white/80 backdrop-blur-xl border border-[#e2e8f0] p-6 rounded-2xl shadow-sm flex flex-col items-center">
                        <div className="relative group mb-4">
                            <div className="w-32 h-32 rounded-full overflow-hidden border-4 border-white shadow-md bg-[#f8fafc] flex items-center justify-center relative">
                                {avatarPreview ? (
                                    <img src={avatarPreview} alt="Avatar Preview" className="w-full h-full object-cover" />
                                ) : user.avatar_url ? (
                                    <img src={user.avatar_url} alt={user.name} className="w-full h-full object-cover" />
                                ) : (
                                    <span className="text-4xl font-bold text-[#cbd5e1]">{user.name.charAt(0)}</span>
                                )}
                                
                                <label className="absolute inset-0 bg-black/50 text-white flex flex-col items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity cursor-pointer">
                                    <UploadCloud className="w-6 h-6 mb-1" />
                                    <span className="text-[10px] font-bold uppercase tracking-wider">Change</span>
                                    <input type="file" className="hidden" accept="image/*" onChange={handleAvatarChange} />
                                </label>
                            </div>
                            {(avatarPreview || (user.avatar_url && !user.avatar_url.includes('ui-avatars.com'))) && (
                                <button 
                                    type="button" 
                                    onClick={removeAvatar}
                                    className="absolute bottom-0 right-0 p-2 bg-rose-500 hover:bg-rose-600 text-white rounded-full shadow-lg transition-transform transform hover:scale-110"
                                    title="Remove Photo"
                                >
                                    <Trash2 className="w-4 h-4" />
                                </button>
                            )}
                        </div>
                        <h2 className="text-xl font-serif font-bold text-[#0f172a] text-center">{user.name}</h2>
                        <p className="text-[13px] text-[#64748b] font-medium text-center">{user.designation || 'Administrator'}</p>
                        
                        <div className="w-full mt-6 space-y-3 border-t border-[#e2e8f0] pt-6">
                            <div className="flex items-center gap-3 text-[13px] text-[#475569]">
                                <Mail className="w-4 h-4 text-[#94a3b8]" />
                                <span>{user.email}</span>
                            </div>
                            {user.phone && (
                                <div className="flex items-center gap-3 text-[13px] text-[#475569]">
                                    <Phone className="w-4 h-4 text-[#94a3b8]" />
                                    <span>{user.phone}</span>
                                </div>
                            )}
                            {user.city && (
                                <div className="flex items-center gap-3 text-[13px] text-[#475569]">
                                    <MapPin className="w-4 h-4 text-[#94a3b8]" />
                                    <span>{user.city}, {user.country}</span>
                                </div>
                            )}
                        </div>
                    </div>
                </div>

                {/* Right Column: Forms */}
                <div className="xl:col-span-2 space-y-6">
                    
                    {/* Profile Information Form */}
                    <form onSubmit={handleProfileSubmit} className="bg-white/80 backdrop-blur-xl border border-[#e2e8f0] rounded-2xl shadow-sm overflow-hidden">
                        <div className="px-6 py-5 border-b border-[#e2e8f0] flex items-center gap-3">
                            <div className="w-8 h-8 rounded-lg bg-[#e0f2fe] flex items-center justify-center text-[#0284c7]">
                                <User className="w-4 h-4" />
                            </div>
                            <div>
                                <h2 className="text-[15px] font-bold text-[#0f172a]">Profile Information</h2>
                                <p className="text-[12px] text-[#64748b]">Update your personal account details.</p>
                            </div>
                        </div>

                        <div className="p-6 grid grid-cols-1 md:grid-cols-2 gap-5">
                            <div>
                                <label className="block text-[12px] font-bold uppercase tracking-wider text-[#475569] mb-1.5">Full Name <span className="text-rose-500">*</span></label>
                                <input
                                    type="text"
                                    value={profileData.name}
                                    onChange={e => setProfileData('name', e.target.value)}
                                    className="w-full text-[13px] bg-white border border-[#cbd5e1] rounded-xl px-4 py-2.5 focus:ring-2 focus:ring-[#0284c7]/20 focus:border-[#0284c7] outline-none transition-all"
                                    required
                                />
                                {profileErrors.name && <p className="text-rose-500 text-[11px] mt-1">{profileErrors.name}</p>}
                            </div>

                            <div>
                                <label className="block text-[12px] font-bold uppercase tracking-wider text-[#475569] mb-1.5">Email Address <span className="text-rose-500">*</span></label>
                                <input
                                    type="email"
                                    value={profileData.email}
                                    onChange={e => setProfileData('email', e.target.value)}
                                    className="w-full text-[13px] bg-white border border-[#cbd5e1] rounded-xl px-4 py-2.5 focus:ring-2 focus:ring-[#0284c7]/20 focus:border-[#0284c7] outline-none transition-all"
                                    required
                                />
                                {profileErrors.email && <p className="text-rose-500 text-[11px] mt-1">{profileErrors.email}</p>}
                            </div>

                            <div>
                                <label className="block text-[12px] font-bold uppercase tracking-wider text-[#475569] mb-1.5">Phone Number</label>
                                <input
                                    type="text"
                                    value={profileData.phone}
                                    onChange={e => setProfileData('phone', e.target.value)}
                                    className="w-full text-[13px] bg-white border border-[#cbd5e1] rounded-xl px-4 py-2.5 focus:ring-2 focus:ring-[#0284c7]/20 focus:border-[#0284c7] outline-none transition-all"
                                />
                                {profileErrors.phone && <p className="text-rose-500 text-[11px] mt-1">{profileErrors.phone}</p>}
                            </div>
                            
                            <div>
                                <label className="block text-[12px] font-bold uppercase tracking-wider text-[#475569] mb-1.5">Designation</label>
                                <input
                                    type="text"
                                    value={profileData.designation}
                                    onChange={e => setProfileData('designation', e.target.value)}
                                    className="w-full text-[13px] bg-white border border-[#cbd5e1] rounded-xl px-4 py-2.5 focus:ring-2 focus:ring-[#0284c7]/20 focus:border-[#0284c7] outline-none transition-all"
                                />
                            </div>

                            <div className="md:col-span-2">
                                <label className="block text-[12px] font-bold uppercase tracking-wider text-[#475569] mb-1.5">Address</label>
                                <input
                                    type="text"
                                    value={profileData.address}
                                    onChange={e => setProfileData('address', e.target.value)}
                                    className="w-full text-[13px] bg-white border border-[#cbd5e1] rounded-xl px-4 py-2.5 focus:ring-2 focus:ring-[#0284c7]/20 focus:border-[#0284c7] outline-none transition-all"
                                />
                            </div>

                            <div>
                                <label className="block text-[12px] font-bold uppercase tracking-wider text-[#475569] mb-1.5">City</label>
                                <input
                                    type="text"
                                    value={profileData.city}
                                    onChange={e => setProfileData('city', e.target.value)}
                                    className="w-full text-[13px] bg-white border border-[#cbd5e1] rounded-xl px-4 py-2.5 focus:ring-2 focus:ring-[#0284c7]/20 focus:border-[#0284c7] outline-none transition-all"
                                />
                            </div>

                            <div>
                                <label className="block text-[12px] font-bold uppercase tracking-wider text-[#475569] mb-1.5">Country</label>
                                <input
                                    type="text"
                                    value={profileData.country}
                                    onChange={e => setProfileData('country', e.target.value)}
                                    className="w-full text-[13px] bg-white border border-[#cbd5e1] rounded-xl px-4 py-2.5 focus:ring-2 focus:ring-[#0284c7]/20 focus:border-[#0284c7] outline-none transition-all"
                                />
                            </div>
                        </div>

                        <div className="px-6 py-4 bg-[#f8fafc] border-t border-[#e2e8f0] flex justify-end">
                            <button
                                type="submit"
                                disabled={profileProcessing}
                                className="flex items-center gap-2 px-5 py-2.5 bg-[#0284c7] hover:bg-[#0369a1] text-white text-[13px] font-bold rounded-xl shadow-md shadow-[#0284c7]/20 transition-all disabled:opacity-50"
                            >
                                <Save className="w-4 h-4" />
                                {profileProcessing ? 'Saving...' : 'Save Profile'}
                            </button>
                        </div>
                    </form>

                    {/* Password Form */}
                    <form onSubmit={handlePasswordSubmit} className="bg-white/80 backdrop-blur-xl border border-[#e2e8f0] rounded-2xl shadow-sm overflow-hidden">
                        <div className="px-6 py-5 border-b border-[#e2e8f0] flex items-center gap-3">
                            <div className="w-8 h-8 rounded-lg bg-amber-50 flex items-center justify-center text-amber-600">
                                <KeyRound className="w-4 h-4" />
                            </div>
                            <div>
                                <h2 className="text-[15px] font-bold text-[#0f172a]">Security & Password</h2>
                                <p className="text-[12px] text-[#64748b]">Ensure your account is using a long, random password to stay secure.</p>
                            </div>
                        </div>

                        <div className="p-6 grid grid-cols-1 md:grid-cols-2 gap-5">
                            <div className="md:col-span-2">
                                <label className="block text-[12px] font-bold uppercase tracking-wider text-[#475569] mb-1.5">Current Password</label>
                                <div className="relative">
                                    <div className="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                        <Lock className="w-4 h-4 text-[#94a3b8]" />
                                    </div>
                                    <input
                                        type="password"
                                        value={passwordData.current_password}
                                        onChange={e => setPasswordData('current_password', e.target.value)}
                                        className="w-full pl-10 text-[13px] bg-white border border-[#cbd5e1] rounded-xl px-4 py-2.5 focus:ring-2 focus:ring-amber-500/20 focus:border-amber-500 outline-none transition-all"
                                    />
                                </div>
                                {passwordErrors.current_password && <p className="text-rose-500 text-[11px] mt-1">{passwordErrors.current_password}</p>}
                            </div>

                            <div>
                                <label className="block text-[12px] font-bold uppercase tracking-wider text-[#475569] mb-1.5">New Password</label>
                                <input
                                    type="password"
                                    value={passwordData.password}
                                    onChange={e => setPasswordData('password', e.target.value)}
                                    className="w-full text-[13px] bg-white border border-[#cbd5e1] rounded-xl px-4 py-2.5 focus:ring-2 focus:ring-amber-500/20 focus:border-amber-500 outline-none transition-all"
                                />
                                {passwordErrors.password && <p className="text-rose-500 text-[11px] mt-1">{passwordErrors.password}</p>}
                            </div>

                            <div>
                                <label className="block text-[12px] font-bold uppercase tracking-wider text-[#475569] mb-1.5">Confirm New Password</label>
                                <input
                                    type="password"
                                    value={passwordData.password_confirmation}
                                    onChange={e => setPasswordData('password_confirmation', e.target.value)}
                                    className="w-full text-[13px] bg-white border border-[#cbd5e1] rounded-xl px-4 py-2.5 focus:ring-2 focus:ring-amber-500/20 focus:border-amber-500 outline-none transition-all"
                                />
                            </div>
                        </div>

                        <div className="px-6 py-4 bg-[#f8fafc] border-t border-[#e2e8f0] flex justify-end">
                            <button
                                type="submit"
                                disabled={passwordProcessing}
                                className="flex items-center gap-2 px-5 py-2.5 bg-amber-500 hover:bg-amber-600 text-white text-[13px] font-bold rounded-xl shadow-md shadow-amber-500/20 transition-all disabled:opacity-50"
                            >
                                <Save className="w-4 h-4" />
                                {passwordProcessing ? 'Saving...' : 'Update Password'}
                            </button>
                        </div>
                    </form>

                </div>
            </div>
        </AdminLayout>
    );
}
