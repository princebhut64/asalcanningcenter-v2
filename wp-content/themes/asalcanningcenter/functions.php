<?php
/**
 * asalcanningcenter functions and definitions
 *
 * @link https://developer.wordpress.org/themes/basics/theme-functions/
 *
 * @package asalcanningcenter
 */

if ( ! defined( '_S_VERSION' ) ) {
	// Replace the version number of the theme on each release.
	define( '_S_VERSION', '3.9.0' );
}

/**
 * Sets up theme defaults and registers support for various WordPress features.
 *
 * Note that this function is hooked into the after_setup_theme hook, which
 * runs before the init hook. The init hook is too late for some features, such
 * as indicating support for post thumbnails.
 */
function asalcanningcenter_setup() {
	/*
		* Make theme available for translation.
		* Translations can be filed in the /languages/ directory.
		* If you're building a theme based on asalcanningcenter, use a find and replace
		* to change 'asalcanningcenter' to the name of your theme in all the template files.
		*/
	load_theme_textdomain( 'asalcanningcenter', get_template_directory() . '/languages' );

	// Add default posts and comments RSS feed links to head.
	add_theme_support( 'automatic-feed-links' );

	/*
		* Let WordPress manage the document title.
		* By adding theme support, we declare that this theme does not use a
		* hard-coded <title> tag in the document head, and expect WordPress to
		* provide it for us.
		*/
	add_theme_support( 'title-tag' );

	/*
		* Enable support for Post Thumbnails on posts and pages.
		*
		* @link https://developer.wordpress.org/themes/functionality/featured-images-post-thumbnails/
		*/
	add_theme_support( 'post-thumbnails' );

	// This theme uses wp_nav_menu() in one location.
	register_nav_menus(
		array(
			'menu-1' => esc_html__( 'Primary', 'asalcanningcenter' ),
		)
	);

	/*
		* Switch default core markup for search form, comment form, and comments
		* to output valid HTML5.
		*/
	add_theme_support(
		'html5',
		array(
			'search-form',
			'comment-form',
			'comment-list',
			'gallery',
			'caption',
			'style',
			'script',
		)
	);

	// Set up the WordPress core custom background feature.
	add_theme_support(
		'custom-background',
		apply_filters(
			'asalcanningcenter_custom_background_args',
			array(
				'default-color' => 'ffffff',
				'default-image' => '',
			)
		)
	);

	// Add theme support for selective refresh for widgets.
	add_theme_support( 'customize-selective-refresh-widgets' );

	/**
	 * Add support for core custom logo.
	 *
	 * @link https://codex.wordpress.org/Theme_Logo
	 */
	add_theme_support(
		'custom-logo',
		array(
			'height'      => 250,
			'width'       => 250,
			'flex-width'  => true,
			'flex-height' => true,
		)
	);
}
add_action( 'after_setup_theme', 'asalcanningcenter_setup' );

/**
 * Set the content width in pixels, based on the theme's design and stylesheet.
 *
 * Priority 0 to make it available to lower priority callbacks.
 *
 * @global int $content_width
 */
function asalcanningcenter_content_width() {
	$GLOBALS['content_width'] = apply_filters( 'asalcanningcenter_content_width', 640 );
}
add_action( 'after_setup_theme', 'asalcanningcenter_content_width', 0 );

/**
 * Register widget area.
 *
 * @link https://developer.wordpress.org/themes/functionality/sidebars/#registering-a-sidebar
 */
function asalcanningcenter_widgets_init() {
	register_sidebar(
		array(
			'name'          => esc_html__( 'Sidebar', 'asalcanningcenter' ),
			'id'            => 'sidebar-1',
			'description'   => esc_html__( 'Add widgets here.', 'asalcanningcenter' ),
			'before_widget' => '<section id="%1$s" class="widget %2$s">',
			'after_widget'  => '</section>',
			'before_title'  => '<h2 class="widget-title">',
			'after_title'   => '</h2>',
		)
	);
}
add_action( 'widgets_init', 'asalcanningcenter_widgets_init' );

