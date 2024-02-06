<?php
/**
 * Enqueue scripts and stylesheets
 *
 */

function emarket_scripts() {	
	$scheme_meta = get_post_meta( get_the_ID(), 'scheme', true );
	$scheme = ( $scheme_meta != '' && $scheme_meta != 'none' && is_page() ) ? $scheme_meta : emarket_options()->getCpanelValue('scheme');
	$emarket_direction = emarket_options()->getCpanelValue('direction');
	$layout_styles = emarket_options()->getCpanelValue('layout');

	$app_css = get_template_directory_uri() . '/css/app-default.css';
	global $wp_styles;
	wp_dequeue_style('sw-wooswatches');
	wp_dequeue_style('fontawesome');
	wp_dequeue_style('slick_slider_css');
	wp_dequeue_style('fontawesome_css');
	wp_dequeue_style('shortcode_css');
	wp_dequeue_style('yith-wcwl-font-awesome');
	wp_dequeue_style('tabcontent_styles');
	wp_dequeue_script('swpb_slick_slider');	

	wp_deregister_style( 'elementor-icons-shared-0' );
	wp_deregister_style( 'font-awesome' );
	/* enqueue script & style */
	if ( !is_admin() ){
		$layout_ID = ( get_option( 'page_on_front' ) ) ? get_option( 'page_on_front' ) : 0;
		$home_layout = get_post_meta( $layout_ID, 'page_home_template', true );
	
		wp_enqueue_style('bootstrap', get_template_directory_uri() . '/css/bootstrap.min.css', array(), null);	
		wp_enqueue_style('font-awesome', get_template_directory_uri() . '/css/font-awesome.min.css', array(), null);		
		wp_enqueue_style('fancybox_css', get_template_directory_uri() . '/css/jquery.fancybox.css', array(), null);				
		wp_enqueue_style('emarket_css', $app_css, array(), null);	
		wp_enqueue_script('fancybox', get_template_directory_uri() . '/js/jquery.fancybox.pack.js', array('jquery'), null, true);
		wp_deregister_script('wc-cart');
		wp_enqueue_script( 'wc-cart',  get_template_directory_uri(). '/js/cart.min.js' , array( 'jquery' ), false, true );
		wp_enqueue_script('plugins', get_template_directory_uri() . '/js/plugins.js', array('jquery'), null, true);
		wp_enqueue_script('bootstrap_js', get_template_directory_uri() . '/js/bootstrap.min.js', array('jquery'), null, true);
		wp_enqueue_script('slick_slider',get_template_directory_uri().'/js/slick.min.js',array(),null,true);
		wp_enqueue_script('isotope_script', get_template_directory_uri() . '/js/isotope.js', array(), null, true);
		wp_enqueue_script('wc-quantity', get_template_directory_uri() . '/js/wc-quantity-increment.min.js', array('jquery'), null, true);
		wp_enqueue_script('nav-bar', get_template_directory_uri() . '/js/jquery.nav.min.js', array('jquery'), null, true);
		wp_enqueue_script('circle_script', get_template_directory_uri() . '/js/circleText.js', array(), null, true);
		
		if( defined( 'ELEMENTOR_VERSION' ) && \Elementor\Plugin::$instance->experiments->is_feature_active( 'e_dom_optimization' ) ){
			wp_enqueue_style('optimize_dom', get_template_directory_uri() . '/css/optimize-dom-output.css', array(), null);
		}
		
		if( class_exists( 'WooCommerce' ) ){
			if( is_woocommerce() || is_singular( 'product' ) || is_front_page() || is_search() ){
				wp_enqueue_style('emarket-woocommerce', get_template_directory_uri() . '/css/woocommerce-custom.css', array(), null);	
			}
		}

		if( is_rtl() || $emarket_direction == '1' ){
			wp_enqueue_style('rtl_css', get_template_directory_uri() . '/css/rtl.css', array(), null);
		}
		wp_enqueue_style('emarket_responsive_css', get_template_directory_uri() . '/css/app-responsive.css', array(), null);
		
		/* Load style.css from child theme */
		if (is_child_theme()) {
			wp_enqueue_style('emarket_child_css', get_stylesheet_uri(), false, null);
		}
		
		if( !wp_script_is( 'jquery-cookie' ) ){
			wp_enqueue_script('plugins');
		}
	}
	if (is_single() && comments_open() && get_option('thread_comments')) {
		wp_enqueue_script('comment-reply');
	}	

	if( class_exists( 'WooCommerce' ) ){
		wp_dequeue_style('woocommerce-smallscreen');
		wp_dequeue_style('woocommerce-layout');		
		wp_enqueue_style( 'woocommerce-smallscreen-custom', get_template_directory_uri() . '/css/woocommerce/woocommerce-smallscreen.css', array(), null );
		
	}
	
	if( is_page() ){
		$home_layout = get_post_meta( get_the_ID(), 'page_home_template', true );
	}
	
	if($layout_styles != "home-style"){
		wp_enqueue_style( "$home_layout", get_template_directory_uri() . "/css/$home_layout.css", array(), null );
	}
	if( is_array( $layout_styles ) ){
		foreach ( $layout_styles as $layout_style ){
			wp_enqueue_style( "$layout_style", get_template_directory_uri() . "/css/$layout_style.css", array(), null );
		}
	}
	if ( !is_admin() ){
		$translation_text = array(
			'cart_text' 		 => esc_html__( 'Add To Cart', 'emarket' ),
			'compare_text' 	 => esc_html__( 'Add to Compare', 'emarket' ),
			'wishlist_text'  => esc_html__( 'Add to WishList', 'emarket' ),
			'quickview_text' => esc_html__( 'QuickView', 'emarket' ),
			'ajax_url' => admin_url( 'admin-ajax.php', 'relative' ), 
			'redirect' => get_permalink( get_option( 'woocommerce_myaccount_page_id' ) ),
			'message' => esc_html__( 'Please enter your usename and password', 'emarket' )
		);		
		wp_localize_script( 'emarket_custom_js', 'custom_text', $translation_text );
		wp_enqueue_script( 'emarket_custom_js', get_template_directory_uri() . '/js/main.js', array(), '1.0.0', true );
	}
	
	if( emarket_options()->getCpanelValue( 'more_menu' ) ) {
		$overflow_text = array(
			'more_text' => esc_html__( 'More...', 'emarket' ),
			'more_menu'	=> emarket_options()->getCpanelValue( 'more_menu' )
		);
		wp_register_script('menu-overflow', get_template_directory_uri() . '/js/menu-overflow.min.js', array(), null, true);
		wp_localize_script( 'menu-overflow', 'menu_text', $overflow_text );
		wp_enqueue_script( 'menu-overflow' );
	}	

	/*
	** QuickView
	*/
	if( class_exists( 'WooCommerce' ) ) {
		global $woocommerce;
		$assets_path          = str_replace( array( 'http:', 'https:' ), '', WC()->plugin_url() ) . '/assets/';
		$frontend_script_path = $assets_path . 'js/frontend/';
		$wc_ajax_url 		  = WC_AJAX::get_endpoint( "%%endpoint%%" );
		$admin_url 			  = admin_url('admin-ajax.php');	
		$emarket_dest_folder = ( function_exists( 'sw_wooswatches_construct' ) ) ? 'woocommerce' : 'woocommerce_select';
		$woocommerce_params = array(
			'ajax'  => array(
				'url'	=> $admin_url
			)
		);
		$_wpUtilSettings = array(
			'ajax_url'     => $woocommerce->ajax_url(),
			'wc_ajax_url'  => 	$wc_ajax_url
		);
		$wc_add_to_cart_variation_params = array(
			'i18n_no_matching_variations_text' => esc_attr__( 'Sorry, no products matched your selection. Please choose a different combination.', 'emarket' ),
			'i18n_make_a_selection_text'       => esc_attr__( 'Please select some product options before adding this product to your cart.', 'emarket' ),
			'i18n_unavailable_text'            => esc_attr__( 'Sorry, this product is unavailable. Please choose a different combination.', 'emarket' ),
		);
		
		$quickview_text = array(			
			'ajax_url' => WC_AJAX::get_endpoint( "%%endpoint%%" ), 			
			'wp_embed' => esc_url ( home_url('/') . 'wp-includes/js/wp-embed.min.js' ),
			'underscore' =>  esc_url ( home_url('/') . 'wp-includes/js/underscore.min.js' ),
			'wp_util' =>  esc_url ( home_url('/') . 'wp-includes/js/wp-util.min.js' ),
			'add_to_cart' => esc_url( $frontend_script_path . 'add-to-cart.min.js' ),
			'woocommerce' => esc_url( $frontend_script_path . 'woocommerce.min.js' ),
			'add_to_cart_variable' => esc_url( get_template_directory_uri() . '/js/'. $emarket_dest_folder .'/add-to-cart-variation.min.js' ),
			'wpUtilSettings' => json_encode( $_wpUtilSettings ),
			'woocommerce_params' => json_encode( $woocommerce_params ),
			'wc_add_to_cart_variation_params' => json_encode( $wc_add_to_cart_variation_params )
		);
		wp_register_script('sw-quickview', get_template_directory_uri() . '/js/quickview.js', array(), null, true);
		wp_localize_script( 'sw-quickview', 'quickview_param', $quickview_text );
		wp_enqueue_script( 'sw-quickview' );		
	}

	/*
** Maintaince Mode
*/
if( !is_user_logged_in() && emarket_options()->getCpanelValue('maintaince_enable') ){ 
	$output = '';
	$countdown = emarket_options()->getCpanelValue('maintaince_date');
	if( $countdown != '' ):
		$output .= 'jQuery(function($){
			"use strict";
			function emarket_check_height(){
				var W_height = $( window ).height();
				if( W_height > 767) {
					setTimeout(function(){
						var cm_height = $( window ).height();
						var cm_target = $( "body > .body-wrapper" );
						cm_target.css( "height", cm_height );
					}, 1000);
		}
	}
	$(window).on( "load", function(){
		emarket_check_height();
	});
	$(document).ready(function(){ 
		var end_date = new Date( "'. esc_js( $countdown ) .'" ).getTime()/1000;
		$("#countdown-container").ClassyCountdown({
			theme: "white", 
			end: end_date, 
			now: $.now()/1000,
			labelsOptions: {
				lang: {
					days: "'. esc_html__( 'Days', 'emarket' ) .'",
					hours: "'. esc_html__( 'Hours', 'emarket' ) .'",
					minutes: "'. esc_html__( 'Mins', 'emarket' ) .'",
					seconds: "'. esc_html__( 'Secs', 'emarket' ) .'"
				},
				style: "font-size: 0.5em;"
			},
		});
	});
	});';
	endif;

	wp_enqueue_style('countdown_css', get_template_directory_uri() . '/css/jquery.classycountdown.min.css', array(), null);
	wp_enqueue_style('maintaince_css', get_template_directory_uri() . '/css/style-maintaince.css', array(), null);
	wp_register_script('countdown',get_template_directory_uri(). '/js/maintaince/jquery.classycountdown.min.js', array(), null, true);
	wp_enqueue_script( 'knob', get_template_directory_uri(). '/js/maintaince/jquery.knob.js', array(), null, true);	
	wp_enqueue_script( 'throttle',get_template_directory_uri() . '/js/maintaince/jquery.throttle.js', array(), null, true);	
	wp_enqueue_script( 'countdown' );
	wp_add_inline_script( 'countdown', $output );
}

	/*
	** Dequeue and enqueue css, js mobile
	*/
	if( emarket_mobile_check() ) :
		if( is_front_page() || is_home() ) :
			wp_dequeue_script( 'prettyPhoto' );
			wp_dequeue_script( 'prettyPhoto-init' );
			wp_dequeue_style( 'woocommerce_prettyPhoto_css' );
		endif;
		
		if( !emarket_options()->getCpanelValue( 'mobile_jquery' ) ) :
			wp_dequeue_script( 'tp-tools' );
			wp_dequeue_script( 'revmin' );
		endif;
		
		wp_dequeue_style( 'jquery-colorbox' );
		wp_dequeue_style( 'colorbox' );
		wp_dequeue_script( 'jquery-colorbox' );		
		wp_dequeue_script( 'emarket_megamenu' );
		wp_dequeue_script( 'emarket' );	
		wp_dequeue_script( 'yith-woocompare-main' );
	endif;
	wp_enqueue_style('emarket-mobile', get_template_directory_uri() . '/css/mobile.css', array(), null);

	/*
	** Dequeue some css and jquery mobile responsive
	*/
	
	global $sw_detect;
	if( !empty( $sw_detect ) && $sw_detect->isMobile() && !$sw_detect->isTablet() ){
		wp_dequeue_style( 'jquery-colorbox' );
		wp_dequeue_style( 'colorbox' );
		wp_dequeue_script( 'jquery-colorbox' );
		wp_dequeue_script( 'emarket_megamenu' );
		wp_dequeue_script( 'yith-woocompare-main' );		
	}
}
add_action('wp_enqueue_scripts', 'emarket_scripts', 100);