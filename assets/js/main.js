document.addEventListener('DOMContentLoaded', () => {
    const header = document.getElementById('main-header');

    initHamburgerMenu();
    initHeaderScroll(header);
    initSmoothScroll(header);
    initReveals();
    initScrollToTarget(header);
    initCatalogFilter();
    initWhatsAppChat();
});

function initHamburgerMenu() {
    const hamburger = document.getElementById('hamburger');
    const navLinks = document.getElementById('nav-links');
    const navOverlay = document.getElementById('nav-overlay');

    if (!hamburger || !navLinks) return;

    const toggleMenu = () => {
        const isOpen = navLinks.classList.toggle('active');
        if (navOverlay) navOverlay.classList.toggle('active', isOpen);
        hamburger.classList.toggle('is-active', isOpen);
        hamburger.setAttribute('aria-expanded', isOpen);
        document.body.style.overflow = isOpen ? 'hidden' : 'auto';
    };

    hamburger.addEventListener('click', toggleMenu);
    if (navOverlay) navOverlay.addEventListener('click', toggleMenu);

    navLinks.querySelectorAll('a').forEach(link => {
        link.addEventListener('click', () => {
            setTimeout(() => {
                navLinks.classList.remove('active');
                if (navOverlay) navOverlay.classList.remove('active');
                hamburger.classList.remove('is-active');
                hamburger.setAttribute('aria-expanded', 'false');
                document.body.style.overflow = 'auto';
            }, 200);
        });
    });
}

function initHeaderScroll(header) {
    if (!header) return;

    const onScroll = () => {
        header.classList.toggle('scrolled', window.scrollY > 60);
    };
    window.addEventListener('scroll', onScroll, { passive: true });
    onScroll();
}

function initSmoothScroll(header) {
    document.querySelectorAll('a[href*="#"]').forEach(anchor => {
        anchor.addEventListener('click', function (e) {
            const href = this.getAttribute('href');
            const url = new URL(href, window.location.href);

            if (url.pathname === window.location.pathname && url.hash) {
                const target = document.querySelector(url.hash);
                if (target) {
                    e.preventDefault();
                    target.scrollIntoView({ behavior: 'smooth' });
                    history.pushState(null, null, url.hash);
                }
            } else if (url.hash && url.pathname !== window.location.pathname) {
                e.preventDefault();

                const navigate = () => {
                    const newUrl = url.origin + url.pathname + '?scroll=' + url.hash.substring(1);
                    window.location.href = newUrl;
                };

                if (window.scrollY > 100) {
                    window.scrollTo({ top: 0, behavior: 'smooth' });
                    setTimeout(navigate, 600);
                } else {
                    navigate();
                }
            }
        });
    });
}

function initScrollToTarget(header) {
    const urlParams = new URLSearchParams(window.location.search);
    const scrollTargetId = urlParams.get('scroll') || (window.location.hash ? window.location.hash.substring(1) : null);

    if (!scrollTargetId) return;

    const target = document.getElementById(scrollTargetId);
    if (!target) return;

    if (window.location.hash && 'scrollRestoration' in history) {
        history.scrollRestoration = 'manual';
        window.scrollTo(0, 0);
    }

    setTimeout(() => {
        const headerHeight = header ? header.offsetHeight : 0;
        const targetPosition = target.getBoundingClientRect().top + window.pageYOffset - headerHeight;

        window.scrollTo({
            top: targetPosition,
            behavior: 'smooth'
        });

        if (urlParams.has('scroll')) {
            const newUrl = window.location.pathname + (window.location.hash || '');
            history.replaceState(null, null, newUrl);
        }
    }, 800);
}

function initCatalogFilter() {
    const menuContainer = document.getElementById('menu-content-container');
    const desktopFilters = document.querySelectorAll('.category-filter-desktop a');
    const mobileFilter = document.getElementById('mobile-category-filter');

    if (!menuContainer) return;

    const filterMenu = async (url) => {
        menuContainer.style.opacity = '0.3';
        menuContainer.style.pointerEvents = 'none';
        menuContainer.style.transition = 'opacity 0.4s ease';

        try {
            const response = await fetch(url);
            const html = await response.text();

            const parser = new DOMParser();
            const doc = parser.parseFromString(html, 'text/html');
            const newContent = doc.getElementById('menu-content-container');

            if (newContent) {
                menuContainer.innerHTML = newContent.innerHTML;
                window.history.pushState({}, '', url);

                desktopFilters.forEach(link => {
                    const linkHref = link.getAttribute('href');
                    const isActive = linkHref === url || (url.includes(linkHref) && linkHref !== 'katalog.php');
                    link.style.color = isActive ? 'var(--primary)' : 'var(--text-muted)';
                    link.style.borderBottomColor = isActive ? 'var(--primary)' : 'transparent';
                });

                const filterSection = document.querySelector('.category-filter-desktop')?.closest('.container')?.parentElement;
                if (filterSection) {
                    const topOffset = filterSection.offsetTop - 80;
                    window.scrollTo({ top: topOffset, behavior: 'smooth' });
                }
            } else {
                window.location.href = url;
            }

        } catch (err) {
            console.error('Filtering error:', err);
            window.location.href = url;
        } finally {
            menuContainer.style.opacity = '1';
            menuContainer.style.pointerEvents = 'all';
            initReveals();
        }
    };

    desktopFilters.forEach(link => {
        link.addEventListener('click', (e) => {
            e.preventDefault();
            filterMenu(link.getAttribute('href'));
        });
    });

    initCategoryScrollArrows();

    if (mobileFilter) {
        mobileFilter.addEventListener('change', (e) => {
            filterMenu(e.target.value);
        });
    }
}

