# Task 1 Report: Initialize parallax.js

## Summary

Created the parallax.js script and wired it into the project footer. The script dynamically loads GSAP 3.12.5 and ScrollTrigger from jsDelivr CDN, then applies scroll-driven parallax animations to any element with the `.parallax-element` class. It respects `prefers-reduced-motion` and bails out silently when the user has reduced motion enabled.

## Files Modified

- **assets/js/parallax.js** (new) -- Parallax animation module
- **includes/footer.php** (modified) -- Added `<script type="module">` tag for parallax.js

## What parallax.js Does

1. Checks `prefers-reduced-motion`. If enabled, exits immediately with no animations.
2. Dynamically injects GSAP core and ScrollTrigger plugin scripts from jsDelivr CDN.
3. Registers the ScrollTrigger plugin.
4. Selects all `.parallax-element` elements in the DOM.
5. For each element, reads `data-speed` (defaults to 0.3) and creates a GSAP `to` animation with `scrollTrigger` that translates the element on the Y axis as the user scrolls. The `scrub: true` option ties animation progress directly to scroll position.

## Design Decisions

- **Dynamic CDN loading** rather than bundling: This project has no build toolchain. Loading GSAP via CDN keeps the setup simple and avoids adding a node_modules dependency.
- **`type="module"`** on the script tag: Provides automatic deferred loading and strict mode. The module orchestrates CDN script injection, which is the standard pattern for projects without a bundler.
- **`speed * 200` formula** for Y offset: A `data-speed` of 1.0 yields 200px of travel, which provides a noticeable but not extreme parallax effect. Speed 0.3 (default) yields 60px travel.
- **`scrub: true`** makes the animation progress directly tied to scroll position for a smooth, native-feeling parallax.

## How to Use

Add the class `parallax-element` and a `data-speed` attribute to any HTML element:

```html
<div class="parallax-element" data-speed="0.5">Content here</div>
```

Lower speed values produce subtler movement; higher values produce more dramatic parallax.

## Testing Notes

- Node syntax check passed (`node --check`).
- No existing `.parallax-element` usage in the codebase yet -- the elements will be added in Task 2.
- The script is a no-op when no matching elements exist, so it is safe to load on all pages.
