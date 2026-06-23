# Task 3 Report: Apply Parallax to All Guest Pages

## Status: DONE

## Summary
Added `.parallax-element` class and `data-speed` attribute to suitable elements across all 5 remaining guest pages. No HTML restructuring was performed -- only class and attribute additions.

## Changes Made

### 1. katalog.php (line 52)
- Target: `.page-header > .container`
- Added: `class="container parallax-element"` and `data-speed="0.15"`
- Effect: Page header content moves at 15% of scroll speed.

### 2. galeri.php (line 11)
- Target: `.page-header > .container`
- Added: `class="container parallax-element"` and `data-speed="0.15"`
- Effect: Page header content moves at 15% of scroll speed.
- Note: Gallery images already have `loading="lazy"` present (line 55).

### 3. kontak.php (line 15)
- Target: `.page-header > .container`
- Added: `class="container parallax-element"` and `data-speed="0.15"`
- Effect: Page header content moves at 15% of scroll speed.

### 4. tentang.php (lines 12, 34)
- Target 1: `.page-header > .container`
  - Added: `class="container parallax-element"` and `data-speed="0.15"`
  - Effect: Page header content moves at 15% of scroll speed.
- Target 2: `.art-image-wrapper`
  - Added: `class="art-image-wrapper parallax-element"` and `data-speed="0.2"`
  - Effect: Art image moves at 20% of scroll speed.

### 5. detail.php (line 30)
- Target: `.parallax-wrap`
- Added: `class="parallax-wrap parallax-element"` and `data-speed="0.1"`
- Effect: Food image container moves at 10% of scroll speed.

## Files Modified
1. `/opt/lampp/htdocs/monalisa_resto/katalog.php`
2. `/opt/lampp/htdocs/monalisa_resto/galeri.php`
3. `/opt/lampp/htdocs/monalisa_resto/kontak.php`
4. `/opt/lampp/htdocs/monalisa_resto/tentang.php`
5. `/opt/lampp/htdocs/monalisa_resto/detail.php`

## Verification
- All pages can be opened in a browser and scrolled to verify parallax movement.
- `assets/js/parallax.js` handles `prefers-reduced-motion` automatically.
- No existing CSS, JS, or PHP logic was modified.
