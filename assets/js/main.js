document.addEventListener('DOMContentLoaded', () => {
    const header = document.querySelector('header');
    const hamburger = document.querySelector('.hamburger');
    const navLinks = document.querySelector('.nav-links');

    if (hamburger) {
        hamburger.addEventListener('click', () => {
            navLinks.classList.toggle('active');
        });
    }

    window.addEventListener('scroll', () => {
        if (window.scrollY > 50) {
            header.classList.add('scrolled');
        } else {
            header.classList.remove('scrolled');
        }
    });

    document.querySelectorAll('a[href*="#"]').forEach(anchor => {
        anchor.addEventListener('click', function (e) {
            const href = this.getAttribute('href');
            const url = new URL(href, window.location.origin + window.location.pathname);
            
            if (url.pathname === window.location.pathname || url.pathname === '/' + window.location.pathname.split('/').pop()) {
                const targetId = url.hash;
                const target = document.querySelector(targetId);
                
                if (target) {
                    e.preventDefault();
                    target.scrollIntoView({ behavior: 'smooth' });
                    if (navLinks && navLinks.classList.contains('active')) {
                        navLinks.classList.remove('active');
                    }
                    history.pushState(null, null, targetId);
                }
            }
        });
    });

    document.querySelectorAll('.nav-links a:not([href*="#"]), .logo').forEach(link => {
        link.addEventListener('click', function (e) {
            const targetUrl = this.href;
            if (window.scrollY > 0 && targetUrl && !e.ctrlKey && !e.shiftKey && !e.metaKey && e.button !== 1) {
                e.preventDefault();
                window.scrollTo({ top: 0, behavior: 'smooth' });
                
                let isNavigated = false;
                const checkScroll = () => {
                    if (window.scrollY < 5 && !isNavigated) {
                        isNavigated = true;
                        window.location.href = targetUrl;
                    } else if (!isNavigated) {
                        requestAnimationFrame(checkScroll);
                    }
                };
                
                // Fallback timeout in case scroll takes too long
                setTimeout(() => {
                    if (!isNavigated) {
                        isNavigated = true;
                        window.location.href = targetUrl;
                    }
                }, 600);

                requestAnimationFrame(checkScroll);
            }
        });
    });
});
