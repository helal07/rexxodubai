import React, { useState, useEffect } from 'react';
import { useForm, Head, Link } from '@inertiajs/react';
import AdminLayout from '@/Layouts/AdminLayout';
import { Save, ArrowLeft, Image as ImageIcon, Video, Star, Settings2, Layout, Type, HelpCircle, ShoppingBag, Eye } from 'lucide-react';
import ImageUploader from '@/Components/ImageUploader';

export default function LandingPageCreate({ availableProducts }: any) {
    const { data, setData, post, processing, errors } = useForm({
        title: '',
        slug: '',
        hero_title: '',
        subtitle: '',
        
        regular_price: '',
        offer_price: '',
        offer_end_date: '',
        
        theme_color: '#4f46e5',
        text_color: '#1e293b',
        background_color: '#f8fafc',
        other_color: '#ffffff',
        
        primary_button_text: 'Buy Now',
        secondary_button_text: 'Learn More',
        youtube_video_url: '',
        youtube_autoplay: true,
        
        assigned_products: [] as any[],
        
        features: [
            { title: '', text: '' },
            { title: '', text: '' },
        ],
        why_choose_us: [
            { title: '', text: '' },
            { title: '', text: '' },
            { title: '', text: '' },
            { title: '', text: '' },
        ],
        media_banners: ['', ''],
        reviews: [
            { name: '', city: '', rating: '5', text: '' },
            { name: '', city: '', rating: '5', text: '' },
            { name: '', city: '', rating: '5', text: '' },
            { name: '', city: '', rating: '5', text: '' },
        ],
        gallery_images: ['', '', '', '', '', '', '', ''],
        
        short_description: '',
        long_description: '',
        
        faqs: [
            { question: '', answer: '' },
            { question: '', answer: '' },
            { question: '', answer: '' },
        ],
        
        homepage_product_title: 'Featured Product',
        show_product_section: true,
        is_active: true,
    });

    const [isSlugCustomized, setIsSlugCustomized] = useState(false);

    // Auto-generate slug helper supporting Unicode/Bengali
    const generateSlug = (text: string) => {
        return text
            .toLowerCase()
            .trim()
            .replace(/[^\p{L}\p{N}\s-]/gu, '')
            .replace(/[\s_-]+/g, '-')
            .replace(/^-+|-+$/g, '');
    };

    const handleTitleChange = (val: string) => {
        setData('title', val);
        if (!isSlugCustomized) {
            setData('slug', generateSlug(val));
        }
    };

    const handleSlugChange = (val: string) => {
        setIsSlugCustomized(true);
        setData('slug', val);
    };

    const handleSubmit = (e: React.FormEvent) => {
        e.preventDefault();
        post('/admin/landing-pages', {
            forceFormData: true,
            preserveState: true,
            preserveScroll: true,
            onError: (errs) => {
                console.error('Validation errors:', errs);
            }
        });
    };

    const handleArrayChange = (field: string, index: number, key: string, value: any) => {
        const newArray = [...(data as any)[field]];
        newArray[index][key] = value;
        setData(field as any, newArray);
    };
    
    const handleFlatArrayChange = (field: string, index: number, value: any) => {
        const newArray = [...(data as any)[field]];
        newArray[index] = value;
        setData(field as any, newArray);
    };

    const handleProductToggle = (product: any) => {
        const exists = data.assigned_products.find((p: any) => p.id === product.id);
        if (exists) {
            setData('assigned_products', data.assigned_products.filter((p: any) => p.id !== product.id));
        } else {
            setData('assigned_products', [
                ...data.assigned_products,
                {
                    id: product.id,
                    regular_price: product.price || '',
                    offer_price: '',
                }
            ]);
        }
    };

    const handleProductPriceChange = (productId: number, field: 'regular_price' | 'offer_price', value: string) => {
        setData('assigned_products', data.assigned_products.map((p: any) => {
            if (p.id === productId) {
                return { ...p, [field]: value };
            }
            return p;
        }));
    };

    // Live preview sync
    const syncPreviewData = () => {
        const formatImages = (arr: any[]) => {
            return (arr || []).map((item: any) => {
                if (item instanceof File) {
                    try {
                        return URL.createObjectURL(item);
                    } catch (e) {
                        return '';
                    }
                }
                return typeof item === 'string' ? item : '';
            });
        };

        const previewData = {
            ...data,
            media_banners: formatImages(data.media_banners),
            gallery_images: formatImages(data.gallery_images),
        };

        const previewProducts = availableProducts
            .filter((p: any) => data.assigned_products.some((ap: any) => ap.id === p.id))
            .map((p: any) => {
                const ap = data.assigned_products.find((x: any) => x.id === p.id);
                return {
                    ...p,
                    pivot: {
                        regular_price: ap?.regular_price,
                        offer_price: ap?.offer_price,
                    }
                };
            });

        sessionStorage.setItem('landing_page_preview', JSON.stringify({
            landingPage: previewData,
            products: previewProducts
        }));
    };

    const handlePreview = () => {
        syncPreviewData();
        window.open('/landing-page-preview', '_blank');
    };

    // Update preview sessionStorage on changes
    useEffect(() => {
        syncPreviewData();
    }, [data]);

    return (
        <AdminLayout activePage="landing-pages" pageTitle="Landing Page Create">
            <Head title="Create Landing Page" />
            
            <div className="flex justify-between items-center mb-6">
                <div className="flex items-center gap-3">
                    <Link href="/admin/landing-pages" className="p-2 bg-white rounded-xl shadow-sm border border-[#e2e8f0] text-[#64748b] hover:text-[#0f172a]">
                        <ArrowLeft className="w-5 h-5" />
                    </Link>
                    <h2 className="text-xl font-bold text-[#0f172a]">Landing Page Create</h2>
                </div>
                <div className="flex items-center gap-3">
                    <button 
                        type="button" 
                        onClick={handlePreview} 
                        className="bg-white border border-slate-300 hover:bg-slate-50 text-slate-700 px-4 py-2 rounded-lg font-semibold text-sm transition-all shadow-sm flex items-center gap-2"
                    >
                        <Eye className="w-4 h-4 text-indigo-600" />
                        Live Preview
                    </button>
                    <button 
                        onClick={handleSubmit} 
                        disabled={processing} 
                        className="bg-indigo-600 hover:bg-indigo-700 text-white px-6 py-2 rounded-lg font-medium text-sm transition-all shadow-sm flex items-center gap-2"
                    >
                        <Save className="w-4 h-4" />
                        {processing ? 'Saving...' : 'Save Page'}
                    </button>
                </div>
            </div>

            {Object.keys(errors).length > 0 && (
                <div className="mb-6 p-4 bg-red-50 border border-red-200 rounded-xl text-red-700 text-sm space-y-1">
                    <div className="font-bold flex items-center gap-2">
                        <span>Please fix the following validation errors:</span>
                    </div>
                    <ul className="list-disc list-inside text-xs mt-2">
                        {Object.entries(errors).map(([key, msg]) => (
                            <li key={key}><strong>{key}:</strong> {msg}</li>
                        ))}
                    </ul>
                </div>
            )}

            <form onSubmit={handleSubmit} className="space-y-6 pb-20">
                
                {/* General Settings */}
                <div className="bg-white border border-[#e2e8f0] rounded-xl shadow-sm overflow-hidden">
                    <div className="bg-[#f8fafc] px-6 py-3 border-b border-[#e2e8f0] flex items-center gap-2">
                        <Settings2 className="w-4 h-4 text-indigo-600" />
                        <h3 className="font-semibold text-[#0f172a]">General Settings</h3>
                    </div>
                    <div className="p-6 grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label className="block text-xs font-medium text-[#475569] mb-1">Landing Page Title *</label>
                            <input 
                                type="text" 
                                required 
                                value={data.title} 
                                onChange={e => handleTitleChange(e.target.value)} 
                                placeholder="e.g. Premium Tool Set Offer"
                                className="w-full border border-[#e2e8f0] rounded-lg px-3 py-2 text-sm focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 outline-none" 
                            />
                        </div>
                        <div>
                            <div className="flex justify-between items-center mb-1">
                                <label className="block text-xs font-medium text-[#475569]">URL Slug *</label>
                                {!isSlugCustomized && data.slug && (
                                    <span className="text-[10px] text-emerald-600 font-medium">Auto-generated</span>
                                )}
                            </div>
                            <input 
                                type="text" 
                                required 
                                value={data.slug} 
                                onChange={e => handleSlugChange(e.target.value)} 
                                placeholder="e.g. premium-tool-set-offer"
                                className="w-full border border-[#e2e8f0] rounded-lg px-3 py-2 text-sm focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 outline-none font-mono text-xs" 
                            />
                        </div>
                        <div>
                            <label className="block text-xs font-medium text-[#475569] mb-1">Hero Title</label>
                            <input type="text" value={data.hero_title} onChange={e => setData('hero_title', e.target.value)} className="w-full border border-[#e2e8f0] rounded-lg px-3 py-2 text-sm focus:border-indigo-500 outline-none" />
                        </div>
                        <div>
                            <label className="block text-xs font-medium text-[#475569] mb-1">Sub-title</label>
                            <input type="text" value={data.subtitle} onChange={e => setData('subtitle', e.target.value)} className="w-full border border-[#e2e8f0] rounded-lg px-3 py-2 text-sm focus:border-indigo-500 outline-none" />
                        </div>
                    </div>
                </div>

                {/* Offer & Pricing Settings */}
                <div className="bg-white border border-[#e2e8f0] rounded-xl shadow-sm overflow-hidden">
                    <div className="bg-[#f8fafc] px-6 py-3 border-b border-[#e2e8f0] flex items-center gap-2">
                        <ShoppingBag className="w-4 h-4 text-indigo-600" />
                        <h3 className="font-semibold text-[#0f172a]">Bundle / Global Pricing & Offer (Optional)</h3>
                    </div>
                    <div className="p-6 grid grid-cols-1 md:grid-cols-3 gap-6">
                        <div>
                            <label className="block text-xs font-medium text-[#475569] mb-1">Global / Bundle Regular Price</label>
                            <input type="number" step="0.01" value={data.regular_price} onChange={e => setData('regular_price', e.target.value)} placeholder="e.g. 1500" className="w-full border border-[#e2e8f0] rounded-lg px-3 py-2 text-sm focus:border-indigo-500 outline-none" />
                        </div>
                        <div>
                            <label className="block text-xs font-medium text-[#475569] mb-1">Global / Bundle Offer Price</label>
                            <input type="number" step="0.01" value={data.offer_price} onChange={e => setData('offer_price', e.target.value)} placeholder="e.g. 980" className="w-full border border-[#e2e8f0] rounded-lg px-3 py-2 text-sm focus:border-indigo-500 outline-none" />
                        </div>
                        <div>
                            <label className="block text-xs font-medium text-[#475569] mb-1">Offer End Date (Countdown)</label>
                            <input type="datetime-local" value={data.offer_end_date} onChange={e => setData('offer_end_date', e.target.value)} className="w-full border border-[#e2e8f0] rounded-lg px-3 py-2 text-sm focus:border-indigo-500 outline-none" />
                        </div>
                    </div>
                </div>

                {/* Colors & Styling */}
                <div className="bg-white border border-[#e2e8f0] rounded-xl shadow-sm overflow-hidden">
                    <div className="bg-[#f8fafc] px-6 py-3 border-b border-[#e2e8f0] flex items-center gap-2">
                        <Layout className="w-4 h-4 text-indigo-600" />
                        <h3 className="font-semibold text-[#0f172a]">Colors & Styling</h3>
                    </div>
                    <div className="p-6 grid grid-cols-2 md:grid-cols-4 gap-6">
                        <div>
                            <label className="block text-xs font-medium text-[#475569] mb-1">Theme Color</label>
                            <div className="flex gap-2 items-center">
                                <input type="color" value={data.theme_color} onChange={e => setData('theme_color', e.target.value)} className="w-8 h-8 rounded cursor-pointer border border-[#e2e8f0]" />
                                <input type="text" value={data.theme_color} onChange={e => setData('theme_color', e.target.value)} className="w-full border border-[#e2e8f0] rounded-lg px-3 py-2 text-sm outline-none" />
                            </div>
                        </div>
                        <div>
                            <label className="block text-xs font-medium text-[#475569] mb-1">Text Color</label>
                            <div className="flex gap-2 items-center">
                                <input type="color" value={data.text_color} onChange={e => setData('text_color', e.target.value)} className="w-8 h-8 rounded cursor-pointer border border-[#e2e8f0]" />
                                <input type="text" value={data.text_color} onChange={e => setData('text_color', e.target.value)} className="w-full border border-[#e2e8f0] rounded-lg px-3 py-2 text-sm outline-none" />
                            </div>
                        </div>
                        <div>
                            <label className="block text-xs font-medium text-[#475569] mb-1">Background Color</label>
                            <div className="flex gap-2 items-center">
                                <input type="color" value={data.background_color} onChange={e => setData('background_color', e.target.value)} className="w-8 h-8 rounded cursor-pointer border border-[#e2e8f0]" />
                                <input type="text" value={data.background_color} onChange={e => setData('background_color', e.target.value)} className="w-full border border-[#e2e8f0] rounded-lg px-3 py-2 text-sm outline-none" />
                            </div>
                        </div>
                        <div>
                            <label className="block text-xs font-medium text-[#475569] mb-1">Other Color</label>
                            <div className="flex gap-2 items-center">
                                <input type="color" value={data.other_color} onChange={e => setData('other_color', e.target.value)} className="w-8 h-8 rounded cursor-pointer border border-[#e2e8f0]" />
                                <input type="text" value={data.other_color} onChange={e => setData('other_color', e.target.value)} className="w-full border border-[#e2e8f0] rounded-lg px-3 py-2 text-sm outline-none" />
                            </div>
                        </div>
                    </div>
                </div>

                {/* Buttons & Media */}
                <div className="bg-white border border-[#e2e8f0] rounded-xl shadow-sm overflow-hidden">
                    <div className="bg-[#f8fafc] px-6 py-3 border-b border-[#e2e8f0] flex items-center gap-2">
                        <Video className="w-4 h-4 text-indigo-600" />
                        <h3 className="font-semibold text-[#0f172a]">Buttons & Video</h3>
                    </div>
                    <div className="p-6 grid grid-cols-1 md:grid-cols-3 gap-6">
                        <div>
                            <label className="block text-xs font-medium text-[#475569] mb-1">Primary Button Text</label>
                            <input type="text" value={data.primary_button_text} onChange={e => setData('primary_button_text', e.target.value)} className="w-full border border-[#e2e8f0] rounded-lg px-3 py-2 text-sm outline-none" />
                        </div>
                        <div>
                            <label className="block text-xs font-medium text-[#475569] mb-1">Secondary Button Text</label>
                            <input type="text" value={data.secondary_button_text} onChange={e => setData('secondary_button_text', e.target.value)} className="w-full border border-[#e2e8f0] rounded-lg px-3 py-2 text-sm outline-none" />
                        </div>
                        <div>
                            <label className="block text-xs font-medium text-[#475569] mb-1">YouTube Video URL</label>
                            <input type="text" value={data.youtube_video_url} onChange={e => setData('youtube_video_url', e.target.value)} placeholder="https://youtube.com/..." className="w-full border border-[#e2e8f0] rounded-lg px-3 py-2 text-sm outline-none" />
                            <label className="flex items-center gap-2 mt-2 cursor-pointer select-none">
                                <input type="checkbox" checked={data.youtube_autoplay} onChange={e => setData('youtube_autoplay', e.target.checked)} className="rounded text-indigo-600 focus:ring-indigo-500 w-4 h-4" />
                                <span className="text-xs text-slate-700 font-medium">Autoplay Video on Visit (Muted)</span>
                            </label>
                        </div>
                    </div>
                </div>

                {/* Products Linked */}
                <div className="bg-white border border-[#e2e8f0] rounded-xl shadow-sm overflow-hidden">
                    <div className="bg-[#f8fafc] px-6 py-3 border-b border-[#e2e8f0] flex items-center gap-2">
                        <ShoppingBag className="w-4 h-4 text-indigo-600" />
                        <h3 className="font-semibold text-[#0f172a]">Assigned Products & Multiple Discounts</h3>
                    </div>
                    <div className="p-6">
                        <label className="block text-xs font-medium text-[#475569] mb-4">Click to Select/Deselect Products and Set Specific Offers</label>
                        <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4 max-h-[550px] overflow-y-auto pr-2">
                            {availableProducts.map((p: any) => {
                                const assigned = data.assigned_products.find((ap: any) => ap.id === p.id);
                                const isSelected = !!assigned;
                                return (
                                    <div 
                                        key={p.id} 
                                        className={`relative rounded-xl border transition-all ${isSelected ? 'border-indigo-600 bg-indigo-50/40 p-4' : 'border-[#e2e8f0] bg-white hover:border-indigo-300 p-3'}`}
                                    >
                                        <div 
                                            onClick={() => handleProductToggle(p)}
                                            className="flex items-center gap-3 cursor-pointer"
                                        >
                                            <div className={`w-5 h-5 rounded-md flex items-center justify-center border flex-shrink-0 ${isSelected ? 'bg-indigo-600 border-indigo-600' : 'border-slate-300'}`}>
                                                {isSelected && <svg className="w-3.5 h-3.5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path strokeLinecap="round" strokeLinejoin="round" strokeWidth="3" d="M5 13l4 4L19 7" /></svg>}
                                            </div>
                                            <div className="w-12 h-12 bg-slate-100 rounded-lg overflow-hidden flex-shrink-0">
                                                {p.primary_image_url ? (
                                                    <img src={p.primary_image_url} alt={p.name} className="w-full h-full object-cover" />
                                                ) : (
                                                    <div className="w-full h-full flex items-center justify-center text-slate-400">
                                                        <ShoppingBag className="w-5 h-5" />
                                                    </div>
                                                )}
                                            </div>
                                            <div className="flex-1 min-w-0">
                                                <div className="font-semibold text-sm text-[#0f172a] truncate">
                                                    {p.name}
                                                </div>
                                                <div className="text-xs text-slate-500 font-medium">
                                                    Regular: ৳{p.price || 0}
                                                </div>
                                            </div>
                                        </div>

                                        {isSelected && (
                                            <div className="mt-3 pt-3 border-t border-indigo-100 grid grid-cols-2 gap-2">
                                                <div>
                                                    <label className="block text-[11px] font-semibold text-slate-600 mb-1">Regular Price</label>
                                                    <input 
                                                        type="number" 
                                                        step="0.01" 
                                                        value={assigned.regular_price ?? ''} 
                                                        onChange={e => handleProductPriceChange(p.id, 'regular_price', e.target.value)} 
                                                        placeholder={String(p.price || 0)} 
                                                        className="w-full border border-slate-300 rounded-lg px-2 py-1.5 text-xs bg-white focus:border-indigo-600 outline-none" 
                                                    />
                                                </div>
                                                <div>
                                                    <label className="block text-[11px] font-semibold text-indigo-700 mb-1">Offer Price</label>
                                                    <input 
                                                        type="number" 
                                                        step="0.01" 
                                                        value={assigned.offer_price ?? ''} 
                                                        onChange={e => handleProductPriceChange(p.id, 'offer_price', e.target.value)} 
                                                        placeholder="Discounted" 
                                                        className="w-full border border-indigo-300 rounded-lg px-2 py-1.5 text-xs bg-white text-indigo-900 font-bold focus:border-indigo-600 outline-none" 
                                                    />
                                                </div>
                                            </div>
                                        )}
                                    </div>
                                );
                            })}
                        </div>
                        {data.assigned_products.length === 0 && (
                            <p className="text-xs text-slate-500 mt-3">No products selected yet. Click on products to select them and configure discounts.</p>
                        )}
                        {data.assigned_products.length > 0 && (
                            <p className="text-xs text-indigo-600 font-medium mt-3">{data.assigned_products.length} product(s) selected with individual discounts.</p>
                        )}
                    </div>
                </div>

                {/* Features & Why Choose Us */}
                <div className="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div className="bg-white border border-[#e2e8f0] rounded-xl shadow-sm overflow-hidden">
                        <div className="bg-[#f8fafc] px-6 py-3 border-b border-[#e2e8f0] flex items-center gap-2">
                            <Star className="w-4 h-4 text-indigo-600" />
                            <h3 className="font-semibold text-[#0f172a]">Features</h3>
                        </div>
                        <div className="p-6 space-y-4">
                            {data.features.map((feature, i) => (
                                <div key={i} className="space-y-2 border-b border-slate-100 pb-4 last:border-0 last:pb-0">
                                    <label className="block text-xs font-bold text-indigo-600 uppercase">Feature {i + 1}</label>
                                    <input type="text" placeholder="Title" value={feature.title} onChange={e => handleArrayChange('features', i, 'title', e.target.value)} className="w-full border border-[#e2e8f0] rounded-lg px-3 py-2 text-sm outline-none" />
                                    <textarea placeholder="Description" value={feature.text} onChange={e => handleArrayChange('features', i, 'text', e.target.value)} className="w-full border border-[#e2e8f0] rounded-lg px-3 py-2 text-sm outline-none resize-none h-16" />
                                </div>
                            ))}
                        </div>
                    </div>
                    
                    <div className="bg-white border border-[#e2e8f0] rounded-xl shadow-sm overflow-hidden">
                        <div className="bg-[#f8fafc] px-6 py-3 border-b border-[#e2e8f0] flex items-center gap-2">
                            <Star className="w-4 h-4 text-indigo-600" />
                            <h3 className="font-semibold text-[#0f172a]">Why Choose Us</h3>
                        </div>
                        <div className="p-6 space-y-4 h-[350px] overflow-y-auto">
                            {data.why_choose_us.map((item, i) => (
                                <div key={i} className="space-y-2 border-b border-slate-100 pb-4 last:border-0 last:pb-0">
                                    <label className="block text-xs font-bold text-indigo-600 uppercase">Reason {i + 1}</label>
                                    <input type="text" placeholder="Title" value={item.title} onChange={e => handleArrayChange('why_choose_us', i, 'title', e.target.value)} className="w-full border border-[#e2e8f0] rounded-lg px-3 py-2 text-sm outline-none" />
                                    <textarea placeholder="Description" value={item.text} onChange={e => handleArrayChange('why_choose_us', i, 'text', e.target.value)} className="w-full border border-[#e2e8f0] rounded-lg px-3 py-2 text-sm outline-none resize-none h-16" />
                                </div>
                            ))}
                        </div>
                    </div>
                </div>

                {/* Reviews */}
                <div className="bg-white border border-[#e2e8f0] rounded-xl shadow-sm overflow-hidden">
                    <div className="bg-[#f8fafc] px-6 py-3 border-b border-[#e2e8f0] flex items-center gap-2">
                        <Type className="w-4 h-4 text-indigo-600" />
                        <h3 className="font-semibold text-[#0f172a]">Customer Reviews</h3>
                    </div>
                    <div className="p-6 grid grid-cols-1 md:grid-cols-2 gap-6">
                        {data.reviews.map((review, i) => (
                            <div key={i} className="border border-slate-200 p-4 rounded-xl space-y-3 bg-slate-50">
                                <label className="block text-xs font-bold text-indigo-600 uppercase">Review {i + 1}</label>
                                <div className="grid grid-cols-2 gap-3">
                                    <input type="text" placeholder="Reviewer Name" value={review.name} onChange={e => handleArrayChange('reviews', i, 'name', e.target.value)} className="w-full border border-[#e2e8f0] rounded-lg px-3 py-2 text-sm outline-none" />
                                    <input type="text" placeholder="City" value={review.city} onChange={e => handleArrayChange('reviews', i, 'city', e.target.value)} className="w-full border border-[#e2e8f0] rounded-lg px-3 py-2 text-sm outline-none" />
                                </div>
                                <select value={review.rating} onChange={e => handleArrayChange('reviews', i, 'rating', e.target.value)} className="w-full border border-[#e2e8f0] rounded-lg px-3 py-2 text-sm outline-none">
                                    <option value="5">5 Stars</option>
                                    <option value="4">4 Stars</option>
                                    <option value="3">3 Stars</option>
                                    <option value="2">2 Stars</option>
                                    <option value="1">1 Star</option>
                                </select>
                                <textarea placeholder="Review Text" value={review.text} onChange={e => handleArrayChange('reviews', i, 'text', e.target.value)} className="w-full border border-[#e2e8f0] rounded-lg px-3 py-2 text-sm outline-none resize-none h-16" />
                            </div>
                        ))}
                    </div>
                </div>

                {/* Media Banners & Gallery - REDESIGNED */}
                <div className="bg-white border border-[#e2e8f0] rounded-xl shadow-sm overflow-hidden">
                    <div className="bg-[#f8fafc] px-6 py-3 border-b border-[#e2e8f0] flex items-center gap-2">
                        <ImageIcon className="w-4 h-4 text-indigo-600" />
                        <h3 className="font-semibold text-[#0f172a]">Media Banners & Gallery Photos</h3>
                    </div>
                    <div className="p-6 space-y-6">
                        <div>
                            <h4 className="text-sm font-bold text-slate-800 mb-3">Media Banners</h4>
                            <div className="grid grid-cols-1 md:grid-cols-2 gap-4">
                                {data.media_banners.map((banner: any, i: number) => (
                                    <ImageUploader
                                        key={i}
                                        label={`Banner Photo ${i + 1}`}
                                        value={banner}
                                        onChange={(newVal) => handleFlatArrayChange('media_banners', i, newVal)}
                                        onRemove={() => handleFlatArrayChange('media_banners', i, '')}
                                    />
                                ))}
                            </div>
                        </div>
                        
                        <hr className="border-slate-100" />
                        
                        <div>
                            <h4 className="text-sm font-bold text-slate-800 mb-3">Gallery Photos</h4>
                            <div className="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-4">
                                {data.gallery_images.map((img: any, i: number) => (
                                    <ImageUploader
                                        key={i}
                                        label={`Gallery Photo ${i + 1}`}
                                        value={img}
                                        onChange={(newVal) => handleFlatArrayChange('gallery_images', i, newVal)}
                                        onRemove={() => handleFlatArrayChange('gallery_images', i, '')}
                                    />
                                ))}
                            </div>
                        </div>
                    </div>
                </div>

                {/* Descriptions */}
                <div className="bg-white border border-[#e2e8f0] rounded-xl shadow-sm overflow-hidden">
                    <div className="bg-[#f8fafc] px-6 py-3 border-b border-[#e2e8f0] flex items-center gap-2">
                        <Type className="w-4 h-4 text-indigo-600" />
                        <h3 className="font-semibold text-[#0f172a]">Descriptions</h3>
                    </div>
                    <div className="p-6 space-y-4">
                        <div>
                            <label className="block text-xs font-medium text-[#475569] mb-1">Short Description (HTML allowed)</label>
                            <textarea value={data.short_description} onChange={e => setData('short_description', e.target.value)} className="w-full border border-[#e2e8f0] rounded-lg px-3 py-2 text-sm outline-none h-24" />
                        </div>
                        <div>
                            <label className="block text-xs font-medium text-[#475569] mb-1">Long Description (HTML allowed)</label>
                            <textarea value={data.long_description} onChange={e => setData('long_description', e.target.value)} className="w-full border border-[#e2e8f0] rounded-lg px-3 py-2 text-sm outline-none h-48" />
                        </div>
                    </div>
                </div>

                {/* FAQs */}
                <div className="bg-white border border-[#e2e8f0] rounded-xl shadow-sm overflow-hidden">
                    <div className="bg-[#f8fafc] px-6 py-3 border-b border-[#e2e8f0] flex items-center gap-2">
                        <HelpCircle className="w-4 h-4 text-indigo-600" />
                        <h3 className="font-semibold text-[#0f172a]">FAQs [3 Items]</h3>
                    </div>
                    <div className="p-6 space-y-4">
                        {data.faqs.map((faq, i) => (
                            <div key={i} className="space-y-2 border-b border-slate-100 pb-4 last:border-0 last:pb-0">
                                <label className="block text-xs font-bold text-indigo-600 uppercase">FAQ {i + 1}</label>
                                <input type="text" placeholder="Question" value={faq.question} onChange={e => handleArrayChange('faqs', i, 'question', e.target.value)} className="w-full border border-[#e2e8f0] rounded-lg px-3 py-2 text-sm outline-none font-medium" />
                                <textarea placeholder="Answer" value={faq.answer} onChange={e => handleArrayChange('faqs', i, 'answer', e.target.value)} className="w-full border border-[#e2e8f0] rounded-lg px-3 py-2 text-sm outline-none resize-none h-16" />
                            </div>
                        ))}
                    </div>
                </div>

            </form>
        </AdminLayout>
    );
}
