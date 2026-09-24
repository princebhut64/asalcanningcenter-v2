<?php
/**
 * The template for displaying the footer
 *
 * Contains the closing of the #content div and all content after.
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package asalcanningcenter
 */

?>

	<?php
/**
 * Footer Settings
 *
 * ACF Options Page Fields
 */


/* =========================================================
   FOOTER SETTINGS
========================================================= */

// Footer About
$footer_description = get_field('footer_description', 'option');
$footer_fssai_logo  = get_field('footer_fssai_logo', 'option');
$footer_fssai_license = get_field('footer_fssai_license', 'option');

// Certifications
$certifications = get_field('certifications', 'option');

// Contact Information
$footer_address = get_field('footer_address', 'option');
$footer_phone   = get_field('footer_phone', 'option');
$footer_email   = get_field('footer_email', 'option');

// Footer Bottom
$footer_copyright = get_field('footer_copyright', 'option');
$footer_tagline   = get_field('footer_tagline', 'option');
?>


<!-- =========================================================
     CERTIFICATION SECTION (HOME PAGE ONLY)
========================================================= -->

<?php if ( ( is_front_page() || is_home() ) && $certifications ) : ?>
    <section class="cert-section">
        <div class="container">
            <div class="cert-grid">
                <?php foreach ($certifications as $cert) : ?>
                    <?php
                    $cert_icon        = $cert['cert_icon'] ?? '';
                    $cert_logo        = $cert['cert_logo'] ?? '';
                    $cert_title       = $cert['cert_title'] ?? '';
                    $cert_description = $cert['cert_description'] ?? '';
                    ?>
                    <div class="cert-card">
						<!-- Certification Logo -->
                        <?php if ($cert_logo) : ?>
                            <?php
                            $cert_logo_url = is_array($cert_logo) ? $cert_logo['url'] : wp_get_attachment_image_url( $cert_logo, 'full' );
                            ?>
                            <?php if ($cert_logo_url) : ?>
                                <img src="<?php echo esc_url( add_query_arg( 'v', '2.0', $cert_logo_url ) ); ?>" alt="<?php echo esc_attr($cert_title); ?>" class="fssai-cert-logo">
                            <?php endif; ?>
                        <?php elseif ($cert_icon) : ?>
                            <!-- Certification Icon -->
                            <i class="<?php echo esc_attr($cert_icon); ?>"></i>
                        <?php endif; ?>
                        <!-- Certification Title -->
                        <?php if ($cert_title) : ?>
                            <h4><?php echo esc_html($cert_title); ?></h4>
                        <?php endif; ?>
                        <!-- Certification Description -->
                        <?php if ($cert_description) : ?>
                            <p><?php echo esc_html($cert_description); ?></p>
                        <?php endif; ?>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </section>
<?php endif; ?>

<!-- =========================================================
     FOOTER
========================================================= -->

