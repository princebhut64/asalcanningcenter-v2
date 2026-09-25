<?php
$faq_tag         = get_sub_field('faq_tag');
$faq_title       = get_sub_field('faq_title');
$faq_description = get_sub_field('faq_description');
$faq_items       = get_sub_field('faq_items');
?>

<?php if ($faq_items) : ?>

<section class="faq-section" style="padding: 5rem 0; background: var(--cream-dark); border-top: 1px solid var(--border-light);">
    <div class="container" style="max-width: 1150px;">

        <!-- FAQ Header -->
        <div class="section-header text-center gsap-reveal"
             style="margin-bottom: 2.5rem;">

            <?php if ($faq_tag) : ?>
                <div class="section-tag"
                     style="margin: 0 auto 0.75rem; display: inline-flex;">

                    <i class="fa-solid fa-circle-question"></i>

                    <?php echo esc_html($faq_tag); ?>

                </div>
            <?php endif; ?>


            <?php if ($faq_title) : ?>
                <h2 class="heading-serif"
                    style="font-size: 2.5rem; color: var(--primary);">

                    <?php echo esc_html($faq_title); ?>

                </h2>
            <?php endif; ?>


            <?php if ($faq_description) : ?>
                <p style="color: var(--text-muted); font-size: 1rem;">

                    <?php echo esc_html($faq_description); ?>

                </p>
            <?php endif; ?>

        </div>


        <!-- FAQ Accordion -->
        <div class="faq-container gsap-reveal">

            <?php foreach ($faq_items as $index => $faq) :

                $question = $faq['faq_question'] ?? '';
                $answer   = $faq['faq_answer'] ?? '';

                if (!$question || !$answer) {
                    continue;
                }
            ?>

                <div class="faq-item">

                    <button
                        class="faq-header"
                        type="button"
                        aria-expanded="false"
                    >

                        <span>
                            <?php echo esc_html($question); ?>
                        </span>

                        <i class="fa-solid fa-chevron-down faq-icon"></i>

                    </button>


                    <div class="faq-body">

                        <div class="faq-content">
                            <?php echo wp_kses_post($answer); ?>
                        </div>

                    </div>

                </div>

            <?php endforeach; ?>

        </div>

    </div>

    <script>
    (function() {
        function splitFAQColumns() {
            const containers = document.querySelectorAll('.faq-container');
            containers.forEach(function(container) {
                if (container.querySelector('.faq-column')) return;
                const items = Array.from(container.querySelectorAll(':scope > .faq-item'));
                const total = items.length;
                if (total <= 1) return;

                // Divide items into 2 columns: left gets Math.ceil(total / 2)
                const leftCount = Math.ceil(total / 2);

                const leftCol = document.createElement('div');
                leftCol.className = 'faq-column faq-column-left';

                const rightCol = document.createElement('div');
                rightCol.className = 'faq-column faq-column-right';

                items.forEach(function(item, index) {
                    if (index < leftCount) {
                        leftCol.appendChild(item);
                    } else {
                        rightCol.appendChild(item);
                    }
                });

                container.innerHTML = '';
                container.appendChild(leftCol);
                container.appendChild(rightCol);
            });
        }

        if (document.readyState === 'loading') {
            document.addEventListener('DOMContentLoaded', splitFAQColumns);
        } else {
            splitFAQColumns();
        }
    })();
    </script>
</section>

<?php endif; ?>