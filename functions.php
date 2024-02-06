<?php 
if ( !defined( 'ICL_LANGUAGE_CODE' ) && !defined('EMARKET_THEME') ){
        define( 'EMARKET_THEME', 'emarket_theme' );
}else{
        define( 'EMARKET_THEME', 'emarket_theme'.ICL_LANGUAGE_CODE );
}

define( 'EMARKET_UPDATE_MENU', false );
define( 'EMARKET_UPDATE_FONT', false );

/**
 * Variables
 */
require_once ( get_template_directory().'/lib/defines.php' );
require_once ( get_template_directory().'/lib/mobile-layout.php' );
require_once ( get_template_directory().'/lib/classes.php' );		// Utility functions
require_once ( get_template_directory().'/lib/utils.php' );			// Utility functions
require_once ( get_template_directory().'/lib/init.php' );			// Initial theme setup and constants
require_once ( get_template_directory().'/lib/cleanup.php' );		// Cleanup
require_once ( get_template_directory().'/lib/widgets.php' );		// Sidebars and widgets
require_once ( get_template_directory().'/lib/scripts.php' );		// Scripts and stylesheets
require_once ( get_template_directory().'/lib/custom-color.php' );		// Scripts and stylesheets
require_once ( get_template_directory().'/lib/metabox.php' );	// Custom functions
require_once ( get_template_directory().'/lib/plugin-requirement.php' );			// Custom functions
require_once ( get_template_directory().'/lib/import/sw-import.php' );
	
if( defined( 'ELEMENTOR_VERSION' ) ){
	require_once ( get_template_directory().'/lib/elementor-custom.php' );	// Elementor custom
}

if( class_exists( 'WooCommerce' ) ){
	require_once ( get_template_directory().'/lib/woocommerce-hook.php' );	// Utility functions
	
	if( class_exists( 'WC_Vendors' ) ) :
		require_once ( get_template_directory().'/lib/wc-vendor-hook.php' );			/** WC Vendor **/
	endif;
	
	if( class_exists( 'WeDevs_Dokan' ) ) :
		require_once ( get_template_directory().'/lib/dokan-vendor-hook.php' );			/** Dokan Vendor **/
	endif;
	
	if( class_exists( 'WCMp' ) ) :
		require_once ( get_template_directory().'/lib/wc-marketplace-hook.php' );			/** WC MarketPlace Vendor **/
	endif;
}

function emarket_template_load( $template ){ 
	if( !is_user_logged_in() && emarket_options()->getCpanelValue('maintaince_enable') ){
		$template = get_template_part( 'maintaince' );
	}
	return $template;
}
add_filter( 'template_include', 'emarket_template_load' );



add_filter( 'emarket_widget_register', 'emarket_add_custom_widgets' );
function emarket_add_custom_widgets( $emarket_widget_areas ){
	if( class_exists( 'sw_woo_search_widget' ) ){
		$emarket_widget_areas[] = array(
			'name' => esc_html__('Widget Search', 'emarket'),
			'id'   => 'search',
			'before_widget' => '<div id="%1$s" class="widget %1$s %2$s"><div class="widget-inner">',
			'after_widget'  => '</div></div>',
			'before_title'  => '<h3>',
			'after_title'   => '</h3>'
		);
	}
	$emarket_widget_areas[] = array(
		'name' => esc_html__('Widget Mobile Top', 'emarket'),
		'id'   => 'top-mobile',
		'before_widget' => '<div id="%1$s" class="widget %1$s %2$s"><div class="widget-inner">',
		'after_widget'  => '</div></div>',
		'before_title'  => '<h3>',
		'after_title'   => '</h3>'
	);
	return $emarket_widget_areas;
}
function isa_add_img_title( $attr, $attachment = null ) {

    $img_title = trim( strip_tags( $attachment->post_title ) );

    $attr['title'] = $img_title;
    $attr['alt'] = $img_title;

    return $attr;
}
add_filter( 'wp_get_attachment_image_attributes','isa_add_img_title', 10, 2 );
function emarket_theme_support() {
    remove_theme_support( 'widgets-block-editor' );
}
add_action( 'after_setup_theme', 'emarket_theme_support' );

/**
* Support SVG
**/
function emarket_businessplus_mime_types($mimes) {
    $mimes['svg'] = 'image/svg+xml';
    return $mimes;
}
add_filter('upload_mimes', 'emarket_businessplus_mime_types');
add_filter('mime_types', 'emarket_businessplus_mime_types');

