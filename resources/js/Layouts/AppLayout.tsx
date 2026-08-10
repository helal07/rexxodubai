import React from 'react';
import { usePage } from '@inertiajs/react';
import '../../css/app.css';
import { CartProvider } from '@/Contexts/CartContext';
import { SiteSettingsProvider } from '@/Contexts/SiteSettingsContext';
import Header from '@/Components/Header';
import CartDrawer from '@/Components/CartDrawer';
import Footer from '@/Components/Footer';
import CookieBanner from '@/Components/CookieBanner';

export default function AppLayout({ children }: { children: React.ReactNode }) {
  const { menuTree, categoriesTree, apiSettings, cmsData } = usePage().props as any;

  // Merge CMS global settings (logo, favicon) into initial settings
  const mergedSettings = {
    ...apiSettings,
    ...(cmsData?.global?.logo_url ? { logo_url: cmsData.global.logo_url } : {}),
    ...(cmsData?.global?.favicon_url ? { favicon_url: cmsData.global.favicon_url } : {})
  };

  return (
    <div className="min-h-screen flex flex-col antialiased selection:bg-black selection:text-white font-sans text-[#0A0A0A] bg-white">
      <SiteSettingsProvider initialSettings={mergedSettings}>
        <CartProvider>
          <Header initialMenu={menuTree} initialCategories={categoriesTree} />
          <main className="flex-grow">{children}</main>
          <CartDrawer />
          <Footer />
          <CookieBanner />
        </CartProvider>
      </SiteSettingsProvider>
    </div>
  );
}
