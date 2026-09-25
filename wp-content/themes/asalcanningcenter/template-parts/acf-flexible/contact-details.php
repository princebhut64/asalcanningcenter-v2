<!-- Contact Main Section -->
<section style="background: var(--cream-dark);">

  <div class="container contact-main-grid">

    <?php
    /*
     * ----------------------------------------------------------------------
     * ACF DATA
     * ----------------------------------------------------------------------
     */

    // Founder
    $founder_image       = get_sub_field('founder_image');
    $founder_name        = get_sub_field('founder_name');
    $founder_role        = get_sub_field('founder_role');
    $founder_description = get_sub_field('founder_description');


    // Contact details
    $business_name    = get_sub_field('contact_business_name');
    $business_subtitle = get_sub_field('contact_business_subtitle');
    $contact_address  = get_sub_field('contact_address');
    $phone_1          = get_sub_field('contact_phone_1');
    $phone_2          = get_sub_field('contact_phone_2');
    $phone_3          = get_sub_field('contact_phone_3');
    $contact_email    = get_sub_field('contact_email');
    $contact_website  = get_sub_field('contact_website');


    // Working hours
    $center_hours  = get_sub_field('center_hours');
    $machine_hours = get_sub_field('machine_hours');


    // Google Map
    $contact_map_embed = get_sub_field('contact_map_embed');


    // QR Image
    $contact_qr_image = get_sub_field('contact_qr_image');


    /*
     * ----------------------------------------------------------------------
     * DEFAULTS
     * ----------------------------------------------------------------------
     */

    if (empty($founder_name) || trim($founder_name) === 'Ms. Jalpa G. Patel') {
      $founder_name = 'Jalpa G. Patel';
    }

    if (empty($founder_role)) {
      $founder_role = 'FOUNDER & CHIEF FACILITATOR';
    }

    if (empty($founder_description) || strpos($founder_description, 'Direct consultation') !== false) {
      $founder_description = 'Leading food processing innovations, domestic canning workshops, and small-scale cottage setup mentorship for over two decades.';
    }

    $business_name = $business_name ?: 'Asal Canning Center';

    $business_subtitle = $business_subtitle ?: '(Cottage Industry Food Processing)';

    $contact_address = $contact_address ?: 'Chhaya Chitra 5, Kailash Society, Nr. Mahalaxmi Cross Roads, Paldi, Ahmedabad – 380007, Gujarat, India';

    $center_hours = $center_hours ?: 'Mon – Sat: 10:00 AM – 6:00 PM';

    $machine_hours = $machine_hours ?: 'Mon – Sat: 10:00 AM – 4:00 PM';


    /*
     * Founder image
     */
    $founder_image_url = '';
    $founder_image_alt = $founder_name;

    if (is_array($founder_image)) {

      $founder_image_url = $founder_image['url'] ?? '';
      $founder_image_alt = $founder_image['alt'] ?? $founder_name;

    } elseif (is_numeric($founder_image)) {

      $founder_image_url = wp_get_attachment_image_url(
        $founder_image,
        'large'
      );

      $founder_image_alt = get_post_meta(
        $founder_image,
        '_wp_attachment_image_alt',
        true
      ) ?: $founder_name;

    } elseif (is_string($founder_image)) {

      $founder_image_url = $founder_image;

    }

    // High-trust default: fallback to official square portrait of Jalpa G. Patel
    if (empty($founder_image_url)) {
      $founder_image_url = wp_get_attachment_image_url(112, 'large')
        ?: content_url('/uploads/2026/09/jalpa-patel-founder-square.jpg');
    }


    /*
     * QR image
     */
    $qr_image_url = '';
    $qr_image_alt = 'Contact QR Code';

    if (is_array($contact_qr_image)) {

      $qr_image_url = $contact_qr_image['url'] ?? '';
      $qr_image_alt = $contact_qr_image['alt'] ?? 'Contact QR Code';

    } elseif (is_numeric($contact_qr_image)) {

      $qr_image_url = wp_get_attachment_image_url(
        $contact_qr_image,
        'medium'
      );

    } elseif (is_string($contact_qr_image)) {

      $qr_image_url = $contact_qr_image;

    }
    ?>


    <!-- ================================================================
         LEFT COLUMN: FOUNDER & SUPPORT
         ================================================================ -->
    <div>

      <!-- Founder Spotlight Card (matches About Us / Heritage design) -->
      <div class="founder-card light-sheen-card gsap-reveal" style="margin-bottom: 1.25rem;">

        <?php if ($founder_image_url) : ?>

          <div class="founder-img-box">

            <img
              src="<?php echo esc_url($founder_image_url); ?>"
              alt="<?php echo esc_attr($founder_image_alt); ?>"
            />

          </div>

        <?php endif; ?>


        <h3>
          <?php echo esc_html($founder_name); ?>
        </h3>


        <span class="role">
          <?php echo esc_html($founder_role); ?>
        </span>


        <p>
          <?php echo esc_html($founder_description); ?>
        </p>

        <!-- Trust & Credential Badges -->
        <div class="founder-badges">
          <span class="mini-badge">
            <i class="fa-solid fa-award"></i>
            GCCI Award 2007
          </span>
          <span class="mini-badge">
            FSSAI Certified
          </span>
        </div>

      </div>


      <?php
      /*
       * Support Widgets
       */
      $support_items = get_sub_field('contact_support_items');

      if ($support_items) :

        foreach ($support_items as $support_item) :

          $support_icon = $support_item['support_icon'] ?? '';
          $support_title = $support_item['support_title'] ?? '';
          $support_description = $support_item['support_description'] ?? '';
      ?>

          <div class="support-widget-card light-sheen-card gsap-reveal">

            <i class="<?php echo esc_attr($support_icon ?: 'fa-solid fa-circle-info'); ?>"></i>

            <div>

              <h5
                style="font-family: var(--font-serif); font-size: 1.05rem; color: var(--primary);"
              >
                <?php echo esc_html($support_title); ?>
              </h5>

              <p
                style="font-size: 0.775rem; color: var(--text-muted); margin: 0;"
              >
                <?php echo esc_html($support_description); ?>
              </p>

            </div>

          </div>

      <?php
        endforeach;

      endif;
      ?>

    </div>


    <!-- ================================================================
         CENTER COLUMN: CONTACT DETAILS
         ================================================================ -->
    <div class="contact-details-box light-sheen-card gsap-reveal">

      <h2
        class="heading-serif"
        style="font-size: 2rem; color: var(--primary); margin-bottom: 0.2rem;"
      >
        <?php echo esc_html($business_name); ?>
      </h2>


      <span
        style="color: var(--accent-hover); font-weight: 700; font-size: 0.8rem; text-transform: uppercase; letter-spacing: 0.05em; display: block; margin-bottom: 1.25rem;"
      >
        <?php echo esc_html($business_subtitle); ?>
      </span>


      <!-- Address -->
      <div class="info-row">

        <i class="fa-solid fa-location-dot"></i>

        <div>

          <h4
            style="font-family: var(--font-serif); font-size: 1.1rem; color: var(--primary);"
          >
            Address
          </h4>

          <p
            style="font-size: 0.875rem; color: var(--text-muted);"
          >
            <?php echo nl2br(esc_html($contact_address)); ?>
          </p>

        </div>

      </div>


      <!-- Phone -->
      <div class="info-row">

        <i class="fa-solid fa-phone"></i>

        <div>

          <h4
            style="font-family: var(--font-serif); font-size: 1.1rem; color: var(--primary);"
          >
            Phone &amp; Mobile
          </h4>

          <p style="font-size: 0.875rem; color: var(--text-muted);">

            <?php if ($phone_1) : ?>

              <a
                href="tel:<?php echo esc_attr(preg_replace('/[^0-9+]/', '', $phone_1)); ?>"
                style="color: var(--primary); font-weight: 700;"
              >
                <?php echo esc_html($phone_1); ?>
              </a>

            <?php endif; ?>


            <?php if ($phone_2) : ?>
              <br />

              <a
                href="tel:<?php echo esc_attr(preg_replace('/[^0-9+]/', '', $phone_2)); ?>"
                style="color: var(--primary); font-weight: 700;"
              >
                <?php echo esc_html($phone_2); ?>
              </a>

            <?php endif; ?>


            <?php if ($phone_3) : ?>
              /
              <a
                href="tel:<?php echo esc_attr(preg_replace('/[^0-9+]/', '', $phone_3)); ?>"
                style="color: var(--primary); font-weight: 700;"
              >
                <?php echo esc_html($phone_3); ?>
              </a>
            <?php endif; ?>

          </p>

        </div>

      </div>


      <!-- Email -->
      <div class="info-row">

        <i class="fa-solid fa-envelope"></i>

        <div>

          <h4
            style="font-family: var(--font-serif); font-size: 1.1rem; color: var(--primary);"
          >
            Email &amp; Online
          </h4>

          <p style="font-size: 0.875rem; color: var(--text-muted);">

            <?php if ($contact_email) : ?>

              <a
                href="mailto:<?php echo esc_attr($contact_email); ?>"
                style="color: var(--accent-hover); font-weight: 700;"
              >
                <?php echo esc_html($contact_email); ?>
              </a>

            <?php endif; ?>


            <?php if ($contact_website) : ?>

              <br />

              <span>
                <?php echo esc_html($contact_website); ?>
              </span>

            <?php endif; ?>

          </p>

        </div>

      </div>


      <!-- ==============================================================
           WORKING HOURS
           ============================================================== -->
      <div class="time-badge-box">

        <div>

          <small
            style="color: var(--accent-hover); font-weight: 700; text-transform: uppercase; font-size: 0.725rem;"
          >
            <i class="fa-regular fa-clock"></i>
            Center Working Hours
          </small>

          <p
            style="font-size: 0.825rem; font-weight: 600; margin: 0.15rem 0 0;"
          >
            <?php echo esc_html($center_hours); ?>
          </p>

        </div>


        <div>

          <small
            style="color: var(--accent-hover); font-weight: 700; text-transform: uppercase; font-size: 0.725rem;"
          >
            <i class="fa-solid fa-gears"></i>
            Machine Working Hours
          </small>

          <p
            style="font-size: 0.825rem; font-weight: 600; margin: 0.15rem 0 0;"
          >
            <?php echo esc_html($machine_hours); ?>
          </p>

        </div>

      </div>


      <!-- ==============================================================
           CONTACT FORM 7
           ============================================================== -->
      <?php
        $contact_form = get_sub_field('contact_form');
        $form_id = '';
        if ( is_object( $contact_form ) && isset( $contact_form->ID ) ) {
            $form_id = $contact_form->ID;
        } elseif ( is_array( $contact_form ) && isset( $contact_form['ID'] ) ) {
            $form_id = $contact_form['ID'];
        } elseif ( is_scalar( $contact_form ) && ! empty( $contact_form ) ) {
            $form_id = $contact_form;
        }

        // Fallback to primary contact form if field is unassigned
        if ( empty( $form_id ) ) {
            $form_id = 224;
        }

        $selected_form = '[contact-form-7 id="' . esc_attr( $form_id ) . '"]';
      ?>

      <?php if ($selected_form) : ?>

        <div style="margin-top: 1.5rem;" id="contactFormWrap">

          <?php
            $inquiry_product = isset($_GET['product']) ? sanitize_text_field(wp_unslash($_GET['product'])) : '';
            $inquiry_type    = isset($_GET['type']) ? sanitize_text_field(wp_unslash($_GET['type'])) : '';
          ?>

          <?php if (!empty($inquiry_product)) : ?>
            <div class="inquiry-product-tag">
              <i class="fa-solid fa-jar"></i>
              <div>
                <strong>Inquiring For: <?php echo esc_html($inquiry_product); ?></strong>
                <span><?php echo ($inquiry_type === 'sample') ? 'Sample Pack Request &amp; Specifications' : 'Bulk Batch Order &amp; Custom Preservation Consultation'; ?></span>
              </div>
            </div>
          <?php endif; ?>

          <div class="inquiry-trust-banner">
            <div class="inquiry-trust-icon">
              <i class="fa-solid fa-bolt"></i>
            </div>
            <div class="inquiry-trust-text">
              <strong>Fast Direct Response &bull; Founder Direct Review</strong>
              <span>Direct consultation with Jalpa G. Patel and our cottage processing team. Typical response within 2–4 business hours.</span>
            </div>
          </div>

          <?php echo do_shortcode($selected_form); ?>

        </div>

      <?php endif; ?>

    </div>


    <!-- ================================================================
         RIGHT COLUMN: MAP & QR
         ================================================================ -->
    <div>

      <!-- Google Map -->
      <?php if ($contact_map_embed) : ?>

        <div class="map-frame-card gsap-reveal">

          <?php echo $contact_map_embed; ?>

        </div>

      <?php endif; ?>


      <!-- QR Code -->
      <?php if ($qr_image_url) : ?>

        <div class="qr-card light-sheen-card gsap-reveal">

          <h4
            style="font-family: var(--font-serif); font-size: 1.15rem; color: var(--primary); margin-bottom: 0.2rem;"
          >
            Scan for Contact Info
          </h4>

          <p
            style="font-size: 0.775rem; color: var(--text-muted); margin-bottom: 0.65rem;"
          >
            Scan to save phone number and facility location.
          </p>

          <img
            src="<?php echo esc_url($qr_image_url); ?>"
            alt="<?php echo esc_attr($qr_image_alt); ?>"
            style="width: 130px; margin: 0 auto; border-radius: var(--radius-sm); border: 2px solid var(--border-gold);"
          />

        </div>

      <?php endif; ?>

    </div>

  </div>

</section>