/**
 * Enqueue scripts and styles.
 */
function asalcanningcenter_scripts() {
	wp_enqueue_style( 'font-awesome', "https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css", array(), _S_VERSION );
	wp_enqueue_style( 'asalcanningcenter-style', get_stylesheet_uri(), array(), _S_VERSION );
	wp_style_add_data( 'asalcanningcenter-style', 'rtl', 'replace' );

	/*
     * GSAP
     */
    wp_enqueue_script('gsap',get_template_directory_uri() . '/js/gsap.min.js',array(),'3.12.2',true);
	wp_enqueue_script('product-js',get_template_directory_uri() . '/js/products.js',array(),_S_VERSION,true);
	
    /*
	* GSAP ScrollTrigger
	*
	* Must load AFTER GSAP
	*/
    wp_enqueue_script('gsap-scrolltrigger',get_template_directory_uri() . '/js/ScrollTrigger.min.js',array('gsap'),'3.12.2',true);
	wp_enqueue_script( 'asalcanningcenter-site-motion', get_template_directory_uri() . '/js/site-motion.js', array(), _S_VERSION, true );
	wp_enqueue_script( 'asalcanningcenter-main', get_template_directory_uri() . '/js/main.js', array(), _S_VERSION, true );

	if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
		wp_enqueue_script( 'comment-reply' );
	}

	wp_localize_script(
        'product-js',
        'asalProductsData',
        [
            'products' => asal_get_products_catalog_data(),
        ]
    );
}
add_action( 'wp_enqueue_scripts', 'asalcanningcenter_scripts' );

/**
 * SPEED OPTIMIZATION: Defer non-critical scripts for faster First Contentful Paint (FCP)
 */
function asal_defer_scripts( $tag, $handle, $src ) {
    $defer_handles = [
        'gsap',
        'gsap-scrolltrigger',
        'asalcanningcenter-site-motion',
        'asalcanningcenter-main',
        'product-js',
    ];

    if ( in_array( $handle, $defer_handles, true ) ) {
        if ( false === strpos( $tag, ' defer' ) ) {
            $tag = str_replace( ' src', ' defer src', $tag );
        }
    }
    return $tag;
}
add_filter( 'script_loader_tag', 'asal_defer_scripts', 10, 3 );

/**
 * SPEED OPTIMIZATION: Disable WordPress emoji scripts & styles to reduce HTTP requests
 */
function asal_disable_emojis() {
    remove_action( 'wp_head', 'print_emoji_detection_script', 7 );
    remove_action( 'admin_print_scripts', 'print_emoji_detection_script' );
    remove_action( 'wp_print_styles', 'print_emoji_styles' );
    remove_action( 'admin_print_styles', 'print_emoji_styles' );
    remove_filter( 'the_content_feed', 'wp_staticize_emoji' );
    remove_filter( 'comment_text_rss', 'wp_staticize_emoji' );
    remove_filter( 'wp_mail', 'wp_staticize_emoji_for_email' );
    add_filter( 'tiny_mce_plugins', function( $plugins ) {
        return is_array( $plugins ) ? array_diff( $plugins, [ 'wpemoji' ] ) : [];
    });
}
add_action( 'init', 'asal_disable_emojis' );

/**
 * SPEED & SECURITY: Remove unnecessary metadata from <head>
 */
remove_action( 'wp_head', 'wp_generator' );
remove_action( 'wp_head', 'wlwmanifest_link' );
remove_action( 'wp_head', 'rsd_link' );

/**
 * Implement the Custom Header feature.
 */
require get_template_directory() . '/inc/custom-header.php';

/**
 * Custom template tags for this theme.
 */
require get_template_directory() . '/inc/template-tags.php';

/**
 * Functions which enhance the theme by hooking into WordPress.
 */
