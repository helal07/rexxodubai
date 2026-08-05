export interface Category {
  id: number;
  parent_id: number | null;
  name: string;
  slug: string;
  description?: string | null;
  image_url?: string | null;
  sort_order: number;
  is_active: boolean;
  products_count?: number;
  children?: Category[];
  parent?: Category | null;
}

export interface MenuItem {
  id: number;
  parent_id: number | null;
  label: string;
  url: string | null;
  column_group?: 'left' | 'highlights' | null;
  image_url?: string | null;
  sort_order: number;
  is_active: boolean;
  children?: MenuItem[];
  parent?: MenuItem | null;
}

export interface ProductImage {
  id: number;
  image_url: string;
  sort_order: number;
}

export interface Product {
  id: number;
  name: string;
  slug: string;
  category_id?: number | null;
  scent_family: string;
  concentration: string;
  sizes: string[];
  price: number;
  short_description: string;
  description: string;
  notes_top: string;
  notes_heart: string;
  notes_base: string;
  primary_image_url: string;
  secondary_image_url?: string;
  gender: 'women' | 'men' | 'unisex';
  is_featured: boolean;
  is_new_arrival: boolean;
  stock: number;
  images?: ProductImage[];
  category?: Category;
}

const API_BASE = import.meta.env.VITE_API_URL || 'http://127.0.0.1:8000/api';

// Fallback hierarchical categories and subcategories
export const FALLBACK_CATEGORIES: Category[] = [
  {
    id: 1,
    parent_id: null,
    name: "Men Perfumes",
    slug: "men-perfumes",
    description: "Architectural, smoky, leather, and wood extraits.",
    sort_order: 1,
    is_active: true,
    children: [
      { id: 11, parent_id: 1, name: "Eau de Parfum", slug: "men-eau-de-parfum", sort_order: 1, is_active: true },
      { id: 12, parent_id: 1, name: "Parfum Extraits", slug: "men-parfum-extraits", sort_order: 2, is_active: true },
      { id: 13, parent_id: 1, name: "Woody & Smoked Leather", slug: "woody-leather", sort_order: 3, is_active: true },
      { id: 14, parent_id: 1, name: "Fresh Citrus & Vetiver", slug: "fresh-citrus", sort_order: 4, is_active: true },
    ]
  },
  {
    id: 2,
    parent_id: null,
    name: "Women Perfumes",
    slug: "women-perfumes",
    description: "Sculpted florals, velvet damask roses, and warm amber vapor.",
    sort_order: 2,
    is_active: true,
    children: [
      { id: 21, parent_id: 2, name: "Floral & Damask Rose", slug: "floral-rose", sort_order: 1, is_active: true },
      { id: 22, parent_id: 2, name: "Amber & Bourbon Vanilla", slug: "amber-vanilla", sort_order: 2, is_active: true },
      { id: 23, parent_id: 2, name: "Parfum Extraits", slug: "women-parfum-extraits", sort_order: 3, is_active: true },
      { id: 24, parent_id: 2, name: "Gourmand & White Musk", slug: "gourmand-musk", sort_order: 4, is_active: true },
    ]
  },
  {
    id: 3,
    parent_id: null,
    name: "Unisex & Rare Oud",
    slug: "unisex-rare-oud",
    description: "Genderless high-perfumery blending rare agarwood and resins.",
    sort_order: 3,
    is_active: true,
    children: [
      { id: 31, parent_id: 3, name: "Cambodian & Laotian Oud", slug: "rare-oud", sort_order: 1, is_active: true },
      { id: 32, parent_id: 3, name: "Incense & Silver Resins", slug: "incense-resins", sort_order: 2, is_active: true },
      { id: 33, parent_id: 3, name: "Private Reserve Flacons", slug: "private-reserve", sort_order: 3, is_active: true },
    ]
  },
  {
    id: 4,
    parent_id: null,
    name: "Gifts & Sets",
    slug: "gifts-sets",
    description: "Curated discovery sets and luxury coffrets.",
    sort_order: 4,
    is_active: true,
    children: [
      { id: 41, parent_id: 4, name: "Discovery Quads", slug: "discovery-quads", sort_order: 1, is_active: true },
      { id: 42, parent_id: 4, name: "Luxury Gift Coffrets", slug: "gift-coffrets", sort_order: 2, is_active: true },
      { id: 43, parent_id: 4, name: "Pocket Atomizers", slug: "pocket-atomizers", sort_order: 3, is_active: true },
    ]
  },
  {
    id: 5,
    parent_id: null,
    name: "Iconic Editions",
    slug: "iconic-editions",
    description: "Master collections and evening flacons.",
    sort_order: 5,
    is_active: true,
    children: [
      { id: 51, parent_id: 5, name: "The Alchemy Series", slug: "alchemy-series", sort_order: 1, is_active: true },
      { id: 52, parent_id: 5, name: "Night Flacons", slug: "night-flacons", sort_order: 2, is_active: true },
    ]
  }
];

