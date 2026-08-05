import React, { useState } from 'react';
import { Link } from '@inertiajs/react';
import HeroVideo from '@/Components/HeroVideo';
import ProductCard from '@/Components/ProductCard';

export const revalidate = 60; // ISR revalidation every 60s

interface HomePageProps {
  featuredProducts?: any[];
  newArrivals?: any[];
}

export default function HomePage({ featuredProducts = [], newArrivals = [] }: HomePageProps) {
  const [section1Gender, setSection1Gender] = useState<'women' | 'men'>('women');
  const [section2Gender, setSection2Gender] = useState<'women' | 'men'>('women');

  // 1:1 Prada Category Showcase 1 Data
  const showcase1Women = [
    {
      title: "Women's Bags",
      subtitle: "Extrait de Parfum Flacons",
      image: "https://images.unsplash.com/photo-1548036328-c9fa89d128fa?auto=format&fit=crop&w=800&q=85",
      url: "/perfumes?gender=women",
    },
    {
      title: "Women's Ready to Wear",
      subtitle: "Floral & Silk Extraits",
      image: "https://images.unsplash.com/photo-1539109136881-3be0616acf4b?auto=format&fit=crop&w=800&q=85",
      url: "/perfumes?gender=women",
    },
    {
      title: "Women's Shoes",
      subtitle: "Amber Stiletto Discovery",
      image: "https://images.unsplash.com/photo-1543163521-1bf539c55dd2?auto=format&fit=crop&w=800&q=85",
      url: "/perfumes?gender=women",
    },
    {
      title: "Women's Accessories",
      subtitle: "Handcrafted Leather Atomizers",
      image: "https://images.unsplash.com/photo-1511499767150-a48a237f0083?auto=format&fit=crop&w=800&q=85",
      url: "/perfumes?gender=women",
    },
  ];

  const showcase1Men = [
    {
      title: "Men's Bags",
      subtitle: "Smoky Leather Fragrances",
      image: "https://images.unsplash.com/photo-1553062407-98eeb64c6a62?auto=format&fit=crop&w=800&q=85",
      url: "/perfumes?gender=men",
    },
    {
      title: "Men's Ready to Wear",
      subtitle: "Woody Vetiver Extraits",
      image: "https://images.unsplash.com/photo-1617137984095-74e4e5e3613f?auto=format&fit=crop&w=800&q=85",
      url: "/perfumes?gender=men",
    },
    {
      title: "Men's Shoes",
      subtitle: "Artisan Leather & Oud",
      image: "https://images.unsplash.com/photo-1549298916-b41d501d3772?auto=format&fit=crop&w=800&q=85",
      url: "/perfumes?gender=men",
    },
    {
      title: "Men's Accessories",
      subtitle: "Signature Flacon Coffrets",
      image: "https://images.unsplash.com/photo-1508296695146-257a814070b4?auto=format&fit=crop&w=800&q=85",
      url: "/perfumes?gender=men",
    },
  ];

  // 1:1 Prada Iconic Collections 2 Data
  const iconicBagsWomen = [
    {
      title: "Prada Buckle",
      price: "$ 4,800",
      image: "https://images.unsplash.com/photo-1584917865442-de89df76afd3?auto=format&fit=crop&w=800&q=85",
      url: "/perfumes?gender=women",
    },
    {
      title: "Prada Galleria",
      price: "$ 4,200",
      image: "https://images.unsplash.com/photo-1590874103328-eac38a683ce7?auto=format&fit=crop&w=800&q=85",
      url: "/perfumes?gender=women",
    },
    {
      title: "Prada Aimée",
      price: "$ 3,600",
      image: "https://images.unsplash.com/photo-1566150905458-1bf1fc113f0d?auto=format&fit=crop&w=800&q=85",
      url: "/perfumes?gender=women",
    },
    {
      title: "Prada Cleo",
      price: "$ 3,300",
      image: "https://images.unsplash.com/photo-1591561954557-26941169b49e?auto=format&fit=crop&w=800&q=85",
      url: "/perfumes?gender=women",
    },
  ];

  const iconicBagsMen = [
    {
      title: "Prada Brique",
      price: "$ 3,100",
      image: "https://images.unsplash.com/photo-1553062407-98eeb64c6a62?auto=format&fit=crop&w=800&q=85",
      url: "/perfumes?gender=men",
    },
    {
      title: "Prada Re-Nylon",
      price: "$ 2,400",
      image: "https://images.unsplash.com/photo-1622560480605-d83c853bc5c3?auto=format&fit=crop&w=800&q=85",
      url: "/perfumes?gender=men",
    },
    {
      title: "Prada Tote",
      price: "$ 3,500",
      image: "https://images.unsplash.com/photo-1544816155-12df9643f363?auto=format&fit=crop&w=800&q=85",
      url: "/perfumes?gender=men",
    },
    {
      title: "Prada Briefcase",
      price: "$ 4,100",
      image: "https://images.unsplash.com/photo-1553062407-98eeb64c6a62?auto=format&fit=crop&w=800&q=85",
      url: "/perfumes?gender=men",
    },
  ];

  const activeShowcase1 = section1Gender === 'women' ? showcase1Women : showcase1Men;
  const activeIconic2 = section2Gender === 'women' ? iconicBagsWomen : iconicBagsMen;

  return (
    <div className="bg-white text-[#0A0A0A]">
      {/* ── 1. HERO CAMPAIGN SECTION (Dynamic Video / Poster Background) ── */}
      <HeroVideo />

      {/* ── 2. CURATED CATEGORY SHOWCASE 1 (1:1 Prada Layout) ── */}
      <section className="py-16 md:py-24 max-w-[1440px] mx-auto px-6 space-y-8">
        {/* Centered Editorial Intro Statement */}
        <div className="max-w-2xl mx-auto text-center space-y-5">
          <p className="text-[13px] sm:text-[14px] text-[#4A4744] font-normal leading-relaxed tracking-normal">
            Distinct nuances and playful proportions conveying contemporary and unique details: reveal new accessories to find everyday.
          </p>

          {/* Centered Gender Switch Tabs */}
          <div className="flex items-center justify-center space-x-6 pt-1">
            <button
              onClick={() => setSection1Gender('women')}
              className={`text-[13px] uppercase font-bold tracking-[0.1em] transition-all cursor-pointer ${
                section1Gender === 'women'
                  ? 'text-[#0A0A0A] border-b-2 border-[#0A0A0A] pb-0.5'
                  : 'text-[#8E8B85] hover:text-[#0A0A0A]'
              }`}
            >
              Women
            </button>
            <button
              onClick={() => setSection1Gender('men')}
              className={`text-[13px] uppercase font-bold tracking-[0.1em] transition-all cursor-pointer ${
                section1Gender === 'men'
                  ? 'text-[#0A0A0A] border-b-2 border-[#0A0A0A] pb-0.5'
                  : 'text-[#8E8B85] hover:text-[#0A0A0A]'
              }`}
            >
              Men
            </button>
          </div>
        </div>

        {/* 4-Column Studio Showcase Grid */}
        <div className="grid grid-cols-2 lg:grid-cols-4 gap-4 md:gap-6 pt-4">
          {activeShowcase1.map((item, idx) => (
            <Link
              key={idx}
              href={item.url}
              className="group block transition-all duration-300"
            >
              <div className="relative aspect-square w-full bg-[#F6F6F6] overflow-hidden flex items-center justify-center p-6 sm:p-8 transition-colors group-hover:bg-[#EFEFEF]">
                <img
                  src={item.image}
                  alt={item.title}
                  className="w-full h-full object-contain mix-blend-multiply group-hover:scale-105 transition-transform duration-700"
                  loading="lazy"
                />
              </div>
              <div className="pt-4 text-center">
                <h3 className="text-[13px] font-sans font-medium text-[#0A0A0A] uppercase tracking-[0.06em] group-hover:opacity-70 transition-opacity">
                  {item.title}
                </h3>
              </div>
            </Link>
          ))}
        </div>
      </section>

      {/* ── 3. FULL-WIDTH EDITORIAL CAMPAIGN BANNER 1 (Prada Galleria Staircase) ── */}
      <section className="relative w-full aspect-[4/5] sm:aspect-[16/10] lg:aspect-[21/9] bg-[#0A0A0A] overflow-hidden flex flex-col justify-end pb-12 sm:pb-16 text-center text-white">
        <img
          src="https://images.unsplash.com/photo-1509631179647-0177331693ae?auto=format&fit=crop&w=2400&q=90"
          alt="Prada Galleria Campaign"
          className="absolute inset-0 w-full h-full object-cover object-center opacity-85"
        />
        <div className="absolute inset-0 bg-gradient-to-t from-black/80 via-black/20 to-transparent" />

        <div className="relative z-10 max-w-2xl mx-auto px-6 space-y-3">
          <h2 className="text-[28px] sm:text-[38px] md:text-[46px] font-sans font-bold tracking-tight text-white leading-tight">
            Prada Galleria
          </h2>
          <p className="text-[12px] sm:text-[13px] uppercase tracking-[0.14em] text-white/90 font-medium">
            Signature silhouettes redefined with intricate artisan craftsmanship.
          </p>
          <div className="pt-2">
            <Link
              href="/perfumes?gender=women"
              className="inline-block text-[13px] uppercase font-bold tracking-[0.14em] text-white hover:text-white/80 border-b-2 border-white pb-0.5 transition-colors"
            >
              DISCOVER
            </Link>
          </div>
        </div>
      </section>

      {/* ── 4. ICONIC COLLECTIONS 2 (Cursive Header + 4-Column Cutout Grid) ── */}
      <section className="py-16 md:py-24 max-w-[1440px] mx-auto px-6 space-y-8">
        {/* Centered Cursive Heading & Description */}
        <div className="max-w-2xl mx-auto text-center space-y-4">
          <h2 className="font-serif italic text-[32px] sm:text-[42px] font-light text-[#0A0A0A] tracking-tight">
            Iconic Collections
          </h2>
          <p className="text-[13px] sm:text-[14px] text-[#4A4744] font-normal leading-relaxed">
            Prada's iconic shapes created in the newest luxury materials, bringing together traditional elegance with a contemporary spirit.
          </p>

          {/* Gender Tabs */}
          <div className="flex items-center justify-center space-x-6 pt-1">
            <button
              onClick={() => setSection2Gender('women')}
              className={`text-[13px] uppercase font-bold tracking-[0.1em] transition-all cursor-pointer ${
                section2Gender === 'women'
                  ? 'text-[#0A0A0A] border-b-2 border-[#0A0A0A] pb-0.5'
                  : 'text-[#8E8B85] hover:text-[#0A0A0A]'
              }`}
            >
              Women
            </button>
            <button
              onClick={() => setSection2Gender('men')}
              className={`text-[13px] uppercase font-bold tracking-[0.1em] transition-all cursor-pointer ${
                section2Gender === 'men'
                  ? 'text-[#0A0A0A] border-b-2 border-[#0A0A0A] pb-0.5'
                  : 'text-[#8E8B85] hover:text-[#0A0A0A]'
              }`}
            >
              Men
            </button>
          </div>
        </div>

        {/* 4-Column Studio Product Cards */}
        <div className="grid grid-cols-2 lg:grid-cols-4 gap-4 md:gap-6 pt-4">
          {activeIconic2.map((bag, idx) => (
            <Link
              key={idx}
              href={bag.url}
              className="group block transition-all duration-300"
            >
              <div className="relative aspect-square w-full bg-[#F6F6F6] overflow-hidden flex items-center justify-center p-6 sm:p-8 transition-colors group-hover:bg-[#EFEFEF]">
                <img
                  src={bag.image}
                  alt={bag.title}
                  className="w-full h-full object-contain mix-blend-multiply group-hover:scale-105 transition-transform duration-700"
                  loading="lazy"
                />
              </div>
              <div className="pt-4 text-center space-y-1">
                <h3 className="text-[13px] font-sans font-medium text-[#0A0A0A] uppercase tracking-[0.06em] group-hover:opacity-70 transition-opacity">
                  {bag.title}
                </h3>
              </div>
            </Link>
          ))}
        </div>
      </section>

      {/* ── 5. FULL-WIDTH EDITORIAL CAMPAIGN BANNER 2 (Artisan Craft / Men's Campaign) ── */}
      <section className="relative w-full aspect-[4/5] sm:aspect-[16/10] lg:aspect-[21/9] bg-[#0A0A0A] overflow-hidden flex flex-col justify-end pb-12 sm:pb-16 text-center text-white">
        <img
          src="https://images.unsplash.com/photo-1549298916-b41d501d3772?auto=format&fit=crop&w=2400&q=90"
          alt="Men's Shoes Artisan Campaign"
          className="absolute inset-0 w-full h-full object-cover object-center opacity-85"
        />
        <div className="absolute inset-0 bg-gradient-to-t from-black/80 via-black/20 to-transparent" />

        <div className="relative z-10 max-w-2xl mx-auto px-6 space-y-3">
          <h2 className="text-[28px] sm:text-[38px] md:text-[46px] font-sans font-bold tracking-tight text-white leading-tight">
            Men's Shoes
          </h2>
          <p className="text-[12px] sm:text-[13px] uppercase tracking-[0.14em] text-white/90 font-medium">
            Autumn Winter Campaign
          </p>
          <div className="pt-2">
            <Link
              href="/perfumes?gender=men"
              className="inline-block text-[13px] uppercase font-bold tracking-[0.14em] text-white hover:text-white/80 border-b-2 border-white pb-0.5 transition-colors"
            >
              DISCOVER
            </Link>
          </div>
        </div>
      </section>

      {/* ── 6. PRADASPHERE NEWS / MAISON STORIES (Deep Black Editorial Section) ── */}
      <section className="bg-[#0A0A0A] text-white py-20 md:py-28">
        <div className="max-w-[1440px] mx-auto px-6 space-y-12">
          <div className="text-center">
            <h2 className="text-[20px] sm:text-[24px] font-sans font-medium tracking-[0.08em] text-white">
              Pradasphere News
            </h2>
          </div>

          {/* 3 Horizontal Editorial Story Cards */}
          <div className="grid grid-cols-1 md:grid-cols-3 gap-8">
            {[
              {
                category: "CAMPAIGN",
                title: "Prada FW 2026 Campaign",
                image: "https://images.unsplash.com/photo-1515886657613-9f3515b0c78f?auto=format&fit=crop&w=1200&q=85",
                url: "/perfumes",
              },
              {
                category: "FASHION SHOW",
                title: "Men's Fall Winter 2026 Show",
                image: "https://images.unsplash.com/photo-1509631179647-0177331693ae?auto=format&fit=crop&w=1200&q=85",
                url: "/perfumes",
              },
              {
                category: "SPECIAL PROJECTS",
                title: "Prada Frame: Natural Architecture",
                image: "https://images.unsplash.com/photo-1490481651871-ab68de25d43d?auto=format&fit=crop&w=1200&q=85",
                url: "/about",
              },
            ].map((story, idx) => (
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

