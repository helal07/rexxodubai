'use client';

import React, { useState, useEffect } from 'react';
import { Link } from '@inertiajs/react';
import { usePage } from '@inertiajs/react';
import { Search, Menu, X, Phone, Globe, Mail, MessageSquare, MessageCircle, ChevronDown, ChevronRight, ShoppingBag, Sparkles, Layers } from 'lucide-react';
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

  const rawCategories: Category[] = (initialCategories && initialCategories.length > 0)
    ? initialCategories
    : FALLBACK_CATEGORIES;

  // Enrich categories with fallback subcategories if DB children are not populated
  const enrichedCategories: Category[] = rawCategories.map(cat => {
    if (cat.children && cat.children.length > 0) {
      return cat;
    }
    const fallbackMatch = FALLBACK_CATEGORIES.find(f => 
      f.slug === cat.slug || f.name.toLowerCase() === cat.name.toLowerCase()
    );
    if (fallbackMatch && fallbackMatch.children && fallbackMatch.children.length > 0) {
      return {
        ...cat,
        children: fallbackMatch.children,
        description: cat.description || fallbackMatch.description,
        image_url: cat.image_url || fallbackMatch.image_url,
      };
    }
    return cat;
  });

  const [isVisible, setIsVisible] = useState(true);
  const [isScrolled, setIsScrolled] = useState(false);
  const [isHeaderHovered, setIsHeaderHovered] = useState(false);
  const [lastScrollY, setLastScrollY] = useState(0);
  const [menuDrawerOpen, setMenuDrawerOpen] = useState(false);
  const [contactDrawerOpen, setContactDrawerOpen] = useState(false);
  const [searchOpen, setSearchOpen] = useState(false);
  const [searchQuery, setSearchQuery] = useState('');

  // Accordion state for Drawer categories
  const [expandedCategories, setExpandedCategories] = useState<Record<number | string, boolean>>({
    'products-master': true // Expand by default so subcategories are immediately visible
  });

  // Hover state for Desktop MegaMenu
  const [hoveredCategory, setHoveredCategory] = useState<Category | MenuItem | null>(null);

  const toggleCategory = (id: number | string) => {
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

  // 1:1 Prada Header Transparency Rule:
  // On Homepage, header is transparent with white text by default at top.
  // When scrolled, hovered, or search is open, or on inner pages, header becomes solid white with black text.
  const isHomePage = pathname === '/' || pathname === '' || pathname.startsWith('/?');
  const isTransparent = isHomePage && !isScrolled && !isHeaderHovered && !searchOpen && !hoveredCategory;

  // Master Products item with all subcategories flattened for MegaMenu
  const allProductsMegaItem: MenuItem = {
    id: 88888,
    parent_id: null,
    label: 'All Fragrances',
    url: '/perfumes',
    sort_order: 0,
    is_active: true,
    children: enrichedCategories.flatMap(c => (c.children && c.children.length > 0) ? c.children : [c]) as any,
  };

  return (
    <>
      {/* Dynamic Announcement Bar */}
      {settings.announcement && (
        <div
          className={`text-[11px] font-mono py-1.5 px-4 text-center tracking-widest uppercase transition-colors duration-300 relative z-50 ${
            isTransparent
              ? 'bg-black/60 text-white border-b border-white/10 backdrop-blur-xs'
              : 'bg-[#0A0A0A] text-white border-b border-[#1E283D]'
          }`}
        >
          <span>{settings.announcement}</span>
        </div>
      )}

      {/* Main Luxury Header Bar (Transparent by default on Home, White on scroll) */}
      <header
        onMouseEnter={() => setIsHeaderHovered(true)}
        onMouseLeave={() => {
          setIsHeaderHovered(false);
          setHoveredCategory(null);
        }}
        className={`fixed ${settings.announcement ? 'top-[29px]' : 'top-0'} left-0 w-full z-40 transition-all duration-300 ${
          isVisible ? 'translate-y-0' : '-translate-y-full'
        } ${
          isTransparent
            ? 'bg-transparent text-white border-b border-transparent shadow-none'
            : 'bg-white text-[#0A0A0A] border-b border-[#E5E5E5] shadow-xs'
        }`}
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
              className={`flex items-center gap-2 text-[13px] uppercase font-bold tracking-wider transition-colors cursor-pointer shrink-0 ${
                isTransparent
                  ? 'text-white hover:opacity-75 drop-shadow-xs'
                  : 'text-[#0A0A0A] hover:text-[#B8712E]'
              }`}
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
              className={`flex items-center gap-2 text-[13px] uppercase font-bold tracking-wider transition-colors cursor-pointer shrink-0 ${
                isTransparent
                  ? 'text-white hover:opacity-75 drop-shadow-xs'
                  : 'text-[#0A0A0A] hover:text-[#B8712E]'
              }`}
              aria-label="Search"
            >
              <Search size={18} strokeWidth={2} />
              <span className="hidden sm:inline">Search</span>
            </button>

            {/* Desktop Quick Category Links with Hover Dropdowns */}
            <nav className={`hidden lg:flex items-center space-x-6 pl-4 border-l transition-colors ${
              isTransparent ? 'border-white/20' : 'border-[#E5E5E5]'
            }`}>
              {/* Master Products Link */}
              <div
                className="relative py-4"
                onMouseEnter={() => setHoveredCategory(allProductsMegaItem)}
              >
                <Link
                  href="/perfumes"
                  className={`text-[12px] uppercase font-bold tracking-wider transition-colors flex items-center gap-1 ${
                    isTransparent
                      ? hoveredCategory?.id === allProductsMegaItem.id ? 'text-[#B8712E]' : 'text-white hover:opacity-80 drop-shadow-xs'
                      : hoveredCategory?.id === allProductsMegaItem.id ? 'text-[#B8712E]' : 'text-[#0A0A0A] hover:text-[#B8712E]'
                  }`}
                >
                  <span>Products</span>
                  <ChevronDown
                    size={13}
                    className={`transition-transform duration-200 ${
                      hoveredCategory?.id === allProductsMegaItem.id ? 'rotate-180 text-[#B8712E]' : isTransparent ? 'text-white/70' : 'text-slate-400'
                    }`}
                  />
                </Link>
              </div>

              {/* Individual Category Links */}
              {enrichedCategories.slice(0, 5).map((cat) => {
                const label = cat.name;
                const url = `/perfumes?category=${cat.slug}`;
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
                        isTransparent
                          ? isCurrentHovered ? 'text-[#B8712E]' : 'text-white hover:opacity-80 drop-shadow-xs'
                          : isCurrentHovered ? 'text-[#B8712E]' : 'text-[#0A0A0A] hover:text-[#B8712E]'
                      }`}
                    >
                      <span>{label}</span>
                      {hasSub && (
                        <ChevronDown
                          size={13}
                          className={`transition-transform duration-200 ${
                            isCurrentHovered ? 'rotate-180 text-[#B8712E]' : isTransparent ? 'text-white/70' : 'text-slate-400'
                          }`}
                        />
                      )}
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
                  className={`h-8 md:h-10 w-auto max-w-[220px] object-contain transition-all group-hover:opacity-90 ${
                    isTransparent ? 'brightness-0 invert drop-shadow-xs' : ''
                  }`}
                />
              ) : (
                <span className={`font-serif text-[24px] md:text-[32px] tracking-[0.16em] font-extrabold uppercase leading-none transition-all group-hover:tracking-[0.18em] ${
                  isTransparent ? 'text-white drop-shadow-xs' : 'text-[#0A0A0A]'
                }`}>
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
              className={`text-[13px] uppercase font-bold tracking-wider transition-colors cursor-pointer ${
                isTransparent
                  ? 'text-white hover:opacity-75 drop-shadow-xs'
                  : 'text-[#0A0A0A] hover:text-[#B8712E]'
              }`}
            >
              Contact us
            </button>

            <button
              onClick={openCart}
              className={`flex items-center gap-1.5 text-[13px] uppercase font-bold tracking-wider transition-colors cursor-pointer ${
                isTransparent
                  ? 'text-white hover:opacity-75 drop-shadow-xs'
                  : 'text-[#0A0A0A] hover:text-[#B8712E]'
              }`}
              aria-label="View Shopping Cart"
            >
              <ShoppingBag size={18} strokeWidth={2} />
              <span className={`text-[10px] font-mono px-1.5 py-0.5 rounded-full font-bold transition-colors ${
                isTransparent ? 'bg-white text-black' : 'bg-[#0A0A0A] text-white'
              }`}>
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
              <div className="flex items-center justify-between mb-2">
                <span className="text-[10px] font-mono font-bold tracking-[0.2em] uppercase text-[#B8712E] block">
                  PRODUCTS & CATEGORIES
                </span>
                <Link
                  href="/perfumes"
                  onClick={() => setMenuDrawerOpen(false)}
                  className="text-[11px] font-mono font-bold text-slate-500 hover:text-black uppercase underline"
                >
                  View All &rarr;
                </Link>
              </div>

              {/* Master Product Menu Accordion */}
              <div className="bg-[#FBF9F6] border border-[#EBE7DF] rounded-xl p-3 mb-4">
                <div className="flex items-center justify-between">
                  <Link
                    href="/perfumes"
                    onClick={() => setMenuDrawerOpen(false)}
                    className="text-[13px] font-bold uppercase tracking-wider text-[#0A0A0A] hover:text-[#B8712E] flex items-center gap-2"
                  >
                    <Sparkles size={15} className="text-[#B8712E]" />
                    <span>Product Catalog (All)</span>
                  </Link>
                  <button
                    type="button"
                    onClick={() => toggleCategory('products-master')}
                    className="p-1 text-slate-400 hover:text-black cursor-pointer"
                  >
                    {expandedCategories['products-master'] ? (
                      <ChevronDown size={16} className="text-[#B8712E]" />
                    ) : (
                      <ChevronRight size={16} />
                    )}
                  </button>
                </div>

                {expandedCategories['products-master'] && (
                  <div className="mt-3 pt-3 border-t border-[#EBE7DF] space-y-2">
                    {enrichedCategories.map((cat) => (
                      <div key={'sub-group-' + cat.id} className="space-y-1">
                        <Link
                          href={`/perfumes?category=${cat.slug}`}
                          onClick={() => setMenuDrawerOpen(false)}
                          className="block text-[12px] font-bold uppercase text-[#0A0A0A] hover:text-[#B8712E] pl-2"
                        >
                          • {cat.name}
                        </Link>
                        {cat.children && cat.children.length > 0 && (
                          <div className="pl-5 space-y-1">
                            {cat.children.map((sub) => (
                              <Link
                                key={'drawer-sub-' + sub.id}
                                href={`/perfumes?category=${sub.slug}`}
                                onClick={() => setMenuDrawerOpen(false)}
                                className="block text-[12px] text-slate-600 hover:text-[#B8712E] py-0.5"
                              >
                                ↳ {sub.name}
                              </Link>
                            ))}
                          </div>
                        )}
                      </div>
                    ))}
                  </div>
                )}
              </div>

              <span className="text-[10px] font-mono font-bold tracking-[0.2em] uppercase text-slate-400 block pt-2 mb-2">
                DIRECT CATEGORY ACCESS
              </span>

              <div className="space-y-3">
                {enrichedCategories.map((item) => {
                  const label = item.name;
                  const mainUrl = `/perfumes?category=${item.slug}`;
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
                              {children.map((sub) => {
                                const subLabel = sub.name;
                                const subUrl = `/perfumes?category=${sub.slug}`;
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

            {/* Bottom Drawer Actions */}
            <div className="pt-6 border-t border-[#F5F3EF] space-y-3">
              <button
                onClick={() => {
                  setMenuDrawerOpen(false);
                  setContactDrawerOpen(true);
                }}
                className="w-full text-left py-2 text-[12px] uppercase font-bold tracking-wider text-[#0A0A0A] hover:text-[#B8712E] transition-colors flex items-center justify-between"
              >
                <span>Customer Care & Concierge</span>
                <span>&rarr;</span>
              </button>

              <div className="text-[11px] text-[#6E6B66] font-mono">
                {settings.phone && <div>Direct: {settings.phone}</div>}
                {settings.email && <div>Email: {settings.email}</div>}
              </div>
            </div>
          </div>
        </div>
      )}

      {/* Slide-In Contact Concierge Drawer */}
      {contactDrawerOpen && (
        <div className="fixed inset-0 z-50 flex justify-end animate-fade-in">
          {/* Dark Overlay Background */}
          <div
            className="fixed inset-0 bg-black/65 backdrop-blur-xs transition-opacity"
            onClick={() => setContactDrawerOpen(false)}
          />

          {/* Right White Slide-In Contact Panel */}
          <div className="relative w-full max-w-[420px] bg-white text-[#0A0A0A] h-full flex flex-col justify-between p-6 sm:p-8 shadow-2xl z-10 overflow-y-auto">
            {/* Top Bar */}
            <div className="flex justify-between items-center pb-6 border-b border-[#F5F3EF]">
              <span className="font-serif text-[18px] font-bold uppercase tracking-wider text-[#0A0A0A]">
                Client Concierge
              </span>
              <button
                onClick={() => setContactDrawerOpen(false)}
                className="p-1 text-[#0A0A0A] hover:text-[#B8712E] transition-colors cursor-pointer"
                aria-label="Close Contact Panel"
              >
                <X size={20} />
              </button>
            </div>

            {/* Concierge Body */}
            <div className="py-6 flex-1 space-y-6">
              <div>
                <span className="text-[10px] font-mono font-bold tracking-[0.2em] uppercase text-[#B8712E] block mb-1">
                  PRESTIGE ASSISTANCE
                </span>
                <p className="text-[13px] text-[#6E6B66] leading-relaxed">
                  Our private olfactory concierges are at your service for bespoke consultations, order inquiries, and luxury gifting recommendations.
                </p>
              </div>

              <div className="space-y-4">
                {/* WhatsApp Direct */}
                {settings.whatsapp && (
                  <a
                    href={`https://wa.me/${settings.whatsapp.replace(/[^0-9]/g, '')}?text=Hello%20Rexxo%20BD%20Concierge,%20I%20would%20like%20assistance%20with%20your%20fragrance%20collection.`}
                    target="_blank"
                    rel="noopener noreferrer"
                    className="flex items-center gap-3 p-4 bg-[#F5F3EF] hover:bg-[#0A0A0A] text-[#0A0A0A] hover:text-white transition-all group border border-[#DEDBD4]"
                  >
                    <MessageSquare size={20} className="text-[#25D366] group-hover:text-white shrink-0" />
                    <div>
                      <div className="text-[12px] font-bold uppercase tracking-wider">WhatsApp VIP Chat</div>
                      <div className="text-[11px] text-[#6E6B66] group-hover:text-slate-300 font-mono">
                        {settings.whatsapp}
                      </div>
                    </div>
                  </a>
                )}

                {/* Phone Call */}
                {settings.phone && (
                  <a
                    href={`tel:${settings.phone}`}
                    className="flex items-center gap-3 p-4 bg-[#F5F3EF] hover:bg-[#0A0A0A] text-[#0A0A0A] hover:text-white transition-all group border border-[#DEDBD4]"
                  >
                    <Phone size={20} className="text-[#B8712E] group-hover:text-white shrink-0" />
                    <div>
                      <div className="text-[12px] font-bold uppercase tracking-wider">Telephone Concierge</div>
                      <div className="text-[11px] text-[#6E6B66] group-hover:text-slate-300 font-mono">
                        {settings.phone}
                      </div>
                    </div>
                  </a>
                )}

                {/* Email Direct */}
                {settings.email && (
                  <a
                    href={`mailto:${settings.email}`}
                    className="flex items-center gap-3 p-4 bg-[#F5F3EF] hover:bg-[#0A0A0A] text-[#0A0A0A] hover:text-white transition-all group border border-[#DEDBD4]"
                  >
                    <Mail size={20} className="text-[#6E6B66] group-hover:text-white shrink-0" />
                    <div>
                      <div className="text-[12px] font-bold uppercase tracking-wider">Email Inquiry</div>
                      <div className="text-[11px] text-[#6E6B66] group-hover:text-slate-300 font-mono">
                        {settings.email}
                      </div>
                    </div>
                  </a>
                )}
              </div>

              {/* Atelier Address */}
              {settings.address && (
                <div className="p-4 border border-[#DEDBD4] bg-white space-y-1">
                  <div className="text-[10px] font-mono font-bold tracking-[0.16em] uppercase text-[#6E6B66]">
                    FLAGSHIP BOUTIQUE
                  </div>
                  <div className="text-[12px] text-[#0A0A0A] font-medium leading-snug">
                    {settings.address}
                  </div>
                </div>
              )}
            </div>

            {/* Bottom Note */}
            <div className="pt-6 border-t border-[#F5F3EF] text-center">
              <span className="text-[10px] font-mono tracking-widest text-[#6E6B66] uppercase">
                {settings.siteName || 'REXXO BD'} · HAUTE PARFUMERIE
              </span>
            </div>
          </div>
        </div>
      )}
    </>
  );
}
