<?php
/**
 * Core Values & Pillars Section - A New Era in Food Preservation
 */

$pillars_tag         = get_sub_field('pillars_tag');
$pillars_title       = get_sub_field('pillars_title');
$pillars_description = get_sub_field('pillars_description');
$pillars_items       = get_sub_field('pillars_items');
$stats               = get_sub_field('stats');

/*
 * Fallback values
 */
if (!$pillars_tag) {
    $pillars_tag = 'A New Era in Food Preservation';
}

if (!$pillars_title) {
    $pillars_title = 'Pioneering Modern Artisanal Canning & Quality Standards';
}

if (!$pillars_description) {
    $pillars_description = 'Bridging authentic agricultural harvests with modern hygienic extraction, precision hermetic sealing, and retort pouch packing.';
}

if (empty($stats)) {
    $stats = [
        [
            'stat_icon'   => 'fa-solid fa-calendar-check',
            'stat_number' => '24',
            'stat_suffix' => '+ Years',
            'stat_title'  => 'Processing Excellence'
        ],
        [
            'stat_icon'   => 'fa-solid fa-jar',
            'stat_number' => '50',
            'stat_suffix' => '+',
            'stat_title'  => 'Product Formulations'
        ],
        [
            'stat_icon'   => 'fa-solid fa-users',
            'stat_number' => '10000',
            'stat_suffix' => '+',
            'stat_title'  => 'Satisfied Clients'
        ],
        [
            'stat_icon'   => 'fa-solid fa-shield-halved',
            'stat_number' => '',
            'stat_suffix' => 'TM 3340551',
            'stat_title'  => '100% FSSAI Compliant'
        ]
    ];
}

if (empty($pillars_items)) {
    $pillars_items = [
        [
            'pillar_icon'        => 'fa-solid fa-leaf',
            'pillar_title'       => '100% Pure Natural Purity',
            'pillar_description' => 'Zero artificial colors, synthetic flavors, or chemical preservatives. Pure thermal processing retains genuine farm freshness.'
        ],
        [
            'pillar_icon'        => 'fa-solid fa-gears',
            'pillar_title'       => 'Modern Cottage Technology',
            'pillar_description' => 'Equipped with commercial pulpers, steam-jacketed kettles, hermetic tin sealers, and precision retort sterilization units.'
        ],
        [
            'pillar_icon'        => 'fa-solid fa-clock-rotate-left',
            'pillar_title'       => '12–24 Months Shelf Stability',
            'pillar_description' => 'Safe, long ambient storage life without refrigeration through hermetic sealing and micro-tested commercial pasteurization.'
        ],
        [
            'pillar_icon'        => 'fa-solid fa-certificate',
            'pillar_title'       => 'Registered TM 3340551 & FSSAI',
            'pillar_description' => 'Government-recognized cottage processing unit adhering to strict FSSAI food safety standards and Trade Mark No. 3340551.'
        ]
    ];
}
?>

<!-- FLOATING TRUST STATS BAR (HOME/INDEX PAGE ONLY) -->
<?php if ( (is_front_page() || is_home()) && !empty($stats) ) : ?>
<section class="stats-section" style="margin-top: -2.5rem; position: relative; z-index: 10;">
    <div class="container">
        <div class="stats-card-grid">
            <?php foreach ($stats as $stat) : ?>
                <?php
                $stat_icon   = $stat['stat_icon'] ?? '';
                $stat_number = $stat['stat_number'] ?? '';
                $stat_suffix = $stat['stat_suffix'] ?? '';
                $stat_title  = $stat['stat_title'] ?? '';
                ?>
                <div class="stat-box">
                    <?php if ($stat_icon) : ?>
                        <div class="stat-icon">
                            <i class="<?php echo esc_attr($stat_icon); ?>"></i>
                        </div>
                    <?php endif; ?>

                    <div class="stat-info">
                        <?php if ($stat_number !== '') : ?>
                            <h3>
                                <span class="counter" data-target="<?php echo esc_attr($stat_number); ?>">0</span><?php if ($stat_suffix) : ?><?php echo esc_html($stat_suffix); ?><?php endif; ?>
                            </h3>
                        <?php else : ?>
                            <h3 style="font-size: 1.35rem; line-height: 1.2;">
                                <?php echo esc_html($stat_suffix); ?>
                            </h3>
                        <?php endif; ?>

                        <?php if ($stat_title) : ?>
                            <p><?php echo esc_html($stat_title); ?></p>
                        <?php endif; ?>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>
<?php endif; ?>

<!-- CORE VALUES & NEW ERA PILLARS -->
<section class="products-section new-era-section" style="background-color: var(--cream-dark); padding: 5rem 0 4rem; border-bottom: 1px solid var(--border-light);">
  <div class="container">
    <div class="section-header text-center gsap-reveal" style="max-width: 780px; margin: 0 auto 2.5rem;">
      <?php if ($pillars_tag) : ?>
        <div class="section-tag" style="justify-content: center;">
          <i class="fa-solid fa-gem"></i>
          <?php echo esc_html($pillars_tag); ?>
        </div>
      <?php endif; ?>

      <?php if ($pillars_title) : ?>
        <h2 class="heading-serif" style="font-size: clamp(2rem, 3.5vw, 2.5rem); color: var(--primary); margin-bottom: 0.65rem;">
          <?php echo esc_html($pillars_title); ?>
        </h2>
      <?php endif; ?>

      <?php if ($pillars_description) : ?>
        <p style="color: var(--text-muted); font-size: 1.05rem; line-height: 1.6;">
          <?php echo esc_html($pillars_description); ?>
        </p>
      <?php endif; ?>
    </div>

    <?php if (!empty($pillars_items)) : ?>
      <div class="pillars-grid gsap-grid-stagger">
        <?php foreach ($pillars_items as $item) : ?>
          <?php
          $pillar_icon = $item['pillar_icon'] ?? 'fa-solid fa-gem';
          $pillar_title = $item['pillar_title'] ?? '';
          $pillar_description = $item['pillar_description'] ?? '';
          ?>
          <div class="pillar-card light-sheen-card">
            <?php if ($pillar_icon) : ?>
              <div class="pillar-icon">
                <i class="<?php echo esc_attr($pillar_icon); ?>"></i>
              </div>
            <?php endif; ?>

            <?php if ($pillar_title) : ?>
              <h3><?php echo esc_html($pillar_title); ?></h3>
            <?php endif; ?>

            <?php if ($pillar_description) : ?>
              <p><?php echo esc_html($pillar_description); ?></p>
            <?php endif; ?>
          </div>
        <?php endforeach; ?>
      </div>
    <?php endif; ?>
  </div>
</section>