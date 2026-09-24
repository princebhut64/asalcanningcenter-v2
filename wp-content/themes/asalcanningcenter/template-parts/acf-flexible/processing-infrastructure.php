<!-- 2. CANNING & PROCESSING INFRASTRUCTURE HIGHLIGHTS -->
<section style="padding: 3.5rem 0 4.5rem; background: var(--cream-dark); border-top: 1px solid var(--border-light);">
  <div class="container">

    <div class="section-header text-center gsap-reveal" style="max-width: 750px; margin: 0 auto 2.5rem;">

      <div class="section-tag" style="justify-content: center;">
        <i class="fa-solid fa-industry"></i>
        <?php
        echo esc_html(
          get_sub_field('infrastructure_tag')
          ?: 'Processing Infrastructure'
        );
        ?>
      </div>

      <h2
        class="heading-serif"
        style="font-size: 2.5rem; color: var(--primary); margin-bottom: 0.5rem;"
      >
        <?php
        echo esc_html(
          get_sub_field('infrastructure_title')
          ?: 'Precision & Hygiene Standards'
        );
        ?>
      </h2>

      <p style="color: var(--text-muted); font-size: 1rem;">
        <?php
        echo esc_html(
          get_sub_field('infrastructure_description')
          ?: 'Our processing unit in Paldi, Ahmedabad is engineered for zero-touch extraction, hermetic double-seam tinning, and temperature-controlled thermal pasteurization.'
        );
        ?>
      </p>

    </div>


    <div class="facility-tour-grid gsap-grid-stagger">

      <?php
      $infrastructure_items = get_sub_field('infrastructure_items');

      if ($infrastructure_items) :

        foreach ($infrastructure_items as $item) :

          $icon = $item['infrastructure_icon'] ?? '';
          $title = $item['infrastructure_item_title'] ?? '';
          $description = $item['infrastructure_item_description'] ?? '';
      ?>

          <div class="facility-card light-sheen-card">

            <div class="facility-card-icon">
              <?php if ($icon) : ?>
                <i class="<?php echo esc_attr($icon); ?>"></i>
              <?php else : ?>
                <i class="fa-solid fa-industry"></i>
              <?php endif; ?>
            </div>

            <h4>
              <?php echo esc_html($title); ?>
            </h4>

            <p>
              <?php echo esc_html($description); ?>
            </p>

          </div>

      <?php
        endforeach;

      endif;
      ?>

    </div>

  </div>
</section>