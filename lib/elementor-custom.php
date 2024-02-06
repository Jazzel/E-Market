<?php 

use ElementorPro\Modules\ThemeBuilder\Module;
use ElementorPro\Modules\ThemeBuilder\Classes\Theme_Support;

add_action( 'elementor/theme/register_locations', 'emarket_custom_location_action' );
function emarket_custom_location_action( $location_manager ){
	$core_locations = $location_manager->get_core_locations();
	$overwrite_header_location = false;
	$overwrite_footer_location = false;

	foreach ( $core_locations as $location => $settings ) {
		if ( ! $location_manager->get_location( $location ) ) {
			if ( 'header' === $location ) {
				$overwrite_header_location = true;
			} elseif ( 'footer' === $location ) {
				$overwrite_footer_location = true;
			}
			$location_manager->register_core_location( $location, [
				'overwrite' => true,
			] );
		}
	}
	if ( $overwrite_header_location || $overwrite_footer_location ) {
		if( !emarket_mobile_check() ){
			$theme_builder_module = Module::instance();

			$conditions_manager = $theme_builder_module->get_conditions_manager();

			$headers = $conditions_manager->get_documents_for_location( 'header' );
			$footers = $conditions_manager->get_documents_for_location( 'footer' );
			
			$support = new Theme_Support();
			
			if ( ! empty( $headers ) || ! empty( $footers ) ) {
				add_action( 'get_header', [ $support, 'get_header' ] );
				add_action( 'get_footer', [ $support, 'get_footer' ] );
			}
		}
	}
	
}

add_action( 'elementor/editor/after_enqueue_styles', 'emarket_custom_style_elementor_backend' );
function emarket_custom_style_elementor_backend(){
	wp_enqueue_style('emarket-elementor-editor', get_template_directory_uri() . '/css/emarket-elementor-editor.css', array(), null);		
}
add_action( 'after_setup_theme', 'emarket_custom_hook_elementor' );
function emarket_custom_hook_elementor(){
	// var_dump( Module::instance()->get_conditions_manager()->get_documents_for_location( 'archive' ) );
}