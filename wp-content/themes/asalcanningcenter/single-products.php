<?php
/**
 * ============================================================
 * ASAL CANNING CENTER
 * SINGLE PRODUCTS TEMPLATE
 * ============================================================
 */

get_header();


// ============================================================
// PRODUCT ID
// ============================================================

$product_id = get_the_ID();


// ============================================================
// BASIC PRODUCT DATA
// ============================================================

$product_title = get_the_title();

$product_url = get_permalink();


// ============================================================
// ACF FIELDS
// ============================================================

$product_badge = get_field(
    'product_badge',
    $product_id
);

$short_description = get_field(
    'short_description',
    $product_id
);

$rating_text = get_field(
    'rating_text',
    $product_id
);

$packaging_size = get_field(
    'packaging_size',
    $product_id
);

$fssai_license = get_field(
    'fssai_license',
    $product_id
);

$storage_rules = get_field(
    'storage_rules',
    $product_id
);

$bulk_order_link = get_field(
    'bulk_order_link',
    $product_id
);

$sample_request_link = get_field(
    'sample_request_link',
    $product_id
);

$back_to_product_catalog_link = get_field(
    'back_to_product_catalog_link',
    $product_id
);


// ============================================================
// FEATURED IMAGE
// ============================================================

$featured_image = get_the_post_thumbnail_url(
    $product_id,
    'large'
);


// ============================================================
// PRODUCT GALLERY
// ============================================================

$product_gallery = get_field(
    'product_gallery',
    $product_id
);


// ============================================================
// PRODUCT CATEGORY
// ============================================================

$product_categories = get_the_terms(
    $product_id,
    'product_category'
);

$main_category = '';

$sub_category = '';

if (
    !empty($product_categories) &&
    !is_wp_error($product_categories)
) {

    foreach (
        $product_categories
        as $category
    ) {

        if (
            (int) $category->parent === 0
        ) {

            $main_category =
                $category->name;

            break;
        }
    }


    foreach (
        $product_categories
        as $category
    ) {

        if (
            (int) $category->parent !== 0
        ) {

            $sub_category =
                $category->name;

            break;
        }
    }


    if (
        empty($main_category) &&
        !empty($product_categories)
    ) {

        $main_category =
            $product_categories[0]->name;
    }
}


// ============================================================
// PACKAGING TYPE
// ============================================================

$packaging_terms = get_the_terms(
    $product_id,
    'packaging_type'
);

$packaging_names = [];

if (
    !empty($packaging_terms) &&
    !is_wp_error($packaging_terms)
) {

    foreach (
        $packaging_terms
        as $packaging_term
    ) {

        $packaging_names[] =
            $packaging_term->name;
    }
}

$packaging_text =
    implode(
        ' / ',
        $packaging_names
    );


// ============================================================
// PROCESSING METHOD
// ============================================================

$processing_method = get_field(
    'processing_method',
    $product_id
);


// ============================================================
// NUTRITIONAL VALUES
// ============================================================

$nutritional_values = get_field(
    'nutritional_values',
    $product_id
);

?>

<section
    class="story-section"
    style="padding: 2.25rem 0 3.5rem;"
