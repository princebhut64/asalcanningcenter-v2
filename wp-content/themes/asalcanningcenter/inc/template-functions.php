<?php
/**
 * Functions which enhance the theme by hooking into WordPress
 *
 * @package asalcanningcenter
 */

/**
 * Adds custom classes to the array of body classes.
 *
 * @param array $classes Classes for the body element.
 * @return array
 */
function asalcanningcenter_body_classes( $classes ) {
	// Adds a class of hfeed to non-singular pages.
	if ( ! is_singular() ) {
		$classes[] = 'hfeed';
	}

	// Adds a class of no-sidebar when there is no sidebar present.
	if ( ! is_active_sidebar( 'sidebar-1' ) ) {
		$classes[] = 'no-sidebar';
	}

	return $classes;
}
add_filter( 'body_class', 'asalcanningcenter_body_classes' );

/**
 * Add a pingback url auto-discovery header for single posts, pages, or attachments.
 */
function asalcanningcenter_pingback_header() {
	if ( is_singular() && pings_open() ) {
		printf( '<link rel="pingback" href="%s">', esc_url( get_bloginfo( 'pingback_url' ) ) );
	}
}
add_action( 'wp_head', 'asalcanningcenter_pingback_header' );


/**
 * Products Custom Post Type
 * Taxonomies:
 * - Product Category
 * - Packaging Type
 */

function register_products_cpt_and_taxonomies() {

    /* =========================================================
     * PRODUCT CUSTOM POST TYPE
     * ========================================================= */
    $labels = array(
        'name'                  => 'Products',
        'singular_name'         => 'Product',
        'menu_name'             => 'Products',
        'name_admin_bar'        => 'Product',
        'add_new'               => 'Add New',
        'add_new_item'          => 'Add New Product',
        'new_item'              => 'New Product',
        'edit_item'             => 'Edit Product',
        'view_item'             => 'View Product',
        'all_items'             => 'All Products',
        'search_items'          => 'Search Products',
        'not_found'             => 'No products found.',
        'not_found_in_trash'    => 'No products found in Trash.',
    );

    $args = array(
        'labels'             => $labels,
        'public'             => true,
        'show_ui'            => true,
        'show_in_menu'       => true,
        'menu_position'      => 5,
        'menu_icon'          => 'dashicons-products',

        'supports'           => array(
            'title',
            'editor',
            'thumbnail',
            'excerpt',
        ),

        'has_archive'        => false,

        'rewrite'            => array(
            'slug'       => 'products',
            'with_front' => false,
        ),

        'show_in_rest'       => true,
    );

    register_post_type('products', $args);


    /* =========================================================
     * PRODUCT CATEGORY TAXONOMY
     * ========================================================= */
    $category_labels = array(
        'name'              => 'Categories',
        'singular_name'     => 'Category',
        'search_items'      => 'Search Categories',
        'all_items'         => 'All Categories',
        'parent_item'       => 'Parent Category',
        'parent_item_colon' => 'Parent Category:',
        'edit_item'         => 'Edit Category',
        'update_item'       => 'Update Category',
        'add_new_item'      => 'Add New Category',
        'new_item_name'     => 'New Category Name',
        'menu_name'         => 'Category',
    );

    $category_args = array(
        'labels'            => $category_labels,
        'public'            => true,
        'hierarchical'      => true,

        'show_ui'           => true,
        'show_admin_column' => true,
        'show_in_rest'      => true,

        'rewrite'           => array(
            'slug' => 'product-category',
        ),
    );

    register_taxonomy(
        'product_category',
        array('products'),
        $category_args
    );


    /* =========================================================
     * PACKAGING TYPE TAXONOMY
     * ========================================================= */
    $packaging_labels = array(
        'name'              => 'Packaging Types',
        'singular_name'     => 'Packaging Type',
        'search_items'      => 'Search Packaging Types',
        'all_items'         => 'All Packaging Types',
        'parent_item'       => 'Parent Packaging Type',
        'parent_item_colon' => 'Parent Packaging Type:',
        'edit_item'         => 'Edit Packaging Type',
        'update_item'       => 'Update Packaging Type',
        'add_new_item'      => 'Add New Packaging Type',
        'new_item_name'     => 'New Packaging Type Name',
        'menu_name'         => 'Packaging Type',
    );

    $packaging_args = array(
        'labels'            => $packaging_labels,
        'public'            => true,
        'hierarchical'      => true,

        'show_ui'           => true,
        'show_admin_column' => true,
        'show_in_rest'      => true,

        'rewrite'           => array(
            'slug' => 'packaging-type',
        ),
    );

    register_taxonomy(
        'packaging_type',
        array('products'),
        $packaging_args
    );
}

