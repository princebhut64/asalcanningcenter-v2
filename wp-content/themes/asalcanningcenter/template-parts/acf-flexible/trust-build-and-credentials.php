<?php
/**
 * Dedicated Trust Build & Credentials Section
 */

$credentials_tag = get_sub_field('credentials_tag');
$credentials_title = get_sub_field('credentials_title');
$credentials_description = get_sub_field('credentials_description');

$credentials_items = get_sub_field('credentials_items');


/*
 * Fallback values
 */
if (!$credentials_tag) {
    $credentials_tag = 'Official Credentials';
}

if (!$credentials_title) {
    $credentials_title = 'Why Institutions & Enterprises Trust Us';
}

if (!$credentials_description) {
    $credentials_description = 'Backed by government recognition, chamber awards, and strict FSSAI food safety certifications.';
}
?>


<!-- DEDICATED TRUST BUILD & CREDENTIALS SECTION -->
<section
  style="background: var(--cream-dark); padding: 4rem 0; border-top: 1px solid var(--border-light); border-bottom: 1px solid var(--border-light);"
>

  <div class="container">

    <div
      class="section-header text-center gsap-reveal"
      style="max-width: 750px; margin: 0 auto 2.5rem;"
    >

      <?php if ($credentials_tag) : ?>

        <div
          class="section-tag"
          style="justify-content: center;"
        >

          <i class="fa-solid fa-award"></i>

          <?php echo esc_html($credentials_tag); ?>

        </div>

      <?php endif; ?>


      <?php if ($credentials_title) : ?>

        <h2
          class="heading-serif"
          style="font-size: 2.5rem; color: var(--primary); margin-bottom: 0.5rem;"
        >

          <?php echo esc_html($credentials_title); ?>

        </h2>

      <?php endif; ?>


      <?php if ($credentials_description) : ?>

        <p
          style="color: var(--text-muted); font-size: 1rem;"
        >

          <?php echo esc_html($credentials_description); ?>

        </p>

      <?php endif; ?>

    </div>


    <?php if (!empty($credentials_items)) : ?>

      <div class="facility-tour-grid gsap-grid-stagger">

        <?php foreach ($credentials_items as $item) : ?>

          <?php
          $credential_icon = $item['credential_icon'] ?? '';
          $credential_title = $item['credential_title'] ?? '';
          $credential_description = $item['credential_description'] ?? '';
        ?>

          <div
            class="facility-card light-sheen-card"
            style="padding: 1.75rem;"
          >

            <?php if ($credential_icon) : ?>

              <div
                class="facility-card-icon"
                style="background: var(--cream-dark); color: accent;"
              >

                <i class="<?php echo esc_attr($credential_icon); ?>"></i>

              </div>

            <?php endif; ?>


            <?php if ($credential_title) : ?>

              <h4
                style="font-family: var(--font-serif); font-size: 1.35rem; color: var(--primary);"
              >

                <?php echo esc_html($credential_title); ?>

              </h4>

            <?php endif; ?>


            <?php if ($credential_description) : ?>

              <p
                style="font-size: 0.875rem; color: var(--text-muted);"
              >

                <?php echo esc_html($credential_description); ?>

              </p>

            <?php endif; ?>

          </div>


        <?php endforeach; ?>

      </div>

    <?php endif; ?>

  </div>

</section>