>

    <div class="container">

        <!-- BACK TO PRODUCT CATALOG -->

        <div
            style="margin-bottom: 1.25rem;"
        >

            <a
                href="<?php echo esc_url(!empty($back_to_product_catalog_link) ? $back_to_product_catalog_link : home_url('/products/')); ?>"
                style="
                    color: var(--accent-hover);
                    font-weight: 700;
                    font-size: 0.875rem;
                "
            >

                <i
                    class="fa-solid fa-arrow-left"
                ></i>

                Back to Product Catalog

            </a>

        </div>


        <div
            class="story-grid"
            style="
                align-items: start;
                grid-template-columns: 1fr 1.15fr;
                gap: 3rem;
            "
        >


            <!-- ==================================================
                 LEFT COLUMN
                 ================================================== -->

            <div>


                <!-- MAIN IMAGE -->

                <div
                    class="
                        product-img-wrapper
                        light-sheen-card
                    "
                    style="
                        height: 440px;
                        border-radius: var(--radius-lg);
                        overflow: hidden;
                        border: 4px solid var(--white);
                        box-shadow: var(--shadow-lg);
                    "
                >

                    <?php

                    if ($featured_image) :

                    ?>

                        <img
                            src="<?php echo esc_url(
                                $featured_image
                            ); ?>"
                            alt="<?php echo esc_attr(
                                $product_title
                            ); ?>"
                            id="mainProductImg"
                            style="
                                width: 100%;
                                height: 100%;
                                object-fit: cover;
                            "
                        />

                    <?php

                    elseif (
                        !empty(
                            $product_gallery
                        )
                    ) :

                        $first_gallery_image =
                            $product_gallery[0];

                        if (
                            is_array(
                                $first_gallery_image
                            )
                        ) {

                            $first_image_url =
                                $first_gallery_image['url']
                                ?? '';

                            $first_image_alt =
                                $first_gallery_image['alt']
                                ?? $product_title;

                        } else {

                            $first_image_url =
                                wp_get_attachment_image_url(
                                    $first_gallery_image,
                                    'large'
                                );

                            $first_image_alt =
                                $product_title;
                        }

                    ?>

                        <img
                            src="<?php echo esc_url(
                                $first_image_url
                            ); ?>"
                            alt="<?php echo esc_attr(
                                $first_image_alt
                            ); ?>"
                            id="mainProductImg"
                            style="
                                width: 100%;
                                height: 100%;
                                object-fit: cover;
                            "
                        />

                    <?php

                    else :

                    ?>

                        <div
                            style="
                                width: 100%;
                                height: 100%;
                                display: flex;
                                align-items: center;
                                justify-content: center;
                                background: var(--cream);
                            "
                        >

                            <i
                                class="
                                    fa-solid
                                    fa-image
                                "
                                style="
                                    font-size: 3rem;
                                    opacity: 0.3;
                                "
                            ></i>

                        </div>

                    <?php

                    endif;

                    ?>

                </div>


                <!-- ==================================================
                     THUMBNAIL GALLERY & JOURNEY SHOWCASE
                     ================================================== -->

                <?php
                $gallery_slides = [];

                // 1. Featured image as first slide
                if ($featured_image) {
                    $gallery_slides[] = [
                        'image_url'     => $featured_image,
                        'thumbnail_url' => $featured_image,
                        'title'         => 'Primary Packshot: ' . $product_title,
                        'badge'         => 'Packshot',
                    ];
                }

                // 2. Curated Authentic Journey & Showcase Presets
                $product_slug = get_post_field('post_name', $product_id);
                $journey_presets = [
                    '18-herbs-medicinal-hair-oil-200ml-amber-bottle' => [
                        [
                            'image_url'     => home_url('/wp-content/uploads/2026/09/hair-oil-girl-showcase.jpg'),
                            'thumbnail_url' => home_url('/wp-content/uploads/2026/09/hair-oil-girl-showcase.jpg'),
                            'title'         => 'Showcase: Long Black Hair Wellness Result',
                            'badge'         => 'Showcase',
                        ],
                        [
                            'image_url'     => home_url('/wp-content/uploads/2026/09/herbal-raw-amla-turmeric.jpg'),
                            'thumbnail_url' => home_url('/wp-content/uploads/2026/09/herbal-raw-amla-turmeric.jpg'),
                            'title'         => 'Raw Botanicals: 18 Medicinal Herbs & Roots Sourcing',
                            'badge'         => 'Botanicals',
                        ],
                        [
                            'image_url'     => home_url('/wp-content/uploads/2026/09/herbal-amla-vat-boiling.jpg'),
                            'thumbnail_url' => home_url('/wp-content/uploads/2026/09/herbal-amla-vat-boiling.jpg'),
                            'title'         => 'Decoction: Traditional Copper Vat Slow Infusion',
                            'badge'         => 'Vat Infusion',
                        ],
                        [
                            'image_url'     => home_url('/wp-content/uploads/2026/09/herbal-amla-drink-banner.jpg'),
                            'thumbnail_url' => home_url('/wp-content/uploads/2026/09/herbal-amla-drink-banner.jpg'),
                            'title'         => 'Herbal Formulation: Ayurvedic Nectar Infusion',
                            'badge'         => 'Heritage',
                        ],
                    ],
                    'alphonso-kesar-mango-pulp-850g-can-1kg-pouch' => [
                        [
                            'image_url'     => home_url('/wp-content/uploads/2026/09/pulp-journey-sourcing-crates.jpg'),
                            'thumbnail_url' => home_url('/wp-content/uploads/2026/09/pulp-journey-sourcing-crates.jpg'),
                            'title'         => 'Journey Step 1: Farm Crate Sourcing & Mango Sorting',
                            'badge'         => 'Sourcing',
                        ],
                        [
                            'image_url'     => home_url('/wp-content/uploads/2026/09/pulp-journey-machine-extraction.jpg'),
                            'thumbnail_url' => home_url('/wp-content/uploads/2026/09/pulp-journey-machine-extraction.jpg'),
                            'title'         => 'Journey Step 2: Continuous SS-304 Pulper Extraction',
                            'badge'         => 'Extraction',
                        ],
                        [
                            'image_url'     => home_url('/wp-content/uploads/2026/09/pulp-journey-pouch-filling.jpg'),
                            'thumbnail_url' => home_url('/wp-content/uploads/2026/09/pulp-journey-pouch-filling.jpg'),
                            'title'         => 'Journey Step 3: Food-Grade Sealed Pouch Packaging Station',
                            'badge'         => 'Pouch Filling',
                        ],
                    ],
                    'jamun-pulp-500g-stand-up-pouch' => [
                        [
                            'image_url'     => home_url('/wp-content/uploads/2026/09/pulp-journey-jamun-extraction.jpg'),
                            'thumbnail_url' => home_url('/wp-content/uploads/2026/09/pulp-journey-jamun-extraction.jpg'),
                            'title'         => 'Journey Step 1: Pure Jamun Continuous Pulper Extraction',
                            'badge'         => 'Extraction',
                        ],
                        [
                            'image_url'     => home_url('/wp-content/uploads/2026/09/pulp-journey-sourcing-crates.jpg'),
                            'thumbnail_url' => home_url('/wp-content/uploads/2026/09/pulp-journey-sourcing-crates.jpg'),
                            'title'         => 'Journey Step 2: Wild Jamun Farm Sourcing & Inspection',
                            'badge'         => 'Sourcing',
                        ],
                        [
                            'image_url'     => home_url('/wp-content/uploads/2026/09/pulp-journey-pouch-filling.jpg'),
                            'thumbnail_url' => home_url('/wp-content/uploads/2026/09/pulp-journey-pouch-filling.jpg'),
                            'title'         => 'Journey Step 3: Aseptic Pouch Filling & Nitrogen Flush',
                            'badge'         => 'Pouch Filling',
                        ],
                    ],
                    'sitafal-pulp-custard-apple-500g-pouch-jar' => [
                        [
                            'image_url'     => home_url('/wp-content/uploads/2026/09/pulp-journey-machine-extraction.jpg'),
                            'thumbnail_url' => home_url('/wp-content/uploads/2026/09/pulp-journey-machine-extraction.jpg'),
                            'title'         => 'Journey Step 1: Continuous SS-304 Custard Apple Pulper',
                            'badge'         => 'Extraction',
                        ],
                        [
                            'image_url'     => home_url('/wp-content/uploads/2026/09/pulp-journey-sourcing-crates.jpg'),
                            'thumbnail_url' => home_url('/wp-content/uploads/2026/09/pulp-journey-sourcing-crates.jpg'),
                            'title'         => 'Journey Step 2: Fresh Custard Apple Crate Sorting',
                            'badge'         => 'Sourcing',
                        ],
                        [
                            'image_url'     => home_url('/wp-content/uploads/2026/09/pulp-journey-pouch-filling.jpg'),
                            'thumbnail_url' => home_url('/wp-content/uploads/2026/09/pulp-journey-pouch-filling.jpg'),
                            'title'         => 'Journey Step 3: Sealed Stand-up Pouch Packaging',
                            'badge'         => 'Pouch Filling',
                        ],
                    ],
                    'falsa-fruit-pulp-500g-sealed-pouch' => [
                        [
                            'image_url'     => home_url('/wp-content/uploads/2026/09/pulp-journey-jamun-extraction.jpg'),
                            'thumbnail_url' => home_url('/wp-content/uploads/2026/09/pulp-journey-jamun-extraction.jpg'),
                            'title'         => 'Journey Step 1: Stainless Steel Fruit Pulp Extraction',
                            'badge'         => 'Extraction',
                        ],
                        [
                            'image_url'     => home_url('/wp-content/uploads/2026/09/pulp-journey-sourcing-crates.jpg'),
                            'thumbnail_url' => home_url('/wp-content/uploads/2026/09/pulp-journey-sourcing-crates.jpg'),
                            'title'         => 'Journey Step 2: Wild Forest Berry Harvest Sorting',
                            'badge'         => 'Sourcing',
                        ],
                        [
                            'image_url'     => home_url('/wp-content/uploads/2026/09/pulp-journey-pouch-filling.jpg'),
                            'thumbnail_url' => home_url('/wp-content/uploads/2026/09/pulp-journey-pouch-filling.jpg'),
                            'title'         => 'Journey Step 3: Hermetic Pouch Dispensing Station',
                            'badge'         => 'Pouch Filling',
                        ],
                    ],
                    'artisanal-amla-chyawanprash-500g-glass-jar' => [
                        [
                            'image_url'     => home_url('/wp-content/uploads/2026/09/herbal-raw-amla-turmeric.jpg'),
                            'thumbnail_url' => home_url('/wp-content/uploads/2026/09/herbal-raw-amla-turmeric.jpg'),
                            'title'         => 'Journey Step 1: Wild Winter Amla & Raw Botanicals',
                            'badge'         => 'Raw Herbs',
                        ],
                        [
                            'image_url'     => home_url('/wp-content/uploads/2026/09/herbal-amla-vat-boiling.jpg'),
                            'thumbnail_url' => home_url('/wp-content/uploads/2026/09/herbal-amla-vat-boiling.jpg'),
                            'title'         => 'Journey Step 2: Desi Cow Ghee Open Vat Slow Roasting',
                            'badge'         => 'Vat Roasting',
                        ],
                        [
                            'image_url'     => home_url('/wp-content/uploads/2026/09/herbal-amla-drink-banner.jpg'),
                            'thumbnail_url' => home_url('/wp-content/uploads/2026/09/herbal-amla-drink-banner.jpg'),
                            'title'         => 'Journey Step 3: Classical Rasayana Health Formulation',
                            'badge'         => 'Showcase',
                        ],
                    ],
                    'amla-murabba-in-honey-syrup-500g-glass-jar' => [
                        [
                            'image_url'     => home_url('/wp-content/uploads/2026/09/herbal-raw-amla-turmeric.jpg'),
                            'thumbnail_url' => home_url('/wp-content/uploads/2026/09/herbal-raw-amla-turmeric.jpg'),
                            'title'         => 'Journey Step 1: Handpicked Banarasi Winter Amla',
                            'badge'         => 'Raw Amla',
                        ],
                        [
                            'image_url'     => home_url('/wp-content/uploads/2026/09/herbal-amla-vat-boiling.jpg'),
                            'thumbnail_url' => home_url('/wp-content/uploads/2026/09/herbal-amla-vat-boiling.jpg'),
                            'title'         => 'Journey Step 2: Slow Honey Infusion in Thermal Vats',
                            'badge'         => 'Honey Vats',
                        ],
                    ],
                ];

                // Append matching presets
                if (isset($journey_presets[$product_slug])) {
                    foreach ($journey_presets[$product_slug] as $preset) {
                        $existing_urls = array_column($gallery_slides, 'image_url');
                        if (!in_array($preset['image_url'], $existing_urls, true)) {
                            $gallery_slides[] = $preset;
                        }
                    }
                }

                // 3. Append any other ACF gallery images
                if (!empty($product_gallery)) {
                    foreach ($product_gallery as $gallery_image) {
                        $image_url = '';
                        $thumbnail_url = '';
                        $image_alt = $product_title;

                        if (is_array($gallery_image)) {
                            $image_url = $gallery_image['url'] ?? '';
                            $image_alt = $gallery_image['alt'] ?? $product_title;
                            $thumbnail_url = $gallery_image['sizes']['thumbnail'] ?? $image_url;
                        } else {
                            $image_url = wp_get_attachment_image_url($gallery_image, 'large');
                            $thumbnail_url = wp_get_attachment_image_url($gallery_image, 'thumbnail') ?: $image_url;
                            $meta_alt = get_post_meta($gallery_image, '_wp_attachment_image_alt', true);
                            if ($meta_alt) {
                                $image_alt = $meta_alt;
                            }
                        }

                        if ($image_url) {
                            $existing_urls = array_column($gallery_slides, 'image_url');
                            if (!in_array($image_url, $existing_urls, true)) {
                                $gallery_slides[] = [
                                    'image_url'     => $image_url,
                                    'thumbnail_url' => $thumbnail_url ?: $image_url,
                                    'title'         => $image_alt ?: $product_title,
                                    'badge'         => 'Gallery',
                                ];
                            }
                        }
                    }
                }

                if (!empty($gallery_slides)) :
                ?>

                    <div
                        class="gallery-meta-bar"
                        style="
                            display: flex;
                            align-items: center;
                            justify-content: space-between;
                            margin-top: 1.15rem;
                            margin-bottom: 0.65rem;
                            padding: 0.45rem 0.85rem;
                            background: var(--cream);
                            border-radius: var(--radius-sm);
                            border: 1px solid var(--border-light);
                            flex-wrap: wrap;
                            gap: 0.5rem;
                        "
                    >
                        <span
                            style="
                                font-size: 0.8rem;
                                font-weight: 700;
                                color: var(--accent-hover);
                                text-transform: uppercase;
                                letter-spacing: 0.05em;
                                display: flex;
                                align-items: center;
                                gap: 6px;
                            "
                        >
                            <i class="fa-solid fa-camera-retro"></i>
                            Journey &amp; Showcase Photos
                        </span>

                        <span
                            id="activeImageLabel"
                            style="
                                font-size: 0.8rem;
                                color: var(--text-dark);
                                font-weight: 600;
                                background: var(--white);
                                padding: 2px 10px;
                                border-radius: 12px;
                                border: 1px solid var(--border-light);
                            "
                        >
                            <?php echo esc_html($gallery_slides[0]['title'] ?? $product_title); ?>
                        </span>
                    </div>

                    <div
                        style="
                            display: flex;
                            gap: 0.85rem;
                            margin-top: 0.5rem;
                            flex-wrap: wrap;
                        "
                        id="thumbGallery"
                    >
                        <?php foreach ($gallery_slides as $index => $slide) : 
                            $is_active = ($index === 0);
                        ?>
                            <div
                                class="product-thumb <?php echo $is_active ? 'active' : ''; ?>"
                                role="button"
                                tabindex="0"
                                title="<?php echo esc_attr($slide['title']); ?>"
                                style="
                                    width: 82px;
                                    height: 82px;
                                    border-radius: 8px;
                                    overflow: hidden;
                                    cursor: pointer;
                                    border: 2px solid <?php echo $is_active ? 'var(--accent-hover)' : 'transparent'; ?>;
                                    position: relative;
                                    transition: all 0.2s ease;
                                    background: var(--cream);
                                    box-shadow: <?php echo $is_active ? '0 0 0 2px rgba(201, 141, 40, 0.35)' : 'var(--shadow-sm)'; ?>;
                                "
                                onclick="changeProductImage(this)"
                                onkeydown="if(event.key==='Enter'||event.key===' ')changeProductImage(this)"
                            >
                                <img
                                    src="<?php echo esc_url($slide['thumbnail_url']); ?>"
                                    alt="<?php echo esc_attr($slide['title']); ?>"
                                    data-full-image="<?php echo esc_url($slide['image_url']); ?>"
                                    data-caption="<?php echo esc_attr($slide['title']); ?>"
                                    style="
                                        width: 100%;
                                        height: 100%;
                                        object-fit: cover;
                                    "
                                />

                                <?php if (!empty($slide['badge'])) : ?>
                                    <span
                                        style="
                                            position: absolute;
                                            bottom: 0;
                                            left: 0;
                                            right: 0;
                                            background: rgba(20, 24, 31, 0.8);
                                            color: #fff;
                                            font-size: 0.65rem;
                                            font-weight: 600;
                                            text-align: center;
                                            padding: 1px 2px;
                                            line-height: 1.2;
                                            white-space: nowrap;
                                            overflow: hidden;
                                            text-overflow: ellipsis;
                                        "
                                    >
                                        <?php echo esc_html($slide['badge']); ?>
                                    </span>
                                <?php endif; ?>
                            </div>
                        <?php endforeach; ?>
                    </div>

                <?php
                endif;
                ?>


                <!-- ==================================================
                     PROCESSING METHOD
                     ================================================== -->

                <div
                    style="
                        background: var(--white);
                        padding: 1.5rem;
                        border-radius: var(--radius-md);
                        border: 1px solid var(--border-light);
                        margin-top: 1.5rem;
                        box-shadow: var(--shadow-sm);
                    "
                >

                    <div
                        style="
                            display: flex;
                            align-items: center;
                            gap: 8px;
                            margin-bottom: 0.85rem;
                        "
                    >

                        <i
                            class="fa-solid fa-gear"
                            style="
                                color: var(--accent-hover);
                                font-size: 1.1rem;
                            "
                        ></i>

                        <h3
                            style="
                                font-family: var(--font-serif);
                                font-size: 1.3rem;
                                color: var(--primary);
                                font-weight: 700;
                            "
                        >
                            Processing & Preservation Method
                        </h3>

                    </div>


                    <div
                        id="methodStepsContainer"
                    >

                        <?php

                        if (
                            !empty(
                                $processing_method
                            )
                        ) :

                            $step_number = 1;

                            foreach (
                                $processing_method
                                as $step
                            ) :

                                $step_title =
                                    $step['step_title']
                                    ?? '';

                                $step_description =
                                    $step['step_description']
                                    ?? '';

                        ?>

                                <div
                                    class="method-step"
                                >

                                    <span
                                        class="method-step-num"
                                    >
                                        <?php
                                        echo esc_html(
                                            $step_number
                                        );
                                        ?>
                                    </span>

                                    <div
                                        style="
                                            font-size: 0.85rem;
                                        "
                                    >

                                        <?php

                                        if (
                                            $step_title
                                        ) :

                                        ?>

                                            <strong>
                                                <?php
                                                echo esc_html(
                                                    $step_title
                                                );
                                                ?>:
                                            </strong>

                                        <?php

                                        endif;

                                        ?>

                                        <?php
                                        echo esc_html(
                                            $step_description
                                        );
                                        ?>

                                    </div>

                                </div>

                        <?php

                                $step_number++;

                            endforeach;

                        else :

                        ?>

                            <div
                                class="method-step"
                            >

                                <span
                                    class="method-step-num"
                                >
                                    1
                                </span>

                                <div
                                    style="
                                        font-size: 0.85rem;
                                    "
                                >
                                    Processing information
                                    will be updated soon.
                                </div>

                            </div>

                        <?php

                        endif;

                        ?>

                    </div>

                </div>

            </div>


            <!-- ==================================================
                 RIGHT COLUMN
                 ================================================== -->

            <div
                class="story-content"
            >


                <!-- CATEGORY -->

                <div
                    class="section-tag"
                    style="
                        margin-bottom: 0.5rem;
                    "
                >

                    <i
                        class="fa-solid fa-star"
                    ></i>

                    <?php

                    if ($product_badge) {

                        echo esc_html(
                            $product_badge
                        );

                    } elseif (
                        $main_category
                    ) {

                        echo esc_html(
                            $main_category
                        );

                    } else {

                        echo 'Pure Natural Preserve';

                    }

                    ?>

                </div>


                <!-- TITLE -->

                <h1
                    class="heading-serif"
                    style="
                        font-size: 2.5rem;
                        color: var(--primary);
                        margin-bottom: 0.5rem;
                        line-height: 1.15;
                    "
                >

                    <?php
                    echo esc_html(
                        $product_title
                    );
                    ?>

                </h1>


                <!-- RATING -->

                <?php

                if ($rating_text) :

                ?>

                    <div
                        class="product-rating"
                        style="
                            margin-bottom: 1rem;
                        "
                    >

                        <span
                            class="rating-stars"
                        >
                            ★★★★★
                        </span>

                        <span
                            class="rating-text"
                        >
                            <?php
                            echo esc_html(
                                $rating_text
                            );
                            ?>
                        </span>

                    </div>

                <?php

                endif;

                ?>


                <!-- SHORT DESCRIPTION -->

                <?php

                if (
                    $short_description
                ) :

                ?>

                    <p
                        style="
                            font-size: 1rem;
                            color: var(--text-muted);
                            margin-bottom: 1.25rem;
                        "
                    >

                        <?php
                        echo esc_html(
                            $short_description
                        );
                        ?>

                    </p>

                <?php

                endif;

                ?>


                <!-- ==================================================
                     SPECIFICATIONS
                     ================================================== -->

                <div
                    style="
                        background: var(--white);
                        padding: 1.35rem;
                        border-radius: var(--radius-md);
                        border: 1px solid var(--border-light);
                        margin-bottom: 1.5rem;
                        box-shadow: var(--shadow-sm);
                    "
                >

                    <h4
                        style="
                            font-family: var(--font-serif);
                            font-size: 1.25rem;
                            color: var(--primary);
                            margin-bottom: 0.75rem;
                        "
                    >
                        Technical Specifications
                    </h4>


                    <ul
                        style="
                            display: grid;
                            grid-template-columns: 1fr 1fr;
                            gap: 0.75rem;
                            font-size: 0.85rem;
                        "
                        id="detailSpecsList"
                    >


                        <!-- FSSAI -->

                        <?php

                        if (
                            $fssai_license
                        ) :

                        ?>

                            <li>

                                <strong>
                                    FSSAI License:
                                </strong>

                                <?php
                                echo esc_html(
                                    $fssai_license
                                );
                                ?>

                            </li>

                        <?php

                        endif;

                        ?>


                        <!-- PACKAGING -->

                        <li
                            id="specPkg"
                        >

                            <strong>
                                Packaging Option:
                            </strong>

                            <?php

                            if (
                                $packaging_text
                            ) {

                                echo esc_html(
                                    $packaging_text
                                );

                            } else {

                                echo 'Not specified';

                            }

                            ?>

                        </li>


                        <!-- PACKAGING SIZE -->

                        <li
                            id="specOption"
                        >

                            <strong>
                                Specification:
                            </strong>

                            <?php

                            if (
                                $packaging_size
                            ) {

                                echo esc_html(
                                    $packaging_size
                                );

                            } else {

                                echo 'Not specified';

                            }

                            ?>

                        </li>


                        <!-- STORAGE -->

                        <li
                            id="specStorage"
                        >

                            <strong>
                                Storage Rules:
                            </strong>

                            <?php

                            if (
                                $storage_rules
                            ) {

                                echo esc_html(
                                    $storage_rules
                                );

                            } else {

                                echo 'Not specified';

                            }

                            ?>

                        </li>


                    </ul>


                    <!-- ==================================================
                         NUTRITION
                         ================================================== -->

                    <?php

                    if (
                        !empty(
                            $nutritional_values
                        )
                    ) :

                    ?>

                        <h4
                            style="
                                font-family: var(--font-serif);
                                font-size: 1.25rem;
                                color: var(--primary);
                                margin-top: 1.25rem;
                                margin-bottom: 0.4rem;
                            "
                        >
                            Nutritional Values (Per 100g)
                        </h4>


                        <table
                            class="nutrition-table"
                            id="nutritionTable"
                        >

                            <thead>

                                <tr>

                                    <th>
                                        Nutrient Parameter
                                    </th>

                                    <th>
                                        Value
                                    </th>

                                </tr>

                            </thead>


                            <tbody>

                                <?php

                                foreach (
                                    $nutritional_values
                                    as $nutrition
                                ) :

                                    $nutrient_name =
                                        $nutrition['nutrient_name']
                                        ?? '';

                                    $nutrient_value =
                                        $nutrition['nutrient_value']
                                        ?? '';

                                    if (
                                        !$nutrient_name
                                    ) {
                                        continue;
                                    }

                                ?>

                                    <tr>

                                        <td>
                                            <?php
                                            echo esc_html(
                                                $nutrient_name
                                            );
                                            ?>
                                        </td>

                                        <td>
                                            <?php
                                            echo esc_html(
                                                $nutrient_value
                                            );
                                            ?>
                                        </td>

                                    </tr>

                                <?php

                                endforeach;

                                ?>

                            </tbody>

                        </table>

                    <?php

                    endif;

                    ?>

                </div>


                <!-- ==================================================
                     ACTION BUTTONS
                     ================================================== -->

                <div
                    class="hero-cta-group"
                >


                    <?php

                    if (
                        $bulk_order_link
                    ) :

                    ?>

                        <a
                            href="<?php echo esc_url(
                                $bulk_order_link
                            ); ?>"
                            class="btn btn-accent"
                        >

                            Inquire Bulk Batch Order

                            <i
                                class="fa-solid fa-paper-plane"
                            ></i>

                        </a>

                    <?php

                    else :

                    ?>

                        <a
                            href="<?php echo esc_url(
                                add_query_arg([
                                    'product' => $product_title,
                                    'type'    => 'bulk',
                                ], home_url('/contact-us/#contactFormWrap'))
                            ); ?>"
                            class="btn btn-accent"
                        >

                            Inquire Bulk Batch Order

                            <i
                                class="fa-solid fa-paper-plane"
                            ></i>

                        </a>

                    <?php

                    endif;


                    if (
                        $sample_request_link
                    ) :

                    ?>

                        <a
                            href="<?php echo esc_url(
                                $sample_request_link
                            ); ?>"
                            class="btn btn-outline-gold"
                        >

                            Request Sample

                            <i
                                class="fa-solid fa-jar"
                            ></i>

                        </a>

                    <?php

                    else :

                    ?>

                        <a
                            href="<?php echo esc_url(
                                add_query_arg([
                                    'product' => $product_title,
                                    'type'    => 'sample',
                                ], home_url('/contact-us/#contactFormWrap'))
                            ); ?>"
                            class="btn btn-outline-gold"
                        >

                            Request Sample

                            <i
                                class="fa-solid fa-jar"
                            ></i>

                        </a>

                    <?php

                    endif;

                    ?>

                </div>

            </div>

        </div>

    </div>