// Fallback data for menu items with sub-items
export const FALLBACK_MENU: MenuItem[] = [
  {
    id: 1,
    parent_id: null,
    label: "Men Perfumes",
    url: "/perfumes?category=men-perfumes",
    sort_order: 1,
    is_active: true,
    children: [
      { id: 11, parent_id: 1, label: "Eau de Parfum", url: "/perfumes?category=men-eau-de-parfum", sort_order: 1, is_active: true },
      { id: 12, parent_id: 1, label: "Parfum Extraits", url: "/perfumes?category=men-parfum-extraits", sort_order: 2, is_active: true },
      { id: 13, parent_id: 1, label: "Woody & Smoked Leather", url: "/perfumes?category=woody-leather", sort_order: 3, is_active: true },
      { id: 14, parent_id: 1, label: "Fresh Citrus & Vetiver", url: "/perfumes?category=fresh-citrus", sort_order: 4, is_active: true },
    ]
  },
  {
    id: 2,
    parent_id: null,
    label: "Women Perfumes",
    url: "/perfumes?category=women-perfumes",
    sort_order: 2,
    is_active: true,
    children: [
      { id: 21, parent_id: 2, label: "Floral & Damask Rose", url: "/perfumes?category=floral-rose", sort_order: 1, is_active: true },
      { id: 22, parent_id: 2, label: "Amber & Bourbon Vanilla", url: "/perfumes?category=amber-vanilla", sort_order: 2, is_active: true },
      { id: 23, parent_id: 2, label: "Parfum Extraits", url: "/perfumes?category=women-parfum-extraits", sort_order: 3, is_active: true },
      { id: 24, parent_id: 2, label: "Gourmand & White Musk", url: "/perfumes?category=gourmand-musk", sort_order: 4, is_active: true },
    ]
  },
  {
    id: 3,
    parent_id: null,
    label: "Unisex & Rare Oud",
    url: "/perfumes?category=unisex-rare-oud",
    sort_order: 3,
    is_active: true,
    children: [
      { id: 31, parent_id: 3, label: "Cambodian & Laotian Oud", url: "/perfumes?category=rare-oud", sort_order: 1, is_active: true },
      { id: 32, parent_id: 3, label: "Incense & Silver Resins", url: "/perfumes?category=incense-resins", sort_order: 2, is_active: true },
      { id: 33, parent_id: 3, label: "Private Reserve Flacons", url: "/perfumes?category=private-reserve", sort_order: 3, is_active: true },
    ]
  },
  {
    id: 4,
    parent_id: null,
    label: "Gifts & Sets",
    url: "/perfumes?category=gifts-sets",
    sort_order: 4,
    is_active: true,
    children: [
      { id: 41, parent_id: 4, label: "Discovery Quads", url: "/perfumes?category=discovery-quads", sort_order: 1, is_active: true },
      { id: 42, parent_id: 4, label: "Luxury Gift Coffrets", url: "/perfumes?category=gift-coffrets", sort_order: 2, is_active: true },
      { id: 43, parent_id: 4, label: "Pocket Atomizers", url: "/perfumes?category=pocket-atomizers", sort_order: 3, is_active: true },
    ]
  },
  {
    id: 5,
    parent_id: null,
    label: "Iconic Editions",
    url: "/perfumes?category=iconic-editions",
    sort_order: 5,
    is_active: true,
    children: [
      { id: 51, parent_id: 5, label: "The Alchemy Series", url: "/perfumes?category=alchemy-series", sort_order: 1, is_active: true },
      { id: 52, parent_id: 5, label: "Night Flacons", url: "/perfumes?category=night-flacons", sort_order: 2, is_active: true },
    ]
  },
  {
    id: 6,
    parent_id: null,
    label: "All Fragrances",
    url: "/perfumes",
    sort_order: 6,
    is_active: true,
  }
];

