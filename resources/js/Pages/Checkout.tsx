'use client';

import React, { useState, useEffect } from 'react';
import { usePage } from '@inertiajs/react';
import { useCart } from '@/Contexts/CartContext';
import { Link } from '@inertiajs/react';
import { 
  CheckCircle2, 
  ShieldCheck, 
  Truck, 
  Lock, 
  ShoppingBag, 
  ArrowLeft, 
  CreditCard, 
  Banknote, 
  Smartphone, 
  Sparkles, 
  Printer, 
  MessageCircle, 
  AlertCircle,
  Clock,
  MapPin
} from 'lucide-react';

// All 64 Bangladesh districts (pre-populated for instant render, updated from API)
const BD_DISTRICTS_DEFAULT = [
  // Inside Dhaka
  { name: 'Dhaka', zone: 'inside_dhaka', charge: 60 },
  { name: 'Gazipur', zone: 'inside_dhaka', charge: 60 },
  { name: 'Narayanganj', zone: 'inside_dhaka', charge: 60 },
  { name: 'Manikganj', zone: 'inside_dhaka', charge: 60 },
  { name: 'Munshiganj', zone: 'inside_dhaka', charge: 60 },
  { name: 'Narsingdi', zone: 'inside_dhaka', charge: 60 },
  { name: 'Tangail', zone: 'inside_dhaka', charge: 60 },
  { name: 'Kishoreganj', zone: 'inside_dhaka', charge: 60 },
  { name: 'Faridpur', zone: 'inside_dhaka', charge: 60 },
  // Outside Dhaka
  { name: 'Chittagong', zone: 'outside_dhaka', charge: 120 },
  { name: 'Sylhet', zone: 'outside_dhaka', charge: 120 },
  { name: 'Rajshahi', zone: 'outside_dhaka', charge: 120 },
  { name: 'Khulna', zone: 'outside_dhaka', charge: 120 },
  { name: 'Barisal', zone: 'outside_dhaka', charge: 120 },
  { name: 'Rangpur', zone: 'outside_dhaka', charge: 120 },
  { name: 'Mymensingh', zone: 'outside_dhaka', charge: 120 },
  { name: 'Comilla', zone: 'outside_dhaka', charge: 120 },
  { name: 'Noakhali', zone: 'outside_dhaka', charge: 120 },
  { name: 'Feni', zone: 'outside_dhaka', charge: 120 },
  { name: 'Lakshmipur', zone: 'outside_dhaka', charge: 120 },
  { name: 'Chandpur', zone: 'outside_dhaka', charge: 120 },
  { name: 'Brahmanbaria', zone: 'outside_dhaka', charge: 120 },
  { name: "Cox's Bazar", zone: 'outside_dhaka', charge: 120 },
  { name: 'Rangamati', zone: 'outside_dhaka', charge: 120 },
  { name: 'Khagrachhari', zone: 'outside_dhaka', charge: 120 },
  { name: 'Bandarban', zone: 'outside_dhaka', charge: 120 },
  { name: 'Jessore', zone: 'outside_dhaka', charge: 120 },
  { name: 'Satkhira', zone: 'outside_dhaka', charge: 120 },
  { name: 'Bagerhat', zone: 'outside_dhaka', charge: 120 },
  { name: 'Kushtia', zone: 'outside_dhaka', charge: 120 },
  { name: 'Chuadanga', zone: 'outside_dhaka', charge: 120 },
  { name: 'Meherpur', zone: 'outside_dhaka', charge: 120 },
  { name: 'Narail', zone: 'outside_dhaka', charge: 120 },
  { name: 'Magura', zone: 'outside_dhaka', charge: 120 },
  { name: 'Jhenaidah', zone: 'outside_dhaka', charge: 120 },
  { name: 'Bogura', zone: 'outside_dhaka', charge: 120 },
  { name: 'Chapai Nawabganj', zone: 'outside_dhaka', charge: 120 },
  { name: 'Naogaon', zone: 'outside_dhaka', charge: 120 },
  { name: 'Joypurhat', zone: 'outside_dhaka', charge: 120 },
  { name: 'Natore', zone: 'outside_dhaka', charge: 120 },
  { name: 'Pabna', zone: 'outside_dhaka', charge: 120 },
  { name: 'Sirajganj', zone: 'outside_dhaka', charge: 120 },
  { name: 'Rajbari', zone: 'outside_dhaka', charge: 120 },
  { name: 'Gopalganj', zone: 'outside_dhaka', charge: 120 },
  { name: 'Madaripur', zone: 'outside_dhaka', charge: 120 },
  { name: 'Shariatpur', zone: 'outside_dhaka', charge: 120 },
  { name: 'Habiganj', zone: 'outside_dhaka', charge: 120 },
  { name: 'Moulvibazar', zone: 'outside_dhaka', charge: 120 },
  { name: 'Sunamganj', zone: 'outside_dhaka', charge: 120 },
  { name: 'Dinajpur', zone: 'outside_dhaka', charge: 120 },
  { name: 'Thakurgaon', zone: 'outside_dhaka', charge: 120 },
  { name: 'Panchagarh', zone: 'outside_dhaka', charge: 120 },
  { name: 'Nilphamari', zone: 'outside_dhaka', charge: 120 },
  { name: 'Lalmonirhat', zone: 'outside_dhaka', charge: 120 },
  { name: 'Kurigram', zone: 'outside_dhaka', charge: 120 },
  { name: 'Gaibandha', zone: 'outside_dhaka', charge: 120 },
  { name: 'Jamalpur', zone: 'outside_dhaka', charge: 120 },
  { name: 'Sherpur', zone: 'outside_dhaka', charge: 120 },
  { name: 'Netrokona', zone: 'outside_dhaka', charge: 120 },
  { name: 'Patuakhali', zone: 'outside_dhaka', charge: 120 },
  { name: 'Bhola', zone: 'outside_dhaka', charge: 120 },
  { name: 'Barguna', zone: 'outside_dhaka', charge: 120 },
  { name: 'Pirojpur', zone: 'outside_dhaka', charge: 120 },
  { name: 'Jhalokati', zone: 'outside_dhaka', charge: 120 },
];

