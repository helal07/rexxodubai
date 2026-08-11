'use client';

import React from 'react';
import { Link } from '@inertiajs/react';
import { X, Trash2, Plus, Minus, ArrowRight, ShoppingBag, ShieldCheck, Lock } from 'lucide-react';
import { useCart } from '@/Contexts/CartContext';
import { usePage } from '@inertiajs/react';

export default function CartDrawer() {
  const { items, isOpen, closeCart, removeItem, updateQuantity, subtotal, totalCount } = useCart();
  
  const { siteSettings, apiSettings }: any = usePage().props;
  const settings = siteSettings || apiSettings || {};
  const currencySymbol = settings.currency || 'USD ($)';
  const symbolMatch = currencySymbol.match(/\((.*?)\)/);
  const symbol = symbolMatch ? symbolMatch[1] : (currencySymbol.split(' ')[0] || '$');
  const fullCurrency = currencySymbol;
  const freeShippingThreshold = 200;
  const progressPercent = Math.min(100, (subtotal / freeShippingThreshold) * 100);
  const remainingForFreeShipping = Math.max(0, freeShippingThreshold - subtotal);

  if (!isOpen) return null;

  return (
    <div className="fixed inset-0 z-50 overflow-hidden animate-fade-in">
      {/* Dark Overlay */}
      <div
        className="absolute inset-0 bg-black/70 backdrop-blur-xs transition-opacity duration-300"
        onClick={closeCart}
      />

      <div className="fixed inset-y-0 right-0 max-w-full flex pl-6 sm:pl-10">
        <div className="w-screen max-w-md bg-white border-l border-[#DEDBD4] flex flex-col justify-between shadow-2xl animate-slide-in-right">
          {/* Header */}
          <div className="p-6 border-b border-[#DEDBD4] flex justify-between items-center bg-[#FAF8F5]">
            <div className="space-y-0.5">
              <div className="flex items-center gap-2">
                <ShoppingBag size={18} className="text-[#B8712E]" />
                <h2 className="font-serif text-[20px] uppercase font-bold text-[#0A0A0A] tracking-tight">SHOPPING BAG</h2>
              </div>
              <span className="text-[11px] text-[#6E6B66] uppercase tracking-widest font-mono">
                {totalCount} {totalCount === 1 ? 'ITEM' : 'ITEMS'} RESERVED
              </span>
            </div>
            <button
              onClick={closeCart}
              className="text-[#0A0A0A] hover:text-[#B8712E] p-2 transition-colors cursor-pointer rounded-full hover:bg-black/5"
              aria-label="Close cart"
            >
              <X size={22} />
            </button>
          </div>

          {/* Free Shipping Progress Bar */}
          <div className="px-6 py-3 bg-[#0A0A0A] text-white text-[11px] uppercase tracking-wider">
            <div className="flex justify-between items-center font-medium">
              {subtotal >= freeShippingThreshold ? (
                <span className="text-[#B8712E] font-bold">COMPLIMENTARY EXPRESS SHIPPING UNLOCKED</span>
              ) : (
                <span>ADD <strong className="text-[#B8712E]">{symbol}{remainingForFreeShipping.toFixed(2)} {fullCurrency}</strong> FOR FREE SHIPPING</span>
              )}
              <span className="text-[10px] text-white/60 font-mono">{Math.round(progressPercent)}%</span>
            </div>
            <div className="w-full bg-[#2A2A2A] h-1.5 mt-2 rounded-full overflow-hidden">
              <div
                className="bg-gradient-to-r from-[#B8712E] to-[#E09852] h-full transition-all duration-500 rounded-full"
                style={{ width: `${progressPercent}%` }}
              />
            </div>
          </div>

          {/* Items List */}
          <div className="flex-1 overflow-y-auto p-6 divide-y divide-[#EAE7E1] space-y-4 divide-y-reverse">
            {items.length === 0 ? (
              <div className="h-full flex flex-col items-center justify-center text-center py-16 px-4 space-y-4">
                <div className="w-16 h-16 rounded-full bg-[#FAF8F5] border border-[#DEDBD4] flex items-center justify-center text-[#B8712E]">
                  <ShoppingBag size={28} />
                </div>
                <div className="space-y-1">
                  <p className="font-serif text-[22px] text-[#0A0A0A] font-semibold">Your bag is empty</p>
                  <p className="text-[13px] text-[#6E6B66] max-w-xs font-light">Explore our curated flacons of rare floral, amber, and smoked wood extraits.</p>
                </div>
                <Link
                  href="/perfumes"
                  onClick={closeCart}
                  className="bg-[#0A0A0A] text-white px-8 py-3.5 text-[11px] uppercase tracking-[0.16em] font-bold hover:bg-[#B8712E] transition-colors shadow-sm cursor-pointer"
                >
                  DISCOVER COLLECTION
                </Link>
              </div>
            ) : (
              items.map(item => (
                <div key={`${item.id}-${item.size}`} className="pt-4 first:pt-0 flex gap-4 items-center">
                  <div className="w-20 h-24 bg-[#F6F6F6] border border-[#DEDBD4] shrink-0 flex items-center justify-center p-2 relative overflow-hidden">
                    <img src={item.image} alt={item.name} className="w-full h-full object-contain mix-blend-multiply" />
                  </div>

                  <div className="flex-1 flex flex-col justify-between min-h-[90px]">
                    <div>
                      <div className="flex justify-between items-start gap-2">
                        <h4 className="font-serif text-[15px] font-semibold text-[#0A0A0A] leading-snug line-clamp-1">{item.name}</h4>
                        <button
                          onClick={() => removeItem(item.id, item.size)}
                          className="text-[#8E8B85] hover:text-red-600 transition-colors p-1 cursor-pointer"
                          title="Remove item"
                        >
                          <Trash2 size={15} />
                        </button>
                      </div>
                      <span className="text-[11px] text-[#6E6B66] uppercase tracking-wide block font-mono">
                        {item.concentration || 'EXTRAIT'} · {item.size}
                      </span>
                    </div>

                    <div className="flex justify-between items-center mt-3">
                      {/* Quantity Stepper */}
                      <div className="flex items-center border border-[#DEDBD4] bg-[#FAF8F5]">
                        <button
                          onClick={() => updateQuantity(item.id, item.size, item.quantity - 1)}
                          className="w-7 h-7 flex items-center justify-center text-[#0A0A0A] hover:bg-white transition-colors cursor-pointer text-xs"
                          aria-label="Decrease"
                        >
                          <Minus size={11} />
                        </button>
                        <span className="w-8 text-center text-[12px] font-bold font-mono text-[#0A0A0A]">{item.quantity}</span>
                        <button
                          onClick={() => updateQuantity(item.id, item.size, item.quantity + 1)}
                          className="w-7 h-7 flex items-center justify-center text-[#0A0A0A] hover:bg-white transition-colors cursor-pointer text-xs"
                          aria-label="Increase"
                        >
                          <Plus size={11} />
                        </button>
                      </div>

                      <span className="text-[14px] font-bold text-[#0A0A0A] font-mono">
                        {symbol}{(item.price * item.quantity).toFixed(2)}
                      </span>
                    </div>
                  </div>
                </div>
              ))
            )}
          </div>

          {/* Footer Checkout Summary */}
          {items.length > 0 && (
            <div className="p-6 border-t border-[#DEDBD4] bg-[#FAF8F5] space-y-4">
              <div className="space-y-1.5">
                <div className="flex justify-between text-[15px] font-bold text-[#0A0A0A]">
                  <span className="uppercase tracking-wider">SUBTOTAL</span>
                  <span className="text-[#0A0A0A] font-mono">{symbol}{subtotal.toFixed(2)} {fullCurrency}</span>
                </div>
                <div className="flex justify-between text-[11px] text-[#6E6B66]">
                  <span>Shipping</span>
                  <span>{subtotal >= freeShippingThreshold ? 'Complimentary' : 'Calculated at checkout'}</span>
                </div>
              </div>

              <Link
                href="/checkout"
                onClick={closeCart}
                className="w-full bg-[#0A0A0A] text-white py-4 flex items-center justify-center gap-2.5 text-[12px] uppercase tracking-[0.16em] font-bold hover:bg-[#B8712E] transition-all duration-300 shadow-md cursor-pointer hover:shadow-lg"
              >
                <span>PROCEED TO CHECKOUT</span>
                <ArrowRight size={16} />
              </Link>

              <div className="flex items-center justify-center gap-4 text-[10px] uppercase tracking-wider text-[#8E8B85] pt-1">
                <span className="flex items-center gap-1">
                  <Lock size={11} /> 256-BIT ENCRYPTION
                </span>
                <span>·</span>
                <span className="flex items-center gap-1">
                  <ShieldCheck size={11} /> AUTHENTIC FORMULATION
                </span>
              </div>
            </div>
          )}
        </div>
      </div>
    </div>
  );
}
