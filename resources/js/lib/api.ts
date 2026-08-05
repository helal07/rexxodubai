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
  category?: { id: number; name: string; slug: string };
}

const API_BASE = import.meta.env.VITE_API_URL || 'http://127.0.0.1:8000/api';

// Fallback data for seamless rendering during offline or fast preview
export const FALLBACK_MENU: MenuItem[] = [
  { id: 1, parent_id: null, label: "Gifts", url: "/perfumes?category=gifts", sort_order: 1, is_active: true },
  { id: 2, parent_id: null, label: "Men Perfume", url: "/perfumes?gender=men", sort_order: 2, is_active: true },
  { id: 3, parent_id: null, label: "Women Perfume", url: "/perfumes?gender=women", sort_order: 3, is_active: true },
  { id: 4, parent_id: null, label: "Kids Perfume", url: "/perfumes?gender=kids", sort_order: 4, is_active: true },
  { id: 5, parent_id: null, label: "Common Item", url: "/perfumes?collection=common", sort_order: 5, is_active: true }
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

// Data optimizing fetch functions with SSR & ISR caching
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

