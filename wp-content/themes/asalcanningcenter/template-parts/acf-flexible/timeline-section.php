<?php
/**
 * Timeline Section - Stepped Stairs Journey
 */

$timeline_tag         = get_sub_field('timeline_tag') ?: 'Historical Milestones';
$timeline_title       = get_sub_field('timeline_title') ?: 'Our Journey Over Two Decades';
$timeline_description = get_sub_field('timeline_description') ?: 'Documenting key breakthroughs in cottage food processing and community development.';
$timeline_items       = get_sub_field('timeline_items');

// Curated authentic milestone images matching each era
$default_milestone_images = [
    1 => [
        'url' => home_url('/wp-content/uploads/2026/09/photo-g1.jpg'),
        'alt' => 'Foundation in Paldi, Ahmedabad - Fruit Pulping'
    ],
    2 => [
        'url' => home_url('/wp-content/uploads/2026/09/photo-g3.jpg'),
        'alt' => 'GCCI Innovation Award Ceremony'
    ],
    3 => [
        'url' => home_url('/wp-content/uploads/2026/09/photo-g4.jpg'),
        'alt' => 'Expansion to Retort Pouch Packing Machinery'
    ],
    4 => [
        'url' => home_url('/wp-content/uploads/2026/09/photo-g5.jpg'),
        'alt' => 'State-of-the-Art Modern Canning Center'
    ]
];
$era_meta = [
    1 => ['label' => 'Foundation',   'icon' => 'fa-seedling'],
    2 => ['label' => 'Recognition',  'icon' => 'fa-award'],
    3 => ['label' => 'Innovation',   'icon' => 'fa-flask-vial'],
    4 => ['label' => 'Modern Era',   'icon' => 'fa-crown']
];
?>

