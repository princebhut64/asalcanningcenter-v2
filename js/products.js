/* ==========================================================================
   ASAL CANNING CENTER - Products Database & Interactive Catalog Controller
   Supports 11 Flagship Formulations across Categories with Search & Quick View
   ========================================================================== */

(function () {
  // 11 Flagship Products Database with Custom Local Photos & Packaging Titles
  const productsData = [
    // 1. Pulp Category
    {
      id: "sitafal-pulp",
      mainCategory: "Pulp",
      subCategory: "Sitafal Pulp",
      title: "Sitafal Pulp (Custard Apple 500g Pouch / Jar)",
      badge: "FSSAI Certified",
      img: "images/products/sitafal-pulp.jpg",
      desc: "100% natural Sitafal (Custard Apple) pulp extracted under hygienic cold process. Free from added colors or artificial flavors. Ideal for ice creams, rabdi, & basundi.",
      pkg: "500g Stand-up Pouch / Glass Jar",
      spec: "500g Sealed Pouch / Jar",
      storage: "Keep frozen at -18°C"
    },
    {
      id: "jamun-pulp",
      mainCategory: "Pulp",
      subCategory: "Jamun Pulp",
      title: "Jamun Pulp (500g Stand-up Pouch)",
      badge: "Health Special",
      img: "images/products/jamun-pulp.jpg",
      desc: "Pure black plum (Jamun) pulp extracted from wild harvested berries. Naturally rich in antioxidants and low glycemic index for health applications.",
      pkg: "500g Stand-up Pouch",
      spec: "500g Stand-up Pouch",
      storage: "Keep frozen at -18°C"
    },
    {
      id: "falsa-pulp",
      mainCategory: "Pulp",
      subCategory: "Falsa Pulp",
      title: "Falsa Fruit Pulp (500g Sealed Pouch)",
      badge: "Seasonal Puree",
      img: "images/products/falsa-pulp.jpg",
      desc: "Authentic Falsa (Grewia asiatica) berry pulp with refreshing tart-sweet flavor profile for cooling summer sherbets and beverage bases.",
      pkg: "500g Sealed Pouch",
      spec: "500g Sealed Pouch",
      storage: "Keep frozen at -18°C"
    },
    {
      id: "mango-pulp",
      mainCategory: "Pulp",
      subCategory: "Mango Pulp",
      title: "Alphonso & Kesar Mango Pulp (850g Hermetic Can / 1kg Pouch)",
      badge: "Bestseller",
      img: "images/products/mango-pulp.jpg",
      desc: "Rich, aromatic mango pulp extracted from prime Ratnagiri Alphonso and Junagadh Kesar mangoes. Processed and vacuum sealed in hermetic cans & pouches.",
      pkg: "850g Hermetic Can / 1kg Pouch",
      spec: "850g Can / 1kg Pouch",
      storage: "Cool, dry place away from direct sunlight"
    },

    // 2. Sauces & Gravies Category
    {
      id: "tomato-sauce",
      mainCategory: "Sauces & Gravies",
      subCategory: "Tomato Sauce",
      title: "Authentic Tomato Sauce (500g Glass Bottle)",
      badge: "Artisanal Sauce",
      img: "images/products/tomato-sauce.jpg",
      desc: "Thick, rich ketchup crafted from ripe farm tomatoes, aromatic spices, and balanced sweetness for restaurant & household condiment needs.",
      pkg: "500g Glass Bottle",
      spec: "500g Glass Bottle",
      storage: "Refrigerate after opening"
    },
    {
      id: "pizza-gravy",
      mainCategory: "Sauces & Gravies",
      subCategory: "Italian Pizza Gravy",
      title: "Italian Pizza & Pasta Gravy (500g Glass Jar / 850g Can)",
      badge: "Chef's Choice",
      img: "images/products/pizza-gravy.jpg",
      desc: "Slow-cooked tomato gravy infused with Italian herbs, garlic, and extra virgin olive oil notes for authentic pizza and pasta bases.",
      pkg: "500g Glass Jar / 850g Can",
      spec: "500g Glass Jar / 850g Can",
      storage: "Cool, dry place. Refrigerate after opening"
    },

    // 3. Jams & Pickles Category
    {
      id: "mixed-fruit-jam",
      mainCategory: "Jams & Pickles",
      subCategory: "Fruit Jam",
      title: "Mixed Fruit Jam (500g Glass Jar)",
      badge: "Classic Preserve",
      img: "images/products/mixed-fruit-jam.jpg",
      desc: "Delightful blend of real apples, mangoes, papayas, and strawberries cooked into a smooth velvety spread for breakfast toast and bakery toppings.",
      pkg: "500g Glass Jar",
      spec: "500g Glass Jar",
      storage: "Cool, dry place"
    },
    {
      id: "fruit-squash",
      mainCategory: "Jams & Pickles",
      subCategory: "Fruit Squash",
      title: "Fruit Squash & Concentrates (750ml Bottle)",
      badge: "Refreshing Drink",
      img: "images/products/fruit-squash.jpg",
      desc: "Concentrated fruit squashes in Pineapple, Variyali-Sakar, Khus, and Kachi Keri. Simply dilute with cold water or soda for instant fruit beverages.",
      pkg: "750ml Pouring Bottle",
      spec: "750ml Pouring Bottle",
      storage: "Store in cool place"
    },
    {
      id: "mango-pickle",
      mainCategory: "Jams & Pickles",
      subCategory: "Pickles",
      title: "Gol & Khati Mango Pickles (500g Ceramic Jar)",
      badge: "Traditional",
      img: "images/products/mango-pickle.jpg",
      desc: "Authentic Gujarati-style sweet & sour mango pickles, Chhunda, and Punjabi red chilli preserves prepared with traditional spices & pure oils.",
      pkg: "500g / 1kg Ceramic Jar",
      spec: "500g Ceramic Jar",
      storage: "Store at room temperature"
    },

    // 4. Amla & Herbal Care Category
    {
      id: "amla-murabba",
      mainCategory: "Amla & Herbal Care",
      subCategory: "Amla Products",
      title: "Amla Murabba in Honey Syrup (500g Glass Jar)",
      badge: "Immunity Booster",
      img: "images/products/amla-murabba.jpg",
      desc: "Whole tender Indian gooseberries preserved in pure honey and cardamom-infused syrup. High natural Vitamin-C content for daily wellness.",
      pkg: "500g Glass Jar",
      spec: "500g Glass Jar",
      storage: "Store at room temperature"
    },
    {
      id: "hair-oil",
      mainCategory: "Amla & Herbal Care",
      subCategory: "Herbal Hair Oil",
      title: "18 Herbs Medicinal Hair Oil (200ml Amber Bottle)",
      badge: "Ayurvedic",
      img: "images/products/hair-oil.jpg",
      desc: "Nourishing hair care oil formulated with Bhringraj, Brahmi, Jatamansi, and Amla boiled into pure natural sesame & coconut oil bases.",
      pkg: "200ml Amber Bottle",
      spec: "200ml Amber Bottle",
      storage: "Store at room temperature"
    }
  ];

  window.productsData = productsData;
  let activeCategory = 'all';
  let activePkgFilter = 'all';

  // DOM Loaded Initializer
  document.addEventListener('DOMContentLoaded', () => {
    renderCategorySidebar();
    filterProductsAjax();
  });

  // Render Category Sidebar with Counts
  function renderCategorySidebar() {
    const listEl = document.getElementById('categoryFilterList');
    if (!listEl) return;

    const mainCategories = [
      {
        name: "Pulp",
        icon: "fa-jar",
        subItems: ["Sitafal Pulp", "Jamun Pulp", "Falsa Pulp", "Mango Pulp"]
      },
      {
        name: "Sauces & Gravies",
        icon: "fa-utensils",
        subItems: ["Tomato Sauce", "Italian Pizza Gravy"]
      },
      {
        name: "Jams & Pickles",
        icon: "fa-cubes-stacked",
        subItems: ["Fruit Jam", "Fruit Squash", "Pickles"]
      },
      {
        name: "Amla & Herbal Care",
        icon: "fa-leaf",
        subItems: ["Amla Products", "Herbal Hair Oil"]
      }
    ];

    let html = `
      <li class="main-cat-item">
        <button class="cat-toggle-btn ${activeCategory === 'all' ? 'active' : ''}" onclick="filterCategory('all', this)">
          <span><i class="fa-solid fa-border-all"></i> All Categories</span>
          <span class="cat-count-badge">${productsData.length}</span>
        </button>
      </li>
    `;

    mainCategories.forEach((catGroup, idx) => {
      const groupCount = productsData.filter(p => p.mainCategory === catGroup.name).length;
      const subMenuId = `sub-cat-${idx}`;
      const isGroupActive = activeCategory === catGroup.name;

      html += `
        <li class="main-cat-item">
          <button class="cat-toggle-btn ${isGroupActive ? 'active' : ''}" onclick="toggleSubMenu('${subMenuId}', this)">
            <span><i class="fa-solid ${catGroup.icon}"></i> ${catGroup.name}</span>
            <div style="display: flex; align-items: center; gap: 6px;">
              <span class="cat-count-badge">${groupCount}</span>
              <i class="fa-solid fa-chevron-down" style="font-size: 0.75rem;"></i>
            </div>
          </button>
          <ul class="sub-tree-list open" id="${subMenuId}">
      `;

      catGroup.subItems.forEach(subName => {
        const subCount = productsData.filter(p => p.subCategory === subName || p.title.toLowerCase().includes(subName.toLowerCase())).length;
        const isSubActive = activeCategory === subName;

        html += `
          <li>
            <span class="sub-cat-link ${isSubActive ? 'active' : ''}" onclick="filterCategory('${subName}', this)">
              <span>${subName}</span>
              <span style="font-size: 0.72rem; opacity: 0.8; font-weight: 700;">(${subCount})</span>
            </span>
          </li>
        `;
      });

      html += `
          </ul>
        </li>
      `;
    });

    listEl.innerHTML = html;
  }

  // Render Product Grid Card Layout
  function renderProducts(items) {
    const grid = document.getElementById('productsGrid') || document.getElementById('ajaxProductGrid');
    if (!grid) return;

    grid.innerHTML = '';

    if (items.length === 0) {
      grid.innerHTML = `
        <div style="grid-column: 1 / -1; text-align: center; padding: 3rem 1rem; background: #fff; border-radius: 14px; border: 1px dashed var(--border-medium);">
          <i class="fa-solid fa-magnifying-glass" style="font-size: 2.5rem; color: var(--accent-hover); margin-bottom: 1rem;"></i>
          <h3 style="font-size: 1.4rem; color: var(--primary); margin-bottom: 0.5rem; font-family: var(--font-serif);">No Matching Products Found</h3>
          <p style="color: var(--text-muted); font-size: 0.9rem; margin-bottom: 1.25rem;">Try adjusting your search terms or selecting another category.</p>
          <button class="btn btn-accent btn-sm" onclick="resetAllFilters()" style="background: var(--accent); color: var(--primary); padding: 8px 18px; border-radius: 20px; border: none; cursor: pointer; font-weight: 700;">Reset All Filters</button>
        </div>
      `;
      return;
    }

    items.forEach(p => {
      const card = document.createElement('div');
      card.className = 'product-box light-sheen-card';
      card.style.cursor = 'pointer';
      card.onclick = () => {
        window.location.href = `product-detail.html?id=${p.id}`;
      };

      card.innerHTML = `
        <div class="img-holder">
          <span class="item-tag">${p.badge}</span>
          <img src="${p.img}" alt="${p.title}" loading="lazy" />
        </div>
        <div class="box-info">
          <h4>${p.title}</h4>
          <div class="product-rating">
            <span class="rating-stars">★★★★★</span>
            <span class="rating-text">4.9 (120+ reviews)</span>
          </div>
          <p>${p.desc.length > 95 ? p.desc.substring(0, 95) + '...' : p.desc}</p>
          <div style="display: flex; justify-content: space-between; align-items: center; margin-top: auto; padding-top: 10px; border-top: 1px solid var(--border-light);">
            <span style="font-size: 0.78rem; font-weight: 700; color: var(--terracotta);"><i class="fa-solid fa-box-open"></i> ${p.spec}</span>
            <a href="product-detail.html?id=${p.id}" class="view-btn" onclick="event.stopPropagation();">View Details <i class="fa-solid fa-arrow-right"></i></a>
          </div>
        </div>
      `;

      grid.appendChild(card);
    });

    // Update Product Count Text
    const countEl = document.getElementById('productCountText') || document.getElementById('resultsCountText');
    if (countEl) {
      countEl.innerText = `Showing ${items.length} of ${productsData.length} flagship formulations`;
    }

    // GSAP Reveal Animation
    if (typeof gsap !== 'undefined') {
      gsap.fromTo('.product-box', 
        { opacity: 0, y: 20 },
        { opacity: 1, y: 0, duration: 0.35, stagger: 0.05, ease: 'power2.out' }
      );
    }
  }

  // Toggle Sub Menu in Tree Sidebar
  window.toggleSubMenu = function (menuId, button) {
    const targetMenu = document.getElementById(menuId);
    if (!targetMenu) return;

    const isOpen = targetMenu.classList.contains('open');

    if (isOpen) {
      targetMenu.classList.remove('open');
      if (button) button.classList.remove('active');
    } else {
      targetMenu.classList.add('open');
      if (button) button.classList.add('active');
    }
  };

  // Filter Category Handler
  window.filterCategory = function (category, element) {
    activeCategory = category;

    document.querySelectorAll('.cat-toggle-btn, .sub-cat-link').forEach(el => el.classList.remove('active'));
    if (element) element.classList.add('active');

    const heading = document.getElementById('currentCategoryHeading') || document.getElementById('currentFilterHeading');
    if (heading) {
      heading.innerText = category === 'all' ? 'All Products' : category;
    }

    filterProductsAjax();
  };

  // Select Packaging Filter
  window.selectPackagingFilter = function (pkg, element) {
    document.querySelectorAll('.pkg-filter-item').forEach(el => el.classList.remove('active'));
    if (element) element.classList.add('active');
    activePkgFilter = pkg;
    filterProductsAjax();
  };

  // Instant AJAX Filter Function
  window.filterProductsAjax = function () {
    const searchInput = document.getElementById('productSearchInput');
    const query = searchInput ? searchInput.value.toLowerCase().trim() : '';
    const sortValue = document.getElementById('sortSelect')?.value || 'default';

    let filtered = productsData.filter(item => {
      const matchCategory = (activeCategory === 'all') ||
        (item.mainCategory === activeCategory) ||
        (item.subCategory === activeCategory) ||
        (item.title.toLowerCase().includes(activeCategory.toLowerCase()));

      const matchPackaging = (activePkgFilter === 'all') ||
        (item.pkg.toLowerCase().includes(activePkgFilter.toLowerCase())) ||
        (item.spec.toLowerCase().includes(activePkgFilter.toLowerCase())) ||
        (item.title.toLowerCase().includes(activePkgFilter.toLowerCase()));

      const matchQuery = !query ||
        item.title.toLowerCase().includes(query) ||
        item.desc.toLowerCase().includes(query) ||
        item.subCategory.toLowerCase().includes(query) ||
        item.mainCategory.toLowerCase().includes(query);

      return matchCategory && matchPackaging && matchQuery;
    });

    if (sortValue === 'name-asc') {
      filtered.sort((a, b) => a.title.localeCompare(b.title));
    } else if (sortValue === 'name-desc') {
      filtered.sort((a, b) => b.title.localeCompare(a.title));
    }

    renderProducts(filtered);
  };

  // Reset All Filters
  window.resetAllFilters = function () {
    activeCategory = 'all';
    activePkgFilter = 'all';

    const searchInput = document.getElementById('productSearchInput');
    if (searchInput) searchInput.value = '';

    const sortSelect = document.getElementById('sortSelect');
    if (sortSelect) sortSelect.value = 'default';

    document.querySelectorAll('.pkg-filter-item').forEach(el => el.classList.remove('active'));
    const firstPkg = document.querySelector('.pkg-filter-item[data-pkg="all"]');
    if (firstPkg) firstPkg.classList.add('active');

    renderCategorySidebar();

    const heading = document.getElementById('currentCategoryHeading') || document.getElementById('currentFilterHeading');
    if (heading) heading.innerText = 'All Products';

    filterProductsAjax();
  };

  // Open Quick-View Product Modal
  window.openProductModal = function (productId) {
    const product = productsData.find(p => p.id === productId);
    if (!product) return;

    const modal = document.getElementById('productModal');
    if (!modal) return;

    const mImg = document.getElementById('mImg');
    const mBadge = document.getElementById('mBadge');
    const mTitle = document.getElementById('mTitle');
    const mDesc = document.getElementById('mDesc');
    const mPkg = document.getElementById('mPkg');
    const mStorage = document.getElementById('mStorage');

    if (mImg) mImg.src = product.img;
    if (mBadge) mBadge.innerText = `${product.mainCategory} > ${product.subCategory}`;
    if (mTitle) mTitle.innerText = product.title;
    if (mDesc) mDesc.innerText = product.desc;
    if (mPkg) mPkg.innerText = product.pkg;
    if (mStorage) mStorage.innerText = product.storage;

    modal.style.display = 'flex';
    modal.setAttribute('aria-hidden', 'false');
  };

  // Close Quick-View Modal
  window.closeProductModal = function () {
    const modal = document.getElementById('productModal');
    if (modal) {
      modal.style.display = 'none';
      modal.setAttribute('aria-hidden', 'true');
    }
  };

  // Close modal when clicking outside backdrop
  window.addEventListener('click', (e) => {
    const modal = document.getElementById('productModal');
    if (e.target === modal) {
      closeProductModal();
    }
  });

})();
