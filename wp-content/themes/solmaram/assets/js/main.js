/* SolMaram – main.js */
(function () {
  'use strict';

  /* ── Language switcher ──────────────────────────────────────────── */
  const langToggle   = document.querySelector('.js-lang-toggle');
  const langDropdown = document.querySelector('.lang-switcher__dropdown');
  if ( langToggle && langDropdown ) {
    langToggle.addEventListener('click', () => {
      const open = langToggle.getAttribute('aria-expanded') === 'true';
      langToggle.setAttribute('aria-expanded', String(!open));
      langDropdown.hidden = open;
    });
    // Close on outside click or Escape
    document.addEventListener('click', (e) => {
      if (!e.target.closest('#lang-switcher')) {
        langToggle.setAttribute('aria-expanded', 'false');
        langDropdown.hidden = true;
      }
    });
    document.addEventListener('keydown', (e) => {
      if (e.key === 'Escape') {
        langToggle.setAttribute('aria-expanded', 'false');
        langDropdown.hidden = true;
        langToggle.focus();
      }
    });
  }

  /* ── Mobile nav ─────────────────────────────────────────────────── */
  const hamburger = document.querySelector('.js-nav-toggle');
  const nav       = document.querySelector('.site-nav');
  if (hamburger && nav) {
    hamburger.addEventListener('click', () => {
      const open = hamburger.getAttribute('aria-expanded') === 'true';
      hamburger.setAttribute('aria-expanded', String(!open));
      nav.classList.toggle('is-open', !open);
    });
  }

  /* ── Search toggle ──────────────────────────────────────────────── */
  const searchToggle = document.querySelector('.js-search-toggle');
  const searchBar    = document.getElementById('header-search');
  if (searchToggle && searchBar) {
    searchToggle.addEventListener('click', () => {
      const hidden = searchBar.hidden;
      searchBar.hidden = !hidden;
      searchToggle.setAttribute('aria-expanded', String(hidden));
      if (hidden) searchBar.querySelector('.search-field')?.focus();
    });
  }

  /* ── Sticky header class ────────────────────────────────────────── */
  const header = document.getElementById('site-header');
  if (header) {
    const onScroll = () => header.classList.toggle('is-scrolled', window.scrollY > 40);
    window.addEventListener('scroll', onScroll, { passive: true });
  }

  /* ── Reviews carousel auto-scroll ──────────────────────────────── */
  const carousel = document.querySelector('.reviews-carousel[data-autoplay]');
  if (carousel) {
    let idx = 0;
    const cards = carousel.querySelectorAll('.review-card');
    if (cards.length > 1) {
      setInterval(() => {
        idx = (idx + 1) % cards.length;
        cards[idx].scrollIntoView({ behavior: 'smooth', block: 'nearest', inline: 'center' });
      }, 4000);
    }
  }

  /* ── Two-step checkout ──────────────────────────────────────────── */
  document.querySelectorAll('.js-checkout-step-btn').forEach(btn => {
    btn.addEventListener('click', () => {
      const target = btn.dataset.step;
      document.querySelectorAll('.js-checkout-step-btn').forEach(b =>
        b.classList.toggle('is-active', b.dataset.step === target)
      );
      document.querySelectorAll('.js-checkout-step').forEach(step => {
        step.hidden = step.dataset.step !== target;
      });
    });
  });

  document.querySelectorAll('.js-checkout-next').forEach(btn => {
    btn.addEventListener('click', () => {
      const next = btn.dataset.nextStep;
      document.querySelector(`.js-checkout-step-btn[data-step="${next}"]`)?.click();
    });
  });

  // Currency switcher — set cookie and reload
  document.querySelector('.js-currency-select')?.addEventListener('change', function () {
    document.cookie = 'sm_currency=' + this.value + '; path=/; max-age=' + (365 * 24 * 3600) + '; SameSite=Lax';
    location.reload();
  });

})();
