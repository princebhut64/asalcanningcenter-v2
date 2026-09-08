/* Page-specific scripts */

// Initialize AOS
    AOS.init({
      duration: 800,
      easing: 'ease-out-cubic',
      once: true,
      offset: 60
    });

    // ================= WOWSLIDER SLICE 3D ENGINE =================
    const slidesData = [
      {
        url: 'https://images.unsplash.com/photo-1542838132-92c53300491e?auto=format&fit=crop&w=1920&q=85',
        title: 'Modern Canning Facility',
        desc: 'Industry standard food preservation and hygiene protocols.'
      },
      {
        url: 'https://images.unsplash.com/photo-1553279768-865429fa0078?auto=format&fit=crop&w=1920&q=85',
        title: 'Fresh Mango Pulping',
        desc: 'Retaining authentic seasonal aroma and long shelf life.'
      },
      {
        url: 'https://images.unsplash.com/photo-1577962917302-cd874c4e31d2?auto=format&fit=crop&w=1920&q=85',
        title: 'Precision Processing',
        desc: 'Advanced equipment for cottage & commercial batch yields.'
      },
      {
        url: 'https://images.unsplash.com/photo-1592924357228-91a4daadcfea?auto=format&fit=crop&w=1920&q=85',
        title: 'Farm Fresh Harvesting',
        desc: 'From farm produce to pure processed bottles and cans.'
      }
    ];

    let currentSlide = 0;
    let isTransitioning = false;
    const NUM_SLICES = 6;
    const sliceCanvas = document.getElementById('sliceCanvas');
    const slideBg = document.getElementById('slideBg');
    const captionTitle = document.getElementById('captionTitle');
    const captionDesc = document.getElementById('captionDesc');
    const captionBox = document.getElementById('sliderCaption');
    const dotsContainer = document.getElementById('dotsContainer');
    let autoSlideTimer;

    // Render Dots
    slidesData.forEach((_, idx) => {
      const dot = document.createElement('span');
      dot.className = `dot-pill ${idx === 0 ? 'active' : ''}`;
      dot.addEventListener('click', () => goToSliceSlide(idx));
      dotsContainer.appendChild(dot);
    });

    function setInitialSlide() {
      slideBg.style.backgroundImage = `url('${slidesData[0].url}')`;
      slideBg.classList.add('zoom');
      captionTitle.textContent = slidesData[0].title;
      captionDesc.textContent = slidesData[0].desc;
    }

    function triggerSliceTransition(nextIdx, dir = 'next') {
      if (isTransitioning || nextIdx === currentSlide) return;
      isTransitioning = true;

      const nextSlideData = slidesData[nextIdx];
      sliceCanvas.innerHTML = '';
      const sliceWidth = 100 / NUM_SLICES;

      // Animate caption out
      captionBox.classList.add('is-changing');
      captionBox.style.opacity = '0';
      captionBox.style.transform = 'translateY(20px)';

      for (let i = 0; i < NUM_SLICES; i++) {
        const col = document.createElement('div');
        col.className = 'slice-col';
        col.style.width = `${sliceWidth}%`;

        const inner = document.createElement('div');
        inner.className = 'slice-col-inner';
        inner.style.backgroundImage = `url('${nextSlideData.url}')`;
        inner.style.width = `${NUM_SLICES * 100}%`;
        inner.style.left = `-${i * 100}%`;

        // 3D WOWSlider style start positions
        col.style.opacity = '0';
        col.style.transform = dir === 'next'
          ? `translateX(${i % 2 === 0 ? '-90px' : '90px'}) rotateY(${i % 2 === 0 ? '-18deg' : '18deg'}) scale(1.04)`
          : `translateX(${i % 2 === 0 ? '90px' : '-90px'}) rotateY(${i % 2 === 0 ? '18deg' : '-18deg'}) scale(1.04)`;
        col.style.transition = `all 0.9s cubic-bezier(0.16, 1, 0.3, 1) ${i * 0.07}s`;

        col.appendChild(inner);
        sliceCanvas.appendChild(col);

        // Force repaint & run transition
        setTimeout(() => {
          col.style.opacity = '1';
          col.style.transform = 'translateY(0) rotateY(0deg) scale(1)';
        }, 30);
      }

      // Update dots
      document.querySelectorAll('.dot-pill').forEach((d, i) => {
        d.classList.toggle('active', i === nextIdx);
      });

      // Complete transition
      setTimeout(() => {
        slideBg.classList.remove('zoom');
        slideBg.style.backgroundImage = `url('${nextSlideData.url}')`;
        void slideBg.offsetWidth; // Reflow for smooth zoom
        slideBg.classList.add('zoom');

        captionTitle.textContent = nextSlideData.title;
        captionDesc.textContent = nextSlideData.desc;
        captionBox.style.opacity = '1';
        captionBox.style.transform = 'translateY(0)';

        sliceCanvas.innerHTML = '';
        currentSlide = nextIdx;
        isTransitioning = false;
        captionBox.classList.remove('is-changing');
      }, 900 + (NUM_SLICES * 70));
    }

    function nextSliceSlide() {
      const next = (currentSlide + 1) % slidesData.length;
      triggerSliceTransition(next, 'next');
      resetSliderTimer();
    }

    function prevSliceSlide() {
      const prev = (currentSlide - 1 + slidesData.length) % slidesData.length;
      triggerSliceTransition(prev, 'prev');
      resetSliderTimer();
    }

    function goToSliceSlide(idx) {
      if (idx === currentSlide) return;
      const dir = idx > currentSlide ? 'next' : 'prev';
      triggerSliceTransition(idx, dir);
      resetSliderTimer();
    }

    function startSliderTimer() {
      autoSlideTimer = setInterval(nextSliceSlide, 5000);
    }

    function resetSliderTimer() {
      clearInterval(autoSlideTimer);
      startSliderTimer();
    }

    setInitialSlide();
    startSliderTimer();

    // ================= COUNTER ANIMATION =================
    const counters = document.querySelectorAll('.counter');
    let countersRun = false;

    function runCounters() {
      counters.forEach(counter => {
        const target = +counter.getAttribute('data-target');
        const duration = 1500;
        const step = target / (duration / 25);
        let count = 0;

        const update = () => {
          count += step;
          if (count < target) {
            counter.innerText = Math.ceil(count);
            setTimeout(update, 25);
          } else {
            counter.innerText = target;
          }
        };
        update();
      });
    }

    window.addEventListener('scroll', () => {
      const statsSection = document.querySelector('.stats-bar-section');
      if (statsSection) {
        const rect = statsSection.getBoundingClientRect();
        if (rect.top < window.innerHeight && !countersRun) {
          runCounters();
          countersRun = true;
        }
      }
    });

    // ================= LIGHTBOX INTERACTION =================
    const modal = document.getElementById('lightboxModal');
    const modalImg = document.getElementById('lightboxImg');
    const modalClose = document.getElementById('lightboxClose');

    document.querySelectorAll('.lightbox-trigger').forEach(item => {
      item.addEventListener('click', (e) => {
        const targetImg = item.tagName === 'IMG' ? item : item.querySelector('img');
        if (targetImg) {
          modalImg.src = targetImg.src;
          modal.classList.add('open');
        }
      });
    });

    modalClose.addEventListener('click', () => modal.classList.remove('open'));
    modal.addEventListener('click', (e) => {
      if (e.target === modal) modal.classList.remove('open');
    });
