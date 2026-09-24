<!-- Gallery Grid Section -->
<section class="gallery-section" style="padding-top: 2.5rem; padding-bottom: 4rem;">
  <div class="container">

    <!-- Category Filter Tabs — segmented pill switcher -->
    <div class="filter-tabs gsap-reveal" style="margin-bottom: 2rem;">

      <button class="filter-btn active" data-filter="all">
        <i class="fa-solid fa-border-all"></i> All Media
      </button>

      <button class="filter-btn" data-filter="videos">
        <i class="fa-brands fa-youtube"></i> Videos
      </button>

      <button class="filter-btn" data-filter="facility">
        <i class="fa-solid fa-industry"></i> Facility &amp; Machinery
      </button>

      <button class="filter-btn" data-filter="operations">
        <i class="fa-solid fa-blender"></i> Extraction &amp; Batching
      </button>

      <button class="filter-btn" data-filter="products">
        <i class="fa-solid fa-jar"></i> Preserves &amp; Products
      </button>

      <button class="filter-btn" data-filter="workshops">
        <i class="fa-solid fa-users-gear"></i> Training Seminars
      </button>

    </div>


    <!-- Authentic Gallery Grid -->
    <div class="gallery-grid gsap-grid-stagger">

      <?php
      $gallery_items = get_sub_field('gallery_items');

      if ($gallery_items) :

        foreach ($gallery_items as $item) :

          $gallery_image       = $item['gallery_image'] ?? '';
          $media_type          = $item['media_type'] ?? 'image';
          $youtube_id          = $item['youtube_id'] ?? '';
          $gallery_category    = $item['gallery_category'] ?? '';
          $gallery_title       = $item['gallery_title'] ?? '';
          $gallery_description = $item['gallery_description'] ?? '';

          /*
           * Image URL
           */
          $image_url = '';
          $image_alt = $gallery_title;

          if (is_array($gallery_image)) {

            $image_url = $gallery_image['url'] ?? '';
            $image_alt = $gallery_image['alt'] ?? $gallery_title;

          } elseif (is_numeric($gallery_image)) {

            $image_url = wp_get_attachment_image_url(
              $gallery_image,
              'large'
            );

            $image_alt = get_post_meta(
              $gallery_image,
              '_wp_attachment_image_alt',
              true
            ) ?: $gallery_title;

          } elseif (is_string($gallery_image)) {

            $image_url = $gallery_image;
          }

          if (empty($image_alt)) {
            $image_alt = __('Asal Canning Center Facility Gallery', 'asalcanningcenter');
          }


          /*
           * Video / Image category
           */
          $data_category = $gallery_category;

          /*
           * If media type is video but category is empty,
           * automatically use videos category.
           */
          if ($media_type === 'video' && empty($data_category)) {
            $data_category = 'videos';
          }

      ?>

        <div
          class="gallery-item light-sheen-card"
          data-category="<?php echo esc_attr($data_category); ?>"
          <?php if ($media_type === 'video' && $youtube_id) : ?>
            data-youtube="<?php echo esc_attr($youtube_id); ?>"
          <?php endif; ?>
        >

          <?php if ($image_url) : ?>

            <img
              src="<?php echo esc_url($image_url); ?>"
              alt="<?php echo esc_attr($image_alt); ?>"
              class="lightbox-trigger"
            />

          <?php endif; ?>


          <div class="gallery-overlay">

            <h4>
              <?php echo esc_html($gallery_title); ?>
            </h4>

            <p>
              <?php echo esc_html($gallery_description); ?>
            </p>

          </div>

        </div>

      <?php
        endforeach;

      endif;
      ?>

    </div>

  </div>
</section>