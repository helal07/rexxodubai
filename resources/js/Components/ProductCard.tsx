'use client';

import React, { useState } from 'react';
import { Link } from '@inertiajs/react';
import { Product } from '@/lib/api';

interface ProductCardProps {
  product: Product;
  variant?: 'prada' | 'detailed';
}

export default function ProductCard({ product, variant = 'prada' }: ProductCardProps) {
  const [isHovered, setIsHovered] = useState(false);

  const priceFormatted = product.price ? `$${Number(product.price).toFixed(2)}` : '';

  return (
    <div
      className="group relative flex flex-col transition-all duration-300"
      onMouseEnter={() => setIsHovered(true)}
      onMouseLeave={() => setIsHovered(false)}
    >
      <Link href={`/product/${product.slug}`} className="block relative">
        {/* Studio Product Square Container (1:1 Prada Aesthetic) */}
        <div className="relative aspect-square w-full bg-[#F6F6F6] overflow-hidden flex items-center justify-center p-6 md:p-8 transition-colors group-hover:bg-[#F0F0F0]">
          {/* Badges */}
          <div className="absolute top-3 left-3 flex flex-col space-y-1 z-20">
            {product.is_new_arrival && (
              <span className="bg-[#0A0A0A] text-white text-[9px] uppercase tracking-[0.14em] px-2 py-0.5 font-bold">
                NEW
              </span>
            )}
            {product.is_featured && (
              <span className="bg-white text-[#0A0A0A] border border-[#0A0A0A] text-[9px] uppercase tracking-[0.14em] px-2 py-0.5 font-bold">
                EDITION
              </span>
            )}
          </div>

          {/* Primary Studio Shot */}
          <img
            src={product.primary_image_url}
            alt={product.name}
            className={`w-full h-full object-contain transition-all duration-700 group-hover:scale-105 ${
              isHovered && product.secondary_image_url ? 'opacity-0 scale-95' : 'opacity-100 scale-100'
            }`}
            loading="lazy"
          />

          {/* Secondary Hover Lifestyle/Detail Shot */}
          {product.secondary_image_url && (
            <img
              src={product.secondary_image_url}
              alt={`${product.name} alternate`}
              className={`absolute inset-0 w-full h-full object-contain p-6 md:p-8 transition-all duration-700 group-hover:scale-105 ${
                isHovered ? 'opacity-100 scale-100' : 'opacity-0 scale-95'
              }`}
              loading="lazy"
            />
          )}
        </div>

        {/* Centered Prada Product Title & Price Label */}
        <div className="pt-4 pb-2 text-center space-y-1">
          <h3 className="text-[13px] font-sans font-medium uppercase tracking-[0.06em] text-[#0A0A0A] group-hover:opacity-70 transition-opacity">
            {product.name}
          </h3>
          {priceFormatted && (
            <p className="text-[12px] text-[#6E6B66] font-normal tracking-wide">
              {priceFormatted}
            </p>
          )}
          {product.scent_family && (
            <span className="text-[11px] text-[#8E8B85] uppercase tracking-wider block font-light">
              {product.scent_family}
            </span>
          )}
        </div>
      </Link>
    </div>
  );
}

