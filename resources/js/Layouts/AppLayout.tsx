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
  const { menuTree, categoriesTree, apiSettings } = usePage().props as any;

  return (
    <div className="min-h-screen flex flex-col antialiased selection:bg-black selection:text-white font-sans text-[#0A0A0A] bg-white">
      <SiteSettingsProvider initialSettings={apiSettings}>
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
