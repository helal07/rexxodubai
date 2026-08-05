'use client';

import React, { useState, useEffect } from 'react';
import { Link } from '@inertiajs/react';
import { usePage } from '@inertiajs/react';
import { Search, Menu, X, Phone, Globe, Mail, MessageSquare, MessageCircle, ChevronDown, ChevronRight, ShoppingBag } from 'lucide-react';
import { useCart } from '@/Contexts/CartContext';
import { useSiteSettings } from '@/Contexts/SiteSettingsContext';
import { MenuItem, Category, FALLBACK_MENU, FALLBACK_CATEGORIES } from '@/lib/api';
import MegaMenu from '@/Components/MegaMenu';

interface HeaderProps {
  initialMenu?: MenuItem[];
  initialCategories?: Category[];
}

export default function Header({ initialMenu, initialCategories }: HeaderProps) {
  const { url: pathname } = usePage();
  const { totalCount, openCart } = useCart();
  const { settings, menuItems } = useSiteSettings();

  // Dynamic menu items and categories
  const activeMenuItems: MenuItem[] = (initialMenu && initialMenu.length > 0 && initialMenu !== FALLBACK_MENU)
    ? initialMenu
    : ((menuItems && menuItems.length > 0) ? menuItems : FALLBACK_MENU);

  const activeCategories: Category[] = (initialCategories && initialCategories.length > 0)
    ? initialCategories
    : FALLBACK_CATEGORIES;

  const [isVisible, setIsVisible] = useState(true);
  const [isScrolled, setIsScrolled] = useState(false);
  const [lastScrollY, setLastScrollY] = useState(0);
  const [menuDrawerOpen, setMenuDrawerOpen] = useState(false);
  const [contactDrawerOpen, setContactDrawerOpen] = useState(false);
  const [searchOpen, setSearchOpen] = useState(false);
  const [searchQuery, setSearchQuery] = useState('');

  // Accordion state for Drawer categories
  const [expandedCategories, setExpandedCategories] = useState<Record<number, boolean>>({});

  // Hover state for Desktop MegaMenu
  const [hoveredCategory, setHoveredCategory] = useState<Category | MenuItem | null>(null);

  const toggleCategory = (id: number) => {
    setExpandedCategories(prev => ({
      ...prev,
      [id]: !prev[id]
    }));
  };

  useEffect(() => {
    const handleScroll = () => {
      const currentScrollY = window.scrollY;
      setIsScrolled(currentScrollY > 40);

      if (currentScrollY > 120 && currentScrollY > lastScrollY) {
        setIsVisible(false);
      } else {
        setIsVisible(true);
      }
      setLastScrollY(currentScrollY);
    };

    window.addEventListener('scroll', handleScroll, { passive: true });
    return () => window.removeEventListener('scroll', handleScroll);
  }, [lastScrollY]);

  // Prevent background scroll when drawers are open
  useEffect(() => {
    if (menuDrawerOpen || contactDrawerOpen) {
      document.body.style.overflow = 'hidden';
    } else {
      document.body.style.overflow = 'unset';
    }
  }, [menuDrawerOpen, contactDrawerOpen]);

  // Use categories as primary source if available with subcategories, or menu items
  const navigationItems = (activeCategories && activeCategories.length > 0) ? activeCategories : activeMenuItems;

  return (
    <>
      {/* Dynamic Announcement Bar */}
      {settings.announcement && (
        <div className="bg-[#0A0A0A] text-white text-[11px] font-mono py-1.5 px-4 text-center tracking-widest uppercase border-b border-[#1E283D] relative z-50">
          <span>{settings.announcement}</span>
        </div>
      )}

      {/* Main Luxury Header Bar */}
      <header
        className={`fixed ${settings.announcement ? 'top-[29px]' : 'top-0'} left-0 w-full z-40 bg-white text-[#0A0A0A] border-b border-[#E5E5E5] shadow-xs transition-transform duration-300 ${
          isVisible ? 'translate-y-0' : '-translate-y-full'
        }`}
        onMouseLeave={() => setHoveredCategory(null)}
      >
        <div className="max-w-[1440px] mx-auto px-6 h-16 flex items-center justify-between">
          {/* Left Actions: Menu, Search, & Desktop Category Links */}
          <div className="flex items-center space-x-6 sm:space-x-8 flex-1">
            <button
              onClick={() => {
                setMenuDrawerOpen(true);
                setContactDrawerOpen(false);
                setSearchOpen(false);
              }}
              className="flex items-center gap-2 text-[13px] uppercase font-bold tracking-wider text-[#0A0A0A] hover:text-[#B8712E] transition-colors cursor-pointer shrink-0"
              aria-label="Open Navigation Menu"
            >
              <Menu size={18} strokeWidth={2} />
              <span>Menu</span>
            </button>

            <button
              onClick={() => {
                setSearchOpen(!searchOpen);
                setMenuDrawerOpen(false);
                setContactDrawerOpen(false);
              }}
              className="flex items-center gap-2 text-[13px] uppercase font-bold tracking-wider text-[#0A0A0A] hover:text-[#B8712E] transition-colors cursor-pointer shrink-0"
              aria-label="Search"
            >
              <Search size={18} strokeWidth={2} />
              <span className="hidden sm:inline">Search</span>
            </button>

            {/* Desktop Quick Category Links with Hover Dropdowns */}
            <nav className="hidden lg:flex items-center space-x-6 pl-4 border-l border-[#E5E5E5]">
              {navigationItems.slice(0, 5).map((cat: any) => {
                const label = cat.name || cat.label;
                const url = cat.url || `/perfumes?category=${cat.slug}`;
                const hasSub = cat.children && cat.children.length > 0;
                const isCurrentHovered = hoveredCategory && ((hoveredCategory as any).id === cat.id);

                return (
                  <div
                    key={cat.id}
                    className="relative py-4"
                    onMouseEnter={() => hasSub && setHoveredCategory(cat)}
                  >
                    <Link
                      href={url}
                      className={`text-[12px] uppercase font-bold tracking-wider transition-colors flex items-center gap-1 ${
                        isCurrentHovered ? 'text-[#B8712E]' : 'text-[#0A0A0A] hover:text-[#B8712E]'
                      }`}
                    >
                      <span>{label}</span>
                      {hasSub && <ChevronDown size={13} className={`transition-transform duration-200 ${isCurrentHovered ? 'rotate-180 text-[#B8712E]' : 'text-slate-400'}`} />}
                    </Link>
                  </div>
                );
              })}
            </nav>
          </div>

          {/* Dynamic Brand Logo / Wordmark */}
          <div className="shrink-0 text-center px-4">
            <Link
              href="/"
              onClick={() => {
                setMenuDrawerOpen(false);
                setContactDrawerOpen(false);
                setHoveredCategory(null);
              }}
              className="inline-block flex items-center justify-center group"
            >
              {(settings.logo_url || settings.site_logo) ? (
                <img
                  src={settings.logo_url || settings.site_logo}
                  alt={settings.siteName || 'Brand Logo'}
                  className="h-8 md:h-10 w-auto max-w-[220px] object-contain transition-transform group-hover:opacity-90"
                />
              ) : (
                <span className="font-serif text-[24px] md:text-[32px] tracking-[0.16em] font-extrabold uppercase text-[#0A0A0A] leading-none transition-transform group-hover:tracking-[0.18em]">
                  {settings.siteName || 'REXXO BD'}
                </span>
              )}
            </Link>
          </div>

          {/* Right Actions: Contact us & Cart */}
          <div className="flex items-center space-x-5 sm:space-x-6 flex-1 justify-end shrink-0">
            <button
              onClick={() => {
                setContactDrawerOpen(true);
                setMenuDrawerOpen(false);
                setSearchOpen(false);
              }}
              className="text-[13px] uppercase font-bold tracking-wider text-[#0A0A0A] hover:text-[#B8712E] transition-colors cursor-pointer"
            >
              Contact us
            </button>

            <button
              onClick={openCart}
              className="flex items-center gap-1.5 text-[13px] uppercase font-bold tracking-wider text-[#0A0A0A] hover:text-[#B8712E] transition-colors cursor-pointer"
              aria-label="View Shopping Cart"
            >
              <ShoppingBag size={18} strokeWidth={2} />
              <span className="bg-[#0A0A0A] text-white text-[10px] font-mono px-1.5 py-0.5 rounded-full">
                {totalCount}
              </span>
            </button>
          </div>
        </div>

        {/* Desktop MegaMenu on Hover */}
        {hoveredCategory && (
          <MegaMenu
            item={hoveredCategory}
            isOpen={Boolean(hoveredCategory)}
            onClose={() => setHoveredCategory(null)}
          />
        )}

        {/* Full-Width Search Overlay */}
        {searchOpen && (
          <div className="bg-white text-[#0A0A0A] border-t border-[#DEDBD4] py-6 px-8 shadow-xl animate-fade-in">
            <div className="max-w-3xl mx-auto space-y-4">
              <div className="flex items-center border-b-2 border-[#0A0A0A] pb-2">
                <Search size={20} className="text-[#6E6B66] mr-3 shrink-0" />
                <input
                  type="text"
                  placeholder="Search perfumes, amber, oud, rose, notes..."
                  value={searchQuery}
                  onChange={e => setSearchQuery(e.target.value)}
                  onKeyDown={e => {
                    if (e.key === 'Enter') {
                      window.location.href = `/perfumes?search=${encodeURIComponent(searchQuery)}`;
                    }
                  }}
                  className="w-full bg-transparent text-[16px] text-[#0A0A0A] focus:outline-none placeholder:text-[#6E6B66] font-medium"
                  autoFocus
                />
                {searchQuery && (
                  <button onClick={() => setSearchQuery('')} className="text-[#6E6B66] hover:text-black mr-3 p-1">
                    <X size={18} />
                  </button>
                )}
                <button
                  onClick={() => {
                    if (searchQuery) window.location.href = `/perfumes?search=${encodeURIComponent(searchQuery)}`;
                  }}
                  className="bg-[#0A0A0A] text-white text-[11px] font-bold uppercase tracking-widest px-5 py-2 hover:bg-[#B8712E] transition-colors shrink-0 cursor-pointer"
                >
                  SEARCH
                </button>
              </div>

              {/* Popular Search Tags */}
              <div className="flex flex-wrap items-center gap-2 pt-1 text-[12px] text-[#6E6B66]">
                <span className="font-semibold text-[#0A0A0A] uppercase tracking-wider text-[11px]">POPULAR SEARCHES:</span>
                {['Amber', 'Woody Leather', 'Rare Oud', 'Gifts', 'Damask Rose', 'Parfum Extraits'].map(tag => (
                  <button
                    key={tag}
                    onClick={() => {
                      window.location.href = `/perfumes?search=${encodeURIComponent(tag)}`;
                    }}
                    className="bg-[#F5F3EF] hover:bg-[#0A0A0A] hover:text-white text-[#0A0A0A] px-3 py-1 text-[11px] uppercase tracking-wider transition-colors border border-[#DEDBD4]"
                  >
                    {tag}
                  </button>
                ))}
              </div>
            </div>
          </div>
        )}
      </header>

      {/* Slide-In Navigation Drawer (Hierarchical Categories & Subcategories) */}
      {menuDrawerOpen && (
        <div className="fixed inset-0 z-50 flex animate-fade-in">
          {/* Dark Overlay Background */}
          <div
            className="fixed inset-0 bg-black/65 backdrop-blur-xs transition-opacity"
            onClick={() => setMenuDrawerOpen(false)}
          />

          {/* Left White Slide-In Navigation Panel */}
          <div className="relative w-full max-w-[380px] bg-white text-[#0A0A0A] h-full flex flex-col justify-between p-6 sm:p-8 shadow-2xl z-10 overflow-y-auto">
            {/* Top Bar: Close & Search */}
            <div className="flex justify-between items-center pb-6 border-b border-[#F5F3EF]">
              <button
                onClick={() => setMenuDrawerOpen(false)}
                className="flex items-center gap-2 text-[13px] uppercase font-bold tracking-wider text-[#0A0A0A] hover:text-[#B8712E] transition-colors cursor-pointer"
              >
                <X size={18} strokeWidth={2} />
                <span>Close</span>
              </button>

              <button
                onClick={() => {
                  setMenuDrawerOpen(false);
                  setSearchOpen(true);
                }}
                className="flex items-center gap-2 text-[13px] uppercase font-bold tracking-wider text-[#0A0A0A] hover:text-[#B8712E] transition-colors cursor-pointer"
              >
                <Search size={18} strokeWidth={2} />
                <span>Search</span>
              </button>
            </div>

            {/* Dynamic Hierarchical Categories & Subcategories Accordion */}
            <div className="py-6 flex-1 space-y-4">
              <span className="text-[10px] font-mono font-bold tracking-[0.2em] uppercase text-[#B8712E] block mb-2">
                COLLECTIONS & CATEGORIES
              </span>

              <div className="space-y-3">
                {navigationItems.map((item: any) => {
                  const label = item.name || item.label;
                  const mainUrl = item.url || `/perfumes?category=${item.slug}`;
                  const children = item.children || [];
                  const hasChildren = children.length > 0;
                  const isExpanded = expandedCategories[item.id];

                  return (
                    <div key={item.id} className="border-b border-[#F5F3EF] pb-3 last:border-b-0">
                      {hasChildren ? (
                        <div>
                          {/* Parent Category Row with Expand / Collapse Toggle */}
                          <div className="flex items-center justify-between group">
                            <Link
                              href={mainUrl}
                              onClick={() => setMenuDrawerOpen(false)}
                              className="text-[14px] font-bold uppercase tracking-wider text-[#0A0A0A] hover:text-[#B8712E] transition-colors flex-1 py-1"
                            >
                              {label}
                            </Link>

                            <button
                              type="button"
                              onClick={() => toggleCategory(item.id)}
                              className="p-2 text-slate-400 hover:text-black transition-colors cursor-pointer"
                              aria-label={`Toggle subcategories for ${label}`}
                            >
                              {isExpanded ? (
                                <ChevronDown size={18} className="text-[#B8712E]" />
                              ) : (
                                <ChevronRight size={18} />
                              )}
                            </button>
                          </div>

                          {/* Subcategories Accordion List */}
                          {isExpanded && (
                            <div className="pl-4 mt-2 space-y-2 border-l-2 border-[#B8712E]/30 animate-fade-in">
                              <Link
                                href={mainUrl}
                                onClick={() => setMenuDrawerOpen(false)}
                                className="block text-[12px] font-bold uppercase text-[#B8712E] hover:underline py-1 tracking-wider"
                              >
                                View All {label} &rarr;
                              </Link>
                              {children.map((sub: any) => {
                                const subLabel = sub.name || sub.label;
                                const subUrl = sub.url || `/perfumes?category=${sub.slug}`;
                                return (
                                  <Link
                                    key={sub.id}
                                    href={subUrl}
                                    onClick={() => setMenuDrawerOpen(false)}
                                    className="block text-[13px] text-slate-700 hover:text-[#B8712E] hover:font-semibold transition-colors py-1"
                                  >
                                    {subLabel}
                                  </Link>
                                );
                              })}
                            </div>
                          )}
                        </div>
                      ) : (
                        <Link
                          href={mainUrl}
                          onClick={() => setMenuDrawerOpen(false)}
                          className="block text-[14px] font-bold uppercase tracking-wider text-[#0A0A0A] hover:text-[#B8712E] transition-colors py-1"
                        >
                          {label}
                        </Link>
                      )}
                    </div>
                  );
                })}
              </div>

              {/* Direct Quick Link to All Fragrances */}
              <div className="pt-4 border-t border-[#F5F3EF]">
                <Link
                  href="/perfumes"
                  onClick={() => setMenuDrawerOpen(false)}
                  className="inline-flex items-center gap-2 text-[12px] font-bold uppercase tracking-[0.16em] text-[#B8712E] hover:text-black transition-colors"
                >
                  <span>Explore Full Catalog</span>
                  <span>&rarr;</span>
                </Link>
              </div>
            </div>

            {/* Bottom Utility Links */}
            <div className="pt-6 border-t border-[#DEDBD4] space-y-3 text-[13px] text-[#0A0A0A]">
              <button
                onClick={() => {
                  setMenuDrawerOpen(false);
                  setContactDrawerOpen(true);
                }}
                className="flex items-center gap-3 font-medium hover:text-[#B8712E] transition-colors w-full text-left"
              >
                <Phone size={16} />
                <span>Contact us ({settings.phone})</span>
              </button>

              <div className="flex items-center gap-3 font-medium text-[#6E6B66] cursor-pointer hover:text-[#0A0A0A] transition-colors">
                <Globe size={16} />
                <span>International / English</span>
              </div>
            </div>
          </div>
        </div>
      )}

      {/* Right Slide-In Client Service Drawer */}
      {contactDrawerOpen && (
        <div className="fixed inset-0 z-50 flex justify-end animate-fade-in">
          {/* Dark Overlay Background */}
          <div
            className="fixed inset-0 bg-black/65 backdrop-blur-xs transition-opacity"
            onClick={() => setContactDrawerOpen(false)}
          />

          {/* Right White Slide-In Panel */}
          <div className="relative w-full max-w-[480px] bg-white text-[#0A0A0A] h-full flex flex-col justify-between p-8 md:p-10 shadow-2xl z-10 overflow-y-auto">
            {/* Top Close Button */}
            <div className="flex justify-end">
              <button
                onClick={() => setContactDrawerOpen(false)}
                className="p-2 text-[#0A0A0A] hover:opacity-60 transition-opacity cursor-pointer"
                aria-label="Close Client Service"
              >
                <X size={22} />
              </button>
            </div>

            {/* Client Service Content */}
            <div className="py-4 space-y-8 flex-1">
              <h2 className="text-[26px] font-sans font-bold text-[#0A0A0A] tracking-tight">
                Client Service
              </h2>

              {/* Stacked Option Cards */}
              <div className="space-y-4">
                {/* WhatsApp Direct Message */}
                <a
                  href={`https://api.whatsapp.com/send?phone=${settings.whatsapp}&text=Hello%20${encodeURIComponent(settings.siteName)},%20I%20would%20like%20to%20inquire%20about%20your%20luxury%20perfumes.`}
                  target="_blank"
                  rel="noreferrer"
                  className="flex justify-between items-center p-5 border border-[#DEDBD4] hover:border-black transition-all bg-white group cursor-pointer"
                >
                  <div>
                    <span className="text-[15px] font-medium text-[#0A0A0A] group-hover:font-semibold block">
                      Message via WhatsApp
                    </span>
                    <span className="text-[11px] text-[#6E6B66]">Click to open WhatsApp chat directly ({settings.phone})</span>
                  </div>
                  <MessageSquare size={20} className="text-[#0A0A0A]" />
                </a>

                {/* Send message via Gmail */}
                <a
                  href={`https://mail.google.com/mail/?view=cm&fs=1&to=${settings.email}&su=Inquiry%20-%20${encodeURIComponent(settings.siteName)}%20Perfumes&body=Hello%20${encodeURIComponent(settings.siteName)}%20Client%20Service,%20I%20would%20like%20to%20inquire%20about%20your%20luxury%20perfumes.`}
                  target="_blank"
                  rel="noreferrer"
                  className="flex justify-between items-center p-5 border border-[#DEDBD4] hover:border-black transition-all bg-white group cursor-pointer"
                >
                  <div>
                    <span className="text-[15px] font-medium text-[#0A0A0A] group-hover:font-semibold block">
                      Send message via Gmail
                    </span>
                    <span className="text-[11px] text-[#6E6B66]">Opens Gmail to {settings.email}</span>
                  </div>
                  <Mail size={20} className="text-[#0A0A0A]" />
                </a>

                {/* Send via Default Email */}
                <a
                  href={`mailto:${settings.email}?subject=Inquiry%20-%20${encodeURIComponent(settings.siteName)}%20Perfumes`}
                  className="flex justify-between items-center p-5 border border-[#DEDBD4] hover:border-black transition-all bg-white group cursor-pointer"
                >
                  <div>
                    <span className="text-[15px] font-medium text-[#0A0A0A] group-hover:font-semibold block">
                      Send via Default Mail App
                    </span>
                    <span className="text-[11px] text-[#6E6B66]">{settings.email}</span>
                  </div>
                  <Mail size={20} className="text-[#6E6B66]" />
                </a>

                {/* Live Chat */}
                <div className="flex justify-between items-center p-5 border border-[#DEDBD4] bg-white opacity-60 cursor-not-allowed">
                  <div>
                    <span className="text-[15px] font-normal text-[#6E6B66] block">
                      Live Chat (Offline)
                    </span>
                    <span className="text-[11px] text-[#6E6B66]">Available Mon-Sat 9am-8pm</span>
                  </div>
                  <MessageCircle size={20} className="text-[#6E6B66]" />
                </div>
              </div>

              {/* Policy & Hours Description Note */}
              <p className="text-[12px] text-[#6E6B66] font-light leading-relaxed pt-4 border-t border-[#F5F3EF]">
                You can contact our Client Service by phone at {settings.phone} (from Monday to Saturday from 9 am to 8 pm CET), by e-mail at{' '}
                <a href={`mailto:${settings.email}`} className="underline text-[#0A0A0A]">
                  {settings.email}
                </a>
                , or per livechat.
              </p>
            </div>
          </div>
        </div>
      )}
    </>
  );
}
