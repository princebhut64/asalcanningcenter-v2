/* ==========================================================================
   ASAL CANNING CENTER
   PRODUCTS DATABASE & INTERACTIVE CATALOG CONTROLLER

   WordPress Dynamic Version

   CPT:
   products

   Taxonomies:
   product_category
   packaging_type

   Data comes from PHP:
   asalProductsData.products
   ========================================================================== */

(function () {

  'use strict';


  /* ============================================================
     WORDPRESS PRODUCTS DATA
     ============================================================ */

  const productsData =
    typeof asalProductsData !== 'undefined' &&
    Array.isArray(asalProductsData.products)
      ? asalProductsData.products
      : [];


  /*
   * Make globally available
   */
  window.productsData = productsData;


  /* ============================================================
     ACTIVE FILTERS
     ============================================================ */

  let activeCategory = 'all';

  let activePkgFilter = 'all';


  /* ============================================================
     DOM LOADED
     ============================================================ */

  document.addEventListener(
    'DOMContentLoaded',
    function () {

      renderCategorySidebar();

      filterProductsAjax();

    }
  );


  /* ============================================================
     CATEGORY ICON
     ============================================================ */

  function getCategoryIcon(category) {

    const slug = String(category)
      .toLowerCase()
      .replace(/&/g, '')
      .replace(/\s+/g, '-');


    const icons = {

      'pulp':
        'fa-jar',

      'sauces-gravies':
        'fa-utensils',

      'jams-pickles':
        'fa-cubes-stacked',

      'amla-herbal-care':
        'fa-leaf'

    };


    return icons[slug] || 'fa-layer-group';

  }


  /* ============================================================
     ESCAPE HTML
     ============================================================ */

  function escapeHtml(value) {

    if (
      value === null ||
      value === undefined
    ) {

      return '';

    }


    return String(value)

      .replace(/&/g, '&amp;')

      .replace(/</g, '&lt;')

      .replace(/>/g, '&gt;')

      .replace(/"/g, '&quot;')

      .replace(/'/g, '&#039;');

  }


  /* ============================================================
     ESCAPE JS STRING
     ============================================================ */

  function escapeJs(value) {

    return String(value || '')
      .replace(/\\/g, '\\\\')
      .replace(/'/g, "\\'");

  }


  /* ============================================================
     RENDER CATEGORY SIDEBAR
     ============================================================ */

  function renderCategorySidebar() {

    const listEl =
      document.getElementById(
        'categoryFilterList'
      );


    if (!listEl) {
      return;
    }


    /*
     * Category hierarchy
     */

    const categoryTree = {};


    productsData.forEach(function (product) {

      const main =
        product.mainCategory ||
        'Uncategorized';


      const sub =
        product.subCategory ||
        '';


      if (!categoryTree[main]) {

        categoryTree[main] = [];

      }


      if (
        sub &&
        !categoryTree[main].includes(sub)
      ) {

        categoryTree[main].push(sub);

      }

    });


    /*
     * Start HTML
     */

    let html = `

      <li class="main-cat-item">

        <button
          class="cat-toggle-btn ${
            activeCategory === 'all'
              ? 'active'
              : ''
          }"
          onclick="filterCategory('all', this)"
        >

          <span>

            <i class="fa-solid fa-border-all"></i>

            All Categories

          </span>


          <span class="cat-count-badge">

            ${productsData.length}

          </span>

        </button>

      </li>

    `;


    /*
     * Categories
     */

    Object.keys(categoryTree).forEach(
      function (category, index) {

        const subCategories =
          categoryTree[category];


        /*
         * Main category count
         */

        const groupCount =
          productsData.filter(
            function (product) {

              return (
                product.mainCategory ===
                category
              );

            }
          ).length;


        const subMenuId =
          `sub-cat-${index}`;


        const isGroupActive =
          activeCategory === category;


        html += `

          <li class="main-cat-item">

            <button
              class="cat-toggle-btn ${
                isGroupActive
                  ? 'active'
                  : ''
              }"
              onclick="toggleSubMenu(
                '${subMenuId}',
                this
              )"
            >

              <span>

                <i
                  class="fa-solid ${
                    getCategoryIcon(category)
                  }"
                ></i>

                ${category}

              </span>


              <div
                style="
                  display: flex;
                  align-items: center;
                  gap: 6px;
                "
              >

                <span class="cat-count-badge">

                  ${groupCount}

                </span>


                <i
                  class="fa-solid fa-chevron-down"
                  style="
                    font-size: 0.75rem;
                  "
                ></i>

              </div>

            </button>


            <ul
              class="sub-tree-list open"
              id="${subMenuId}"
            >

        `;


        /*
         * Sub categories
         */

        subCategories.forEach(
          function (subName) {

            const subCount =
              productsData.filter(
                function (product) {

                  return (
                    product.subCategory ===
                    subName
                  );

                }
              ).length;


            const isSubActive =
              activeCategory === subName;


            html += `

              <li>

                <span
                  class="sub-cat-link ${
                    isSubActive
                      ? 'active'
                      : ''
                  }"
                  onclick="filterCategory(
                    '${escapeJs(subName)}',
                    this
                  )"
                >

                  <span>
                    ${escapeHtml(subName)}
                  </span>


                  <span
                    style="
                      font-size: 0.72rem;
                      opacity: 0.8;
                      font-weight: 700;
                    "
                  >
                    (${subCount})
                  </span>

                </span>

              </li>

            `;

          }
        );


        html += `

            </ul>

          </li>

        `;

      }
    );


    /*
     * Put HTML in sidebar
     */

    listEl.innerHTML = html;

  }


  /* ============================================================
     RENDER PRODUCTS
     ============================================================ */

  /* ============================================================
     PAGINATION & INFINITE SCROLL STATE (12 PER BATCH)
     ============================================================ */

  const PRODUCTS_PER_PAGE = 12;
  let currentFilteredProducts = [];
  let currentLoadedCount = 0;
  let productObserver = null;
  let isLoadingMore = false;

  /* ============================================================
     BUILD PRODUCT CARD
     ============================================================ */

  function buildProductCard(p) {
    const card = document.createElement('div');
    card.className = 'product-box light-sheen-card';
    card.style.cursor = 'pointer';

    card.onclick = function () {
      if (p.url) {
        window.location.href = p.url;
      }
    };

    const title = escapeHtml(p.title);
    const badge = escapeHtml(p.badge);
    const image = escapeHtml(p.img);
    const rating_text = escapeHtml(p.rating_text);
    const specification = escapeHtml(p.spec);
    const productUrl = escapeHtml(p.url);

    let shortDescription = p.desc || '';
    if (shortDescription.length > 95) {
      shortDescription = shortDescription.substring(0, 95) + '...';
    }
    shortDescription = escapeHtml(shortDescription);

    card.innerHTML = `
      <div class="img-holder">
        <span class="item-tag">${badge}</span>
        ${
          image
            ? `<img src="${image}" alt="${title}" loading="lazy" />`
            : `<div style="height: 100%; min-height: 220px; display: flex; align-items: center; justify-content: center;">
                 <i class="fa-solid fa-image" style="font-size: 3rem; opacity: 0.3;"></i>
               </div>`
        }
      </div>
      <div class="box-info">
        <h4>${title}</h4>
        <div class="product-rating">
          <span class="rating-stars">★★★★★</span>
          <span class="rating-text">${rating_text}</span>
        </div>
        <p>${shortDescription}</p>
        <div style="display: flex; justify-content: space-between; align-items: center; margin-top: auto; padding-top: 10px; border-top: 1px solid var(--border-light);">
          <span style="font-size: 0.78rem; font-weight: 700; color: var(--terracotta);">
            <i class="fa-solid fa-box-open"></i> ${specification}
          </span>
          <a href="${productUrl}" class="view-btn" onclick="event.stopPropagation();">
            View Details <i class="fa-solid fa-arrow-right"></i>
          </a>
        </div>
      </div>
    `;

    return card;
  }

  /* ============================================================
     SCROLL OBSERVER & BATCH LOADER
     ============================================================ */

  function initProductScrollObserver() {
    const sentinel = document.getElementById('productScrollSentinel');
    if (!sentinel) return;

    if (productObserver) {
      productObserver.disconnect();
    }

    productObserver = new IntersectionObserver((entries) => {
      entries.forEach(entry => {
        if (entry.isIntersecting) {
          loadNextProductBatch();
        }
      });
    }, {
      rootMargin: '300px 0px 300px 0px',
      threshold: 0.05
    });

    if (currentLoadedCount < currentFilteredProducts.length) {
      productObserver.observe(sentinel);
    }
  }

  function loadNextProductBatch() {
    if (isLoadingMore || currentLoadedCount >= currentFilteredProducts.length) {
      return;
    }

    const grid = document.getElementById('productsGrid') || document.getElementById('ajaxProductGrid');
    const sentinel = document.getElementById('productScrollSentinel');
    const endNotice = document.getElementById('productEndNotice');
    if (!grid) return;

    isLoadingMore = true;
    if (sentinel) sentinel.style.display = 'flex';

    setTimeout(() => {
      const nextBatch = currentFilteredProducts.slice(
        currentLoadedCount,
        currentLoadedCount + PRODUCTS_PER_PAGE
      );

      if (!nextBatch.length) {
        isLoadingMore = false;
        if (sentinel) sentinel.style.display = 'none';
        return;
      }

      const newCards = [];
      nextBatch.forEach(p => {
        const card = buildProductCard(p);
        card.style.opacity = '0';
        card.style.transform = 'translateY(20px)';
        grid.appendChild(card);
        newCards.push(card);
      });

      currentLoadedCount += nextBatch.length;

      if (typeof gsap !== 'undefined') {
        gsap.to(newCards, {
          opacity: 1,
          y: 0,
          duration: 0.4,
          stagger: 0.04,
          ease: 'power2.out',
          clearProps: 'transform'
        });
      } else {
        newCards.forEach(c => {
          c.style.opacity = '1';
          c.style.transform = '';
        });
      }

      if (typeof window.initTiltEffect === 'function') {
        window.initTiltEffect();
      }

      updateProductCount(currentLoadedCount, currentFilteredProducts.length);

      if (currentLoadedCount >= currentFilteredProducts.length) {
        if (sentinel) sentinel.style.display = 'none';
        if (endNotice && currentFilteredProducts.length > PRODUCTS_PER_PAGE) {
          endNotice.style.display = 'block';
        }
        if (productObserver && sentinel) {
          productObserver.unobserve(sentinel);
        }
      }

      isLoadingMore = false;
    }, 120);
  }

  /* ============================================================
     RENDER PRODUCTS
     ============================================================ */

  function renderProducts(items) {
    const grid = document.getElementById('productsGrid') || document.getElementById('ajaxProductGrid');
    const sentinel = document.getElementById('productScrollSentinel');
    const endNotice = document.getElementById('productEndNotice');

    if (!grid) return;

    grid.innerHTML = '';
    currentFilteredProducts = Array.isArray(items) ? items : [];
    currentLoadedCount = 0;
    isLoadingMore = false;

    if (!currentFilteredProducts.length) {
      grid.innerHTML = `
        <div style="grid-column: 1 / -1; text-align: center; padding: 3rem 1rem; background: #fff; border-radius: 14px; border: 1px dashed var(--border-medium);">
          <i class="fa-solid fa-magnifying-glass" style="font-size: 2.5rem; color: var(--accent-hover); margin-bottom: 1rem;"></i>
          <h3 style="font-size: 1.4rem; color: var(--primary); margin-bottom: 0.5rem; font-family: var(--font-serif);">No Matching Products Found</h3>
          <p style="color: var(--text-muted); font-size: 0.9rem; margin-bottom: 1.25rem;">Try adjusting your search terms or selecting another category.</p>
          <button class="btn btn-accent btn-sm" onclick="resetAllFilters()" style="background: var(--accent); color: var(--primary); padding: 8px 18px; border-radius: 20px; border: none; cursor: pointer; font-weight: 700;">Reset All Filters</button>
        </div>
      `;
      if (sentinel) sentinel.style.display = 'none';
      if (endNotice) endNotice.style.display = 'none';
      updateProductCount(0, 0);
      return;
    }

    // Initial batch of 12 products
    const initialBatch = currentFilteredProducts.slice(0, PRODUCTS_PER_PAGE);
    currentLoadedCount = initialBatch.length;

    const initialCards = [];
    initialBatch.forEach(p => {
      const card = buildProductCard(p);
      grid.appendChild(card);
      initialCards.push(card);
    });

    if (typeof gsap !== 'undefined') {
      gsap.fromTo(
        initialCards,
        { opacity: 0, y: 20 },
        { opacity: 1, y: 0, duration: 0.35, stagger: 0.04, ease: 'power2.out', clearProps: 'transform' }
      );
    }

    if (typeof window.initTiltEffect === 'function') {
      window.initTiltEffect();
    }

    updateProductCount(currentLoadedCount, currentFilteredProducts.length);

    if (currentLoadedCount < currentFilteredProducts.length) {
      if (sentinel) sentinel.style.display = 'flex';
      if (endNotice) endNotice.style.display = 'none';
      initProductScrollObserver();
    } else {
      if (sentinel) sentinel.style.display = 'none';
      if (endNotice) endNotice.style.display = 'none';
    }
  }

  /* ============================================================
     PRODUCT COUNT
     ============================================================ */

  function updateProductCount(visibleCount, totalFilteredCount) {
    const countEl =
      document.getElementById('productCountText') ||
      document.getElementById('resultsCountText');

    if (!countEl) return;

    const total = totalFilteredCount !== undefined ? totalFilteredCount : productsData.length;

    if (total === 0) {
      countEl.innerText = 'No products match your search or filter criteria';
      return;
    }

    if (visibleCount >= total) {
      countEl.innerText = `Showing all ${total} available formulations`;
    } else {
      countEl.innerText = `Showing ${visibleCount} of ${total} formulations (scroll for more)`;
    }
  }


  /* ============================================================
     TOGGLE SUB MENU
     ============================================================ */

  window.toggleSubMenu =
    function (
      menuId,
      button
    ) {

      const targetMenu =
        document.getElementById(
          menuId
        );


      if (!targetMenu) {
        return;
      }


      const isOpen =
        targetMenu.classList.contains(
          'open'
        );


      if (isOpen) {

        targetMenu.classList.remove(
          'open'
        );


        if (button) {

          button.classList.remove(
            'active'
          );

        }

      } else {

        targetMenu.classList.add(
          'open'
        );


        if (button) {

          button.classList.add(
            'active'
          );

        }

      }

    };


  /* ============================================================
     FILTER CATEGORY
     ============================================================ */

  window.filterCategory =
    function (
      category,
      element
    ) {

      activeCategory =
        category;


      /*
       * Remove old active
       */

      document
        .querySelectorAll(
          '.cat-toggle-btn, .sub-cat-link'
        )
        .forEach(
          function (el) {

            el.classList.remove(
              'active'
            );

          }
        );


      /*
       * Add active
       */

      if (element) {

        element.classList.add(
          'active'
        );

      }


      /*
       * Heading
       */

      const heading =
        document.getElementById(
          'currentCategoryHeading'
        ) ||
        document.getElementById(
          'currentFilterHeading'
        );


      if (heading) {

        heading.innerText =
          category === 'all'
            ? 'All Products'
            : category;

      }


      /*
       * Filter
       */

      filterProductsAjax();

    };


  /* ============================================================
     PACKAGING FILTER
     ============================================================ */

  window.selectPackagingFilter =
    function (
      pkg,
      element
    ) {

      /*
       * Remove active
       */

      document
        .querySelectorAll(
          '.pkg-filter-item'
        )
        .forEach(
          function (el) {

            el.classList.remove(
              'active'
            );

          }
        );


      /*
       * Add active
       */

      if (element) {

        element.classList.add(
          'active'
        );

      }


      activePkgFilter =
        pkg;


      /*
       * Filter
       */

      filterProductsAjax();

    };


  /* ============================================================
     MAIN FILTER
     ============================================================ */

  window.filterProductsAjax =
    function () {


      /*
       * Search
       */

      const searchInput =
        document.getElementById(
          'productSearchInput'
        );


      const query =
        searchInput
          ? searchInput.value
              .toLowerCase()
              .trim()
          : '';


      /*
       * Sort
       */

      const sortSelect =
        document.getElementById(
          'sortSelect'
        );


      const sortValue =
        sortSelect
          ? sortSelect.value
          : 'default';


      /*
       * Filter products
       */

      let filtered =
        productsData.filter(
          function (item) {


            /*
             * CATEGORY
             */

            const matchCategory =
              activeCategory ===
                'all' ||

              item.mainCategory ===
                activeCategory ||

              item.subCategory ===
                activeCategory ||

              (
                item.title &&
                item.title
                  .toLowerCase()
                  .includes(
                    activeCategory
                      .toLowerCase()
                  )
              );


            /*
             * PACKAGING
             */

            const matchPackaging =
              activePkgFilter ===
                'all' ||

              (
                Array.isArray(
                  item.packagingTypes
                ) &&

                item.packagingTypes.some(
                  function (pkg) {

                    return (
                      String(pkg)
                        .toLowerCase() ===
                      activePkgFilter
                        .toLowerCase()
                    );

                  }
                )
              );


            /*
             * SEARCH
             */

            const matchQuery =
              !query ||

              (
                item.title &&
                item.title
                  .toLowerCase()
                  .includes(query)
              ) ||

              (
                item.desc &&
                item.desc
                  .toLowerCase()
                  .includes(query)
              ) ||

              (
                item.subCategory &&
                item.subCategory
                  .toLowerCase()
                  .includes(query)
              ) ||

              (
                item.mainCategory &&
                item.mainCategory
                  .toLowerCase()
                  .includes(query)
              );


            return (
              matchCategory &&
              matchPackaging &&
              matchQuery
            );

          }
        );


      /*
       * SORT A-Z
       */

      if (
        sortValue ===
        'name-asc'
      ) {

        filtered.sort(
          function (a, b) {

            return (
              a.title || ''
            ).localeCompare(
              b.title || ''
            );

          }
        );

      }


      /*
       * SORT Z-A
       */

      else if (
        sortValue ===
        'name-desc'
      ) {

        filtered.sort(
          function (a, b) {

            return (
              b.title || ''
            ).localeCompare(
              a.title || ''
            );

          }
        );

      }


      /*
       * Render
       */

      renderProducts(
        filtered
      );

    };


  /* ============================================================
     RESET ALL FILTERS
     ============================================================ */

  window.resetAllFilters =
    function () {


      /*
       * Reset category
       */

      activeCategory =
        'all';


      /*
       * Reset packaging
       */

      activePkgFilter =
        'all';


      /*
       * Reset search
       */

      const searchInput =
        document.getElementById(
          'productSearchInput'
        );


      if (searchInput) {

        searchInput.value = '';

      }


      /*
       * Reset sorting
       */

      const sortSelect =
        document.getElementById(
          'sortSelect'
        );


      if (sortSelect) {

        sortSelect.value =
          'default';

      }


      /*
       * Reset packaging active
       */

      document
        .querySelectorAll(
          '.pkg-filter-item'
        )
        .forEach(
          function (el) {

            el.classList.remove(
              'active'
            );

          }
        );


      const firstPkg =
        document.querySelector(
          '.pkg-filter-item[data-pkg="all"]'
        );


      if (firstPkg) {

        firstPkg.classList.add(
          'active'
        );

      }


      /*
       * Rebuild category sidebar
       */

      renderCategorySidebar();


      /*
       * Reset heading
       */

      const heading =
        document.getElementById(
          'currentCategoryHeading'
        ) ||
        document.getElementById(
          'currentFilterHeading'
        );


      if (heading) {

        heading.innerText =
          'All Products';

      }


      /*
       * Render products
       */

      filterProductsAjax();

    };


  /* ============================================================
     QUICK VIEW MODAL
     ============================================================ */

  window.openProductModal =
    function (
      productId
    ) {

      const product =
        productsData.find(
          function (item) {

            return (
              String(item.id) ===
              String(productId)
            );

          }
        );


      if (!product) {
        return;
      }


      const modal =
        document.getElementById(
          'productModal'
        );


      if (!modal) {
        return;
      }


      const mImg =
        document.getElementById(
          'mImg'
        );


      const mBadge =
        document.getElementById(
          'mBadge'
        );


      const mTitle =
        document.getElementById(
          'mTitle'
        );


      const mDesc =
        document.getElementById(
          'mDesc'
        );


      const mPkg =
        document.getElementById(
          'mPkg'
        );


      const mStorage =
        document.getElementById(
          'mStorage'
        );


      if (mImg) {

        mImg.src =
          product.img || '';

      }


      if (mBadge) {

        mBadge.innerText =
          `${product.mainCategory || ''} > ${
            product.subCategory || ''
          }`;

      }


      if (mTitle) {

        mTitle.innerText =
          product.title || '';

      }


      if (mDesc) {

        mDesc.innerText =
          product.desc || '';

      }


      if (mPkg) {

        mPkg.innerText =
          product.pkg || '';

      }


      if (mStorage) {

        mStorage.innerText =
          product.storage || '';

      }


      modal.style.display =
        'flex';


      modal.setAttribute(
        'aria-hidden',
        'false'
      );

    };


  /* ============================================================
     CLOSE QUICK VIEW
     ============================================================ */

  window.closeProductModal =
    function () {

      const modal =
        document.getElementById(
          'productModal'
        );


      if (modal) {

        modal.style.display =
          'none';


        modal.setAttribute(
          'aria-hidden',
          'true'
        );

      }

    };


  /* ============================================================
     CLOSE MODAL OUTSIDE
     ============================================================ */

  window.addEventListener(
    'click',
    function (event) {

      const modal =
        document.getElementById(
          'productModal'
        );


      if (
        modal &&
        event.target === modal
      ) {

        closeProductModal();

      }

    }
  );


})();