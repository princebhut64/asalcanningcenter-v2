<?php

/* ==========================================================================
   BATCH YIELD & SHELF LIFE CALCULATOR - ACF DATA
   ========================================================================== */

$calculator_tag = get_sub_field('calculator_tag');
$calculator_title = get_sub_field('calculator_title');
$calculator_description = get_sub_field('calculator_description');

$produce_options = get_sub_field('produce_options');
$packaging_options = get_sub_field('packaging_options');
$weight_presets = get_sub_field('weight_presets');


/*
 * Fallback content
 */
if (!$calculator_tag) {
    $calculator_tag = 'Interactive Tool';
}

if (!$calculator_title) {
    $calculator_title = 'Batch Yield & Shelf Life Calculator';
}

if (!$calculator_description) {
    $calculator_description = 'Estimate your net pulp extraction yield and packed unit output for custom contract processing.';
}


/*
 * Prepare calculator data for JavaScript
 */
$calculator_data = array(
    'produce' => array(),
    'packaging' => array(),
);


/* --------------------------------------------------------------------------
   PRODUCE DATA
   -------------------------------------------------------------------------- */

if (!empty($produce_options)) {

    foreach ($produce_options as $produce) {

        $calculator_data['produce'][] = array(
            'value' => $produce['produce_value'] ?? '',
            'name' => $produce['produce_name'] ?? '',
            'yield' => floatval($produce['yield_percentage'] ?? 0),
            'extract' => $produce['extract_type'] ?? '',
        );

    }

}


/* --------------------------------------------------------------------------
   PACKAGING DATA
   -------------------------------------------------------------------------- */

if (!empty($packaging_options)) {

    foreach ($packaging_options as $package) {

        $calculator_data['packaging'][] = array(
            'value' => $package['package_value'] ?? '',
            'name' => $package['package_name'] ?? '',
            'unit_weight' => floatval($package['unit_weight'] ?? 0),
            'unit_label' => $package['unit_label'] ?? 'Units',
            'shelf_life' => $package['shelf_life'] ?? '',
            'specification' => $package['package_specification'] ?? '',
        );

    }

}

?>


<!-- ==========================================================================
     Batch Yield & Shelf Life Calculator Section
     ========================================================================== -->

