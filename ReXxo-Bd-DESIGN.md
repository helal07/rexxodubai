# ReXxo Bd — Design Specification
Luxury perfume e-commerce, styled in the language of prada.com/ww/en.html

---

## 1. Brand Direction

ReXxo Bd is a perfume house, not a fashion house — the design borrows Prada's restraint and editorial confidence, but every choice is re-grounded in scent: glass, amber, vapor, the ritual of applying fragrance. Nothing generic (no cream/terracotta AI-default palette, no numbered 01/02/03 markers unless something is genuinely sequential).

**One-line brief:** A quiet, confident black-and-paper site where amber glass is the only color that speaks, and perfume bottles are photographed like sculpture.

---

## 2. Design Tokens

### Color
| Token | Hex | Use |
|---|---|---|
| `ink` | `#0A0A0A` | Primary text, logo, nav links |
| `paper` | `#FFFFFF` | Primary background |
| `bone` | `#F5F3EF` | Alternate section background |
| `amber` | `#B8712E` | Signature accent — CTAs, hover states, price tags, active nav underline. Used sparingly, never as a background fill |
| `smoke` | `#6E6B66` | Secondary/muted text, captions |
| `hairline` | `#DEDBD4` | Borders, dividers, input outlines |
| `ink-70` | `rgba(10,10,10,0.7)` | Overlay on hero imagery |

Rule: amber never covers more than ~5% of any viewport. It marks a single point of attention (a hover underline, a "Add to Bag" button, a price), never a block.

### Typography
- **Display** — `Fraunces` (Google Fonts), high-contrast serif with a slight quirk in the italics. Used for the wordmark, hero headlines, and section titles. Set tight (-1% to -2% tracking) at large sizes.
- **Nav / Body** — `Inter` — neutral grotesk, used for navigation, body copy, product names. Nav items set in small caps, +0.08em tracking, 12–13px.
- **Utility / Labels** — `Inter` 11px, uppercase, +0.12em tracking, `smoke` color — used for eyebrows like "EAU DE PARFUM · 100ML" or "NEW ARRIVAL".

Type scale (desktop): 64/44/32/24/18/16/14/12px. Mobile hero drops to 34px.

