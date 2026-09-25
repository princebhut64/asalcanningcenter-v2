/* ==========================================================================
   ASAL CANNING CENTER - Main JavaScript Controller
   ========================================================================== */

document.addEventListener('DOMContentLoaded', () => {

  /* ------------------------------------------------------------------
     1. GSAP + ScrollTrigger
     ------------------------------------------------------------------ */
  if (typeof gsap !== 'undefined') {
    gsap.registerPlugin(ScrollTrigger);
    initGSAPAnimations();
    initAmbientLightBeams();
    initTimelineProgressAnimation();
  }

  /* ------------------------------------------------------------------
     2. Navigation & Header
     ------------------------------------------------------------------ */
  initNavigation();

  /* ------------------------------------------------------------------
     3. Stats Counter
     ------------------------------------------------------------------ */
  initStatsCounters();

  /* ------------------------------------------------------------------
     4. Filter Tabs & Gallery Infinite Scroll
     ------------------------------------------------------------------ */
  initFilterTabs();
  initGalleryInfiniteScroll();

  /* ------------------------------------------------------------------
     5. Lightbox Modal (Image + YouTube)
     ------------------------------------------------------------------ */
  initLightboxModal();

  /* ------------------------------------------------------------------
     6. FAQ Accordion (split into 2 columns + toggle)
     ------------------------------------------------------------------ */
  initFAQAccordion();

  /* ------------------------------------------------------------------
     7. 3D Tilt Sheen on Cards
     ------------------------------------------------------------------ */
  initTiltEffect();

  /* ------------------------------------------------------------------
     8. Batch Calculator
     ------------------------------------------------------------------ */
  initBatchCalculator();

  /* ------------------------------------------------------------------
     9. News & Media document lightbox
     ------------------------------------------------------------------ */
  initMediaDocLightbox();

  /* ------------------------------------------------------------------
     10. Contact Form Autofill for Product Inquiries
     ------------------------------------------------------------------ */
  initContactFormAutoFill();

});


/* ==========================================================================
   1. AMBIENT LIGHT BEAMS (GSAP breathe)
   ========================================================================== */
function initAmbientLightBeams() {
  gsap.to('.ambient-light-gold', {
    scale: 1.25, opacity: 0.85, duration: 4.5,
    repeat: -1, yoyo: true, ease: 'sine.inOut'
  });
  gsap.to('.ambient-light-terracotta', {
    scale: 1.2, opacity: 0.65, duration: 5.5,
    repeat: -1, yoyo: true, ease: 'sine.inOut'
  });
}


/* ==========================================================================
   2. GSAP SCROLL ANIMATIONS
   ========================================================================== */
function initGSAPAnimations() {

  /* Header entrance */
  gsap.from('header', { y: -50, opacity: 0, duration: 0.8, ease: 'power3.out' });

  /* Hero section stagger */
  if (document.querySelector('.hero-section')) {
    const heroTl = gsap.timeline({ defaults: { ease: 'power3.out', duration: 0.8 } });
    heroTl
      .from('.hero-badge',       { opacity: 0, y: 20, delay: 0.1 })
      .from('.hero-content h1',  { opacity: 0, y: 25 }, '-=0.5')
      .from('.hero-content p',   { opacity: 0, y: 20 }, '-=0.6')
      .from('.hero-cta-group',   { opacity: 0, y: 15 }, '-=0.5')
      .from('.hero-features-list', { opacity: 0, y: 15 }, '-=0.4')
      .from('.hero-image-card',  { opacity: 0, scale: 0.95, duration: 1 }, '-=0.8')
      .from('.hero-glass-badge', { opacity: 0, x: -20, duration: 0.6 }, '-=0.5');
  }

  /* Section reveal on scroll — use fromTo so opacity never sticks at 0 */
  gsap.utils.toArray('.gsap-reveal').forEach((el) => {
    gsap.fromTo(el,
      { opacity: 0, y: 35 },
      {
        scrollTrigger: { trigger: el, start: 'top 90%', toggleActions: 'play none none none' },
        opacity: 1, y: 0, duration: 0.8, ease: 'power3.out', clearProps: 'all'
      }
    );
  });

  /* Grid stagger */
  gsap.utils.toArray('.gsap-grid-stagger').forEach((grid) => {
    // If this grid is the infinite-scroll gallery grid, skip here since initGalleryInfiniteScroll controls its reveal
    if (grid.classList.contains('gallery-grid') && document.getElementById('galleryScrollSentinel')) return;
    const items = Array.from(grid.children);
    if (!items.length) return;
    gsap.fromTo(items,
      { opacity: 0, y: 20 },
      {
        scrollTrigger: { trigger: grid, start: 'top 92%', toggleActions: 'play none none none' },
        opacity: 1, y: 0, duration: 0.5, stagger: 0.05, ease: 'power2.out', clearProps: 'all'
      }
    );
  });
}


