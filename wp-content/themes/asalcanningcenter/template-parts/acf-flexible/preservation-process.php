<?php
$process_tag         = get_sub_field('process_tag');
$process_title       = get_sub_field('process_title');
$process_description = get_sub_field('process_description');
$process_steps       = get_sub_field('process_steps');
?>

<?php if ($process_steps) : ?>

<section class="process-section">
    <div class="container">

        <!-- Section Header -->
        <div class="section-header text-center gsap-reveal"
             style="max-width: 750px; margin: 0 auto 2.5rem;">

            <?php if ($process_tag) : ?>
                <div class="section-tag" style="justify-content: center;">
                    <i class="fa-solid fa-gears"></i>
                    <?php echo esc_html($process_tag); ?>
                </div>
            <?php endif; ?>

            <?php if ($process_title) : ?>
                <h2 class="heading-serif"
                    style="font-size: 2.5rem; color: var(--primary);">
                    <?php echo esc_html($process_title); ?>
                </h2>
            <?php endif; ?>

            <?php if ($process_description) : ?>
                <p style="color: var(--text-muted); font-size: 1rem;">
                    <?php echo esc_html($process_description); ?>
                </p>
            <?php endif; ?>

        </div>


        <!-- Process Steps -->
        <div class="process-grid gsap-grid-stagger">

            <?php
            $step_count = 1;

            foreach ($process_steps as $step) :

                $step_number      = $step['step_number'] ?? '';
                $step_title       = $step['step_title'] ?? '';
                $step_description = $step['step_description'] ?? '';

                // Auto number if step number is empty
                if (!$step_number) {
                    $step_number = str_pad($step_count, 2, '0', STR_PAD_LEFT);
                }
            ?>

                <div class="process-step light-sheen-card">

                    <?php if ($step_number) : ?>
                        <div class="step-num">
                            <?php echo esc_html($step_number); ?>
                        </div>
                    <?php endif; ?>

                    <?php if ($step_title) : ?>
                        <h3>
                            <?php echo esc_html($step_title); ?>
                        </h3>
                    <?php endif; ?>

                    <?php if ($step_description) : ?>
                        <p>
                            <?php echo esc_html($step_description); ?>
                        </p>
                    <?php endif; ?>

                </div>

            <?php
                $step_count++;
            endforeach;
            ?>

        </div>

    </div>
</section>

<?php endif; ?>