### Spacing & Grid
- 12-column grid, 1440px max content width, 24px gutter.
- Section vertical rhythm: 120px desktop / 64px mobile between major sections.
- No rounded corners on structural elements (buttons get a 2px radius at most — Prada's language is hard-edged, not soft).

### Motion
- Header hides on scroll-down, reveals on scroll-up (Prada's exact pattern).
- Mega-menu: 180ms fade + 8px slide-down on open, no bounce/spring easing — ease-out only.
- Product image hover: cross-fade to a secondary lifestyle shot, 300ms.
- Respect `prefers-reduced-motion` — disable the hide/reveal and cross-fades, snap instead.

---

## 3. Signature Element

**The Scent Trail.** On the product listing and product detail pages, hovering a bottle releases a soft, slow-drifting amber-to-transparent radial blur that trails the cursor by ~150ms (like diffusing fragrance), rendered behind the product image only — never over text. This is the one deliberate risk; everywhere else stays quiet and disciplined. On mobile (no hover), this is simply omitted — don't fake it with a tap animation.

---

## 4. Site Structure

```
/                          Home
/perfumes                  All Perfumes (PLP)
/perfumes/women            PLP filtered
/perfumes/men              PLP filtered
/perfumes/unisex           PLP filtered
/product/{slug}            PDP
/cart                      Cart
/checkout                  Checkout
/account, /account/orders  Customer account
/about, /contact           Static/editorial pages
--- Admin ---
/admin/login
/admin/menus               Menu builder (CRUD)
/admin/products             Product CRUD
/admin/orders
```

---

## 5. Header & Mega-Menu (the editable part)

Structure mirrors Prada exactly: a thin top utility bar (shipping note / country), then a centered logo row, then the primary nav row. Each top-level nav item can open a mega-menu panel with up to 3 columns: a category column, a "Highlights" column (2–4 image tiles), and an optional editorial banner.

**This entire nav must be data-driven** — nothing hardcoded in the frontend — so the admin dashboard can add/edit/reorder/delete menu items without a deploy.

Menu data shape (what the admin CRUD manages):
```
MenuItem {
  id
  parent_id        (null = top-level)
  label            "Women's Fragrances"
  url              "/perfumes/women"
  column_group     "left" | "highlights" | null   (which mega-menu column it renders in)
  image_url         nullable, for Highlights tiles
  sort_order
  is_active
}
```
Top-level items render as the primary nav bar. Children with `column_group = left` render as plain text links in the first column. Children with `column_group = highlights` render as image tiles (Prada's "Prada Galleria / Prada Carry" style tile row). This gives you Prada's exact visual pattern while staying fully editable.

---

## 6. Homepage

1. **Hero** — full-bleed video or still of a single bottle against dark backdrop, wordmark overlay, one line of copy, two CTAs ("For Her" / "For Him" — or here, "Shop Women" / "Shop Men").
2. **Category strip** — 4 tiles: Women's Fragrances / Men's Fragrances / Exclusive Collections / Gift Sets. Image + label only, no description (Prada's pattern).
3. **Editorial banner** — one large lifestyle image + a short line of brand copy + "Discover" link. This is where ReXxo Bd's own voice lives (not Prada's copy).
4. **New Arrivals** — horizontal scroll/grid of 4–6 bottles, each with the Scent Trail hover.
5. **Two-tile split** — "Tradition Reconsidered" style pairing (e.g. Women's vs Men's collection banners side by side).
6. **Journal / News** — 3 editorial cards (launch stories, ingredient stories) — ReXxo Bd's answer to "Pradasphere News."
7. **Newsletter** — single email input, minimal, on `bone` background.
8. **Footer** — see below.

---

## 7. Product Listing Page (PLP)

- Left filter rail (desktop) / bottom sheet (mobile): Scent Family, Concentration (EDP/EDT/Parfum), Size, Price.
- Grid: 3-up desktop, 2-up tablet, 1-up mobile. Each card: bottle image (cross-fades on hover per Scent Trail spec), name in Display face, eyebrow label (concentration + size), price in `amber`.
- Sort: Newest / Price / Alphabetical — plain text dropdown, no icon-heavy UI.

## 8. Product Detail Page (PDP)

- Left: sticky image gallery (thumbnails below on desktop, swipe on mobile).
- Right: eyebrow (scent family), product name in Display 32px, price, size selector (pill buttons, not a dropdown), "Add to Bag" (full-width, `ink` background, `paper` text, hover inverts to `amber` background), accordion for Notes (Top/Heart/Base), Description, Shipping.
- Below fold: "You May Also Like" — 4-up carousel, same card component as PLP.

## 9. Cart / Checkout

- Cart: slide-in drawer from the right (Prada's "shopping bag" pattern), line items with size/qty stepper, subtotal, single "Checkout" CTA.
- Checkout: single-page, 3 sections (Contact → Shipping → Payment), progress shown as thin `hairline` rule that fills with `amber` as sections complete — no numbered steps (nothing here is a meaningful sequence worth counting, it's Prada's understated pattern, not a wizard).

## 10. Footer

Four columns: Client Service (contact, WhatsApp/email/phone placeholders), Company (About, Sustainability, Careers), Legal (Privacy, Terms, Cookie Policy), Follow (social icons, text links not icon-only — accessible). Bottom bar: copyright + country/language selector, exactly Prada's footer rhythm.

---

## 11. Component Inventory
`Header`, `MegaMenu`, `MobileNavDrawer`, `Hero`, `CategoryTile`, `ProductCard`, `FilterRail`, `SizeSelector`, `AccordionNotes`, `CartDrawer`, `NewsletterBar`, `Footer`, `Breadcrumb`, `EmptyState` (for empty cart/wishlist — written in the interface's voice: "Your bag is empty. Explore the collection.")

## 12. Accessibility & Quality Floor
- Visible keyboard focus ring on all interactive elements (2px `amber` outline).
- Mega-menu fully operable by keyboard (Tab/Escape/Arrow keys).
- Color contrast: `smoke` on `paper` meets AA for body text; `amber` is never used for body text, only accents/buttons with sufficient size.
- Reduced motion respected as noted above.
- Responsive down to 360px width.
