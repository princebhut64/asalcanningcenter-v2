/* Shared motion system for the Asal Canning Center experience. */
(function () {
  'use strict';

  const reducedMotion = window.matchMedia('(prefers-reduced-motion: reduce)').matches;

  function syncResponsiveState() {
    const narrow = window.innerWidth <= 1100;
    document.body.classList.toggle('is-narrow', narrow);
  }

  function initHeader() {
    const header = document.querySelector('header');
    if (!header) return;
    const update = () => header.classList.toggle('is-scrolled', window.scrollY > 24);
    update();
    window.addEventListener('scroll', update, { passive: true });
  }

  function initReveals() {
    const targets = document.querySelectorAll('.section-title, .product-card, .video-card, .founder-card, .about-box, .contact-card, .gallery-card, .press-card, .catalog-card, .contact-grid-main > *, .about-layout > *');
    targets.forEach((target, index) => {
      target.classList.add('motion-reveal');
      target.style.setProperty('--reveal-delay', `${Math.min(index % 5, 4) * 70}ms`);
    });
    if (reducedMotion || !('IntersectionObserver' in window)) {
      targets.forEach(target => target.classList.add('is-visible'));
      return;
    }
    const observer = new IntersectionObserver((entries, currentObserver) => {
      entries.forEach(entry => {
        if (!entry.isIntersecting) return;
        entry.target.classList.add('is-visible');
        currentObserver.unobserve(entry.target);
      });
    }, { threshold: 0.12, rootMargin: '0px 0px -40px' });
    targets.forEach(target => observer.observe(target));
  }

  function initPageTransitions() {
    document.body.classList.add('page-ready');
  }

  function initHeroParallax() {
    const hero = document.querySelector('.wow-slider-wrapper');
    const image = hero && hero.querySelector('.slide-bg-base');
    if (!hero || !image || reducedMotion || window.matchMedia('(pointer: coarse)').matches) return;
    hero.addEventListener('pointermove', event => {
      const bounds = hero.getBoundingClientRect();
      const x = ((event.clientX - bounds.left) / bounds.width - 0.5) * 10;
      const y = ((event.clientY - bounds.top) / bounds.height - 0.5) * 6;
      image.style.transform = `scale(1.08) translate3d(${x}px, ${y}px, 0)`;
    }, { passive: true });
    hero.addEventListener('pointerleave', () => { image.style.transform = ''; });
  }

  document.addEventListener('DOMContentLoaded', () => {
    syncResponsiveState();
    window.addEventListener('resize', syncResponsiveState, { passive: true });
    initHeader();
    initReveals();
    initPageTransitions();
    initHeroParallax();
  });
})();
