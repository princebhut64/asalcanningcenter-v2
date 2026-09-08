/* News & Media Archive JavaScript */

document.addEventListener('DOMContentLoaded', () => {
  // ================= MEDIA ARCHIVE DATABASE =================
  const mediaItems = [
    { id: 1, title: "Umiya Parivar Special Feature", cat: "magazine", date: "June 2003", img: "https://images.unsplash.com/photo-1524995997946-a1c2e315a42f?auto=format&fit=crop&w=800&q=80" },
    { id: 2, title: "GCCI Award Ceremony Felicitations", cat: "awards", date: "2006-2007", img: "https://images.unsplash.com/photo-1567521464027-f127ff144326?auto=format&fit=crop&w=800&q=80" },
    { id: 3, title: "Divya Bhaskar - Woman Power", cat: "newspaper", date: "Editorial", img: "https://images.unsplash.com/photo-1588681664899-f142ff2dc9b1?auto=format&fit=crop&w=800&q=80" },
    { id: 4, title: "Gujarat Samachar - Stri Shakti", cat: "newspaper", date: "Daily Press", img: "https://images.unsplash.com/photo-1495020689067-958852a7765e?auto=format&fit=crop&w=800&q=80" },
    { id: 5, title: "Times of India - Business of Empowerment", cat: "newspaper", date: "National Daily", img: "https://images.unsplash.com/photo-1504711434969-e33886168f5c?auto=format&fit=crop&w=800&q=80" },
    { id: 6, title: "Social Entrepreneur Recognition", cat: "magazine", date: "Feature Article", img: "https://images.unsplash.com/photo-1457369804613-52c61a468e7d?auto=format&fit=crop&w=800&q=80" },
    { id: 7, title: "SME Development Board Award", cat: "awards", date: "Honor Certificate", img: "https://images.unsplash.com/photo-1551836022-d5d88e9218df?auto=format&fit=crop&w=800&q=80" },
    { id: 8, title: "Gujarat Chamber of Commerce Certificate", cat: "awards", date: "2007 Award", img: "https://images.unsplash.com/photo-1559526324-c1f275fbfa32?auto=format&fit=crop&w=800&q=80" },
    { id: 9, title: "Sandesh - Cottage Industry Milestones", cat: "newspaper", date: "State Press", img: "https://images.unsplash.com/photo-1585829365295-ab7cd400c167?auto=format&fit=crop&w=800&q=80" },
    { id: 10, title: "Express News - Women Venture Beyond", cat: "newspaper", date: "Feature", img: "https://images.unsplash.com/photo-1586339949916-3e9457bef6d3?auto=format&fit=crop&w=800&q=80" },
    { id: 11, title: "Women No. 1 Magazine Cover", cat: "magazine", date: "Special Edition", img: "https://images.unsplash.com/photo-1512820790803-83ca734da794?auto=format&fit=crop&w=800&q=80" },
    { id: 12, title: "Certificate of Appreciation - Training", cat: "awards", date: "CED Recognition", img: "https://images.unsplash.com/photo-1606326608606-aa0b62935f2b?auto=format&fit=crop&w=800&q=80" }
  ];

  const pressGrid = document.getElementById('pressGrid');
  const docLightbox = document.getElementById('docLightbox');
  const modalDocTitle = document.getElementById('modalDocTitle');
  const modalDocImg = document.getElementById('modalDocImg');

  function renderPressCards(items) {
    if (!pressGrid) return;
    pressGrid.innerHTML = '';
    items.forEach((item, index) => {
      const card = document.createElement('div');
      card.className = 'press-card light-sheen-card';
      card.setAttribute('role', 'button');
      card.setAttribute('tabindex', '0');
      card.setAttribute('aria-label', `Open ${item.title}`);
      card.addEventListener('click', () => openLightbox(item.title, item.img));
      card.addEventListener('keydown', event => {
        if (event.key === 'Enter' || event.key === ' ') {
          event.preventDefault();
          openLightbox(item.title, item.img);
        }
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
  function openLightbox(title, imgSrc) {
    if (!docLightbox || !modalDocTitle || !modalDocImg) return;
    modalDocTitle.textContent = title;
    modalDocImg.src = imgSrc;
    docLightbox.classList.add('active');
    docLightbox.setAttribute('aria-hidden', 'false');
  }

  function closeLightbox() {
    if (!docLightbox) return;
    docLightbox.classList.remove('active');
    docLightbox.setAttribute('aria-hidden', 'true');
  }

  window.closeLightbox = closeLightbox;

  if (docLightbox) {
    docLightbox.addEventListener('click', (e) => {
      if (e.target === docLightbox) closeLightbox();
    });
  }

  document.addEventListener('keydown', (e) => {
    if (e.key === 'Escape') closeLightbox();
  });

  // Initial render
  renderPressCards(mediaItems);
});