/* ==========================================================================
   3. TIMELINE JOURNEY ANIMATION
   ========================================================================== */
function initTimelineProgressAnimation() {
  if (typeof gsap === 'undefined' || typeof ScrollTrigger === 'undefined') return;

  const timelineGrids = document.querySelectorAll('.timeline-split-grid');
  if (!timelineGrids.length) return;

  timelineGrids.forEach((grid, idx) => {
    const cardBox    = grid.querySelector('.timeline-card-box');
    const imgCard    = grid.querySelector('.timeline-img-card');
    const nodeCircle = grid.querySelector('.timeline-node-circle');

    const tl = gsap.timeline({
      scrollTrigger: { trigger: grid, start: 'top 85%', toggleActions: 'play none none none' }
    });

    if (nodeCircle) {
      tl.fromTo(nodeCircle,
        { scale: 0, opacity: 0 },
        { scale: 1, opacity: 1, duration: 0.5, ease: 'back.out(1.7)' }
      );
    }

    if (cardBox) {
      tl.fromTo(cardBox,
        { opacity: 0, x: idx % 2 === 0 ? -60 : 60 },
        { opacity: 1, x: 0, duration: 0.7, ease: 'power3.out', clearProps: 'transform' },
        '-=0.3'
      );
    }

    if (imgCard) {
      tl.fromTo(imgCard,
        { opacity: 0, x: idx % 2 === 0 ? 60 : -60, scale: 0.95 },
        { opacity: 1, x: 0, scale: 1, duration: 0.7, ease: 'power3.out', clearProps: 'transform' },
        '-=0.6'
      );
    }
  });
}


/* ==========================================================================
   4. NAVIGATION & HEADER
   ========================================================================== */
function initNavigation() {
  const header  = document.querySelector('header');
  const menuBtn = document.querySelector('.mobile-menu-btn');
  const nav     = document.querySelector('nav');

  /* Null guard — header may not exist on some admin pages */
  if (!header) return;

  window.addEventListener('scroll', () => {
    if (window.scrollY > 40) {
      header.classList.add('scrolled');
    } else {
      header.classList.remove('scrolled');
    }
  }, { passive: true });

  if (menuBtn && nav) {
    menuBtn.addEventListener('click', () => {
      nav.classList.toggle('active');
      const icon = menuBtn.querySelector('i');
      if (icon) {
        icon.classList.toggle('fa-bars',  !nav.classList.contains('active'));
        icon.classList.toggle('fa-xmark',  nav.classList.contains('active'));
      }
    });

    /* Close nav on any link click (mobile) */
    nav.querySelectorAll('a').forEach(link => {
      link.addEventListener('click', () => {
        nav.classList.remove('active');
        const icon = menuBtn.querySelector('i');
        if (icon) {
          icon.classList.add('fa-bars');
          icon.classList.remove('fa-xmark');
        }
      });
    });
  }
}


/* ==========================================================================
   5. STATS COUNTER ANIMATION
   ========================================================================== */
