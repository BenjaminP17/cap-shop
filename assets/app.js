import './bootstrap.js';
import './styles/app.css';

// ── Utilitaire CSRF-safe fetch (POST JSON) ────────────────────────────────────
async function postJson(url, body = {}) {
    const res = await fetch(url, {
        method: 'POST',
        headers: { 'Content-Type': 'application/json', 'X-Requested-With': 'XMLHttpRequest' },
        body: JSON.stringify(body),
    });
    return res.json();
}

// ── Badge panier (mis à jour par le serveur) ──────────────────────────────────
function updateBadge(count) {
    document.querySelectorAll('#cartBadge').forEach(badge => {
        badge.textContent = count;
        badge.classList.remove('bump');
        void badge.offsetWidth;
        badge.classList.add('bump');
    });
}

document.querySelectorAll('#cartBadge').forEach(badge => {
    badge.addEventListener('transitionend', () => badge.classList.remove('bump'));
});

// ── Boutons "Ajouter au panier" (page d'accueil) ──────────────────────────────
document.querySelectorAll('[data-add]').forEach(btn => {
    btn.addEventListener('click', async (e) => {
        e.stopPropagation();
        const id = btn.dataset.id;
        if (!id) return;

        const original = btn.textContent;
        btn.textContent = '…';
        btn.disabled = true;

        try {
            const data = await postJson(`/cart/add/${id}`);
            updateBadge(data.count);
            btn.textContent = '✓ Ajouté';
            btn.style.background = 'var(--green)';
            setTimeout(() => {
                btn.textContent = original;
                btn.style.background = '';
                btn.disabled = false;
            }, 1200);
        } catch {
            btn.textContent = original;
            btn.disabled = false;
        }
    });
});

// ── Page panier : quantités + suppression ─────────────────────────────────────
function updateCartUI(id, data) {
    document.querySelectorAll('#cartBadge').forEach(b => b.textContent = data.count);
    const totalEl = document.getElementById('summaryTotal');
    const subEl   = document.getElementById('summarySubtotal');
    if (totalEl) totalEl.textContent = data.total;
    if (subEl)   subEl.textContent   = data.total;
}

// Boutons +/−
document.querySelectorAll('.qty-btn').forEach(btn => {
    btn.addEventListener('click', async () => {
        const id      = btn.dataset.id;
        const qtyEl   = document.getElementById(`qty-${id}`);
        const subEl   = document.getElementById(`sub-${id}`);
        const current = parseInt(qtyEl?.textContent ?? '1', 10);
        const newQty  = btn.dataset.action === 'inc' ? current + 1 : current - 1;

        if (newQty <= 0) {
            await removeRow(id);
            return;
        }

        const data = await postJson(`/cart/update/${id}`, { quantity: newQty });
        if (qtyEl) qtyEl.textContent = newQty;
        updateCartUI(id, data);
    });
});

// Bouton ✕ suppression ligne
document.querySelectorAll('.cart-row__del').forEach(btn => {
    btn.addEventListener('click', () => removeRow(btn.dataset.id));
});

async function removeRow(id) {
    const data = await postJson(`/cart/remove/${id}`);
    const row  = document.getElementById(`row-${id}`);
    if (row) {
        row.style.transition = 'opacity .25s ease, transform .25s ease';
        row.style.opacity    = '0';
        row.style.transform  = 'translateX(12px)';
        setTimeout(() => {
            row.remove();
            if (!document.querySelector('.cart-row')) {
                location.reload();
            }
        }, 260);
    }
    updateCartUI(id, data);
}

// Vider le panier
const clearBtn = document.getElementById('clearCartBtn');
if (clearBtn) {
    clearBtn.addEventListener('click', async () => {
        await postJson('/cart/clear');
        location.reload();
    });
}

// ── Filter pill toggle ────────────────────────────────────────────────────────
document.querySelectorAll('.pill').forEach(pill => {
    pill.addEventListener('click', () => {
        document.querySelectorAll('.pill').forEach(p => p.dataset.active = 'false');
        pill.dataset.active = 'true';
    });
});

// ── FAQ accordion ─────────────────────────────────────────────────────────────
document.querySelectorAll('.faq__item').forEach(item => {
    item.querySelector('.faq__q')?.addEventListener('click', () => {
        const isOpen = item.classList.contains('open');
        document.querySelectorAll('.faq__item').forEach(o => o.classList.remove('open'));
        if (!isOpen) item.classList.add('open');
    });
});
document.querySelector('.faq__item')?.classList.add('open');

// ── Countdown collab ──────────────────────────────────────────────────────────
const countdownTarget = new Date();
countdownTarget.setDate(countdownTarget.getDate() + 2);
countdownTarget.setHours(countdownTarget.getHours() + 14);
countdownTarget.setMinutes(countdownTarget.getMinutes() + 38);
countdownTarget.setSeconds(countdownTarget.getSeconds() + 21);

function tickCountdown() {
    let diff = Math.max(0, countdownTarget - new Date()) / 1000;
    const d = Math.floor(diff / 86400); diff -= d * 86400;
    const h = Math.floor(diff / 3600);  diff -= h * 3600;
    const m = Math.floor(diff / 60);    diff -= m * 60;
    const s = Math.floor(diff);
    const pad = n => String(n).padStart(2, '0');
    const set = (k, v) => { const el = document.querySelector(`[data-cd="${k}"]`); if (el) el.textContent = v; };
    set('d', pad(d)); set('h', pad(h)); set('m', pad(m)); set('s', pad(s));
}
if (document.querySelector('[data-cd]')) {
    tickCountdown();
    setInterval(tickCountdown, 1000);
}

// ── Scroll reveal ─────────────────────────────────────────────────────────────
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

// ── Float sticker dismiss ─────────────────────────────────────────────────────
document.getElementById('floatSticker')?.addEventListener('click', (e) => {
    e.currentTarget.style.transform = 'scale(0)';
    setTimeout(() => { e.currentTarget.style.display = 'none'; }, 250);
});

// ── Nav scroll-spy ────────────────────────────────────────────────────────────
const navLinks = document.querySelectorAll('.nav__links a[href^="#"]');
if (navLinks.length) {
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
}

// ── Newsletter form ───────────────────────────────────────────────────────────
document.getElementById('newsletterForm')?.addEventListener('submit', (e) => {
    e.preventDefault();
    e.target.querySelector('input').value = '';
    e.target.querySelector('button').textContent = '✓ INSCRIT';
});
