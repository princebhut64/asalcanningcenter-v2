/* Page-specific scripts */

// Initialize AOS
    AOS.init({ duration: 700, easing: 'ease-out-cubic', once: true, offset: 40 });

    // ================= WOWSLIDER 3D SLICED ENGINE =================
    const slidesData = [
      {
        url: 'https://images.unsplash.com/photo-1542838132-92c53300491e?auto=format&fit=crop&w=1920&q=85',
        title: 'Modern Production Infrastructure',
        desc: 'Advanced cottage canning, pulp extraction, and sealing technology.'
      },
      {
        url: 'https://images.unsplash.com/photo-1577962917302-cd874c4e31d2?auto=format&fit=crop&w=1920&q=85',
        title: 'Precision Temperature Control',
        desc: 'Sterilized boiling, vacuum processing, and hygienic pouching.'
      },
      {
        url: 'https://images.unsplash.com/photo-1553279768-865429fa0078?auto=format&fit=crop&w=1920&q=85',
        title: 'Pure Farm Fresh Preservation',
        desc: 'Preserving natural colors, taste, and active nutrients without preservatives.'
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

    // ================= GALLERY DATABASE =================
    const galleryItems = [
      // Facility & Machines
      { id: 1, title: "Main Center & Reception", cat: "facility", type: "image", src: "https://images.unsplash.com/photo-1542838132-92c53300491e?auto=format&fit=crop&w=1000&q=80", subtitle: "Paldi Center Entrance" },
      { id: 2, title: "Commercial Canning Pulper", cat: "facility", type: "image", src: "https://images.unsplash.com/photo-1581091226825-a6a2a5aee158?auto=format&fit=crop&w=1000&q=80", subtitle: "Stainless Steel Pulp Extraction Unit" },
      { id: 3, title: "Automatic Tin Sealing Machine", cat: "facility", type: "image", src: "https://images.unsplash.com/photo-1581092160607-ee22621dd758?auto=format&fit=crop&w=1000&q=80", subtitle: "Hermetic Double Seam Sealing" },
      { id: 4, title: "Pouch Packaging Equipment", cat: "facility", type: "image", src: "https://images.unsplash.com/photo-1587293852726-70cdb56c2866?auto=format&fit=crop&w=1000&q=80", subtitle: "Vacuum Sealing Station" },
      
      // Pulping & Processing
      { id: 5, title: "Fresh Mango Batch Pulping", cat: "pulping", type: "image", src: "https://images.unsplash.com/photo-1553279768-865429fa0078?auto=format&fit=crop&w=1000&q=80", subtitle: "Kesar & Alphonso Pulping" },
      { id: 6, title: "Precision Cooking & Boiling", cat: "pulping", type: "image", src: "https://images.unsplash.com/photo-1556910103-1c02745aae4d?auto=format&fit=crop&w=1000&q=80", subtitle: "Temperature Monitored Kettles" },
      { id: 7, title: "Tomato Sauce Simmering", cat: "pulping", type: "image", src: "https://images.unsplash.com/photo-1577962917302-cd874c4e31d2?auto=format&fit=crop&w=1000&q=80", subtitle: "Spiced Sauce Processing" },
      { id: 8, title: "Amla Sorting & Washing", cat: "pulping", type: "image", src: "https://images.unsplash.com/photo-1615485290382-441e4d049cb5?auto=format&fit=crop&w=1000&q=80", subtitle: "Raw Organic Ingredients" },
      { id: 9, title: "Custard Apple (Sitafal) Extraction", cat: "pulping", type: "image", src: "https://images.unsplash.com/photo-1546548970-71785318a17b?auto=format&fit=crop&w=1000&q=80", subtitle: "Gentle Cold Deseeding" },
      
      // Bottling & Packaging
      { id: 10, title: "Glass Bottle Sterilization & Filling", cat: "packaging", type: "image", src: "https://images.unsplash.com/photo-1518843875459-f738682238a6?auto=format&fit=crop&w=1000&q=80", subtitle: "Pure Sauce & Squash Bottling" },
      { id: 11, title: "Hermetically Sealed Tin Inventory", cat: "packaging", type: "image", src: "https://images.unsplash.com/photo-1592924357228-91a4daadcfea?auto=format&fit=crop&w=1000&q=80", subtitle: "Room-Temperature Safe Stock" },
      { id: 12, title: "Frozen Leak-Proof Preserve Bags", cat: "packaging", type: "image", src: "https://images.unsplash.com/photo-1563227812-0ea4c22e6cc8?auto=format&fit=crop&w=1000&q=80", subtitle: "40-Hour Freshness Safe Bags" },
      
      // Training Sessions
      { id: 13, title: "Women Empowerment Workshop", cat: "training", type: "image", src: "https://images.unsplash.com/photo-1573496359142-b8d87734a5a2?auto=format&fit=crop&w=1000&q=80", subtitle: "Cottage Business Mentoring" },
      { id: 14, title: "Home Canning Live Seminar", cat: "training", type: "image", src: "https://images.unsplash.com/photo-1531482615713-2afd69097998?auto=format&fit=crop&w=1000&q=80", subtitle: "Hygiene & Canning Protocols" },
      
      // Videos
      { id: 15, title: "Mango Pulp Extraction Process", cat: "videos", type: "video", src: "https://images.unsplash.com/photo-1553279768-865429fa0078?auto=format&fit=crop&w=1000&q=80", subtitle: "Watch Machine in Action" },
      { id: 16, title: "Tomato Sauce Boiling Demo", cat: "videos", type: "video", src: "https://images.unsplash.com/photo-1577962917302-cd874c4e31d2?auto=format&fit=crop&w=1000&q=80", subtitle: "Authentic Formulation Video" },
      { id: 17, title: "Tin Canning Double Seam Demo", cat: "videos", type: "video", src: "https://images.unsplash.com/photo-1542838132-92c53300491e?auto=format&fit=crop&w=1000&q=80", subtitle: "Industrial Sealer Tour" },
      { id: 18, title: "Women Training Session Highlight", cat: "videos", type: "video", src: "https://images.unsplash.com/photo-1573496359142-b8d87734a5a2?auto=format&fit=crop&w=1000&q=80", subtitle: "Facilitator Address & Guidance" }
    ];

    let activeList = [...galleryItems];
    let currentLightboxIdx = 0;
    const galleryGrid = document.getElementById('galleryGrid');

    function renderGallery(items) {
      galleryGrid.innerHTML = '';
      items.forEach((item, index) => {
        const card = document.createElement('div');
        card.className = 'gallery-item-card';
        card.setAttribute('role', 'button');
        card.setAttribute('tabindex', '0');
        card.setAttribute('aria-label', `Open ${item.title}`);
        card.setAttribute('data-aos', 'fade-up');
        card.setAttribute('data-aos-delay', (index % 4) * 80);
        card.addEventListener('click', () => openGalleryLightbox(index));
        card.addEventListener('keydown', event => {
          if (event.key === 'Enter' || event.key === ' ') { event.preventDefault(); openGalleryLightbox(index); }
        });

        card.innerHTML = `
          <div class="gallery-thumb">
            <img src="${item.src}" alt="${item.title}" loading="lazy" />
            ${item.type === 'video' ? `
              <div class="video-badge-overlay">
                <div class="video-play-icon"><i class="fa-solid fa-play"></i></div>
              </div>` : ''}
          </div>
          <div class="gallery-caption-box">
            <h4>${item.title}</h4>
            <span>${item.subtitle}</span>
          </div>
        `;
        galleryGrid.appendChild(card);
      });
    }

    // Filter Logic
    const filterBtns = document.querySelectorAll('.filter-btn');
    filterBtns.forEach(btn => {
      btn.addEventListener('click', () => {
        filterBtns.forEach(b => b.classList.remove('active'));
        btn.classList.add('active');

        const filter = btn.getAttribute('data-filter');
        if (filter === 'all') {
          activeList = [...galleryItems];
        } else {
          activeList = galleryItems.filter(item => item.cat === filter);
        }
        renderGallery(activeList);
      });
    });

    // ================= LIGHTBOX SLIDER CONTROLS =================
    const lightboxModal = document.getElementById('galleryLightbox');
    const lightboxMediaHolder = document.getElementById('lightboxMediaHolder');
    const lightboxTitle = document.getElementById('lightboxTitle');
    const lightboxIndex = document.getElementById('lightboxIndex');

    function openGalleryLightbox(index) {
      currentLightboxIdx = index;
      updateLightboxContent();
      lightboxModal.classList.add('active');
      lightboxModal.setAttribute('aria-hidden', 'false');
      lightboxModal.querySelector('.lightbox-close-btn')?.focus();
    }

    function updateLightboxContent() {
      const item = activeList[currentLightboxIdx];
      if (!item) return;

      lightboxTitle.textContent = item.title;
      lightboxIndex.textContent = `${currentLightboxIdx + 1} / ${activeList.length}`;

      if (item.type === 'image') {
        lightboxMediaHolder.innerHTML = `<img src="${item.src}" alt="${item.title}" />`;
      } else {
        // Video Preview Mock or Embedded Player
        lightboxMediaHolder.innerHTML = `
          <div style="text-align:center; color:#fff; padding:20px;">
            <i class="fa-brands fa-youtube" style="font-size:4rem; color:var(--primary); margin-bottom:12px;"></i>
            <h4 style="font-size:1.2rem; margin-bottom:6px;">${item.title}</h4>
            <p style="font-size:0.9rem; color:#94a3b8;">High-Definition Demonstration Video</p>
          </div>
        `;
      }
    }

    function navigateLightbox(dir) {
      currentLightboxIdx += dir;
      if (currentLightboxIdx < 0) currentLightboxIdx = activeList.length - 1;
      if (currentLightboxIdx >= activeList.length) currentLightboxIdx = 0;
      updateLightboxContent();
    }

    function closeGalleryLightbox() {
      lightboxModal.classList.remove('active');
      lightboxModal.setAttribute('aria-hidden', 'true');
    }

    lightboxModal.addEventListener('click', (e) => {
      if (e.target === lightboxModal) closeGalleryLightbox();
    });

    document.addEventListener('keydown', (e) => {
      if (!lightboxModal.classList.contains('active')) return;
      if (e.key === 'Escape') closeGalleryLightbox();
      if (e.key === 'ArrowLeft') navigateLightbox(-1);
      if (e.key === 'ArrowRight') navigateLightbox(1);
    });

    // Initial render
    renderGallery(activeList);
