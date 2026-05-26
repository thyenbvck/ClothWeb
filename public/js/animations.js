/* ============================================================
   BKSHOP — Global Animation Script
   Scroll reveal · Back-to-top · Image lazy fade · Ripple
   ============================================================ */
document.addEventListener('DOMContentLoaded', function () {

    /* ── 1. Scroll-to-top button ── */
    var btn = document.createElement('button');
    btn.id = 'scrollTopBtn';
    btn.setAttribute('aria-label', 'Back to top');
    btn.innerHTML = '<i class="fas fa-chevron-up"></i>';
    document.body.appendChild(btn);

    window.addEventListener('scroll', function () {
        btn.classList.toggle('show', window.scrollY > 350);
    }, { passive: true });

    btn.addEventListener('click', function () {
        window.scrollTo({ top: 0, behavior: 'smooth' });
    });

    /* ── 2. Scroll Reveal (IntersectionObserver) ── */
    var selectors = [
        '.product-card',
        '.category-card',
        '.blog-preview-card',
        '.usp-item',
        '.team-member',
        '.filter-widget',
        '.sec-head',
        '.section-heading',
        '.editorial-content',
        'footer .footer-col',
        'footer .footer-brand',
        'footer .footer-newsletter'
    ];

    var targets = document.querySelectorAll(selectors.join(', '));

    if ('IntersectionObserver' in window) {
        var io = new IntersectionObserver(function (entries) {
            entries.forEach(function (entry) {
                if (!entry.isIntersecting) return;

                /* stagger siblings in the same parent */
                var siblings = Array.from(entry.target.parentElement.children);
                var idx = siblings.indexOf(entry.target);
                var delay = Math.min(idx * 70, 300);

                setTimeout(function () {
                    entry.target.classList.add('visible');
                }, delay);

                io.unobserve(entry.target);
            });
        }, { threshold: 0.08, rootMargin: '0px 0px -30px 0px' });

        targets.forEach(function (el) {
            el.classList.add('reveal');
            io.observe(el);
        });
    } else {
        /* fallback: reveal immediately */
        targets.forEach(function (el) { el.classList.add('reveal', 'visible'); });
    }

    /* ── 3. Image lazy-load fade ── */
    document.querySelectorAll('.product-card-media img, .pi-pic img').forEach(function (img) {
        if (!img.complete) {
            img.style.opacity = '0';
            img.addEventListener('load', function () {
                img.style.transition = 'opacity .4s ease';
                img.style.opacity = '1';
            });
        }
    });

    /* ── 4. Ripple on primary buttons ── */
    var rippleTargets = document.querySelectorAll(
        '.btn-black, .btn-accent, .btn-hero-primary, .btn-full-black, .btn-full-accent'
    );

    rippleTargets.forEach(function (el) {
        el.addEventListener('click', function (e) {
            var rect = el.getBoundingClientRect();
            var size = Math.max(rect.width, rect.height);
            var x = e.clientX - rect.left - size / 2;
            var y = e.clientY - rect.top  - size / 2;

            var dot = document.createElement('span');
            dot.style.cssText = [
                'position:absolute',
                'pointer-events:none',
                'border-radius:50%',
                'background:rgba(255,255,255,.28)',
                'width:'  + size + 'px',
                'height:' + size + 'px',
                'left:'   + x + 'px',
                'top:'    + y + 'px',
                'transform:scale(0)',
                'animation:_ripple .5s ease-out forwards'
            ].join(';');

            el.appendChild(dot);
            setTimeout(function () { dot.remove(); }, 550);
        });
    });

    /* inject ripple keyframes once */
    if (!document.getElementById('_ripple-kf')) {
        var s = document.createElement('style');
        s.id = '_ripple-kf';
        s.textContent = '@keyframes _ripple { to { transform:scale(2.8); opacity:0; } }';
        document.head.appendChild(s);
    }

    /* ── 5. Wishlist heart toggle ── */
    document.querySelectorAll('.wishlist-btn').forEach(function (btn) {
        btn.addEventListener('click', function (e) {
            e.preventDefault();
            e.stopPropagation();
            var icon = btn.querySelector('i');
            if (icon.classList.contains('far')) {
                icon.classList.replace('far', 'fas');
                icon.style.color = '#C8102E';
                btn.style.color  = '#C8102E';
            } else {
                icon.classList.replace('fas', 'far');
                icon.style.color = '';
                btn.style.color  = '';
            }
        });
    });

});
