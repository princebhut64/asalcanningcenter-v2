/* ==========================================================================
   ASAL CANNING CENTER - Real-time Product Database (Original Website Items)
   ========================================================================== */

const productsData = [
  // 1. Pulps
  {
    id: "sitafal-pulp",
    mainCategory: "Pulp",
    subCategory: "Sitafal Pulp",
    title: "Sitafal Pulp (Custard Apple)",
    badge: "Pulp > Seasonal",
    img: "https://images.unsplash.com/photo-1615485290382-441e4d049cb5?auto=format&fit=crop&w=600&q=80",
    desc: "Fresh extraction of authentic creamy Sitafal (Custard Apple) pulp. Processed cleanly without seed breakage, preserving natural aroma for ice creams, shakes, and desserts.",
    pkg: "Food-grade Sealed Pouches & Tins",
    spec: "1kg / 5kg Pouches",
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
    spec: "500g / 1kg Pouches",
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
    spec: "500g Pouch Packs",
    storage: "Store frozen; no added artificial flavors"
  },
  {
    id: "mango-pulp-tin",
    mainCategory: "Pulp",
    subCategory: "Mango Pulp",
    title: "Mango Pulp (Tin & Pouch)",
    badge: "Pulp > Year Round",
    img: "https://images.unsplash.com/photo-1553279768-865429fa0078?auto=format&fit=crop&w=600&q=80",
    desc: "Signature Mango Pulp preservation. Available in ready long-shelf-life tin cans (stays fresh without freezing) and batch pouch extractions for bulk yields.",
    pkg: "Commercial Tins & 1kg/5kg Pouches",
    spec: "850g Tin / 1kg Pouch",
    storage: "Tins stay at room temperature; Pouches in deep freeze"
  },

  // 2. Sauces & Gravies
  {
    id: "tomato-sauce",
    mainCategory: "Sauces & Gravies",
    subCategory: "Tomato Sauce",
    title: "Authentic Tomato Sauce",
    badge: "Sauces",
    img: "https://images.unsplash.com/photo-1592924357228-91a4daadcfea?auto=format&fit=crop&w=600&q=80",
    desc: "Traditional rich tomato puree seasoned with cinnamon and cloves. Crafted from ripe tomatoes with guaranteed home-style thickness and taste.",
    pkg: "Glass Bottles / Sterilized Jars",
    spec: "500g Bottle / Jars",
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
    spec: "500g Glass Jars",
    storage: "Store in cool & dry place"
  },

  // 3. Jams, Squashes & Pickles
  {
    id: "mixed-fruit-jam",
    mainCategory: "Jams & Pickles",
    subCategory: "Fruit Jam",
    title: "Mixed Fruit Jam",
    badge: "Jams",
    img: "https://images.unsplash.com/photo-1619546813926-a78fa6372cd2?auto=format&fit=crop&w=600&q=80",
    desc: "Prepared with real peach, apple, pineapple, papaya, plum, and strawberry. Ideal spread balancing authentic fruit sweetness.",
    pkg: "Glass Jars (500g, 1kg)",
    spec: "500g Glass Jar",
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
    spec: "750ml Bottle",
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
    spec: "500g / 1kg Jars",
    storage: "Room temperature away from moisture"
  },

  // 4. Amla & Health Care
  {
    id: "amla-range",
    mainCategory: "Amla & Herbal Care",
    subCategory: "Amla Products",
    title: "Chyawanprash & Amla Murabba",
    badge: "Ayurvedic",
    img: "https://images.unsplash.com/photo-1615485290382-441e4d049cb5?auto=format&fit=crop&w=600&q=80",
    desc: "Immunity revitalizers made with whole wild gooseberries, natural honey, and rejuvenating herbs to maintain high natural Vitamin C content.",
    pkg: "Sealed Jars",
    spec: "500g / 1kg Sealed Jar",
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
    spec: "200ml / 500ml Dispenser",
    storage: "Room temperature"
  }
];

let activeCategory = 'all';
let activePackaging = 'all';

document.addEventListener('DOMContentLoaded', () => {
  renderCatalogProducts(productsData);
  updateCategoryCounts();
});

