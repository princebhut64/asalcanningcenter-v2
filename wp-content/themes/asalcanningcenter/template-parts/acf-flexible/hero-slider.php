<?php
/**
 * Hero Slider Template Part
 * 
 * Smooth crossfade slider with zero image blinking and seamless text transitions.
 * Supports Autoplay, Hover Pause, Touch Swipe, Keyboard Navigation, and Image Preloading.
 *
 * @package asalcanningcenter
 */

$slides = [];
if ( have_rows( 'slides' ) ) {
    while ( have_rows( 'slides' ) ) {
        the_row();
        $slide_image       = get_sub_field( 'slide_image' );
        $slide_title       = get_sub_field( 'slide_title' );
        $slide_description = get_sub_field( 'slide_description' );

        $img_url = '';
        $img_alt = '';
        if ( is_array( $slide_image ) ) {
            $img_url = $slide_image['url'] ?? '';
            $img_alt = $slide_image['alt'] ?? '';
        } elseif ( is_numeric( $slide_image ) ) {
            $img_url = wp_get_attachment_image_url( $slide_image, 'full' );
            $img_alt = get_post_meta( $slide_image, '_wp_attachment_image_alt', true );
        } elseif ( is_string( $slide_image ) ) {
            $img_url = $slide_image;
        }

        if ( ! empty( $img_url ) ) {
            $slides[] = [
                'url'   => $img_url,
                'alt'   => $img_alt ?: ( $slide_title ?: 'Asal Canning Center' ),
                'title' => $slide_title ?: '',
                'desc'  => $slide_description ?: '',
            ];
        }
    }
}

// Fallback if no slides configured
if ( empty( $slides ) ) {
    $brand_title    = get_field( 'header_brand_title', 'option' ) ?: 'ASAL CANNING CENTER';
    $brand_subtitle = get_field( 'header_brand_subtitle', 'option' ) ?: 'Cottage Industry & Food Processing Since 2000';
    $slides[] = [
        'url'   => '',
        'alt'   => $brand_title,
        'title' => $brand_title,
        'desc'  => $brand_subtitle,
    ];
}

$total_slides = count( $slides );
?>

<section class="wow-slider-wrapper hero-smooth-slider" id="heroSlider" aria-roledescription="carousel" aria-label="<?php esc_attr_e( 'Hero Highlights Slider', 'asalcanningcenter' ); ?>">

    <!-- Slides Viewport -->
    <div class="hero-slides-viewport" id="heroSlidesViewport">
        <?php foreach ( $slides as $index => $slide ) : ?>
            <div class="hero-slide-item <?php echo $index === 0 ? 'is-active' : ''; ?>"
                 data-slide-index="<?php echo esc_attr( $index ); ?>"
                 role="group"
                 aria-roledescription="slide"
                 aria-label="<?php echo esc_attr( sprintf( __( 'Slide %d of %d', 'asalcanningcenter' ), $index + 1, $total_slides ) ); ?>"
                 <?php echo $index !== 0 ? 'aria-hidden="true"' : ''; ?>>
                
                <div class="slide-bg-media"
                     style="<?php echo ! empty( $slide['url'] ) ? 'background-image: url(\'' . esc_url( $slide['url'] ) . '\');' : ''; ?>"
                     role="img"
                     aria-label="<?php echo esc_attr( $slide['alt'] ); ?>"></div>
                
                <div class="slider-overlay-gradient"></div>

                <?php if ( ! empty( $slide['title'] ) || ! empty( $slide['desc'] ) ) : ?>
                    <div class="hero-caption">
                        <?php if ( ! empty( $slide['title'] ) ) : ?>
                            <h2 class="slide-caption-title"><?php echo esc_html( $slide['title'] ); ?></h2>
                        <?php endif; ?>
                        <?php if ( ! empty( $slide['desc'] ) ) : ?>
                            <p class="slide-caption-desc"><?php echo esc_html( $slide['desc'] ); ?></p>
                        <?php endif; ?>
                    </div>
                <?php endif; ?>
            </div>
        <?php endforeach; ?>
    </div>

    <?php if ( $total_slides > 1 ) : ?>
        <!-- Slider Navigation Controls -->
        <button class="slider-nav-btn prev"
                id="heroSliderPrevBtn"
                type="button"
                aria-label="<?php esc_attr_e( 'Previous Slide', 'asalcanningcenter' ); ?>">
            <i class="fa-solid fa-chevron-left" aria-hidden="true"></i>
        </button>

        <button class="slider-nav-btn next"
                id="heroSliderNextBtn"
                type="button"
                aria-label="<?php esc_attr_e( 'Next Slide', 'asalcanningcenter' ); ?>">
            <i class="fa-solid fa-chevron-right" aria-hidden="true"></i>
        </button>

        <!-- Slider Pagination Dots -->
        <div class="slider-dots-box" id="heroDotsContainer" role="tablist" aria-label="<?php esc_attr_e( 'Choose slide to display', 'asalcanningcenter' ); ?>">
            <?php foreach ( $slides as $index => $slide ) : ?>
                <button class="dot-pill <?php echo $index === 0 ? 'active' : ''; ?>"
                        type="button"
                        role="tab"
                        data-slide-target="<?php echo esc_attr( $index ); ?>"
                        aria-selected="<?php echo $index === 0 ? 'true' : 'false'; ?>"
                        aria-label="<?php echo esc_attr( sprintf( __( 'Go to slide %d', 'asalcanningcenter' ), $index + 1 ) ); ?>">
                </button>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>

</section>

