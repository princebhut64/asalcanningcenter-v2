/* ==========================================================================
   ASAL CANNING CENTER - GSAP 3 Motion & Interactive Controller
   ========================================================================== */
/* ==========================================================================
   BATCH YIELD & SHELF LIFE CALCULATOR
   ========================================================================== */

(function () {

    'use strict';


    /* ----------------------------------------------------------------------
       GET ACF DATA
       ---------------------------------------------------------------------- */

    const calculatorData = window.batchCalculatorData || {};

    const produceData = Array.isArray(calculatorData.produce)
        ? calculatorData.produce
        : [];

    const packagingData = Array.isArray(calculatorData.packaging)
        ? calculatorData.packaging
        : [];


    /* ----------------------------------------------------------------------
       INITIALIZE CALCULATOR
       ---------------------------------------------------------------------- */

    function initBatchCalculator() {

        const produceSelect = document.getElementById('calcProduce');

        const weightInput = document.getElementById('calcWeight');

        const packageSelect = document.getElementById('calcPackage');


        /*
         * If calculator is not on this page
         */
        if (
            !produceSelect ||
            !weightInput ||
            !packageSelect
        ) {
            return;
        }


        /*
         * Produce change
         */
        produceSelect.addEventListener(
            'change',
            calculateBatchYield
        );


        /*
         * Weight typing
         */
        weightInput.addEventListener(
            'input',
            calculateBatchYield
        );


        weightInput.addEventListener(
            'keyup',
            calculateBatchYield
        );


        /*
         * Package change
         */
        packageSelect.addEventListener(
            'change',
            calculateBatchYield
        );


        /*
         * Preset buttons
         */
        const presetButtons =
            document.querySelectorAll('.calc-preset-btn');


        presetButtons.forEach(function (button) {

            button.addEventListener('click', function (event) {

                event.preventDefault();


                const value =
                    parseFloat(this.getAttribute('data-val'));


                if (isNaN(value)) {
                    return;
                }


                /*
                 * Set weight
                 */
                weightInput.value = value;


                /*
                 * Calculate
                 */
                calculateBatchYield();


                /*
                 * Active button
                 */
                presetButtons.forEach(function (btn) {

                    btn.classList.remove('active');

                });


                this.classList.add('active');

            });

        });


        /*
         * Initial calculation
         */
        calculateBatchYield();

    }


    /* ----------------------------------------------------------------------
       CALCULATE BATCH YIELD
       ---------------------------------------------------------------------- */

    function calculateBatchYield() {

        const produceSelect =
            document.getElementById('calcProduce');

        const weightInput =
            document.getElementById('calcWeight');

        const packageSelect =
            document.getElementById('calcPackage');


        const outputUnits =
            document.getElementById('outputUnits');

        const outputShelf =
            document.getElementById('outputShelf');

        const outputBreakdown =
            document.getElementById('outputBreakdown');


        /*
         * Safety check
         */
        if (
            !produceSelect ||
            !weightInput ||
            !packageSelect ||
            !outputUnits ||
            !outputShelf
        ) {
            return;
        }


        /* ------------------------------------------------------------------
           GET VALUES
           ------------------------------------------------------------------ */

        const produceValue =
            produceSelect.value;


        const packageValue =
            packageSelect.value;


        let weight =
            parseFloat(weightInput.value);


        /*
         * Invalid weight
         */
        if (
            isNaN(weight) ||
            weight <= 0
        ) {

            outputUnits.textContent =
                '0 Units';


            outputShelf.innerHTML =
                '<i class="fa-solid fa-clock"></i> ' +
                'Please enter a valid weight';


            if (outputBreakdown) {

                outputBreakdown.innerHTML =
                    '<i class="fa-solid fa-info-circle" ' +
                    'style="color: var(--accent);"></i> ' +
                    'Please enter raw produce weight to calculate ' +
                    'net yield and packed units.';

            }


            updatePresetButtons(0);

            return;
        }


        /* ------------------------------------------------------------------
           FIND PRODUCE
           ------------------------------------------------------------------ */

        let selectedProduce = null;


        produceData.forEach(function (item) {

            if (String(item.value) === String(produceValue)) {

                selectedProduce = item;

            }

        });


        /* ------------------------------------------------------------------
           FIND PACKAGING
           ------------------------------------------------------------------ */

        let selectedPackage = null;


        packagingData.forEach(function (item) {

            if (String(item.value) === String(packageValue)) {

                selectedPackage = item;

            }

        });


        /* ------------------------------------------------------------------
           SAFETY FALLBACK
           ------------------------------------------------------------------ */

        if (!selectedProduce) {

            selectedProduce = {
                value: 'mango',
                name: 'Alphonso / Kesar Mango',
                yield: 70,
                extract: 'Pure Pulp'
            };

        }


        if (!selectedPackage) {

            selectedPackage = {
                value: '850g-can',
                name: '850g Hermetic Tin Can',
                unit_weight: 0.85,
                unit_label: 'Cans',
                shelf_life: '12 - 24 Months',
                specification: '850g Hermetic Tin Can'
            };

        }


        /* ------------------------------------------------------------------
           GET ACF VALUES
           ------------------------------------------------------------------ */

        const yieldPercentage =
            parseFloat(selectedProduce.yield) || 0;


        const unitWeight =
            parseFloat(selectedPackage.unit_weight) || 0;


        const produceName =
            selectedProduce.name || 'Produce';


        const extractType =
            selectedProduce.extract || 'Extract';


        const unitLabel =
            selectedPackage.unit_label || 'Units';


        const shelfLife =
            selectedPackage.shelf_life || '-';


        const specification =
            selectedPackage.specification ||
            selectedPackage.name ||
            'Package';


        /* ------------------------------------------------------------------
           CALCULATE NET PULP
           ------------------------------------------------------------------ */

        const yieldFactor =
            yieldPercentage / 100;


        const totalPulpKg =
            weight * yieldFactor;


        /* ------------------------------------------------------------------
           CALCULATE PACKED UNITS
           ------------------------------------------------------------------ */

        let unitsProduced = 0;


        if (unitWeight > 0) {

            unitsProduced =
                Math.floor(totalPulpKg / unitWeight);

        }


        /* ------------------------------------------------------------------
           FORMAT NUMBERS
           ------------------------------------------------------------------ */

        const formattedUnits =
            unitsProduced.toLocaleString('en-US');


        const formattedWeight =
            weight.toLocaleString('en-US', {
                maximumFractionDigits: 1
            });


        const formattedPulp =
            totalPulpKg.toLocaleString('en-US', {
                minimumFractionDigits: 1,
                maximumFractionDigits: 1
            });


        /* ------------------------------------------------------------------
           UPDATE MAIN OUTPUT
           ------------------------------------------------------------------ */

        outputUnits.textContent =
            formattedUnits + ' ' + unitLabel;


        /* ------------------------------------------------------------------
           UPDATE SHELF LIFE
           ------------------------------------------------------------------ */

        outputShelf.innerHTML =
            '<i class="fa-solid fa-clock"></i> ' +
            'Ambient Shelf Life: ' +
            escapeHtml(shelfLife);


        /* ------------------------------------------------------------------
           UPDATE BREAKDOWN
           ------------------------------------------------------------------ */

        if (outputBreakdown) {

            outputBreakdown.innerHTML =
                '<i class="fa-solid fa-chart-pie" ' +
                'style="color: var(--accent);"></i> ' +

                'Batch Yield Summary: ' +

                '<strong>' +
                escapeHtml(formattedWeight) +
                ' KG</strong> ' +

                escapeHtml(produceName) +

                ' &rarr; ' +

                '<strong>' +
                escapeHtml(formattedPulp) +
                ' KG</strong> ' +

                escapeHtml(extractType) +

                ' (' +
                escapeHtml(yieldPercentage) +
                '% Yield) ' +

                '&rarr; ' +

                '<strong>' +
                escapeHtml(formattedUnits) +
                ' ' +
                escapeHtml(unitLabel) +
                '</strong> ' +

                '(' +
                escapeHtml(specification) +
                ')';

        }


        /* ------------------------------------------------------------------
           UPDATE PRESET
           ------------------------------------------------------------------ */

        updatePresetButtons(weight);

    }


    /* ----------------------------------------------------------------------
       PRESET ACTIVE STATE
       ---------------------------------------------------------------------- */

    function updatePresetButtons(weight) {

        const buttons =
            document.querySelectorAll('.calc-preset-btn');


        buttons.forEach(function (button) {

            const presetValue =
                parseFloat(
                    button.getAttribute('data-val')
                );


            if (
                !isNaN(presetValue) &&
                presetValue === weight
            ) {

                button.classList.add('active');

            } else {

                button.classList.remove('active');

            }

        });

    }


    /* ----------------------------------------------------------------------
       ESCAPE HTML
       ---------------------------------------------------------------------- */

    function escapeHtml(value) {

        return String(value)
            .replace(/&/g, '&amp;')
            .replace(/</g, '&lt;')
            .replace(/>/g, '&gt;')
            .replace(/"/g, '&quot;')
            .replace(/'/g, '&#039;');

    }


    /* ----------------------------------------------------------------------
       DOM READY
       ---------------------------------------------------------------------- */

    if (document.readyState === 'loading') {

        document.addEventListener(
            'DOMContentLoaded',
            initBatchCalculator
        );

    } else {

        initBatchCalculator();

    }


})();

/* News & Media Archive JavaScript */

document.addEventListener('DOMContentLoaded', function () {

    const pressGrid = document.getElementById('pressGrid');
    const docLightbox = document.getElementById('docLightbox');
    const modalDocTitle = document.getElementById('modalDocTitle');
    const modalDocImg = document.getElementById('modalDocImg');


    /*
    |--------------------------------------------------------------------------
    | FILTER BUTTONS
    |--------------------------------------------------------------------------
    */

    const filterBtns = document.querySelectorAll('.filter-btn');

    filterBtns.forEach(function (btn) {

        btn.addEventListener('click', function () {

            filterBtns.forEach(function (button) {
                button.classList.remove('active');
            });

            btn.classList.add('active');

            const filter = btn.getAttribute('data-filter');

            const cards = document.querySelectorAll(
                '#pressGrid .press-card'
            );

            cards.forEach(function (card) {

                const category = card.getAttribute('data-category');

                if (
                    filter === 'all' ||
                    category === filter
                ) {

                    card.style.display = '';

                } else {

                    card.style.display = 'none';

                }

            });

        });

    });


    /*
    |--------------------------------------------------------------------------
    | OPEN LIGHTBOX
    |--------------------------------------------------------------------------
    */

    function openLightbox(title, image) {

        if (!docLightbox || !modalDocTitle || !modalDocImg) {
            return;
        }

        modalDocTitle.textContent = title;

        modalDocImg.src = image;
        modalDocImg.alt = title;


        docLightbox.classList.add('active');

        docLightbox.setAttribute(
            'aria-hidden',
            'false'
        );


        // Prevent background scrolling
        document.body.style.overflow = 'hidden';

    }


    /*
    |--------------------------------------------------------------------------
    | CLOSE LIGHTBOX
    |--------------------------------------------------------------------------
    */

    function closeLightbox() {

        if (!docLightbox) {
            return;
        }

        docLightbox.classList.remove('active');

        docLightbox.setAttribute(
            'aria-hidden',
            'true'
        );


        // Restore scrolling
        document.body.style.overflow = '';

    }


    /*
    |--------------------------------------------------------------------------
    | GLOBAL FUNCTION
    |--------------------------------------------------------------------------
    */

    window.closeLightbox = closeLightbox;


    /*
    |--------------------------------------------------------------------------
    | PRESS CARD CLICK
    |--------------------------------------------------------------------------
    */

    if (pressGrid) {

        pressGrid.addEventListener('click', function (event) {

            const card = event.target.closest('.press-card');

            if (!card) {
                return;
            }


            const title = card.getAttribute('data-title');

            const image = card.getAttribute('data-image');


            if (!title || !image) {
                console.error(
                    'Media title or image is missing:',
                    card
                );

                return;
            }


            openLightbox(
                title,
                image
            );

        });


        /*
        |--------------------------------------------------------------------------
        | KEYBOARD ACCESSIBILITY
        |--------------------------------------------------------------------------
        */

        pressGrid.addEventListener('keydown', function (event) {

            const card = event.target.closest('.press-card');

            if (!card) {
                return;
            }


            if (
                event.key === 'Enter' ||
                event.key === ' '
            ) {

                event.preventDefault();

                const title = card.getAttribute('data-title');

                const image = card.getAttribute('data-image');


                if (title && image) {

                    openLightbox(
                        title,
                        image
                    );

                }

            }

        });

    }


    /*
    |--------------------------------------------------------------------------
    | CLICK OUTSIDE LIGHTBOX
    |--------------------------------------------------------------------------
    */

    if (docLightbox) {

        docLightbox.addEventListener('click', function (event) {

            /*
             * Close only when clicking the dark overlay,
             * not the actual lightbox frame.
             */

            if (event.target === docLightbox) {

                closeLightbox();

            }

        });

    }


    /*
    |--------------------------------------------------------------------------
    | ESC KEY
    |--------------------------------------------------------------------------
    */

    document.addEventListener('keydown', function (event) {

        if (event.key === 'Escape') {

            closeLightbox();

        }

    });

});
/* ==========================================================================
   ASAL CANNING CENTER - GSAP 3 Motion & Interactive Controller
   ========================================================================== */

document.addEventListener('DOMContentLoaded', () => {
  // 1. Initialize GSAP & ScrollTrigger Plugins
  if (typeof gsap !== 'undefined') {
    gsap.registerPlugin(ScrollTrigger);
    initGSAPAnimations();
    initAmbientLightBeams();
    initTimelineProgressAnimation();
  } else {
    console.warn('GSAP library not detected. Basic fallbacks will run.');
  }

  // 2. Navigation & Header Controllers
  initNavigation();

  // 3. Counter Animation Controller
  initStatsCounters();

  // 4. Category & Gallery Filter Tabs
  initFilterTabs();

  // 5. Lightbox Modal Controller
  initLightboxModal();

  // 6. FAQ Accordion Toggle
  initFAQAccordion();

  // 7. 3D Tilt & Light Sweep Sheen Effect on Cards
  initTiltEffect();

  // 8. Batch Yield & Shelf Life Calculator Initial Run & Listeners
  initBatchCalculator();
});

/* --------------------------------------------------------------------------
   1. BREATHING AMBIENT LIGHT RAYS & GLOW BEAMS
   -------------------------------------------------------------------------- */
function initAmbientLightBeams() {
  if (typeof gsap !== 'undefined') {
    gsap.to('.ambient-light-gold', {
      scale: 1.25,
      opacity: 0.85,
      duration: 4.5,
      repeat: -1,
      yoyo: true,
      ease: 'sine.inOut'
    });

    gsap.to('.ambient-light-terracotta', {
      scale: 1.2,
      opacity: 0.65,
      duration: 5.5,
      repeat: -1,
      yoyo: true,
      ease: 'sine.inOut'
    });
  }
}

/* --------------------------------------------------------------------------
   2. GSAP ANIMATIONS
   -------------------------------------------------------------------------- */
function initGSAPAnimations() {
  // Header Entrance
  gsap.from('header', {
    y: -50,
    opacity: 0,
    duration: 0.8,
    ease: 'power3.out'
  });

  // Hero Section Stagger
  if (document.querySelector('.hero-section')) {
    const heroTl = gsap.timeline({ defaults: { ease: 'power3.out', duration: 0.8 } });
    heroTl.from('.hero-badge', { opacity: 0, y: 20, delay: 0.1 })
          .from('.hero-content h1', { opacity: 0, y: 25 }, '-=0.5')
          .from('.hero-content p', { opacity: 0, y: 20 }, '-=0.6')
          .from('.hero-cta-group', { opacity: 0, y: 15 }, '-=0.5')
          .from('.hero-features-list', { opacity: 0, y: 15 }, '-=0.4')
          .from('.hero-image-card', { opacity: 0, scale: 0.95, duration: 1 }, '-=0.8')
          .from('.hero-glass-badge', { opacity: 0, x: -20, duration: 0.6 }, '-=0.5');
  }

  // Reveal Sections on Scroll using fromTo so opacity is never stuck at 0
  gsap.utils.toArray('.gsap-reveal').forEach((el) => {
    gsap.fromTo(el, 
      { opacity: 0, y: 35 },
      {
        scrollTrigger: {
          trigger: el,
          start: 'top 90%',
          toggleActions: 'play none none none'
        },
        opacity: 1,
        y: 0,
        duration: 0.8,
        ease: 'power3.out',
        clearProps: 'all'
      }
    );
  });

  // Stagger Grids (Products, Process, Values, Gallery)
  gsap.utils.toArray('.gsap-grid-stagger').forEach((grid) => {
    const items = Array.from(grid.children);
    gsap.fromTo(items, 
      { opacity: 0, y: 30 },
      {
        scrollTrigger: {
          trigger: grid,
          start: 'top 90%',
          toggleActions: 'play none none none'
        },
        opacity: 1,
        y: 0,
        duration: 0.6,
        stagger: 0.1,
        ease: 'power3.out',
        clearProps: 'all'
      }
    );
  });
}

/* --------------------------------------------------------------------------
   3. ULTRA INTERACTIVE ANIMATED TIMELINE JOURNEY
   -------------------------------------------------------------------------- */
function initTimelineProgressAnimation() {
  if (typeof gsap === 'undefined' || typeof ScrollTrigger === 'undefined') return;

  const timelineGrids = document.querySelectorAll('.timeline-split-grid');
  if (!timelineGrids.length) return;

  timelineGrids.forEach((grid, idx) => {
    const cardBox = grid.querySelector('.timeline-card-box');
    const imgCard = grid.querySelector('.timeline-img-card');
    const nodeCircle = grid.querySelector('.timeline-node-circle');

    const tl = gsap.timeline({
      scrollTrigger: {
        trigger: grid,
        start: 'top 85%',
        toggleActions: 'play none none none'
      }
    });

    if (nodeCircle) {
      tl.fromTo(nodeCircle, 
        { scale: 0, opacity: 0 }, 
        { scale: 1, opacity: 1, duration: 0.5, ease: 'back.out(1.7)' }
      );
    }

    if (cardBox) {
      const fromLeft = idx % 2 === 0;
      tl.fromTo(cardBox, 
        { opacity: 0, x: fromLeft ? -60 : 60 }, 
        { opacity: 1, x: 0, duration: 0.7, ease: 'power3.out', clearProps: 'transform' },
        '-=0.3'
      );
    }

    if (imgCard) {
      const fromRight = idx % 2 === 0;
      tl.fromTo(imgCard, 
        { opacity: 0, x: fromRight ? 60 : -60, scale: 0.95 }, 
        { opacity: 1, x: 0, scale: 1, duration: 0.7, ease: 'power3.out', clearProps: 'transform' },
        '-=0.6'
      );
    }
  });
}

/* --------------------------------------------------------------------------
   4. NAVIGATION & HEADER CONTROLLER
   -------------------------------------------------------------------------- */
function initNavigation() {
  const header = document.querySelector('header');
  const menuBtn = document.querySelector('.mobile-menu-btn');
  const nav = document.querySelector('nav');

  window.addEventListener('scroll', () => {
    if (window.scrollY > 40) {
      header.classList.add('scrolled');
    } else {
      header.classList.remove('scrolled');
    }
  });

  if (menuBtn && nav) {
    menuBtn.addEventListener('click', () => {
      nav.classList.toggle('active');
      const icon = menuBtn.querySelector('i');
      if (icon) {
        if (nav.classList.contains('active')) {
          icon.classList.replace('fa-bars', 'fa-xmark');
        } else {
          icon.classList.replace('fa-xmark', 'fa-bars');
        }
      }
    });

    nav.querySelectorAll('a').forEach(link => {
      link.addEventListener('click', () => {
        nav.classList.remove('active');
        const icon = menuBtn.querySelector('i');
        if (icon) icon.classList.replace('fa-xmark', 'fa-bars');
      });
    });
  }
}

/* --------------------------------------------------------------------------
   5. STATS COUNTER ANIMATION
   -------------------------------------------------------------------------- */
function initStatsCounters() {
  const counters = document.querySelectorAll('.counter');
  if (!counters.length) return;

  const observer = new IntersectionObserver((entries, observerInstance) => {
    entries.forEach(entry => {
      if (entry.isIntersecting) {
        const target = entry.target;
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
        observerInstance.unobserve(target);
      }
    });
  }, { threshold: 0.3 });

  counters.forEach(counter => observer.observe(counter));
}

/* --------------------------------------------------------------------------
   6. FILTER TABS (PRODUCTS & GALLERY)
   -------------------------------------------------------------------------- */
function initFilterTabs() {
  const filterBtns = document.querySelectorAll('.filter-btn');
  const filterItems = document.querySelectorAll('[data-category]');

  if (!filterBtns.length || !filterItems.length) return;

  filterBtns.forEach(btn => {
    btn.addEventListener('click', () => {
      filterBtns.forEach(b => b.classList.remove('active'));
      btn.classList.add('active');

      const filterVal = btn.getAttribute('data-filter');

      filterItems.forEach(item => {
        const category = item.getAttribute('data-category');
        if (filterVal === 'all' || category === filterVal) {
          item.style.display = '';
          item.style.opacity = '1';
          item.style.visibility = 'visible';
          
          if (typeof gsap !== 'undefined') {
            gsap.fromTo(item, 
              { opacity: 0, y: 15 }, 
              { opacity: 1, y: 0, duration: 0.35, ease: 'power2.out', clearProps: 'transform' }
            );
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

/* --------------------------------------------------------------------------
   7. LIGHTBOX MODAL - IMAGE + YOUTUBE VIDEO
   -------------------------------------------------------------------------- */
function initLightboxModal() {

  let modal = document.querySelector('.lightbox-modal');

  if (!modal) {

    modal = document.createElement('div');
    modal.className = 'lightbox-modal';

    modal.innerHTML = `
      <div class="lightbox-content" style="max-width: 900px; width: 90%;">

        <button
          class="lightbox-close"
          aria-label="Close lightbox"
          type="button"
        >&times;</button>

        <div
          class="lightbox-media-container"
          id="lightboxMediaContainer"
          style="
            position: relative;
            width: 100%;
            display: flex;
            justify-content: center;
            align-items: center;
            background: #000;
            border-radius: 8px;
            overflow: hidden;
          "
        ></div>

        <div
          class="lightbox-caption"
          id="lightboxCaption"
        ></div>

      </div>
    `;

    document.body.appendChild(modal);
  }


  const mediaContainer = modal.querySelector('#lightboxMediaContainer');
  const modalCaption = modal.querySelector('#lightboxCaption');
  const closeBtn = modal.querySelector('.lightbox-close');


  /* ----------------------------------------------------------------------
     OPEN MODAL
     ---------------------------------------------------------------------- */
  function openModal(mediaData, isVideo, captionText) {

    if (!mediaData) {
      console.log('Lightbox: No media found');
      return;
    }

    mediaContainer.innerHTML = '';
    mediaContainer.style.paddingBottom = '0';


    /* --------------------------------------------------------------------
       YOUTUBE VIDEO
       -------------------------------------------------------------------- */
    if (isVideo) {

      console.log('Opening YouTube:', mediaData);

      mediaContainer.style.paddingBottom = '56.25%';

      const iframe = document.createElement('iframe');

      iframe.src =
        'https://www.youtube.com/embed/' +
        encodeURIComponent(mediaData) +
        '?autoplay=1&rel=0';

      iframe.title = captionText || 'YouTube video';

      iframe.style.position = 'absolute';
      iframe.style.top = '0';
      iframe.style.left = '0';
      iframe.style.width = '100%';
      iframe.style.height = '100%';
      iframe.style.border = '0';

      iframe.setAttribute(
        'allow',
        'accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share'
      );

      iframe.setAttribute(
        'allowfullscreen',
        ''
      );

      mediaContainer.appendChild(iframe);

    }


    /* --------------------------------------------------------------------
       IMAGE
       -------------------------------------------------------------------- */
    else {

      console.log('Opening Image:', mediaData);

      const image = document.createElement('img');

      image.src = mediaData;
      image.alt = captionText || 'Enlarged View';

      image.id = 'lightboxImage';

      image.style.maxWidth = '100%';
      image.style.maxHeight = '80vh';
      image.style.width = 'auto';
      image.style.height = 'auto';
      image.style.objectFit = 'contain';
      image.style.display = 'block';

      mediaContainer.appendChild(image);
    }


    modalCaption.innerText = captionText || '';

    modal.classList.add('active');

    document.body.style.overflow = 'hidden';


    /* GSAP animation */
    if (typeof gsap !== 'undefined') {

      gsap.fromTo(
        modal.querySelector('.lightbox-content'),
        {
          scale: 0.8,
          opacity: 0
        },
        {
          scale: 1,
          opacity: 1,
          duration: 0.35,
          ease: 'back.out(1.4)'
        }
      );

    }

  }


  /* ----------------------------------------------------------------------
     CLOSE MODAL
     ---------------------------------------------------------------------- */
  function closeModal() {

    modal.classList.remove('active');

    document.body.style.overflow = '';

    /*
     * Remove iframe/image.
     * This also stops YouTube playback.
     */
    mediaContainer.innerHTML = '';

    modalCaption.innerText = '';
  }


  /* ----------------------------------------------------------------------
     GALLERY CLICK
     ---------------------------------------------------------------------- */
  document.addEventListener('click', function (e) {

    const galleryItem = e.target.closest('.gallery-item');

    if (!galleryItem) {
      return;
    }

    e.preventDefault();


    /* ---------------------------------------------------------------
       GET YOUTUBE ID
       --------------------------------------------------------------- */
    const youtubeId = galleryItem.getAttribute('data-youtube');


    /* ---------------------------------------------------------------
       GET TITLE
       --------------------------------------------------------------- */
    const titleElement = galleryItem.querySelector('.gallery-overlay h4');

    const caption =
      titleElement
        ? titleElement.innerText.trim()
        : '';


    /* ---------------------------------------------------------------
       VIDEO
       --------------------------------------------------------------- */
    if (youtubeId && youtubeId.trim() !== '') {

      openModal(
        youtubeId.trim(),
        true,
        caption
      );

      return;
    }


    /* ---------------------------------------------------------------
       IMAGE
       --------------------------------------------------------------- */
    const img = galleryItem.querySelector('img');

    if (img) {

      const imgSrc =
        img.getAttribute('src') ||
        img.getAttribute('data-src');

      if (imgSrc) {

        openModal(
          imgSrc,
          false,
          caption || img.getAttribute('alt') || ''
        );

      }

    }

  });


  /* ----------------------------------------------------------------------
     CLOSE BUTTON
     ---------------------------------------------------------------------- */
  if (closeBtn) {

    closeBtn.addEventListener('click', function (e) {

      e.preventDefault();
      e.stopPropagation();

      closeModal();

    });

  }


  /* ----------------------------------------------------------------------
     CLICK OUTSIDE
     ---------------------------------------------------------------------- */
  modal.addEventListener('click', function (e) {

    if (e.target === modal) {
      closeModal();
    }

  });


  /* ----------------------------------------------------------------------
     ESCAPE KEY
     ---------------------------------------------------------------------- */
  document.addEventListener('keydown', function (e) {

    if (
      e.key === 'Escape' &&
      modal.classList.contains('active')
    ) {

      closeModal();

    }

  });

}


/* --------------------------------------------------------------------------
   INIT
   -------------------------------------------------------------------------- */
if (document.readyState === 'loading') {

  document.addEventListener(
    'DOMContentLoaded',
    initLightboxModal
  );

} else {

  initLightboxModal();

}

/* --------------------------------------------------------------------------
   8. FAQ ACCORDION
   -------------------------------------------------------------------------- */
function initFAQAccordion() {
  const faqHeaders = document.querySelectorAll('.faq-header');
  if (!faqHeaders.length) return;

  faqHeaders.forEach(header => {
    header.addEventListener('click', () => {
      const item = header.parentElement;
      const content = item.querySelector('.faq-content');

      document.querySelectorAll('.faq-item').forEach(other => {
        if (other !== item) {
          other.classList.remove('active');
          const otherContent = other.querySelector('.faq-content');
          if (otherContent) otherContent.style.maxHeight = null;
        }
      });

      item.classList.toggle('active');
      if (item.classList.contains('active')) {
        content.style.maxHeight = content.scrollHeight + 'px';
      } else {
        content.style.maxHeight = null;
      }
    });
  });
}

/* --------------------------------------------------------------------------
   9. 3D TILT & MOUSEMOVE LIGHT SWEEP SHEEN EFFECT ON CARDS
   -------------------------------------------------------------------------- */
function initTiltEffect() {
  const tiltCards = document.querySelectorAll('.product-card, .hero-image-card, .founder-card, .gallery-item, .facility-card, .blog-card, .timeline-card-box, .timeline-img-card');

  tiltCards.forEach(card => {
    card.addEventListener('mousemove', (e) => {
      const rect = card.getBoundingClientRect();
      const x = e.clientX - rect.left;
      const y = e.clientY - rect.top;
      const centerX = rect.width / 2;
      const centerY = rect.height / 2;
      const rotateX = ((y - centerY) / centerY) * -4;
      const rotateY = ((x - centerX) / centerX) * 4;

      card.style.transform = `perspective(1000px) rotateX(${rotateX}deg) rotateY(${rotateY}deg) translateY(-4px)`;
    });

    card.addEventListener('mouseleave', () => {
      card.style.transform = `perspective(1000px) rotateX(0deg) rotateY(0deg) translateY(0px)`;
    });
  });
}

/* --------------------------------------------------------------------------
   10. BATCH YIELD & SHELF LIFE CALCULATOR CONTROLLER
   -------------------------------------------------------------------------- */
function initBatchCalculator() {
  const produceSelect = document.getElementById('calcProduceSelect');
  const weightInput = document.getElementById('calcWeightInput');
  const pkgSelect = document.getElementById('calcPkgSelect');

  if (!produceSelect || !weightInput || !pkgSelect) return;

  const events = ['change', 'input', 'keyup'];
  events.forEach(evt => {
    produceSelect.addEventListener(evt, calculateBatchYield);
    weightInput.addEventListener(evt, calculateBatchYield);
    pkgSelect.addEventListener(evt, calculateBatchYield);
  });

  // Calculate initial load
  calculateBatchYield();
}

function setCalcWeightPreset(val) {
  const weightInput = document.getElementById('calcWeightInput');
  if (weightInput) {
    weightInput.value = val;
    calculateBatchYield();

    // Highlight active preset button
    document.querySelectorAll('.calc-preset-btn').forEach(btn => {
      if (parseInt(btn.getAttribute('data-val'), 10) === val) {
        btn.classList.add('active');
      } else {
        btn.classList.remove('active');
      }
    });
  }
}

function calculateBatchYield() {
  const produceSelect = document.getElementById('calcProduceSelect');
  const weightInput = document.getElementById('calcWeightInput');
  const pkgSelect = document.getElementById('calcPkgSelect');

  const outputUnitsEl = document.getElementById('calcOutputUnits');
  const outputShelfEl = document.getElementById('calcOutputShelf');
  const outputBreakdownEl = document.getElementById('calcBreakdownSummary');

  if (!produceSelect || !weightInput || !pkgSelect || !outputUnitsEl || !outputShelfEl) return;

  const produce = produceSelect.value || 'mango';
  let weight = parseFloat(weightInput.value);

  // Safety fallback for empty or invalid weight
  if (isNaN(weight) || weight <= 0) {
    weight = 0;
  }

  const pkg = pkgSelect.value || 'tin';

  let unitWeight = 0.85; // Default 850g tin
  let shelfText = "18 - 24 Mos";
  let unitLabel = "Cans";
  let unitSpecText = "850g Hermetic Can";

  if (pkg === 'tin') {
    unitWeight = 0.85;
    shelfText = "18 - 24 Mos";
    unitLabel = "Cans";
    unitSpecText = "850g Hermetic Can";
  } else if (pkg === 'pouch') {
    unitWeight = 1.0;
    shelfText = "12 - 18 Mos";
    unitLabel = "Pouches";
    unitSpecText = "1kg Retort Pouch";
  } else if (pkg === 'jar') {
    unitWeight = 0.5;
    shelfText = "12 - 15 Mos";
    unitLabel = "Jars";
    unitSpecText = "500g Glass Jar";
  }

  // Pulp extraction yield factors
  let yieldFactor = 0.70; // 70% pulp yield for mango
  let produceName = "Alphonso / Kesar Mango";
  let extractType = "Pure Mango Pulp";

  if (produce === 'mango') {
    yieldFactor = 0.70;
    produceName = "Alphonso / Kesar Mango";
    extractType = "Pure Mango Pulp";
  } else if (produce === 'tomato') {
    yieldFactor = 0.85;
    produceName = "Fresh Red Tomato";
    extractType = "Concentrated Puree";
  } else if (produce === 'amla') {
    yieldFactor = 0.65;
    produceName = "Indian Gooseberry (Amla)";
    extractType = "Cold-Press Juice";
  } else if (produce === 'fruit') {
    yieldFactor = 0.75;
    produceName = "Mixed Orchard Fruits";
    extractType = "Fruit Jam Base";
  }

  const totalPulpKg = weight * yieldFactor;
  const unitsProduced = weight > 0 ? Math.floor(totalPulpKg / unitWeight) : 0;
  const formattedUnits = unitsProduced.toLocaleString('en-US');

  // Update DOM Output
  outputUnitsEl.textContent = `${formattedUnits} ${unitLabel}`;
  outputShelfEl.textContent = shelfText;

  if (outputBreakdownEl) {
    if (weight > 0) {
      outputBreakdownEl.innerHTML = `<i class="fa-solid fa-chart-pie" style="color: var(--accent);"></i> Batch Yield Summary: <strong>${weight} KG</strong> ${produceName} &rarr; <strong>${totalPulpKg.toFixed(1)} KG</strong> ${extractType} (${(yieldFactor * 100).toFixed(0)}% Yield) &rarr; <strong>${formattedUnits} ${unitLabel}</strong> (${unitSpecText})`;
    } else {
      outputBreakdownEl.innerHTML = `<i class="fa-solid fa-info-circle" style="color: var(--accent);"></i> Please enter raw produce weight to calculate net yield and packed units.`;
    }
  }

  // Sync active preset highlight if applicable
  document.querySelectorAll('.calc-preset-btn').forEach(btn => {
    const presetVal = parseInt(btn.getAttribute('data-val'), 10);
    if (presetVal === weight) {
      btn.classList.add('active');
    } else {
      btn.classList.remove('active');
    }
  });
}

/* --------------------------------------------------------------------------
   11. PRODUCT DETAIL THUMBNAIL SWITCHER & ACTIVE BORDER HIGHLIGHTER
   -------------------------------------------------------------------------- */
function changeProductDetailImg(thumbEl) {
  if (!thumbEl) return;
  const mainImg = document.getElementById('mainProductImg');
  if (mainImg) {
    mainImg.src = thumbEl.src;
  }

  const gallery = thumbEl.parentElement;
  if (gallery) {
    const thumbs = gallery.querySelectorAll('img, .product-thumb');
    thumbs.forEach(t => {
      t.classList.remove('active');
      t.style.border = '2px solid var(--border-medium)';
    });
  }

  thumbEl.classList.add('active');
  thumbEl.style.border = '2.5px solid var(--accent)';
}


 function setCalcWeight(val) {
  const weightEl = document.getElementById('calcWeight');
  if (weightEl) {
    weightEl.value = val;
    calculateBatchYield();
  }
}