function initStatsCounters() {
  const counters = document.querySelectorAll('.counter');
  if (!counters.length) return;

  const observer = new IntersectionObserver((entries, obs) => {
    entries.forEach(entry => {
      if (!entry.isIntersecting) return;
      const target    = entry.target;
      const targetVal = parseInt(target.getAttribute('data-target') || '0', 10);

      if (typeof gsap !== 'undefined') {
        gsap.to(target, {
          innerText: targetVal,
          duration: 1.8,
          snap: { innerText: 1 },
          ease: 'power2.out'
        });
      } else {
        let count = 0;
        const speed = targetVal / 40;
        const timer = setInterval(() => {
          count += speed;
          if (count >= targetVal) {
            target.innerText = targetVal;
            clearInterval(timer);
          } else {
            target.innerText = Math.ceil(count);
          }
        }, 30);
      }
      obs.unobserve(target);
    });
  }, { threshold: 0.3 });

  counters.forEach(counter => observer.observe(counter));
}


/* ==========================================================================
   6. FILTER TABS (Products & Media pages — excludes gallery section)
   ========================================================================== */
function initFilterTabs() {
  /* Gallery section has its own dedicated controller below */
  const isGalleryPage = !!document.querySelector('.gallery-section');

  const filterBtns  = isGalleryPage
    ? document.querySelectorAll('.filter-btn:not(.gallery-section .filter-btn)')
    : document.querySelectorAll('.filter-btn');

  const filterItems = isGalleryPage
    ? document.querySelectorAll('[data-category]:not(.gallery-item)')
    : document.querySelectorAll('[data-category]');

  if (!filterBtns.length || !filterItems.length) return;

  filterBtns.forEach(btn => {
    btn.addEventListener('click', () => {
      filterBtns.forEach(b => b.classList.remove('active'));
      btn.classList.add('active');

      const filterVal = btn.getAttribute('data-filter');

      filterItems.forEach(item => {
        const category = item.getAttribute('data-category');
        const matches = (filterVal === 'all' || category === filterVal);

        if (matches) {
          const wasHidden = (item.style.display === 'none');
          item.style.display    = '';
          item.style.visibility = 'visible';

          if (wasHidden) {
            if (typeof gsap !== 'undefined') {
              gsap.fromTo(item,
                { opacity: 0, y: 12 },
                { opacity: 1, y: 0, duration: 0.3, ease: 'power2.out', clearProps: 'all' }
              );
            } else {
              item.style.opacity = '1';
            }
          } else {
            item.style.opacity = '1';
          }
        } else {
          item.style.display = 'none';
        }
      });

      if (typeof ScrollTrigger !== 'undefined') {
        setTimeout(() => ScrollTrigger.refresh(), 100);
      }
    });
  });
}


/* ==========================================================================
   6.1 GALLERY INFINITE SCROLL + FILTER (12 per batch)
   ========================================================================== */
