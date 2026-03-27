# Lotsha Primary School — Site Handoff Document

> This document is the living record of development decisions, technical audits, and future work for the Lotsha Primary School website. Update this file whenever major changes are made.

---

## Project Overview

| Field | Details |
|---|---|
| **Project** | Lotsha Primary School Website |
| **Framework** | Astro v5 |
| **Styling** | Tailwind CSS v4 (via `@tailwindcss/vite`) |
| **CDN / Images** | Cloudinary (`djpfl2uwe`) |
| **Dev Server** | `npm run dev` (port 4321) |

---

## Cloudinary Asset Organization

All images are served from Cloudinary account `djpfl2uwe` using the following folder structure:

```
lotsha-web/
├── achievers/
├── backgrounds/
├── documents/
├── hero/             ← Hero slider backgrounds (slide1–5)
├── logo/             ← School logos (logo5.png, lotsha_logo.png)
├── memories/
├── staff/            ← Staff profile photos (principal, educators)
├── student_life/
└── yearbook/
    └── [year]/
        └── grades/
            └── gradeRR/, grade1/ … grade7/
```

**Key note:** Cloudinary appends a 6-character hash to uploaded filenames (e.g., `slide1_ydp9jd`). An automated Node.js script (`/tmp/migrate-v3.mjs`) was used to map local paths → Cloudinary URLs. Re-run this script whenever new images are uploaded to sync them into the codebase.

**Image format:** All Cloudinary URLs use `f_avif,q_auto` for AVIF delivery. **Exception:** `principal_qqvw0x.avif` uses `f_auto,q_auto,w_600` because the original file exceeds Cloudinary's 30MP AVIF encoding limit.

---

## Changelog

### 2026-03-18
- ✅ Installed `astro-cloudinary` package
- ✅ Added `.env` with `CLOUDINARY_URL` and `PUBLIC_CLOUDINARY_CLOUD_NAME`
- ✅ Automated full codebase migration from local `/public/assets/images/` to Cloudinary URLs (37 images across 17 files)
- ✅ Enforced `f_avif,q_auto` on all Cloudinary image URLs site-wide
- ✅ Fixed principal image with `f_auto,w_600` workaround for AVIF pixel limit
- ✅ Updated logo in Navigation and SecondaryNav to Cloudinary
- ✅ **Lighthouse fixes applied:**
  - Trimmed Google Fonts from 10 families → 3 (Lato, Outfit, Bodoni Moda) — removes major render-blocking
  - Added unique `<meta name="description">` to all 20 pages
  - Added `<link rel="canonical">` to every page via Layout
  - Added Open Graph (`og:title`, `og:description`, `og:image`, etc.) tags
  - Added Twitter Card meta tags
  - Added JSON-LD `School` structured data to every page
  - Removed `transparenttextures.com` external fetch (was loading on every page)
  - Added `loading="lazy"` to all below-the-fold Cloudinary images
  - Added `loading="eager"` to hero/LCP images (slide1, principal)

---

## Lighthouse Audit — 2026-03-18

**Baseline scores (before improvements):**

| Metric | Score | Status |
|---|---|---|
| Performance | 69 | 🔴 Needs Work |
| Accessibility | 90 | 🟢 Good |
| Best Practices | 73 | 🟠 Needs Work |
| SEO | 82 | 🟠 Needs Work |

---

## Performance Improvement Plan

### 🔴 Priority 1 — Performance (69 → target 90+)

#### 1.1 Reduce Render-Blocking Google Fonts
**Problem:** `Layout.astro` loads **10 font families** in one massive Google Fonts `<link>` tag. This is one of the single largest performance bottlenecks — it blocks rendering and adds ~800ms to FCP.

**Fix:**
- Trim the font stack to only the 2–3 fonts actually used throughout the site (likely `Lato`, `Outfit`, `Bodoni Moda`).
- Add `font-display: optional` or `font-display: swap` to the Google Fonts `&display=swap` param.
- Preload the most critical font: `<link rel="preload" as="font" ...>`.

**Files to change:** `src/layouts/Layout.astro` (line 31)

---

#### 1.2 Add `width` and `height` Attributes to All `<img>` Tags
**Problem:** No `<img>` tags in the project have `width` and `height` attributes. This causes **Cumulative Layout Shift (CLS)** — images "pop in" and push content down, harming both UX and the Performance score.

**Fix:** Add explicit `width` and `height` to every `<img>` tag. For Cloudinary images, use the known dimensions or approximately match the render size.

**Files to change:** `staff.astro`, `index.astro`, `Navigation.astro`, `SecondaryNav.astro`

---

#### 1.3 Lazy-Load Below-the-Fold Images
**Problem:** All staff images, gallery thumbnails, and secondary content images load eagerly. This wastes bandwidth on first load.

**Fix:** Add `loading="lazy"` to all `<img>` tags that are not above the fold. Reserve `loading="eager"` only for the hero/LCP image.

