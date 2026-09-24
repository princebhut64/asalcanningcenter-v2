<!-- Clean Light Contact Banner -->
<section class="contact-clean-banner">

  <div class="ambient-light-beam ambient-light-gold"></div>

  <div
    class="container text-center"
    style="max-width: 750px; margin: 0 auto; position: relative; z-index: 2;"
  >

    <div class="section-tag" style="margin-bottom: 0.5rem;">
      <i class="fa-solid fa-headset"></i>

      <?php
      echo esc_html(
        get_sub_field('contact_banner_tag')
        ?: 'Direct Facilitator Inquiries'
      );
      ?>
    </div>


    <h1
      class="heading-serif"
      style="font-size: 2.5rem; color: var(--primary); margin-bottom: 0.25rem;"
    >
      <?php
      echo esc_html(
        get_sub_field('contact_banner_title')
        ?: 'Contact Asal Canning Center'
      );
      ?>
    </h1>


    <p style="color: var(--text-muted); font-size: 0.95rem;">
      <?php
      echo esc_html(
        get_sub_field('contact_banner_description')
        ?: 'Direct consultation for cottage canning setups, fruit pulping appointments, and custom batch seaming in Paldi, Ahmedabad.'
      );
      ?>
    </p>

  </div>

</section>