function initGalleryInfiniteScroll() {
  const sentinel = document.getElementById('galleryScrollSentinel');
  // Guard: ONLY run on pages with the infinite scroll sentinel (e.g. Gallery page)
  // This prevents breaking the homepage gallery which has no sentinel.
  if (!sentinel) return;

  const galleryGrid = document.querySelector('.gallery-grid');
  if (!galleryGrid) return;

  const allItems = Array.from(galleryGrid.querySelectorAll('.gallery-item'));
  if (!allItems.length) return;

  const endNotice  = document.getElementById('galleryEndNotice');
  const filterBtns = document.querySelectorAll('.gallery-section .filter-btn');

  const PER_PAGE = 12;
  let activeFilter    = 'all';
  let loadedCount     = 0;
  let isLoading       = false;
  let galleryObserver = null;

  /* Hide ALL items on init — show via refreshView */
  allItems.forEach(item => { item.style.display = 'none'; });

  function getMatching() {
    return allItems.filter(item => {
      const cat = item.getAttribute('data-category');
      return activeFilter === 'all' || cat === activeFilter;
    });
  }

  function setupObserver() {
    if (!sentinel) return;
    if (galleryObserver) galleryObserver.disconnect();

    galleryObserver = new IntersectionObserver((entries) => {
      entries.forEach(entry => { if (entry.isIntersecting) loadNextBatch(); });
    }, { rootMargin: '300px 0px 300px 0px', threshold: 0.05 });

    galleryObserver.observe(sentinel);
  }

  function loadNextBatch() {
    if (isLoading) return;
    const matching = getMatching();
    if (loadedCount >= matching.length) return;

    isLoading = true;
    if (sentinel) sentinel.style.display = 'flex';

    setTimeout(() => {
      const batch = matching.slice(loadedCount, loadedCount + PER_PAGE);

      /* Show items without pre-setting opacity:0 to avoid white flash.
         GSAP fromTo handles the full animation. */
      batch.forEach(item => {
        item.style.display    = '';
        item.style.visibility = 'visible';
      });

      loadedCount += batch.length;

      if (typeof gsap !== 'undefined') {
        gsap.fromTo(batch,
          { opacity: 0, y: 20 },
          { opacity: 1, y: 0, duration: 0.35, stagger: 0.04, ease: 'power2.out', clearProps: 'transform' }
        );
      } else {
        batch.forEach(i => { i.style.opacity = '1'; });
      }

      if (typeof ScrollTrigger !== 'undefined') {
        setTimeout(() => ScrollTrigger.refresh(), 120);
      }

      if (loadedCount >= matching.length) {
        if (sentinel) sentinel.style.display = 'none';
        if (endNotice && matching.length > PER_PAGE) endNotice.style.display = 'block';
        if (galleryObserver && sentinel) galleryObserver.unobserve(sentinel);
      }

      isLoading = false;
    }, 100);
  }

  function refreshView() {
    const matching = getMatching();
    loadedCount = 0;

    /* Hide all */
    allItems.forEach(item => { item.style.display = 'none'; item.style.opacity = ''; });

    const initialBatch = matching.slice(0, PER_PAGE);
    initialBatch.forEach(item => {
      item.style.display    = '';
      item.style.visibility = 'visible';
    });
    loadedCount = initialBatch.length;

    if (typeof gsap !== 'undefined' && initialBatch.length) {
      gsap.fromTo(initialBatch,
        { opacity: 0, y: 15 },
        { opacity: 1, y: 0, duration: 0.35, stagger: 0.03, ease: 'power2.out', clearProps: 'transform' }
      );
    } else {
      initialBatch.forEach(i => { i.style.opacity = '1'; });
    }

    if (typeof ScrollTrigger !== 'undefined') {
      setTimeout(() => ScrollTrigger.refresh(), 100);
    }

    if (endNotice) endNotice.style.display = 'none';

    if (loadedCount < matching.length) {
      if (sentinel) sentinel.style.display = 'flex';
      setupObserver();
    } else {
      if (sentinel) sentinel.style.display = 'none';
      if (endNotice && matching.length > PER_PAGE) endNotice.style.display = 'block';
    }
  }

  /* Bind gallery filter tabs */
  filterBtns.forEach(btn => {
    btn.addEventListener('click', () => {
      filterBtns.forEach(b => b.classList.remove('active'));
      btn.classList.add('active');
      activeFilter = btn.getAttribute('data-filter') || 'all';
      refreshView();
    });
  });

  /* Initial render */
  refreshView();
}


/* ==========================================================================
   7. LIGHTBOX MODAL (Image + YouTube video)
   ========================================================================== */
