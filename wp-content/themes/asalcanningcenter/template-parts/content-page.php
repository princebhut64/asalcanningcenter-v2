<?php
/**
 * Template part for displaying page content in page.php
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package asalcanningcenter
 */


$_term = get_queried_object();
if ( get_the_content() ) {
	?>
	<section class="main-content-wrap single-content-page">
		<div class="container">
			<div class="row">
				<div class="col-12">
					<?php the_content(); ?>
				</div>
			</div>
		</div>
	</section>
	<?php
}

if ( have_rows( 'page_builder', $_term ) ) {
	while ( have_rows( 'page_builder', $_term ) ) {
		the_row();
		$layout_section = get_row_layout();

		switch ( $layout_section ) {
			case 'home_banner':
			case 'brand_story_and_founder_spotlight':
			case 'products_section':
			case 'preservation_process':
			case 'faq_accordion':
			case 'facility_and_processing_gallery':
			case 'batch_calculator':
			case 'inner_page_hero_banner':
			case 'timeline_section':
			case 'core_values_and_pillars':
			case 'news_hero_banner':
			case 'trust_build_and_credentials':
			case 'clippings_media_archive':
			case 'gallery_section':
			case 'processing_infrastructure':
			case 'contact_banner':
			case 'contact_details':
			case 'products_view':
			case 'hero_slider':
			case 'google_reviews_shortcode':
				$template_name = str_replace( '_', '-', $layout_section );
				get_template_part( 'template-parts/acf-flexible/' . $template_name );
				break;
			default:
				break;
		}
	}
}