// Render Product Cards Grid
function renderCatalogProducts(items) {
  const container = document.getElementById('ajaxProductGrid');
  if (!container) return;

  container.innerHTML = '';

  if (items.length === 0) {
    container.innerHTML = `
      <div class="no-results-box">
        <i class="fa-solid fa-magnifying-glass text-gold"></i>
        <h3 class="heading-serif" style="font-size: 1.6rem; color: var(--primary);">No Matching Products Found</h3>
        <p style="color: var(--text-muted); margin-bottom: 1.5rem;">Try adjusting your search terms or filter selections.</p>
        <button class="btn btn-outline-gold btn-sm" onclick="resetAllFilters()">Reset All Filters</button>
      </div>
    `;
    return;
  }

  items.forEach(p => {
    const card = document.createElement('div');
    card.className = 'product-card light-sheen-card';
    card.setAttribute('data-category', p.mainCategory);

    card.innerHTML = `
      <div class="product-img-wrapper">
        <span class="product-tag">${p.badge}</span>
        <img src="${p.img}" alt="${p.title}" />
      </div>
      <div class="product-body">
        <h3>${p.title}</h3>
        <p>${p.desc.length > 90 ? p.desc.substring(0, 90) + '...' : p.desc}</p>
        <div class="product-meta">
          <span class="product-spec"><i class="fa-solid fa-box"></i> ${p.spec}</span>
          <a href="product-detail.html" class="btn btn-outline-gold btn-sm">Details <i class="fa-solid fa-arrow-right"></i></a>
        </div>
      </div>
    `;

    container.appendChild(card);
  });

  // Smooth GSAP Reveal Animation on filtered items
  if (typeof gsap !== 'undefined') {
    gsap.fromTo('#ajaxProductGrid .product-card', 
      { opacity: 0, y: 20, scale: 0.96 },
      { opacity: 1, y: 0, scale: 1, duration: 0.4, stagger: 0.06, ease: 'power2.out' }
    );
  }
}

// Real-time AJAX Filter Function
function filterProductsAjax() {
  const searchInput = document.getElementById('productSearchInput');
  const query = searchInput ? searchInput.value.toLowerCase().trim() : '';
  const sortValue = document.getElementById('sortSelect')?.value || 'default';

  let filtered = productsData.filter(item => {
    const matchesCategory = (activeCategory === 'all' || item.mainCategory === activeCategory);
    const matchesPackaging = (activePackaging === 'all' || item.pkg.toLowerCase().includes(activePackaging.toLowerCase()));
    const matchesQuery = query === '' || 
      item.title.toLowerCase().includes(query) || 
      item.desc.toLowerCase().includes(query) || 
      item.subCategory.toLowerCase().includes(query);

    return matchesCategory && matchesPackaging && matchesQuery;
  });

  // Apply Sorting
  if (sortValue === 'name-asc') {
    filtered.sort((a, b) => a.title.localeCompare(b.title));
  } else if (sortValue === 'name-desc') {
    filtered.sort((a, b) => b.title.localeCompare(a.title));
  }

  // Update Results Text
  const resultsCountText = document.getElementById('resultsCountText');
  if (resultsCountText) {
    resultsCountText.innerText = `Showing ${filtered.length} of ${productsData.length} items`;
  }

  renderCatalogProducts(filtered);
}

// Select Category Filter
function selectCategoryFilter(category, element) {
  document.querySelectorAll('#categoryFilterList .filter-link').forEach(el => el.classList.remove('active'));
  element.classList.add('active');
  activeCategory = category;

  const heading = document.getElementById('currentFilterHeading');
  if (heading) {
    heading.innerText = category === 'all' ? 'All Products' : category;
  }

  filterProductsAjax();
}

// Select Packaging Filter
function selectPackagingFilter(pkg, element) {
  element.parentElement.parentElement.querySelectorAll('.filter-link').forEach(el => el.classList.remove('active'));
  element.classList.add('active');
  activePackaging = pkg;

  filterProductsAjax();
}

// Reset All Filters
function resetAllFilters() {
  activeCategory = 'all';
  activePackaging = 'all';

  const searchInput = document.getElementById('productSearchInput');
  if (searchInput) searchInput.value = '';

  const sortSelect = document.getElementById('sortSelect');
  if (sortSelect) sortSelect.value = 'default';

  document.querySelectorAll('.filter-link').forEach(el => el.classList.remove('active'));
  const firstCat = document.querySelector('#categoryFilterList .filter-link');
  if (firstCat) firstCat.classList.add('active');

  const heading = document.getElementById('currentFilterHeading');
  if (heading) heading.innerText = 'All Products';

  filterProductsAjax();
}

// Update Badge Counts
function updateCategoryCounts() {
  const countAll = document.getElementById('countAll');
  if (countAll) countAll.innerText = productsData.length;

  const countPulp = document.getElementById('countPulp');
  if (countPulp) countPulp.innerText = productsData.filter(p => p.mainCategory === 'Pulp').length;

  const countSauces = document.getElementById('countSauces');
  if (countSauces) countSauces.innerText = productsData.filter(p => p.mainCategory === 'Sauces & Gravies').length;

  const countJams = document.getElementById('countJams');
  if (countJams) countJams.innerText = productsData.filter(p => p.mainCategory === 'Jams & Pickles').length;

  const countHealth = document.getElementById('countHealth');
  if (countHealth) countHealth.innerText = productsData.filter(p => p.mainCategory === 'Amla & Herbal Care').length;
}
