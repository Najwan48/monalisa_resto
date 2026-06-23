const GSAP_CDN = 'https://cdn.jsdelivr.net/npm/gsap@3.12.5/dist/gsap.min.js';
const SCROLL_TRIGGER_CDN = 'https://cdn.jsdelivr.net/npm/gsap@3.12.5/dist/ScrollTrigger.min.js';
const PARALLAX_SELECTOR = '.parallax-element';
const SPEED_ATTR = 'data-speed';
const DEFAULT_SPEED = 0.3;

function loadScript(src) {
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

    const elements = document.querySelectorAll(PARALLAX_SELECTOR);

    elements.forEach(element => {
        const speed = parseFloat(element.getAttribute(SPEED_ATTR)) || DEFAULT_SPEED;
        const yOffset = speed * 500;

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
}

async function initParallax() {
    if (prefersReducedMotion()) return;

    try {
        await loadScript(GSAP_CDN);
        await loadScript(SCROLL_TRIGGER_CDN);
        applyParallax();
    } catch (loadError) {
        console.error('Parallax: failed to load GSAP dependencies.', loadError);
        const warning = document.createElement('div');
        warning.style.position = 'fixed';
        warning.style.bottom = '10px';
        warning.style.right = '10px';
        warning.style.background = '#FEF2F2';
        warning.style.color = '#DC2626';
        warning.style.padding = '10px';
        warning.style.borderRadius = '8px';
        warning.style.zIndex = '9999';
        warning.innerText = 'Info: Efek animasi tidak dapat dimuat.';
        document.body.appendChild(warning);
    }
}

if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', initParallax);
} else {
    initParallax();
}
