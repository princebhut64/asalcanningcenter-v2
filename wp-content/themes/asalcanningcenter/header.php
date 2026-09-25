<?php
/**
 * The header for our theme
 *
 * This is the template that displays all of the <head> section and everything up until <div id="content">
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package asalcanningcenter
 */

?>
<!doctype html>
<html <?php language_attributes(); ?>>
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="profile" href="https://gmpg.org/xfn/11">

	<!-- Preconnect to external font and icon CDNs for faster DNS & TLS handshake -->
	<link rel="preconnect" href="https://fonts.googleapis.com">
	<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
	<link rel="preconnect" href="https://cdnjs.cloudflare.com" crossorigin>

	<?php wp_head(); ?>
	<!-- Google Fonts & Font Awesome Icons -->
	<link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:ital,wght@0,400;0,500;0,600;0,700;1,400&family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet" />

</head>

<body <?php body_class(); ?>>
<?php wp_body_open(); ?>
<div id="page" class="site">
	<a class="skip-link screen-reader-text" href="#primary"><?php esc_html_e( 'Skip to content', 'asalcanningcenter' ); ?></a>

<?php
// Brand Settings
$header_established = get_field('header_established', 'option');
$brand_title        = get_field('header_brand_title', 'option');
$brand_subtitle     = get_field('header_brand_subtitle', 'option');

// CTA Settings
$cta_text = get_field('header_cta_text', 'option');
$cta_url  = get_field('header_cta_url', 'option');
?>
	<header>
		<div class="container header-content">
		<a href="<?php echo esc_url(home_url('/')); ?>" class="brand">
			<div class="brand-badge"><?php echo esc_html($header_established); ?></div>
			<div class="brand-title">
			<h1><?php echo esc_html($brand_title); ?></h1>
			<span><?php echo esc_html($brand_subtitle); ?></span>
			</div>
		</a>

		<button class="mobile-menu-btn" aria-label="Toggle Mobile Menu">
			<i class="fa-solid fa-bars"></i>
		</button>

		<nav class="main-navigation">

            <?php
            wp_nav_menu(
                array(
                    'menu_id' => 'menu-1',
					'menu' => 'Menu 1',
                    'container'      => false,
                    'menu_class'     => '',
                    'fallback_cb'    => false,
                )
            );
            ?>

        </nav>

		<a href="<?php echo esc_url($cta_url ?: '#'); ?>" class="btn btn-accent btn-sm header-cta"><?php echo esc_html($cta_text); ?> <i class="fa-solid fa-arrow-right"></i></a>
		</div>
	</header><!-- #masthead -->

