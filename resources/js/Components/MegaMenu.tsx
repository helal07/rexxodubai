'use client';

import React from 'react';
import { Link } from '@inertiajs/react';
import { MenuItem, Category } from '@/lib/api';

interface MegaMenuProps {
  item: MenuItem | Category;
  isOpen: boolean;
  onClose: () => void;
}

export default function MegaMenu({ item, isOpen, onClose }: MegaMenuProps) {
  if (!isOpen) return null;

  const children = item.children || [];
  if (children.length === 0) return null;

  const title = (item as any).label || (item as any).name || 'Fragrance Collection';
  const mainUrl = (item as any).url || `/perfumes?category=${(item as any).slug}`;
  const description = (item as any).description || 'Master-crafted pure parfums and rare olfactory extraits.';

  return (
    <div
      onMouseLeave={onClose}
      className="absolute top-full left-0 w-full bg-white border-b border-[#DEDBD4] shadow-2xl z-50 animate-fade-in transition-all duration-200"
    >
      <div className="max-w-[1440px] mx-auto px-8 py-10 grid grid-cols-12 gap-8">
        {/* Column 1: Category / Subcategory Links */}
        <div className="col-span-4 border-r border-[#DEDBD4] pr-8">
          <div className="flex items-center justify-between mb-4">
            <span className="text-[11px] tracking-[0.16em] uppercase font-bold text-[#B8712E] font-mono block">
              SUBCATEGORIES & EDITIONS
            </span>
            <Link
              href={mainUrl}
              onClick={onClose}
              className="text-[11px] font-mono text-slate-500 hover:text-black uppercase underline underline-offset-2 tracking-wider"
            >
              View All &rarr;
            </Link>
          </div>

          <ul className="space-y-3.5">
            {children.map((child: any) => {
              const childLabel = child.label || child.name;
              const childUrl = child.url || `/perfumes?category=${child.slug}`;
              return (
                <li key={child.id}>
                  <Link
                    href={childUrl}
                    onClick={onClose}
                    className="text-[14px] text-[#0A0A0A] hover:text-[#B8712E] transition-colors font-medium tracking-wide flex items-center group"
                  >
                    <span className="w-0 group-hover:w-2.5 transition-all duration-200 h-[1.5px] bg-[#B8712E] mr-0 group-hover:mr-2"></span>
                    <span className="group-hover:font-semibold">{childLabel}</span>
                  </Link>
                </li>
              );
            })}
          </ul>
        </div>

        {/* Column 2: Highlights & Discovery Preview */}
        <div className="col-span-5 border-r border-[#DEDBD4] pr-8">
          <span className="text-[11px] tracking-[0.16em] uppercase font-bold text-[#6E6B66] font-mono block mb-4">
            DISCOVERY & FLACONS
          </span>
          <div className="grid grid-cols-2 gap-4">
            <Link
              href={mainUrl}
              onClick={onClose}
              className="group block relative overflow-hidden bg-[#F5F3EF] border border-[#E5E5E5] hover:border-[#B8712E] transition-all"
            >
              <div className="aspect-[4/5] relative overflow-hidden">
                <img
                  src={(item as any).image_url || 'https://images.unsplash.com/photo-1594035910387-fea47794261f?auto=format&fit=crop&w=600&q=80'}
                  alt={title}
                  className="object-cover w-full h-full group-hover:scale-105 transition-transform duration-500"
                />
              </div>
              <div className="p-3 bg-white border-t border-[#E5E5E5]">
                <span className="text-[12px] font-bold tracking-wide text-[#0A0A0A] group-hover:text-[#B8712E] transition-colors block uppercase">
                  {title}
                </span>
                <span className="text-[10px] text-[#6E6B66] tracking-widest uppercase font-mono">EXPLORE COLLECTION &rarr;</span>
              </div>
            </Link>

            <Link
              href="/perfumes"
              onClick={onClose}
              className="group block relative overflow-hidden bg-[#F5F3EF] border border-[#E5E5E5] hover:border-[#B8712E] transition-all"
            >
              <div className="aspect-[4/5] relative overflow-hidden">
                <img
                  src="https://images.unsplash.com/photo-1547887537-6158d64c35b3?auto=format&fit=crop&w=600&q=80"
                  alt="Discovery Flacons"
                  className="object-cover w-full h-full group-hover:scale-105 transition-transform duration-500"
                />
              </div>
              <div className="p-3 bg-white border-t border-[#E5E5E5]">
                <span className="text-[12px] font-bold tracking-wide text-[#0A0A0A] group-hover:text-[#B8712E] transition-colors block uppercase">
                  Discovery Quads
                </span>
                <span className="text-[10px] text-[#6E6B66] tracking-widest uppercase font-mono">SAMPLE SETS &rarr;</span>
              </div>
            </Link>
          </div>
        </div>

        {/* Column 3: Brand Craft Editorial Callout */}
        <div className="col-span-3 flex flex-col justify-between bg-[#F5F3EF] p-6 border border-[#DEDBD4]">
          <div>
            <span className="text-[10px] tracking-[0.16em] uppercase text-[#B8712E] font-bold font-mono block mb-2">
              REXXO BD CRAFT
            </span>
            <h4 className="font-serif text-[19px] text-[#0A0A0A] leading-snug mb-3 font-bold">
              {title}
            </h4>
            <p className="text-[12px] text-[#6E6B66] leading-relaxed mb-4">
              {description}
            </p>
          </div>
          <Link
            href={mainUrl}
            onClick={onClose}
            className="text-[11px] uppercase tracking-widest font-bold text-[#0A0A0A] hover:text-[#B8712E] underline underline-offset-4 flex items-center gap-1.5"
          >
            Discover All {title} &rarr;
          </Link>
        </div>
      </div>
    </div>
  );
}
