<?php
$gallery_tag         = get_sub_field('gallery_tag');
$gallery_title       = get_sub_field('gallery_title');
$gallery_description = get_sub_field('gallery_description');
$gallery_items       = get_sub_field('gallery_items');
$gallery_button      = get_sub_field('gallery_button');
?>

<?php if ($gallery_items) : ?>

<section class="gallery-section"
         style="padding: 5rem 0; background: var(--white); border-top: 1px solid var(--border-light);">

    <div class="container">

        <!-- Section Header -->
        <div class="section-header text-center gsap-reveal"
             style="max-width: 750px; margin: 0 auto 2.5rem;">

            <?php if ($gallery_tag) : ?>
                <div class="section-tag" style="justify-content: center;">
                    <i class="fa-solid fa-camera"></i>
                    <?php echo esc_html($gallery_tag); ?>
                </div>
            <?php endif; ?>


            <?php if ($gallery_title) : ?>
                <h2 class="heading-serif"
                    style="font-size: 2.5rem; color: var(--primary);">

                    <?php echo esc_html($gallery_title); ?>

                </h2>
            <?php endif; ?>


            <?php if ($gallery_description) : ?>
                <p style="color: var(--text-muted); font-size: 1rem;">

                    <?php echo esc_html($gallery_description); ?>

                </p>
            <?php endif; ?>

        </div>


        <!-- Gallery -->
        <div class="gallery-grid gsap-grid-stagger"
             style="
                display: grid;
                grid-template-columns: repeat(auto-fill, minmax(260px, 1fr));
                gap: 1.5rem;
             ">

            <?php foreach ($gallery_items as $item) :

                $image    = $item['gallery_image'] ?? '';
                $title    = $item['gallery_title'] ?? '';
                $subtitle = $item['gallery_subtitle'] ?? '';

                if (!$image) {
                    continue;
                }

                $image_url = is_array($image)
                    ? ($image['url'] ?? '')
                    : wp_get_attachment_image_url($image, 'large');

                $image_alt = is_array($image)
                    ? ($image['alt'] ?? $title)
                    : get_post_meta($image, '_wp_attachment_image_alt', true);

                $full_image_url = is_array($image)
                    ? ($image['url'] ?? '')
                    : wp_get_attachment_image_url($image, 'full');

            ?>

                <div class="gallery-item light-sheen-card">

                    <img
                        src="<?php echo esc_url($image_url); ?>"
                        alt="<?php echo esc_attr($image_alt ?: $title); ?>"
                        class="lightbox-trigger"
                        loading="lazy"
                    />

                    <?php if ($title || $subtitle) : ?>

                        <div class="gallery-overlay">

                            <?php if ($title) : ?>
                                <h4>
                                    <?php echo esc_html($title); ?>
                                </h4>
                            <?php endif; ?>

                            <?php if ($subtitle) : ?>
                                <p>
                                    <?php echo esc_html($subtitle); ?>
                                </p>
                            <?php endif; ?>

                        </div>

                    <?php endif; ?>

                </div>

            <?php endforeach; ?>

        </div>


        <!-- View Gallery Button -->
        <?php if ($gallery_button) : ?>

            <div class="text-center" style="margin-top: 2rem;">

                <a
                    href="<?php echo esc_url($gallery_button['url']); ?>"
                    class="btn btn-outline-gold"
                    target="<?php echo esc_attr($gallery_button['target'] ?: '_self'); ?>"
                    <?php if (!empty($gallery_button['target']) && $gallery_button['target'] === '_blank') : ?>
                        rel="noopener"
                    <?php endif; ?>
                >

                    <?php echo esc_html($gallery_button['title']); ?>

                    <i class="fa-solid fa-arrow-right"></i>

                </a>

            </div>

        <?php endif; ?>

    </div>

</section>

<?php endif; ?>