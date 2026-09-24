<?php
/**
 * Inner Page Hero Banner
 */

$hero_kicker = get_sub_field('inner_hero_kicker');
$hero_title  = get_sub_field('inner_hero_title');
$hero_description = get_sub_field('inner_hero_description');
?>

<!-- Inner Page Hero Banner -->
<section class="hero-section" style="min-height: 45vh; padding-top: 7rem; padding-bottom: 4rem;">

    <div class="ambient-light-beam ambient-light-gold"></div>

    <div
        class="container hero-slider-content text-center"
        style="max-width: 800px; margin: 0 auto; padding-bottom: 0; position: relative; z-index: 2;"
    >

        <?php if ($hero_kicker) : ?>

            <div class="hero-kicker" style="justify-content: center;">

                <span class="hero-kicker-line"></span>

                <?php echo esc_html($hero_kicker); ?>

            </div>

        <?php endif; ?>


        <?php if ($hero_title) : ?>

            <h1 class="heading-serif" style="font-size: 3.2rem; color: var(--primary); margin-bottom: 1rem;">
                <?php echo esc_html($hero_title); ?>
            </h1>

        <?php endif; ?>


        <?php if ($hero_description) : ?>

            <p style="color: var(--text-muted); font-size: 1.08rem; line-height: 1.65; max-width: 750px; margin: 0 auto 2rem;">
                <?php echo esc_html($hero_description); ?>
            </p>

        <?php endif; ?>

    </div>

</section>