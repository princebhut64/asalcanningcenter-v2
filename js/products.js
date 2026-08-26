/* Page-specific scripts */

// Product Database with Structured Categories & Sub-items
    const productsData = [
      // Pulps
      {
        id: "sitafal-pulp",
        mainCategory: "Pulp",
        subCategory: "Sitafal Pulp",
        title: "Sitafal Pulp (Custard Apple)",
        badge: "Pulp > Seasonal",
        img: "https://images.unsplash.com/photo-1615485290382-441e4d049cb5?auto=format&fit=crop&w=600&q=80",
        desc: "Fresh extraction of authentic creamy Sitafal (Custard Apple) pulp. Processed cleanly without seed breakage, preserving natural aroma for ice creams, shakes, and desserts.",
        pkg: "Food-grade Sealed Pouches & Tins",
        storage: "Deep freeze or specialized 40-hour leak-proof preserve bags"
      },
      {
        id: "jamun-pulp",
        mainCategory: "Pulp",
        subCategory: "Jamun Pulp",
        title: "Jamun Pulp",
        badge: "Pulp > Seasonal",
        img: "https://images.unsplash.com/photo-1546548970-71785318a17b?auto=format&fit=crop&w=600&q=80",
        desc: "Pure nutrient-rich Jamun pulp processed under strict cottage standards. Known for diabetic-friendly natural benefits and intense natural purple color.",
        pkg: "500g, 1kg Pouches / Commercial Tins",
        storage: "Keep in deep freezer for year-round utility"
      },
      {
        id: "falsa-pulp",
        mainCategory: "Pulp",
        subCategory: "Falsa Pulp",
        title: "Falsa Pulp",
        badge: "Pulp > Seasonal",
        img: "https://images.unsplash.com/photo-1553279768-865429fa0078?auto=format&fit=crop&w=600&q=80",
        desc: "Tart, sweet, and cooling Falsa fruit extract processed during peak seasonal harvest. Retains all natural vitamins and tang for refreshing juices.",
        pkg: "Vacuum Sealed Pouches",
        storage: "Store frozen; no added artificial flavors"
      },
      {
        id: "mango-pulp-tin",
        mainCategory: "Pulp",
        subCategory: "Mango Pulp",
        title: "Mango Pulp (Tin & Pouch)",
        badge: "Pulp > Year Round",
        img: "https://images.unsplash.com/photo-1592924357228-91a4daadcfea?auto=format&fit=crop&w=600&q=80",
        desc: "Signature Mango Pulp preservation. Available in ready long-shelf-life tin cans (stays fresh without freezing) and batch pouch extractions for bulk yields.",
        pkg: "Commercial Tins & 1kg/5kg Pouches",
        storage: "Tins stay at room temperature; Pouches in deep freeze"
      },

      // Sauces & Gravies
      {
        id: "tomato-sauce",
        mainCategory: "Sauces & Gravies",
        subCategory: "Tomato Sauce",
        title: "Authentic Tomato Sauce",
        badge: "Sauces",
        img: "https://images.unsplash.com/photo-1577962917302-cd874c4e31d2?auto=format&fit=crop&w=600&q=80",
        desc: "Traditional rich tomato puree seasoned with cinnamon and cloves. Crafted from ripe tomatoes with guaranteed home-style thickness and taste.",
        pkg: "Glass Bottles / Sterilized Jars",
        storage: "Refrigerate after opening"
      },
      {
        id: "pizza-gravy",
        mainCategory: "Sauces & Gravies",
        subCategory: "Italian Pizza Gravy",
        title: "Italian Pizza & Pasta Gravy",
        badge: "Gravies",
        img: "https://images.unsplash.com/photo-1518843875459-f738682238a6?auto=format&fit=crop&w=600&q=80",
        desc: "Concentrated Italian-style tomato gravy infused with Mediterranean herbs and spices for fast food kitchens and home Italian dining.",
        pkg: "Airtight Glass/Plastic Containers",
        storage: "Store in cool & dry place"
      },

      // Jams, Squashes & Pickles
      {
        id: "mixed-fruit-jam",
        mainCategory: "Jams & Pickles",
        subCategory: "Fruit Jam",
        title: "Mixed Fruit Jam",
        badge: "Jams",
        img: "https://images.unsplash.com/photo-1619546813926-a78fa6372cd2?auto=format&fit=crop&w=600&q=80",
        desc: "Prepared with real peach, apple, pineapple, papaya, plum, and strawberry. Ideal spread balancing authentic fruit sweetness.",
        pkg: "Glass Jars (500g, 1kg)",
        storage: "Cool dry place"
      },
      {
        id: "fruit-squash",
        mainCategory: "Jams & Pickles",
        subCategory: "Fruit Squash",
        title: "Fruit Squash & Concentrates",
        badge: "Squash",
        img: "https://images.unsplash.com/photo-1518843875459-f738682238a6?auto=format&fit=crop&w=600&q=80",
        desc: "Refreshing concentrates in Pineapple, Variyali-Sakar, Khus, and Kachi Keri. Simply dilute with water or soda for fresh fruit drinks.",
        pkg: "Bottles with easy-pour cap",
        storage: "Room temperature"
      },
      {
        id: "readymade-pickles",
        mainCategory: "Jams & Pickles",
        subCategory: "Pickles",
        title: "Gol & Khati Mango Pickles",
        badge: "Pickles",
        img: "https://images.unsplash.com/photo-1589927986089-35812388d1f4?auto=format&fit=crop&w=600&q=80",
        desc: "Gujarati-style cured mango pickles, chana-methi, sweet Chhunda, and Punjabi red chilli preserves made with pure mustard oil and spices.",
        pkg: "Hygienic Ceramic & PET Jars",
        storage: "Room temperature away from moisture"
      },

      // Amla & Health
      {
        id: "amla-range",
        mainCategory: "Amla & Herbal Care",
        subCategory: "Amla Products",
        title: "Chyawanprash & Amla Murabba",
        badge: "Ayurvedic",
        img: "https://images.unsplash.com/photo-1615485290382-441e4d049cb5?auto=format&fit=crop&w=600&q=80",
        desc: "Immunity revitalizers made with whole wild gooseberries, natural honey, and rejuvenating herbs to maintain high natural Vitamin C content.",
        pkg: "Sealed Jars",
        storage: "Store in cool environment"
      },
      {
        id: "hair-oil",
        mainCategory: "Amla & Herbal Care",
        subCategory: "Herbal Hair Oil",
        title: "18 Herbs Medicinal Hair Oil",
        badge: "Hair Care",
        img: "https://images.unsplash.com/photo-1577962917302-cd874c4e31d2?auto=format&fit=crop&w=600&q=80",
        desc: "Herbal nourishment with Bhringraj, Brahmi, Jatamansi, Shikakai, and Amla boiled into pure carrier oils to prevent hair fall and dandruff.",
        pkg: "200ml, 500ml Dispenser Bottles",
        storage: "Room temperature"
      }
    ];

    const productsGrid = document.getElementById('productsGrid');

    // Render Product Cards
    function renderProducts(items) {
      productsGrid.innerHTML = '';
      if(items.length === 0) {
        productsGrid.innerHTML = `<p style="grid-column: 1/-1; text-align: center; padding: 40px; color: #94a3b8;">No products found in this category.</p>`;
        return;
      }

      items.forEach(p => {
        const card = document.createElement('div');
        card.className = 'product-box';
        card.setAttribute('role', 'button');
        card.setAttribute('tabindex', '0');
        card.setAttribute('aria-label', `View details for ${p.title}`);
        card.addEventListener('click', () => openProductModal(p.id));
        card.addEventListener('keydown', event => {
          if (event.key === 'Enter' || event.key === ' ') { event.preventDefault(); openProductModal(p.id); }
        });
        card.innerHTML = `
          <div class="img-holder">
            <span class="item-tag">${p.badge}</span>
            <img src="${p.img}" alt="${p.title}" />
          </div>
          <div class="box-info">
            <h4>${p.title}</h4>
            <p>${p.desc.substring(0, 85)}...</p>
            <div class="view-btn"><i class="fa-solid fa-eye"></i> View Full Details</div>
          </div>
        `;
        productsGrid.appendChild(card);
      });
    }

    // Toggle Sub-menus in Sidebar
    function toggleSubMenu(menuId, btn) {
      const sub = document.getElementById(menuId);
      sub.classList.toggle('open');
      btn.classList.toggle('active');
    }

    // Category / Sub-Category Filter
    function filterCategory(catKey, el) {
      document.querySelectorAll('.sub-cat-link').forEach(l => l.classList.remove('active'));
      document.querySelectorAll('.cat-toggle-btn').forEach(b => b.classList.remove('active'));
      
      el.classList.add('active');
      document.getElementById('currentCategoryHeading').textContent = catKey === 'all' ? 'All Products' : catKey;

      if(catKey === 'all') {
        renderProducts(productsData);
        document.getElementById('productCountText').textContent = `Showing all ${productsData.length} items`;
      } else {
        const filtered = productsData.filter(p => p.subCategory === catKey || p.mainCategory === catKey);
        renderProducts(filtered);
        document.getElementById('productCountText').textContent = `Showing ${filtered.length} item(s) in "${catKey}"`;
      }
    }

    // Single Product Modal Controls
    function openProductModal(productId) {
      const p = productsData.find(item => item.id === productId);
      if(!p) return;

      document.getElementById('mImg').src = p.img;
      document.getElementById('mBadge').textContent = p.badge;
      document.getElementById('mTitle').textContent = p.title;
      document.getElementById('mDesc').textContent = p.desc;
      document.getElementById('mPkg').textContent = p.pkg;
      document.getElementById('mStorage').textContent = p.storage;

      const modal = document.getElementById('productModal');
      modal.classList.add('active');
      modal.setAttribute('aria-hidden', 'false');
      document.querySelector('.modal-close-btn')?.focus();
    }

    function closeProductModal() {
      const modal = document.getElementById('productModal');
      modal.classList.remove('active');
      modal.setAttribute('aria-hidden', 'true');
    }

    // Close on background click
    document.getElementById('productModal').addEventListener('click', (e) => {
      if(e.target === document.getElementById('productModal')) closeProductModal();
    });
    document.addEventListener('keydown', event => {
      if (event.key === 'Escape') closeProductModal();
    });

    // Initial load
    renderProducts(productsData);
