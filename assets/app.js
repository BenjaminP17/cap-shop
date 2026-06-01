import './bootstrap.js';
import './styles/app.css';

// ── Utilitaire fetch JSON ─────────────────────────────────────────────────────
async function postJson(url, body = {}) {
    const res = await fetch(url, {
        method: 'POST',
        headers: { 'Content-Type': 'application/json', 'X-Requested-With': 'XMLHttpRequest' },
        body: JSON.stringify(body),
    });
    if (!res.ok) throw new Error(`HTTP ${res.status}`);
    return res.json();
}

// ── Mise à jour du badge panier ───────────────────────────────────────────────
function updateBadge(count) {
    document.querySelectorAll('#cartBadge').forEach(badge => {
        badge.textContent = count;
        badge.classList.remove('bump');
        void badge.offsetWidth;
        badge.classList.add('bump');
    });
}

// ── Delegation : "Ajouter au panier" (fonctionne après chaque swap Turbo) ─────
// Un seul listener sur document — intercepte tous les [data-add] présents et futurs
document.addEventListener('click', async (e) => {
    const btn = e.target.closest('[data-add]');
    if (!btn) return;

    const id = btn.dataset.id;
    if (!id) return;

    e.stopPropagation();
    const original = btn.textContent;
    btn.textContent = '…';
    btn.disabled = true;

    try {
        const data = await postJson(`/cart/add/${id}`);
        updateBadge(data.count);

        btn.textContent = '✓ Ajouté';
        btn.style.background = 'var(--green)';

        // Confirmation inline sur la page produit
        const confirm = document.getElementById('addConfirm');
        if (confirm) confirm.style.display = 'block';

        setTimeout(() => {
            btn.textContent = original;
            btn.style.background = '';
            btn.disabled = false;
            if (confirm) confirm.style.display = 'none';
        }, 1500);
    } catch (err) {
        console.error('Erreur ajout panier:', err);
        btn.textContent = original;
        btn.disabled = false;
    }
});

document.addEventListener('click', () => {
    document.querySelectorAll('#cartBadge').forEach(b => b.classList.remove('bump'));
});

// ── Page panier : quantités + suppression (delegation) ───────────────────────
document.addEventListener('click', async (e) => {
    // Bouton +/−
    const qtyBtn = e.target.closest('.qty-btn');
    if (qtyBtn) {
        const id     = qtyBtn.dataset.id;
        const qtyEl  = document.getElementById(`qty-${id}`);
        const current = parseInt(qtyEl?.textContent ?? '1', 10);
        const newQty  = qtyBtn.dataset.action === 'inc' ? current + 1 : current - 1;

        if (newQty <= 0) { removeCartRow(id); return; }

        const data = await postJson(`/cart/update/${id}`, { quantity: newQty });
        if (qtyEl) qtyEl.textContent = newQty;
        syncCartSummary(data);
        return;
    }

    // Bouton supprimer ✕
    const delBtn = e.target.closest('.cart-row__del');
    if (delBtn) { removeCartRow(delBtn.dataset.id); return; }

    // Vider le panier
    if (e.target.closest('#clearCartBtn')) {
        await postJson('/cart/clear');
        location.reload();
    }
});

async function removeCartRow(id) {
    const data = await postJson(`/cart/remove/${id}`);
    const row  = document.getElementById(`row-${id}`);
    if (row) {
        row.style.transition = 'opacity .25s ease, transform .25s ease';
        row.style.opacity    = '0';
        row.style.transform  = 'translateX(12px)';
        setTimeout(() => {
            row.remove();
            if (!document.querySelector('.cart-row')) location.reload();
        }, 260);
    }
    syncCartSummary(data);
}

function syncCartSummary(data) {
    document.querySelectorAll('#cartBadge').forEach(b => b.textContent = data.count);
    const totalEl = document.getElementById('summaryTotal');
    const subEl   = document.getElementById('summarySubtotal');
    if (totalEl) totalEl.textContent = data.total;
    if (subEl)   subEl.textContent   = data.total;
}

// ── Fonctions ré-initialisées à chaque page (Turbo) ──────────────────────────
function initPage() {
    // Pills filtres
    document.querySelectorAll('.pill').forEach(pill => {
        pill.addEventListener('click', () => {
            document.querySelectorAll('.pill').forEach(p => p.dataset.active = 'false');
            pill.dataset.active = 'true';
        });
    });

    // FAQ accordion
    document.querySelectorAll('.faq__item').forEach(item => {
        item.querySelector('.faq__q')?.addEventListener('click', () => {
            const isOpen = item.classList.contains('open');
            document.querySelectorAll('.faq__item').forEach(o => o.classList.remove('open'));
            if (!isOpen) item.classList.add('open');
        });
    });
    document.querySelector('.faq__item')?.classList.add('open');

    // Float sticker
    document.getElementById('floatSticker')?.addEventListener('click', (e) => {
        e.currentTarget.style.transform = 'scale(0)';
        setTimeout(() => { e.currentTarget.style.display = 'none'; }, 250);
    });

    // Newsletter
    document.getElementById('newsletterForm')?.addEventListener('submit', (e) => {
        e.preventDefault();
        e.target.querySelector('input').value = '';
        e.target.querySelector('button').textContent = '✓ INSCRIT';
    });

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

    // Nav scroll-spy
    const navLinks = document.querySelectorAll('.nav__links a[href^="#"]');
    if (navLinks.length) {
        const spy = new IntersectionObserver((entries) => {
            entries.forEach(e => {
                if (e.isIntersecting) {
                    navLinks.forEach(l => l.classList.toggle('active', l.getAttribute('href') === '#' + e.target.id));
                }
            });
        }, { rootMargin: '-40% 0px -55% 0px' });
        ['drops', 'cats', 'best', 'collab', 'custom', 'look'].forEach(id => {
            const s = document.getElementById(id);
            if (s) spy.observe(s);
        });
    }

    // Countdown collab
    if (document.querySelector('[data-cd]')) {
        const target = new Date();
        target.setDate(target.getDate() + 2);
        target.setHours(target.getHours() + 14);
        target.setMinutes(target.getMinutes() + 38);
        target.setSeconds(target.getSeconds() + 21);

        const tick = () => {
            let diff = Math.max(0, target - new Date()) / 1000;
            const d = Math.floor(diff / 86400); diff -= d * 86400;
            const h = Math.floor(diff / 3600);  diff -= h * 3600;
            const m = Math.floor(diff / 60);    diff -= m * 60;
            const s = Math.floor(diff);
            const pad = n => String(n).padStart(2, '0');
            const set = (k, v) => { const el = document.querySelector(`[data-cd="${k}"]`); if (el) el.textContent = v; };
            set('d', pad(d)); set('h', pad(h)); set('m', pad(m)); set('s', pad(s));
        };
        tick();
        setInterval(tick, 1000);
    }
}

// Lance initPage au premier chargement ET après chaque navigation Turbo
document.addEventListener('turbo:load', initPage);
