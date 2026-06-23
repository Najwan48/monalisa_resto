# Full-Page Element Parallax Implementation Plan

> **For agentic workers:** REQUIRED SUB-SKILL: Use superpowers:subagent-driven-development (recommended) or superpowers:executing-plans to implement this plan task-by-task. Steps use checkbox (`- [ ]`) syntax for tracking.

**Goal:** Implement full-page parallax scrolling effects on guest pages using GSAP ScrollTrigger.

**Architecture:** Use a dedicated JS file (`parallax.js`) and data-attributes (`data-speed`) on HTML elements to drive scroll-based animation via GSAP.

**Tech Stack:** GSAP (v3+), ScrollTrigger (GSAP plugin).

## Global Constraints

- Icons must come exclusively from approved libraries (Lucide, Phosphor, Heroicons, Iconoir, Remix Icon).
- No emojis in documentation or code.
- No comments (`//`, `/* */`, etc.) in the codebase.
- Code must be self-documenting.
- Use `useGSAP` for React if applicable (not applicable here, plain JS).
- Clean up animations on unmount (if necessary, though for global parallax it's usually static).
- No more than 3 simultaneous complex animations on the same page.
- Animation logic must not block the main thread.

---

### Task 1: Initialize parallax.js

**Files:**
- Create: `assets/js/parallax.js`
- Modify: `includes/footer.php`

**Interfaces:**
- Consumes: GSAP and ScrollTrigger libraries.
- Produces: Global parallax event listener for elements with `.parallax-element`.

- [ ] **Step 1: Create `assets/js/parallax.js`**

```javascript
import { gsap } from 'gsap';
import { ScrollTrigger } from 'gsap/ScrollTrigger';

gsap.registerPlugin(ScrollTrigger);

const initParallax = () => {
    const prefersReduced = window.matchMedia('(prefers-reduced-motion: reduce)').matches;
    if (prefersReduced) return;

    document.querySelectorAll('.parallax-element').forEach((el) => {
        const speed = parseFloat(el.getAttribute('data-speed')) || 0.1;
        gsap.to(el, {
            y: () => -100 * speed,
            ease: 'none',
            scrollTrigger: {
                trigger: el,
                start: 'top bottom',
                end: 'bottom top',
                scrub: true,
                invalidateOnRefresh: true,
            }
        });
    });
};

document.addEventListener('DOMContentLoaded', initParallax);
```

- [ ] **Step 2: Add `parallax.js` to `includes/footer.php`**

```html
<!-- Assuming GSAP is loaded via CDN or module loader -->
<script src="assets/js/parallax.js" type="module"></script>
```

- [ ] **Step 3: Commit**

```bash
git add assets/js/parallax.js includes/footer.php
git commit -m "feat: add parallax.js logic and integrate to footer"
```

### Task 2: Apply Parallax to `index.php` Signature Dishes

**Files:**
- Modify: `index.php:88-105`

**Interfaces:**
- Consumes: `.parallax-element` class and `data-speed` attribute.
- Produces: Animated menu cards.

- [ ] **Step 1: Modify `index.php`**

Update the loop in `index.php` to add the necessary attributes:

```php
<div class="signature-item reveal reveal-up delay-<?= $index + 1 ?> revealed parallax-element" data-speed="0.2" style="...">
```

- [ ] **Step 2: Verify functionality**

Reload the page and scroll to see if the elements move at different speeds relative to the scroll.

- [ ] **Step 3: Commit**

```bash
git add index.php
git commit -m "feat: enable parallax on signature menu items"
```
