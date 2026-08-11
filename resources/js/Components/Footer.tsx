'use client';

import React, { useState } from 'react';
import { Link, usePage } from '@inertiajs/react';
import {
  MapPin,
  ArrowRight,
  Check
} from 'lucide-react';

export default function Footer() {
  const { siteSettings, apiSettings, cmsData }: any = usePage().props;
  const oldSettings = siteSettings || apiSettings || {};
  
  const global = cmsData?.global || {};
  const footerCms = cmsData?.footer || {};
  const settings = { ...oldSettings, ...global, ...footerCms };

  const [email, setEmail] = useState('');
  const [subscribed, setSubscribed] = useState(false);

  const handleSubscribe = (e: React.FormEvent) => {
    e.preventDefault();
    if (!email) return;
    setSubscribed(true);
    setEmail('');
    setTimeout(() => setSubscribed(false), 5000);
  };

  const formatUrl = (url: string) => {
    if (!url) return '#';
    if (!url.startsWith('http://') && !url.startsWith('https://')) {
      return `https://${url}`;
    }
    return url;
  };

  const businessName = settings.site_name || settings.siteName || 'RaaxO BD';
  const brandName = businessName.toUpperCase();

  return (
    <footer className="bg-white text-[#0A0A0A] border-t border-[#E5E5E5] pt-16 pb-10">
      <div className="max-w-[1440px] mx-auto px-6 grid grid-cols-1 md:grid-cols-12 gap-10 md:gap-8 pb-16 border-b border-[#E5E5E5]">
        {/* Column 1: Newsletter & Socials (Span 5 on Desktop) */}
        <div className="md:col-span-5 space-y-6">
          <h4 className="text-[12px] uppercase font-bold tracking-[0.1em] text-[#0A0A0A]">
            MUST HAVE IT? SUBSCRIBE
          </h4>

          {subscribed ? (
            <div className="flex items-center space-x-2 text-[13px] text-green-700 font-medium py-2">
              <Check className="w-4 h-4" />
              <span>Thank you for subscribing to our private communications.</span>
            </div>
          ) : (
            <form onSubmit={handleSubscribe} className="relative max-w-md">
              <div className="flex items-center border-b border-[#0A0A0A] pb-2">
                <input
                  type="email"
                  value={email}
                  onChange={(e) => setEmail(e.target.value)}
                  placeholder="Enter your email address"
                  required
                  className="w-full text-[13px] text-[#0A0A0A] placeholder-[#8E8B85] bg-transparent focus:outline-none pr-8 font-light"
                />
                <button
                  type="submit"
                  aria-label="Subscribe"
                  className="cursor-pointer hover:opacity-60 transition-opacity p-1"
                >
                  <ArrowRight className="w-4 h-4 text-[#0A0A0A]" />
                </button>
              </div>
            </form>
          )}

          {/* Social Icons Row (1:1 with Prada) */}
          <div className="flex items-center space-x-5 pt-2 text-[#0A0A0A]">
            {/* Facebook */}
            {(settings.facebook_enabled !== '0' && settings.facebook_enabled !== false) && (
            <a
              href={formatUrl(settings.facebook_url || "https://facebook.com")}
              target="_blank"
              rel="noreferrer"
              aria-label="Facebook"
              className="hover:opacity-60 transition-opacity"
            >
              <svg className="w-4 h-4 fill-current" viewBox="0 0 24 24">
                <path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z" />
              </svg>
            </a>
            )}

            {/* Instagram */}
            {(settings.instagram_enabled !== '0' && settings.instagram_enabled !== false) && (
            <a
              href={formatUrl(settings.instagram_url || "https://instagram.com")}
              target="_blank"
              rel="noreferrer"
              aria-label="Instagram"
              className="hover:opacity-60 transition-opacity"
            >
              <svg className="w-4 h-4 fill-current" viewBox="0 0 24 24">
                <path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zm0-2.163c-3.259 0-3.667.014-4.947.072-4.358.2-6.78 2.618-6.98 6.98-.059 1.281-.073 1.689-.073 4.948 0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98-1.281-.059-1.69-.073-4.949-.073zm0 5.838c-3.403 0-6.162 2.759-6.162 6.162s2.759 6.163 6.162 6.163 6.162-2.759 6.162-6.163c0-3.403-2.759-6.162-6.162-6.162zm0 10.162c-2.209 0-4-1.79-4-4 0-2.209 1.791-4 4-4s4 1.791 4 4c0 2.21-1.791 4-4 4zm6.406-11.845c-.796 0-1.441.645-1.441 1.44s.645 1.44 1.441 1.44c.795 0 1.439-.645 1.439-1.44s-.644-1.44-1.439-1.44z" />
94:               </svg>
95:             </a>
            )}

            {/* TikTok */}
            {(settings.tiktok_enabled !== '0' && settings.tiktok_enabled !== false) && (
            <a
              href={formatUrl(settings.tiktok_url || "https://tiktok.com")}
              target="_blank"
              rel="noreferrer"
              aria-label="TikTok"
              className="hover:opacity-60 transition-opacity"
            >
              <svg className="w-4 h-4 fill-current" viewBox="0 0 24 24">
                <path d="M12.525.02c1.31-.02 2.61-.01 3.91-.02.08 1.53.63 3.09 1.75 4.17 1.12 1.11 2.7 1.62 4.24 1.79v4.03c-1.44-.05-2.89-.35-4.2-.97-.57-.26-1.1-.59-1.62-.93-.01 2.92.01 5.84-.02 8.75-.08 1.4-.54 2.79-1.35 3.94-1.31 1.92-3.58 3.17-5.91 3.21-1.43.08-2.86-.31-4.08-1.03-2.02-1.19-3.44-3.37-3.65-5.71-.02-.5-.03-1-.01-1.49.18-1.9 1.12-3.72 2.58-4.96 1.66-1.44 3.98-2.13 6.15-1.72.02 1.48-.04 2.96-.04 4.44-.99-.32-2.15-.23-3.02.37-.63.41-1.11 1.04-1.36 1.75-.21.51-.15 1.07-.14 1.61.24 1.64 1.82 3.02 3.5 2.87 1.12-.01 2.19-.66 2.77-1.61.19-.33.4-.67.41-1.06.1-1.79.06-3.57.07-5.36.01-4.03-.01-8.05.02-12.07z" />
              </svg>
            </a>
            )}

            {/* X (formerly Twitter) */}
            {(settings.twitter_enabled !== '0' && settings.twitter_enabled !== false) && (
            <a
              href={formatUrl(settings.twitter_url || settings.x_url || "https://twitter.com")}
              target="_blank"
              rel="noreferrer"
              aria-label="X"
              className="hover:opacity-60 transition-opacity"
            >
              <svg className="w-4 h-4 fill-current" viewBox="0 0 24 24">
                <path d="M18.244 2.25h3.308l-7.227 8.26 8.502 11.24H16.17l-5.214-6.817L4.99 21.75H1.68l7.73-8.835L1.254 2.25H8.08l4.713 6.231zm-1.161 17.52h1.833L7.084 4.126H5.117z" />
              </svg>
            </a>
            )}

            {/* YouTube */}
            {(settings.youtube_enabled !== '0' && settings.youtube_enabled !== false) && (
            <a
              href={formatUrl(settings.youtube_url || "https://youtube.com")}
              target="_blank"
              rel="noreferrer"
              aria-label="YouTube"
              className="hover:opacity-60 transition-opacity"
            >
              <svg className="w-4 h-4 fill-current" viewBox="0 0 24 24">
                <path d="M23.498 6.186a3.016 3.016 0 0 0-2.122-2.136C19.505 3.545 12 3.545 12 3.545s-7.505 0-9.377.505A3.017 3.017 0 0 0 .502 6.186C0 8.07 0 12 0 12s0 3.93.502 5.814a3.016 3.016 0 0 0 2.122 2.136c1.871.505 9.376.505 9.376.505s7.505 0 9.377-.505a3.015 3.015 0 0 0 2.122-2.136C24 15.93 24 12 24 12s0-3.93-.502-5.814zM9.545 15.568V8.432L15.818 12l-6.273 3.568z" />
              </svg>
            </a>
            )}

            {/* WhatsApp */}
            {(settings.whatsapp_enabled !== '0' && settings.whatsapp_enabled !== false) && (
            <a
              href={`https://api.whatsapp.com/send?phone=${settings.contact_phone || settings.whatsapp || '8801700000000'}`}
              target="_blank"
              rel="noreferrer"
              aria-label="WhatsApp"
              className="hover:opacity-60 transition-opacity"
            >
              <svg className="w-4 h-4 fill-current" viewBox="0 0 24 24">
                <path d="M12.031 0C5.397 0 0 5.397 0 12.031c0 2.118.552 4.184 1.599 6.002L.062 24l6.126-1.503c1.765.962 3.76 1.47 5.843 1.47 6.634 0 12.031-5.397 12.031-12.031C24.062 5.397 18.665 0 12.031 0zm.007 22.029c-1.802 0-3.57-.484-5.112-1.401l-.367-.218-3.642.894.97-3.414-.239-.381A9.972 9.972 0 0 1 2.054 12.03c0-5.502 4.478-9.98 9.984-9.98 5.506 0 9.984 4.478 9.984 9.98 0 5.507-4.478 9.999-9.984 9.999zm5.474-7.489c-.3-.15-1.774-.875-2.049-.975-.275-.1-.475-.15-.675.15s-.775.975-.95 1.175-.35.225-.65.075c-.3-.15-1.267-.467-2.414-1.489-.892-.796-1.494-1.78-1.669-2.08-.175-.3-.019-.462.131-.611.135-.134.3-.35.45-.525.15-.175.2-.3-.3-.5.1-.2.05-.375-.025-.525s-.675-1.625-.925-2.225c-.244-.583-.492-.504-.675-.513-.175-.008-.375-.01-.575-.01s-.525.075-.8.375c-.275.3-1.05 1.025-1.05 2.5s1.075 2.899 1.225 3.1c.15.2 2.115 3.23 5.124 4.53 3.009 1.3 3.009.867 3.559.817.55-.05 1.774-.725 2.024-1.425.25-.7.25-1.3.175-1.425-.075-.125-.275-.2-.575-.35z" />
              </svg>
            </a>
            )}
          </div>
          {settings.about_text && (
            <div className="pt-4 text-[13px] text-[#4A4744] max-w-sm">
              <p>{settings.about_text}</p>
            </div>
          )}
        </div>

        {/* Column 2: COMPANY (Span 3 on Desktop) */}
        <div className="md:col-span-3 space-y-4">
          <h4 className="text-[11px] uppercase font-bold tracking-[0.14em] text-[#0A0A0A]">
            COMPANY
          </h4>
          <ul className="space-y-2.5 text-[13px] text-[#4A4744]">
            <li>
              <Link href="/about" className="hover:text-black transition-colors">
                About {businessName}
              </Link>
            </li>
            <li>
              <Link href="/about" className="hover:text-black transition-colors">
                Our Story & Heritage
              </Link>
            </li>
            <li>
              <Link href="/about" className="hover:text-black transition-colors">
                Sustainability & Craft
              </Link>
            </li>
            <li>
              <Link href="/contact" className="hover:text-black transition-colors">
                Work with us
              </Link>
            </li>
            <li>
              <Link href="/contact" className="hover:text-black transition-colors">
                Client Care & Contact
              </Link>
            </li>
          </ul>
        </div>

        {/* Column 3: LEGAL TERMS AND CONDITIONS (Span 4 on Desktop) */}
        <div className="md:col-span-4 space-y-4">
          <h4 className="text-[11px] uppercase font-bold tracking-[0.14em] text-[#0A0A0A]">
            LEGAL TERMS AND CONDITIONS
          </h4>
          <ul className="space-y-2.5 text-[13px] text-[#4A4744]">
            <li>
              <Link href="/terms" className="hover:text-black transition-colors">
                Legal Notice
              </Link>
            </li>
            <li>
              <Link href="/privacy" className="hover:text-black transition-colors">
                Privacy Policy
              </Link>
            </li>
            <li>
              <Link href="/cookies" className="hover:text-black transition-colors">
                Cookie Policy
              </Link>
            </li>
            <li>
              <Link href="/sitemap" className="hover:text-black transition-colors">
                Sitemap
              </Link>
            </li>
          </ul>
        </div>
      </div>

      {/* Sub-Footer Bottom Bar */}
      <div className="max-w-[1440px] mx-auto px-6 pt-6 flex flex-col md:flex-row justify-between items-center text-[11px] text-[#6E6B66] tracking-wider uppercase font-medium space-y-4 md:space-y-0">
        <div>
          ©{brandName} {new Date().getFullYear()} {settings.footerText ? `| ${settings.footerText}` : '| Fine Fragrance & Luxury Extraits'}
        </div>

        <div className="flex items-center space-x-8">
          <Link href="/contact" className="hover:text-black transition-colors">
            STORE LOCATOR
          </Link>
          <div className="flex items-center space-x-1.5 hover:text-black transition-colors cursor-pointer">
            <MapPin className="w-3.5 h-3.5" />
            <span>LOCATION: BANGLADESH / ENGLISH</span>
          </div>
        </div>
      </div>

      {/* Hardcoded Minimal Classic Developer Credit Bar */}
      <div className="max-w-[1440px] mx-auto px-6 mt-6 pt-5 border-t border-[#F0EFEB] text-[11px] text-[#8E8B85] flex flex-wrap items-center justify-between gap-4">
        <div className="flex items-center gap-2">
          <span className="font-medium tracking-wider uppercase text-[#6E6B66]">Developed By:</span>
          <a
            href="https://www.itsolution.bd"
            target="_blank"
            rel="noreferrer"
            className="font-bold text-[#0A0A0A] hover:text-[#B8712E] transition-colors uppercase tracking-widest border-b border-[#0A0A0A]/30 pb-0.5 hover:border-[#B8712E]"
          >
            IT Solution
          </a>
        </div>

        <div className="flex items-center gap-5 sm:gap-6 flex-wrap">

          <a
            href="https://api.whatsapp.com/send/?phone=1682000977"
            target="_blank"
            rel="noreferrer"
            aria-label="WhatsApp IT Solution"
            className="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-[#25D366]/10 text-[#128C7E] hover:bg-[#25D366] hover:text-white transition-all duration-300 font-medium text-[10.5px] uppercase tracking-wider"
          >
            <svg className="w-3.5 h-3.5 fill-current" viewBox="0 0 24 24">
              <path d="M12.031 0C5.397 0 0 5.397 0 12.031c0 2.118.552 4.184 1.599 6.002L.062 24l6.126-1.503c1.765.962 3.76 1.47 5.843 1.47 6.634 0 12.031-5.397 12.031-12.031C24.062 5.397 18.665 0 12.031 0zm.007 22.029c-1.802 0-3.57-.484-5.112-1.401l-.367-.218-3.642.894.97-3.414-.239-.381A9.972 9.972 0 0 1 2.054 12.03c0-5.502 4.478-9.98 9.984-9.98 5.506 0 9.984 4.478 9.984 9.98 0 5.507-4.478 9.999-9.984 9.999zm5.474-7.489c-.3-.15-1.774-.875-2.049-.975-.275-.1-.475-.15-.675.15s-.775.975-.95 1.175-.35.225-.65.075c-.3-.15-1.267-.467-2.414-1.489-.892-.796-1.494-1.78-1.669-2.08-.175-.3-.019-.462.131-.611.135-.134.3-.35.45-.525.15-.175.2-.3.3-.5.1-.2.05-.375-.025-.525s-.675-1.625-.925-2.225c-.244-.583-.492-.504-.675-.513-.175-.008-.375-.01-.575-.01s-.525.075-.8.375c-.275.3-1.05 1.025-1.05 2.5s1.075 2.899 1.225 3.1c.15.2 2.115 3.23 5.124 4.53 3.009 1.3 3.009.867 3.559.817.55-.05 1.774-.725 2.024-1.425.25-.7.25-1.3.175-1.425-.075-.125-.275-.2-.575-.35z" />
            </svg>
            <span>WhatsApp</span>
          </a>
        </div>
      </div>
    </footer>
  );
}


