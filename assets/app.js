import './bootstrap.js';
import './styles/app.css';

// Cart bump animation
const cartBadge = document.getElementById('cartBadge');
if (cartBadge) {
    let count = parseInt(cartBadge.textContent, 10);
    document.querySelectorAll('[data-add]').forEach(btn => {
        btn.addEventListener('click', (e) => {
            e.stopPropagation();
            count += 1;
            cartBadge.textContent = count;
            cartBadge.classList.remove('bump');
            void cartBadge.offsetWidth;
            cartBadge.classList.add('bump');
            const original = btn.textContent;
            btn.textContent = '✓ Ajouté';
            btn.style.background = 'var(--green)';
            setTimeout(() => { btn.textContent = original; btn.style.background = ''; }, 1100);
        });
    });
    cartBadge.addEventListener('transitionend', () => cartBadge.classList.remove('bump'));
}

// Filter pill toggle
document.querySelectorAll('.pill').forEach(pill => {
    pill.addEventListener('click', () => {
        document.querySelectorAll('.pill').forEach(p => p.dataset.active = 'false');
        pill.dataset.active = 'true';
    });
});

// FAQ accordion
document.querySelectorAll('.faq__item').forEach(item => {
    item.querySelector('.faq__q').addEventListener('click', () => {
        const isOpen = item.classList.contains('open');
        document.querySelectorAll('.faq__item').forEach(o => o.classList.remove('open'));
        if (!isOpen) item.classList.add('open');
    });
});
const firstFaq = document.querySelector('.faq__item');
if (firstFaq) firstFaq.classList.add('open');

// Countdown timer
const countdownTarget = new Date();
countdownTarget.setDate(countdownTarget.getDate() + 2);
countdownTarget.setHours(countdownTarget.getHours() + 14);
countdownTarget.setMinutes(countdownTarget.getMinutes() + 38);
countdownTarget.setSeconds(countdownTarget.getSeconds() + 21);

function tickCountdown() {
    const now = new Date();
    let diff = Math.max(0, countdownTarget - now) / 1000;
    const d = Math.floor(diff / 86400); diff -= d * 86400;
    const h = Math.floor(diff / 3600);  diff -= h * 3600;
    const m = Math.floor(diff / 60);    diff -= m * 60;
    const s = Math.floor(diff);
    const pad = n => String(n).padStart(2, '0');
    const set = (k, v) => { const el = document.querySelector(`[data-cd="${k}"]`); if (el) el.textContent = v; };
    set('d', pad(d)); set('h', pad(h)); set('m', pad(m)); set('s', pad(s));
}
tickCountdown();
setInterval(tickCountdown, 1000);

// Scroll reveal
const revealObserver = new IntersectionObserver((entries) => {
    entries.forEach(e => {
        if (e.isIntersecting) {
            e.target.style.opacity = '1';
            e.target.style.transform = 'translateY(0)';
            revealObserver.unobserve(e.target);
        }
    });
}, { threshold: 0.08 });

document.querySelectorAll('.section, .collab, .newsletter').forEach(el => {
    el.style.opacity = '0';
    el.style.transform = 'translateY(20px)';
    el.style.transition = 'opacity .55s ease, transform .55s ease';
    revealObserver.observe(el);
});

// Float sticker dismiss
const floatSticker = document.getElementById('floatSticker');
if (floatSticker) {
    floatSticker.addEventListener('click', () => {
        floatSticker.style.transform = 'scale(0)';
        setTimeout(() => { floatSticker.style.display = 'none'; }, 250);
    });
}

// Nav scroll-spy
const navLinks = document.querySelectorAll('.nav__links a[href^="#"]');
const spyObserver = new IntersectionObserver((entries) => {
    entries.forEach(e => {
        if (e.isIntersecting) {
            navLinks.forEach(l => l.classList.toggle('active', l.getAttribute('href') === '#' + e.target.id));
        }
    });
}, { rootMargin: '-40% 0px -55% 0px' });

['drops', 'cats', 'best', 'collab', 'custom', 'look'].forEach(id => {
    const section = document.getElementById(id);
    if (section) spyObserver.observe(section);
});

// Newsletter form
const newsletterForm = document.getElementById('newsletterForm');
if (newsletterForm) {
    newsletterForm.addEventListener('submit', (e) => {
        e.preventDefault();
        newsletterForm.querySelector('input').value = '';
        const btn = newsletterForm.querySelector('button');
        btn.textContent = '✓ INSCRIT';
    });
}