function initCategoryScrollArrows() {
    const categoryNav = document.getElementById('category-nav');
    const scrollLeft = document.getElementById('scroll-left');
    const scrollRight = document.getElementById('scroll-right');

    if (!categoryNav || !scrollLeft || !scrollRight) return;

    const updateArrows = () => {
        const scrollLeftPos = categoryNav.scrollLeft;
        const maxScroll = categoryNav.scrollWidth - categoryNav.clientWidth;

        scrollLeft.classList.toggle('visible', scrollLeftPos > 10);
        scrollRight.classList.toggle('visible', scrollLeftPos < maxScroll - 10);
    };

    scrollLeft.addEventListener('click', () => {
        categoryNav.scrollBy({ left: -200, behavior: 'smooth' });
    });

    scrollRight.addEventListener('click', () => {
        categoryNav.scrollBy({ left: 200, behavior: 'smooth' });
    });

    categoryNav.addEventListener('scroll', updateArrows);
    window.addEventListener('resize', updateArrows);

    setTimeout(updateArrows, 500);
}

function openWhatsAppChat(phone, message) {
    const encodedMessage = encodeURIComponent(message);
    const url = `https://wa.me/${phone}?text=${encodedMessage}`;
    window.open(url, '_blank');
}

function initWhatsAppChat() {
    const waFloatBtn = document.getElementById('waFloatBtn');
    const waChatPopup = document.getElementById('waChatPopup');
    const waChatClose = document.getElementById('waChatClose');
    const waChatSend = document.getElementById('waChatSend');
    const waChatInput = document.getElementById('waChatInput');

    if (!waFloatBtn || !waChatPopup) return;

    waFloatBtn.addEventListener('click', () => {
        waChatPopup.classList.toggle('active');
    });

    if (waChatClose) {
        waChatClose.addEventListener('click', () => {
            waChatPopup.classList.remove('active');
        });
    }

    const handleSend = () => {
        if (!waChatInput || !waChatSend) return;
        const message = waChatInput.value.trim();
        const phone = waChatSend.getAttribute('data-phone');
        if (message && phone) {
            openWhatsAppChat(phone, message);
            waChatInput.value = '';
            waChatPopup.classList.remove('active');
        }
    };

    if (waChatSend) {
        waChatSend.addEventListener('click', handleSend);
    }

    if (waChatInput) {
        waChatInput.addEventListener('keypress', (e) => {
            if (e.key === 'Enter') {
                handleSend();
            }
        });
    }

    const quickBtns = waChatPopup.querySelectorAll('.wa-quick-btn');
    quickBtns.forEach(btn => {
        btn.addEventListener('click', () => {
            const message = btn.getAttribute('data-msg');
            const phone = waChatSend.getAttribute('data-phone');
            if (message && phone) {
                openWhatsAppChat(phone, message);
                waChatPopup.classList.remove('active');
            }
        });
    });

    document.addEventListener('click', (e) => {
        if (!waChatPopup.contains(e.target) && !waFloatBtn.contains(e.target)) {
            waChatPopup.classList.remove('active');
        }
    });
}

function initReveals() {
    const revealObserver = new IntersectionObserver((entries) => {
        requestAnimationFrame(() => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    entry.target.classList.add('revealed');
                    revealObserver.unobserve(entry.target);
                    entry.target.addEventListener('transitionend', () => {
                        entry.target.style.willChange = 'auto';
                    }, { once: true });
                }
            });
        });
    }, { threshold: 0.1, rootMargin: '0px 0px -50px 0px' });

    document.querySelectorAll('.reveal').forEach(el => revealObserver.observe(el));

    const menuObserver = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.style.opacity = '1';
                entry.target.style.transform = 'translateY(0)';
                menuObserver.unobserve(entry.target);
            }
        });
    }, { threshold: 0, rootMargin: '0px 0px -20px 0px' });

    document.querySelectorAll('.menu-card').forEach(card => {
        if (!card.classList.contains('reveal')) {
            card.style.opacity = '0';
            card.style.transform = 'translateY(30px)';
            card.style.transition = 'opacity 0.8s cubic-bezier(0.16, 1, 0.3, 1), transform 0.8s cubic-bezier(0.16, 1, 0.3, 1)';
            menuObserver.observe(card);
        }
    });
}

function copyToClipboard(text, btn) {
    navigator.clipboard.writeText(text).then(() => {
        const icon = btn.querySelector('i');
        const status = btn.querySelector('.copy-status');

        if (icon) icon.className = 'ri-check-line';
        if (status) status.style.display = 'inline';

        btn.style.color = 'var(--primary)';

        setTimeout(() => {
            if (icon) icon.className = 'ri-file-copy-line';
            if (status) status.style.display = 'none';
            btn.style.color = 'var(--text-muted)';
        }, 2000);
    });
}
