<section style="padding: 4rem 0; background: var(--cream-dark);" class="wow-slider-wrapper" id="heroSlider">

    <div class="slide-bg-base" id="slideBg"></div>

    <div class="slice-canvas" id="sliceCanvas"></div>

    <div class="slider-overlay-gradient"></div>

    <div class="hero-caption" id="sliderCaption" style="z-index: 10; pointer-events: none;">

        <h2 id="captionTitle" style="color: #ffffff !important; text-shadow: 0 4px 20px rgba(0,0,0,0.95), 0 2px 6px rgba(0,0,0,0.98); font-weight: 800;"></h2>

        <p id="captionDesc" style="color: #f7fafc !important; text-shadow: 0 2px 12px rgba(0,0,0,0.9), 0 1px 4px rgba(0,0,0,0.95); font-size: clamp(1rem, 2vw, 1.25rem); font-weight: 500;"></p>

    </div>


    <button
        class="slider-nav-btn prev"
        onclick="prevSliceSlide()"
        aria-label="Previous Slide"
    >
        <i class="fa-solid fa-chevron-left"></i>
    </button>


    <button
        class="slider-nav-btn next"
        onclick="nextSliceSlide()"
        aria-label="Next Slide"
    >
        <i class="fa-solid fa-chevron-right"></i>
    </button>


    <div
        class="slider-dots-box"
        id="dotsContainer"
    ></div>

</section>


<script>

const slidesData = [
    <?php
    if (have_rows('slides')) :
        while (have_rows('slides')) : the_row();

            $slide_image       = get_sub_field('slide_image');
            $slide_title       = get_sub_field('slide_title');
            $slide_description = get_sub_field('slide_description');

            if ($slide_image) :
    ?>
    {
        url: <?php echo wp_json_encode($slide_image); ?>,
        title: <?php echo wp_json_encode($slide_title); ?>,
        desc: <?php echo wp_json_encode($slide_description); ?>
    },
    <?php
            endif;

        endwhile;
    endif;
    ?>
];


let currentSlide = 0;
let isTransitioning = false;

const NUM_SLICES = 8;
const SLICE_DURATION = 800;
const AUTO_SLIDE_TIME = 5000;


const sliceCanvas = document.getElementById('sliceCanvas');
const slideBg = document.getElementById('slideBg');
const captionTitle = document.getElementById('captionTitle');
const captionDesc = document.getElementById('captionDesc');
const captionBox = document.getElementById('sliderCaption');
const dotsContainer = document.getElementById('dotsContainer');

let autoSlideTimer = null;



/* =========================================================
   INITIALIZE DOTS
========================================================= */

function createDots() {

    dotsContainer.innerHTML = '';

    slidesData.forEach((slide, index) => {

        const dot = document.createElement('span');

        dot.className = 'dot-pill';

        if (index === 0) {
            dot.classList.add('active');
        }

        dot.addEventListener('click', function () {
            goToSliceSlide(index);
        });

        dotsContainer.appendChild(dot);

    });

}



/* =========================================================
   UPDATE DOTS
========================================================= */

function updateDots(index) {

    const dots =
        dotsContainer.querySelectorAll('.dot-pill');

    dots.forEach((dot, i) => {

        dot.classList.toggle(
            'active',
            i === index
        );

    });

}



/* =========================================================
   INITIAL SLIDE
========================================================= */

function setInitialSlide() {

    if (!slidesData.length) {
        return;
    }

    const firstSlide = slidesData[0];


    /*
     * Set background immediately
     */

    slideBg.style.backgroundImage =
        'url("' + firstSlide.url + '")';


    /*
     * Set caption
     */

    captionTitle.textContent =
        firstSlide.title || '';

    captionDesc.textContent =
        firstSlide.desc || '';


    /*
     * Make sure caption is visible
     */

    captionBox.style.opacity = '1';

    captionBox.style.transform =
        'translateX(-50%) translateY(0)';

}



/* =========================================================
   CREATE SLICE
========================================================= */

