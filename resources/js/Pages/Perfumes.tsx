import React from 'react';
import ProductCard from '@/Components/ProductCard';
import { Link, Head, usePage } from '@inertiajs/react';

export const revalidate = 30;

interface PageProps {
  products: any[];
  filters?: {
    gender?: string;
    category?: string;
    collection?: string;
    scent_family?: string;
    concentration?: string;
    sort?: string;
    search?: string;
  };
  isFallback?: boolean;
}

export default function PLPPage({ products = [], filters: params = {}, isFallback = false }: PageProps) {
  const { siteSettings, apiSettings }: any = usePage().props;
  const settings = siteSettings || apiSettings || {};
  const brandName = settings.siteName || 'RaaxO BD';
  // Human-readable titles mapping
  const categoryTitles: Record<string, string> = {
    'men-perfumes': 'MEN PERFUMES',
    'men-eau-de-parfum': 'MEN · EAU DE PARFUM',
    'men-parfum-extraits': 'MEN · PARFUM EXTRAITS',
    'woody-leather': 'WOODY & SMOKED LEATHER',
    'fresh-citrus': 'FRESH CITRUS & VETIVER',
    'women-perfumes': 'WOMEN PERFUMES',
    'floral-rose': 'FLORAL & DAMASK ROSE',
    'amber-vanilla': 'AMBER & BOURBON VANILLA',
    'women-parfum-extraits': 'WOMEN · PARFUM EXTRAITS',
    'gourmand-musk': 'GOURMAND & WHITE MUSK',
    'unisex-rare-oud': 'UNISEX & RARE OUD',
    'rare-oud': 'CAMBODIAN & LAOTIAN OUD',
    'incense-resins': 'INCENSE & SILVER RESINS',
    'private-reserve': 'PRIVATE RESERVE FLACONS',
    'gifts-sets': 'GIFTS & DISCOVERY SETS',
    'discovery-quads': 'DISCOVERY QUADS',
    'gift-coffrets': 'LUXURY GIFT COFFRETS',
    'pocket-atomizers': 'POCKET ATOMIZERS',
    'iconic-editions': 'ICONIC EDITIONS',
    'alchemy-series': 'THE ALCHEMY SERIES',
    'night-flacons': 'NIGHT FLACONS',
  };

  // Dynamic Title based on selected category or search query
  let pageTitle = 'ALL FINE FRAGRANCES';
  if (params.category && categoryTitles[params.category]) {
    pageTitle = categoryTitles[params.category];
  } else if (params.category) {
    pageTitle = params.category.replace(/-/g, ' ').toUpperCase();
  } else if (params.gender === 'men') {
    pageTitle = 'MEN PERFUMES';
  } else if (params.gender === 'women') {
    pageTitle = 'WOMEN PERFUMES';
  } else if (params.gender === 'kids') {
    pageTitle = 'KIDS PERFUMES';
  } else if (params.collection === 'common') {
    pageTitle = 'COMMON ITEM';
  } else if (params.search) {
    pageTitle = isFallback ? `SEARCH: "${params.search}"` : `RESULTS FOR "${params.search}"`;
  }

  // Related Subcategory Quick Tabs
  const quickFilters = [
    { label: 'All Fragrances', url: '/perfumes' },
    { label: 'Men', url: '/perfumes?category=men-perfumes' },
    { label: 'Women', url: '/perfumes?category=women-perfumes' },
    { label: 'Rare Oud', url: '/perfumes?category=unisex-rare-oud' },
    { label: 'Gifts & Sets', url: '/perfumes?category=gifts-sets' },
    { label: 'Parfum Extraits', url: '/perfumes?category=men-parfum-extraits' },
    { label: 'Damask Rose', url: '/perfumes?category=floral-rose' },
  ];

  return (
    <div className="max-w-[1440px] mx-auto px-6 pt-24 pb-16 space-y-8 animate-fade-in">
      <Head>
        <title>{`${pageTitle} — Fine Fragrances | ${brandName}`}</title>
        <meta name="description" content={`Explore ${pageTitle.toLowerCase()} collection at ${brandName}. Handcrafted extraits, rare absolutes, and long-lasting perfumes in Bangladesh.`} />
      </Head>

      {/* Category Header */}
      <div className="flex flex-col md:flex-row justify-between items-start md:items-end border-b border-[#DEDBD4] pb-6 gap-4">
        <div>
          <span className="text-[11px] uppercase tracking-[0.2em] font-semibold text-[#B8712E] font-mono block mb-1">
            Raaxo Dubai · COLLECTION TAXONOMY
          </span>
          <h1 className="font-serif text-[30px] md:text-[42px] text-[#0A0A0A] uppercase font-bold tracking-tight">
            {pageTitle}
          </h1>
        </div>

        {/* Minimal Bottle Counter */}
        <div className="text-[12px] text-[#6E6B66] uppercase tracking-wider font-medium font-mono">
          SHOWING <strong className="text-[#0A0A0A] font-bold">{products.length}</strong> BOTTLES
        </div>
      </div>

      {/* Quick Category & Subcategory Filter Tabs */}
      <div className="flex items-center gap-2 overflow-x-auto pb-2 scrollbar-none">
        {quickFilters.map((tab) => {
          const isActive = (tab.url === '/perfumes' && !params.category && !params.gender && !params.search)
            || (params.category && tab.url.includes(params.category));

          return (
            <Link
              key={tab.label}
              href={tab.url}
              className={`px-4 py-2 text-[11px] uppercase font-bold tracking-wider rounded-none border transition-all whitespace-nowrap ${isActive
                  ? 'bg-[#0A0A0A] text-white border-[#0A0A0A]'
                  : 'bg-[#F5F3EF] text-[#0A0A0A] border-[#DEDBD4] hover:bg-white hover:border-[#0A0A0A]'
                }`}
            >
              {tab.label}
            </Link>
          );
        })}
      </div>

      {/* Smart Fallback Recommendation Banner */}
      {isFallback && (params.search || params.category) && (
        <div className="bg-[#F5F3EF] border border-[#DEDBD4] p-5 flex flex-col sm:flex-row sm:items-center justify-between gap-3">
          <div className="space-y-1">
            <span className="text-[11px] font-bold text-[#B8712E] uppercase tracking-widest block font-mono">
              SMART RECOMMENDATION
            </span>
            <p className="text-[13px] text-[#0A0A0A] font-medium">
              We selected our premier signature perfumes for your current discovery:
            </p>
          </div>
          <Link
            href="/perfumes"
            className="text-[11px] font-bold uppercase tracking-widest underline text-[#0A0A0A] hover:text-[#B8712E] shrink-0"
          >
            DISCOVER ALL PERFUMES &rarr;
          </Link>
        </div>
      )}

      {/* Product Cards Grid */}
      {products.length === 0 ? (
        <div className="text-center py-20 bg-[#F5F3EF] border border-[#DEDBD4] space-y-4">
          <h3 className="font-serif text-[24px] text-[#0A0A0A]">No perfumes found in this subcategory.</h3>
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
