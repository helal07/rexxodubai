import React, { useState } from 'react';
import { Head, useForm } from '@inertiajs/react';
import AdminLayout from '@/Layouts/AdminLayout';
import { Save, Image as ImageIcon, Video, FileText, Type, ListTree, AppWindow } from 'lucide-react';
import MenuBuilder from '@/Components/Admin/MenuBuilder';
import PageBuilder from '@/Components/Admin/PageBuilder';

interface CmsData {
    [section: string]: {
        [key: string]: string | any;
    };
}

interface CmsProps {
    cmsData?: CmsData;
    pagesData?: any[];
    menuItems?: any[];
    parentMenuItems?: any[];
}

export default function CmsIndex({ cmsData = {}, pagesData = [], menuItems = [], parentMenuItems = [] }: CmsProps) {
    const [activeTab, setActiveTab] = useState('global');

    // Flatten initial data for the form
    const initialData: Record<string, any> = {};
    const sections = ['global', 'home_hero', 'home_campaign', 'maison_stories', 'footer'];
    
    // We provide defaults for keys that might not exist yet in DB
    const schema: Record<string, Record<string, any>> = {
        global: {
            site_name: '',
            tagline: '',
            logo_url: null,
            favicon_url: null,
            seo_meta_description: '',
        },
        home_hero: {
            hero_video_url: null,
        },
        home_campaign: {
            title: 'The Signature Collection',
            subtitle: 'Silhouettes redefined with intricate artisan craftsmanship.',
            banner_image: null,
            button_text: 'DISCOVER',
            button_link: '/perfumes'
        },
        maison_stories: {
            story1_category: 'CAMPAIGN',
            story1_title: 'Artisan Perfumery Collection',
            story1_image: null,
            story1_url: '/perfumes',
            
            story2_category: 'SHOWCASE',
            story2_title: 'Discover Exquisite Notes',
            story2_image: null,
            story2_url: '/perfumes',
            
            story3_category: 'BEHIND THE SCENES',
            story3_title: 'The Making of Luxury',
            story3_image: null,
            story3_url: '/about',
        },
        footer: {
            about_text: '',
            contact_email: '',
            contact_phone: '',
            contact_address: '',
            facebook_url: '',
            facebook_enabled: '1',
            instagram_url: '',
            instagram_enabled: '1',
            twitter_url: '',
            twitter_enabled: '1',
            tiktok_url: '',
            tiktok_enabled: '1',
            youtube_url: '',
            youtube_enabled: '1',
            whatsapp_enabled: '1',
            footerText: 'Fine Fragrance & Luxury Extraits',
        }
    };

    sections.forEach(section => {
        initialData[section] = { ...schema[section] };
        if (cmsData[section]) {
            Object.keys(cmsData[section]).forEach(key => {
                initialData[section][key] = cmsData[section][key];
            });
        }
    });

    const { data, setData, post, processing, errors } = useForm(initialData);

    const handleSectionChange = (section: string, key: string, value: any) => {
        setData(section, { ...data[section], [key]: value });
    };

    const submit = (e: React.FormEvent) => {
        e.preventDefault();
        post('/admin/cms', {
            preserveScroll: true,
            forceFormData: true, // Needed for file uploads
        });
    };

    const renderInput = (section: string, key: string, label: string, type: string = 'text', hint?: string) => {
        const value = data[section][key];
        return (
            <div className="space-y-1" key={`${section}-${key}`}>
                <div className="flex flex-col">
                    <label className="block text-[11px] font-bold uppercase tracking-wider text-[#64748b]">
                        {label}
                    </label>
                    {hint && <span className="text-[10px] text-[#94a3b8] mb-1 leading-tight">{hint}</span>}
                </div>
                {type === 'textarea' ? (
                    <textarea
                        value={value || ''}
                        onChange={e => handleSectionChange(section, key, e.target.value)}
                        className="w-full text-[13px] border-[#e2e8f0] rounded-xl focus:border-[#38bdf8] focus:ring-[#38bdf8] p-3"
                        rows={3}
                    />
                ) : type === 'file' ? (
                    <div className="flex items-center gap-4">
                        {typeof value === 'string' && (value.startsWith('/') || value.startsWith('http')) && (
                            <div className="w-16 h-16 rounded overflow-hidden bg-gray-100 flex-shrink-0">
                                {value.match(/\.(mp4|webm|mov)$/i) ? (
                                    <video src={value} className="w-full h-full object-cover" muted />
                                ) : (
                                    <img src={value} className="w-full h-full object-cover" />
                                )}
                            </div>
                        )}
                        <input
                            type="file"
                            onChange={e => handleSectionChange(section, key, e.target.files?.[0] || null)}
                            className="text-[12px] file:mr-4 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-sm file:font-semibold file:bg-[#e0f2fe] file:text-[#0284c7] hover:file:bg-[#bae6fd]"
                        />
                    </div>
                ) : type === 'checkbox' ? (
                    <label className="flex items-center gap-2 cursor-pointer mt-2">
                        <input
                            type="checkbox"
                            checked={value !== '0' && value !== false}
                            onChange={e => handleSectionChange(section, key, e.target.checked ? '1' : '0')}
                            className="w-4 h-4 text-[#0284c7] border-slate-300 rounded focus:ring-[#0284c7]"
                        />
                        <span className="text-[12px] font-bold text-slate-500 uppercase">Enable</span>
                    </label>
                ) : (
                    <input
                        type={type}
                        value={value || ''}
                        onChange={e => handleSectionChange(section, key, e.target.value)}
                        className="w-full text-[13px] border-[#e2e8f0] rounded-xl focus:border-[#38bdf8] focus:ring-[#38bdf8] px-3 py-2"
                    />
                )}
            </div>
        );
    };

    return (
        <AdminLayout
            activePage="cms"
            pageTitle="Frontend CMS"
            pageSubtitle="Manage all dynamic contents, videos, images, and footer data for the website."
        >
            <Head title="Frontend CMS — Admin" />

            <form onSubmit={submit} className="flex gap-6 max-w-6xl">
                {/* Tabs */}
                <div className="w-64 flex-shrink-0 space-y-2">
                    {[
                        { id: 'global', name: 'Global Settings', icon: Type },
                        { id: 'home_hero', name: 'Home: Hero Video', icon: Video },
                        { id: 'maison_stories', name: 'Home: Maison Stories', icon: ImageIcon },
                        { id: 'footer', name: 'Footer Information', icon: FileText },
                        { id: 'custom_pages', name: 'Custom Pages', icon: AppWindow },
                        { id: 'menus', name: 'Navigation Menus', icon: ListTree },
                    ].map(tab => {
                        const Icon = tab.icon;
                        return (
                            <button
                                key={tab.id}
                                type="button"
                                onClick={() => setActiveTab(tab.id)}
                                className={`w-full flex items-center gap-3 px-4 py-3 text-left rounded-xl text-[13px] font-bold uppercase tracking-wider transition-all ${
                                    activeTab === tab.id
                                        ? 'bg-[#0284c7] text-white shadow-md shadow-[#0284c7]/20'
                                        : 'bg-white text-[#64748b] hover:bg-[#f8fafc] border border-[#e2e8f0]'
                                }`}
                            >
                                <Icon className="w-4 h-4" />
                                {tab.name}
                            </button>
                        );
                    })}
                </div>

                {/* Content Panel */}
                <div className="flex-1 bg-white/90 backdrop-blur-xl border border-[#38bdf8]/30 p-6 md:p-8 rounded-2xl shadow-sm space-y-6">
                    <div className="flex items-center justify-between border-b border-[#e2e8f0] pb-4">
                        <h3 className="text-lg font-serif font-bold text-[#0f172a] uppercase tracking-wide">
                            {activeTab.replace('_', ' ')}
                        </h3>
                        {activeTab !== 'custom_pages' && activeTab !== 'menus' && (
                            <button
                                type="submit"
                                disabled={processing}
                                className="px-6 py-2 bg-[#0284c7] hover:bg-[#0369a1] text-white text-[12px] font-bold uppercase tracking-wider rounded-xl transition-all shadow-md shadow-[#0284c7]/20 flex items-center gap-2 disabled:opacity-50"
                            >
                                <Save className="w-4 h-4" /> Save Changes
                            </button>
                        )}
                    </div>

                    <div className="space-y-6">
                        {activeTab === 'global' && (
                            <>
                                {renderInput('global', 'site_name', 'Brand / Site Name')}
                                {renderInput('global', 'tagline', 'Tagline')}
                                {renderInput('global', 'seo_meta_description', 'SEO Meta Description', 'textarea')}
                                {renderInput('global', 'logo_url', 'Brand Logo (Image)', 'file', 'Recommended: PNG or WEBP with transparent background. High resolution.')}
                                {renderInput('global', 'favicon_url', 'Favicon (Icon)', 'file', 'Recommended: 32x32 or 64x64 (.ico or .png)')}
                            </>
                        )}

                        {activeTab === 'home_hero' && (
                            <>
                                {renderInput('home_hero', 'hero_video_url', 'Hero Background Video (MP4)', 'file', 'Recommended: 1920x1080 (16:9), Max 10MB')}
                            </>
                        )}

                        {activeTab === 'maison_stories' && (
                            <div className="space-y-8">
                                <div className="p-4 bg-[#f8fafc] rounded-xl border border-[#e2e8f0] space-y-4">
                                    <h4 className="font-bold text-[#0f172a] text-[13px] uppercase tracking-wider">Story 1</h4>
                                    {renderInput('maison_stories', 'story1_category', 'Category (e.g. CAMPAIGN)')}
                                    {renderInput('maison_stories', 'story1_title', 'Title')}
                                    {renderInput('maison_stories', 'story1_url', 'Link URL')}
                                    {renderInput('maison_stories', 'story1_image', 'Image', 'file', 'Recommended: 1200x1600 (portrait) or 1200x1200 (square)')}
                                </div>
                                <div className="p-4 bg-[#f8fafc] rounded-xl border border-[#e2e8f0] space-y-4">
                                    <h4 className="font-bold text-[#0f172a] text-[13px] uppercase tracking-wider">Story 2</h4>
                                    {renderInput('maison_stories', 'story2_category', 'Category')}
                                    {renderInput('maison_stories', 'story2_title', 'Title')}
                                    {renderInput('maison_stories', 'story2_url', 'Link URL')}
                                    {renderInput('maison_stories', 'story2_image', 'Image', 'file', 'Recommended: 1200x1600 (portrait) or 1200x1200 (square)')}
                                </div>
                                <div className="p-4 bg-[#f8fafc] rounded-xl border border-[#e2e8f0] space-y-4">
                                    <h4 className="font-bold text-[#0f172a] text-[13px] uppercase tracking-wider">Story 3</h4>
                                    {renderInput('maison_stories', 'story3_category', 'Category')}
                                    {renderInput('maison_stories', 'story3_title', 'Title')}
                                    {renderInput('maison_stories', 'story3_url', 'Link URL')}
                                    {renderInput('maison_stories', 'story3_image', 'Image', 'file', 'Recommended: 1200x1600 (portrait) or 1200x1200 (square)')}
                                </div>
                            </div>
                        )}

                        {activeTab === 'footer' && (
                            <>
                                {renderInput('footer', 'about_text', 'About Us Text', 'textarea')}
                                {renderInput('footer', 'contact_email', 'Contact Email', 'email')}
                                {renderInput('footer', 'contact_phone', 'Contact Phone / WhatsApp')}
                                {renderInput('footer', 'contact_address', 'Contact Address', 'textarea')}
                                
                                <div className="p-4 bg-[#f8fafc] rounded-xl border border-[#e2e8f0] space-y-4">
                                    <h4 className="font-bold text-[#0f172a] text-[13px] uppercase tracking-wider mb-2">Social Media Links</h4>
                                    
                                    <div className="flex gap-4 items-end">
                                        <div className="flex-1">{renderInput('footer', 'facebook_url', 'Facebook URL')}</div>
                                        <div className="pb-2">{renderInput('footer', 'facebook_enabled', '', 'checkbox')}</div>
                                    </div>
                                    
                                    <div className="flex gap-4 items-end">
                                        <div className="flex-1">{renderInput('footer', 'instagram_url', 'Instagram URL')}</div>
                                        <div className="pb-2">{renderInput('footer', 'instagram_enabled', '', 'checkbox')}</div>
                                    </div>
                                    
                                    <div className="flex gap-4 items-end">
                                        <div className="flex-1">{renderInput('footer', 'twitter_url', 'X (Twitter) URL')}</div>
                                        <div className="pb-2">{renderInput('footer', 'twitter_enabled', '', 'checkbox')}</div>
                                    </div>
                                    
                                    <div className="flex gap-4 items-end">
                                        <div className="flex-1">{renderInput('footer', 'tiktok_url', 'TikTok URL')}</div>
                                        <div className="pb-2">{renderInput('footer', 'tiktok_enabled', '', 'checkbox')}</div>
                                    </div>
                                    
                                    <div className="flex gap-4 items-end">
                                        <div className="flex-1">{renderInput('footer', 'youtube_url', 'YouTube URL')}</div>
                                        <div className="pb-2">{renderInput('footer', 'youtube_enabled', '', 'checkbox')}</div>
                                    </div>
                                    
                                    <div className="flex gap-4 items-end">
                                        <div className="flex-1">{renderInput('footer', 'whatsapp_enabled', 'Show WhatsApp icon in footer?', 'checkbox')}</div>
                                    </div>
                                </div>

                                {renderInput('footer', 'footerText', 'Footer Copyright Text')}
                            </>
                        )}

                        {activeTab === 'custom_pages' && (
                            <PageBuilder pages={pagesData} />
                        )}

                        {activeTab === 'menus' && (
                            <MenuBuilder items={menuItems} parentItems={parentMenuItems} />
                        )}
                    </div>
                </div>
            </form>
        </AdminLayout>
    );
}