export const FALLBACK_PRODUCTS: Product[] = [
  {
    id: 1,
    name: "L'Ombre d'Ambre",
    slug: "l-ombre-d-ambre",
    scent_family: "Floral Amber",
    concentration: "Eau de Parfum",
    sizes: ["50ml", "100ml"],
    price: 285,
    short_description: "A dark velvet amber infused with crushed Damask rose and liquid resin.",
    description: "L'Ombre d'Ambre evokes the quiet tension between light and dark. Hand-distilled amber absolute is tempered by Turkish rose and warm cedarwood, creating a trail that lingers long into the evening like vapor against warm skin.",
    notes_top: "Turkish Rose, Cardamom, Pink Pepper",
    notes_heart: "Amber Resin, Orris Butter, Jasmine Sambac",
    notes_base: "Laotian Oud, Creamy Sandalwood, Bourbon Vanilla",
    primary_image_url: "https://images.unsplash.com/photo-1594035910387-fea47794261f?auto=format&fit=crop&w=1000&q=85",
    secondary_image_url: "https://images.unsplash.com/photo-1547887537-6158d64c35b3?auto=format&fit=crop&w=1000&q=85",
    gender: "women",
    is_featured: true,
    is_new_arrival: true,
    stock: 25
  },
  {
    id: 2,
    name: "Cuir Noir Extrait",
    slug: "cuir-noir",
    scent_family: "Woody Leather",
    concentration: "Parfum",
    sizes: ["100ml"],
    price: 340,
    short_description: "Raw leather softened by smoked cade wood and wild birch tar.",
    description: "An homage to modern architectural forms. Cuir Noir combines rich, vegetable-tanned leather notes with crisp bergamot and dry vetiver.",
    notes_top: "Calabrian Bergamot, Black Pepper",
    notes_heart: "Russian Leather, Violet Leaf, Saffron",
    notes_base: "Smoked Birch Tar, Haitian Vetiver, Ambergris",
    primary_image_url: "https://images.unsplash.com/photo-1523293182086-7651a899d37f?auto=format&fit=crop&w=1000&q=85",
    secondary_image_url: "https://images.unsplash.com/photo-1592945403244-b3fbafd7f539?auto=format&fit=crop&w=1000&q=85",
    gender: "men",
    is_featured: true,
    is_new_arrival: false,
    stock: 18
  },
  {
    id: 3,
    name: "Velours de Rose",
    slug: "velours-de-rose",
    scent_family: "Floral Amber",
    concentration: "Eau de Parfum",
    sizes: ["50ml", "100ml"],
    price: 260,
    short_description: "Opulent May roses drenched in golden honey and rare frankincense.",
    description: "Velours de Rose captures the sensory weight of velvet. Rare May roses harvested at dawn are layered over frankincense and rich patchouli.",
    notes_top: "May Rose, Mandarin Blossom",
    notes_heart: "Honey accord, Frankincense, Geranium",
    notes_base: "Patchouli Heart, Benzoin, Musk",
    primary_image_url: "https://images.unsplash.com/photo-1588405748880-12d1d2a59f75?auto=format&fit=crop&w=1000&q=85",
    secondary_image_url: "https://images.unsplash.com/photo-1547887537-6158d64c35b3?auto=format&fit=crop&w=1000&q=85",
    gender: "women",
    is_featured: true,
    is_new_arrival: true,
    stock: 30
  },
  {
    id: 4,
    name: "Vapour d'Argent",
    slug: "vapour-d-argent",
    scent_family: "Rare Oud",
    concentration: "Parfum",
    sizes: ["50ml", "100ml"],
    price: 390,
    short_description: "Ethereal metallic incense intertwined with crystalline musk.",
    description: "Vapour d'Argent is pure olfactory sculpture. A translucent blend of cold incense, mineral notes, and rare white oud.",
    notes_top: "Aldehydes, White Iris, Mint Leaf",
    notes_heart: "Silver Frankincense, Lily of the Valley",
    notes_base: "White Oud, Crystal Musk, Cedarwood",
    primary_image_url: "https://images.unsplash.com/photo-1616949755610-8c9bbc08f138?auto=format&fit=crop&w=1000&q=85",
    secondary_image_url: "https://images.unsplash.com/photo-1594035910387-fea47794261f?auto=format&fit=crop&w=1000&q=85",
    gender: "unisex",
    is_featured: true,
    is_new_arrival: true,
    stock: 12
  }
];

