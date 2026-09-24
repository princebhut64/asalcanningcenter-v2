<?php
/**
 * News Hero Banner
 */

$news_hero_badge = get_sub_field('news_hero_badge');
$news_hero_title_before = get_sub_field('news_hero_title_before');
$news_hero_title_highlight = get_sub_field('news_hero_title_highlight');
$news_hero_description = get_sub_field('news_hero_description');

$news_trust_metrics = get_sub_field('news_trust_metrics');


/*
 * Fallback values
 */
if (!$news_hero_badge) {
    $news_hero_badge = 'Public Media Coverage & Honors';
}

if (!$news_hero_title_before) {
    $news_hero_title_before = 'Recognized Across';
}

if (!$news_hero_title_highlight) {
    $news_hero_title_highlight = 'Leading Publications';
}

if (!$news_hero_description) {
    $news_hero_description = 'Documenting over two decades of excellence in cottage food preservation, state innovation awards, and community empowerment in Paldi, Ahmedabad.';
}
?>


<!-- NEWS HERO BANNER (NO SLIDER) -->
<section
  class="hero-section"
  style="padding: 3.5rem 0 2.5rem;"
>

  <div class="ambient-light-beam ambient-light-gold"></div>

  <div class="ambient-light-beam ambient-light-terracotta"></div>


  <div
    class="container text-center"
    style="max-width: 850px; margin: 0 auto; position: relative; z-index: 2;"
  >

    <?php if ($news_hero_badge) : ?>
      <div class="section-tag" style="margin: 0 auto 1rem; display: inline-flex;">
        <i class="fa-solid fa-camera-retro"></i> <?php echo esc_html($news_hero_badge); ?>
      </div>

    <?php endif; ?>


    <?php if ($news_hero_title_before || $news_hero_title_highlight) : ?>

      <h1
        class="heading-serif"
        style="font-size: 3.2rem; color: var(--primary); margin-bottom: 1rem;"
      >

        <?php if ($news_hero_title_before) : ?>

          <?php echo esc_html($news_hero_title_before); ?>

        <?php endif; ?>


        <?php if ($news_hero_title_highlight) : ?>

          <span>
            <?php echo esc_html($news_hero_title_highlight); ?>
          </span>

        <?php endif; ?>

      </h1>

    <?php endif; ?>


    <?php if ($news_hero_description) : ?>

      <p
        style="color: var(--text-muted); font-size: 1.08rem; line-height: 1.65; max-width: 750px; margin: 0 auto 2rem;"
      >

        <?php echo esc_html($news_hero_description); ?>

      </p>

    <?php endif; ?>


    <!-- Trust Metrics Stats Bar -->

    <?php if (!empty($news_trust_metrics)) : ?>

      <div
        style="display: flex; flex-wrap: wrap; justify-content: center; gap: 2rem; padding-top: 1.25rem; border-top: 1px solid var(--border-medium);"
      >

        <?php foreach ($news_trust_metrics as $metric) : ?>

          <?php
          $metric_value = $metric['metric_value'] ?? '';
          $metric_label = $metric['metric_label'] ?? '';
          $metric_color = $metric['metric_color'] ?? 'accent';


          /*
           * Allowed colors
           */
          $allowed_colors = array(
              'accent',
              'terracotta',
              'primary'
          );

          if (!in_array($metric_color, $allowed_colors, true)) {
              $metric_color = 'accent';
          }


          /*
           * Convert color option to CSS variable
           */
          $color_variable = 'var(--' . $metric_color . ')';
          ?>


          <div style="text-align: center;">

            <?php if ($metric_value) : ?>

              <div
                style="font-family: var(--font-serif); font-size: 2.2rem; font-weight: 700; color: <?php echo esc_attr($color_variable); ?>;"
              >

                <?php echo esc_html($metric_value); ?>

              </div>

            <?php endif; ?>


            <?php if ($metric_label) : ?>

              <div
                style="font-size: 0.775rem; font-weight: 700; text-transform: uppercase; color: var(--primary); letter-spacing: 0.05em;"
              >

                <?php echo esc_html($metric_label); ?>

              </div>

            <?php endif; ?>

          </div>


        <?php endforeach; ?>

      </div>

    <?php endif; ?>

  </div>

</section>