function initLightboxModal() {

  let modal = document.querySelector('.lightbox-modal');

  if (!modal) {
    modal = document.createElement('div');
    modal.className = 'lightbox-modal';
    modal.innerHTML = `
      <div class="lightbox-content" style="max-width:900px;width:90%;">
        <button class="lightbox-close" aria-label="Close lightbox" type="button">&times;</button>
        <div class="lightbox-media-container" id="lightboxMediaContainer" style="
          position:relative;width:100%;display:flex;
          justify-content:center;align-items:center;
          background:#000;border-radius:8px;overflow:hidden;
        "></div>
        <div class="lightbox-caption" id="lightboxCaption"></div>
      </div>`;
    document.body.appendChild(modal);
  }

  const mediaContainer = modal.querySelector('#lightboxMediaContainer');
  const modalCaption   = modal.querySelector('#lightboxCaption');
  const closeBtn       = modal.querySelector('.lightbox-close');

  function openModal(mediaData, isVideo, captionText) {
    if (!mediaData) return;

    mediaContainer.innerHTML = '';
    mediaContainer.style.paddingBottom = '0';

    if (isVideo) {
      mediaContainer.style.paddingBottom = '56.25%';
      const iframe = document.createElement('iframe');
      iframe.src = 'https://www.youtube.com/embed/' + encodeURIComponent(mediaData) + '?autoplay=1&rel=0';
      iframe.title = captionText || 'YouTube video';
      Object.assign(iframe.style, { position:'absolute', top:'0', left:'0', width:'100%', height:'100%', border:'0' });
      iframe.setAttribute('allow', 'accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share');
      iframe.setAttribute('allowfullscreen', '');
      mediaContainer.appendChild(iframe);
    } else {
      const image = document.createElement('img');
      image.src     = mediaData;
      image.alt     = captionText || 'Enlarged View';
      image.id      = 'lightboxImage';
      Object.assign(image.style, { maxWidth:'100%', maxHeight:'80vh', width:'auto', height:'auto', objectFit:'contain', display:'block' });
      mediaContainer.appendChild(image);
    }

    if (modalCaption) modalCaption.innerText = captionText || '';
    modal.classList.add('active');
    document.body.style.overflow = 'hidden';

    if (typeof gsap !== 'undefined') {
      gsap.fromTo(
        modal.querySelector('.lightbox-content'),
        { scale: 0.85, opacity: 0 },
        { scale: 1, opacity: 1, duration: 0.3, ease: 'back.out(1.4)' }
      );
    }
  }

  function closeModal() {
    modal.classList.remove('active');
    document.body.style.overflow = '';
    mediaContainer.innerHTML = '';
    if (modalCaption) modalCaption.innerText = '';
  }

  /* Clicks on gallery items or video press cards */
  document.addEventListener('click', function(e) {
    const item = e.target.closest('.gallery-item, .press-card[data-youtube]');
    if (!item) return;

    e.preventDefault();

    const youtubeId = item.getAttribute('data-youtube');
    const titleEl   = item.querySelector('.gallery-overlay h4, .press-footer h4');
    const caption   = titleEl ? titleEl.innerText.trim() : (item.getAttribute('data-title') || '');

    if (youtubeId && youtubeId.trim()) {
      openModal(youtubeId.trim(), true, caption);
      return;
    }

    const img = item.querySelector('img');
    if (img) {
      const src = img.getAttribute('src') || img.getAttribute('data-src');
      if (src) openModal(src, false, caption || img.getAttribute('alt') || '');
    }
  });

  if (closeBtn) {
    closeBtn.addEventListener('click', (e) => { e.preventDefault(); e.stopPropagation(); closeModal(); });
  }

  modal.addEventListener('click', (e) => { if (e.target === modal) closeModal(); });

  document.addEventListener('keydown', (e) => {
    if (e.key === 'Escape' && modal.classList.contains('active')) closeModal();
  });
}


/* ==========================================================================
   8. FAQ ACCORDION — 2-column split + toggle
   ========================================================================== */
