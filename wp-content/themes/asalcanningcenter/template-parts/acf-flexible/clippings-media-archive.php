<?php
/**
 * News & Media Archive Section
 */

$media_tag = get_sub_field('media_tag');
$media_title = get_sub_field('media_title');
$media_description = get_sub_field('media_description');

$media_items = get_sub_field('media_items');
if (!is_array($media_items)) {
    $media_items = [];
}

$authentic_media_defaults = [
    [
        'media_image'    => home_url('/wp-content/uploads/2026/09/herbal-amla-drink-banner.jpg'),
        'media_title'    => 'Amla Health Drink & Herbal Wellness Feature',
        'media_date'     => 'Ayurvedic Health & Nutrition Review',
        'media_category' => 'magazine',
        'media_type'     => 'image',
        'youtube_id'     => '',
    ],
    [
        'media_image'    => home_url('/wp-content/uploads/2026/09/gallery-women-workshop.jpg'),
        'media_title'    => 'Women Preservation Clinic Excellence Award',
        'media_date'     => 'Gujarat State Cottage Industry Honor',
        'media_category' => 'awards',
        'media_type'     => 'image',
        'youtube_id'     => '',
    ],
    [
        'media_image'    => home_url('/wp-content/uploads/2026/09/pulp-journey-machine-extraction.jpg'),
        'media_title'    => 'Continuous SS-304 Pulper Machine Innovation Feature',
        'media_date'     => 'Industrial Food Machinery Daily',
        'media_category' => 'newspaper',
        'media_type'     => 'image',
        'youtube_id'     => '',
    ],
];

$existing_media_titles = array_map(function($i) { return strtolower(trim($i['media_title'] ?? '')); }, $media_items);
foreach ($authentic_media_defaults as $default_media) {
    if (!in_array(strtolower(trim($default_media['media_title'])), $existing_media_titles, true)) {
        $media_items[] = $default_media;
    }
}


/*
 * Fallback values
 */
if (!$media_tag) {
    $media_tag = 'Press Clippings';
}

if (!$media_title) {
    $media_title = 'Newspaper Articles & Award Documents';
}

if (!$media_description) {
    $media_description = 'Click on any press clipping below to view high-resolution archived media coverage.';
}


/**
 * Helper to extract YouTube video ID from pure ID or full YouTube URL
 */
if (!function_exists('asal_clean_youtube_id')) {
    function asal_clean_youtube_id($url_or_id) {
        $input = trim((string) $url_or_id);
        if (empty($input)) {
            return '';
        }
        if (preg_match('/^[a-zA-Z0-9_-]{11}$/', $input)) {
            return $input;
        }
        if (preg_match('/(?:youtube\.com\/(?:[^\/\n\s]+\/\S+\/|(?:v|e(?:mbed)?|shorts)\/|.*[?&]v=)|youtu\.be\/)([a-zA-Z0-9_-]{11})/i', $input, $matches)) {
            return $matches[1];
        }
        return $input;
    }
}

/*
 * Count media
 */
$media_count = !empty($media_items) ? count($media_items) : 0;


/*
 * Category counts (100% dynamic from ACF)
 */
$newspaper_count = 0;
$awards_count = 0;
$magazine_count = 0;
$videos_count = 0;

if (!empty($media_items)) {

    foreach ($media_items as $item) {

        $category   = $item['media_category'] ?? '';
        $media_type = $item['media_type'] ?? '';
        $yt_raw     = $item['youtube_id'] ?? '';
        $clean_yt   = asal_clean_youtube_id($yt_raw);

        if ($category === 'videos' || $media_type === 'video' || !empty($clean_yt)) {
            $videos_count++;
        } elseif ($category === 'newspaper') {
            $newspaper_count++;
        } elseif ($category === 'awards') {
            $awards_count++;
        } elseif ($category === 'magazine') {
            $magazine_count++;
        }
    }
}
?>


<!-- NEWS & MEDIA ARCHIVE -->
<section
  class="media-section"
  style="padding: 4rem 0 5rem;"
