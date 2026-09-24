<section class="hero-section catalog-hero-section">
    <div class="ambient-light-beam ambient-light-gold"></div>

    <div class="container text-center"
         style="max-width: 800px; margin: 0 auto; position: relative; z-index: 2;">

        <div class="hero-kicker"
             style="justify-content: center; margin-bottom: 0.75rem;">

            <span class="hero-kicker-line"></span>

            <?php
            $catalog_hero_kicker = get_sub_field('catalog_hero_kicker');

            if ($catalog_hero_kicker) {
                echo esc_html($catalog_hero_kicker);
            }
            ?>

        </div>


        <h1 class="heading-serif"
            style="font-size: 2.8rem; color: var(--primary, #14181F); margin-bottom: 0.5rem; font-weight: 700;">

            <?php
            $catalog_hero_title = get_sub_field('catalog_hero_title');

            if ($catalog_hero_title) {
                echo esc_html($catalog_hero_title);
            }
            ?>

        </h1>


        <p style="color: var(--text-muted, #626D7A);
                  font-size: 1.05rem;
                  max-width: 650px;
                  margin: 0 auto 1.5rem;">

            <?php
            $catalog_hero_description = get_sub_field('catalog_hero_description');

            if ($catalog_hero_description) {
                echo esc_html($catalog_hero_description);
            }
            ?>

        </p>


        <!-- Search -->
        <div class="catalog-search-bar"
             style="max-width: 650px; margin: 0 auto; position: relative;">

            <i class="fa-solid fa-magnifying-glass"
               style="position: absolute;
                      left: 20px;
                      top: 50%;
                      transform: translateY(-50%);
                      color: var(--accent-hover, #C98D28);
                      font-size: 1.1rem;">
            </i>

            <input
                type="text"
                id="productSearchInput"
                placeholder="Search flagship products..."
                aria-label="Search flagship products"
                onkeyup="filterProductsAjax()"
            >

        </div>

    </div>
</section>

<!-- Main Catalog Layout with Dynamic Category Hierarchy & Packaging Filters -->
<section class="catalog-wrapper">

    <div class="container catalog-grid">

        <!-- Left Sidebar: Category Hierarchy & Filter Catalog -->
        <aside class="category-tree-panel">

            <div class="tree-title">

                <span>
                    <i class="fa-solid fa-sliders"></i>
                    Filter Catalog
                </span>

                <button
                    onclick="resetAllFilters()"
                    class="reset-filter-btn"
                >
                    Reset All
                </button>

            </div>


            <!-- All Categories Section -->
            <div
                class="filter-group"
                style="margin-bottom: 1.5rem;"
            >

                <span class="filter-group-label">
                    ALL CATEGORIES
                </span>

                <ul
                    class="tree-list"
                    id="categoryFilterList"
                >
                    <!-- Rendered by JavaScript -->
                </ul>

            </div>


            <!-- Packaging Format Filter Section -->
            <div
                class="filter-group"
                style="
                    padding-top: 1rem;
                    border-top: 1px solid var(--border-light);
                "
            >

                <span class="filter-group-label">
                    PACKAGING TYPE
                </span>


                <ul class="filter-pkg-list">

                    <!-- ALL PACKAGING -->
                    <li>

                        <div
                            class="pkg-filter-item active"
                            data-pkg="all"
                            onclick="selectPackagingFilter('all', this)"
                        >

                            <i class="fa-solid fa-border-all"></i>

                            <span>
                                All Packaging Types
                            </span>

                        </div>

                    </li>


                    <?php

                    /*
                     * Get packaging taxonomy terms
                     */

                    $packaging_terms = get_terms([
                        'taxonomy'   => 'packaging_type',
                        'hide_empty' => true,
                        'orderby'    => 'name',
                        'order'      => 'ASC',
                    ]);


                    if (
                        !is_wp_error($packaging_terms) &&
                        !empty($packaging_terms)
                    ) :

                        foreach ($packaging_terms as $packaging_term) :

                            $pkg_name = $packaging_term->name;
                            $pkg_slug = $packaging_term->slug;


                            /*
                             * Default icon
                             */
                            $pkg_icon = 'fa-box';


                            /*
                             * Packaging icon
                             */
                            switch ($pkg_slug) {

                                case 'can':

                                    $pkg_icon =
                                        'fa-box-archive';

                                    break;


                                case 'pouch':

                                    $pkg_icon =
                                        'fa-box';

                                    break;


                                case 'jar':

                                    $pkg_icon =
                                        'fa-jar';

                                    break;


                                case 'bottle':

                                    $pkg_icon =
                                        'fa-bottle-droplet';

                                    break;

                            }

                            ?>

                            <li>

                                <div
                                    class="pkg-filter-item"
                                    data-pkg="<?php echo esc_attr($pkg_name); ?>"
                                    onclick="selectPackagingFilter('<?php echo esc_js($pkg_name); ?>', this)"
                                >

                                    <i
                                        class="fa-solid <?php echo esc_attr($pkg_icon); ?>"
                                    ></i>

                                    <span>
                                        <?php
                                        echo esc_html($pkg_name);
                                        ?>
                                    </span>

                                </div>

                            </li>

                            <?php

                        endforeach;

                    endif;

                    ?>

                </ul>

            </div>

        </aside>


        <!-- Right Products Grid Container -->
        <div>

            <div class="catalog-header">

                <div>

                    <h2 id="currentCategoryHeading">
                        All Products
                    </h2>

                    <p id="productCountText">
                        Showing all available formulations and pulp extractions
                    </p>

                </div>


                <div
                    style="
                        display: flex;
                        gap: 1rem;
                        align-items: center;
                    "
                >

                    <select
                        id="sortSelect"
                        class="form-control"
                        aria-label="Sort products by"
                        style="
                            width: auto;
                            padding: 0.45rem 1rem;
                            border-radius: 20px;
                            border: 1px solid var(--border-medium);
                            font-size: 0.85rem;
                            background: var(--white);
                            cursor: pointer;
                        "
                        onchange="filterProductsAjax()"
                    >

                        <option value="default">
                            Sort by: Default
                        </option>

                        <option value="name-asc">
                            Name: A to Z
                        </option>

                        <option value="name-desc">
                            Name: Z to A
                        </option>

                    </select>


                    <button
                        class="btn btn-outline-gold btn-sm"
                        onclick="resetAllFilters()"
                        style="
                            font-size: 0.8rem;
                            padding: 6px 14px;
                            border-radius: 20px;
                            cursor: pointer;
                        "
                    >
                        Reset Filters
                    </button>

                </div>

            </div>


            <!-- Products -->
            <div
                class="product-cards-wrap"
                id="productsGrid"
            >
                <!-- Rendered dynamically -->
            </div>


        </div>

    </div>

</section>