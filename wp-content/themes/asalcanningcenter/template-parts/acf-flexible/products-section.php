<?php
/**
 * Featured Products Section
 *
 * ACF Section Fields:
 * - sub_title
 * - title
 * - description
 * - featured_products
 * - view_more_button
 */

$sub_title        = get_sub_field('sub_title');
$title            = get_sub_field('title');
$description      = get_sub_field('description');
$featured_products = get_sub_field('featured_products');
$view_more_button = get_sub_field('view_more_button');
?>

<section class="products-section">
    <div class="container">

        <!-- Section Header -->
        <div class="section-header text-center gsap-reveal">

            <?php if ($sub_title) : ?>
                <div class="section-tag" style="justify-content: center;">
                    <i class="fa-solid fa-jar"></i>

                    <?php echo esc_html($sub_title); ?>
                </div>
            <?php endif; ?>


            <?php if ($title) : ?>
                <h2 class="heading-serif"
                    style="font-size: 2.5rem; color: var(--primary);">

                    <?php echo esc_html($title); ?>

                </h2>
            <?php endif; ?>


            <?php if ($description) : ?>
                <p style="color: var(--text-muted); font-size: 1rem;">

                    <?php echo wp_kses_post($description); ?>

                </p>
            <?php endif; ?>

        </div>


        <!-- Products -->
        <?php if ($featured_products) : ?>

            <div class="product-cards-wrap gsap-grid-stagger"
                 style="
                    display: grid;
                    grid-template-columns: repeat(auto-fill, minmax(260px, 1fr));
                    gap: 24px;
                 ">

                <?php foreach ($featured_products as $product) : ?>

                    <?php
                    /*
                     * Post Object can return:
                     * - WP_Post object
                     * - Post ID
                     */

                    if (is_object($product)) {
                        $product_id = $product->ID;
                    } else {
                        $product_id = $product;
                    }


                    /*
                     * Make sure this is a valid product post
                     */
                    if (!$product_id) {
                        continue;
                    }


                    /*
                     * --------------------------------
                     * Product Basic Information
                     * --------------------------------
                     */

                    $product_title = get_the_title($product_id);

                    $product_url = get_permalink($product_id);


                    /*
                     * --------------------------------
                     * Product Featured Image
                     * --------------------------------
                     */

                    $product_image = get_the_post_thumbnail_url(
                        $product_id,
                        'large'
                    );


                    /*
                     * --------------------------------
                     * Product ACF Fields
                     * --------------------------------
                     */

                    $product_badge = get_field(
                        'product_badge',
                        $product_id
                    );

                    $product_rating = get_field(
                        'rating_text',
                        $product_id
                    );

                    $product_description = get_field(
                        'short_description',
                        $product_id
                    );

                    $product_packaging = get_field(
                        'packaging_size',
                        $product_id
                    );
                    ?>


                    <div class="product-box light-sheen-card">

                        <!-- Product Image -->
                        <div class="img-holder">

                            <?php if ($product_badge) : ?>

                                <span class="item-tag">
                                    <?php echo esc_html($product_badge); ?>
                                </span>

                            <?php endif; ?>


                            <?php if ($product_image) : ?>

                                <a href="<?php echo esc_url($product_url); ?>">

                                    <img
                                        src="<?php echo esc_url($product_image); ?>"
                                        alt="<?php echo esc_attr($product_title); ?>"
                                        loading="lazy"
                                    />

                                </a>

                            <?php endif; ?>

                        </div>


                        <!-- Product Information -->
                        <div class="box-info">

                            <?php if ($product_title) : ?>

                                <h4>
                                    <a href="<?php echo esc_url($product_url); ?>">
                                        <?php echo esc_html($product_title); ?>
                                    </a>
                                </h4>

                            <?php endif; ?>


                            <!-- Rating -->
                            <?php if ($product_rating ) : ?>

                                <div class="product-rating">

                                    <span class="rating-stars">
                                        ★★★★★
                                    </span>

                                    <span class="rating-text">

                                        <?php if ($product_rating) : ?>

                                            <?php echo esc_html($product_rating); ?>

                                        <?php endif; ?>

                                    </span>

                                </div>

                            <?php endif; ?>


                            <!-- Description -->
                            <?php if ($product_description) : ?>

                                <p>
                                    <?php echo wp_kses_post($product_description); ?>
                                </p>

                            <?php endif; ?>


                            <!-- Bottom Information -->
                            <div
                                style="
                                    display: flex;
                                    justify-content: space-between;
                                    align-items: center;
                                    gap: 15px;
                                    margin-top: auto;
                                    padding-top: 10px;
                                    border-top: 1px solid var(--border-light);
                                "
                            >

                                <?php if ($product_packaging) : ?>

                                    <span
                                        style="
                                            font-size: 0.78rem;
                                            font-weight: 700;
                                            color: var(--terracotta);
                                        "
                                    >

                                        <i class="fa-solid fa-box-open"></i>

                                        <?php echo esc_html($product_packaging); ?>

                                    </span>

                                <?php endif; ?>


                                <a
                                    href="<?php echo esc_url($product_url); ?>"
                                    class="view-btn"
                                >
                                    View Details

                                    <i class="fa-solid fa-arrow-right"></i>
                                </a>

                            </div>

                        </div>

                    </div>


                <?php endforeach; ?>

            </div>

        <?php else : ?>

            <p class="text-center">
                No featured products found.
            </p>

        <?php endif; ?>


        <!-- View More Button -->
        <?php if ($view_more_button) : ?>

            <div class="text-center" style="margin-top: 2.5rem;">

                <a
                    href="<?php echo esc_url($view_more_button['url']); ?>"
                    class="btn btn-outline-gold"
                    target="<?php echo esc_attr($view_more_button['target'] ?: '_self'); ?>"
                >

                    <?php
                    echo esc_html(
                        $view_more_button['title']
                    );
                    ?>

                    <i class="fa-solid fa-arrow-right"></i>

                </a>

            </div>

        <?php endif; ?>

    </div>
</section>