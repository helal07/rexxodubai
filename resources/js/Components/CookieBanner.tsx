'use client';

import React, { useState, useEffect } from 'react';
import { Link } from '@inertiajs/react';
import { X } from 'lucide-react';

export default function CookieBanner() {
  const [showBanner, setShowBanner] = useState(false);
  const [mounted, setMounted] = useState(false);

  useEffect(() => {
    setMounted(true);
    const consent = localStorage.getItem('prada_cookie_consent');
    if (!consent) {
      setShowBanner(true);
    }
  }, []);

  if (!mounted || !showBanner) return null;

  const handleAcceptAll = () => {
    localStorage.setItem('prada_cookie_consent', 'accepted');
    setShowBanner(false);
  };

  const handleReject = () => {
    localStorage.setItem('prada_cookie_consent', 'rejected');
    setShowBanner(false);
  };

  return (
    <div className="fixed bottom-0 left-0 w-full bg-[#0A0A0A] text-white border-t border-white/20 z-50 p-6 shadow-2xl animate-fade-in">
      <div className="max-w-[1440px] mx-auto flex flex-col lg:flex-row items-start lg:items-center justify-between gap-6">
        <div className="space-y-2 max-w-4xl">
          <span className="text-[10px] uppercase tracking-[0.2em] font-semibold text-[#B8712E] block">
            COOKIES & PRIVACY PREFERENCES
          </span>
          <p className="text-[12px] text-[#DEDBD4] font-light leading-relaxed">
            ReXxo Bd uses cookies and similar tracking technologies to ensure optimal site navigation, analyze audience traffic, and deliver personalized perfume recommendations. You can manage your preferences or accept all cookies below. Read our{' '}
            <Link href="/privacy" className="underline hover:text-[#B8712E]">
              Privacy Policy
            </Link>{' '}
            for full details.
          </p>
        </div>

        <div className="flex flex-wrap items-center gap-3 shrink-0">
          <button
            onClick={handleReject}
            className="px-5 py-2.5 text-[11px] uppercase font-bold tracking-[0.14em] border border-[#DEDBD4] text-white hover:bg-white hover:text-[#0A0A0A] transition-colors"
          >
            REJECT NON-ESSENTIAL
          </button>
          <button
            onClick={handleAcceptAll}
            className="px-6 py-2.5 text-[11px] uppercase font-bold tracking-[0.14em] bg-[#B8712E] text-white hover:bg-white hover:text-[#0A0A0A] transition-colors shadow-sm"
          >
            ACCEPT ALL COOKIES
          </button>
          <button
            onClick={() => setShowBanner(false)}
            className="p-2 text-[#DEDBD4] hover:text-white transition-colors"
            aria-label="Close cookies banner"
          >
            <X size={18} />
          </button>
        </div>
      </div>
    </div>
  );
}
