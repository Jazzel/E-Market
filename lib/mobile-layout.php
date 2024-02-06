<?php 
/*
** Mobile Layout 
*/
require_once( get_template_directory().'/lib/mobile-detect.php' );

/*
** Check Header Mobile or Desktop
*/
function emarket_header_check(){ 	
	$mobile_header = ( get_post_meta( get_the_ID(), 'page_mobile_header', true ) != '' && is_page() ) ? get_post_meta( get_the_ID(), 'page_mobile_header', true ) : emarket_options()->getCpanelValue( 'mobile_header_style' );
	$page_header   = ( get_post_meta( get_the_ID(), 'page_header_style', true ) != '' && ( is_page() || is_single() ) ) ? get_post_meta( get_the_ID(), 'page_header_style', true ) : emarket_options()->getCpanelValue('header_style');
	$elementor_enable = emarket_options()->getCpanelValue( 'enable_elementor' );
	/* 
	** Display header or not 
	*/
	if( get_post_meta( get_the_ID(), 'page_header_hide', true ) ) :
		return ;
	endif;
	if( emarket_mobile_check() ):
		$header_mobile_elementor = emarket_options()->getCpanelValue('mobile_header');
		if( defined( 'ELEMENTOR_VERSION' ) && \Elementor\Plugin::$instance->documents->get( $header_mobile_elementor ) ){
			echo \Elementor\Plugin::$instance->frontend->get_builder_content( $header_mobile_elementor );
		}else{
			get_template_part( 'mlayouts/header', $mobile_header );
		}
	else: 
		if( !empty( $elementor_enable ) && $elementor_enable ){
			get_header(); 
		}else{
			get_template_part( 'templates/header', $page_header );
		}
	endif;
}

/*
** Check Footer Mobile or Desktop
*/
function emarket_footer_check(){
	$mobile_footer = ( get_post_meta( get_the_ID(), 'page_mobile_footer', true ) != '' && ( is_page() || is_single() ) ) ? get_post_meta( get_the_ID(), 'page_mobile_footer', true ) : emarket_options()->getCpanelValue( 'mobile_footer_style' );
	$elementor_enable = emarket_options()->getCpanelValue( 'enable_elementor' );
	if( emarket_mobile_check() && $mobile_footer != '' ):
		$footer_mobile_elementor = emarket_options()->getCpanelValue('mobile_footer');
		if( defined( 'ELEMENTOR_VERSION' ) && \Elementor\Plugin::$instance->documents->get( $footer_mobile_elementor ) ){
			echo \Elementor\Plugin::$instance->frontend->get_builder_content( $footer_mobile_elementor );
		}else{
			get_template_part( 'mlayouts/footer', $mobile_footer );
		}
	else: 
		if( !empty( $elementor_enable ) && $elementor_enable ){
			get_footer();
		}else{
			get_template_part( 'templates/footer' );
		}
	endif;
}

/*
** Check Content Page Mobile or Desktop
*/
function emarket_pagecontent_check(){
	$mobile_content = emarket_options()->getCpanelValue( 'mobile_content' );
	if( emarket_mobile_check() && $mobile_content != '' && is_front_page() ):
		if( defined( 'ELEMENTOR_VERSION' ) && \Elementor\Plugin::$instance->documents->get( $mobile_content ) ){
			echo do_shortcode( \Elementor\Plugin::$instance->frontend->get_builder_content( $mobile_content ) );
		}else{
			echo sw_get_the_content_by_id( $mobile_content );
		}
	else: 
		the_content();
	endif;
}

/*
** Check Product Listing Mobile or Desktop
*/
function emarket_product_listing_check(){
	if( emarket_mobile_check() ) :
		get_template_part('mlayouts/archive','product-mobile');
	else: 
		 wc_get_template( 'archive-product.php' );
	endif;
}

/*
** Check Product Listing Mobile or Desktop
*/
function emarket_blog_listing_check(){
	if( emarket_mobile_check()  ) :
		get_template_part('mlayouts/archive', 'mobile');
	else: 
		get_template_part( 'templates/content' );
	endif;		
}

/*
** Check Product Detail Mobile or Desktop
*/
function emarket_product_detail_check(){
	if( emarket_mobile_check()  ) :
		get_template_part('mlayouts/single','product');
	else: 
		 wc_get_template( 'single-product.php' );
	endif;
}

/*
** Check Product Detail Mobile or Desktop
*/
function emarket_content_detail_check(){
	$emarket_blog_style = emarket_options()->getCpanelValue( 'blog_style' );
	if( emarket_mobile_check() ) :
		get_template_part('mlayouts/single','mobile');
	elseif( $emarket_blog_style == 'home-style17' ): 
		 get_template_part('templates/content', 'single-style1');
	elseif ( $emarket_blog_style == 'blog-style-service' ):
		get_template_part('templates/content', 'single-style2');
	elseif ( $emarket_blog_style == 'home-style57' ):
		get_template_part('templates/content', 'single-style3');
	else :
		 get_template_part('templates/content', 'single-default');
	endif;		
}

/*
** Product Meta
*/
if( !function_exists( 'emarket_mobile_check' ) ){
	function emarket_mobile_check(){
		global $sw_detect;
		
		$sw_demo   		  = get_option( 'sw_mdemo' );
		$mobile_check   = emarket_options()->getCpanelValue( 'mobile_enable' );
		
		if( $sw_demo == 1 ) :
			return true;
		endif;
		
		if( !empty( $sw_detect ) && $mobile_check && $sw_detect->isMobile() && !$sw_detect->isTablet() ) :
			return true;
		else: 
			return false;
		endif;
		return false;
	}
}

/*
** Number of post for a WordPress archive page
*/
function emarket_Per_category_basis($query){
    if ( ( $query->is_category ) ) {
        /* set post per page */
        if ( is_archive() && emarket_mobile_check() ){
            $query->set('posts_per_page', 3);
        }
    }
    return $query;

}
add_filter('pre_get_posts', 'emarket_Per_category_basis');

add_filter( 'elementor/theme/get_location_templates/template_id', 'sw_custom_template_include' );
function sw_custom_template_include( $theme_template_id ){
	$mobile_elementor = emarket_options()->getCpanelValue( 'mobile_elementor' );
	if( !empty( $mobile_elementor ) && $mobile_elementor ){
		return $theme_template_id;
	}
	if( ( ( is_category() || is_single() ) || ( is_post_type_archive( 'product' ) || is_singular( 'product' ) || is_tax( 'product_cat' ) || is_tax( 'product_brand' ) ) ) && emarket_mobile_check() ){
		$theme_template_id = 0;
	}
	return $theme_template_id;
}