function initFAQAccordion() {
  /* Column split is also handled inline in faq-accordion.php for early render,
     but we run it here too for any dynamically loaded content */
  const containers = document.querySelectorAll('.faq-container');
  containers.forEach(container => {
    if (container.querySelector('.faq-column')) return; /* already split */
    const items = Array.from(container.querySelectorAll(':scope > .faq-item'));
    if (items.length <= 1) return;

    const leftCount = Math.ceil(items.length / 2);
    const leftCol   = document.createElement('div');
    leftCol.className = 'faq-column faq-column-left';
    const rightCol  = document.createElement('div');
    rightCol.className = 'faq-column faq-column-right';

    items.forEach((item, idx) => {
      (idx < leftCount ? leftCol : rightCol).appendChild(item);
    });

    container.innerHTML = '';
    container.appendChild(leftCol);
    container.appendChild(rightCol);
  });

  /* Accordion toggle */
  document.querySelectorAll('.faq-header').forEach(header => {
    if (header.dataset.faqBound) return;
    header.dataset.faqBound = 'true';

    header.addEventListener('click', () => {
      const item    = header.closest('.faq-item');
      if (!item) return;
      const content = item.querySelector('.faq-content');
      if (!content) return;

      const isOpening = !item.classList.contains('active');

      /* Close all others */
      document.querySelectorAll('.faq-item.active').forEach(other => {
        if (other === item) return;
        other.classList.remove('active');
        const otherHeader  = other.querySelector('.faq-header');
        const otherContent = other.querySelector('.faq-content');
        if (otherHeader)  otherHeader.setAttribute('aria-expanded', 'false');
        if (otherContent) otherContent.style.maxHeight = null;
      });

      if (isOpening) {
        item.classList.add('active');
        header.setAttribute('aria-expanded', 'true');
        content.style.maxHeight = content.scrollHeight + 'px';
      } else {
        item.classList.remove('active');
        header.setAttribute('aria-expanded', 'false');
        content.style.maxHeight = null;
      }
    });
  });
}


/* ==========================================================================
   9. 3D TILT + LIGHT SHEEN on Cards
   ========================================================================== */
function initTiltEffect() {
  const cards = document.querySelectorAll(
    '.product-card, .product-box, .hero-image-card, .founder-card, ' +
    '.facility-card, .blog-card, .timeline-card-box, .timeline-img-card'
  );

  cards.forEach(card => {
    if (card.dataset.tiltBound) return;
    card.dataset.tiltBound = 'true';

    card.addEventListener('mousemove', (e) => {
      const rect    = card.getBoundingClientRect();
      const x       = e.clientX - rect.left;
      const y       = e.clientY - rect.top;
      const rotateX = ((y - rect.height / 2) / (rect.height / 2)) * -3;
      const rotateY = ((x - rect.width  / 2) / (rect.width  / 2)) *  3;
      card.style.transform = `perspective(1000px) rotateX(${rotateX}deg) rotateY(${rotateY}deg) translateY(-4px)`;
    });

    card.addEventListener('mouseleave', () => {
      card.style.transform = '';
    });
  });
}
window.initTiltEffect = initTiltEffect;


/* ==========================================================================
   10. BATCH YIELD CALCULATOR
   ========================================================================== */
function initBatchCalculator() {
  const produceSelect = document.getElementById('calcProduceSelect');
  const weightInput   = document.getElementById('calcWeightInput');
  const pkgSelect     = document.getElementById('calcPkgSelect');

  /* Only run if calculator exists on this page */
  if (!produceSelect || !weightInput || !pkgSelect) return;

  ['change', 'input'].forEach(evt => {
    produceSelect.addEventListener(evt, calculateBatchYield);
    weightInput.addEventListener(evt, calculateBatchYield);
    pkgSelect.addEventListener(evt, calculateBatchYield);
  });

  calculateBatchYield();
}

/* Called by preset buttons via onclick in HTML */
function setCalcWeightPreset(val) {
  const weightInput = document.getElementById('calcWeightInput');
  if (!weightInput) return;
  weightInput.value = val;
  calculateBatchYield();

  document.querySelectorAll('.calc-preset-btn').forEach(btn => {
    const presetVal = parseInt(btn.getAttribute('data-val'), 10);
    btn.classList.toggle('active', presetVal === val);
  });
}

