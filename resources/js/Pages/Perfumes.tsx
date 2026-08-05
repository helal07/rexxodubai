import React from 'react';

import ProductCard from '@/Components/ProductCard';
import { Link } from '@inertiajs/react';

export const revalidate = 30;

interface PageProps {
  searchParams: Promise<{
    gender?: string;
    category?: string;
    collection?: string;
    scent_family?: string;
    concentration?: string;
    sort?: string;
    search?: string;
  }>;
}
export default function PLPPage({ products, filters: params, isFallback }: any) {

  // Dynamic Title based on selected category or search query
  let pageTitle = 'ALL FINE FRAGRANCES';
  if (params.gender === 'men') pageTitle = 'MEN PERFUME';
  else if (params.gender === 'women') pageTitle = 'WOMEN PERFUME';
  else if (params.gender === 'kids') pageTitle = 'KIDS PERFUME';
  else if (params.category === 'gifts') pageTitle = 'GIFTS';
  else if (params.collection === 'common') pageTitle = 'COMMON ITEM';
  else if (params.search) {
    pageTitle = isFallback ? `SEARCH: "${params.search}"` : `RESULTS FOR "${params.search}"`;
  }

  return (
    <div className="max-w-[1440px] mx-auto px-6 pt-24 pb-16 space-y-8 animate-fade-in">
      {/* Category Header */}
      <div className="flex flex-col md:flex-row justify-between items-start md:items-end border-b border-[#DEDBD4] pb-6 gap-4">
        <div>
          <span className="text-[11px] uppercase tracking-[0.2em] font-semibold text-[#B8712E] block mb-1">
            REXXO BD · COLLECTION
          </span>
          <h1 className="font-serif text-[32px] md:text-[44px] text-[#0A0A0A] uppercase font-bold tracking-tight">
            {pageTitle}
          </h1>
        </div>

        {/* Minimal Bottle Counter */}
        <div className="text-[12px] text-[#6E6B66] uppercase tracking-wider font-medium">
          SHOWING <strong className="text-[#0A0A0A]">{products.length}</strong> BOTTLES
        </div>
      </div>

      {/* Smart Fallback Recommendation Banner (When wrong/unmatched search query entered) */}
      {isFallback && params.search && (
        <div className="bg-[#F5F3EF] border border-[#DEDBD4] p-5 flex items-center justify-between">
          <div className="space-y-1">
            <span className="text-[11px] font-bold text-[#B8712E] uppercase tracking-widest block">
              SMART RECOMMENDATION
            </span>
            <p className="text-[13px] text-[#0A0A0A] font-medium">
              We couldn't find an exact match for &quot;<span className="italic">{params.search}</span>&quot;, so we selected our premier signature perfumes for you below:
            </p>
          </div>
          <Link
            href="/perfumes"
            className="text-[11px] font-bold uppercase tracking-widest underline text-[#0A0A0A] hover:text-[#B8712E] shrink-0 ml-4"
          >
            CLEAR SEARCH
          </Link>
        </div>
      )}

      {/* Full-Width Product Cards Grid */}
      {products.length === 0 ? (
        <div className="text-center py-20 bg-[#F5F3EF] border border-[#DEDBD4] space-y-4">
          <h3 className="font-serif text-[24px] text-[#0A0A0A]">No perfumes match this selection.</h3>
          <p className="text-[13px] text-[#6E6B66]">Explore our full master catalog of luxury extraits.</p>
          <Link
            href="/perfumes"
            className="inline-block bg-[#0A0A0A] text-white px-8 py-3.5 text-[11px] uppercase font-bold tracking-[0.14em] hover:bg-[#B8712E] transition-colors"
          >
            DISCOVER ALL PERFUMES
          </Link>
        </div>
      ) : (
        <div className="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
          {products.map(product => (
            <ProductCard key={product.id} product={product} />
          ))}
        </div>
      )}
    </div>
  );
}
