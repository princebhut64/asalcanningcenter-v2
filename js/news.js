/* Page-specific scripts */

// Initialize AOS
    AOS.init({ duration: 700, easing: 'ease-out-cubic', once: true, offset: 40 });

    // ================= WOWSLIDER 3D SLICED ENGINE =================
    const slidesData = [
      {
        url: 'https://images.unsplash.com/photo-1542838132-92c53300491e?auto=format&fit=crop&w=1920&q=85',
        title: 'Featured Across Leading Publications',
        desc: 'Documenting the empowerment and growth of cottage preservation in Gujarat.'
      },
      {
        url: 'https://images.unsplash.com/photo-1577962917302-cd874c4e31d2?auto=format&fit=crop&w=1920&q=85',
        title: 'GCCI Business Performance Award 2007',
        desc: 'State recognition by Gujarat Chamber of Commerce & Industry.'
      },
      {
        url: 'https://images.unsplash.com/photo-1553279768-865429fa0078?auto=format&fit=crop&w=1920&q=85',
        title: 'Over 2 Decades of Public Trust',
        desc: 'Inspiring hundreds of women towards self-reliant home entrepreneurship.'
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
    function startSliderTimer() { autoSlideTimer = setInterval(nextSliceSlide, 5000); }
    function resetSliderTimer() { clearInterval(autoSlideTimer); startSliderTimer(); }

    setInitialSlide();
    startSliderTimer();

    // ================= MEDIA ARCHIVE DATABASE =================
    const mediaItems = [
      { id: 1, title: "Umiya Parivar Special Feature", cat: "magazine", date: "June 2003", img: "https://images.unsplash.com/photo-1585829365295-ab7cd400c167?auto=format&fit=crop&w=800&q=80" },
      { id: 2, title: "GCCI Award Ceremony Felicitations", cat: "awards", date: "2006-2007", img: "https://images.unsplash.com/photo-1578575437130-527eed3abbec?auto=format&fit=crop&w=800&q=80" },
      { id: 3, title: "Divya Bhaskar - Woman Power", cat: "newspaper", date: "Editorial", img: "https://images.unsplash.com/photo-1504711434969-e33886168f5c?auto=format&fit=crop&w=800&q=80" },
      { id: 4, title: "Gujarat Samachar - Stri Shakti", cat: "newspaper", date: "Daily Press", img: "https://images.unsplash.com/photo-1586339949916-3e9457bef6d3?auto=format&fit=crop&w=800&q=80" },
      { id: 5, title: "Times of India - Business of Empowerment", cat: "newspaper", date: "National Daily", img: "https://images.unsplash.com/photo-1566378246598-5b11a0d486cc?auto=format&fit=crop&w=800&q=80" },
      { id: 6, title: "Social Entrepreneur Recognition", cat: "magazine", date: "Feature Article", img: "https://images.unsplash.com/photo-1585829365295-ab7cd400c167?auto=format&fit=crop&w=800&q=80" },
      { id: 7, title: "SME Development Board Award", cat: "awards", date: "Honor Certificate", img: "https://images.unsplash.com/photo-1578575437130-527eed3abbec?auto=format&fit=crop&w=800&q=80" },
      { id: 8, title: "Gujarat Chamber of Commerce Certificate", cat: "awards", date: "2007 Award", img: "https://images.unsplash.com/photo-1578575437130-527eed3abbec?auto=format&fit=crop&w=800&q=80" },
      { id: 9, title: "Sandesh - Cottage Industry Milestones", cat: "newspaper", date: "State Press", img: "https://images.unsplash.com/photo-1586339949916-3e9457bef6d3?auto=format&fit=crop&w=800&q=80" },
      { id: 10, title: "Express News - Women Venture Beyond", cat: "newspaper", date: "Feature", img: "https://images.unsplash.com/photo-1504711434969-e33886168f5c?auto=format&fit=crop&w=800&q=80" },
      { id: 11, title: "Women No. 1 Magazine Cover", cat: "magazine", date: "Special Edition", img: "https://images.unsplash.com/photo-1585829365295-ab7cd400c167?auto=format&fit=crop&w=800&q=80" },
      { id: 12, title: "Certificate of Appreciation - Training", cat: "awards", date: "CED Recognition", img: "https://images.unsplash.com/photo-1578575437130-527eed3abbec?auto=format&fit=crop&w=800&q=80" }
    ];

    const pressGrid = document.getElementById('pressGrid');

    function renderPressCards(items) {
      pressGrid.innerHTML = '';
      items.forEach((item, index) => {
        const card = document.createElement('div');
        card.className = 'press-card';
        card.setAttribute('role', 'button');
        card.setAttribute('tabindex', '0');
        card.setAttribute('aria-label', `Open ${item.title}`);
        card.setAttribute('data-aos', 'fade-up');
        card.setAttribute('data-aos-delay', (index % 4) * 100);
        card.addEventListener('click', () => openLightbox(item.title, item.img));
        card.addEventListener('keydown', event => {
          if (event.key === 'Enter' || event.key === ' ') { event.preventDefault(); openLightbox(item.title, item.img); }
        });

        card.innerHTML = `
          <div class="press-thumb">
            <img src="${item.img}" alt="${item.title}" loading="lazy" />
            <div class="zoom-overlay">
              <i class="fa-solid fa-magnifying-glass-plus"></i>
            </div>
          </div>
          <div class="press-footer">
            <h4>${item.title}</h4>
            <span>${item.date}</span>
          </div>
        `;
        pressGrid.appendChild(card);
      });
    }

    // Filter Navigation
    const filterBtns = document.querySelectorAll('.filter-btn');
    filterBtns.forEach(btn => {
      btn.addEventListener('click', () => {
        filterBtns.forEach(b => b.classList.remove('active'));
        btn.classList.add('active');

        const filter = btn.getAttribute('data-filter');
        if (filter === 'all') {
          renderPressCards(mediaItems);
        } else {
          const filtered = mediaItems.filter(i => i.cat === filter);
          renderPressCards(filtered);
        }
      });
    });

    // Lightbox Controls
    const docLightbox = document.getElementById('docLightbox');
    const modalDocTitle = document.getElementById('modalDocTitle');
    const modalDocImg = document.getElementById('modalDocImg');

    function openLightbox(title, imgSrc) {
      modalDocTitle.textContent = title;
      modalDocImg.src = imgSrc;
      docLightbox.classList.add('active');
      docLightbox.setAttribute('aria-hidden', 'false');
    }

    function closeLightbox() {
      docLightbox.classList.remove('active');
      docLightbox.setAttribute('aria-hidden', 'true');
    }

    docLightbox.addEventListener('click', (e) => {
      if (e.target === docLightbox) closeLightbox();
    });

    document.addEventListener('keydown', (e) => {
      if (e.key === 'Escape') closeLightbox();
    });

    // Initial render
    renderPressCards(mediaItems);
