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
      if (!is_array($gallery_items)) {
          $gallery_items = [];
      }

      $authentic_gallery_defaults = [
          [
              'gallery_image'       => home_url('/wp-content/uploads/2026/09/gallery-training-jalpa-patel.jpg'),
              'media_type'          => 'image',
              'youtube_id'          => '',
              'gallery_category'    => 'workshops',
              'gallery_title'       => 'Training Seminars with Jalpa Patel',
              'gallery_description' => 'Founder Jalpa Patel conducting commercial canning and preservation workshop for women entrepreneurs.',
          ],
          [
              'gallery_image'       => home_url('/wp-content/uploads/2026/09/pulp-journey-machine-extraction.jpg'),
              'media_type'          => 'image',
              'youtube_id'          => '',
              'gallery_category'    => 'operations',
              'gallery_title'       => 'Continuous SS-304 Pulper Extraction',
              'gallery_description' => 'High-capacity SS-304 continuous pulping system extracting fresh fruit pulp under zero-touch hygiene.',
          ],
          [
              'gallery_image'       => home_url('/wp-content/uploads/2026/09/pulp-journey-pouch-filling.jpg'),
              'media_type'          => 'image',
              'youtube_id'          => '',
              'gallery_category'    => 'operations',
              'gallery_title'       => 'Pouch Dispensing Station',
              'gallery_description' => 'Aseptic pouch filling and hermetic heat-sealing line delivering airtight protection.',
          ],
          [
              'gallery_image'       => home_url('/wp-content/uploads/2026/09/herbal-amla-vat-boiling.jpg'),
              'media_type'          => 'image',
              'youtube_id'          => '',
              'gallery_category'    => 'facility',
              'gallery_title'       => 'Thermal Vats & Herbal Processing',
              'gallery_description' => 'Heavy-gauge steam-jacketed thermal vats for slow boiling of herbal decoctions and fruit syrups.',
          ],
          [
              'gallery_image'       => home_url('/wp-content/uploads/2026/09/gallery-women-workshop.jpg'),
              'media_type'          => 'image',
              'youtube_id'          => '',
              'gallery_category'    => 'workshops',
              'gallery_title'       => 'Women Fruit Preservation Clinic',
              'gallery_description' => 'Capacity building training program empowering regional women in commercial cottage food processing.',
          ],
          [
              'gallery_image'       => home_url('/wp-content/uploads/2026/09/pulp-journey-sourcing-crates.jpg'),
              'media_type'          => 'image',
              'youtube_id'          => '',
              'gallery_category'    => 'operations',
              'gallery_title'       => 'Farm Harvest Sourcing & Sorting Crates',
              'gallery_description' => 'Direct farm-sourced fresh fruits inspected and graded for peak natural sweetness and maturity.',
          ],
      ];

      $existing_titles = array_map(function($i) { return strtolower(trim($i['gallery_title'] ?? '')); }, $gallery_items);
      foreach ($authentic_gallery_defaults as $default_item) {
          if (!in_array(strtolower(trim($default_item['gallery_title'])), $existing_titles, true)) {
              $gallery_items[] = $default_item;
          }
      }

      if (!empty($gallery_items)) :

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

    <!-- Auto-Load on Scroll Sentinel & Loader -->
    <div
      id="galleryScrollSentinel"
      class="infinite-scroll-sentinel"
      style="display: none;"
    >
      <div class="infinite-scroll-loader">
        <div class="spinner"></div>
        <span class="loader-text">Loading more gallery media...</span>
      </div>
    </div>

    <div
      id="galleryEndNotice"
      class="gallery-end-notice"
      style="display: none;"
    >
      <i class="fa-solid fa-circle-check" style="color: var(--accent); margin-right: 6px;"></i>
      You've viewed all gallery items
    </div>

  </div>
</section>