</section>


<!-- ============================================================
     RELATED PRODUCTS
     ============================================================ -->

<section
    class="products-section"
    style="
        background-color: var(--cream-dark);
        padding: 3.5rem 0;
    "
>

    <div class="container">


        <div
            class="section-header"
        >

            <div
                class="section-tag"
            >

                <i
                    class="fa-solid fa-layer-group"
                ></i>

                Similar Formulations

            </div>


            <h2>
                Related Preserves & Concentrates
            </h2>


            <p>
                Explore other fruit pulps and natural
                concentrates processed under the same
                cottage hygiene standards.
            </p>

        </div>


        <div
            class="product-cards-wrap"
            id="relatedProductsGrid"
            style="
                display: grid;
                grid-template-columns:
                    repeat(
                        auto-fill,
                        minmax(260px, 1fr)
                    );
                gap: 24px;
            "
        >


            <?php

            /*
             * ==================================================
             * RELATED PRODUCTS
             * ==================================================
             *
             * First priority:
             * Same child category
             *
             * Second priority:
             * Same parent category
             *
             * Exclude current product
             */

            $related_args = [

                'post_type' =>
                    'products',

                'post_status' =>
                    'publish',

                'posts_per_page' =>
                    4,

                'post__not_in' => [
                    $product_id
                ],

                'orderby' =>
                    'date',

                'order' =>
                    'DESC',

            ];


            /*
             * Use same child category
             */

            if (
                $sub_category
            ) {

                $related_args['tax_query'] = [

                    [
                        'taxonomy' =>
                            'product_category',

                        'field' =>
                            'name',

                        'terms' => [
                            $sub_category
                        ],

                    ]

                ];

            }

            /*
             * Otherwise same parent category
             */

            elseif (
                $main_category
            ) {

                $related_args['tax_query'] = [

                    [
                        'taxonomy' =>
                            'product_category',

                        'field' =>
                            'name',

                        'terms' => [
                            $main_category
                        ],

                    ]

                ];

            }


            $related_query =
                new WP_Query(
                    $related_args
                );


            /*
             * If fewer than 4 results
             * found in child category,
             * try parent category.
             */

            if (
                $related_query->post_count < 4 &&
                $main_category &&
                $sub_category
            ) {

                wp_reset_postdata();


                $related_args['tax_query'] = [

                    [
                        'taxonomy' =>
                            'product_category',

                        'field' =>
                            'name',

                        'terms' => [
                            $main_category
                        ],

                    ]

                ];


                $related_query =
                    new WP_Query(
                        $related_args
                    );

            }


            if (
                $related_query->have_posts()
            ) :

                while (
                    $related_query->have_posts()
                ) :

                    $related_query->the_post();


                    $related_id =
                        get_the_ID();


                    $related_badge =
                        get_field(
                            'product_badge',
                            $related_id
                        );


                    $related_desc =
                        get_field(
                            'short_description',
                            $related_id
                        );


                    $related_img =
                        get_the_post_thumbnail_url(
                            $related_id,
                            'medium'
                        );


                    $related_packaging =
                        get_field(
                            'packaging_size',
                            $related_id
                        );

            ?>


                    <div
                        class="
                            product-box
                            light-sheen-card
                        "
                        style="
                            cursor: pointer;
                        "
                        onclick="
                            window.location.href='<?php
                            echo esc_url(
                                get_permalink(
                                    $related_id
                                )
                            );
                            ?>';
                        "
                    >


                        <div
                            class="img-holder"
                        >

                            <?php

                            if (
                                $related_badge
                            ) :

                            ?>

                                <span
                                    class="item-tag"
                                >

                                    <?php
                                    echo esc_html(
                                        $related_badge
                                    );
                                    ?>

                                </span>

                            <?php

                            endif;


                            if (
                                $related_img
                            ) :

                            ?>

                                <img
                                    src="<?php
                                    echo esc_url(
                                        $related_img
                                    );
                                    ?>"
                                    alt="<?php
                                    echo esc_attr(
                                        get_the_title(
                                            $related_id
                                        )
                                    );
                                    ?>"
                                    loading="lazy"
                                />

                            <?php

                            endif;

                            ?>

                        </div>


                        <div
                            class="box-info"
                        >

                            <h4>

                                <?php
                                echo esc_html(
                                    get_the_title(
                                        $related_id
                                    )
                                );
                                ?>

                            </h4>


                            <div
                                class="product-rating"
                            >

                                <span
                                    class="rating-stars"
                                >
                                    ★★★★★
                                </span>


                                <?php

                                $related_rating =
                                    get_field(
                                        'rating_text',
                                        $related_id
                                    );


                                if (
                                    $related_rating
                                ) :

                                ?>

                                    <span
                                        class="rating-text"
                                    >

                                        <?php
                                        echo esc_html(
                                            $related_rating
                                        );
                                        ?>

                                    </span>

                                <?php

                                endif;

                                ?>

                            </div>


                            <?php

                            if (
                                $related_desc
                            ) :

                            ?>

                                <p>

                                    <?php

                                    echo esc_html(
                                        wp_trim_words(
                                            $related_desc,
                                            16,
                                            '...'
                                        )
                                    );

                                    ?>

                                </p>

                            <?php

                            endif;

                            ?>


                            <div
                                style="
                                    display: flex;
                                    justify-content: space-between;
                                    align-items: center;
                                    margin-top: auto;
                                    padding-top: 10px;
                                    border-top: 1px solid var(--border-light);
                                "
                            >

                                <?php

                                if (
                                    $related_packaging
                                ) :

                                ?>

                                    <span
                                        style="
                                            font-size: 0.78rem;
                                            font-weight: 700;
                                            color: var(--terracotta);
                                        "
                                    >

                                        <i
                                            class="
                                                fa-solid
                                                fa-box-open
                                            "
                                        ></i>

                                        <?php
                                        echo esc_html(
                                            $related_packaging
                                        );
                                        ?>

                                    </span>

                                <?php

                                endif;

                                ?>


                                <a
                                    href="<?php
                                    echo esc_url(
                                        get_permalink(
                                            $related_id
                                        )
                                    );
                                    ?>"
                                    class="view-btn"
                                    onclick="
                                        event.stopPropagation();
                                    "
                                >

                                    View Details

                                    <i
                                        class="
                                            fa-solid
                                            fa-arrow-right
                                        "
                                    ></i>

                                </a>

                            </div>

                        </div>

                    </div>


            <?php

                endwhile;

                wp_reset_postdata();

            else :

            ?>


                <div
                    style="
                        grid-column: 1 / -1;
                        text-align: center;
                        padding: 2rem;
                    "
                >

                    <p>
                        No related products found.
                    </p>

                </div>


            <?php

            endif;

            ?>

        </div>

    </div>