require get_template_directory() . '/inc/template-functions.php';

/**
 * Customizer additions.
 */
require get_template_directory() . '/inc/customizer.php';

/**
 * Load Jetpack compatibility file.
 */
if ( defined( 'JETPACK__VERSION' ) ) {
	require get_template_directory() . '/inc/jetpack.php';
}

/**
 * Contact Form 7 Handler & Inquiries Storage.
 */
require get_template_directory() . '/inc/contact-inquiries.php';

/**
 * Ensure 'Videos' category choice is always available in Media Archive ACF fields
 */
add_filter('acf/load_field/name=media_category', function($field) {
    if (isset($field['choices']) && is_array($field['choices'])) {
        if (!isset($field['choices']['videos'])) {
            $field['choices']['videos'] = __('Videos', 'asalcanningcenter');
        }
    }
    return $field;
});

/**
 * Custom Admin Styling for ACF Media Archive & Repeater Fields
 */
add_action('admin_head', function() {
    ?>
    <style>
        /* ACF Media Items Repeater Block & Table Styling */
        .acf-field-6aa6695e48573 .acf-repeater .acf-row {
            border: 1px solid #dcdcde !important;
            border-radius: 8px !important;
            margin-bottom: 12px !important;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.04) !important;
            transition: border-color 0.2s ease, box-shadow 0.2s ease !important;
            background: #ffffff !important;
            overflow: hidden !important;
        }
        .acf-field-6aa6695e48573 .acf-repeater .acf-row:hover {
            border-color: #E0A238 !important;
            box-shadow: 0 3px 10px rgba(224, 162, 56, 0.12) !important;
        }
        .acf-field-6aa6695e48573 .acf-repeater .acf-row-handle {
            background: #f6f7f7 !important;
            font-weight: 600 !important;
            color: #1d2327 !important;
        }
        .acf-field-6aa6695e48573 .acf-repeater .acf-row-handle .acf-row-number {
            font-size: 13px !important;
        }
        /* Ensure table layout fallback is never squished */
        .acf-field-6aa6695e48573 .acf-table {
            table-layout: auto !important;
        }
        .acf-field-6aa6695e48573 .acf-table th,
        .acf-field-6aa6695e48573 .acf-table td {
            vertical-align: middle !important;
            padding: 10px 8px !important;
        }
        .acf-field-6aa6695e48573 .acf-table td[data-name="media_title"] input {
            min-width: 180px !important;
        }
        .acf-field-6aa6695e48573 .acf-table td[data-name="media_date"] input {
            min-width: 110px !important;
        }
        .acf-field-6aa6695e48573 .acf-table td[data-name="media_category"] select,
        .acf-field-6aa6695e48573 .acf-table td[data-name="media_type"] select {
            min-width: 130px !important;
        }
        .acf-field-6aa6695e48573 .acf-table td[data-name="youtube_id"] input {
            min-width: 160px !important;
        }
        /* Highlight Video rows in admin */
        .acf-field-6aa6695e48573 .acf-field-6aa669c048579 input {
            border-color: #E0A238 !important;
            background: #fffdf8 !important;
        }

        /* =====================================================
         * PRODUCT ADD/EDIT SCREEN: Category & Packaging Boxes
         * ===================================================== */

        /* Make the taxonomy meta boxes bigger and easier to use */
        #product_categorydiv .inside,
        #packaging_typediv .inside {
            padding: 8px 12px 12px !important;
        }

        /* Scrollable checkbox list */
        #product_categorydiv .categorychecklist,
        #packaging_typediv .categorychecklist {
            max-height: 200px !important;
            overflow-y: auto !important;
            border: 1px solid #dcdcde !important;
            border-radius: 6px !important;
            padding: 8px 10px !important;
            margin-bottom: 10px !important;
            background: #fafafa !important;
        }

        /* Category checkbox items */
        #product_categorydiv .categorychecklist li,
        #packaging_typediv .categorychecklist li {
            padding: 3px 0 !important;
            line-height: 1.5 !important;
        }

        /* "Add New Category" toggle link */
        #product_categorydiv .category-add-toggle,
        #packaging_typediv .category-add-toggle {
            display: inline-block !important;
            color: #E0A238 !important;
            font-weight: 600 !important;
            font-size: 13px !important;
            cursor: pointer !important;
            padding: 4px 0 !important;
            margin-top: 6px !important;
            text-decoration: none !important;
            border: none !important;
            background: none !important;
        }
        #product_categorydiv .category-add-toggle:hover,
        #packaging_typediv .category-add-toggle:hover {
            color: #C98D28 !important;
            text-decoration: underline !important;
        }
        #product_categorydiv .category-add-toggle::before,
        #packaging_typediv .category-add-toggle::before {
            content: '+ ' !important;
        }

        /* "Add New Category" inline form panel */
        #product_categorydiv .category-adder,
        #packaging_typediv .category-adder {
            display: none; /* toggled by WordPress JS */
            margin-top: 10px !important;
            padding: 12px !important;
            background: #fffdf8 !important;
            border: 1px solid #E0A238 !important;
            border-radius: 8px !important;
            box-shadow: 0 2px 6px rgba(224, 162, 56, 0.12) !important;
        }

        /* New category name input */
        #product_categorydiv #newproduct_category,
        #packaging_typediv #newpackaging_type {
            width: 100% !important;
            padding: 8px 12px !important;
            border: 1px solid #dcdcde !important;
            border-radius: 6px !important;
            font-size: 13px !important;
            margin-bottom: 8px !important;
            box-sizing: border-box !important;
        }
        #product_categorydiv #newproduct_category:focus,
        #packaging_typediv #newpackaging_type:focus {
            border-color: #E0A238 !important;
            box-shadow: 0 0 0 2px rgba(224, 162, 56, 0.2) !important;
            outline: none !important;
        }

        /* Parent category dropdown inside the adder */
        #product_categorydiv #newproduct_category_parent,
        #packaging_typediv #newpackaging_type_parent {
            width: 100% !important;
            padding: 7px 10px !important;
            border: 1px solid #dcdcde !important;
            border-radius: 6px !important;
            font-size: 13px !important;
            margin-bottom: 8px !important;
            box-sizing: border-box !important;
            background: #fff !important;
        }

        /* "Add New Category" submit button */
        #product_categorydiv .button.category-add,
        #packaging_typediv .button.category-add {
            background: #E0A238 !important;
            border-color: #C98D28 !important;
            color: #fff !important;
            font-weight: 600 !important;
            border-radius: 6px !important;
            padding: 6px 16px !important;
            font-size: 13px !important;
            cursor: pointer !important;
            width: 100% !important;
            text-align: center !important;
        }
        #product_categorydiv .button.category-add:hover,
        #packaging_typediv .button.category-add:hover {
            background: #C98D28 !important;
            border-color: #B07E22 !important;
        }

        /* "Most Used" tab */
        #product_categorydiv .tabs-panel,
        #packaging_typediv .tabs-panel {
            border-radius: 6px !important;
        }
        #product_categorydiv ul.category-tabs,
        #packaging_typediv ul.category-tabs {
            display: flex !important;
            gap: 4px !important;
            margin-bottom: 8px !important;
            border-bottom: 2px solid #E0A238 !important;
        }
        #product_categorydiv ul.category-tabs li a,
        #packaging_typediv ul.category-tabs li a {
            padding: 5px 12px !important;
            font-size: 12px !important;
            font-weight: 600 !important;
            border-radius: 6px 6px 0 0 !important;
            text-decoration: none !important;
            color: #50575e !important;
        }
        #product_categorydiv ul.category-tabs li.tabs a,
        #packaging_typediv ul.category-tabs li.tabs a {
            background: #E0A238 !important;
            color: #fff !important;
        }
    </style>
    <?php
});



