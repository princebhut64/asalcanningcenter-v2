/* Page-specific scripts */

// Initialize AOS
    AOS.init({
      duration: 800,
      easing: 'ease-out-cubic',
      once: true,
      offset: 60
    });

    // WOWSlider 3D Slicing Transition Engine
    const slidesData = [
      {
        url: 'https://images.unsplash.com/photo-1577962917302-cd874c4e31d2?auto=format&fit=crop&w=1920&q=85',
        title: 'Expert in Canning and Packaging',
        desc: 'Dedicated to hygienic and sustainable food processing solutions.'
      },
      {
        url: 'https://images.unsplash.com/photo-1542838132-92c53300491e?auto=format&fit=crop&w=1920&q=85',
        title: 'Empowering Women Entrepreneurs',
        desc: 'Providing domestic canning guidelines and commercial training.'
      },
      {
        url: 'https://images.unsplash.com/photo-1553279768-865429fa0078?auto=format&fit=crop&w=1920&q=85',
        title: 'Guaranteed Taste & Quality',
        desc: 'FDCA Registered & FSSAI certified standard processes.'
      }
    ];

    let currentSlide = 0;
    let isTransitioning = false;
    const NUM_SLICES = 8;
    const sliceCanvas = document.getElementById('sliceCanvas');
    const slideBg = document.getElementById('slideBg');
    const captionTitle = document.getElementById('captionTitle');
    const captionDesc = document.getElementById('captionDesc');
    const captionBox = document.getElementById('sliderCaption');
    const dotsContainer = document.getElementById('dotsContainer');
    let autoSlideTimer;

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

      captionBox.style.opacity = '0';
      captionBox.style.transform = 'translateX(-50%) translateY(20px)';

      for (let i = 0; i < NUM_SLICES; i++) {
        const col = document.createElement('div');
        col.className = 'slice-col';
        col.style.width = `${sliceWidth}%`;

        const inner = document.createElement('div');
        inner.className = 'slice-col-inner';
        inner.style.backgroundImage = `url('${nextSlideData.url}')`;
        inner.style.width = `${NUM_SLICES * 100}%`;
        inner.style.left = `-${i * 100}%`;

        col.style.opacity = '0';
        col.style.transform = dir === 'next'
          ? `translateY(${i % 2 === 0 ? '-50px' : '50px'}) rotateY(-35deg) scale(0.9)`
          : `translateY(${i % 2 === 0 ? '50px' : '-50px'}) rotateY(35deg) scale(0.9)`;
        col.style.transition = `all 0.75s cubic-bezier(0.22, 1, 0.36, 1) ${i * 0.05}s`;

        col.appendChild(inner);
        sliceCanvas.appendChild(col);

        setTimeout(() => {
          col.style.opacity = '1';
          col.style.transform = 'translateY(0) rotateY(0deg) scale(1)';
        }, 30);
      }

      document.querySelectorAll('.dot-pill').forEach((d, i) => {
        d.classList.toggle('active', i === nextIdx);
      });

      setTimeout(() => {
        slideBg.classList.remove('zoom');
        slideBg.style.backgroundImage = `url('${nextSlideData.url}')`;
        void slideBg.offsetWidth;
        slideBg.classList.add('zoom');

        captionTitle.textContent = nextSlideData.title;
        captionDesc.textContent = nextSlideData.desc;
        captionBox.style.opacity = '1';
        captionBox.style.transform = 'translateX(-50%) translateY(0)';

        sliceCanvas.innerHTML = '';
        currentSlide = nextIdx;
        isTransitioning = false;
      }, 750 + (NUM_SLICES * 50));
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

    // Statistics Counter
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