export async function getCategoriesTree(): Promise<Category[]> {
  try {
    const res = await fetch(`${API_BASE}/categories`);
    if (!res.ok) return FALLBACK_CATEGORIES;
    const data = await res.json();
    return Array.isArray(data) && data.length > 0 ? data : FALLBACK_CATEGORIES;
  } catch (err) {
    return FALLBACK_CATEGORIES;
  }
}

export async function getMenuTree(): Promise<MenuItem[]> {
  try {
    const res = await fetch(`${API_BASE}/menu`, {
      cache: 'no-store'
    });
    if (!res.ok) return FALLBACK_MENU;
    const data = await res.json();
    return Array.isArray(data) && data.length > 0 ? data : FALLBACK_MENU;
  } catch (err) {
    return FALLBACK_MENU;
  }
}

export async function getSiteSettings(): Promise<Record<string, string>> {
  try {
    const res = await fetch(`${API_BASE}/settings`);
    if (!res.ok) return {};
    return await res.json();
  } catch (err) {
    return {};
  }
}

export async function getProducts(params?: Record<string, string>): Promise<{ data: Product[]; total: number; isFallback?: boolean }> {
  try {
    const query = new URLSearchParams(params).toString();
    const res = await fetch(`${API_BASE}/products${query ? `?${query}` : ''}`);
    if (!res.ok) throw new Error('API offline');
    const json = await res.json();
    let resultList: Product[] = json.data || json || FALLBACK_PRODUCTS;

    if (params?.search) {
      const q = params.search.toLowerCase().trim();
      resultList = resultList.filter(p =>
        p.name.toLowerCase().includes(q) ||
        p.scent_family.toLowerCase().includes(q) ||
        p.concentration.toLowerCase().includes(q) ||
        (p.notes_top && p.notes_top.toLowerCase().includes(q)) ||
        (p.notes_heart && p.notes_heart.toLowerCase().includes(q)) ||
        (p.notes_base && p.notes_base.toLowerCase().includes(q)) ||
        (p.short_description && p.short_description.toLowerCase().includes(q))
      );

      if (resultList.length === 0) {
        return { data: FALLBACK_PRODUCTS, total: FALLBACK_PRODUCTS.length, isFallback: true };
      }
    }

    return {
      data: resultList,
      total: resultList.length
    };
  } catch (err) {
    let filtered = [...FALLBACK_PRODUCTS];
    if (params?.search) {
      const q = params.search.toLowerCase().trim();
      const matched = filtered.filter(p =>
        p.name.toLowerCase().includes(q) ||
        p.scent_family.toLowerCase().includes(q) ||
        p.concentration.toLowerCase().includes(q) ||
        (p.notes_top && p.notes_top.toLowerCase().includes(q)) ||
        (p.notes_heart && p.notes_heart.toLowerCase().includes(q)) ||
        (p.notes_base && p.notes_base.toLowerCase().includes(q)) ||
        (p.short_description && p.short_description.toLowerCase().includes(q))
      );
      if (matched.length > 0) {
        return { data: matched, total: matched.length };
      } else {
        return { data: FALLBACK_PRODUCTS, total: FALLBACK_PRODUCTS.length, isFallback: true };
      }
    }

    if (params?.gender && params.gender !== 'all') {
      filtered = filtered.filter(p => p.gender === params.gender || p.gender === 'unisex');
    }
    if (params?.scent_family) {
      filtered = filtered.filter(p => p.scent_family.toLowerCase().includes(params.scent_family.toLowerCase()));
    }
    return { data: filtered, total: filtered.length };
  }
}

export async function getProductBySlug(slug: string): Promise<{ product: Product; related: Product[] } | null> {
  try {
    const res = await fetch(`${API_BASE}/products/${slug}`, {
      next: { revalidate: 60, tags: [`product-${slug}`] }
    });
    if (!res.ok) throw new Error('Not found');
    return await res.json();
  } catch (err) {
    const found = FALLBACK_PRODUCTS.find(p => p.slug === slug || String(p.id) === slug);
    if (!found) return { product: FALLBACK_PRODUCTS[0], related: FALLBACK_PRODUCTS.slice(1) };
    const related = FALLBACK_PRODUCTS.filter(p => p.id !== found.id);
    return { product: found, related };
  }
}
