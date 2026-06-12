/**
 * Citora · Premium animation helpers
 *
 * Vanilla JS, no external deps. Activates on DOMContentLoaded.
 *
 * - Scroll reveal: any element with `.cit-reveal` becomes `.is-visible`
 *   the first time it intersects the viewport.
 * - Counter up: any element with `[data-cit-counter]` animates from 0 to its
 *   final numeric value when revealed. Supports `data-cit-prefix` and
 *   `data-cit-suffix` (e.g. "$", "k+").
 * - Parallax: any element with `[data-cit-parallax]` translates vertically
 *   in sync with scroll. Value is the strength factor (e.g. 0.15).
 *
 * All effects bail out when the user has prefers-reduced-motion.
 */

const REDUCED = window.matchMedia('(prefers-reduced-motion: reduce)').matches;

function setupScrollReveal() {
    const reveals = document.querySelectorAll('.cit-reveal');
    const counters = document.querySelectorAll('[data-cit-counter]');
    if (!reveals.length && !counters.length) return;

    if (REDUCED || !('IntersectionObserver' in window)) {
        reveals.forEach((el) => el.classList.add('is-visible'));
        counters.forEach(animateCounter);
        return;
    }

    const observer = new IntersectionObserver(
        (entries) => {
            for (const entry of entries) {
                if (!entry.isIntersecting) continue;
                entry.target.classList.add('is-visible');
                if (entry.target.matches('[data-cit-counter]')) animateCounter(entry.target);
                entry.target.querySelectorAll('[data-cit-counter]').forEach(animateCounter);
                observer.unobserve(entry.target);
            }
        },
        { threshold: 0.12, rootMargin: '0px 0px -40px 0px' }
    );

    reveals.forEach((el) => observer.observe(el));
    counters.forEach((el) => observer.observe(el));
}

function animateCounter(el) {
    if (el.dataset.citCounterDone === '1') return;
    el.dataset.citCounterDone = '1';

    const target = parseFloat(el.dataset.citCounter);
    if (!Number.isFinite(target)) return;

    const prefix = el.dataset.citPrefix ?? '';
    const suffix = el.dataset.citSuffix ?? '';
    const decimals = parseInt(el.dataset.citDecimals ?? '0', 10);
    const duration = parseInt(el.dataset.citDuration ?? '900', 10);
    const start = performance.now();

    if (REDUCED) {
        el.textContent = `${prefix}${formatNumber(target, decimals)}${suffix}`;
        return;
    }

    function tick(now) {
        const elapsed = Math.min(1, (now - start) / duration);
        // ease-out cubic
        const eased = 1 - Math.pow(1 - elapsed, 3);
        const value = target * eased;
        el.textContent = `${prefix}${formatNumber(value, decimals)}${suffix}`;
        if (elapsed < 1) requestAnimationFrame(tick);
    }
    requestAnimationFrame(tick);
}

function formatNumber(value, decimals) {
    return value.toLocaleString('es-CO', {
        minimumFractionDigits: decimals,
        maximumFractionDigits: decimals,
    });
}

function setupParallax() {
    const items = Array.from(document.querySelectorAll('[data-cit-parallax]'));
    if (!items.length || REDUCED) return;

    let ticking = false;

    function update() {
        const scrollY = window.scrollY;
        for (const el of items) {
            const strength = parseFloat(el.dataset.citParallax) || 0.15;
            el.style.transform = `translate3d(0, ${(-scrollY * strength).toFixed(2)}px, 0)`;
        }
        ticking = false;
    }

    window.addEventListener(
        'scroll',
        () => {
            if (!ticking) {
                requestAnimationFrame(update);
                ticking = true;
            }
        },
        { passive: true }
    );
    update();
}

function setupMagneticHover() {
    const items = document.querySelectorAll('[data-cit-magnetic]');
    if (!items.length || REDUCED) return;

    items.forEach((el) => {
        const strength = parseFloat(el.dataset.citMagnetic) || 0.25;
        el.addEventListener('mousemove', (e) => {
            const rect = el.getBoundingClientRect();
            const x = e.clientX - rect.left - rect.width / 2;
            const y = e.clientY - rect.top - rect.height / 2;
            el.style.transform = `translate(${(x * strength).toFixed(1)}px, ${(y * strength).toFixed(1)}px)`;
        });
        el.addEventListener('mouseleave', () => {
            el.style.transform = '';
        });
    });
}

function init() {
    setupScrollReveal();
    setupParallax();
    setupMagneticHover();
}

if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', init);
} else {
    init();
}
