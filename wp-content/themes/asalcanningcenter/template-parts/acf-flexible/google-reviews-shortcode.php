<?php
$google_reviews_shortcode = get_sub_field('code');
$reviews_tag              = get_sub_field('reviews_tag') ?: 'Google Verified Reviews';
$reviews_title            = get_sub_field('reviews_title') ?: 'What Our Clients & Partners Say';
$reviews_description      = get_sub_field('reviews_description') ?: 'Genuine experiences from families, orchard growers, and food entrepreneurs who trust Asal Canning Center for authentic flavor and reliable preservation.';

if ($google_reviews_shortcode) :
?>
<section class="google-reviews-section" style="padding: 5rem 0; background: var(--white); border-top: 1px solid var(--border-light); border-bottom: 1px solid var(--border-light); position: relative;">
    <div class="container">
        <!-- Section Header -->
        <div class="section-header text-center gsap-reveal" style="max-width: 780px; margin: 0 auto 2.5rem;">
            <div class="section-tag" style="justify-content: center; margin-bottom: 0.75rem;">
                <i class="fa-brands fa-google" style="color: #4285F4;"></i>
                <?php echo esc_html($reviews_tag); ?>
            </div>

            <h2 class="heading-serif" style="font-size: clamp(2rem, 3.5vw, 2.5rem); color: var(--primary); margin-bottom: 0.65rem;">
                <?php echo esc_html($reviews_title); ?>
            </h2>

            <p style="color: var(--text-muted); font-size: 1.05rem; line-height: 1.6;">
                <?php echo esc_html($reviews_description); ?>
            </p>

            <div class="reviews-trust-pill" style="display: inline-flex; align-items: center; gap: 0.65rem; margin-top: 1rem; padding: 0.45rem 1.25rem; background: var(--cream); border-radius: var(--radius-full); border: 1px solid var(--border-gold); box-shadow: var(--shadow-sm);">
                <div style="color: #F4B400; font-size: 0.95rem; letter-spacing: 2px;">
                    <i class="fa-solid fa-star"></i>
                    <i class="fa-solid fa-star"></i>
                    <i class="fa-solid fa-star"></i>
                    <i class="fa-solid fa-star"></i>
                    <i class="fa-solid fa-star"></i>
                </div>
                <span style="font-weight: 700; color: var(--primary); font-size: 0.9rem;">4.7 / 5.0 Rating</span>
                <span style="color: var(--text-muted); font-size: 0.825rem;">(306+ Google Reviews)</span>
            </div>
        </div>

        <!-- Google Reviews Shortcode Widget -->
        <div class="google-reviews-widget-wrap gsap-reveal" style="min-height: 180px; margin-top: 1.5rem;">
            <?php echo do_shortcode($google_reviews_shortcode); ?>
        </div>
    </div>
</section>
<?php endif; ?>