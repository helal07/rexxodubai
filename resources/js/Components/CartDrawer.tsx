'use client';

import React from 'react';
import { Link } from '@inertiajs/react';

import { X, Trash2, Plus, Minus, ArrowRight } from 'lucide-react';
import { useCart } from '@/Contexts/CartContext';

export default function CartDrawer() {
  const { items, isOpen, closeCart, removeItem, updateQuantity, subtotal } = useCart();
  const freeShippingThreshold = 200;
  const progressPercent = Math.min(100, (subtotal / freeShippingThreshold) * 100);

  if (!isOpen) return null;

  return (
    <div className="fixed inset-0 z-50 overflow-hidden animate-fade-in">
      {/* Dark Overlay */}
      <div
        className="absolute inset-0 bg-black/60 backdrop-blur-xs transition-opacity"
        onClick={closeCart}
      />

      <div className="fixed inset-y-0 right-0 max-w-full flex pl-10">
        <div className="w-screen max-w-md bg-white border-l border-[#DEDBD4] flex flex-col justify-between shadow-2xl">
          {/* Header */}
          <div className="p-6 border-b border-[#DEDBD4] flex justify-between items-center bg-[#F5F3EF]">
            <div>
              <h2 className="font-serif text-[22px] uppercase font-semibold text-[#0A0A0A]">SHOPPING BAG</h2>
              <span className="text-[11px] text-[#6E6B66] uppercase tracking-widest">
                {items.length} {items.length === 1 ? 'ITEM' : 'ITEMS'}
              </span>
            </div>
            <button
              onClick={closeCart}
              className="text-[#0A0A0A] hover:text-[#B8712E] p-2 transition-colors"
              aria-label="Close cart"
            >
              <X size={24} />
            </button>
          </div>

          {/* Free Shipping Bar */}
          <div className="px-6 py-3 bg-[#0A0A0A] text-white text-[11px] uppercase tracking-wider">
            {subtotal >= freeShippingThreshold ? (
              <span className="text-[#B8712E] font-bold">COMPLIMENTARY SHIPPING APPLIED</span>
            ) : (
              <span>ADD ${freeShippingThreshold - subtotal} USD MORE FOR COMPLIMENTARY SHIPPING</span>
            )}
            <div className="w-full bg-[#6E6B66] h-1 mt-2">
              <div
                className="bg-[#B8712E] h-1 transition-all duration-300"
                style={{ width: `${progressPercent}%` }}
              />
            </div>
          </div>

          {/* Items List */}
          <div className="flex-1 overflow-y-auto p-6 divide-y divide-[#DEDBD4]">
            {items.length === 0 ? (
              <div className="h-full flex flex-col items-center justify-center text-center py-12">
                <p className="font-serif text-[20px] text-[#0A0A0A] mb-2">Your bag is empty.</p>
                <p className="text-[13px] text-[#6E6B66] mb-6">Explore our curated collection of fine extraits.</p>
                <Link
                  href="/perfumes"
                  onClick={closeCart}
                  className="bg-[#0A0A0A] text-white px-6 py-3 text-[11px] uppercase tracking-[0.12em] font-semibold hover:bg-[#B8712E] transition-colors"
                >
                  DISCOVER PERFUMES
                </Link>
              </div>
            ) : (
              items.map(item => (
                <div key={`${item.id}-${item.size}`} className="py-4 flex gap-4">
                  <div className="w-20 h-24 relative bg-[#F5F3EF] border border-[#DEDBD4] shrink-0">
                    <img  src={item.image} alt={item.name}  className="object-cover" />
                  </div>

                  <div className="flex-1 flex flex-col justify-between">
                    <div>
                      <div className="flex justify-between items-start">
                        <h4 className="font-serif text-[15px] font-medium text-[#0A0A0A]">{item.name}</h4>
                        <button
                          onClick={() => removeItem(item.id, item.size)}
                          className="text-[#6E6B66] hover:text-[#B8712E] transition-colors"
                        >
                          <Trash2 size={15} />
                        </button>
                      </div>
                      <span className="text-[11px] text-[#6E6B66] uppercase tracking-wide block">
                        {item.concentration} · {item.size}
                      </span>
                    </div>

                    <div className="flex justify-between items-center mt-2">
                      <div className="flex items-center border border-[#DEDBD4]">
                        <button
                          onClick={() => updateQuantity(item.id, item.size, item.quantity - 1)}
                          className="px-2 py-1 text-[#0A0A0A] hover:bg-[#F5F3EF]"
                        >
                          <Minus size={12} />
                        </button>
                        <span className="px-3 text-[12px] font-semibold">{item.quantity}</span>
                        <button
                          onClick={() => updateQuantity(item.id, item.size, item.quantity + 1)}
                          className="px-2 py-1 text-[#0A0A0A] hover:bg-[#F5F3EF]"
                        >
                          <Plus size={12} />
                        </button>
                      </div>

                      <span className="text-[14px] font-semibold text-[#B8712E]">
                        ${item.price * item.quantity} USD
                      </span>
                    </div>
                  </div>
                </div>
              ))
            )}
          </div>

          {/* Footer Checkout */}
          {items.length > 0 && (
            <div className="p-6 border-t border-[#DEDBD4] bg-[#F5F3EF] space-y-4">
              <div className="flex justify-between text-[14px] font-semibold text-[#0A0A0A]">
                <span className="uppercase tracking-wider">SUBTOTAL</span>
                <span className="text-[#B8712E]">${subtotal} USD</span>
              </div>
              <p className="text-[11px] text-[#6E6B66]">Taxes & shipping calculated at checkout.</p>
              <Link
                href="/checkout"
                onClick={closeCart}
                className="w-full bg-[#0A0A0A] text-white py-4 flex items-center justify-center gap-2 text-[12px] uppercase tracking-[0.14em] font-semibold hover:bg-[#B8712E] transition-colors"
              >
                PROCEED TO CHECKOUT <ArrowRight size={16} />
              </Link>
            </div>
          )}
        </div>
      </div>
    </div>
  );
}
