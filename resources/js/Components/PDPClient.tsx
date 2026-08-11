import React, { useState, useEffect } from 'react';
import { router } from '@inertiajs/react';
import { Product } from '@/lib/api';
import { useCart } from '@/Contexts/CartContext';
import { ChevronDown, ChevronUp, ShieldCheck, Truck, RefreshCw, ShoppingBag, Zap, Check, Sparkles, MessageCircle } from 'lucide-react';
import ScentTrail from './ScentTrail';

interface PDPClientProps {
  product: Product | any;
}

export default function PDPClient({ product }: PDPClientProps) {
  const { addItem } = useCart();
  
  const hasVariants = product.variants && product.variants.length > 0;
  const initialVariant = hasVariants ? product.variants[0] : null;

  const [selectedVariant, setSelectedVariant] = useState<any>(initialVariant);
  const [selectedSize, setSelectedSize] = useState(initialVariant ? initialVariant.name : (product.sizes?.[0] || '100ml'));
  const [quantity, setQuantity] = useState(1);
  const [isAdded, setIsAdded] = useState(false);
  const [isBuyingNow, setIsBuyingNow] = useState(false);
  const [activeImage, setActiveImage] = useState(product.primary_image_url);
  const [activeAccordion, setActiveAccordion] = useState<string | null>('notes');

  useEffect(() => {
    if (selectedVariant) {
        setSelectedSize(selectedVariant.name);
    }
  }, [selectedVariant]);

  const allImages = [
    product.primary_image_url,
    ...(product.secondary_image_url ? [product.secondary_image_url] : []),
    ...(product.images?.map((i: any) => i.image_url) || [])
  ].filter(Boolean);

  const toggleAccordion = (section: string) => {
    setActiveAccordion(prev => (prev === section ? null : section));
  };

  const currentPrice = selectedVariant ? Number(selectedVariant.pivot?.price || selectedVariant.price) : Number(product.price);

  const handleAddToCart = () => {
    addItem(
      {
        id: product.id,
        name: product.name,
        slug: product.slug,
        price: currentPrice || 0,
        size: selectedSize,
        image: product.primary_image_url,
        concentration: product.concentration || 'Extrait De Parfum',
      },
      quantity,
      true
    );

    setIsAdded(true);
    setTimeout(() => setIsAdded(false), 2200);
  };

  const handleBuyNow = () => {
    setIsBuyingNow(true);
    addItem(
      {
        id: product.id,
        name: product.name,
        slug: product.slug,
        price: currentPrice || 0,
        size: selectedSize,
        image: product.primary_image_url,
        concentration: product.concentration || 'Extrait De Parfum',
      },
      quantity,
      false
    );
    router.visit('/checkout');
  };

  const formattedUnitPrice = currentPrice ? `৳${currentPrice.toFixed(2)}` : '৳0.00';
  const totalPrice = currentPrice ? `৳${(currentPrice * quantity).toFixed(2)}` : '৳0.00';

  return (
    <div className="grid grid-cols-1 lg:grid-cols-12 gap-12 items-start">
      {/* Left: Sticky Image Gallery */}
      <div className="lg:col-span-7 space-y-4 lg:sticky lg:top-28">
        <ScentTrail className="w-full aspect-[4/5] bg-[#F5F3EF] border border-[#DEDBD4] relative overflow-hidden flex items-center justify-center">
          <img
            src={activeImage}
            alt={product.name}
            className="w-full h-full object-contain p-6 md:p-10 transition-transform duration-500 hover:scale-105"
          />
        </ScentTrail>

        {/* Thumbnail Selector */}
        {allImages.length > 1 && (
          <div className="flex space-x-3 overflow-x-auto pb-2 scrollbar-none">
            {allImages.map((img, idx) => (
              <button
                key={idx}
                onClick={() => setActiveImage(img)}
                className={`w-20 h-24 relative bg-[#F5F3EF] border transition-all shrink-0 cursor-pointer ${
                  activeImage === img ? 'border-[#B8712E] ring-1 ring-[#B8712E]' : 'border-[#DEDBD4] opacity-70 hover:opacity-100'
                }`}
              >
                <img src={img} alt={`${product.name} view ${idx + 1}`} className="absolute inset-0 w-full h-full object-contain p-2" />
              </button>
            ))}
          </div>
        )}
      </div>

      {/* Right: Product Details, Pricing, Size & Modern Classic Purchase Actions */}
      <div className="lg:col-span-5 space-y-7">
        <div className="border-b border-[#DEDBD4] pb-6 space-y-3">
          <div className="flex items-center justify-between">
            <span className="text-[11px] uppercase tracking-[0.18em] text-[#B8712E] font-semibold block">
              {product.concentration || 'EXTRAIT DE PARFUM'} {product.scent_family ? `· ${product.scent_family}` : ''}
            </span>
            <span className="inline-flex items-center gap-1.5 text-[10px] uppercase font-bold tracking-widest text-emerald-700 bg-emerald-50 border border-emerald-200 px-2.5 py-0.5 rounded-full">
              <span className="w-1.5 h-1.5 rounded-full bg-emerald-500 animate-pulse"></span>
              IN STOCK
            </span>
          </div>

          <h1 className="font-serif text-[34px] md:text-[42px] text-[#0A0A0A] font-semibold leading-tight tracking-tight">
            {product.name}
          </h1>

          {/* Pricing Header */}
          <div className="flex items-baseline gap-3 pt-1">
            <span className="text-[24px] font-sans font-bold text-[#0A0A0A] tracking-tight">
              {formattedUnitPrice} <span className="text-[13px] font-normal text-[#6E6B66]">USD</span>
            </span>
            {quantity > 1 && (
              <span className="text-[13px] text-[#B8712E] font-semibold tracking-wide">
                ({totalPrice} total)
              </span>
            )}
            <span className="text-[11px] text-[#6E6B66] uppercase tracking-wider pl-2 border-l border-[#DEDBD4]">
              Taxes Included · Free Shipping
            </span>
          </div>

          <p className="text-[13px] text-[#4A4744] font-normal leading-relaxed pt-2">
            {product.description || product.short_description}
          </p>
        </div>

        {/* Size / Variant Selector */}
        {(hasVariants || (product.sizes && product.sizes.length > 0)) && (
          <div className="space-y-3">
            <div className="flex justify-between items-center">
              <span className="text-[11px] uppercase tracking-[0.14em] font-bold text-[#0A0A0A]">
                SELECT FLACON SIZE:
              </span>
              <span className="text-[11px] text-[#6E6B66] uppercase tracking-wider font-mono">
                {selectedSize}
              </span>
            </div>
            <div className="flex flex-wrap gap-2.5">
              {hasVariants ? (
                product.variants.map((v: any) => (
                  <button
                    key={v.id}
                    type="button"
                    onClick={() => setSelectedVariant(v)}
                    className={`px-5 py-2.5 text-[12px] font-bold uppercase tracking-wider transition-all cursor-pointer border ${
                      selectedVariant?.id === v.id
                        ? 'bg-[#0A0A0A] text-white border-[#0A0A0A] shadow-xs ring-1 ring-[#0A0A0A]'
                        : 'bg-[#FAF8F5] text-[#0A0A0A] border-[#DEDBD4] hover:border-[#0A0A0A] hover:bg-white'
                    }`}
                  >
                    {v.name}
                  </button>
                ))
              ) : (
                product.sizes.map((sz: string) => (
                  <button
                    key={sz}
                    type="button"
                    onClick={() => setSelectedSize(sz)}
                    className={`px-5 py-2.5 text-[12px] font-bold uppercase tracking-wider transition-all cursor-pointer border ${
                      selectedSize === sz
                        ? 'bg-[#0A0A0A] text-white border-[#0A0A0A] shadow-xs ring-1 ring-[#0A0A0A]'
                        : 'bg-[#FAF8F5] text-[#0A0A0A] border-[#DEDBD4] hover:border-[#0A0A0A] hover:bg-white'
                    }`}
                  >
                    {sz}
                  </button>
                ))
              )}
            </div>
          </div>
        )}

        {/* Quantity & CTA Buttons Section */}
        <div className="space-y-4 pt-1">
          <div className="flex items-center gap-4">
            {/* Quantity Stepper */}
            <div className="flex items-center border border-[#DEDBD4] bg-[#FAF8F5] shrink-0">
              <button
                type="button"
                onClick={() => setQuantity(prev => Math.max(1, prev - 1))}
                disabled={quantity <= 1}
                className="w-11 h-12 flex items-center justify-center text-[#0A0A0A] hover:bg-white disabled:opacity-40 disabled:hover:bg-transparent transition-colors cursor-pointer text-[15px] font-bold"
                aria-label="Decrease quantity"
              >
                −
              </button>
              <span className="w-12 text-center text-[13px] font-bold font-mono text-[#0A0A0A]">
                {quantity}
              </span>
              <button
                type="button"
                onClick={() => setQuantity(prev => Math.min(10, prev + 1))}
                disabled={quantity >= 10}
                className="w-11 h-12 flex items-center justify-center text-[#0A0A0A] hover:bg-white disabled:opacity-40 disabled:hover:bg-transparent transition-colors cursor-pointer text-[15px] font-bold"
                aria-label="Increase quantity"
              >
                +
              </button>
            </div>

            {/* Primary Action: Modern Classic ADD TO BAG Button */}
            <button
              type="button"
              onClick={handleAddToCart}
              className={`flex-1 h-12 px-6 text-[12px] uppercase font-bold tracking-[0.16em] transition-all duration-300 shadow-sm flex items-center justify-center gap-2.5 cursor-pointer relative overflow-hidden shimmer-btn ${
                isAdded
                  ? 'bg-emerald-700 text-white border border-emerald-700'
                  : 'bg-[#0A0A0A] text-white hover:bg-[#B8712E] border border-[#0A0A0A] hover:border-[#B8712E]'
              }`}
            >
              {isAdded ? (
                <>
                  <Check size={16} className="animate-bounce" />
                  <span>ADDED TO BAG</span>
                </>
              ) : (
                <>
                  <ShoppingBag size={16} />
                  <span>ADD TO BAG — {totalPrice}</span>
                </>
              )}
            </button>
          </div>

          {/* Secondary Action: BUY NOW / EXPRESS CHECKOUT Button */}
          <button
            type="button"
            onClick={handleBuyNow}
            disabled={isBuyingNow}
            className="w-full h-12 bg-gradient-to-r from-[#B8712E] to-[#9E5F24] hover:from-[#A56325] hover:to-[#8B501B] text-white text-[12px] uppercase font-bold tracking-[0.18em] transition-all duration-300 shadow-md flex items-center justify-center gap-2 cursor-pointer hover:shadow-lg active:scale-[0.99]"
          >
            <Zap size={16} className="fill-current text-white/90" />
            <span>{isBuyingNow ? 'PROCEEDING...' : 'BUY NOW · EXPRESS CHECKOUT'}</span>
          </button>

          {/* Discreet Concierge Inquire Link */}
          <div className="text-center pt-1">
            <a
              href={`https://wa.me/?text=Hello%20ReXxo%20Bd,%20I%20would%20like%20to%20inquire%20about%20${encodeURIComponent(product.name)}`}
              target="_blank"
              rel="noopener noreferrer"
              className="inline-flex items-center gap-1.5 text-[11px] uppercase tracking-[0.12em] font-medium text-[#6E6B66] hover:text-[#B8712E] transition-colors"
            >
              <MessageCircle size={13} />
              <span>Questions about this formulation? Ask Scent Concierge</span>
            </a>
          </div>
        </div>

        {/* Guarantee Badges */}
        <div className="grid grid-cols-3 gap-2 border-y border-[#DEDBD4] py-4 text-center text-[11px] text-[#6E6B66] uppercase tracking-wider">
          <div className="flex flex-col items-center gap-1">
            <Truck size={16} className="text-[#B8712E]" />
            <span>Artisanal Delivery</span>
          </div>
          <div className="flex flex-col items-center gap-1">
            <ShieldCheck size={16} className="text-[#B8712E]" />
            <span>Hand-Distilled Formula</span>
          </div>
          <div className="flex flex-col items-center gap-1">
            <RefreshCw size={16} className="text-[#B8712E]" />
            <span>Signature Glass Vessel</span>
          </div>
        </div>

        {/* Accordions */}
        <div className="divide-y divide-[#DEDBD4] border-b border-[#DEDBD4]">
          {/* Notes Accordion */}
          <div className="py-4">
            <button
              onClick={() => toggleAccordion('notes')}
              className="w-full flex justify-between items-center text-left text-[12px] uppercase font-semibold tracking-wider text-[#0A0A0A]"
            >
              <span>OLFACTORY SCENT PYRAMID & NOTES</span>
              {activeAccordion === 'notes' ? <ChevronUp size={16} /> : <ChevronDown size={16} />}
            </button>
            {activeAccordion === 'notes' && (
              <div className="pt-4 text-[13px] text-[#6E6B66] space-y-3 font-light animate-fade-in">
                <p><strong>TOP NOTES:</strong> {product.notes_top || 'Calabrian Bergamot, Cardamom'}</p>
                <p><strong>HEART NOTES:</strong> {product.notes_heart || 'Damask Rose, Amber Resin'}</p>
                <p><strong>BASE NOTES:</strong> {product.notes_base || 'Haitian Vetiver, Bourbon Vanilla'}</p>
              </div>
            )}
          </div>

          {/* Scent Ritual Accordion */}
          <div className="py-4">
            <button
              onClick={() => toggleAccordion('ritual')}
              className="w-full flex justify-between items-center text-left text-[12px] uppercase font-semibold tracking-wider text-[#0A0A0A]"
            >
              <span>APPLICATION & SCENT RITUAL</span>
              {activeAccordion === 'ritual' ? <ChevronUp size={16} /> : <ChevronDown size={16} />}
            </button>
            {activeAccordion === 'ritual' && (
              <div className="pt-4 text-[13px] text-[#6E6B66] font-light leading-relaxed animate-fade-in">
                Apply one pulse on neck, wrists, and inner elbows. The oil-rich extrait relies on body heat to diffuse amber and rose molecules continuously for over 12 hours.
              </div>
            )}
          </div>

          {/* Shipping Accordion */}
          <div className="py-4">
            <button
              onClick={() => toggleAccordion('shipping')}
              className="w-full flex justify-between items-center text-left text-[12px] uppercase font-semibold tracking-wider text-[#0A0A0A]"
            >
              <span>CONCIERGE & PACKAGING</span>
              {activeAccordion === 'shipping' ? <ChevronUp size={16} /> : <ChevronDown size={16} />}
            </button>
            {activeAccordion === 'shipping' && (
              <div className="pt-4 text-[13px] text-[#6E6B66] font-light leading-relaxed animate-fade-in">
                Dispatched in signature black cushioned boxes with hand-stamped wax seals.
              </div>
            )}
          </div>
        </div>
      </div>
    </div>
  );
}