/**
* Update Data menu to new version
**/
add_action( 'admin_init', 'emarket_custom_init' );
function emarket_custom_init(){
	if( !isset( $_GET['emarket_update_data'] ) ){
		return;
	}
	global $wp_version;
	global $wpdb;
	$version = get_option( 'emarket_version' );
	if( isset( $_GET['update_font'] ) && $_GET['update_font'] ){
		$fontx = wp_remote_retrieve_body(wp_remote_get('https://www.googleapis.com/webfonts/v1/webfonts?key=AIzaSyBVy9_Zen4pDUsHnk0Qcd9vMeKtSm5y94Y'));
		$fontx =  json_decode( $fontx );
		$fonts = array();
		if( !isset( $fontx->error ) ){
			foreach($fontx->items as $cut){
				foreach($cut->variants as $variant){
					$fonts[] = $cut->family;
				}
			}
			$fonts = array_unique( $fonts );
			$current_fonts = json_decode( get_option( ( 'sw_google_fonts' ) ) );
			if( count( array_diff( (array)$current_fonts, $fonts ) ) ){
				update_option( 'sw_google_fonts', json_encode( $fonts ) );
			}
		}
	}
	if( empty( $version ) ){ 

		$results = $wpdb->get_results( $wpdb->prepare( "SELECT distinct `post_id` FROM {$wpdb->postmeta} as m1 left join {$wpdb->posts} as m2 on m1.post_id = m2.ID where m2.post_type='nav_menu_item' and m1.meta_key in ( '_menu_item_mega_active', '_menu_item_icon', '_menu_item_span', '_menu_item_dropdown_span', '_menu_item_mega_full', '_menu_item_show_description_as_subtitle', '_menu_item_hide_title', '_menu_item_disable_link', '_menu_item_advanced', '_menu_item_advanced_content', '_menu_item_page_select', '_menu_item_which_user', '_menu_item_user_role', %s ) order by m1.post_id ", '_menu_item_imgupload' ) );

		$x = array();
		foreach( $results as $key => $result ){
			$x[$key]['post_id'] = $result->post_id;
			$x3 = array();
			$x1 = $wpdb->get_results( $wpdb->prepare( "SELECT `meta_key`, `meta_value` FROM {$wpdb->postmeta} WHERE post_id = %d and `meta_key` in ( '_menu_item_mega_active', '_menu_item_icon', '_menu_item_span', '_menu_item_dropdown_span', '_menu_item_mega_full', '_menu_item_show_description_as_subtitle', '_menu_item_hide_title', '_menu_item_disable_link', '_menu_item_advanced', '_menu_item_advanced_content', '_menu_item_page_select', '_menu_item_which_user', '_menu_item_user_role', '_menu_item_imgupload' )", $result->post_id ) );
			foreach( $x1 as $x2 ){
				$x3[$key][$x2->meta_key] = $x2->meta_value;

			}
			$x[$key]['meta'] = $x3[$key];
		}

		foreach( $x as $val ){
			update_post_meta( $val['post_id'], 'menu_meta', $val['meta'] );
		}	
		$wpdb->get_results( $wpdb->prepare( "delete FROM {$wpdb->postmeta} where meta_key in ( '_menu_item_mega_active', '_menu_item_icon', '_vc_post_settings', '_menu_item_span', '_menu_item_dropdown_span', '_menu_item_mega_full', '_menu_item_show_description_as_subtitle', '_menu_item_hide_title', '_menu_item_disable_link', '_menu_item_advanced', '_menu_item_advanced_content', '_menu_item_page_select', '_menu_item_which_user', '_menu_item_user_role', %s )", '_menu_item_imgupload' ) );
	}
	update_option( 'emarket_version', $wp_version );
}

$version = get_option( 'emarket_version' );
if( !empty( $version ) ){ 
	require_once ( get_template_directory().'/lib/nav-update.php' );			
}else{
	require_once ( get_template_directory().'/lib/nav.php' );			
}
add_action( 'admin_notices', 'emarket_update_admin_notice' );


function emarket_update_admin_notice(){
	global $wp_version;
	if(  get_option( 'emarket_version' ) == $wp_version ){
		return;
	}
	$url = array( 'emarket_update_data' => true, 'nonce' => wp_create_nonce( 'emarket_run_update_data' ) );
	
	if( EMARKET_UPDATE_FONT ){
		$url['update_font'] = true;
	}	
	?>
	<div data-dismissible="pp-registration-disabled-notice-forever" id="message" class="notice notice-warning is-dismissible">
		<p><?php esc_html_e( 'There is new version of the theme was updated. Please click Run update to update theme data to new version.', 'emarket' ); ?></p>
		<p><a class="button button-primary" href="<?php echo esc_url( add_query_arg( $url, admin_url('/') ) ); ?>"><?php echo esc_html__( 'Run Update', 'emarket' ); ?></a></p>
		<button type="button" class="notice-dismiss"><span class="screen-reader-text"><?php echo esc_html__( 'Dismiss this notice.', 'emarket' ) ?></span></button>
	</div>
<?php
}