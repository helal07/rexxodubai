import React, { useState, useEffect } from 'react';
import { Head, Link, usePage } from '@inertiajs/react';
import { Play, CheckCircle2, Star, ChevronDown, ChevronUp, ShoppingBag, Clock, MapPin, ShieldCheck, Truck, ArrowRight, CheckSquare, Square } from 'lucide-react';
import ProductCard from '@/Components/ProductCard';

export default function LandingPageShow({ landingPage: initialLandingPage, products: initialProducts, isPreview = false }: any) {
    const { siteSettings, apiSettings }: any = usePage().props;
    const currency = siteSettings?.currency || 'BDT (৳)';
    const symbolMatch = currency.match(/\((.*?)\)/);
    const symbol = symbolMatch ? symbolMatch[1] : (currency.split(' ')[0] || '৳');

    const [pageData, setPageData] = useState<any>(() => {
        if (isPreview && typeof window !== 'undefined') {
            try {
                const stored = sessionStorage.getItem('landing_page_preview');
                if (stored) {
                    const parsed = JSON.parse(stored);
                    return parsed.landingPage || initialLandingPage || {};
                }
            } catch (e) { console.error('Error loading preview', e); }
        }
        return initialLandingPage || {};
    });

    const [pageProducts, setPageProducts] = useState<any[]>(() => {
        if (isPreview && typeof window !== 'undefined') {
            try {
                const stored = sessionStorage.getItem('landing_page_preview');
                if (stored) {
                    const parsed = JSON.parse(stored);
                    return parsed.products || initialProducts || [];
                }
            } catch (e) { console.error('Error loading preview products', e); }
        }
        return initialProducts || [];
    });

    useEffect(() => {
        if (isPreview) {
            const handleStorage = () => {
                try {
                    const stored = sessionStorage.getItem('landing_page_preview');
                    if (stored) {
                        const parsed = JSON.parse(stored);
                        if (parsed.landingPage) setPageData(parsed.landingPage);
                        if (parsed.products) setPageProducts(parsed.products);
                    }
                } catch (e) { console.error(e); }
            };
            window.addEventListener('storage', handleStorage);
            const interval = setInterval(handleStorage, 1000);
            return () => {
                window.removeEventListener('storage', handleStorage);
                clearInterval(interval);
            };
        }
    }, [isPreview]);

    const {
        title = '',
        hero_title = '',
        subtitle = '',
        theme_color = '#4f46e5',
        text_color = '#1e293b',
        background_color = '#f8fafc',
        primary_button_text = 'অর্ডার করতে এখানে ক্লিক করুন',
        secondary_button_text = '',
        youtube_video_url = '',
        youtube_autoplay = true,
        features = [],
        why_choose_us = [],
        media_banners = [],
        reviews = [],
        gallery_images = [],
        short_description = '',
        long_description = '',
        faqs = [],
        homepage_product_title = '',
        show_product_section = true,
        regular_price = '',
        offer_price = '',
        offer_end_date = '',
    } = pageData;

    const products = pageProducts || [];

    const [activeFaq, setActiveFaq] = useState<number | null>(null);
    const [timeLeft, setTimeLeft] = useState({ days: 0, hours: 0, minutes: 0, seconds: 0 });

    // Helper to safely get image source string without producing [object Object]
    const getImageSrc = (item: any): string => {
        if (!item) return '';
        if (typeof item === 'string') {
            const trimmed = item.trim();
            if (!trimmed || trimmed === '[object Object]' || trimmed === 'null' || trimmed === 'undefined') {
                return '';
            }
            return trimmed;
        }
        if (item instanceof File) {
            try {
                return URL.createObjectURL(item);
            } catch (e) {
                return '';
            }
        }
        if (typeof item === 'object') {
            if (typeof item.url === 'string' && item.url !== '[object Object]') return item.url;
            if (typeof item.preview === 'string' && item.preview !== '[object Object]') return item.preview;
            if (typeof item.src === 'string' && item.src !== '[object Object]') return item.src;
        }
        return '';
    };

    const validBanners = (media_banners || []).map(getImageSrc).filter(Boolean);
    const validGallery = (gallery_images || []).map(getImageSrc).filter(Boolean);

    // YouTube Parser - handles watch URLs, youtu.be, shorts, embeds, and raw IDs or full iframes
    const getYoutubeId = (url: string) => {
        if (!url) return null;
        let input = url.trim();
        const iframeMatch = input.match(/src=["']([^"']+)["']/);
        if (iframeMatch) {
            input = iframeMatch[1];
        }
        if (/^[a-zA-Z0-9_-]{11}$/.test(input)) {
            return input;
        }
        const regExp = /(?:youtu\.be\/|youtube(?:-nocookie)?\.com\/(?:embed\/|v\/|shorts\/|watch\?v=|watch\?.+&v=))([\w-]{11})/i;
        const match = input.match(regExp);
        return match ? match[1] : null;
    };
    const videoId = getYoutubeId(youtube_video_url);

    // Countdown Timer
    useEffect(() => {
        if (!offer_end_date) return;
        const endDate = new Date(offer_end_date).getTime();
        
        const timer = setInterval(() => {
            const now = new Date().getTime();
            const distance = endDate - now;
            
            if (distance < 0) {
                clearInterval(timer);
                return;
            }
            
            setTimeLeft({
                days: Math.floor(distance / (1000 * 60 * 60 * 24)),
                hours: Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60)),
                minutes: Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60)),
                seconds: Math.floor((distance % (1000 * 60)) / 1000),
            });
        }, 1000);
        
        return () => clearInterval(timer);
    }, [offer_end_date]);

    // Product Selection State for Checkout
    const [selectedProductIds, setSelectedProductIds] = useState<number[]>(() => {
        if (products && products.length > 0) {
            return products.map((p: any) => p.id);
        }
        return [];
    });

    useEffect(() => {
        if (products && products.length > 0) {
            setSelectedProductIds((prev) => {
                if (prev.length === 0) return products.map((p: any) => p.id);
                return prev;
            });
        }
    }, [products]);

    const toggleProductSelection = (productId: number) => {
        if (selectedProductIds.includes(productId)) {
            if (selectedProductIds.length === 1) {
                return;
            }
            setSelectedProductIds(selectedProductIds.filter(id => id !== productId));
        } else {
            setSelectedProductIds([...selectedProductIds, productId]);
        }
    };

    // Calculate dynamic subtotal
    const getProductPrice = (p: any) => {
        if (p.pivot?.offer_price) return Number(p.pivot.offer_price);
        if (offer_price) return Number(offer_price);
        if (p.price) return Number(p.price);
        return 0;
    };

    const getProductRegularPrice = (p: any) => {
        if (p.pivot?.regular_price) return Number(p.pivot.regular_price);
        if (regular_price) return Number(regular_price);
        if (p.price) return Number(p.price);
        return 0;
    };

    // Pricing for Hero Section
    const heroRegularPrice = regular_price || (products.length === 1 ? (products[0].pivot?.regular_price || products[0].price) : null);
    const heroOfferPrice = offer_price || (products.length === 1 ? (products[0].pivot?.offer_price || products[0].pivot?.regular_price || products[0].price) : null);

    // Checkout Form State
    const [checkoutForm, setCheckoutForm] = useState({
        name: '',
        phone: '',
        address: '',
        city: 'Dhaka',
        paymentMethod: 'cod',
    });
    const [isSubmitting, setIsSubmitting] = useState(false);
    const [orderSuccess, setOrderSuccess] = useState(false);
    const [orderError, setOrderError] = useState('');

    const shippingCost = checkoutForm.city.toLowerCase() === 'dhaka' ? 60 : 120;
    const selectedProductsList = (products || []).filter((p: any) => selectedProductIds.includes(p.id));
    const itemsSubtotal = selectedProductsList.reduce((sum: number, p: any) => sum + getProductPrice(p), 0);
    const orderTotal = itemsSubtotal + (selectedProductsList.length > 0 ? shippingCost : 0);

    const handleOrderSubmit = async (e: React.FormEvent) => {
        e.preventDefault();
        setIsSubmitting(true);
        setOrderError('');

        if (selectedProductsList.length === 0) {
            setOrderError('Please select at least one product to order.');
            setIsSubmitting(false);
            return;
        }

        const payload = {
            customer_name: checkoutForm.name,
            customer_phone: checkoutForm.phone,
            customer_email: `${checkoutForm.phone}@landing.local`,
            shipping_address: checkoutForm.address,
            city: checkoutForm.city,
            payment_method: checkoutForm.paymentMethod,
            shipping_cost: shippingCost,
            items: selectedProductsList.map((p: any) => ({
                product_id: p.id,
                unit_price: getProductPrice(p),
                size: 'Default',
                quantity: 1,
            })),
        };

        try {
            const response = await fetch('/api/orders', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json', 'Accept': 'application/json' },
                body: JSON.stringify(payload),
            });
            const data = await response.json();
            
            if (!response.ok) throw new Error(data.message || 'Failed to place order.');

            if (['sslcommerz', 'eps', 'bkash'].includes(checkoutForm.paymentMethod)) {
                try {
                    const payRes = await fetch('/api/payment/initiate', {
                        method: 'POST',
                        headers: { 'Content-Type': 'application/json', 'Accept': 'application/json' },
                        body: JSON.stringify({ order_id: data.order.id, gateway: checkoutForm.paymentMethod }),
                    });
                    const payData = await payRes.json();
                    if (payRes.ok && payData.redirect_url) {
                        window.location.href = payData.redirect_url;
                        return;
                    }
                } catch (e) { console.error('Payment redirection failed', e); }
            }

            setOrderSuccess(true);
        } catch (err: any) {
            setOrderError(err.message || 'An error occurred.');
        } finally {
            setIsSubmitting(false);
        }
    };

    const scrollToCheckout = (e?: React.MouseEvent) => {
        if (e) e.preventDefault();
        document.getElementById('checkout-section')?.scrollIntoView({ behavior: 'smooth' });
    };

    const CTAButton = ({ className = "" }: { className?: string }) => (
        <button
            type="button"
            onClick={scrollToCheckout}
            style={{ backgroundColor: theme_color }}
            className={`flex items-center justify-center gap-2 text-white px-8 py-4 rounded-xl font-bold text-lg md:text-xl shadow-xl hover:scale-105 active:scale-95 transition-all w-full animate-bounce ${className}`}
        >
            <ShoppingBag className="w-6 h-6" />
            <span>{primary_button_text || 'অর্ডার করতে এখানে ক্লিক করুন'}</span>
            <ArrowRight className="w-5 h-5" />
        </button>
    );

    return (
        <div style={{ backgroundColor: background_color, color: text_color }} className="min-h-screen font-sans antialiased selection:bg-indigo-500 selection:text-white">
            <Head title={title}>
                <meta name="description" content={short_description || title} />
            </Head>
            
            {/* Hero Section */}
            <section className="relative pt-12 pb-10 px-4 overflow-hidden">
                <div className="max-w-4xl mx-auto text-center relative z-10">
                    {/* Offer Countdown Banner */}
                    {offer_end_date && (
                        <div className="inline-flex flex-col items-center justify-center mb-6 bg-red-50 text-red-700 px-6 py-3 rounded-2xl border border-red-100 shadow-sm">
                            <span className="text-xs font-bold uppercase tracking-wider mb-1 flex items-center gap-1">
                                <Clock size={14} className="animate-spin-slow" /> Limited Time Offer Ends In
                            </span>
                            <div className="flex items-center gap-2 font-mono text-xl md:text-2xl font-black">
                                <div className="flex flex-col items-center"><span>{String(timeLeft.days).padStart(2, '0')}</span><span className="text-[10px] uppercase">Days</span></div>:
                                <div className="flex flex-col items-center"><span>{String(timeLeft.hours).padStart(2, '0')}</span><span className="text-[10px] uppercase">Hrs</span></div>:
                                <div className="flex flex-col items-center"><span>{String(timeLeft.minutes).padStart(2, '0')}</span><span className="text-[10px] uppercase">Min</span></div>:
                                <div className="flex flex-col items-center text-red-600"><span>{String(timeLeft.seconds).padStart(2, '0')}</span><span className="text-[10px] uppercase">Sec</span></div>
                            </div>
                        </div>
                    )}

                    <h1 className="text-3xl md:text-5xl lg:text-6xl font-black tracking-tight leading-tight mb-4 drop-shadow-sm" style={{ color: text_color }}>
                        {hero_title || title}
                    </h1>
                    
                    {subtitle && (
                        <p className="text-lg md:text-xl mb-8 max-w-2xl mx-auto opacity-80 font-medium">
                            {subtitle}
                        </p>
                    )}

                    {/* Pricing Block */}
                    {(heroRegularPrice || heroOfferPrice) && (
                        <div className="flex justify-center items-center gap-4 mb-8">
                            {heroRegularPrice && (
                                <span className="text-xl md:text-2xl text-slate-400 line-through font-bold">
                                    {symbol}{Number(heroRegularPrice).toLocaleString()}
                                </span>
                            )}
                            {heroOfferPrice && (
                                <span className="text-3xl md:text-4xl font-black" style={{ color: theme_color }}>
                                    {symbol}{Number(heroOfferPrice).toLocaleString()}
                                </span>
                            )}
                        </div>
                    )}
                    
                    <div className="max-w-md mx-auto">
                        <CTAButton />
                        <div className="flex justify-center items-center gap-4 mt-4 text-xs font-bold text-slate-500 uppercase tracking-wider">
                            <span className="flex items-center gap-1"><Truck size={14} /> Fast Delivery</span>
                            <span className="flex items-center gap-1"><ShieldCheck size={14} /> 100% Original</span>
                        </div>
                    </div>
                </div>
            </section>

            {/* Video Section */}
            {videoId && (
                <section className="px-4 py-6">
                    <div className="max-w-4xl mx-auto rounded-3xl overflow-hidden shadow-2xl bg-black border-4 border-white/10">
                        <div className="aspect-w-16 aspect-h-9 relative w-full pb-[56.25%] bg-black">
                            <iframe 
                                className="absolute top-0 left-0 w-full h-full border-0"
                                src={`https://www.youtube.com/embed/${videoId}?${youtube_autoplay !== false ? 'autoplay=1&mute=1&playsinline=1&rel=0&enablejsapi=1' : 'autoplay=0&rel=0&enablejsapi=1'}`} 
                                title="Product Video" 
                                allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share" 
                                referrerPolicy="strict-origin-when-cross-origin"
                                allowFullScreen
                            ></iframe>
                        </div>
                    </div>
                </section>
            )}

            {/* Media Banners Section */}
            {validBanners.length > 0 && (
                <section className="px-4 py-6">
                    <div className="max-w-4xl mx-auto grid grid-cols-1 md:grid-cols-2 gap-4">
                        {validBanners.map((bannerUrl: string, i: number) => (
                            <div key={i} className="rounded-2xl overflow-hidden shadow-lg border border-slate-200/50 bg-white">
                                <img src={bannerUrl} alt={`Banner ${i+1}`} className="w-full h-auto object-cover" />
                            </div>
                        ))}
                    </div>
                </section>
            )}

            {/* Middle CTA */}
            <section className="px-4 py-8 max-w-md mx-auto text-center">
                <CTAButton />
            </section>

            {/* Description */}
            {(short_description || long_description) && (
                <section className="px-6 py-12 max-w-3xl mx-auto">
                    <div className="bg-white/60 backdrop-blur-lg rounded-3xl p-8 shadow-sm border border-white/50">
                        {short_description && (
                            <div className="text-xl md:text-2xl font-bold leading-relaxed mb-6 text-center" dangerouslySetInnerHTML={{ __html: short_description }} />
                        )}
                        {long_description && (
                            <div className="prose prose-lg mx-auto opacity-90" dangerouslySetInnerHTML={{ __html: long_description }} />
                        )}
                    </div>
                </section>
            )}

            {/* Why Choose Us & Features */}
            <section className="px-4 py-12 bg-white/40 border-y border-black/5">
                <div className="max-w-5xl mx-auto">
                    {features && features.some((f: any) => f.title) && (
                        <div className="grid grid-cols-1 md:grid-cols-2 gap-8 mb-16">
                            {features.filter((f: any) => f.title).map((feature: any, i: number) => (
                                <div key={i} className="flex gap-4 p-6 bg-white rounded-2xl shadow-sm border border-slate-100">
                                    <div className="flex-shrink-0">
                                        <div style={{ backgroundColor: `${theme_color}15`, color: theme_color }} className="p-4 rounded-full">
                                            <Star className="w-8 h-8" />
                                        </div>
                                    </div>
                                    <div>
                                        <h3 className="text-xl font-bold mb-2">{feature.title}</h3>
                                        <p className="opacity-70 leading-relaxed text-sm">{feature.text}</p>
                                    </div>
                                </div>
                            ))}
                        </div>
                    )}

                    {why_choose_us && why_choose_us.some((w: any) => w.title) && (
                        <div className="text-center">
                            <h2 className="text-3xl font-black mb-10">Why Buy From Us?</h2>
                            <div className="grid grid-cols-2 md:grid-cols-4 gap-4">
                                {why_choose_us.filter((w: any) => w.title).map((item: any, i: number) => (
                                    <div key={i} className="p-6 rounded-2xl bg-white shadow-sm border border-slate-100 flex flex-col items-center">
                                        <CheckCircle2 className="w-12 h-12 mb-4" style={{ color: theme_color }} />
                                        <h3 className="font-bold text-lg mb-2">{item.title}</h3>
                                        <p className="text-xs opacity-70">{item.text}</p>
                                    </div>
                                ))}
                            </div>
                        </div>
                    )}
                </div>
            </section>

            {/* Another CTA */}
            <section className="px-4 py-12 max-w-md mx-auto text-center">
                <p className="font-bold text-lg mb-4">Don't miss out on this offer!</p>
                <CTAButton />
            </section>

            {/* Gallery */}
            {validGallery.length > 0 && (
                <section className="px-4 py-12">
                    <div className="max-w-5xl mx-auto">
                        <h2 className="text-2xl md:text-3xl font-black text-center mb-8">Product Gallery</h2>
                        <div className="grid grid-cols-2 md:grid-cols-4 gap-3 md:gap-4">
                            {validGallery.map((imgUrl: string, i: number) => (
                                <div key={i} className="aspect-square rounded-2xl overflow-hidden bg-white shadow-md border border-slate-100 hover:scale-[1.03] transition-all duration-300">
                                    <img src={imgUrl} alt={`Gallery ${i+1}`} className="w-full h-full object-cover" />
                                </div>
                            ))}
                        </div>
                    </div>
                </section>
            )}

            {/* Reviews */}
            {reviews && reviews.some((r: any) => r.name) && (
                <section className="px-4 py-16 bg-slate-900 text-white">
                    <div className="max-w-4xl mx-auto">
                        <h2 className="text-3xl font-black text-center mb-10">Customer Feedback</h2>
                        <div className="grid grid-cols-1 md:grid-cols-2 gap-6">
                            {reviews.filter((r: any) => r.name).map((review: any, i: number) => (
                                <div key={i} className="bg-white/10 p-6 rounded-2xl border border-white/10">
                                    <div className="flex text-yellow-400 mb-4">
                                        {[...Array(Number(review.rating) || 5)].map((_, j) => (
                                            <Star key={j} className="w-4 h-4 fill-current" />
                                        ))}
                                    </div>
                                    <p className="text-lg mb-4 italic font-serif">"{review.text}"</p>
                                    <div className="flex items-center gap-3">
                                        <div className="w-10 h-10 rounded-full bg-slate-800 flex items-center justify-center font-bold text-sm">
                                            {review.name.charAt(0)}
                                        </div>
                                        <div>
                                            <p className="font-bold text-sm">{review.name}</p>
                                            <p className="text-xs opacity-70">{review.city}</p>
                                        </div>
                                    </div>
                                </div>
                            ))}
                        </div>
                    </div>
                </section>
            )}

            {/* INTEGRATED CHECKOUT SECTION */}
            <section id="checkout-section" className="px-4 py-16 scroll-mt-4">
                <div className="max-w-xl mx-auto bg-white text-slate-900 rounded-3xl shadow-2xl overflow-hidden border-2 border-dashed" style={{ borderColor: theme_color }}>
                    <div className="p-6 md:p-8 text-center text-white relative overflow-hidden" style={{ backgroundColor: theme_color }}>
                        <h2 className="text-2xl md:text-3xl font-black mb-2">অর্ডার কনফার্ম করতে ফর্মটি পূরণ করুন</h2>
                        <p className="text-sm opacity-90">সঠিক নাম, ঠিকানা ও মোবাইল নাম্বার দিন। আমরা দ্রুত ডেলিভারি করব।</p>
                    </div>

                    {orderSuccess ? (
                        <div className="p-8 text-center space-y-4">
                            <div className="w-16 h-16 bg-emerald-100 text-emerald-600 rounded-full flex items-center justify-center mx-auto mb-4">
                                <CheckCircle2 size={36} />
                            </div>
                            <h3 className="text-2xl font-black text-slate-800">ধন্যবাদ! আপনার অর্ডারটি সফল হয়েছে।</h3>
                            <p className="text-slate-600 text-sm">আমাদের প্রতিনিধি শীঘ্রই আপনার সাথে যোগাযোগ করবেন।</p>
                            <Link href="/" className="inline-block mt-4 px-6 py-2.5 bg-slate-900 text-white rounded-xl font-bold text-sm hover:bg-slate-800">
                                হোমপেজে ফিরে যান
                            </Link>
                        </div>
                    ) : (
                        <form onSubmit={handleOrderSubmit} className="p-6 md:p-8 space-y-6">
                            {orderError && (
                                <div className="p-4 bg-red-50 border border-red-200 text-red-600 rounded-xl text-sm font-semibold">
                                    {orderError}
                                </div>
                            )}

                            {/* Product Selection List if multiple products */}
                            {products.length > 0 && (
                                <div className="space-y-3">
                                    <label className="block text-xs font-bold text-slate-700 uppercase tracking-wider">
                                        আপনার পছন্দমতো প্রোডাক্ট সিলেক্ট করুন
                                    </label>
                                    <div className="space-y-2">
                                        {products.map((product: any) => {
                                            const isSelected = selectedProductIds.includes(product.id);
                                            const regular = getProductRegularPrice(product);
                                            const offer = getProductPrice(product);

                                            return (
                                                <div 
                                                    key={product.id}
                                                    onClick={() => toggleProductSelection(product.id)}
                                                    className={`flex items-center justify-between p-3 rounded-2xl border cursor-pointer transition-all ${
                                                        isSelected 
                                                            ? 'border-indigo-600 bg-indigo-50/50 shadow-sm' 
                                                            : 'border-slate-200 bg-slate-50 opacity-60 hover:opacity-100'
                                                    }`}
                                                >
                                                    <div className="flex items-center gap-3">
                                                        <div className={`text-lg ${isSelected ? 'text-indigo-600' : 'text-slate-400'}`}>
                                                            {isSelected ? <CheckSquare className="w-5 h-5 fill-indigo-600 text-white" /> : <Square className="w-5 h-5" />}
                                                        </div>
                                                        <div className="w-12 h-12 rounded-xl bg-white overflow-hidden border border-slate-200 flex-shrink-0 flex items-center justify-center">
                                                            {product.primary_image_url ? (
                                                                <img src={product.primary_image_url} alt={product.name} className="w-full h-full object-cover" />
                                                            ) : (
                                                                <ShoppingBag className="w-5 h-5 text-slate-400" />
                                                            )}
                                                        </div>
                                                        <div>
                                                            <h4 className="font-bold text-sm text-slate-800 line-clamp-1">{product.name}</h4>
                                                            <div className="flex items-center gap-2 text-xs">
                                                                {regular > offer && (
                                                                    <span className="text-slate-400 line-through font-medium">{symbol}{regular}</span>
                                                                )}
                                                                <span className="font-black text-indigo-700">{symbol}{offer}</span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <span className={`text-xs font-bold px-2 py-1 rounded-md ${isSelected ? 'bg-indigo-600 text-white' : 'bg-slate-200 text-slate-600'}`}>
                                                        {isSelected ? 'Selected' : 'Add'}
                                                    </span>
                                                </div>
                                            );
                                        })}
                                    </div>
                                </div>
                            )}

                            {/* Order Summary Box */}
                            <div className="p-4 rounded-2xl bg-slate-50 border border-slate-200 space-y-2">
                                <div className="flex justify-between text-sm text-slate-600">
                                    <span>সিলেক্টেড প্রোডাক্ট ({selectedProductsList.length} টি)</span>
                                    <span className="font-bold">{symbol}{itemsSubtotal.toLocaleString()}</span>
                                </div>
                                <div className="flex justify-between text-sm text-slate-600">
                                    <span>ডেলিভারি চার্জ ({checkoutForm.city})</span>
                                    <span className="font-bold">{symbol}{shippingCost}</span>
                                </div>
                                <div className="pt-2 border-t border-slate-200 flex justify-between text-base font-black text-slate-900">
                                    <span>সর্বমোট মূল্য:</span>
                                    <span className="text-xl font-extrabold" style={{ color: theme_color }}>
                                        {symbol}{orderTotal.toLocaleString()}
                                    </span>
                                </div>
                            </div>

                            {/* Customer Information Inputs */}
                            <div className="space-y-4">
                                <div>
                                    <label className="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">আপনার নাম *</label>
                                    <input 
                                        type="text" 
                                        required 
                                        placeholder="আপনার সম্পূর্ণ নাম লিখুন"
                                        value={checkoutForm.name}
                                        onChange={e => setCheckoutForm({ ...checkoutForm, name: e.target.value })}
                                        className="w-full px-4 py-3 rounded-xl border border-slate-200 text-sm focus:border-indigo-600 focus:ring-1 focus:ring-indigo-600 outline-none transition-all"
                                    />
                                </div>
                                <div>
                                    <label className="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">মোবাইল নাম্বার *</label>
                                    <input 
                                        type="tel" 
                                        required 
                                        placeholder="01XXXXXXXXX"
                                        value={checkoutForm.phone}
                                        onChange={e => setCheckoutForm({ ...checkoutForm, phone: e.target.value })}
                                        className="w-full px-4 py-3 rounded-xl border border-slate-200 text-sm focus:border-indigo-600 focus:ring-1 focus:ring-indigo-600 outline-none transition-all font-mono"
                                    />
                                </div>
                                <div>
                                    <label className="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">ডেলিভারি এরিয়া *</label>
                                    <div className="grid grid-cols-2 gap-3">
                                        <button
                                            type="button"
                                            onClick={() => setCheckoutForm({ ...checkoutForm, city: 'Dhaka' })}
                                            className={`p-3 rounded-xl border text-center font-bold text-sm transition-all ${
                                                checkoutForm.city === 'Dhaka' 
                                                    ? 'border-indigo-600 bg-indigo-50 text-indigo-700' 
                                                    : 'border-slate-200 text-slate-600 hover:bg-slate-50'
                                            }`}
                                        >
                                            ঢাকার ভিতরে (৳৬০)
                                        </button>
                                        <button
                                            type="button"
                                            onClick={() => setCheckoutForm({ ...checkoutForm, city: 'Outside Dhaka' })}
                                            className={`p-3 rounded-xl border text-center font-bold text-sm transition-all ${
                                                checkoutForm.city === 'Outside Dhaka' 
                                                    ? 'border-indigo-600 bg-indigo-50 text-indigo-700' 
                                                    : 'border-slate-200 text-slate-600 hover:bg-slate-50'
                                            }`}
                                        >
                                            ঢাকার বাইরে (৳১২০)
                                        </button>
                                    </div>
                                </div>
                                <div>
                                    <label className="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">সম্পূর্ণ ঠিকানা *</label>
                                    <textarea 
                                        required 
                                        placeholder="বাসা নং, রোড নং, এলাকা, থানা, জেলা"
                                        value={checkoutForm.address}
                                        onChange={e => setCheckoutForm({ ...checkoutForm, address: e.target.value })}
                                        rows={3}
                                        className="w-full px-4 py-3 rounded-xl border border-slate-200 text-sm focus:border-indigo-600 focus:ring-1 focus:ring-indigo-600 outline-none transition-all resize-none"
                                    />
                                </div>

                                {/* Payment Method Selection */}
                                <div>
                                    <label className="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">পেমেন্ট মেথড</label>
                                    <div className="grid grid-cols-2 gap-3">
                                        <label className={`flex items-center gap-2 p-3 rounded-xl border cursor-pointer transition-all ${checkoutForm.paymentMethod === 'cod' ? 'border-indigo-600 bg-indigo-50 font-bold text-indigo-900' : 'border-slate-200 text-slate-700'}`}>
                                            <input 
                                                type="radio" 
                                                name="paymentMethod" 
                                                value="cod" 
                                                checked={checkoutForm.paymentMethod === 'cod'} 
                                                onChange={e => setCheckoutForm({ ...checkoutForm, paymentMethod: e.target.value })}
                                                className="text-indigo-600"
                                            />
                                            <span className="text-xs">ক্যাশ অন ডেলিভারি</span>
                                        </label>
                                        
                                        {apiSettings?.bkash_enabled && (
                                            <label className={`flex items-center gap-2 p-3 rounded-xl border cursor-pointer transition-all ${checkoutForm.paymentMethod === 'bkash' ? 'border-pink-600 bg-pink-50 font-bold text-pink-900' : 'border-slate-200 text-slate-700'}`}>
                                                <input 
                                                    type="radio" 
                                                    name="paymentMethod" 
                                                    value="bkash" 
                                                    checked={checkoutForm.paymentMethod === 'bkash'} 
                                                    onChange={e => setCheckoutForm({ ...checkoutForm, paymentMethod: e.target.value })}
                                                    className="text-pink-600"
                                                />
                                                <span className="text-xs">বিকাশ (bKash)</span>
                                            </label>
                                        )}

                                        {apiSettings?.sslcommerz_enabled && (
                                            <label className={`flex items-center gap-2 p-3 rounded-xl border cursor-pointer transition-all ${checkoutForm.paymentMethod === 'sslcommerz' ? 'border-indigo-600 bg-indigo-50 font-bold text-indigo-900' : 'border-slate-200 text-slate-700'}`}>
                                                <input 
                                                    type="radio" 
                                                    name="paymentMethod" 
                                                    value="sslcommerz" 
                                                    checked={checkoutForm.paymentMethod === 'sslcommerz'} 
                                                    onChange={e => setCheckoutForm({ ...checkoutForm, paymentMethod: e.target.value })}
                                                    className="text-indigo-600"
                                                />
                                                <span className="text-xs">অনলাইন পেমেন্ট</span>
                                            </label>
                                        )}
                                    </div>
                                </div>
                            </div>

                            <button 
                                type="submit" 
                                disabled={isSubmitting || selectedProductsList.length === 0}
                                style={{ backgroundColor: theme_color }}
                                className="w-full py-4 rounded-2xl text-white font-extrabold text-lg shadow-xl hover:opacity-95 transform hover:-translate-y-0.5 active:translate-y-0 transition-all flex items-center justify-center gap-2 disabled:opacity-50"
                            >
                                {isSubmitting ? 'অর্ডার প্রসেস হচ্ছে...' : `অর্ডার কনফার্ম করুন (${symbol}${orderTotal.toLocaleString()})`}
                            </button>
                        </form>
                    )}
                </div>
            </section>

            {/* FAQs */}
            {faqs && faqs.some((f: any) => f.question) && (
                <section className="px-4 py-16 max-w-3xl mx-auto">
                    <h2 className="text-2xl md:text-3xl font-black text-center mb-10">Frequently Asked Questions</h2>
                    <div className="space-y-4">
                        {faqs.filter((f: any) => f.question).map((faq: any, i: number) => {
                            const isOpen = activeFaq === i;
                            return (
                                <div key={i} className="border border-slate-200 rounded-2xl bg-white overflow-hidden shadow-sm">
                                    <button 
                                        onClick={() => setActiveFaq(isOpen ? null : i)}
                                        className="w-full p-5 text-left font-bold text-base md:text-lg flex justify-between items-center gap-4 hover:bg-slate-50 transition-colors"
                                    >
                                        <span>{faq.question}</span>
                                        {isOpen ? <ChevronUp className="w-5 h-5 text-indigo-600 flex-shrink-0" /> : <ChevronDown className="w-5 h-5 opacity-40 flex-shrink-0" />}
                                    </button>
                                    {isOpen && (
                                        <div className="p-5 pt-0 text-sm text-slate-600 leading-relaxed border-t border-slate-100 bg-slate-50/50">
                                            {faq.answer}
                                        </div>
                                    )}
                                </div>
                            );
                        })}
                    </div>
                </section>
            )}

            {/* Sticky Bottom Order Bar on Mobile */}
            <div className="md:hidden fixed bottom-0 left-0 right-0 p-3 bg-white/95 backdrop-blur-md border-t border-slate-200 shadow-2xl z-50 flex items-center justify-between gap-3">
                <div>
                    <div className="text-[10px] text-slate-500 font-semibold uppercase">অফার মূল্য</div>
                    <div className="text-lg font-black" style={{ color: theme_color }}>
                        {symbol}{(heroOfferPrice || orderTotal).toLocaleString()}
                    </div>
                </div>
                <button
                    onClick={() => scrollToCheckout()}
                    style={{ backgroundColor: theme_color }}
                    className="flex-1 py-3 px-6 rounded-xl text-white font-bold text-sm shadow-lg flex items-center justify-center gap-2"
                >
                    <ShoppingBag className="w-4 h-4" />
                    <span>অর্ডার করুন</span>
                </button>
            </div>
            
        </div>
    );
}