---

#### 1.4 Remove External Texture Background from Layout
**Problem:** `Layout.astro` (line 57) fetches a texture from `transparenttextures.com` on every page load — an uncached external request.

**Fix:** Self-host the texture in `/public/` or remove it entirely (it's nearly invisible at `opacity-[0.03]`).

---

### 🟠 Priority 2 — Best Practices (73 → target 90+)

#### 2.1 Add `meta name="description"` to All Pages
**Problem:** No page in the project has a `<meta name="description">` tag. This tanks both Best Practices and SEO scores.

**Fix:** Add a `description` prop to the `Layout.astro` component and render it as:
```html
<meta name="description" content={description} />
```
Then pass a unique description from each page.

---

#### 2.2 Add Open Graph / Twitter Social Tags
**Problem:** No OG (`og:title`, `og:image`, `og:description`) or Twitter card meta tags exist.

**Fix:** Add these to `Layout.astro` head for rich social sharing previews.

---

#### 2.3 Fix Console Errors
Check and resolve any JavaScript errors that appear in the browser console (e.g. related to `astro:after-swap` or undefined element IDs), as these directly reduce the Best Practices score.

---

### 🟠 Priority 3 — SEO (82 → target 95+)

#### 3.1 Meta Descriptions (see 2.1 above)

#### 3.2 Add `aria-label` and `alt` Text Audit
**Problem:** Some interactive elements (icon-only links/buttons) may be missing accessible `aria-label` attributes.

**Fix:** Audit all `<a>` and `<button>` elements that have no text content and ensure they have clear `aria-label` attributes.

---

#### 3.3 Add Structured Data (JSON-LD)
**Opportunity:** Adding `SchoolOrganization` structured data to the homepage will enable rich results in Google Search and improve crawlability.

**Fix:** Add a `<script type="application/ld+json">` block to `Layout.astro` or `index.astro` with the school's name, address, and contact info.

---

#### 3.4 Add `<link rel="canonical">` Tags
**Problem:** No canonical tags exist. This can confuse crawlers on pages with query params.

**Fix:** Add `<link rel="canonical" href={Astro.url.href} />` to `Layout.astro`.

---

## Files Needing Attention

| File | Issue |
|---|---|
| `src/layouts/Layout.astro` | 10 Google Fonts families, no description meta, no OG tags, external texture |
| `src/pages/index.astro` | No `width`/`height` attrs on principal image |
| `src/components/Navigation.astro` | No `width`/`height` on logo, no `loading="lazy"` on mobile drawer img |
| `src/components/SecondaryNav.astro` | Same as Navigation |
| `src/pages/our-school/staff.astro` | No `width`/`height` on staff `<img>` tags |
| All `.astro` pages | No unique `<meta name="description">` tags |

---

## Environment Variables

```env
PUBLIC_CLOUDINARY_CLOUD_NAME="djpfl2uwe"
CLOUDINARY_URL=cloudinary://[API_KEY]:[API_SECRET]@djpfl2uwe
```

> ⚠️ **Never commit the `.env` file to version control.** It is already in `.gitignore`.

---

## Future Development Notes

- **Yearbook Page:** The Cloudinary `yearbook/` folder structure (`[year]/grades/[grade]/`) is ready and waiting. The yearbook page needs to be built to fetch images dynamically from Cloudinary using the Search API, filtered by year and grade.
- **Student Life Images Missing:** `soccer.jpeg`, `netball.jpg`, `athlets.webp`, `choir.jpg`, `debate.jpeg`, `lrc.jpg`, `dance.jpeg`, `peer.jpeg`, `volley.jpg`, `environment.jpg` were not uploaded to Cloudinary at the time of migration and still need to be uploaded to `lotsha-web/student_life/`.
- **Gallery grid images:** `grid1.jpg` through `grid6.jpg` also not yet uploaded to Cloudinary (`lotsha-web/gallery/`).
- **Hero2 image:** `hero2.jpg` missing from `lotsha-web/hero/`.

---

## Deployment & Final Performance Notes

**Why is Lighthouse penalizing "Text Compression" and "Initial Server Response Time"?**

Because `astro.config.mjs` has no `output: 'server'` defined, this is a **Static Site (SSG)**. Astro pre-renders 100% of the HTML at build time (`npm run build`).

These two specific Lighthouse warnings are **environment-specific**, meaning they are caused by the server hosting the site, not the code itself:
1. **Local Dev Server:** If auditing `localhost:4321` (or `npm run dev`), the local Node server does not gzip files or use a CDN, leading to TTFB (Time to First Byte) latency and a lack of text compression.
2. **Production Fix:** Deploying the built `/dist` folder to a modern edge network (like Vercel, Netlify, or Cloudflare Pages) will automatically serve files with Brotli/Gzip compression and instantly from Edge nodes, fully resolving these warnings. Ensure Nginx has `gzip on;` if self-hosting.
