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

	<?php wp_head(); ?>
	  <!-- Google Fonts & Font Awesome Icons -->
  <link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:ital,wght@0,400;0,500;0,600;0,700;1,400&family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet" />

</head>

<body <?php body_class(); ?>>
<?php wp_body_open(); ?>
<div id="page" class="site">
	<a class="skip-link screen-reader-text" href="#primary"><?php esc_html_e( 'Skip to content', 'asalcanningcenter' ); ?></a>

<?php
$fssai_logo       = get_field('fssai_logo', 'option');
$fssai_license    = get_field('fssai_license', 'option');
$header_location  = get_field('header_location', 'option');
$header_timing    = get_field('header_timing', 'option');
$header_facebook  = get_field('header_facebook', 'option');

// Brand Settings
$header_established = get_field('header_established', 'option');
$brand_title        = get_field('header_brand_title', 'option');
$brand_subtitle     = get_field('header_brand_subtitle', 'option');

// CTA Settings
$cta_text = get_field('header_cta_text', 'option');
$cta_url  = get_field('header_cta_url', 'option');
?>
	<div class="top-bar">
		<div class="container">
			<div class="top-bar-info">
				<div class="top-bar-item" style="display: flex; align-items: center;">
				<?php 
				$fssai_logo_url = is_array($fssai_logo) ? ($fssai_logo['url'] ?? '') : (is_string($fssai_logo) ? $fssai_logo : '');
				if (!empty($fssai_logo_url)) : ?>
					<img src="<?php echo esc_url( add_query_arg( 'v', '2.0', $fssai_logo_url ) ); ?>" alt="<?php echo esc_attr__('FSSAI Logo', 'asalcanningcenter'); ?>" class="fssai-top-logo" style="margin-right: 8px;" />
				<?php endif; ?>
				<span><strong><?php esc_html_e('FSSAI Lic. No.:', 'asalcanningcenter'); ?></strong> <?php echo esc_html($fssai_license); ?></span>
				</div>
				<div class="top-bar-item" style="display: flex; align-items: center;">
				<i class="fa-solid fa-trademark" style="margin-right: 6px; color: var(--accent);"></i>
				<span><strong><?php esc_html_e('Trade Mark No.:', 'asalcanningcenter'); ?></strong> <?php echo esc_html( get_field('trademark_number', 'option') ?: '3340551' ); ?></span>
				</div>
				<div class="top-bar-item">
				<i class="fa-solid fa-location-dot"></i>
				<span><?php echo esc_html($header_location); ?></span>
				</div>
			</div>
			<div class="top-bar-links">
				<a href="<?php echo esc_url( get_permalink( get_page_by_path('contact-us') ) ?: home_url('/contact-us/') ); ?>"><i class="fa-solid fa-clock"></i> <?php echo esc_html($header_timing); ?></a>
				<a href="<?php echo esc_url($header_facebook); ?>" target="_blank" aria-label="<?php echo esc_attr__('Facebook', 'asalcanningcenter'); ?>"><i class="fa-brands fa-facebook-f"></i></a>
			</div>
		</div>
	</div>
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
