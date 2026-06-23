# Task 3: Apply Parallax to All Guest Pages

## Context
This is the final task in the parallax implementation. Tasks 1-2 are complete:
- `assets/js/parallax.js` loads GSAP + ScrollTrigger and applies parallax to `.parallax-element[data-speed]` elements.
- `index.php` already has `.parallax-element` on signature menu items.
- `assets/css/style.css` has `.sticky-sidebar.revealed` and `overflow-x: clip` fixes.

## Goal
Apply parallax effects to ALL remaining guest pages by adding `.parallax-element` class and `data-speed` attribute to suitable elements (hero sections, large images, cards).

## Files to Modify
1. **`katalog.php`** — Add `.parallax-element data-speed="0.15"` to the `.page-header` inner container.
2. **`galeri.php`** — Add `.parallax-element data-speed="0.15"` to `.page-header` inner container. Add `loading="lazy"` to gallery images if not already present.
3. **`kontak.php`** — Add `.parallax-element data-speed="0.15"` to `.page-header` inner container.
4. **`tentang.php`** — Add `.parallax-element data-speed="0.15"` to `.page-header` inner container. Add `.parallax-element data-speed="0.2"` to art image wrappers.
5. **`detail.php`** — Add `.parallax-element data-speed="0.1"` to the detail image container.

## Implementation Pattern
For each page, find the target element and append the classes/attributes:
```html
<!-- Before -->
<div class="page-header-inner">

<!-- After -->
<div class="page-header-inner parallax-element" data-speed="0.15">
```

## Constraints
- No comments in code.
- No emojis.
- Follow existing code patterns.
- Do NOT modify `assets/js/parallax.js` or `assets/css/style.css` — they are already complete.
- Do NOT modify `index.php` — it is already complete.

## Verification
- Open each page in a browser and scroll — parallax elements should move at different speeds than the scroll.
- Check `prefers-reduced-motion` is respected (no animation when enabled).
