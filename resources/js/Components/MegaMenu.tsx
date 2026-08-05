'use client';

import React from 'react';
import { Link } from '@inertiajs/react';

import { MenuItem } from '@/lib/api';

interface MegaMenuProps {
  item: MenuItem;
  isOpen: boolean;
  onClose: () => void;
}

export default function MegaMenu({ item, isOpen, onClose }: MegaMenuProps) {
  if (!isOpen || !item.children || item.children.length === 0) return null;

  const leftLinks = item.children.filter(c => c.column_group === 'left' || !c.column_group);
  const highlights = item.children.filter(c => c.column_group === 'highlights');

  return (
    <div
      onMouseLeave={onClose}
      className="absolute top-full left-0 w-full bg-white border-b border-[#DEDBD4] shadow-xl z-50 animate-fade-in transition-all duration-200"
    >
      <div className="max-w-[1440px] mx-auto px-6 py-10 grid grid-cols-12 gap-8">
        {/* Column 1: Category / Collection Links */}
        <div className="col-span-4 border-r border-[#DEDBD4] pr-8">
          <span className="text-[11px] tracking-[0.12em] uppercase font-semibold text-[#6E6B66] block mb-4">
            COLLECTION & EDITIONS
          </span>
          <ul className="space-y-3">
            {leftLinks.map(child => (
              <li key={child.id}>
                <Link
                  href={child.url || '#'}
                  onClick={onClose}
                  className="text-[14px] text-[#0A0A0A] hover:text-[#B8712E] transition-colors font-medium tracking-wide flex items-center group"
                >
                  <span className="w-0 group-hover:w-2 transition-all duration-200 h-[1px] bg-[#B8712E] mr-0 group-hover:mr-2"></span>
                  {child.label}
                </Link>
              </li>
            ))}
          </ul>
        </div>

        {/* Column 2: Highlights Image Tiles (Prada Galleria Style) */}
        <div className="col-span-5 border-r border-[#DEDBD4] pr-8">
          <span className="text-[11px] tracking-[0.12em] uppercase font-semibold text-[#6E6B66] block mb-4">
            HIGHLIGHTS & BOTTLES
          </span>
          <div className="grid grid-cols-2 gap-4">
            {highlights.map(h => (
              <Link
                key={h.id}
                href={h.url || '#'}
                onClick={onClose}
                className="group block relative overflow-hidden bg-[#F5F3EF]"
              >
                <div className="aspect-[4/5] relative overflow-hidden">
                  <img
                    src={h.image_url || 'https://images.unsplash.com/photo-1594035910387-fea47794261f?auto=format&fit=crop&w=600&q=80'}
                    alt={h.label}

                    className="object-cover group-hover:scale-105 transition-transform duration-500"
                  />
                </div>
                <div className="p-3 bg-white border-t border-[#DEDBD4]">
                  <span className="text-[12px] font-semibold tracking-wide text-[#0A0A0A] group-hover:text-[#B8712E] transition-colors block">
                    {h.label}
                  </span>
                  <span className="text-[10px] text-[#6E6B66] tracking-widest uppercase">DISCOVER BOTTLE →</span>
                </div>
              </Link>
            ))}
          </div>
        </div>

        {/* Column 3: Brand Craft Editorial Callout */}
        <div className="col-span-3 flex flex-col justify-between bg-[#F5F3EF] p-6 border border-[#DEDBD4]">
          <div>
            <span className="text-[10px] tracking-[0.14em] uppercase text-[#6E6B66] font-semibold block mb-2">
              REXXO BD SCENT ART
            </span>
            <h4 className="font-serif text-[20px] text-[#0A0A0A] leading-snug mb-3">
              The Alchemy of Amber Glass & Vapor
            </h4>
            <p className="text-[12px] text-[#6E6B66] leading-relaxed mb-4">
              Explore how raw Damask roses and hand-selected resins create our signature scent trail.
            </p>
          </div>
          <Link
            href="/about"
            onClick={onClose}
            className="text-[11px] uppercase tracking-widest font-semibold text-[#0A0A0A] hover:text-[#B8712E] underline underline-offset-4"
          >
            Read Brand Craft →
          </Link>
        </div>
      </div>
    </div>
  );
}
