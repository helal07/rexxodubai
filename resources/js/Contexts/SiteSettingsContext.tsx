'use client';

import React, { createContext, useContext, useState, useEffect } from 'react';
import { MenuItem, FALLBACK_MENU } from '@/lib/api';

export interface SiteSettings {
  siteName: string;
  tagline: string;
  logo_url?: string;
  favicon_url?: string;
  site_logo?: string;
  site_favicon?: string;
  hero_video_url?: string;
  hero_video?: string;
  hero_poster_url?: string;
  hero_subtitle?: string;
  hero_title?: string;
  hero_link_1_text?: string;
  hero_link_1_url?: string;
  hero_link_2_text?: string;
  hero_link_2_url?: string;
  phone: string;
  whatsapp: string;
  email: string;
  currency: string;
  announcement: string;
  footerText: string;
  [key: string]: any;
}

const DEFAULT_SETTINGS: SiteSettings = {
  siteName: 'REXXO BD',
  tagline: 'Fine Fragrance & Luxury Extraits',
  logo_url: '',
  favicon_url: '/uploads/settings/favicon_1785930191.ico',
  site_favicon: '/uploads/settings/favicon_1785930191.ico',
  hero_video_url: 'https://assets.mixkit.co/videos/preview/mixkit-perfume-bottle-in-a-dark-environment-42525-large.mp4',
  hero_video: 'https://assets.mixkit.co/videos/preview/mixkit-perfume-bottle-in-a-dark-environment-42525-large.mp4',
  hero_poster_url: 'https://images.unsplash.com/photo-1594035910387-fea47794261f?auto=format&fit=crop&w=2400&q=90',
  hero_subtitle: 'NEW COLLECTION',
  hero_title: 'Fall Winter 2026',
  hero_link_1_text: 'FOR HER',
  hero_link_1_url: '/perfumes?gender=women',
  hero_link_2_text: 'FOR HIM',
  hero_link_2_url: '/perfumes?gender=men',
  phone: '+880 1700 000 000',
  whatsapp: '8801700000000',
  email: 'client.service.bd@rexxobd.com',
  currency: 'USD ($)',
  announcement: 'Complimentary luxury gift box & free worldwide express delivery on orders over $250.',
  footerText: 'ReXxo Bd Perfumes Ltd. All Rights Reserved.',
};

interface SiteSettingsContextType {
  settings: SiteSettings;
  menuItems: MenuItem[];
  updateSettings: (newSettings: Partial<SiteSettings>) => void;
  updateMenuItems: (newItems: MenuItem[]) => void;
  resetSettings: () => void;
}

const SiteSettingsContext = createContext<SiteSettingsContextType | undefined>(undefined);

export function SiteSettingsProvider({ children, initialSettings = {} }: { children: React.ReactNode, initialSettings?: Partial<SiteSettings> }) {
  const [settings, setSettings] = useState<SiteSettings>(() => {
    return { ...DEFAULT_SETTINGS, ...initialSettings };
  });
  const [menuItems, setMenuItems] = useState<MenuItem[]>(FALLBACK_MENU);

  useEffect(() => {
    if (initialSettings && Object.keys(initialSettings).length > 0) {
      setSettings(prev => ({ ...prev, ...initialSettings }));
    }
  }, [initialSettings]);

  useEffect(() => {
    // Dynamically update document favicon and title if settings change
    const faviconHref = settings.favicon_url || settings.site_favicon;
    if (faviconHref) {
      let link: HTMLLinkElement | null = document.querySelector("link[rel~='icon']");
      if (!link) {
        link = document.createElement('link');
        link.rel = 'icon';
        document.getElementsByTagName('head')[0].appendChild(link);
      }
      link.href = faviconHref;
    }
  }, [settings.favicon_url, settings.site_favicon]);

  const updateSettings = (newSettings: Partial<SiteSettings>) => {
    setSettings((prev) => {
      const updated = { ...prev, ...newSettings };
      try {
        localStorage.setItem('rexxo_site_settings', JSON.stringify(updated));
        window.dispatchEvent(new Event('storage'));
      } catch (e) {
        console.error('Failed to save site settings', e);
      }
      return updated;
    });
  };

  const updateMenuItems = (newItems: MenuItem[]) => {
    setMenuItems(newItems);
    try {
      localStorage.setItem('rexxo_menu_items', JSON.stringify(newItems));
      window.dispatchEvent(new Event('storage'));
    } catch (e) {
      console.error('Failed to save menu items', e);
    }
  };

  const resetSettings = () => {
    setSettings(DEFAULT_SETTINGS);
    setMenuItems(FALLBACK_MENU);
    try {
      localStorage.removeItem('rexxo_site_settings');
      localStorage.removeItem('rexxo_menu_items');
      window.dispatchEvent(new Event('storage'));
    } catch (e) {
      console.error('Failed to reset site settings', e);
    }
  };

  return (
    <SiteSettingsContext.Provider value={{ settings, menuItems, updateSettings, updateMenuItems, resetSettings }}>
      {children}
    </SiteSettingsContext.Provider>
  );
}

export function useSiteSettings() {
  const context = useContext(SiteSettingsContext);
  if (!context) {
    throw new Error('useSiteSettings must be used within a SiteSettingsProvider');
  }
  return context;
}