function createSlice(
    index,
    slide,
    direction
) {

    const sliceWidth =
        100 / NUM_SLICES;


    const col =
        document.createElement('div');

    col.className =
        'slice-col';


    col.style.width =
        sliceWidth + '%';


    /*
     * Inner image
     */

    const inner =
        document.createElement('div');

    inner.className =
        'slice-col-inner';


    inner.style.backgroundImage =
        'url("' + slide.url + '")';


    /*
     * Important:
     * Every slice uses the same full image.
     */

    inner.style.width =
        NUM_SLICES * 100 + '%';


    inner.style.left =
        -(index * 100) + '%';


    /*
     * Starting position
     */

    if (direction === 'next') {

        if (index % 2 === 0) {

            col.style.transform =
                'translateY(-70px) rotateY(-45deg) scale(.88)';

        } else {

            col.style.transform =
                'translateY(70px) rotateY(-45deg) scale(.88)';

        }

    } else {

        if (index % 2 === 0) {

            col.style.transform =
                'translateY(70px) rotateY(45deg) scale(.88)';

        } else {

            col.style.transform =
                'translateY(-70px) rotateY(45deg) scale(.88)';

        }

    }


    col.style.opacity = '0';


    /*
     * Add inner image
     */

    col.appendChild(inner);

    sliceCanvas.appendChild(col);


    /*
     * Force browser repaint
     */

    col.offsetHeight;


    /*
     * Animate slice
     */

    requestAnimationFrame(() => {

        col.style.opacity = '1';

        col.style.transform =
            'translateY(0) rotateY(0deg) scale(1)';

    });

}



/* =========================================================
   CHANGE SLIDE
========================================================= */

function triggerSliceTransition(
    nextIdx,
    direction = 'next'
) {

    /*
     * Prevent multiple clicks
     */

    if (isTransitioning) {
        return;
    }


    /*
     * Invalid slide
     */

    if (
        !slidesData[nextIdx] ||
        nextIdx === currentSlide
    ) {
        return;
    }


    isTransitioning = true;


    const nextSlide =
        slidesData[nextIdx];


    /*
     * Clear old slices
     */

    sliceCanvas.innerHTML = '';


    /*
     * Caption out
     */

    captionBox.style.opacity = '0';

    captionBox.style.transform =
        'translateX(-50%) translateY(20px)';



    /*
     * Create all slices
     */

    for (
        let i = 0;
        i < NUM_SLICES;
        i++
    ) {

        createSlice(
            i,
            nextSlide,
            direction
        );

    }


    /*
     * Update dots immediately
     */

    updateDots(nextIdx);



    /*
     * Wait until slices finish
     */

    setTimeout(() => {


        /*
         * IMPORTANT:
         *
         * Change background only AFTER
         * slice animation is completed.
         */

        slideBg.style.backgroundImage =
            'url("' + nextSlide.url + '")';



        /*
         * Update caption
         */

        captionTitle.textContent =
            nextSlide.title || '';

        captionDesc.textContent =
            nextSlide.desc || '';



        /*
         * Caption back in
         */

        captionBox.style.opacity = '1';

        captionBox.style.transform =
            'translateX(-50%) translateY(0)';



        /*
         * Remove slices
         *
         * Background underneath is already
         * the new slide.
         */

        sliceCanvas.innerHTML = '';



        /*
         * Update current slide
         */

        currentSlide =
            nextIdx;


        /*
         * Unlock slider
         */

        isTransitioning =
            false;


    }, SLICE_DURATION + 100);

}



/* =========================================================
   NEXT
========================================================= */

function nextSliceSlide() {

    if (
        !slidesData.length ||
        isTransitioning
    ) {
        return;
    }


    const next =
        (currentSlide + 1)
        % slidesData.length;


    triggerSliceTransition(
        next,
        'next'
    );


    resetSliderTimer();

}



/* =========================================================
   PREVIOUS
========================================================= */

function prevSliceSlide() {

    if (
        !slidesData.length ||
        isTransitioning
    ) {
        return;
    }


    const previous =
        (
            currentSlide -
            1 +
            slidesData.length
        )
        % slidesData.length;


    triggerSliceTransition(
        previous,
        'prev'
    );


    resetSliderTimer();

}



/* =========================================================
   DOT CLICK
========================================================= */

function goToSliceSlide(index) {

    if (
        isTransitioning ||
        index === currentSlide ||
        !slidesData[index]
    ) {
        return;
    }


    /*
     * Determine direction
     */

    let direction;


    if (index > currentSlide) {

        direction = 'next';

    } else {

        direction = 'prev';

    }


    triggerSliceTransition(
        index,
        direction
    );


    resetSliderTimer();

}



/* =========================================================
   START AUTOPLAY
========================================================= */

function startSliderTimer() {

    clearInterval(autoSlideTimer);


    if (slidesData.length <= 1) {
        return;
    }


    autoSlideTimer =
        setInterval(() => {

            if (!isTransitioning) {

                nextSliceSlide();

            }

        }, AUTO_SLIDE_TIME);

}



/* =========================================================
   RESET AUTOPLAY
========================================================= */

function resetSliderTimer() {

    clearInterval(autoSlideTimer);

    startSliderTimer();

}



/* =========================================================
   INITIALIZE
========================================================= */

if (slidesData.length > 0) {

    createDots();

    setInitialSlide();

    startSliderTimer();

}

</script>