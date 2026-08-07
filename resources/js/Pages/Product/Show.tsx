import React from 'react';
import { Head, usePage } from '@inertiajs/react';
import ProductCard from '@/Components/ProductCard';
import PDPClient from '@/Components/PDPClient';

export default function ProductDetailPage({ product, related }: any) {
  const { siteSettings, apiSettings }: any = usePage().props;
  const settings = siteSettings || apiSettings || {};
  const brandName = settings.siteName || 'RaaxO BD';
  const siteUrl = typeof window !== 'undefined' ? window.location.origin : (settings.app_url || 'https://raaxodubai.com');

  if (!product) {
    return (
      <div className="max-w-[1440px] mx-auto px-6 py-24 text-center">
        <Head title={`Perfume Not Found — ${brandName}`} />
        <h1 className="font-serif text-[32px] text-[#0A0A0A] mb-4">Perfume bottle not found.</h1>
        <a href="/perfumes" className="text-[11px] uppercase font-bold tracking-widest text-[#B8712E] underline">
          RETURN TO CATALOG →
        </a>
      </div>
    );
  }

  // Compute dynamic Per-Product SEO values with fallbacks
  const metaTitle = product.meta_title?.trim()
    ? product.meta_title
    : `${product.name} — ${product.scent_family || product.concentration || 'Luxury Fragrance'} | ${brandName}`;

  const metaDescription = product.meta_description?.trim()
    ? product.meta_description
    : (product.short_description?.trim()
        ? `${product.short_description} Order authentic ${product.name} by ${brandName} with fast delivery across Bangladesh.`
        : `Discover ${product.name} (${product.concentration || 'Extrait de Parfum'}) by ${brandName}. Handcrafted with rare botanical extracts and exceptional longevity.`);

  const metaKeywords = product.meta_keywords?.trim()
    ? product.meta_keywords
    : [
        product.name,
        product.scent_family,
        product.concentration,
        product.notes_top,
        product.notes_heart,
        product.notes_base,
        'perfume bangladesh',
        'luxury fragrance bd',
        'buy extrait de parfum',
        brandName
      ].filter(Boolean).join(', ');

  const ogImage = product.og_image_url || product.primary_image_url || settings.logo_url || settings.site_logo || '';
  const productUrl = `${siteUrl}/product/${product.slug}`;

  // Schema.org Product Structured Data (JSON-LD) for Google Rich Snippets
  const schemaProductJson = {
    "@context": "https://schema.org/",
    "@type": "Product",
    "name": product.name,
    "image": [product.primary_image_url, product.secondary_image_url, ogImage].filter(Boolean),
    "description": metaDescription,
    "sku": `RAAXO-${product.id}`,
    "brand": {
      "@type": "Brand",
      "name": brandName
    },
    "category": product.category?.name || product.scent_family || 'Fine Fragrance',
    "offers": {
      "@type": "Offer",
      "url": productUrl,
      "priceCurrency": "BDT",
      "price": Number(product.price).toFixed(2),
      "priceValidUntil": "2028-12-31",
      "itemCondition": "https://schema.org/NewCondition",
      "availability": (product.stock && product.stock > 0)
        ? "https://schema.org/InStock"
        : "https://schema.org/OutOfStock",
      "seller": {
        "@type": "Organization",
        "name": brandName
      }
    }
  };

  return (
    <>
      <Head>
        <title>{metaTitle}</title>
        <meta name="description" content={metaDescription} />
        <meta name="keywords" content={metaKeywords} />
        <link rel="canonical" href={productUrl} />

        {/* Open Graph (Facebook / WhatsApp / LinkedIn / Instagram) */}
        <meta property="og:type" content="product" />
        <meta property="og:site_name" content={brandName} />
        <meta property="og:title" content={metaTitle} />
        <meta property="og:description" content={metaDescription} />
        <meta property="og:url" content={productUrl} />
        {ogImage && <meta property="og:image" content={ogImage} />}
        <meta property="product:price:amount" content={String(product.price)} />
        <meta property="product:price:currency" content="BDT" />

        {/* Twitter Card */}
        <meta name="twitter:card" content="summary_large_image" />
        <meta name="twitter:title" content={metaTitle} />
        <meta name="twitter:description" content={metaDescription} />
        {ogImage && <meta name="twitter:image" content={ogImage} />}

        {/* Schema.org Product Rich Snippet JSON-LD */}
        <script type="application/ld+json">
          {JSON.stringify(schemaProductJson)}
        </script>
      </Head>

      <div className="max-w-[1440px] mx-auto px-6 pt-24 pb-8 space-y-20">
        <PDPClient product={product} />

        {/* Below Fold: You May Also Like Section */}
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
    </>
  );
}
