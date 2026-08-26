
/* =========================================================
   Asal Canning Center - Shared JavaScript
   ========================================================= */
(function () {
  'use strict';

  document.addEventListener('DOMContentLoaded', function () {
    // Mobile navigation
    const menuToggle = document.getElementById('menuToggle');
    const navbar = document.getElementById('navbar');

    if (menuToggle && navbar) {
      function setNavState(open) {
        navbar.classList.toggle('is-open', open);
        navbar.classList.toggle('open', open);
        menuToggle.setAttribute('aria-expanded', String(open));
        const icon = menuToggle.querySelector('i');
        if (icon) {
          icon.classList.toggle('fa-bars', !open);
          icon.classList.toggle('fa-xmark', open);
        }
      }

      menuToggle.addEventListener('click', function () {
        setNavState(!navbar.classList.contains('is-open'));
      });

      navbar.querySelectorAll('a').forEach(function (link) {
        link.addEventListener('click', function () {
          if (window.innerWidth <= 767) {
            setNavState(false);
          }
        });
      });

      window.addEventListener('resize', function () {
        if (window.innerWidth > 767) {
          setNavState(false);
        }
      });
    }

    // Close mobile menu with Escape
    document.addEventListener('keydown', function (event) {
      if (event.key === 'Escape' && navbar && navbar.classList.contains('is-open')) {
        navbar.classList.remove('is-open', 'open');
        if (menuToggle) {
          menuToggle.setAttribute('aria-expanded', 'false');
          const icon = menuToggle.querySelector('i');
          if (icon) { icon.classList.add('fa-bars'); icon.classList.remove('fa-xmark'); }
        }
      }
    });

    // Add a useful page class
    const path = window.location.pathname.toLowerCase();
    if (path.includes('about')) document.body.classList.add('page-about');
    else if (path.includes('products')) document.body.classList.add('page-products');
    else if (path.includes('news')) document.body.classList.add('page-news');
    else if (path.includes('gallery')) document.body.classList.add('page-gallery');
    else if (path.includes('contact')) document.body.classList.add('page-contact');
    else document.body.classList.add('page-home');

    // Lazy-load images that don't already define loading behavior.
    document.querySelectorAll('img:not([loading])').forEach(function (img) {
      img.setAttribute('loading', 'lazy');
    });
  });
})();
