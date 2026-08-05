'use client';

import React, { useState } from 'react';
import { useCart } from '@/Contexts/CartContext';
import { Link } from '@inertiajs/react';

import { CheckCircle2, ShieldCheck } from 'lucide-react';

export default function CheckoutPage() {
  const { items, subtotal, clearCart } = useCart();
  const [step, setStep] = useState<1 | 2 | 3>(1);
  const [isCompleted, setIsCompleted] = useState(false);

  const [formData, setFormData] = useState({
    email: '',
    firstName: '',
    lastName: '',
    address: '',
    city: '',
    postalCode: '',
    country: 'Bangladesh',
    paymentMethod: 'card',
  });

  const progressPercent = step === 1 ? 33 : step === 2 ? 66 : 100;

  const handleSubmitOrder = (e: React.FormEvent) => {
    e.preventDefault();
    setIsCompleted(true);
    clearCart();
  };

  if (isCompleted) {
    return (
      <div className="max-w-2xl mx-auto px-6 py-20 text-center space-y-6 animate-fade-in">
        <CheckCircle2 size={64} className="text-[#B8712E] mx-auto" />
        <span className="text-[11px] uppercase tracking-[0.2em] font-semibold text-[#B8712E] block">
          ORDER CONFIRMED · #RX-{Math.floor(100000 + Math.random() * 900000)}
        </span>
        <h1 className="font-serif text-[36px] text-[#0A0A0A]">Thank you for your order</h1>
        <p className="text-[14px] text-[#6E6B66] leading-relaxed">
          We are preparing your handcrafted fragrance bottle. A tracking link has been dispatched to <strong>{formData.email || 'your email'}</strong>.
        </p>
        <Link
          href="/"
          className="inline-block bg-[#0A0A0A] text-white px-8 py-4 text-[12px] uppercase font-bold tracking-[0.14em] hover:bg-[#B8712E] transition-colors"
        >
          RETURN TO HOME
        </Link>
      </div>
    );
  }

  if (items.length === 0) {
    return (
      <div className="max-w-xl mx-auto px-6 py-20 text-center space-y-4">
        <h1 className="font-serif text-[28px] text-[#0A0A0A]">Your bag is empty</h1>
        <p className="text-[13px] text-[#6E6B66]">Add a perfume to your shopping bag before proceeding to checkout.</p>
        <Link href="/perfumes" className="inline-block bg-[#0A0A0A] text-white px-6 py-3 text-[11px] uppercase font-bold tracking-widest">
          DISCOVER CATALOG
        </Link>
      </div>
    );
  }

  return (
    <div className="max-w-[1440px] mx-auto px-6 pt-24 pb-10">
      {/* Amber Line Progress Bar */}
      <div className="mb-10">
        <div className="flex justify-between text-[11px] font-semibold tracking-[0.12em] uppercase text-[#6E6B66] mb-2">
          <span className={step >= 1 ? 'text-[#0A0A0A]' : ''}>1. CONTACT</span>
          <span className={step >= 2 ? 'text-[#0A0A0A]' : ''}>2. SHIPPING</span>
          <span className={step >= 3 ? 'text-[#0A0A0A]' : ''}>3. PAYMENT</span>
        </div>
        <div className="w-full bg-[#DEDBD4] h-[2px]">
          <div className="bg-[#B8712E] h-[2px] transition-all duration-500" style={{ width: `${progressPercent}%` }} />
        </div>
      </div>

      <div className="grid grid-cols-1 lg:grid-cols-12 gap-12">
        {/* Left: 3-Stage Checkout Form */}
        <div className="lg:col-span-7 space-y-8">
          <form onSubmit={handleSubmitOrder} className="space-y-8">
            {/* Step 1: Contact */}
            <div className={`p-6 border ${step === 1 ? 'border-[#0A0A0A] bg-white' : 'border-[#DEDBD4] bg-[#F5F3EF]'}`}>
              <div className="flex justify-between items-center mb-4">
                <h3 className="font-serif text-[20px] uppercase text-[#0A0A0A]">CONTACT INFORMATION</h3>
                {step > 1 && (
                  <button type="button" onClick={() => setStep(1)} className="text-[11px] uppercase font-bold text-[#B8712E]">
                    EDIT
                  </button>
                )}
              </div>

              {step === 1 ? (
                <div className="space-y-4">
                  <div>
                    <label className="text-[11px] uppercase font-semibold text-[#6E6B66] block mb-1">EMAIL ADDRESS</label>
                    <input
                      type="email"
                      required
                      value={formData.email}
                      onChange={e => setFormData({ ...formData, email: e.target.value })}
                      className="w-full border border-[#DEDBD4] p-3 text-[13px] focus:outline-none focus:border-[#B8712E]"
                      placeholder="name@example.com"
                    />
                  </div>
                  <button
                    type="button"
                    onClick={() => formData.email && setStep(2)}
                    className="bg-[#0A0A0A] text-white px-6 py-3 text-[11px] uppercase font-bold tracking-widest hover:bg-[#B8712E]"
                  >
                    CONTINUE TO SHIPPING →
                  </button>
                </div>
              ) : (
                <p className="text-[13px] text-[#6E6B66]">{formData.email}</p>
              )}
            </div>

            {/* Step 2: Shipping Address */}
            <div className={`p-6 border ${step === 2 ? 'border-[#0A0A0A] bg-white' : 'border-[#DEDBD4] bg-[#F5F3EF]'}`}>
              <div className="flex justify-between items-center mb-4">
                <h3 className="font-serif text-[20px] uppercase text-[#0A0A0A]">SHIPPING DESTINATION</h3>
                {step > 2 && (
                  <button type="button" onClick={() => setStep(2)} className="text-[11px] uppercase font-bold text-[#B8712E]">
                    EDIT
                  </button>
                )}
              </div>

              {step === 2 ? (
                <div className="space-y-4">
                  <div className="grid grid-cols-2 gap-4">
                    <div>
                      <label className="text-[11px] uppercase font-semibold text-[#6E6B66] block mb-1">FIRST NAME</label>
                      <input
                        type="text"
                        required
                        value={formData.firstName}
                        onChange={e => setFormData({ ...formData, firstName: e.target.value })}
                        className="w-full border border-[#DEDBD4] p-3 text-[13px] focus:outline-none focus:border-[#B8712E]"
                      />
                    </div>
                    <div>
                      <label className="text-[11px] uppercase font-semibold text-[#6E6B66] block mb-1">LAST NAME</label>
                      <input
                        type="text"
                        required
                        value={formData.lastName}
                        onChange={e => setFormData({ ...formData, lastName: e.target.value })}
                        className="w-full border border-[#DEDBD4] p-3 text-[13px] focus:outline-none focus:border-[#B8712E]"
                      />
                    </div>
                  </div>

                  <div>
                    <label className="text-[11px] uppercase font-semibold text-[#6E6B66] block mb-1">STREET ADDRESS</label>
                    <input
                      type="text"
                      required
                      value={formData.address}
                      onChange={e => setFormData({ ...formData, address: e.target.value })}
                      className="w-full border border-[#DEDBD4] p-3 text-[13px] focus:outline-none focus:border-[#B8712E]"
                    />
                  </div>

                  <div className="grid grid-cols-2 gap-4">
                    <div>
                      <label className="text-[11px] uppercase font-semibold text-[#6E6B66] block mb-1">CITY</label>
                      <input
                        type="text"
                        required
                        value={formData.city}
                        onChange={e => setFormData({ ...formData, city: e.target.value })}
                        className="w-full border border-[#DEDBD4] p-3 text-[13px] focus:outline-none focus:border-[#B8712E]"
                      />
                    </div>
                    <div>
                      <label className="text-[11px] uppercase font-semibold text-[#6E6B66] block mb-1">POSTAL CODE</label>
                      <input
                        type="text"
                        required
                        value={formData.postalCode}
                        onChange={e => setFormData({ ...formData, postalCode: e.target.value })}
                        className="w-full border border-[#DEDBD4] p-3 text-[13px] focus:outline-none focus:border-[#B8712E]"
                      />
                    </div>
                  </div>

                  <button
                    type="button"
                    onClick={() => setStep(3)}
                    className="bg-[#0A0A0A] text-white px-6 py-3 text-[11px] uppercase font-bold tracking-widest hover:bg-[#B8712E]"
                  >
                    CONTINUE TO PAYMENT →
                  </button>
                </div>
              ) : step > 2 ? (
                <p className="text-[13px] text-[#6E6B66]">
                  {formData.firstName} {formData.lastName}, {formData.address}, {formData.city}
                </p>
              ) : null}
            </div>

            {/* Step 3: Payment */}
            <div className={`p-6 border ${step === 3 ? 'border-[#0A0A0A] bg-white' : 'border-[#DEDBD4] bg-[#F5F3EF]'}`}>
              <h3 className="font-serif text-[20px] uppercase text-[#0A0A0A] mb-4">PAYMENT METHOD</h3>
              {step === 3 && (
                <div className="space-y-4">
                  <div className="space-y-2">
                    <label className="flex items-center space-x-3 border border-[#DEDBD4] p-3 cursor-pointer">
                      <input
                        type="radio"
                        name="payment"
                        checked={formData.paymentMethod === 'card'}
                        onChange={() => setFormData({ ...formData, paymentMethod: 'card' })}
                      />
                      <span className="text-[13px] font-semibold text-[#0A0A0A]">CREDIT CARD (VISA / MASTERCARD / AMEX)</span>
                    </label>
                    <label className="flex items-center space-x-3 border border-[#DEDBD4] p-3 cursor-pointer">
                      <input
                        type="radio"
                        name="payment"
                        checked={formData.paymentMethod === 'cod'}
                        onChange={() => setFormData({ ...formData, paymentMethod: 'cod' })}
                      />
                      <span className="text-[13px] font-semibold text-[#0A0A0A]">CASH ON DELIVERY (CONCIERGE HAND-OVER)</span>
                    </label>
                  </div>

                  <div className="pt-4">
                    <button
                      type="submit"
                      className="w-full bg-[#B8712E] text-white py-4 text-[12px] uppercase font-bold tracking-[0.16em] hover:bg-[#0A0A0A] transition-colors"
                    >
                      COMPLETE ORDER — ${subtotal} USD
                    </button>
                  </div>
                </div>
              )}
            </div>
          </form>
        </div>

        {/* Right: Order Summary Sidebar */}
        <div className="lg:col-span-5 bg-[#F5F3EF] border border-[#DEDBD4] p-6 space-y-6 h-fit">
          <h3 className="font-serif text-[20px] uppercase text-[#0A0A0A] pb-4 border-b border-[#DEDBD4]">
            ORDER SUMMARY
          </h3>

          <div className="space-y-4 divide-y divide-[#DEDBD4]">
            {items.map(item => (
              <div key={`${item.id}-${item.size}`} className="pt-3 flex justify-between items-center gap-4">
                <div className="w-14 h-16 relative bg-white border border-[#DEDBD4] shrink-0">
                  <img  src={item.image} alt={item.name}  className="object-cover" />
                </div>
                <div className="flex-1">
                  <h5 className="font-serif text-[14px] text-[#0A0A0A] font-medium">{item.name}</h5>
                  <span className="text-[11px] text-[#6E6B66] uppercase">{item.size} × {item.quantity}</span>
                </div>
                <span className="text-[13px] font-semibold text-[#B8712E]">${item.price * item.quantity} USD</span>
              </div>
            ))}
          </div>

          <div className="border-t border-[#DEDBD4] pt-4 space-y-2 text-[13px]">
            <div className="flex justify-between text-[#6E6B66]">
              <span>SUBTOTAL</span>
              <span>${subtotal} USD</span>
            </div>
            <div className="flex justify-between text-[#6E6B66]">
              <span>SHIPPING</span>
              <span className="text-[#B8712E] font-semibold">COMPLIMENTARY</span>
            </div>
            <div className="flex justify-between text-[16px] font-bold text-[#0A0A0A] pt-2 border-t border-[#DEDBD4]">
              <span>TOTAL</span>
              <span className="text-[#B8712E]">${subtotal} USD</span>
            </div>
          </div>

          <div className="flex items-center gap-2 text-[11px] text-[#6E6B66] uppercase tracking-wider pt-2">
            <ShieldCheck size={16} className="text-[#B8712E]" />
            <span>256-Bit SSL Encrypted Checkout</span>
          </div>
        </div>
      </div>
    </div>
  );
}