<script>
(function () {
    'use strict';

    function initHeroSlider() {
        const slider = document.getElementById('heroSlider');
        if (!slider) return;

        const slides = Array.from(slider.querySelectorAll('.hero-slide-item'));
        const totalSlides = slides.length;
        if (totalSlides <= 1) return;

        const prevBtn = document.getElementById('heroSliderPrevBtn');
        const nextBtn = document.getElementById('heroSliderNextBtn');
        const dotsContainer = document.getElementById('heroDotsContainer');
        const dots = dotsContainer ? Array.from(dotsContainer.querySelectorAll('.dot-pill')) : [];

        let currentIndex = 0;
        let isTransitioning = false;
        let autoTimer = null;
        const AUTO_DELAY = 5500;
        const TRANSITION_DURATION = 850;

        // Preload all slide images to prevent network flash
        slides.forEach(slide => {
            const bgEl = slide.querySelector('.slide-bg-media');
            if (bgEl) {
                const match = bgEl.style.backgroundImage.match(/url\(["']?([^"')]+)["']?\)/);
                if (match && match[1]) {
                    const img = new Image();
                    img.src = match[1];
                }
            }
        });

        function updateDots(newIndex) {
            dots.forEach((dot, idx) => {
                const isActive = idx === newIndex;
                dot.classList.toggle('active', isActive);
                dot.setAttribute('aria-selected', isActive ? 'true' : 'false');
            });
        }

        function goToSlide(nextIndex, direction) {
            if (isTransitioning || nextIndex === currentIndex) return;
            if (nextIndex < 0) nextIndex = totalSlides - 1;
            if (nextIndex >= totalSlides) nextIndex = 0;

            isTransitioning = true;

            const currentSlide = slides[currentIndex];
            const nextSlide = slides[nextIndex];

            // Setup aria attributes
            nextSlide.setAttribute('aria-hidden', 'false');
            currentSlide.setAttribute('aria-hidden', 'true');

            // Set current slide to leaving (remains visible at z-index 2 underneath incoming active slide at z-index 3)
            currentSlide.classList.add('is-leaving');
            currentSlide.classList.remove('is-active');

            // Force reflow for smooth start
            void nextSlide.offsetWidth;

            // Activate incoming slide
            nextSlide.classList.add('is-active');

            updateDots(nextIndex);
            currentIndex = nextIndex;

            // Once crossfade duration finishes, clean up leaving class and unlock
            setTimeout(() => {
                currentSlide.classList.remove('is-leaving');
                isTransitioning = false;
            }, TRANSITION_DURATION);
        }

        function nextSlide() {
            goToSlide(currentIndex + 1, 'next');
        }

        function prevSlide() {
            goToSlide(currentIndex - 1, 'prev');
        }

        // Arrow button listeners
        if (nextBtn) {
            nextBtn.addEventListener('click', (e) => {
                e.preventDefault();
                nextSlide();
                restartTimer();
            });
        }

        if (prevBtn) {
            prevBtn.addEventListener('click', (e) => {
                e.preventDefault();
                prevSlide();
                restartTimer();
            });
        }

        // Dot button listeners
        dots.forEach((dot, idx) => {
            dot.addEventListener('click', (e) => {
                e.preventDefault();
                goToSlide(idx, idx > currentIndex ? 'next' : 'prev');
                restartTimer();
            });
        });

        // Autoplay timer controls
        function startTimer() {
            stopTimer();
            autoTimer = setInterval(() => {
                if (!isTransitioning) {
                    nextSlide();
                }
            }, AUTO_DELAY);
        }

        function stopTimer() {
            if (autoTimer) {
                clearInterval(autoTimer);
                autoTimer = null;
            }
        }

        function restartTimer() {
            stopTimer();
            startTimer();
        }

        // Pause on Hover so users can read slide without sudden change
        slider.addEventListener('mouseenter', stopTimer);
        slider.addEventListener('mouseleave', startTimer);

        // Pause when tab is not active
        document.addEventListener('visibilitychange', () => {
            if (document.hidden) {
                stopTimer();
            } else {
                startTimer();
            }
        });

        // Touch Swipe Navigation for mobile devices
        let touchStartX = 0;
        let touchStartY = 0;
        let touchEndX = 0;
        let touchEndY = 0;

        slider.addEventListener('touchstart', (e) => {
            if (!e.changedTouches || !e.changedTouches.length) return;
            touchStartX = e.changedTouches[0].screenX;
            touchStartY = e.changedTouches[0].screenY;
        }, { passive: true });

        slider.addEventListener('touchend', (e) => {
            if (!e.changedTouches || !e.changedTouches.length) return;
            touchEndX = e.changedTouches[0].screenX;
            touchEndY = e.changedTouches[0].screenY;
            handleSwipe();
        }, { passive: true });

        function handleSwipe() {
            const diffX = touchEndX - touchStartX;
            const diffY = touchEndY - touchStartY;
            if (Math.abs(diffX) > Math.abs(diffY) && Math.abs(diffX) > 40) {
                if (diffX < 0) {
                    nextSlide();
                } else {
                    prevSlide();
                }
                restartTimer();
            }
        }

        // Keyboard Arrow Navigation
        window.addEventListener('keydown', (e) => {
            // Only if hero slider is partially or fully in view
            const rect = slider.getBoundingClientRect();
            const inView = rect.top < window.innerHeight && rect.bottom > 0;
            if (!inView) return;

            if (e.key === 'ArrowRight') {
                nextSlide();
                restartTimer();
            } else if (e.key === 'ArrowLeft') {
                prevSlide();
                restartTimer();
            }
        });

        // Initialize autoplay
        startTimer();
    }

    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', initHeroSlider);
    } else {
        initHeroSlider();
    }
})();
</script>