add_action(
    'init',
    'register_products_cpt_and_taxonomies'
);



/**
 * ------------------------------------------------------------
 * GET PRODUCTS CATALOG DATA
 * ------------------------------------------------------------
 */
function asal_get_products_catalog_data() {

    $products = [];

    $query = new WP_Query([
        'post_type'      => 'products',
        'post_status'    => 'publish',
        'posts_per_page' => -1,
        'orderby'        => 'title',
        'order'          => 'ASC',
        'no_found_rows'  => true,
    ]);

    if ($query->have_posts()) {

        while ($query->have_posts()) {

            $query->the_post();

            $product_id = get_the_ID();


            /*
             * ==================================================
             * PRODUCT CATEGORY
             * ==================================================
             */

            $categories = get_the_terms(
                $product_id,
                'product_category'
            );

            $main_category = '';
            $sub_category  = '';

            if (
                !empty($categories) &&
                !is_wp_error($categories)
            ) {

                /*
                 * First find parent category
                 */
                foreach ($categories as $category) {

                    if ((int) $category->parent === 0) {

                        $main_category = $category->name;

                        break;
                    }
                }


                /*
                 * Then find child category
                 */
                foreach ($categories as $category) {

                    if ((int) $category->parent !== 0) {

                        $sub_category = $category->name;

                        break;
                    }
                }


                /*
                 * If no parent was found
                 */
                if (empty($main_category)) {

                    $main_category = $categories[0]->name;
                }


                /*
                 * If no child category exists,
                 * use the first category as sub category
                 */
                if (
                    empty($sub_category) &&
                    count($categories) === 1
                ) {

                    $sub_category = $categories[0]->name;
                }
            }


            /*
             * ==================================================
             * PACKAGING TYPE
             * ==================================================
             */

            $packaging_terms = get_the_terms(
                $product_id,
                'packaging_type'
            );

            $packaging_types = [];

            if (
                !empty($packaging_terms) &&
                !is_wp_error($packaging_terms)
            ) {

                foreach ($packaging_terms as $packaging_term) {

                    $packaging_types[] = $packaging_term->name;
                }
            }


            /*
             * ==================================================
             * FEATURED IMAGE
             * ==================================================
             */

            $image = get_the_post_thumbnail_url(
                $product_id,
                'large'
            );

            if (!$image) {

                $image = '';
            }


            /*
             * ==================================================
             * ACF FIELDS
             * ==================================================
             */

            $badge = get_field(
                'product_badge',
                $product_id
            );

            $description = get_field(
                'short_description',
                $product_id
            );

            $rating_text = get_field(
                'rating_text',
                $product_id
            );

            $product_packaging = get_field(
                'product_packaging',
                $product_id
            );

            $specification = get_field(
                'packaging_size',
                $product_id
            );

            $storage = get_field(
                'product_storage',
                $product_id
            );


            /*
             * ==================================================
             * PRODUCT DATA
             * ==================================================
             */

            $products[] = [

                'id' => $product_id,

                'slug' => get_post_field(
                    'post_name',
                    $product_id
                ),

                'url' => get_permalink(
                    $product_id
                ),

                'mainCategory' => $main_category,

                'subCategory' => $sub_category,

                'title' => get_the_title(
                    $product_id
                ),

                'badge' => $badge
                    ? wp_strip_all_tags($badge)
                    : '',

                'img' => $image,
                'rating_text' => $rating_text,

                'desc' => $description
                    ? wp_strip_all_tags($description)
                    : '',

                'pkg' => implode(
                    ', ',
                    $packaging_types
                ),

                'spec' => $specification
                    ? wp_strip_all_tags($specification)
                    : '',

                'storage' => $storage
                    ? wp_strip_all_tags($storage)
                    : '',

                'packagingTypes' => $packaging_types,
            ];
        }

        wp_reset_postdata();
    }


    return $products;
}