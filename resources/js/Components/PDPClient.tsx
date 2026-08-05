'use client';

import React, { useState } from 'react';

import { Product } from '@/lib/api';
import { ChevronDown, ChevronUp, ShieldCheck, Truck, RefreshCw, PhoneCall } from 'lucide-react';
import ScentTrail from './ScentTrail';

interface PDPClientProps {
  product: Product;
}

export default function PDPClient({ product }: PDPClientProps) {
  const [selectedSize, setSelectedSize] = useState(product.sizes?.[0] || '100ml');
  const [activeImage, setActiveImage] = useState(product.primary_image_url);
  const [activeAccordion, setActiveAccordion] = useState<string | null>('notes');

  const allImages = [
    product.primary_image_url,
    ...(product.secondary_image_url ? [product.secondary_image_url] : []),
    ...(product.images?.map(i => i.image_url) || [])
  ];

  const toggleAccordion = (section: string) => {
    setActiveAccordion(prev => (prev === section ? null : section));
  };

  const handleInquire = () => {
    // Open Contact drawer via custom event or header trigger
    window.location.href = '/contact';
  };

  return (
    <div className="grid grid-cols-1 lg:grid-cols-12 gap-12 items-start">
      {/* Left: Sticky Image Gallery */}
      <div className="lg:col-span-7 space-y-4 lg:sticky lg:top-28">
        <ScentTrail className="w-full aspect-[4/5] bg-[#F5F3EF] border border-[#DEDBD4] relative overflow-hidden">
          <img
            src={activeImage}
            alt={product.name}




            className="object-cover"
          />
        </ScentTrail>

        {/* Thumbnail Selector */}
        {allImages.length > 1 && (
          <div className="flex space-x-3 overflow-x-auto pb-2">
            {allImages.map((img, idx) => (
              <button
                key={idx}
                onClick={() => setActiveImage(img)}
                className={`w-20 h-24 relative bg-[#F5F3EF] border transition-all shrink-0 ${
                  activeImage === img ? 'border-[#B8712E] ring-1 ring-[#B8712E]' : 'border-[#DEDBD4] opacity-70 hover:opacity-100'
                }`}
              >
                <img  src={img} alt={`${product.name} view ${idx + 1}`}  className="absolute inset-0 w-full h-full object-cover" />
              </button>
            ))}
          </div>
        )}
      </div>

      {/* Right: Product Details & Contact Inquire Action */}
      <div className="lg:col-span-5 space-y-8">
        <div>
          <span className="text-[11px] uppercase tracking-[0.14em] text-[#6E6B66] font-semibold block mb-2">
            {product.concentration} · {product.scent_family}
          </span>
          <h1 className="font-serif text-[36px] md:text-[44px] text-[#0A0A0A] font-light leading-tight mb-3">
            {product.name}
          </h1>
          <p className="text-[14px] text-[#6E6B66] font-light leading-relaxed">
            {product.description || product.short_description}
          </p>
        </div>

        {/* Size Selector */}
        {product.sizes && product.sizes.length > 0 && (
          <div className="space-y-3">
            <span className="text-[11px] uppercase tracking-[0.12em] font-semibold text-[#0A0A0A] block">
              BOTTLE SIZE:
            </span>
            <div className="flex space-x-3">
              {product.sizes.map(sz => (
                <button
                  key={sz}
                  onClick={() => setSelectedSize(sz)}
                  className={`px-5 py-2.5 text-[12px] font-semibold uppercase tracking-wider transition-all border ${
                    selectedSize === sz
                      ? 'bg-[#0A0A0A] text-white border-[#0A0A0A]'
                      : 'bg-white text-[#0A0A0A] border-[#DEDBD4] hover:border-[#B8712E]'
                  }`}
                >
                  {sz}
                </button>
              ))}
            </div>
          </div>
        )}

        {/* Contact Us / Inquire Button */}
        <button
          onClick={handleInquire}
          className="w-full bg-[#0A0A0A] text-white py-5 text-[12px] uppercase font-bold tracking-[0.16em] hover:bg-[#B8712E] transition-colors duration-300 shadow-md flex items-center justify-center gap-3"
        >
          <PhoneCall size={18} /> CONTACT US TO INQUIRE
        </button>

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