<!-- TIMELINE / JOURNEY SECTION (MASTER DESIGN) -->
<section class="timeline-section master-timeline-section">
  <!-- Subtle ambient decorative glow -->
  <div class="timeline-ambient-glow" aria-hidden="true"></div>

  <div class="container" style="position: relative; z-index: 2;">
    <!-- Section Header -->
    <div class="section-header text-center gsap-reveal" style="max-width: 800px; margin: 0 auto 3.75rem;">
      <?php if ($timeline_tag) : ?>
        <div class="section-tag" style="justify-content: center; margin-bottom: 0.85rem;">
          <i class="fa-solid fa-stairs"></i>
          <?php echo esc_html($timeline_tag); ?>
        </div>
      <?php endif; ?>

      <?php if ($timeline_title) : ?>
        <h2 class="heading-serif timeline-main-title">
          <?php echo esc_html($timeline_title); ?>
        </h2>
      <?php endif; ?>

      <?php if ($timeline_description) : ?>
        <p class="timeline-main-desc">
          <?php echo esc_html($timeline_description); ?>
        </p>
      <?php endif; ?>
    </div>

    <?php if (!empty($timeline_items)) : ?>
      <!-- Stepped Stacks Grid -->
      <div class="timeline-stacks-wrap timeline-staircase-wrap gsap-grid-stagger">
        <?php 
        $step_index = 1;
        $total_steps = count($timeline_items);
        foreach ($timeline_items as $item) : 
            $year             = $item['timeline_year'] ?? '';
            $item_title       = $item['timeline_item_title'] ?? '';
            $item_description = $item['timeline_item_description'] ?? '';
            $item_image       = $item['timeline_image'] ?? '';

            $image_url = '';
            $image_alt = $item_title;

            if (!empty($item_image)) {
                if (is_array($item_image)) {
                    $image_url = $item_image['url'] ?? '';
                    $image_alt = $item_image['alt'] ?: $item_title;
                } elseif (is_numeric($item_image)) {
                    $image_url = wp_get_attachment_image_url($item_image, 'large');
                } else {
                    $image_url = $item_image;
                }
            }

            if (empty($image_url) && isset($default_milestone_images[$step_index])) {
                $image_url = $default_milestone_images[$step_index]['url'];
                $image_alt = $default_milestone_images[$step_index]['alt'];
            }

            $current_meta = $era_meta[$step_index] ?? ['label' => 'Milestone', 'icon' => 'fa-circle-check'];
        ?>

          <div class="timeline-stack-item stack-tier-<?php echo $step_index; ?> stair-step-item" data-step="<?php echo $step_index; ?>">

            <!-- Premium Milestone Card -->
            <div class="timeline-stack-card stair-step-card light-sheen-card">
              
              <!-- Card Top Header -->
              <div class="stack-card-top stair-card-top">
                <?php if ($year) : ?>
                  <div class="stack-year-wrap">
                    <span class="stack-year-dot"></span>
                    <span class="stack-year stair-year"><?php echo esc_html($year); ?></span>
                  </div>
                <?php endif; ?>
                
                <span class="stack-era-pill stair-era-pill">
                  <i class="fa-solid <?php echo esc_attr($current_meta['icon']); ?>"></i>
                  <?php echo esc_html($current_meta['label']); ?>
                </span>
              </div>

              <!-- Milestone Image -->
              <?php if ($image_url) : ?>
                <div class="stack-img-holder stair-img-holder">
                  <img src="<?php echo esc_url($image_url); ?>" alt="<?php echo esc_attr($image_alt); ?>" loading="lazy" />
                  <div class="stack-img-sheen"></div>
                </div>
              <?php endif; ?>

              <!-- Content Body -->
              <div class="stack-card-body stair-card-body">
                <?php if ($item_title) : ?>
                  <h4><?php echo esc_html($item_title); ?></h4>
                <?php endif; ?>

                <?php if ($item_description) : ?>
                  <p><?php echo esc_html($item_description); ?></p>
                <?php endif; ?>
              </div>

              <!-- Sleek Card Bottom Bar (Phase Anchor) -->
              <div class="stack-card-phase-bar">
                <span class="phase-track"></span>
                <span class="phase-badge">Phase 0<?php echo $step_index; ?></span>
              </div>
            </div>

            <!-- Precision Engineered Stepped Connector Line __|-- (No badges, pure jewel-crafted line) -->
            <?php if ($step_index < $total_steps) : ?>
              <div class="stack-step-connector" aria-hidden="true">
                <svg class="step-connector-svg" viewBox="0 0 32 36" fill="none" xmlns="http://www.w3.org/2000/svg">
                  <defs>
                    <linearGradient id="conduitGold<?php echo $step_index; ?>" x1="0%" y1="100%" x2="100%" y2="0%">
                      <stop offset="0%" stop-color="#C59A27" />
                      <stop offset="50%" stop-color="#E0A238" />
                      <stop offset="100%" stop-color="#FFD700" />
                    </linearGradient>
                  </defs>

                  <!-- Ambient Glow Background Line -->
                  <path d="M 0 33 L 12 33 Q 16 33 16 29 L 16 7 Q 16 3 20 3 L 32 3" 
                        stroke="rgba(224, 162, 56, 0.25)" 
                        stroke-width="7" 
                        stroke-linecap="round" />

                  <!-- Main Luminous __|-- Gold Stepped Conduit -->
                  <path d="M 0 33 L 12 33 Q 16 33 16 29 L 16 7 Q 16 3 20 3 L 32 3" 
                        stroke="url(#conduitGold<?php echo $step_index; ?>)" 
                        stroke-width="3.5" 
                        stroke-linecap="round" />

                  <!-- Precision Terminal Docking Nodes -->
                  <circle cx="2" cy="33" r="3" fill="#D4AF37" />
                  <circle cx="30" cy="3" r="3" fill="#FFD700" />
                </svg>
              </div>
            <?php endif; ?>

          </div>

        <?php 
          $step_index++;
        endforeach; 
        ?>
      </div>
    <?php endif; ?>
  </div>
</section>