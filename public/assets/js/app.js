/* app.js — Konfirmasi hapus, filter kategori, dan pencarian klien-side */

'use strict';

/* ── Konfirmasi Hapus (FR-06) ────────────────────────────────── */
document.addEventListener('submit', function (e) {
  const form = e.target.closest('.form-delete');
  if (!form) return;

  const name = form.dataset.productName || 'produk ini';
  const ok = window.confirm(`Hapus "${name}"?\n\nTindakan ini tidak dapat dibatalkan.`);
  if (!ok) e.preventDefault();
});

/* ── Pencarian Klien-Side ────────────────────────────────────── */
const searchInput  = document.getElementById('searchInput');
const productGrid  = document.getElementById('productGrid');
const noResult     = document.getElementById('noResult');
const productCount = document.getElementById('productCount');

function runSearch() {
  if (!searchInput || !productGrid) return;

  const query = searchInput.value.trim().toLowerCase();
  const activeCat = (document.querySelector('.cat-pill--active') || { dataset: { cat: 'all' } }).dataset.cat;

  let visible = 0;
  const cards = productGrid.querySelectorAll('.product-card');

  cards.forEach(card => {
    const nameMatch     = card.dataset.name.includes(query);
    const categoryMatch = card.dataset.category.includes(query);
    const catFilter     = activeCat === 'all' || card.dataset.category === activeCat.toLowerCase();

    const show = (nameMatch || categoryMatch) && catFilter;
    card.style.display = show ? '' : 'none';
    if (show) visible++;
  });

  if (productCount) {
    productCount.textContent = visible + ' produk';
  }

  if (noResult) {
    noResult.classList.toggle('empty-state--hidden', visible > 0);
  }
}

if (searchInput) {
  searchInput.addEventListener('input', runSearch);
}

/* ── Filter Kategori ─────────────────────────────────────────── */
document.addEventListener('click', function (e) {
  const pill = e.target.closest('.cat-pill');
  if (!pill) return;

  document.querySelectorAll('.cat-pill').forEach(p => p.classList.remove('cat-pill--active'));
  pill.classList.add('cat-pill--active');

  // Reset search saat ganti kategori
  if (searchInput) searchInput.value = '';

  runSearch();
});

/* ── Auto-dismiss flash setelah 5 detik ─────────────────────── */
document.querySelectorAll('.flash').forEach(function (el) {
  setTimeout(function () {
    el.style.transition = 'opacity .4s ease, transform .4s ease';
    el.style.opacity = '0';
    el.style.transform = 'translateY(-6px)';
    setTimeout(() => el.remove(), 400);
  }, 5000);
});