</section>


<!-- ============================================================
     THUMBNAIL IMAGE JS
     ============================================================ -->

<script>

function changeProductImage(element) {

    const image =
        element.querySelector('img');

    const mainImage =
        document.getElementById(
            'mainProductImg'
        );

    const label =
        document.getElementById(
            'activeImageLabel'
        );

    if (
        !image ||
        !mainImage
    ) {
        return;
    }

    const fullImage =
        image.getAttribute(
            'data-full-image'
        );

    const caption =
        image.getAttribute(
            'data-caption'
        ) || image.getAttribute('alt');

    if (fullImage) {
        mainImage.style.opacity = '0.35';
        mainImage.style.transition = 'opacity 0.2s ease-in-out';
        setTimeout(function() {
            mainImage.src = fullImage;
            if (caption) {
                mainImage.alt = caption;
            }
            mainImage.style.opacity = '1';
        }, 120);
    }

    if (label && caption) {
        label.textContent = caption;
    }

    /*
     * Remove active
     */
    document
        .querySelectorAll(
            '.product-thumb'
        )
        .forEach(
            function (thumb) {
                thumb.classList.remove('active');
                thumb.style.border =
                    '2px solid transparent';
                thumb.style.boxShadow =
                    'var(--shadow-sm)';
            }
        );

    /*
     * Add active
     */
    element.classList.add('active');
    element.style.border =
        '2px solid var(--accent-hover)';
    element.style.boxShadow =
        '0 0 0 2px rgba(201, 141, 40, 0.35)';
}

</script>


<?php

get_footer();