function calculateBatchYield() {
  const produceSelect     = document.getElementById('calcProduceSelect');
  const weightInput       = document.getElementById('calcWeightInput');
  const pkgSelect         = document.getElementById('calcPkgSelect');
  const outputUnitsEl     = document.getElementById('calcOutputUnits');
  const outputShelfEl     = document.getElementById('calcOutputShelf');
  const outputBreakdownEl = document.getElementById('calcBreakdownSummary');

  if (!produceSelect || !weightInput || !pkgSelect || !outputUnitsEl || !outputShelfEl) return;

  const produce = produceSelect.value || 'mango';
  let   weight  = parseFloat(weightInput.value);
  if (isNaN(weight) || weight < 0) weight = 0;

  const pkg = pkgSelect.value || 'tin';

  /* Packaging presets */
  const pkgMap = {
    tin:   { weight: 0.85, shelf: '18 – 24 Months', label: 'Cans',    spec: '850g Hermetic Can' },
    pouch: { weight: 1.0,  shelf: '12 – 18 Months', label: 'Pouches', spec: '1kg Retort Pouch'  },
    jar:   { weight: 0.5,  shelf: '12 – 15 Months', label: 'Jars',    spec: '500g Glass Jar'    }
  };
  const pkgData = pkgMap[pkg] || pkgMap.tin;

  /* Produce presets */
  const produceMap = {
    mango:  { yield: 0.70, name: 'Alphonso / Kesar Mango',   extract: 'Pure Mango Pulp'       },
    tomato: { yield: 0.85, name: 'Fresh Red Tomato',          extract: 'Concentrated Puree'    },
    amla:   { yield: 0.65, name: 'Indian Gooseberry (Amla)', extract: 'Cold-Press Juice'       },
    fruit:  { yield: 0.75, name: 'Mixed Orchard Fruits',      extract: 'Fruit Jam Base'        }
  };
  const produceData = produceMap[produce] || produceMap.mango;

  const totalPulpKg    = weight * produceData.yield;
  const unitsProduced  = weight > 0 ? Math.floor(totalPulpKg / pkgData.weight) : 0;
  const formattedUnits = unitsProduced.toLocaleString('en-US');

  outputUnitsEl.textContent = formattedUnits + ' ' + pkgData.label;
  outputShelfEl.textContent = pkgData.shelf;

  if (outputBreakdownEl) {
    if (weight > 0) {
      outputBreakdownEl.innerHTML =
        '<i class="fa-solid fa-chart-pie" style="color:var(--accent);"></i> ' +
        'Batch Yield Summary: <strong>' + weight + ' KG</strong> ' + produceData.name +
        ' &rarr; <strong>' + totalPulpKg.toFixed(1) + ' KG</strong> ' + produceData.extract +
        ' (' + (produceData.yield * 100).toFixed(0) + '% Yield)' +
        ' &rarr; <strong>' + formattedUnits + ' ' + pkgData.label + '</strong>' +
        ' (' + pkgData.spec + ')';
    } else {
      outputBreakdownEl.innerHTML =
        '<i class="fa-solid fa-info-circle" style="color:var(--accent);"></i> ' +
        'Please enter raw produce weight to calculate net yield and packed units.';
    }
  }

  /* Sync preset buttons */
  document.querySelectorAll('.calc-preset-btn').forEach(btn => {
    const presetVal = parseInt(btn.getAttribute('data-val'), 10);
    btn.classList.toggle('active', presetVal === weight);
  });
}


/* ==========================================================================
   11. NEWS & MEDIA — Document / Press Clipping Lightbox
   ========================================================================== */