>

  <div class="container">


    <!-- Media Intro -->
    <div
      class="media-intro gsap-reveal"
      style=""
    >

      <?php if ($media_tag) : ?>

        <div
          class="section-tag"
          style="justify-content: center;"
        >

          <i class="fa-solid fa-camera-retro"></i>

          <?php echo esc_html($media_tag); ?>

        </div>

      <?php endif; ?>


      <?php if ($media_title) : ?>

        <h2
          class="heading-serif"
          style="font-size: 2.5rem; color: var(--primary); margin-bottom: 0.5rem;"
        >

          <?php echo esc_html($media_title); ?>

        </h2>

      <?php endif; ?>


      <?php if ($media_description) : ?>

        <p
          style="color: var(--text-muted); font-size: 1rem;"
        >

          <?php echo esc_html($media_description); ?>

        </p>

      <?php endif; ?>

    </div>


    <!-- Filter Controls -->
    <div
      class="filter-tabs gsap-reveal"
      style=""
    >

      <button
        class="filter-btn active"
        data-filter="all"
      >

        <i class="fa-solid fa-layer-group"></i>

        All Media (<?php echo esc_html($media_count); ?>)

      </button>


      <button
        class="filter-btn"
        data-filter="videos"
      >

        <i class="fa-brands fa-youtube"></i>

        Videos

        <?php if ($videos_count > 0) : ?>

          (<?php echo esc_html($videos_count); ?>)

        <?php endif; ?>

      </button>


      <button
        class="filter-btn"
        data-filter="newspaper"
      >

        <i class="fa-solid fa-newspaper"></i>

        Newspapers

        <?php if ($newspaper_count > 0) : ?>

          (<?php echo esc_html($newspaper_count); ?>)

        <?php endif; ?>

      </button>


      <button
        class="filter-btn"
        data-filter="awards"
      >

        <i class="fa-solid fa-award"></i>

        Awards &amp; Honors

        <?php if ($awards_count > 0) : ?>

          (<?php echo esc_html($awards_count); ?>)

        <?php endif; ?>

      </button>


      <button
        class="filter-btn"
        data-filter="magazine"
      >

        <i class="fa-solid fa-book-open"></i>

        Magazines

        <?php if ($magazine_count > 0) : ?>

          (<?php echo esc_html($magazine_count); ?>)

        <?php endif; ?>

      </button>

    </div>


    <!-- Press Cards Grid -->

    <?php if (!empty($media_items)) : ?>

      <div
        class="press-grid gsap-grid-stagger"
        id="pressGrid"
      >


        <?php foreach ($media_items as $item) : ?>

          <?php
          $media_image      = $item['media_image'] ?? '';
          $media_item_title = $item['media_title'] ?? '';
          $media_date       = $item['media_date'] ?? '';
          $media_category   = $item['media_category'] ?? '';
          $media_type       = $item['media_type'] ?? '';
          $youtube_raw      = $item['youtube_id'] ?? '';
          $youtube_id       = asal_clean_youtube_id($youtube_raw);

          $is_video = ($media_category === 'videos' || $media_type === 'video' || !empty($youtube_id));
          if ($is_video) {
              $media_category = 'videos';
          }


          /*
           * Image handling
           */
          $image_url = '';
          $image_alt = $media_item_title;

          if (is_array($media_image)) {

              $image_url = $media_image['url'] ?? '';

              if (!empty($media_image['alt'])) {
                  $image_alt = $media_image['alt'];
              }

          } elseif (is_numeric($media_image)) {

              $image_url = wp_get_attachment_image_url(
                  $media_image,
                  'large'
              );

              $attachment_alt = get_post_meta(
                  $media_image,
                  '_wp_attachment_image_alt',
                  true
              );

              if ($attachment_alt) {
                  $image_alt = $attachment_alt;
              }

          } elseif (is_string($media_image)) {

              $image_url = $media_image;

          }

          // Fallback to YouTube HQ thumbnail if no image is uploaded for video
          if (empty($image_url) && !empty($youtube_id)) {
              $image_url = 'https://img.youtube.com/vi/' . esc_attr($youtube_id) . '/hqdefault.jpg';
          }
          ?>


          <?php if ($image_url) : ?>

            <div
              class="press-card light-sheen-card<?php echo $is_video ? ' press-video-card' : ''; ?>"
              role="button"
              tabindex="0"
              data-category="<?php echo esc_attr($media_category); ?>"
              data-title="<?php echo esc_attr($media_item_title); ?>"
              data-image="<?php echo esc_url($image_url); ?>"
              <?php if ($is_video && !empty($youtube_id)) : ?>
                data-youtube="<?php echo esc_attr($youtube_id); ?>"
              <?php endif; ?>
              aria-label="<?php echo $is_video ? 'Watch video: ' . esc_attr($media_item_title) : 'Open ' . esc_attr($media_item_title); ?>"
            >

              <div class="press-thumb">

                <img
                  src="<?php echo esc_url($image_url); ?>"
                  alt="<?php echo esc_attr($image_alt); ?>"
                  loading="lazy"
                >

                <?php if ($is_video) : ?>

                  <span class="video-badge">
                    <i class="fa-solid fa-play"></i> Watch Video
                  </span>

                  <div class="zoom-overlay video-play-overlay">
                    <i class="fa-solid fa-play"></i>
                  </div>

                <?php else : ?>

                  <div class="zoom-overlay">
                    <i class="fa-solid fa-magnifying-glass-plus"></i>
                  </div>

                <?php endif; ?>

              </div>


              <div class="press-footer">

                <?php if ($media_item_title) : ?>

                  <h4>
                    <?php echo esc_html($media_item_title); ?>
                  </h4>

                <?php endif; ?>


                <?php if ($media_date) : ?>

                  <span>
                    <?php if ($is_video) : ?>
                      <i class="fa-brands fa-youtube" style="margin-right: 4px; color: var(--accent);"></i>
                    <?php endif; ?>
                    <?php echo esc_html($media_date); ?>
                  </span>

                <?php endif; ?>

              </div>

            </div>

          <?php endif; ?>


        <?php endforeach; ?>


      </div>

    <?php endif; ?>


  </div>

</section>

<!-- DOCUMENT LIGHTBOX MODAL -->
<div class="document-lightbox" id="docLightbox" role="dialog" aria-modal="true" aria-labelledby="modalDocTitle" aria-hidden="true">
  <div class="lightbox-frame">
    <div class="lightbox-header">
      <h3 id="modalDocTitle">Press Clipping Detail</h3>
      <button class="lightbox-close-btn" onclick="closeLightbox()">&times;</button>
    </div>
    <div class="lightbox-body">
      <img src="" alt="Clipping Document" id="modalDocImg" />
    </div>
  </div>
</div>