interface PlacedOrder {
  id: number;
  order_number: string;
  customer_name: string;
  customer_email: string;
  customer_phone: string;
  shipping_address: string;
  city: string;
  postal_code?: string;
  total_amount: number;
  status: string;
  payment_status: string;
  payment_method: string;
  created_at: string;
  items?: Array<{
    id: number;
    product_name: string;
    size: string;
    unit_price: number;
    quantity: number;
    total_price: number;
  }>;
}

export default function CheckoutPage() {
  const { items, subtotal, clearCart } = useCart();
  const { siteSettings, apiSettings }: any = usePage().props;
  const settings = siteSettings || apiSettings || {};
  const currencySymbol = settings.currency || 'USD ($)';
  const symbolMatch = currencySymbol.match(/\((.*?)\)/);
  const symbol = symbolMatch ? symbolMatch[1] : (currencySymbol.split(' ')[0] || '$');
  const fullCurrency = currencySymbol;
  
  const [step, setStep] = useState<1 | 2 | 3>(1);
  const [isSubmitting, setIsSubmitting] = useState(false);
  const [errorMessage, setErrorMessage] = useState<string | null>(null);
  const [completedOrder, setCompletedOrder] = useState<PlacedOrder | null>(null);

  // Courier charge state
  const [courierCharge, setCourierCharge] = useState<number>(60);
  const [courierZone, setCourierZone] = useState<string>('inside_dhaka');
  const [districts, setDistricts] = useState(BD_DISTRICTS_DEFAULT);
  const [isLoadingCharge, setIsLoadingCharge] = useState(false);

  const [formData, setFormData] = useState({
    firstName: '',
    lastName: '',
    email: '',
    phone: '',
    address: '',
    apartment: '',
    city: 'Dhaka',
    postalCode: '',
    country: 'Bangladesh',
    paymentMethod: 'cod' as 'cod' | 'bkash' | 'card',
    orderNotes: '',
  });

  // Load districts from API on mount (to get admin-configured charges)
  useEffect(() => {
    fetch('/api/courier-districts')
      .then(r => r.json())
      .then((data: Array<{district_name: string; charge: number; zone_type: string}>) => {
        if (Array.isArray(data) && data.length > 0) {
          setDistricts(data.map(d => ({ name: d.district_name, zone: d.zone_type, charge: d.charge })));
        }
      })
      .catch(() => {/* use defaults */});
  }, []);

  // Fetch charge whenever city changes
  useEffect(() => {
    if (!formData.city) return;
    setIsLoadingCharge(true);
    fetch(`/api/courier-charge?city=${encodeURIComponent(formData.city)}`)
      .then(r => r.json())
      .then((data: {charge: number; zone_type: string}) => {
        setCourierCharge(data.charge ?? 120);
        setCourierZone(data.zone_type ?? 'outside_dhaka');
      })
      .catch(() => {
        // Fallback: check in local list
        const found = districts.find(d => d.name.toLowerCase() === formData.city.toLowerCase());
        if (found) { setCourierCharge(found.charge); setCourierZone(found.zone); }
      })
      .finally(() => setIsLoadingCharge(false));
  }, [formData.city]);

  const grandTotal = subtotal + courierCharge;
  const progressPercent = step === 1 ? 33 : step === 2 ? 66 : 100;

  const handleStep1Submit = (e: React.FormEvent) => {
    e.preventDefault();
    if (!formData.email || !formData.phone) {
      setErrorMessage('Please provide both your email and phone number.');
      return;
    }
    setErrorMessage(null);
    setStep(2);
  };

  const handleStep2Submit = (e: React.FormEvent) => {
    e.preventDefault();
    if (!formData.firstName || !formData.address || !formData.city) {
      setErrorMessage('Please fill in your recipient name, address, and city.');
      return;
    }
    setErrorMessage(null);
    setStep(3);
  };

  const handleFinalOrderSubmit = async (e: React.FormEvent) => {
    e.preventDefault();
    setIsSubmitting(true);
    setErrorMessage(null);

    const customerFullName = `${formData.firstName} ${formData.lastName}`.trim();
    const fullAddress = formData.apartment 
      ? `${formData.address}, ${formData.apartment}` 
      : formData.address;

    const payload = {
      customer_name: customerFullName,
      customer_email: formData.email,
      customer_phone: formData.phone,
      shipping_address: fullAddress,
      city: formData.city,
      postal_code: formData.postalCode || null,
      payment_method: formData.paymentMethod,
      shipping_cost: courierCharge,
      items: items.map(item => ({
        product_id: item.id,
        size: item.size || '100ml',
        quantity: item.quantity,
      })),
    };

    try {
      const response = await fetch('/api/orders', {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
          'Accept': 'application/json',
        },
        body: JSON.stringify(payload),
      });

      const data = await response.json();

      if (!response.ok) {
        throw new Error(data.message || 'Failed to place order. Please review your details.');
      }

      // If user selected an online payment gateway (SSLCommerz, EPS, bKash)
      if (['sslcommerz', 'eps', 'bkash'].includes(formData.paymentMethod)) {
        try {
          const payRes = await fetch('/api/payment/initiate', {
            method: 'POST',
            headers: {
              'Content-Type': 'application/json',
              'Accept': 'application/json',
            },
            body: JSON.stringify({
              order_id: data.order.id,
              gateway: formData.paymentMethod,
            }),
          });
          const payData = await payRes.json();
          if (payRes.ok && payData.redirect_url) {
            clearCart();
            window.location.href = payData.redirect_url;
            return;
          }
        } catch (gatewayErr) {
          console.warn('Payment gateway redirection issue, fallback to order summary:', gatewayErr);
        }
      }

      setCompletedOrder(data.order);
      clearCart();
    } catch (err: any) {
      console.error('Order submission error:', err);
      setErrorMessage(err.message || 'An error occurred while communicating with the server. Please try again.');
    } finally {
      setIsSubmitting(false);
    }
  };

  // ── ORDER CONFIRMATION & DIGITAL RECEIPT ──
  if (completedOrder) {
    const paymentLabels: Record<string, string> = {
      cod: 'Cash on Delivery (Concierge Hand-Over)',
      bkash: 'bKash Direct Merchant Instant Checkout',
      sslcommerz: 'SSLCommerz (Cards, MFS & NetBanking)',
      eps: 'Easy Payment System (EPS Gateway)',
      card: 'Credit / Debit Card Secured',
    };

    return (
      <div className="min-h-screen bg-[#FAF8F5] text-[#0A0A0A] pt-24 pb-20 px-6">
        <div className="max-w-3xl mx-auto space-y-8 animate-fade-in">
          {/* Header Card */}
          <div className="bg-white border border-[#DEDBD4] p-8 md:p-12 text-center space-y-6 shadow-xl relative overflow-hidden">
            <div className="absolute top-0 inset-x-0 h-1.5 bg-gradient-to-r from-[#B8712E] via-[#0A0A0A] to-[#B8712E]" />
            
            <div className="w-16 h-16 rounded-full bg-emerald-50 border border-emerald-200 text-emerald-600 flex items-center justify-center mx-auto shadow-sm">
              <CheckCircle2 size={36} />
            </div>

            <div className="space-y-2">
              <span className="text-[11px] uppercase tracking-[0.22em] font-bold text-[#B8712E] font-mono block">
                ORDER OFFICIALLY RECORDED
              </span>
              <h1 className="font-serif text-[32px] md:text-[40px] text-[#0A0A0A] font-light leading-tight">
                Thank you for acquiring ReXxo Bd
              </h1>
              <p className="text-[14px] text-[#6E6B66] max-w-lg mx-auto font-light leading-relaxed">
                Your artisanal flacons are being hand-packaged with our signature black wax seal. A detailed dispatch notification has been sent to <strong>{completedOrder.customer_email}</strong>.
              </p>
            </div>

            {/* Reference Badge */}
            <div className="inline-flex items-center gap-3 bg-[#FAF8F5] border border-[#DEDBD4] px-6 py-3 rounded-md">
              <span className="text-[11px] uppercase tracking-[0.14em] text-[#6E6B66] font-semibold">ORDER REFERENCE:</span>
              <span className="text-[16px] font-mono font-bold text-[#0A0A0A] tracking-wider">{completedOrder.order_number}</span>
            </div>

            {/* Estimated Delivery Timeline */}
            <div className="grid grid-cols-1 sm:grid-cols-2 gap-4 pt-4 border-t border-[#EAE7E1] text-left">
              <div className="p-4 bg-[#FAF8F5] border border-[#DEDBD4] space-y-1">
                <span className="flex items-center gap-1.5 text-[11px] uppercase font-bold tracking-wider text-[#0A0A0A]">
                  <Truck size={14} className="text-[#B8712E]" /> DISPATCH & COURIER
                </span>
                <p className="text-[12px] text-[#6E6B66]">
                  Expected Delivery within <strong>24–48 Hours</strong> in {completedOrder.city}.
                </p>
              </div>

              <div className="p-4 bg-[#FAF8F5] border border-[#DEDBD4] space-y-1">
                <span className="flex items-center gap-1.5 text-[11px] uppercase font-bold tracking-wider text-[#0A0A0A]">
                  <Clock size={14} className="text-[#B8712E]" /> PAYMENT & STATUS
                </span>
                <p className="text-[12px] text-[#6E6B66]">
                  {paymentLabels[completedOrder.payment_method] || completedOrder.payment_method} · <span className="uppercase text-amber-700 font-bold">{completedOrder.status}</span>
                </p>
              </div>
            </div>

            {/* Itemized Receipt Table */}
            <div className="text-left space-y-3 pt-2">
              <h3 className="text-[12px] uppercase font-bold tracking-[0.16em] text-[#0A0A0A] border-b border-[#0A0A0A] pb-2">
                ACQUIRED FLACONS & FORMULATIONS
              </h3>

              <div className="divide-y divide-[#EAE7E1]">
                {completedOrder.items?.map(item => (
                  <div key={item.id} className="py-3 flex justify-between items-center text-[13px]">
                    <div>
                      <h4 className="font-serif font-medium text-[#0A0A0A]">{item.product_name}</h4>
                      <span className="text-[11px] text-[#6E6B66] uppercase font-mono">
                        {item.size} × {item.quantity} Flacon{item.quantity > 1 ? 's' : ''}
                      </span>
                    </div>
                    <span className="font-mono font-bold text-[#0A0A0A]">
                      {symbol}{Number(item.total_price).toFixed(2)}
                    </span>
                  </div>
                ))}
              </div>

              {/* Total Row */}
              <div className="border-t-2 border-[#0A0A0A] pt-4 space-y-1.5 text-[13px]">
                <div className="flex justify-between text-[#6E6B66]">
                  <span>Courier Delivery Charge</span>
                  <span className="font-mono font-semibold text-[#0A0A0A]">{symbol}{Number((completedOrder as any).shipping_cost ?? 0).toFixed(0)}</span>
                </div>
                <div className="flex justify-between text-[16px] font-bold text-[#0A0A0A]">
                  <span>TOTAL AMOUNT</span>
                  <span className="text-[#B8712E] font-mono">{symbol}{Number(completedOrder.total_amount).toFixed(2)}</span>
                </div>
              </div>
            </div>

            {/* Recipient Destination Card */}
            <div className="p-4 bg-[#FAF8F5] border border-[#DEDBD4] text-left space-y-1 text-[12px] text-[#4A4744]">
              <span className="text-[10px] uppercase font-bold tracking-widest text-[#8E8B85] block">DESTINATION:</span>
              <p className="font-semibold text-[#0A0A0A]">{completedOrder.customer_name} · {completedOrder.customer_phone}</p>
              <p>{completedOrder.shipping_address}, {completedOrder.city} {completedOrder.postal_code || ''}</p>
            </div>

            {/* Action Buttons */}
            <div className="flex flex-col sm:flex-row gap-4 pt-4">
              <button
                onClick={() => window.print()}
                className="flex-1 border border-[#0A0A0A] text-[#0A0A0A] hover:bg-[#0A0A0A] hover:text-white py-3.5 text-[12px] uppercase font-bold tracking-[0.14em] transition-all flex items-center justify-center gap-2 cursor-pointer"
              >
                <Printer size={16} /> PRINT OFFICIAL RECEIPT
              </button>

              <a
                href={`https://wa.me/?text=Hello%20ReXxo%20Bd,%20I%20have%20just%20placed%20order%20%23${completedOrder.order_number}%20for%20${symbol}${completedOrder.total_amount}.`}
                target="_blank"
                rel="noopener noreferrer"
                className="flex-1 bg-emerald-700 hover:bg-emerald-800 text-white py-3.5 text-[12px] uppercase font-bold tracking-[0.14em] transition-all flex items-center justify-center gap-2 shadow-sm"
              >
                <MessageCircle size={16} /> WHATSAPP VIP SUPPORT
              </a>
            </div>

            <div className="pt-2">
              <Link
                href="/perfumes"
                className="inline-block text-[12px] uppercase font-bold tracking-[0.14em] text-[#6E6B66] hover:text-[#0A0A0A] border-b border-[#DEDBD4] pb-0.5"
              >
                ← CONTINUE BROWSING CATALOG
              </Link>
            </div>
          </div>
        </div>
      </div>
    );
  }

  // ── EMPTY CART STATE ──
  if (items.length === 0) {
    return (
      <div className="min-h-[70vh] flex items-center justify-center px-6 py-20 bg-white">
        <div className="max-w-md mx-auto text-center space-y-6 animate-fade-in">
          <div className="w-20 h-20 rounded-full bg-[#FAF8F5] border border-[#DEDBD4] flex items-center justify-center mx-auto text-[#B8712E]">
            <ShoppingBag size={36} />
          </div>
          <div className="space-y-2">
            <h1 className="font-serif text-[28px] text-[#0A0A0A] font-medium">Your shopping bag is empty</h1>
            <p className="text-[13px] text-[#6E6B66] leading-relaxed">
              Select your signature perfume extrait from our luxury collections before proceeding to checkout.
            </p>
          </div>
          <Link
            href="/perfumes"
            className="inline-block bg-[#0A0A0A] text-white px-8 py-4 text-[12px] uppercase font-bold tracking-[0.16em] hover:bg-[#B8712E] transition-colors shadow-md"
          >
            EXPLORE PERFUME FLACONS
          </Link>
        </div>
      </div>
    );
  }

  // ── ACTIVE 3-STAGE CHECKOUT FLOW ──
  return (
    <div className="min-h-screen bg-white text-[#0A0A0A] pt-24 pb-20 px-6">
      <div className="max-w-[1440px] mx-auto">
        {/* Back Link & Title */}
        <div className="mb-8 space-y-3">
          <Link
            href="/perfumes"
            className="inline-flex items-center gap-2 text-[11px] uppercase tracking-[0.14em] font-semibold text-[#6E6B66] hover:text-[#0A0A0A] transition-colors"
          >
            <ArrowLeft size={14} /> Back to Catalog
          </Link>
          <div className="flex flex-col md:flex-row md:items-end justify-between gap-4 border-b border-[#DEDBD4] pb-6">
            <div>
              <span className="text-[11px] uppercase tracking-[0.2em] font-bold text-[#B8712E] block">
                AUTHENTIC BOUTIQUE DISPATCH
              </span>
              <h1 className="font-serif text-[32px] md:text-[42px] font-light text-[#0A0A0A] tracking-tight">
                Secure Luxury Checkout
              </h1>
            </div>
            <div className="flex items-center gap-3 text-[11px] text-[#6E6B66] uppercase font-mono">
              <Lock size={14} className="text-emerald-700" />
              <span>256-Bit TLS End-to-End Encryption</span>
            </div>
          </div>
        </div>

        {/* 3-Stage Progress Indicator */}
        <div className="mb-10">
          <div className="flex justify-between text-[11px] font-bold tracking-[0.14em] uppercase mb-2">
            <span className={step >= 1 ? 'text-[#0A0A0A]' : 'text-[#8E8B85]'}>
              1. CONTACT & PHONE
            </span>
            <span className={step >= 2 ? 'text-[#0A0A0A]' : 'text-[#8E8B85]'}>
              2. SHIPPING DESTINATION
            </span>
            <span className={step >= 3 ? 'text-[#0A0A0A]' : 'text-[#8E8B85]'}>
              3. PAYMENT & CONFIRMATION
            </span>
          </div>
          <div className="w-full bg-[#EAE7E1] h-[3px] rounded-full overflow-hidden">
            <div
              className="bg-gradient-to-r from-[#0A0A0A] to-[#B8712E] h-full transition-all duration-500 rounded-full"
              style={{ width: `${progressPercent}%` }}
            />
          </div>
        </div>

        {/* Error Notification Alert */}
        {errorMessage && (
          <div className="mb-8 p-4 bg-red-50 border border-red-200 text-red-700 text-[13px] rounded-sm flex items-center gap-3 animate-fade-in">
            <AlertCircle size={18} className="shrink-0" />
            <span>{errorMessage}</span>
          </div>
        )}

        <div className="grid grid-cols-1 lg:grid-cols-12 gap-12 items-start">
          {/* Left Column: 3-Stage Accordion Form */}
          <div className="lg:col-span-7 space-y-6">
            
            {/* ── STAGE 1: CONTACT INFORMATION ── */}
            <div className={`border transition-all duration-300 ${
              step === 1 ? 'border-[#0A0A0A] bg-white shadow-md' : 'border-[#DEDBD4] bg-[#FAF8F5]'
            }`}>
              <div className="p-6 flex justify-between items-center border-b border-[#EAE7E1]">
                <div className="flex items-center gap-3">
                  <span className={`w-7 h-7 rounded-full flex items-center justify-center text-[12px] font-mono font-bold ${
                    step >= 1 ? 'bg-[#0A0A0A] text-white' : 'bg-[#DEDBD4] text-[#6E6B66]'
                  }`}>
                    1
                  </span>
                  <h3 className="font-serif text-[18px] uppercase font-semibold text-[#0A0A0A]">
                    CONTACT & CLIENT DETAILS
                  </h3>
                </div>
                {step > 1 && (
                  <button
                    type="button"
                    onClick={() => setStep(1)}
                    className="text-[11px] uppercase font-bold tracking-wider text-[#B8712E] hover:underline cursor-pointer"
                  >
                    EDIT
                  </button>
                )}
              </div>

              {step === 1 ? (
                <form onSubmit={handleStep1Submit} className="p-6 space-y-5">
                  <div className="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                      <label className="text-[11px] uppercase font-bold tracking-wider text-[#0A0A0A] block mb-1.5">
                        EMAIL ADDRESS <span className="text-[#B8712E]">*</span>
                      </label>
                      <input
                        type="email"
                        required
                        value={formData.email}
                        onChange={e => setFormData({ ...formData, email: e.target.value })}
                        className="w-full border border-[#DEDBD4] p-3 text-[13px] bg-white text-[#0A0A0A] focus:outline-none focus:border-[#0A0A0A] focus:ring-1 focus:ring-[#0A0A0A] transition-all"
                        placeholder="client@domain.com"
                      />
                      <span className="text-[10px] text-[#8E8B85] mt-1 block">Dispatch receipt and tracking link will be sent here.</span>
                    </div>

                    <div>
                      <label className="text-[11px] uppercase font-bold tracking-wider text-[#0A0A0A] block mb-1.5">
                        PHONE NUMBER (FOR COURIER) <span className="text-[#B8712E]">*</span>
                      </label>
                      <input
                        type="tel"
                        required
                        value={formData.phone}
                        onChange={e => setFormData({ ...formData, phone: e.target.value })}
                        className="w-full border border-[#DEDBD4] p-3 text-[13px] bg-white text-[#0A0A0A] focus:outline-none focus:border-[#0A0A0A] focus:ring-1 focus:ring-[#0A0A0A] transition-all font-mono"
                        placeholder="+880 1700-000000"
                      />
                      <span className="text-[10px] text-[#8E8B85] mt-1 block">Required for delivery agent coordination.</span>
                    </div>
                  </div>

                  <div className="pt-2">
                    <button
                      type="submit"
                      className="w-full sm:w-auto bg-[#0A0A0A] text-white px-8 py-3.5 text-[12px] uppercase font-bold tracking-[0.16em] hover:bg-[#B8712E] transition-colors shadow-sm cursor-pointer"
                    >
                      CONTINUE TO SHIPPING DESTINATION →
                    </button>
                  </div>
                </form>
              ) : (
                <div className="p-6 text-[13px] text-[#4A4744] flex flex-wrap gap-x-6 gap-y-1">
                  <span><strong>Email:</strong> {formData.email}</span>
                  <span><strong>Phone:</strong> {formData.phone}</span>
                </div>
              )}
            </div>

            {/* ── STAGE 2: SHIPPING ADDRESS ── */}
            <div className={`border transition-all duration-300 ${
              step === 2 ? 'border-[#0A0A0A] bg-white shadow-md' : 'border-[#DEDBD4] bg-[#FAF8F5]'
            }`}>
              <div className="p-6 flex justify-between items-center border-b border-[#EAE7E1]">
                <div className="flex items-center gap-3">
                  <span className={`w-7 h-7 rounded-full flex items-center justify-center text-[12px] font-mono font-bold ${
                    step >= 2 ? 'bg-[#0A0A0A] text-white' : 'bg-[#DEDBD4] text-[#6E6B66]'
                  }`}>
                    2
                  </span>
                  <h3 className="font-serif text-[18px] uppercase font-semibold text-[#0A0A0A]">
                    SHIPPING DESTINATION & ADDRESS
                  </h3>
                </div>
                {step > 2 && (
                  <button
                    type="button"
                    onClick={() => setStep(2)}
                    className="text-[11px] uppercase font-bold tracking-wider text-[#B8712E] hover:underline cursor-pointer"
                  >
                    EDIT
                  </button>
                )}
              </div>

              {step === 2 ? (
                <form onSubmit={handleStep2Submit} className="p-6 space-y-4">
                  <div className="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                      <label className="text-[11px] uppercase font-bold tracking-wider text-[#0A0A0A] block mb-1.5">
                        FIRST NAME <span className="text-[#B8712E]">*</span>
                      </label>
                      <input
                        type="text"
                        required
                        value={formData.firstName}
                        onChange={e => setFormData({ ...formData, firstName: e.target.value })}
                        className="w-full border border-[#DEDBD4] p-3 text-[13px] bg-white focus:outline-none focus:border-[#0A0A0A]"
                        placeholder="e.g. Tariq"
                      />
                    </div>
                    <div>
                      <label className="text-[11px] uppercase font-bold tracking-wider text-[#0A0A0A] block mb-1.5">
                        LAST NAME
                      </label>
                      <input
                        type="text"
                        value={formData.lastName}
                        onChange={e => setFormData({ ...formData, lastName: e.target.value })}
                        className="w-full border border-[#DEDBD4] p-3 text-[13px] bg-white focus:outline-none focus:border-[#0A0A0A]"
                        placeholder="e.g. Ahmed"
                      />
                    </div>
                  </div>

                  <div>
                    <label className="text-[11px] uppercase font-bold tracking-wider text-[#0A0A0A] block mb-1.5">
                      STREET ADDRESS & HOUSE NUMBER <span className="text-[#B8712E]">*</span>
                    </label>
                    <input
                      type="text"
                      required
                      value={formData.address}
                      onChange={e => setFormData({ ...formData, address: e.target.value })}
                      className="w-full border border-[#DEDBD4] p-3 text-[13px] bg-white focus:outline-none focus:border-[#0A0A0A]"
                      placeholder="e.g. House 42, Road 11, Banani / Gulshan"
                    />
                  </div>

                  <div className="grid grid-cols-1 sm:grid-cols-3 gap-4">
                    <div>
                      <label className="text-[11px] uppercase font-bold tracking-wider text-[#0A0A0A] block mb-1.5">
                        APARTMENT / SUITE
                      </label>
                      <input
                        type="text"
                        value={formData.apartment}
                        onChange={e => setFormData({ ...formData, apartment: e.target.value })}
                        className="w-full border border-[#DEDBD4] p-3 text-[13px] bg-white focus:outline-none focus:border-[#0A0A0A]"
                        placeholder="e.g. Apt 4B"
                      />
                    </div>
                    <div>
                      <label className="text-[11px] uppercase font-bold tracking-wider text-[#0A0A0A] block mb-1.5">
                        CITY / DISTRICT <span className="text-[#B8712E]">*</span>
                      </label>
                      <div className="relative">
                        <select
                          required
                          value={formData.city}
                          onChange={e => setFormData({ ...formData, city: e.target.value })}
                          className="w-full border border-[#DEDBD4] p-3 text-[13px] bg-white focus:outline-none focus:border-[#0A0A0A] appearance-none pr-8 cursor-pointer"
                        >
                          <option value="" disabled>— Select District —</option>
                          <optgroup label={`Inside Dhaka (${symbol}60)`}>
                            {districts.filter(d => d.zone === 'inside_dhaka').map(d => (
                              <option key={d.name} value={d.name}>{d.name} — {symbol}{d.charge}</option>
                            ))}
                          </optgroup>
                          <optgroup label={`Outside Dhaka (${symbol}120)`}>
                            {districts.filter(d => d.zone === 'outside_dhaka').map(d => (
                              <option key={d.name} value={d.name}>{d.name} — {symbol}{d.charge}</option>
                            ))}
                          </optgroup>
                          {districts.filter(d => d.zone === 'custom').length > 0 && (
                            <optgroup label="Custom Zone">
                              {districts.filter(d => d.zone === 'custom').map(d => (
                                <option key={d.name} value={d.name}>{d.name} — {symbol}{d.charge}</option>
                              ))}
                            </optgroup>
                          )}
                        </select>
                        <MapPin size={14} className="absolute right-3 top-1/2 -translate-y-1/2 text-[#B8712E] pointer-events-none" />
                      </div>
                      {isLoadingCharge && (
                        <span className="text-[10px] text-[#B8712E] mt-1 block animate-pulse">Fetching delivery charge...</span>
                      )}
                      {!isLoadingCharge && formData.city && (
                        <span className="text-[10px] text-emerald-700 mt-1 block font-semibold">
                          ✓ Courier charge: {symbol}{courierCharge} ({courierZone === 'inside_dhaka' ? 'Inside Dhaka' : courierZone === 'outside_dhaka' ? 'Outside Dhaka' : 'Custom Zone'})
                        </span>
                      )}
                    </div>
                    <div>
                      <label className="text-[11px] uppercase font-bold tracking-wider text-[#0A0A0A] block mb-1.5">
                        POSTAL CODE
                      </label>
                      <input
                        type="text"
                        value={formData.postalCode}
                        onChange={e => setFormData({ ...formData, postalCode: e.target.value })}
                        className="w-full border border-[#DEDBD4] p-3 text-[13px] bg-white focus:outline-none focus:border-[#0A0A0A] font-mono"
                        placeholder="e.g. 1213"
                      />
                    </div>
                  </div>

                  <div>
                    <label className="text-[11px] uppercase font-bold tracking-wider text-[#0A0A0A] block mb-1.5">
                      SPECIAL PACKAGING / DELIVERY NOTES (OPTIONAL)
                    </label>
                    <textarea
                      rows={2}
                      value={formData.orderNotes}
                      onChange={e => setFormData({ ...formData, orderNotes: e.target.value })}
                      className="w-full border border-[#DEDBD4] p-3 text-[13px] bg-white focus:outline-none focus:border-[#0A0A0A]"
                      placeholder="e.g. Discreet luxury gift packaging with custom message"
                    />
                  </div>

                  <div className="pt-2">
                    <button
                      type="submit"
                      className="w-full sm:w-auto bg-[#0A0A0A] text-white px-8 py-3.5 text-[12px] uppercase font-bold tracking-[0.16em] hover:bg-[#B8712E] transition-colors shadow-sm cursor-pointer"
                    >
                      CONTINUE TO PAYMENT METHOD →
                    </button>
                  </div>
                </form>
              ) : step > 2 ? (
                <div className="p-6 text-[13px] text-[#4A4744] space-y-0.5">
                  <p className="font-semibold text-[#0A0A0A]">{formData.firstName} {formData.lastName}</p>
                  <p>{formData.address}{formData.apartment ? `, ${formData.apartment}` : ''}, {formData.city} {formData.postalCode}</p>
                </div>
              ) : null}
            </div>

            {/* ── STAGE 3: PAYMENT METHOD ── */}
            <div className={`border transition-all duration-300 ${
              step === 3 ? 'border-[#0A0A0A] bg-white shadow-md' : 'border-[#DEDBD4] bg-[#FAF8F5]'
            }`}>
              <div className="p-6 flex items-center gap-3 border-b border-[#EAE7E1]">
                <span className={`w-7 h-7 rounded-full flex items-center justify-center text-[12px] font-mono font-bold ${
                  step === 3 ? 'bg-[#0A0A0A] text-white' : 'bg-[#DEDBD4] text-[#6E6B66]'
                }`}>
                  3
                </span>
                <h3 className="font-serif text-[18px] uppercase font-semibold text-[#0A0A0A]">
                  PAYMENT SELECTION & ORDER PLACEMENT
                </h3>
              </div>

              {step === 3 && (
                <form onSubmit={handleFinalOrderSubmit} className="p-6 space-y-6">
                  <div className="space-y-3">
                    {/* Option 1: Cash on Delivery */}
                    <label className={`flex items-start gap-4 p-4 border transition-all cursor-pointer ${
                      formData.paymentMethod === 'cod'
                        ? 'border-[#0A0A0A] bg-[#FAF8F5] ring-1 ring-[#0A0A0A]'
                        : 'border-[#DEDBD4] hover:border-[#0A0A0A]'
                    }`}>
                      <input
                        type="radio"
                        name="payment"
                        checked={formData.paymentMethod === 'cod'}
                        onChange={() => setFormData({ ...formData, paymentMethod: 'cod' })}
                        className="mt-1 accent-[#0A0A0A]"
                      />
                      <div className="space-y-1">
                        <div className="flex items-center gap-2">
                          <Banknote size={16} className="text-[#B8712E]" />
                          <span className="text-[13px] font-bold text-[#0A0A0A] uppercase tracking-wide">
                            CASH ON DELIVERY (CONCIERGE HAND-OVER)
                          </span>
                        </div>
                        <p className="text-[12px] text-[#6E6B66] leading-relaxed">
                          Pay in cash upon receipt after physical inspection of the package and tamper-proof wax seal.
                        </p>
                      </div>
                    </label>

                    {/* Option 2: SSLCommerz */}
                    <label className={`flex items-start gap-4 p-4 border transition-all cursor-pointer ${
                      formData.paymentMethod === 'sslcommerz'
                        ? 'border-[#0A0A0A] bg-[#FAF8F5] ring-1 ring-[#0A0A0A]'
                        : 'border-[#DEDBD4] hover:border-[#0A0A0A]'
                    }`}>
                      <input
                        type="radio"
                        name="payment"
                        checked={formData.paymentMethod === 'sslcommerz'}
                        onChange={() => setFormData({ ...formData, paymentMethod: 'sslcommerz' })}
                        className="mt-1 accent-[#0A0A0A]"
                      />
                      <div className="space-y-1">
                        <div className="flex items-center gap-2">
                          <CreditCard size={16} className="text-[#0284c7]" />
                          <span className="text-[13px] font-bold text-[#0A0A0A] uppercase tracking-wide">
                            SSLCOMMERZ (CARDS, BKASH, NAGAD, ROCKET, NETBANKING)
                          </span>
                        </div>
                        <p className="text-[12px] text-[#6E6B66] leading-relaxed">
                          Pay securely via Visa, MasterCard, Amex, bKash, Nagad, Rocket, Upay, or any Bangladeshi bank.
                        </p>
                      </div>
                    </label>

                    {/* Option 3: bKash Direct Merchant */}
                    <label className={`flex items-start gap-4 p-4 border transition-all cursor-pointer ${
                      formData.paymentMethod === 'bkash'
                        ? 'border-[#0A0A0A] bg-[#FAF8F5] ring-1 ring-[#0A0A0A]'
                        : 'border-[#DEDBD4] hover:border-[#0A0A0A]'
                    }`}>
                      <input
                        type="radio"
                        name="payment"
                        checked={formData.paymentMethod === 'bkash'}
                        onChange={() => setFormData({ ...formData, paymentMethod: 'bkash' })}
                        className="mt-1 accent-[#0A0A0A]"
                      />
                      <div className="space-y-1">
                        <div className="flex items-center gap-2">
                          <Smartphone size={16} className="text-[#E2136E]" />
                          <span className="text-[13px] font-bold text-[#0A0A0A] uppercase tracking-wide">
                            bKash DIRECT MERCHANT GATEWAY
                          </span>
                        </div>
                        <p className="text-[12px] text-[#6E6B66] leading-relaxed">
                          Direct tokenized bKash instant checkout with automated order confirmation.
                        </p>
                      </div>
                    </label>

                    {/* Option 4: Easy Payment System (EPS) */}
                    <label className={`flex items-start gap-4 p-4 border transition-all cursor-pointer ${
                      formData.paymentMethod === 'eps'
                        ? 'border-[#0A0A0A] bg-[#FAF8F5] ring-1 ring-[#0A0A0A]'
                        : 'border-[#DEDBD4] hover:border-[#0A0A0A]'
                    }`}>
                      <input
                        type="radio"
                        name="payment"
                        checked={formData.paymentMethod === 'eps'}
                        onChange={() => setFormData({ ...formData, paymentMethod: 'eps' })}
                        className="mt-1 accent-[#0A0A0A]"
                      />
                      <div className="space-y-1">
                        <div className="flex items-center gap-2">
                          <Sparkles size={16} className="text-emerald-700" />
                          <span className="text-[13px] font-bold text-[#0A0A0A] uppercase tracking-wide">
                            EASY PAYMENT SYSTEM (EPS)
                          </span>
                        </div>
                        <p className="text-[12px] text-[#6E6B66] leading-relaxed">
                          Direct account, card & QR gateway authorized by Bangladesh Bank.
                        </p>
                      </div>
                    </label>
                  </div>

                  {/* Final Place Order Button */}
                  <div className="pt-4 border-t border-[#EAE7E1] space-y-3">
                    <button
                      type="submit"
                      disabled={isSubmitting}
                      className="w-full bg-[#0A0A0A] hover:bg-[#B8712E] text-white py-4 text-[13px] uppercase font-bold tracking-[0.18em] transition-all duration-300 shadow-md flex items-center justify-center gap-3 cursor-pointer disabled:opacity-50"
                    >
                      {isSubmitting ? (
                        <>
                          <span className="w-4 h-4 border-2 border-white/30 border-t-white rounded-full animate-spin"></span>
                          <span>RECORDING ARTISANAL ORDER...</span>
                        </>
                      ) : (
                        <>
                          <Sparkles size={16} className="text-[#B8712E]" />
                          <span>CONFIRM & PLACE ORDER — {symbol}{grandTotal.toFixed(2)}</span>
                        </>
                      )}
                    </button>

                    <p className="text-[11px] text-center text-[#8E8B85]">
                      By completing your order, you agree to ReXxo Bd's terms of artisanal craftsmanship and dispatch.
                    </p>
                  </div>
                </form>
              )}
            </div>
          </div>

          {/* Right Column: Sticky Order Summary & Guarantees */}
          <div className="lg:col-span-5 space-y-6 lg:sticky lg:top-28">
            <div className="bg-[#FAF8F5] border border-[#DEDBD4] p-6 space-y-6 shadow-sm">
              <div className="flex justify-between items-baseline border-b border-[#DEDBD4] pb-4">
                <h3 className="font-serif text-[18px] uppercase font-semibold text-[#0A0A0A] tracking-tight">
                  YOUR SELECTION
                </h3>
                <span className="text-[11px] font-mono text-[#6E6B66]">
                  {items.length} {items.length === 1 ? 'FLACON' : 'FLACONS'}
                </span>
              </div>

              {/* Items List */}
              <div className="space-y-4 max-h-80 overflow-y-auto pr-1 divide-y divide-[#EAE7E1]">
                {items.map(item => (
                  <div key={`${item.id}-${item.size}`} className="pt-3 first:pt-0 flex gap-4 items-center">
                    <div className="w-16 h-20 bg-white border border-[#DEDBD4] shrink-0 flex items-center justify-center p-1.5 relative overflow-hidden">
                      <img src={item.image} alt={item.name} className="w-full h-full object-contain mix-blend-multiply" />
                    </div>

                    <div className="flex-1 min-w-0">
                      <h4 className="font-serif text-[14px] font-semibold text-[#0A0A0A] truncate">{item.name}</h4>
                      <span className="text-[11px] text-[#6E6B66] uppercase block font-mono">
                        {item.concentration || 'EXTRAIT'} · {item.size}
                      </span>
                      <span className="text-[11px] text-[#8E8B85] block">Qty: {item.quantity}</span>
                    </div>

                    <span className="font-mono text-[14px] font-bold text-[#0A0A0A]">
                      {symbol}{(item.price * item.quantity).toFixed(2)}
                    </span>
                  </div>
                ))}
              </div>

              {/* Calculation Summary */}
              <div className="border-t border-[#DEDBD4] pt-4 space-y-2 text-[13px]">
                <div className="flex justify-between text-[#6E6B66]">
                  <span>Subtotal</span>
                  <span className="font-mono text-[#0A0A0A]">{symbol}{subtotal.toFixed(2)}</span>
                </div>
                <div className="flex justify-between text-[#6E6B66]">
                  <span className="flex items-center gap-1">
                    <Truck size={12} className="text-[#B8712E]" />
                    Courier Charge
                    {formData.city && (
                      <span className="text-[10px] font-mono bg-[#F5F0EA] text-[#B8712E] px-1.5 py-0.5 rounded">
                        {formData.city}
                      </span>
                    )}
                  </span>
                  {isLoadingCharge ? (
                    <span className="text-[#8E8B85] font-mono animate-pulse">...</span>
                  ) : (
                    <span className="font-mono font-bold text-[#0A0A0A]">{symbol}{courierCharge.toFixed(0)}</span>
                  )}
                </div>
                <div className="flex justify-between text-[#6E6B66]">
                  <span>Signature Gift Packaging</span>
                  <span className="text-[#B8712E] font-bold">INCLUDED</span>
                </div>
                <div className="flex justify-between text-[16px] font-bold text-[#0A0A0A] pt-3 border-t-2 border-[#0A0A0A]">
                  <span>TOTAL DUE</span>
                  <span className="text-[#B8712E] font-mono">{symbol}{grandTotal.toFixed(2)}</span>
                </div>
              </div>

              {/* Trust Badges */}
              <div className="border-t border-[#DEDBD4] pt-4 space-y-3 text-[11px] text-[#6E6B66]">
                <div className="flex items-center gap-2">
                  <ShieldCheck size={16} className="text-[#B8712E] shrink-0" />
                  <span>100% Authentic Hand-Distilled Formulations</span>
                </div>
                <div className="flex items-center gap-2">
                  <Truck size={16} className="text-[#B8712E] shrink-0" />
                  <span>Dispatched in temperature-stable cushioned coffrets</span>
                </div>
                <div className="flex items-center gap-2">
                  <Lock size={16} className="text-[#B8712E] shrink-0" />
                  <span>Bank-grade 256-Bit SSL encrypted transaction</span>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  );
}