<footer>
    <div class="container footer-grid">
        <!-- =================================================
             FOOTER ABOUT
        ================================================== -->
        <div class="footer-col footer-about">
            <h4><?php echo esc_html( get_field('header_brand_title', 'option') ?: get_bloginfo('name') ); ?></h4>
            <?php if ($footer_description) : ?>
                <p><?php echo esc_html($footer_description); ?></p>
            <?php endif; ?>
            <!-- FSSAI -->
            <?php if ($footer_fssai_logo || $footer_fssai_license) : ?>
                <div class="footer-fssai-info" style="color: var(--accent); display: flex; align-items: center; gap: 8px; font-size: 0.85rem; margin-top: 10px;">
                    <?php if ($footer_fssai_logo) : ?>
                        <?php
                        $footer_logo_url = is_array($footer_fssai_logo) ? $footer_fssai_logo['url'] : wp_get_attachment_image_url( $footer_fssai_logo, 'full' );
                        ?>
                        <?php if ($footer_logo_url) : ?>
                            <img src="<?php echo esc_url( add_query_arg( 'v', '2.0', $footer_logo_url ) ); ?>" alt="<?php echo esc_attr__('FSSAI Logo', 'asalcanningcenter'); ?>" class="fssai-footer-logo">
                        <?php endif; ?>
                    <?php endif; ?>
                    <?php if ($footer_fssai_license) : ?>
                        <span><strong><?php esc_html_e('FSSAI Lic. No.:','asalcanningcenter'); ?></strong> <?php echo esc_html($footer_fssai_license); ?></span>
                    <?php endif; ?>
                </div>
            <?php endif; ?>
            <!-- Trade Mark -->
            <div class="footer-trademark-info" style="color: var(--accent); display: flex; align-items: center; gap: 8px; font-size: 0.85rem; margin-top: 6px;">
                <i class="fa-solid fa-trademark"></i>
                <span><strong><?php esc_html_e('Trade Mark No.:', 'asalcanningcenter'); ?></strong> <?php echo esc_html( get_field('trademark_number', 'option') ?: '3340551' ); ?></span>
            </div>
        </div>

        <!-- =================================================
             QUICK LINKS
        ================================================== -->

        <div class="footer-col footer-links">
            <h4><?php esc_html_e('Quick Links', 'asalcanningcenter'); ?></h4>
            <?php
            wp_nav_menu(
                array(
                    'menu_id' => 'footer-menu-1',
					'menu' => 'Menu 1',
                    'container'      => false,
                    'menu_class'     => '',
                    'fallback_cb'    => false,
                )
            );
            ?>
        </div>

        <!-- =================================================
             PRESERVATION SERVICES
        ================================================== -->

        <div class="footer-col footer-links">
            <h4><?php esc_html_e('Preservation Services','asalcanningcenter'); ?>
            </h4>
            <?php
            wp_nav_menu(
                array(
                    'menu_id' => 'footer-menu-2',
					'menu' => 'Menu 2',
                    'container'      => false,
                    'menu_class'     => '',
                    'fallback_cb'    => false,
                )
            );
            ?>
        </div>


        <!-- =================================================
             CONTACT INFORMATION
        ================================================== -->

        <div class="footer-col">
            <h4><?php esc_html_e('Contact Us', 'asalcanningcenter'); ?></h4>
            <!-- Address -->
            <?php if ($footer_address) : ?>
                <p class="footer-contact-item" style="margin-bottom: 0.6rem;">
                    <i class="fa-solid fa-location-dot" style="color: var(--accent); margin-right: 6px;"></i>
                    <span><?php echo esc_html($footer_address); ?></span>
                </p>
            <?php endif; ?>
            <!-- Phone -->
            <?php if ($footer_phone) : ?>
                <p class="footer-contact-item" style="margin-bottom: 0.6rem;">
                    <i class="fa-solid fa-phone" style="color: var(--accent); margin-right: 6px;"></i>
                    <a href="tel:<?php echo esc_attr( preg_replace('/[^0-9+]/','',$footer_phone)); ?>"><?php echo esc_html($footer_phone); ?></a>
                </p>
            <?php endif; ?>
            <!-- Email -->
            <?php if ($footer_email) : ?>
                <p class="footer-contact-item" style="margin-bottom: 0.6rem;">
                    <i class="fa-solid fa-envelope" style="color: var(--accent); margin-right: 6px;"></i>
                    <a href="mailto:<?php echo esc_attr($footer_email); ?>"><?php echo esc_html($footer_email); ?></a>
                </p>
            <?php endif; ?>
        </div>
    </div>

    <!-- =====================================================
         FOOTER BOTTOM
    ====================================================== -->

    <div class="container footer-bottom">
        <p>
			<?php if ($footer_copyright) : ?>
				<?php echo esc_html($footer_copyright); ?>
            <?php else : ?>
                &copy;
                <?php echo esc_html(date('Y')); ?>
                <?php echo esc_html(get_bloginfo('name')); ?>.
                <?php esc_html_e('All Rights Reserved.','asalcanningcenter'); ?>
            <?php endif; ?>
            <span class="footer-tm-badge" style="margin-left: 12px; color: var(--accent); opacity: 0.95;">&bull; <?php esc_html_e('Trade Mark No.:', 'asalcanningcenter'); ?> <?php echo esc_html( get_field('trademark_number', 'option') ?: '3340551' ); ?></span>
        </p>
        <?php if ($footer_tagline) : ?>
            <p><?php echo esc_html($footer_tagline); ?></p>
        <?php endif; ?>
    </div>
</footer>
</div><!-- #page -->

<?php wp_footer(); ?>

</body>
</html>
