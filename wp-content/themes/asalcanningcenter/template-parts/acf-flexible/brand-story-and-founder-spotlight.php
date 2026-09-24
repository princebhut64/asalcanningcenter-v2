<?php
/**
 * Brand Story / Heritage Section
 */

$founder_image       = get_sub_field('founder_image');
$founder_name       = get_sub_field('founder_name');
$founder_role       = get_sub_field('founder_role');
$founder_description = get_sub_field('founder_description');

$founder_badges = get_sub_field('founder_badges');

$story_tag_icon      = get_sub_field('story_tag_icon');
$story_tag           = get_sub_field('story_tag');
$story_title         = get_sub_field('story_title');
$story_description_1 = get_sub_field('story_description');

$story_values = get_sub_field('story_values');
?>

<section class="story-section">

    <div class="container story-grid">

        <!-- Founder Card -->
        <div class="founder-card light-sheen-card gsap-reveal">

            <?php if ($founder_image) : ?>

                <div class="founder-img-box">

                    <img src="<?php echo $founder_image['url']; ?>" alt="Jalpa G. Patel" />

                </div>

            <?php endif; ?>


            <?php if ($founder_name) : ?>
                <h3>
                    <?php echo esc_html($founder_name); ?>
                </h3>
            <?php endif; ?>


            <?php if ($founder_role) : ?>
                <span class="role">
                    <?php echo esc_html($founder_role); ?>
                </span>
            <?php endif; ?>


            <?php if ($founder_description) : ?>
                <p>
                    <?php echo esc_html($founder_description); ?>
                </p>
            <?php endif; ?>


            <!-- Founder Badges -->
            <?php if ($founder_badges) : ?>

                <div class="founder-badges">

                    <?php foreach ($founder_badges as $badge) : ?>

                        <?php
                        $badge_icon = $badge['badge_icon'] ?? '';
                        $badge_text = $badge['badge_text'] ?? '';
                        ?>

                        <?php if ($badge_text) : ?>

                            <span class="mini-badge">

                                <?php if ($badge_icon) : ?>
                                    <i class="<?php echo esc_attr($badge_icon); ?>"></i>
                                <?php endif; ?>

                                <?php echo esc_html($badge_text); ?>

                            </span>

                        <?php endif; ?>

                    <?php endforeach; ?>

                </div>

            <?php endif; ?>

        </div>


        <!-- Story Content -->
        <div class="story-content gsap-reveal">

            <?php if ($story_tag || $story_tag_icon) : ?>

                <div class="section-tag">

                    <?php if ($story_tag_icon) : ?>
                        <i class="<?php echo esc_attr($story_tag_icon); ?>"></i>
                    <?php endif; ?>

                    <?php echo esc_html($story_tag); ?>

                </div>

            <?php endif; ?>


            <?php if ($story_title) : ?>

                <h2>
                    <?php echo esc_html($story_title); ?>
                </h2>

            <?php endif; ?>


            <?php if ($story_description_1) : ?>

                    <?php echo wpautop($story_description_1); ?>

            <?php endif; ?>

            <!-- Values -->
            <?php if ($story_values) : ?>

                <div class="values-grid">

                    <?php foreach ($story_values as $value) : ?>

                        <?php
                        $value_icon        = $value['value_icon'] ?? '';
                        $value_title       = $value['value_title'] ?? '';
                        $value_description = $value['value_description'] ?? '';
                        ?>

                        <div class="value-item">

                            <?php if ($value_icon) : ?>

                                <div class="value-icon">
                                    <i class="<?php echo esc_attr($value_icon); ?>"></i>
                                </div>

                            <?php endif; ?>


                            <div class="value-text">

                                <?php if ($value_title) : ?>

                                    <h4>
                                        <?php echo esc_html($value_title); ?>
                                    </h4>

                                <?php endif; ?>


                                <?php if ($value_description) : ?>

                                    <p>
                                        <?php echo esc_html($value_description); ?>
                                    </p>

                                <?php endif; ?>

                            </div>

                        </div>

                    <?php endforeach; ?>

                </div>

            <?php endif; ?>

        </div>

    </div>

</section>