<?php
/**
 * HOME HERO SECTION
 */

$hero_badge_text          = get_sub_field('hero_badge_text');
$hero_title               = get_sub_field('hero_title');
$hero_title_second        = get_sub_field('hero_title_second');
$hero_title_highlight     = get_sub_field('hero_title_highlight');
$hero_description         = get_sub_field('hero_description');

$hero_primary_button_text = get_sub_field('hero_primary_button_text');
$hero_primary_button_url  = get_sub_field('hero_primary_button_url');

$hero_secondary_button_text = get_sub_field('hero_secondary_button_text');
$hero_secondary_button_url  = get_sub_field('hero_secondary_button_url');

$hero_image = get_sub_field('hero_image');

$hero_award_title       = get_sub_field('hero_award_title');
$hero_award_description = get_sub_field('hero_award_description');

$hero_features = get_sub_field('hero_features');
$stats         = get_sub_field('stats');
?>

<section class="hero-section">

    <div class="ambient-light-beam ambient-light-gold"></div>
    <div class="ambient-light-beam ambient-light-terracotta"></div>

    <div class="container hero-grid">

        <!-- HERO CONTENT -->
        <div class="hero-content">

            <?php if ($hero_badge_text) : ?>
                <div class="hero-badge">
                    <span class="hero-badge-pulse"></span>
                    <span><?php echo esc_html($hero_badge_text); ?></span>
                </div>
            <?php endif; ?>


            <?php if ($hero_title) : ?>
                <h1>
                    <?php echo esc_html($hero_title); ?>

                    <?php if ($hero_title_highlight) : ?>
                        <span>
                            <?php echo esc_html($hero_title_highlight); ?>
                        </span>
                    <?php endif; ?>
                    <?php echo esc_html($hero_title_second); ?>
                </h1>
            <?php endif; ?>


            <?php if ($hero_description) : ?>
                <p>
                    <?php echo esc_html($hero_description); ?>
                </p>
            <?php endif; ?>


            <!-- CTA BUTTONS -->
            <?php if ($hero_primary_button_text || $hero_secondary_button_text) : ?>

                <div class="hero-cta-group">

                    <?php if ($hero_primary_button_text && $hero_primary_button_url) : ?>
                        <a href="<?php echo esc_url($hero_primary_button_url); ?>"
                           class="btn btn-accent">

                            <?php echo esc_html($hero_primary_button_text); ?>

                            <i class="fa-solid fa-arrow-right"></i>
                        </a>
                    <?php endif; ?>


                    <?php if ($hero_secondary_button_text && $hero_secondary_button_url) : ?>
                        <a href="<?php echo esc_url($hero_secondary_button_url); ?>"
                           class="btn btn-outline-gold">

                            <?php echo esc_html($hero_secondary_button_text); ?>

                            <i class="fa-solid fa-headset"></i>
                        </a>
                    <?php endif; ?>

                </div>

            <?php endif; ?>


            <!-- HERO FEATURES -->
            <?php if ($hero_features) : ?>

                <div class="hero-features-list">

                    <?php foreach ($hero_features as $feature) : ?>

                        <?php
                        $feature_text = $feature['feature_text'] ?? '';
                        ?>

                        <?php if ($feature_text) : ?>

                            <div class="hero-feature-item">

                                <i class="fa-solid fa-circle-check"></i>

                                <span>
                                    <?php echo esc_html($feature_text); ?>
                                </span>

                            </div>

                        <?php endif; ?>

                    <?php endforeach; ?>

                </div>

            <?php endif; ?>

        </div>


        <!-- HERO IMAGE -->
        <div class="hero-visual">

            <?php if ($hero_image) : ?>

                <div class="hero-image-card light-sheen-card">

                    <img src="<?php echo $hero_image['url']; ?>" alt="Alphonso & Kesar Mango Pulp Processing" />

                </div>

            <?php endif; ?>


            <!-- AWARD BADGE -->
            <?php if ($hero_award_title || $hero_award_description) : ?>

                <div class="hero-glass-badge">

                    <div class="badge-icon">
                        <i class="fa-solid fa-award"></i>
                    </div>

                    <div>

                        <?php if ($hero_award_title) : ?>
                            <h4>
                                <?php echo esc_html($hero_award_title); ?>
                            </h4>
                        <?php endif; ?>

                        <?php if ($hero_award_description) : ?>
                            <p>
                                <?php echo esc_html($hero_award_description); ?>
                            </p>
                        <?php endif; ?>

                    </div>

                </div>

            <?php endif; ?>

        </div>

    </div>

</section>


<!-- FLOATING STATS -->
<?php if ($stats) : ?>

<section class="stats-section">

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

                                <span
                                    class="counter"
                                    data-target="<?php echo esc_attr($stat_number); ?>"
                                >
                                    0
                                </span>

                                <?php if ($stat_suffix) : ?>
                                    <?php echo esc_html($stat_suffix); ?>
                                <?php endif; ?>

                            </h3>

                        <?php endif; ?>


                        <?php if ($stat_title) : ?>

                            <p>
                                <?php echo esc_html($stat_title); ?>
                            </p>

                        <?php endif; ?>

                    </div>

                </div>

            <?php endforeach; ?>

        </div>

    </div>

</section>

<?php endif; ?>