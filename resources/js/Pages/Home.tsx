import React, { useState } from 'react';
import { Link, Head, usePage } from '@inertiajs/react';
import HeroVideo from '@/Components/HeroVideo';
import ProductCard from '@/Components/ProductCard';

export const revalidate = 60; // ISR revalidation every 60s

interface HomePageProps {
  featuredProducts?: any[];
  newArrivals?: any[];
  categoriesWithProducts?: any[];
  activeCampaigns?: any[];
}

export default function HomePage({ featuredProducts = [], newArrivals = [], categoriesWithProducts = [], activeCampaigns = [] }: HomePageProps) {
  const { siteSettings, apiSettings, cmsData }: any = usePage().props;
  const settings = siteSettings || apiSettings || {};
  
  const global = cmsData?.global || {};
  const brandName = global?.site_name || settings.siteName || 'RaaxO BD';
  const tagline = global?.tagline || settings.tagline || 'Fine Fragrance & Luxury Extraits';
  const metaDesc = global?.seo_meta_description || settings.seo_meta_description || 'Explore handcrafted luxury perfumes, pure extrait de parfums, and bespoke fragrances online in Bangladesh.';



  const stories = cmsData?.maison_stories || {};
  const maisonStoriesData = [
    {
      category: stories.story1_category || "CAMPAIGN",
      title: stories.story1_title || "Artisan Perfumery Collection",
      image: stories.story1_image || "https://images.unsplash.com/photo-1515886657613-9f3515b0c78f?auto=format&fit=crop&w=1200&q=85",
      url: stories.story1_url || "/perfumes",
    },
    {
      category: stories.story2_category || "SHOWCASE",
      title: stories.story2_title || "Discover Exquisite Notes",
      image: stories.story2_image || "https://images.unsplash.com/photo-1509631179647-0177331693ae?auto=format&fit=crop&w=1200&q=85",
      url: stories.story2_url || "/perfumes",
    },
    {
      category: stories.story3_category || "BEHIND THE SCENES",
      title: stories.story3_title || "The Making of Luxury",
      image: stories.story3_image || "https://images.unsplash.com/photo-1490481651871-ab68de25d43d?auto=format&fit=crop&w=1200&q=85",
      url: stories.story3_url || "/about",
    },
  ];

  return (
    <div className="bg-white text-[#0A0A0A]">
      <Head>
        <title>{`${brandName} — ${tagline}`}</title>
        <meta name="description" content={metaDesc} />
        <meta property="og:title" content={`${brandName} — ${tagline}`} />
        <meta property="og:description" content={metaDesc} />
      </Head>

      {/* ── 1. HERO CAMPAIGN SECTION ── */}
      <HeroVideo />

      {/* ── 2. FEATURED PRODUCTS ── */}
      {featuredProducts && featuredProducts.length > 0 && (
        <section className="py-16 md:py-24 max-w-[1440px] mx-auto px-6 space-y-12">
          <div className="max-w-2xl mx-auto text-center space-y-5">
            <h2 className="font-serif italic text-[32px] sm:text-[42px] font-light text-[#0A0A0A] tracking-tight">
              Featured Fragrances
            </h2>
            <p className="text-[13px] sm:text-[14px] text-[#4A4744] font-normal leading-relaxed tracking-normal">
              Distinct nuances and playful proportions conveying contemporary and unique details.
            </p>
          </div>

          <div className="grid grid-cols-2 lg:grid-cols-4 gap-4 md:gap-6">
            {featuredProducts.map((product) => (
              <ProductCard key={product.id} product={product} />
            ))}
          </div>
          <div className="text-center pt-4">
            <Link
              href="/perfumes"
              className="inline-block text-[13px] uppercase font-bold tracking-[0.14em] text-[#0A0A0A] hover:text-[#4A4744] border-b-2 border-[#0A0A0A] pb-0.5 transition-colors"
            >
              VIEW ALL PRODUCTS
            </Link>
          </div>
        </section>
      )}

      {/* ── 3. DYNAMIC CAMPAIGNS ── */}
      {activeCampaigns && activeCampaigns.length > 0 && activeCampaigns.map((camp: any) => (
        <div key={camp.id} className="mb-16">
          <section className="relative w-full aspect-[4/5] sm:aspect-[16/10] lg:aspect-[21/9] bg-[#0A0A0A] overflow-hidden flex flex-col justify-end pb-12 sm:pb-16 text-center text-white">
            <img
              src={camp.banner_image_url || 'https://images.unsplash.com/photo-1509631179647-0177331693ae?auto=format&fit=crop&w=2400&q=90'}
              alt={camp.title || camp.name}
              className="absolute inset-0 w-full h-full object-cover object-center opacity-85"
            />
            <div className="absolute inset-0 bg-gradient-to-t from-black/80 via-black/20 to-transparent" />

            <div className="relative z-10 max-w-2xl mx-auto px-6 space-y-3">
              <h2 className="text-[28px] sm:text-[38px] md:text-[46px] font-sans font-bold tracking-tight text-white leading-tight">
                {camp.title || camp.name}
              </h2>
              {camp.subtitle && (
                <p className="text-[12px] sm:text-[13px] uppercase tracking-[0.14em] text-white/90 font-medium">
                  {camp.subtitle}
                </p>
              )}
              <div className="pt-2">
                <Link
                  href={camp.button_link || '/perfumes'}
                  className="inline-block text-[13px] uppercase font-bold tracking-[0.14em] text-white hover:text-white/80 border-b-2 border-white pb-0.5 transition-colors"
                >
                  {camp.button_text || 'DISCOVER'}
                </Link>
              </div>
            </div>
          </section>

          {/* Campaign Featured Products */}
          {camp.products && camp.products.length > 0 && (
            <section className="py-12 max-w-[1440px] mx-auto px-6">
              <div className="grid grid-cols-2 lg:grid-cols-4 gap-4 md:gap-6">
                {camp.products.map((product: any) => (
                  <ProductCard key={product.id} product={product} />
                ))}
              </div>
            </section>
          )}
        </div>
      ))}

      {/* ── 4. DYNAMIC CATEGORIES ── */}
      {categoriesWithProducts && categoriesWithProducts.length > 0 && (
        <div className="space-y-16 py-16 md:py-24">
          {categoriesWithProducts.map((category) => (
            <section key={category.id} className="max-w-[1440px] mx-auto px-6 space-y-8">
              <div className="flex items-end justify-between border-b border-[#EAEAEA] pb-4">
                <div className="space-y-2">
                  <h2 className="font-serif italic text-[28px] sm:text-[36px] font-light text-[#0A0A0A] tracking-tight">
                    {category.name}
                  </h2>
                  {category.description && (
                    <p className="text-[13px] text-[#4A4744] font-normal leading-relaxed max-w-xl">
                      {category.description}
                    </p>
                  )}
                </div>
                <Link
                  href={`/perfumes?category=${category.slug}`}
                  className="hidden md:inline-block text-[11px] uppercase font-bold tracking-[0.14em] text-[#0A0A0A] hover:text-[#4A4744] border-b-2 border-[#0A0A0A] pb-0.5 transition-colors mb-1"
                >
                  SHOP {category.name}
                </Link>
              </div>

              <div className="grid grid-cols-2 lg:grid-cols-4 gap-4 md:gap-6">
                {category.products.map((product: any) => (
                  <ProductCard key={product.id} product={product} />
                ))}
              </div>
              <div className="md:hidden text-center pt-4">
                <Link
                  href={`/perfumes?category=${category.slug}`}
                  className="inline-block text-[12px] uppercase font-bold tracking-[0.14em] text-[#0A0A0A] hover:text-[#4A4744] border-b-2 border-[#0A0A0A] pb-0.5 transition-colors"
                >
                  SHOP {category.name}
                </Link>
              </div>
            </section>
          ))}
        </div>
      )}

      {/* ── 5. NEW ARRIVALS ── */}
      {newArrivals && newArrivals.length > 0 && (
        <section className="bg-[#F6F6F6] py-16 md:py-24">
          <div className="max-w-[1440px] mx-auto px-6 space-y-12">
            <div className="max-w-2xl mx-auto text-center space-y-4">
              <h2 className="font-sans font-bold text-[28px] sm:text-[36px] tracking-tight text-[#0A0A0A] uppercase">
                New Arrivals
              </h2>
              <p className="text-[13px] sm:text-[14px] text-[#4A4744] font-normal leading-relaxed">
                Explore the latest additions to our luxury perfume collection.
              </p>
            </div>

            <div className="grid grid-cols-2 lg:grid-cols-4 gap-4 md:gap-6">
              {newArrivals.map((product) => (
                <ProductCard key={product.id} product={product} />
              ))}
            </div>
          </div>
        </section>
      )}

      {/* ── 6. MAISON STORIES (Editorial Section) ── */}
      <section className="bg-[#0A0A0A] text-white py-20 md:py-28">
        <div className="max-w-[1440px] mx-auto px-6 space-y-12">
          <div className="text-center">
            <h2 className="text-[20px] sm:text-[24px] font-sans font-medium tracking-[0.08em] text-white">
              Maison Stories
            </h2>
          </div>

          <div className="grid grid-cols-1 md:grid-cols-3 gap-8">
            {maisonStoriesData.map((story, idx) => (
              <Link
                key={idx}
                href={story.url}
                className="group block space-y-4"
              >
                <div className="relative aspect-[16/11] w-full bg-[#1A1A1A] overflow-hidden">
                  <img
                    src={story.image}
                    alt={story.title}
                    className="w-full h-full object-cover transition-transform duration-700 group-hover:scale-105"
                    loading="lazy"
                  />
                </div>
                <div className="text-center space-y-1">
                  <span className="text-[11px] uppercase tracking-[0.16em] text-white/60 font-semibold block">
                    {story.category}
                  </span>
                  <h3 className="text-[14px] sm:text-[15px] font-sans font-medium text-white group-hover:text-white/80 transition-colors tracking-wide">
                    {story.title}
                  </h3>
                </div>
              </Link>
            ))}
          </div>
        </div>
      </section>
    </div>
  );
}
