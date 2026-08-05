import React from 'react';
import ProductCard from '@/Components/ProductCard';
import PDPClient from '@/Components/PDPClient';

export const revalidate = 60;

interface PageProps {
  params: Promise<{ slug: string }>;
}

export default function ProductDetailPage({ product, related }: any) {
  if (!product) {
    return (
      <div className="max-w-[1440px] mx-auto px-6 py-24 text-center">
        <h1 className="font-serif text-[32px] text-[#0A0A0A] mb-4">Perfume bottle not found.</h1>
        <a href="/perfumes" className="text-[11px] uppercase font-bold tracking-widest text-[#B8712E] underline">
          RETURN TO CATALOG →
        </a>
      </div>
    );
  }

  return (
    <div className="max-w-[1440px] mx-auto px-6 pt-24 pb-8 space-y-20">
      <PDPClient product={product} />

      {/* Below Fold: You May Also Like Carousel/Grid */}
      {related && related.length > 0 && (
        <section className="border-t border-[#DEDBD4] pt-12">
          <div className="mb-8">
            <span className="text-[11px] uppercase tracking-[0.14em] font-semibold text-[#B8712E] block">
              RECOMMENDED HARMONIES
            </span>
            <h3 className="font-serif text-[28px] text-[#0A0A0A]">You May Also Like</h3>
          </div>

          <div className="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
            {related.map((p: any) => (
              <ProductCard key={p.id} product={p} />
            ))}
          </div>
        </section>
      )}
    </div>
  );
}