<section style="padding: 4rem 0; background: var(--cream-dark);">

  <div class="container">

    <div class="section-header text-center gsap-reveal" style="max-width: 750px; margin: 0 auto 2rem;">

      <div class="section-tag" style="justify-content: center;">
        <i class="fa-solid fa-calculator"></i>

        <?php echo esc_html($calculator_tag); ?>

      </div>


      <h2 class="heading-serif" style="font-size: 2.5rem; color: var(--primary);">

        <?php echo esc_html($calculator_title); ?>

      </h2>


      <p style="color: var(--text-muted); font-size: 1rem;">

        <?php echo esc_html($calculator_description); ?>

      </p>

    </div>


    <div
      style="background: var(--white); padding: 2.25rem; border-radius: var(--radius-lg); border: 1px solid var(--border-light); box-shadow: var(--shadow-md); max-width: 850px; margin: 0 auto;"
      class="gsap-reveal"
    >


      <!-- ================================================================
           Produce + Weight
           ================================================================ -->

      <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1.25rem; margin-bottom: 1.25rem;">


        <!-- Produce -->
        <div>

          <label
            style="font-size: 0.85rem; font-weight: 700; color: var(--primary); display: block; margin-bottom: 0.4rem;"
            for="calcProduce"
          >
            Select Fruit / Produce Type
          </label>


          <select
            id="calcProduce"
            class="form-control"
            style="width: 100%; padding: 0.65rem 1rem; border-radius: var(--radius-sm); border: 1px solid var(--border-medium); font-size: 0.9rem;"
          >

            <?php if (!empty($produce_options)) : ?>

              <?php foreach ($produce_options as $index => $produce) : ?>

                <?php

                $produce_value = $produce['produce_value'] ?? '';

                $produce_name = $produce['produce_name'] ?? '';

                $yield_percentage = floatval(
                    $produce['yield_percentage'] ?? 0
                );

                ?>

                <option
                  value="<?php echo esc_attr($produce_value); ?>"
                  <?php selected($index, 0); ?>
                >
                  <?php echo esc_html($produce_name); ?>
                  (<?php echo esc_html($yield_percentage); ?>% Yield)
                </option>

              <?php endforeach; ?>

            <?php else : ?>

              <option value="mango">
                Alphonso / Kesar Mango (70% Pulp Yield)
              </option>

            <?php endif; ?>

          </select>

        </div>


        <!-- Weight -->
        <div>

          <label
            style="font-size: 0.85rem; font-weight: 700; color: var(--primary); display: block; margin-bottom: 0.4rem;"
            for="calcWeight"
          >
            Raw Crop Weight (KG)
          </label>


          <input
            type="number"
            id="calcWeight"
            value="100"
            min="1"
            step="0.1"
            class="form-control"
            style="width: 100%; padding: 0.65rem 1rem; border-radius: var(--radius-sm); border: 1px solid var(--border-medium); font-size: 0.9rem;"
          />

        </div>

      </div>


      <!-- ================================================================
           Packaging
           ================================================================ -->

      <div style="margin-bottom: 1.5rem;">

        <label
          style="font-size: 0.85rem; font-weight: 700; color: var(--primary); display: block; margin-bottom: 0.4rem;"
          for="calcPackage"
        >
          Packaging Format & Unit Weight
        </label>


        <select
          id="calcPackage"
          class="form-control"
          style="width: 100%; padding: 0.65rem 1rem; border-radius: var(--radius-sm); border: 1px solid var(--border-medium); font-size: 0.9rem;"
        >

          <?php if (!empty($packaging_options)) : ?>

            <?php foreach ($packaging_options as $index => $package) : ?>

              <?php

              $package_value = $package['package_value'] ?? '';

              $package_name = $package['package_name'] ?? '';

              $unit_weight = floatval(
                  $package['unit_weight'] ?? 0
              );

              $shelf_life = $package['shelf_life'] ?? '';

              ?>

              <option
                value="<?php echo esc_attr($package_value); ?>"
                <?php selected($index, 0); ?>
              >

                <?php echo esc_html($package_name); ?>

                <?php if ($shelf_life) : ?>

                  (<?php echo esc_html($shelf_life); ?> Shelf Life)

                <?php endif; ?>

              </option>

            <?php endforeach; ?>

          <?php else : ?>

            <option value="850g-can">
              850g Hermetic Tin Can (12 - 24 Months Shelf Life)
            </option>

          <?php endif; ?>

        </select>


        <!-- ==============================================================
             Presets
             ============================================================== -->

        <?php if (!empty($weight_presets)) : ?>

          <div class="calc-presets-row">

            <span
              style="font-size: 0.8rem; color: var(--primary); font-weight: 700;"
            >
              Quick Weight Presets:
            </span>


            <?php foreach ($weight_presets as $preset) : ?>

              <?php

              $preset_value = floatval(
                  $preset['weight_value'] ?? 0
              );

              ?>

              <button
                type="button"
                class="calc-preset-btn <?php echo ($preset_value == 100) ? 'active' : ''; ?>"
                data-val="<?php echo esc_attr($preset_value); ?>"
              >
                <?php echo esc_html($preset_value); ?> KG
              </button>

            <?php endforeach; ?>

          </div>

        <?php else : ?>

          <!-- Fallback presets -->

          <div class="calc-presets-row">

            <span
              style="font-size: 0.8rem; color: var(--primary); font-weight: 700;"
            >
              Quick Weight Presets:
            </span>

            <button
              type="button"
              class="calc-preset-btn"
              data-val="50"
            >
              50 KG
            </button>

            <button
              type="button"
              class="calc-preset-btn active"
              data-val="100"
            >
              100 KG
            </button>

            <button
              type="button"
              class="calc-preset-btn"
              data-val="250"
            >
              250 KG
            </button>

            <button
              type="button"
              class="calc-preset-btn"
              data-val="500"
            >
              500 KG
            </button>

          </div>

        <?php endif; ?>

      </div>


      <!-- ================================================================
           Result Box
           ================================================================ -->

      <div
        style="background: var(--cream-dark); padding: 1.35rem; border-radius: var(--radius-md); border: 1px solid var(--border-gold); text-align: center;"
      >

        <span
          style="font-size: 0.775rem; font-weight: 800; color: var(--terracotta); text-transform: uppercase; letter-spacing: 0.05em;"
        >
          Estimated Packed Production Output
        </span>


        <div
          id="outputUnits"
          style="font-family: var(--font-serif); font-size: 2.2rem; font-weight: 700; color: var(--primary); margin: 0.2rem 0;"
        >
          0 Units
        </div>


        <div
          id="outputShelf"
          style="font-size: 0.85rem; color: var(--accent-hover); font-weight: 700; margin-bottom: 0.5rem;"
        >
          <i class="fa-solid fa-clock"></i>
          Ambient Shelf Life: -
        </div>


        <p
          id="outputBreakdown"
          style="font-size: 0.8rem; color: var(--text-muted); margin: 0;"
        >
          <i
            class="fa-solid fa-chart-pie"
            style="color: var(--accent);"
          ></i>

          Batch Yield Summary
        </p>

      </div>

    </div>

  </div>

</section>


<!-- ==========================================================================
     ACF DATA FOR JAVASCRIPT
     ========================================================================== -->

<script>
window.batchCalculatorData = <?php echo wp_json_encode($calculator_data); ?>;
</script>