function initMediaDocLightbox() {
  const pressGrid    = document.getElementById('pressGrid');
  const docLightbox  = document.getElementById('docLightbox');
  const modalTitle   = document.getElementById('modalDocTitle');
  const modalImg     = document.getElementById('modalDocImg');

  function openDocLightbox(title, image) {
    if (!docLightbox || !modalTitle || !modalImg) return;
    modalTitle.textContent = title;
    modalImg.src           = image;
    modalImg.alt           = title;
    docLightbox.classList.add('active');
    docLightbox.setAttribute('aria-hidden', 'false');
    document.body.style.overflow = 'hidden';
  }

  function closeDocLightbox() {
    if (!docLightbox) return;
    docLightbox.classList.remove('active');
    docLightbox.setAttribute('aria-hidden', 'true');
    document.body.style.overflow = '';
  }

  /* Expose globally so onclick="closeLightbox()" in PHP template still works */
  window.closeLightbox = closeDocLightbox;

  if (!pressGrid) return;

  pressGrid.addEventListener('click', (e) => {
    const card = e.target.closest('.press-card');
    if (!card) return;

    /* Video cards are handled by initLightboxModal — skip here */
    const yt = card.getAttribute('data-youtube');
    if (yt && yt.trim()) return;

    const title = card.getAttribute('data-title');
    const image = card.getAttribute('data-image');
    if (!title || !image) return;

    openDocLightbox(title, image);
  });

  pressGrid.addEventListener('keydown', (e) => {
    if (e.key !== 'Enter' && e.key !== ' ') return;
    const card = e.target.closest('.press-card');
    if (!card) return;
    e.preventDefault();

    const yt = card.getAttribute('data-youtube');
    if (yt && yt.trim()) { card.click(); return; }

    const title = card.getAttribute('data-title');
    const image = card.getAttribute('data-image');
    if (title && image) openDocLightbox(title, image);
  });

  if (docLightbox) {
    docLightbox.addEventListener('click', (e) => {
      if (e.target === docLightbox) closeDocLightbox();
    });
  }

  document.addEventListener('keydown', (e) => {
    if (e.key === 'Escape') closeDocLightbox();
  });
}


/* ==========================================================================
   12. PRODUCT DETAIL — Thumbnail Switcher
   ========================================================================== */
function changeProductDetailImg(thumbEl) {
  if (!thumbEl) return;
  const mainImg = document.getElementById('mainProductImg');
  if (mainImg) mainImg.src = thumbEl.src;

  const gallery = thumbEl.parentElement;
  if (gallery) {
    gallery.querySelectorAll('img, .product-thumb').forEach(t => {
      t.classList.remove('active');
      t.style.border = '2px solid var(--border-medium)';
    });
  }

  thumbEl.classList.add('active');
  thumbEl.style.border = '2.5px solid var(--accent)';
}


/* ==========================================================================
   13. CONTACT FORM AUTOFILL (Product Inquiries & Samples)
   ========================================================================== */
function initContactFormAutoFill() {
  const urlParams = new URLSearchParams(window.location.search);
  const product = urlParams.get('product');
  if (!product) return;

  const type = urlParams.get('type') || 'bulk';
  const isSample = type === 'sample';

  const defaultSubject = isSample
    ? `Sample Pack Request: ${product}`
    : `Bulk Batch Order Inquiry: ${product}`;

  const defaultMessage = isSample
    ? `Hello Jalpa & Asal Canning Team,\n\nI would like to request sample units and technical specifications for "${product}". Please share the sample kit details, lead time, and delivery options.\n\nThank you!`
    : `Hello Jalpa & Asal Canning Team,\n\nI am interested in placing a bulk batch order for "${product}". Please provide details regarding batch minimums, production scheduling, packaging formats, and pricing.\n\nThank you!`;

  // Pre-fill Subject field
  const subjectInput = document.querySelector('input[name="your-subject"], input[name="subject"], input[name*="subject"]');
  if (subjectInput && !subjectInput.value) {
    subjectInput.value = defaultSubject;
  }

  // Pre-fill Message field
  const messageInput = document.querySelector('textarea[name="your-message"], textarea[name="message"], textarea[name*="message"]');
  if (messageInput && !messageInput.value) {
    messageInput.value = defaultMessage;
  }

  // Smooth scroll to form if product query is present
  const formWrap = document.getElementById('contactFormWrap');
  if (formWrap) {
    setTimeout(() => {
      formWrap.scrollIntoView({ behavior: 'smooth', block: 'center' });
    }, 450);
  }
}