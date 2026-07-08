const GSAP_CDN = 'https://cdn.jsdelivr.net/npm/gsap@3.12.5/dist/gsap.min.js';
const SCROLL_TRIGGER_CDN = 'https://cdn.jsdelivr.net/npm/gsap@3.12.5/dist/ScrollTrigger.min.js';
const PARALLAX_SELECTOR = '.parallax-element';
const SPEED_ATTR = 'data-speed';
const DEFAULT_SPEED = 0.3;

function loadScript(src) {
    if (document.querySelector(`script[src="${src}"]`)) {
        return Promise.resolve();
    }
    return new Promise((resolve, reject) => {
        const script = document.createElement('script');
        script.src = src;
        script.onload = resolve;
        script.onerror = reject;
        document.head.appendChild(script);
    });
}

function prefersReducedMotion() {
    return window.matchMedia('(prefers-reduced-motion: reduce)').matches;
}

function applyParallax() {
    gsap.registerPlugin(ScrollTrigger);

    document.querySelectorAll(PARALLAX_SELECTOR).forEach(element => {
        const speed = parseFloat(element.getAttribute(SPEED_ATTR)) || DEFAULT_SPEED;
        const mobileFactor = window.innerWidth <= 768 ? 0.25 : 1;
        const yOffset = speed * 500 * mobileFactor;

        gsap.to(element, {
            y: -yOffset,
            ease: 'none',
            scrollTrigger: {
                trigger: element,
                start: 'top bottom+=100',
                end: 'bottom top-=100',
                scrub: 1.5,
                invalidateOnRefresh: true,
            },
        });
    });

    ScrollTrigger.refresh();
}

async function initParallax() {
    if (prefersReducedMotion()) return;

    try {
        await loadScript(GSAP_CDN);
        await loadScript(SCROLL_TRIGGER_CDN);

        requestAnimationFrame(() => {
            applyParallax();
        });
    } catch (loadError) {
        console.error('Parallax: failed to load GSAP dependencies.', loadError);
    }
}

document.addEventListener('DOMContentLoaded', initParallax);

window.addEventListener('pageshow', () => {
    if (typeof ScrollTrigger !== 'undefined') {
        ScrollTrigger.refresh();
    }
});
