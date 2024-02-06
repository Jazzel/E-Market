<?php 
/*
	* Name: WooCommerce Hook
	* Develop: SmartAddons
*/

function categories_postcount_filter ($variable) {
   $variable = str_replace('(', ' ', $variable);
   $variable = str_replace(')', ' ', $variable);
   return $variable;
}
add_filter('wp_list_categories', 'categories_postcount_filter');

add_filter( 'woocommerce_show_admin_notice', 'custom_admin_notice_woocommerce', 10, 2 );
function custom_admin_notice_woocommerce( $x, $notice ){
	if( $notice === 'template_files' ){
		return false;
	}
		return true;
}

/*
** Add WooCommerce support
*/
add_theme_support( 'woocommerce' );

/*
** WooCommerce Compare Version
*/
if( !function_exists( 'sw_woocommerce_version_check' ) ) :
	function sw_woocommerce_version_check( $version = '3.0' ) {
		global $woocommerce;
		if( version_compare( $woocommerce->version, $version, ">=" ) ) {
			return true;
		}else{
			return false;
		}
	}
endif;

/*
** Check vendor user
*/
if( !function_exists( 'sw_woocommerce_vendor_check' ) ) :
	function sw_woocommerce_vendor_check(){
		$vendor_id = get_post_field( 'post_author', get_the_id() );
		$vendor = new WP_User($vendor_id);
		if ( in_array( 'vendor', (array) $vendor->roles ) || in_array( 'seller', (array) $vendor->roles ) || in_array( 'wcfm_vendor', (array) $vendor->roles ) || in_array( 'dc_vendor', (array) $vendor->roles ) ){
			return true;
		}else{
			return false;
		}
	}
endif;



/*
** get offset datetime
*/
if( !function_exists( 'sw_timezone_offset' ) ){
	function sw_timezone_offset( $countdown_time ){
		$timeOffset = 0;	
		if( get_option( 'timezone_string' ) != '' ) :
			$timezone = get_option( 'timezone_string' );
			$dateTimeZone = new DateTimeZone( $timezone );
			$dateTime = new DateTime( "now", $dateTimeZone );
			$timeOffset = $dateTimeZone->getOffset( $dateTime );
		else :
			$dateTime = get_option( 'gmt_offset' );
			$dateTime = intval( $dateTime );
			$timeOffset = $dateTime * 3600;
		endif;
		$offset =  ( $timeOffset < 0 ) ? '-' . gmdate( "H:i", abs( $timeOffset ) ) : '+' . gmdate( "H:i", $timeOffset );

		$date = date( 'Y/m/d H:i:s', $countdown_time );
		$date1 = new DateTime( $date );
		$cd_date =  $date1->format('Y-m-d H:i:s') . $offset;
		
		return $cd_date; 
	}
}
/*
** Sales label
*/
if( !function_exists( 'sw_label_sales' ) ){
	function sw_label_sales(){
		global $product, $post;
		$product_type = ( sw_woocommerce_version_check( '3.0' ) ) ? $product->get_type() : $product->product_type;
		echo sw_label_new();
		if( $product_type != 'variable' ) {
			$forginal_price 	= get_post_meta( $post->ID, '_regular_price', true );	
			$fsale_price 		= get_post_meta( $post->ID, '_sale_price', true );
			if( $fsale_price > 0 && $product->is_on_sale() ){ 
				$sale_off = 100 - ( ( $fsale_price/$forginal_price ) * 100 ); 
				$html = '<div class="sale-off ' . esc_attr( ( sw_label_new() != '' ) ? 'has-newicon' : '' ) .'">';
				$html .= '-' . round( $sale_off ).'%';
				$html .= '</div>';
				echo apply_filters( 'sw_label_sales', $html );
			} 
		}else{
			echo '<div class="' . esc_attr( ( sw_label_new() != '' ) ? 'has-newicon' : '' ) .'">';
			wc_get_template( 'single-product/sale-flash.php' );
			echo '</div>';
		}
	}	
}

if( !function_exists( 'sw_label_stock' ) ){
	function sw_label_stock(){
		global $product;
		$availability = $product->get_availability();
		if( emarket_mobile_check() ) :
	?>
<div class="product-info">
    <?php $stock = $availability['class']; ?>
    <div class="product-stock <?php echo esc_attr( $stock ); ?>">
        <span><?php echo sprintf( ( $stock == 'in-stock' )? '%s' : esc_html__( 'Out stock', 'emarket' ), esc_html__( 'in stock', 'emarket' ) ); ?></span>
    </div>
</div>

<?php endif; 
	} 
}
function emarket_quickview(){
	global $product;
	$html='';
	if( function_exists( 'emarket_options' ) ){
		$quickview = emarket_options()->getCpanelValue( 'product_quickview' );
	}
	if( $quickview ):
		$html = '<a href="javascript:void(0)" data-product_id="'. esc_attr( $product->get_id() ) .'" class="sw-quickview group fancybox" data-type="quickview" data-ajax_url="' . WC_AJAX::get_endpoint( "%%endpoint%%" ) . '">'. esc_html__( 'Quick View ', 'emarket' ) .'</a>';	
	endif;
	return $html;
}

/*
** Minicart via Ajax
*/
add_filter( 'woocommerce_add_to_cart_fragments', 'emarket_add_to_cart_fragment_style7', 100 ); 
add_filter( 'woocommerce_add_to_cart_fragments', 'emarket_add_to_cart_fragment_style6', 100 ); 
add_filter( 'woocommerce_add_to_cart_fragments', 'emarket_add_to_cart_fragment_style5', 100 ); 
add_filter( 'woocommerce_add_to_cart_fragments', 'emarket_add_to_cart_fragment_style4', 100 ); 
add_filter( 'woocommerce_add_to_cart_fragments', 'emarket_add_to_cart_fragment_style3', 100 ); 
add_filter( 'woocommerce_add_to_cart_fragments', 'emarket_add_to_cart_fragment_style2', 100 );
add_filter( 'woocommerce_add_to_cart_fragments', 'emarket_add_to_cart_fragment', 100 );

if( emarket_mobile_check() ) :
	add_filter('woocommerce_add_to_cart_fragments', 'emarket_add_to_cart_fragment_mobile', 100);
endif;

function emarket_add_to_cart_fragment_mobile( $fragments ) {
	ob_start();
	get_template_part( 'woocommerce/minicart-ajax-mobile' );
	$fragments['.emarket-minicart-mobile'] = ob_get_clean();
	return $fragments;		
}
function emarket_add_to_cart_fragment_style7( $fragments ) {
	ob_start();
	get_template_part( 'woocommerce/minicart-ajax-style7' );
	$fragments['.emarket-minicart7'] = ob_get_clean();
	return $fragments;		
}
function emarket_add_to_cart_fragment_style6( $fragments ) {
	ob_start();
	get_template_part( 'woocommerce/minicart-ajax-style6' );
	$fragments['.emarket-minicart6'] = ob_get_clean();
	return $fragments;		
}

function emarket_add_to_cart_fragment_style5( $fragments ) {
	ob_start();
	get_template_part( 'woocommerce/minicart-ajax-style5' );
	$fragments['.emarket-minicart5'] = ob_get_clean();
	return $fragments;		
}

function emarket_add_to_cart_fragment_style4( $fragments ) {
	ob_start();
	get_template_part( 'woocommerce/minicart-ajax-style4' );
	$fragments['.emarket-minicart4'] = ob_get_clean();
	return $fragments;		
}

function emarket_add_to_cart_fragment_style3( $fragments ) {
	ob_start();
	get_template_part( 'woocommerce/minicart-ajax-style3' );
	$fragments['.emarket-minicart3'] = ob_get_clean();
	return $fragments;		
}

function emarket_add_to_cart_fragment_style2( $fragments ) {
	ob_start();
	get_template_part( 'woocommerce/minicart-ajax-style2' );
	$fragments['.emarket-minicart2'] = ob_get_clean();
	return $fragments;		
}

function emarket_add_to_cart_fragment( $fragments ) {
	ob_start();
	get_template_part( 'woocommerce/minicart-ajax' );
	$fragments['.emarket-minicart'] = ob_get_clean();
	return $fragments;		
}	

/*
** Remove WooCommerce breadcrumb
*/
remove_action( 'woocommerce_before_main_content', 'woocommerce_breadcrumb', 20, 0 );

/*
** Add second thumbnail loop product
*/
add_action( 'elementor/editor/init', 'custom_init_function' );
function custom_init_function(){
	remove_action( 'woocommerce_before_shop_loop_item_title', 'woocommerce_template_loop_product_thumbnail', 10 );
}


remove_action( 'woocommerce_before_shop_loop_item_title', 'woocommerce_template_loop_product_thumbnail', 10 );
add_action( 'woocommerce_before_shop_loop_item_title', 'emarket_woocommerce_template_loop_product_thumbnail', 10 );

function emarket_product_thumbnail( $size = 'woocommerce_thumbnail',  $attr = array(), $placeholder = true  ) {
	global $product;
	$product_hover = emarket_options()->getCpanelValue( 'product_thumb_hover' );
	$html = '';
	$gallery = get_post_meta($product->get_id(), '_product_image_gallery', true);
	$attachment_image = '';
	if( $product_hover ){
		$attr = array( 'class' => 'image1' );
	}else{
		$attr = array( 'class' => 'wp-post-image' );
	}
	if( !empty( $gallery ) ) {
		$gallery 					= explode( ',', $gallery );
		$first_image_id 	= $gallery[0];
		$attachment_image = wp_get_attachment_image( $first_image_id , $size, false, array('class' => 'hover-image1 wp-post-image') );
	}
	
	$image = wp_get_attachment_image_src( get_post_thumbnail_id( $product->get_id() ), '' );
	if( $attachment_image ){
		$html .= '<a href="'.get_permalink( $product->get_id() ).'">';
		$html .= '<div class="product-thumb-hover">';
		$html .= $product->get_image( $size, $attr, $placeholder );
		
		if( $product_hover ):
			$html .= $attachment_image;
		endif;
		
		$html .= '</div>';
		$html .= '</a>';				
	}else{
		$html .= '<a href="'.get_permalink( $product->get_id() ).'">' ;
		$html .= $product->get_image( $size, $attr, $placeholder );
		$html .= '</a>';
	}	
	$html .= sw_label_sales();
	return $html;
}

function emarket_woocommerce_template_loop_product_thumbnail(){
	echo emarket_product_thumbnail();
}

/*
** Product Category Listing
*/
add_filter( 'subcategory_archive_thumbnail_size', 'emarket_category_thumb_size' );
function emarket_category_thumb_size(){
	return 'woocommerce_thumbnail';
}

/*
** Filter order
*/
function emarket_addURLParameter($url, $paramName, $paramValue) {
     $url_data = parse_url($url);
     if(!isset($url_data["query"]))
         $url_data["query"]="";

     $params = array();
     parse_str($url_data['query'], $params);
     $params[$paramName] = $paramValue;
     $url_data['query'] = http_build_query($params);
     return emarket_build_url( $url_data );
}

/*
** Build url 
*/
function emarket_build_url($url_data) {
 $url="";
 if(isset($url_data['host']))
 {
	 $url .= $url_data['scheme'] . '://';
	 if (isset($url_data['user'])) {
		 $url .= $url_data['user'];
			 if (isset($url_data['pass'])) {
				 $url .= ':' . $url_data['pass'];
			 }
		 $url .= '@';
	 }
	 $url .= $url_data['host'];
	 if (isset($url_data['port'])) {
		 $url .= ':' . $url_data['port'];
	 }
 }
 if (isset($url_data['path'])) {
	$url .= $url_data['path'];
 }
 if (isset($url_data['query'])) {
	 $url .= '?' . $url_data['query'];
 }
 if (isset($url_data['fragment'])) {
	 $url .= '#' . $url_data['fragment'];
 }
 return $url;
}
if ( !function_exists('wp_get_attachment') ) {
    function wp_get_attachment( $attachment_id )
    {
        $attachment = get_post( $attachment_id );
        return array(
            'alt' => get_post_meta( $attachment->ID, '_wp_attachment_image_alt', true )
        );
    }
}
add_shortcode('emarket_woocommerce_layered_nav_bottom', 'add_clear_filters');
function add_clear_filters() {
    $filterreset = $_SERVER['REQUEST_URI'];
    $filterreset = strtok($filterreset, '?');
    echo '<a class="clear-all" href="'.$filterreset.'">'.esc_html__('Clear All','emarket').'</a>';
}

if( ( !emarket_options()->getCpanelValue( 'product_categories' ) && emarket_sidebar_product() != 'full') || ( !emarket_options()->getCpanelValue( 'shop_modern' ) && emarket_sidebar_product() != 'full' ) ){
	add_action( 'woocommerce_before_main_content', 'emarket_banner_listing', 10 );
}

//remove_action( 'woocommerce_before_shop_loop', 'woocommerce_result_count', 20 );
remove_action( 'woocommerce_before_shop_loop', 'woocommerce_catalog_ordering', 30 );
remove_action( 'woocommerce_before_shop_loop_item_title', 'woocommerce_show_product_loop_sale_flash', 10 );
remove_action( 'woocommerce_after_single_product_summary', 'woocommerce_upsell_display', 15 );
remove_action( 'woocommerce_after_shop_loop_item_title', 'woocommerce_template_loop_rating', 5 );
remove_action( 'woocommerce_after_shop_loop_item_title', 'woocommerce_template_loop_price', 10 );
remove_filter( 'woocommerce_product_loop_start', 'woocommerce_maybe_show_product_subcategories' );

add_filter( 'emarket_custom_category', 'woocommerce_maybe_show_product_subcategories' );
add_action( 'woocommerce_shop_loop_item_title', 'emarket_template_loop_categories', 5 );
add_action( 'woocommerce_after_shop_loop_item_title', 'emarket_template_loop_price', 11 );
add_action( 'woocommerce_after_shop_loop_item_title', 'woocommerce_template_loop_rating', 15 );
add_action( 'woocommerce_before_shop_loop', 'emarket_viewmode_wrapper_start', 5 );
add_action( 'woocommerce_before_shop_loop', 'emarket_viewmode_wrapper_end', 50 );
add_action( 'woocommerce_before_shop_loop', 'emarket_woocommerce_catalog_ordering', 35 );
add_action( 'woocommerce_before_shop_loop', 'woocommerce_pagination', 35 );
add_action( 'woocommerce_before_shop_loop','emarket_woommerce_view_mode_wrap',30 );
remove_action( 'woocommerce_before_shop_loop', 'wc_print_notices', 10 );
add_action('woocommerce_message','wc_print_notices', 10);

add_action( 'woocommerce_before_shop_loop', 'emarket_before_shop_loop_wrapper_start', 1 );
add_action( 'woocommerce_before_shop_loop', 'emarket_before_shop_loop_wrapper_end', 100 );

if( emarket_options()->getCpanelValue( 'product_loadmore' ) ){	
	add_action( 'woocommerce_after_shop_loop', 'woocommerce_pagination_ajax', 10 );	
	remove_action( 'woocommerce_after_shop_loop', 'woocommerce_pagination', 10 );
	remove_action( 'woocommerce_before_shop_loop', 'woocommerce_pagination', 35 );
}else{
	remove_action( 'woocommerce_before_shop_loop', 'woocommerce_pagination', 35 );
	add_action( 'woocommerce_after_shop_loop', 'woocommerce_pagination', 10 );
}
remove_action( 'woocommerce_before_shop_loop', 'wc_print_notices', 10 );
add_action('woocommerce_message','wc_print_notices', 10);
add_filter( 'woocommerce_pagination_args', 'emarket_custom_pagination_args' );

function emarket_template_loop_categories(){
	global $product;
	echo wc_get_product_category_list( $product->get_id(), ', ', '<div class="item-categories">' . _n( '', '', count( $product->get_category_ids() ), 'emarket' ) . ' ', '</div>' ); 
}

function woocommerce_pagination_ajax(){
	global $wp_query;
	$loadmore_style = ( emarket_options()->getCpanelValue( 'product_loadmore_style' ) ) ? emarket_options()->getCpanelValue( 'product_loadmore_style' ) : 0;
	$option_number 	= ( emarket_options()->getCpanelValue( 'product_number' ) ) ? emarket_options()->getCpanelValue( 'product_number' ) : 12;
	$posts_per_page = isset( $_GET['product_count'] ) ? $_GET['product_count'] : $option_number;
	$sw_loadmore = array(
		'nonce' => wp_create_nonce( 'sw_ajax_load_more' ),
		'url'   => admin_url( 'admin-ajax.php' ),
		'query' => $wp_query->query,
		'posts_per_page' => $posts_per_page
	);
	
	if(is_shop()){
		wp_enqueue_script( 'sw_loadmore', get_template_directory_uri() . '/js/product-loadmore.js', array(), null, true );
		wp_localize_script( 'sw_loadmore', 'loadmore', $sw_loadmore );		
		?>
<div class="pagination-ajax"><button
        class="button-ajax <?php echo esc_attr( ( $wp_query->max_num_pages == 1 ) ? 'loaded' : '' ) ?>"
        data-loadmore_style="<?php echo esc_attr( $loadmore_style ); ?>"
        data-maxpage="<?php echo esc_attr( $wp_query->max_num_pages ) ?>"
        data-title="<?php echo esc_attr__( 'Load More', 'emarket' ); ?>"
        data-loaded="<?php echo esc_attr__( 'All Item', 'emarket' ); ?>"></button></div>
<?php 
	}

}

/*
** Pagination Size to Show
*/
function emarket_custom_pagination_args( $args = array() ){
	$args['end_size'] = 2;
	$args['mid_size'] = 1;
	return $args;	
}

function emarket_before_shop_loop_wrapper_start()
{
    echo '<div class="product-nav">';

}

function emarket_before_shop_loop_wrapper_end()
{
	$product_filter_style 	=  emarket_options()->getCpanelValue( 'product_filter_style' );
    echo '</div>';
   if ( ( is_active_sidebar('above-product') && emarket_sidebar_product() == 'full' && $product_filter_style != 'accordion' ) || ( emarket_sidebar_product() == 'left' && $product_filter_style == 'drawer' ) ) {
        ?>
<div class="sw-filter-above-shop <?php echo ( $product_filter_style == 'canvas' ) ? 'sticky-filter' : '';?>"
    id="sw_filter_full">
    <?php if( $product_filter_style == 'canvas' ) : ?>
    <div class="filter-top">
        <h4><?php echo esc_html__('Filter', 'emarket'); ?></h4>
        <a href="#" data-target="#sw_filter_full" class="sw-filter-close"></a>
    </div>
    <?php endif; ?>
    <div class="filter-content">
        <?php dynamic_sidebar('above-product'); ?>
    </div>
</div>
<?php
    }
}

function emarket_shop_filter()
{
		$product_filter_style 	=  emarket_options()->getCpanelValue( 'product_filter_style' );
	
    if ( ( is_active_sidebar('above-product') && emarket_sidebar_product() == 'full' && $product_filter_style != 'accordion' ) || ( emarket_sidebar_product() == 'left' && $product_filter_style == 'drawer' ) ) {
        ?>
<a href="#" data-target="#sw_filter_full pull-right"
    class="sw-filter-button"><?php echo esc_html__('Filters', 'emarket'); ?><i class="fa fa-filter"></i>
</a>

<?php
    }
	 if ( ( is_active_sidebar('above-product') && $product_filter_style == 'accordion' ) ) {
        ?>
<div class="sw-filter-accordion">
    <h4><?php echo esc_html__('Filters by:', 'emarket'); ?></h4>
    <div class="sw-filter-above-shop" id="sw_filter_full">
        <div class="filter-content">
            <?php dynamic_sidebar('above-product'); ?>
        </div>
    </div>
</div>
<?php
}
}

function emarket_banner_listing(){	
	// Check Vendor page of WC MarketPlace
	global $WCMp;
	if ( class_exists( 'WCMp' ) && is_tax($WCMp->taxonomy->taxonomy_name) ) {
		return;
	}

	$shop_content 	=  emarket_options()->getCpanelValue( 'shop_content_top' );
	$banner_enable  = emarket_options()->getCpanelValue( 'product_banner' );
	$link_banner    = emarket_options()->getCpanelValue('link_banner_shop');
	$banner_listing = emarket_options()->getCpanelValue( 'product_listing_banner' );
	$html = '<div class="widget_sp_image">';
	if( 'img_banner' === $banner_enable ){
		$html .= ( $link_banner != '' ) ? '<a href="'.esc_url($link_banner).'">': '';
		$html .= '<img src="'. esc_url( $banner_listing ) .'" alt=""/>';
		$html .= ( $link_banner != '' ) ? '</a>': '';
	}
	elseif( 'page' === $banner_enable && $shop_content != ''){
		if( defined( 'ELEMENTOR_VERSION' ) && \Elementor\Plugin::$instance->documents->get( $shop_content ) ){
			$html .= do_shortcode( \Elementor\Plugin::$instance->frontend->get_builder_content( $shop_content ) );
		}else{
			$html .= sw_get_the_content_by_id( $shop_content );
		}
	}else{
		global $wp_query;
		$cat = $wp_query->get_queried_object();
		if( !is_shop() ) {
			$thumbnail_id = get_term_meta( $cat->term_id, 'thumbnail_id', true );
			$image = wp_get_attachment_url( $thumbnail_id );
			if( $image ) {
				$html .= ( $link_banner != '' ) ? '<a href="'.esc_url($link_banner).'">': '';
				$html .= '<img src="'. esc_url( $image ) .'" alt=""/>';
				$html .= ( $link_banner != '' ) ? '</a>': '';
			}else{
				$html .= ( $link_banner != '' ) ? '<a href="'.esc_url($link_banner).'">': '';
				$html .= '<img src="'. esc_url( $banner_listing ) .'" alt=""/>';
				$html .= ( $link_banner != '' ) ? '</a>': '';
			}
		}else{
			$html .= ( $link_banner != '' ) ? '<a href="'.esc_url($link_banner).'">': '';
			$html .= '<img src="'. esc_url( $banner_listing ) .'" alt=""/>';
			$html .= ( $link_banner != '' ) ? '</a>': '';
		}
	}
	$html .= '</div>';
	if( !is_singular( 'product' ) ){
		echo $html;
	}
}

function emarket_viewmode_wrapper_start(){
	$product_filter_style 	=  emarket_options()->getCpanelValue( 'product_filter_style' );
	echo '<div class="products-nav '.( ( $product_filter_style == 'accordion' ) ? 'accordion-style' : '').' clearfix">';
	emarket_shop_filter();
}
function emarket_viewmode_wrapper_end(){
	echo '</div>';
}
function emarket_woommerce_view_mode_wrap () {
	global $wp_query;

	if ( ! woocommerce_products_will_display() || $wp_query->is_search() ) {
		return;
	}
	$col_lg = emarket_options()->getCpanelValue( 'product_col_large' );
	$class_grid = (   emarket_options()->getCpanelValue( 'product_layout' ) != 'list2' ) ? 'active' : '';
	$class_list = (   emarket_options()->getCpanelValue( 'product_layout' ) == 'list2' ) ? 'active' : '';

	$html = '<div class="view-mode-wrap pull-right clearfix">
				<div class="view-mode">
						<a href="#" class="list-view2 '. esc_attr( $class_list ) .'" title="'. esc_attr__('List view2', 'emarket') .'"><span>'.esc_html__('List view2', 'emarket').'</span></a>
						<a href="#" class="grid-view '. esc_attr( $class_grid ) .'" title="'. esc_attr__('Grid view', 'emarket').'"><span>'. esc_html__('Grid view', 'emarket').'</span></a>				</div>	
			</div>';
	echo $html;
}

function emarket_template_loop_price(){
	global $product;
	?>
<?php if ( $price_html = $product->get_price_html() ) : ?>
<span class="item-price"><?php echo $price_html; ?></span>
<?php endif;
}

function emarket_woocommerce_catalog_ordering() { 
	global $wp_query;
	$product_loadmore 	=  emarket_options()->getCpanelValue( 'product_loadmore' );

	if ( 1 === (int) $wp_query->found_posts || ! woocommerce_products_will_display() || $wp_query->is_search() ) {
		return;
	}
	
	parse_str($_SERVER['QUERY_STRING'], $params);
	$query_string 	= '?'.$_SERVER['QUERY_STRING'];
	$option_number 	=  emarket_options()->getCpanelValue( 'product_number' );
	
	if( $option_number ) {
		$per_page = $option_number;
	} else {
		$per_page = 12;
	}
	
	$pob = !empty( $params['orderby'] ) ? $params['orderby'] : get_option( 'woocommerce_default_catalog_orderby' );
	$po  = !empty($params['product_order'])  ? $params['product_order'] : 'desc';
	$pc  = !empty($params['product_count']) ? $params['product_count'] : $per_page;

	$html = '';
	$html .= '<div class="catalog-ordering">';

	$html .= '<div class="orderby-order-container clearfix">';
	$paged = ( get_query_var('paged') ) ? get_query_var('paged') : 1;
	$max_page = ( $wp_query->max_num_pages >=5 ) ? 5: $wp_query->max_num_pages;
	
	if( $max_page > 1 ): 
	$html .= '<div class="product-number pull-left clearfix"><span class="show-product pull-left">'. esc_html__( 'Show:', 'emarket' ) . ' </span>';
	
	$html .= '<ul class="sort-count order-dropdown pull-left">';
	$html .= '<li>';
	$html .= '<span class="current-li"><a>'. $per_page . '</a><span>'. esc_html__( 'items', 'emarket' ) . '</span></span>';
	$html .= '<ul>';
	
	$i = 1;
	while( $i > 0 && $i <= $max_page ){
		if( $per_page* $i* $paged < intval( $wp_query->found_posts ) ){
			$html .= '<li class="'.( ( $pc == $per_page* $i ) ? 'current': '').'"><a href="'.emarket_addURLParameter( $query_string, 'product_count', $per_page* $i ).'">'. $per_page* $i .'</a></li>';
		}
		$i++;
	}
	
	$html .= '</ul>';
	$html .= '</li>';
	$html .= '</ul></div>';
	endif; 
	
	$html .= '</div>';
	$html .= '</div>';
	ob_start();
	$html .= '<div class="product-sortby pull-right clearfix"><span class="sort-by pull-left">'. esc_html__( 'Sort by', 'emarket' ) . ' </span>';
	woocommerce_catalog_ordering();	
	$html .= ob_get_clean();
	$html .= '</div>';
	if( emarket_mobile_check() ) : 
	$html .= '<div class="filter-product">'. esc_html__('Filter','emarket') .'</div>';
		endif;
	echo $html;
}

add_filter('loop_shop_per_page', 'emarket_loop_shop_per_page');
function emarket_loop_shop_per_page() {
	parse_str($_SERVER['QUERY_STRING'], $params);
	$option_number =  emarket_options()->getCpanelValue( 'product_number' );
	
	if( $option_number ) {
		$per_page = $option_number;
	} else {
		$per_page = 12;
	}

	$pc = !empty($params['product_count']) ? $params['product_count'] : $per_page;
	return $pc;
}

/* =====================================================================================================
** Product loop content 
	 ===================================================================================================== */
	 
/*
** attribute for product listing
*/
function emarket_product_attribute(){
	global $woocommerce_loop;
	
	$col_lg = emarket_options()->getCpanelValue( 'product_col_large' );
	$col_md = emarket_options()->getCpanelValue( 'product_col_medium' );
	$col_sm = emarket_options()->getCpanelValue( 'product_col_sm' );
	$col_mb = emarket_options()->getCpanelValue( 'product_col_mb' );
	$class_col= "item ";
	
	if( isset( get_queried_object()->term_id ) ) :
		$term_col_lg  = get_term_meta( get_queried_object()->term_id, 'term_col_lg', true );
		$term_col_md  = get_term_meta( get_queried_object()->term_id, 'term_col_md', true );
		$term_col_sm  = get_term_meta( get_queried_object()->term_id, 'term_col_sm', true );

		$col_lg = ( intval( $term_col_lg ) > 0 ) ? $term_col_lg : emarket_options()->getCpanelValue( 'product_col_large' );
		$col_md = ( intval( $term_col_md ) > 0 ) ? $term_col_md : emarket_options()->getCpanelValue( 'product_col_medium' );
		$col_sm = ( intval( $term_col_sm ) > 0 ) ? $term_col_sm : emarket_options()->getCpanelValue( 'product_col_sm' );
	endif;
	
	$column1 = str_replace( '.', '' , floatval( 12 / $col_lg ) );
	$column2 = str_replace( '.', '' , floatval( 12 / $col_md ) );
	$column3 = str_replace( '.', '' , floatval( 12 / $col_sm ) );
	if( $col_mb == 2 ){
		$column4 = 'mb-2column';
	}else{
		$column4 = 'mb-1column';
	}

	$class_col .= ' col-lg-'.$column1.' col-md-'.$column2.' col-sm-'.$column3.' col-xs-6 '.$column4.'';
	
	return esc_attr( $class_col );
}

/*
** Check sidebar 
*/
function emarket_sidebar_product(){
	$emarket_sidebar_product = emarket_options() -> getCpanelValue('sidebar_product');
	if( isset( get_queried_object()->term_id ) ){
		$emarket_sidebar_product = ( get_term_meta( get_queried_object()->term_id, 'term_sidebar', true ) != '' ) ? get_term_meta( get_queried_object()->term_id, 'term_sidebar', true ) : emarket_options()->getCpanelValue('sidebar_product');
	}	
	if( is_singular( 'product' ) ) {
		$emarket_sidebar_product = ( get_post_meta( get_the_ID(), 'page_sidebar_layout', true ) != '' ) ? get_post_meta( get_the_ID(), 'page_sidebar_layout', true ) : emarket_options()->getCpanelValue('sidebar_product_detail');
	}
	return $emarket_sidebar_product;
}
	 
remove_action( 'woocommerce_after_shop_loop_item', 'woocommerce_template_loop_product_link_close', 5 );
remove_action( 'woocommerce_shop_loop_item_title', 'woocommerce_template_loop_product_title', 10 );
remove_action( 'woocommerce_after_shop_loop_item', 'product_designer_woocommerce_after_shop_loop_item',90 );
add_action( 'woocommerce_shop_loop_item_title', 'emarket_loop_product_title', 10 );
add_action( 'woocommerce_after_shop_loop_item_title', 'emarket_product_description', 10 );
add_action( 'woocommerce_after_shop_loop_item', 'emarket_product_addcart_start', 1 );
add_action( 'woocommerce_after_shop_loop_item', 'emarket_product_addcart_mid', 5 );
add_action( 'woocommerce_after_shop_loop_item', 'emarket_product_addcart_end', 99 );
if( emarket_options()->getCpanelValue( 'product_listing_countdown' ) ){
	add_action( 'woocommerce_after_shop_loop_item_title', 'emarket_product_deal', 20 );
}

function emarket_loop_product_title(){
	?>
<h4><a href="<?php the_permalink() ?>"
        title="<?php the_title_attribute(); ?>"><?php emarket_trim_words( get_the_title() ); ?></a></h4>
<?php
}

function emarket_product_description(){
	global $post;
	if ( ! $post->post_excerpt ) return;
	
	echo '<div class="item-description">'.wp_trim_words( $post->post_excerpt, 20 ).'</div>';
}

function emarket_product_addcart_start(){
	global $post;
	echo '<div class="item-bottom '. (  $post->post_excerpt ? 'has-des':'' ) .' clearfix">';
}

function emarket_product_addcart_end(){
	echo '</div>';
}

function emarket_product_addcart_mid(){
	global $post;
	$quickview = emarket_options()->getCpanelValue( 'product_quickview' );

	$html ='';
	$product_id = $post->ID;
	/* quickview */
	if( !emarket_mobile_check() ) {
		$html .= emarket_quickview();
	}
	
	/* compare & wishlist */
	if( !emarket_mobile_check() && class_exists( 'YITH_WCWL' ) ){
		$html .= do_shortcode( "[yith_wcwl_add_to_wishlist]" );
	}
	if( !emarket_mobile_check() && class_exists( 'YITH_WOOCOMPARE' )  ){
		
		$html .= '<a href="javascript:void(0)" class="compare button" data-product_id="'. $product_id .'" rel="nofollow">'. esc_html__( 'Compare', 'emarket' ) .'</a>';	
	}
	echo $html;
}

/*
** Add page deal to listing
*/

add_shortcode('emarket_single_deal', 'emarket_product_deal');
function emarket_product_deal(){
	if( is_singular( 'product' ) || is_shop() || is_tax( 'product_cat' ) || is_tax( 'product_tag' ) || is_post_type_archive( 'product' ) ) {
		global $product, $post;
		$start_time 	= get_post_meta( $product->get_id(), '_sale_price_dates_from', true );
		$countdown_time = get_post_meta( $product->get_id(), '_sale_price_dates_to', true );	
		if( !empty ($countdown_time ) && $countdown_time > $start_time ) :
?>
<?php if( is_singular( 'product' ) && emarket_options()->getCpanelValue( 'product_single_countdown' ) ) : ?>
	<div class="single-countdown">
		<div class="wrap-countdown">
			<div class="countdown-time">
				<h4 class="title-deals"><?php echo esc_html__('hurry up!','emarket'); ?><br><?php echo esc_html__(' sales ends soon!','emarket'); ?></h4>
				<div class="product-countdown" data-date="<?php echo esc_attr( $countdown_time ); ?>"
				data-starttime="<?php echo esc_attr( $start_time ); ?>" data-cdtime="<?php echo esc_attr( $countdown_time ); ?>"
				data-id="<?php echo esc_attr( 'product_' . $product->get_id() ); ?>"></div>
			</div>
			<div class="sold-item">
				<?php 
					if( $product->get_stock_quantity() != 0 ){ 
					$available 	 = $product->get_stock_quantity();
					$total_sales = get_post_meta( $post->ID, 'total_sales', true );
					$bar_width 	 = intval( $available ) / intval( $available + $total_sales ) * 100;
				?>
				<div class="emarket-stock clearfix">
					<div class="stock-sold pull-left"><?php esc_html_e( 'Sold items', 'sw_woocommerce' ) ?></div>
				</div>
				<div class="sales-bar clearfix">
					<div class="sales-bar-total">
						<span style="width: <?php echo esc_attr( $bar_width . '%' ); ?>"></span>
					</div>
					<span><?php echo $total_sales; ?>/<?php echo $available; ?></span>
				</div>
				<?php } ?>
			</div>
		</div>
	</div>
<?php elseif ( !is_singular( 'product' ) ) : ?>
<div class="product-countdown" data-date="<?php echo esc_attr( $countdown_time ); ?>"
    data-starttime="<?php echo esc_attr( $start_time ); ?>" data-cdtime="<?php echo esc_attr( $countdown_time ); ?>"
    data-id="<?php echo esc_attr( 'product_' . $product->get_id() ); ?>"></div>
<?php 
		endif;
		endif;
	}
}

/*
** Filter product category class
*/
add_filter( 'product_cat_class', 'emarket_product_category_class', 2 );
function emarket_product_category_class( $classes, $category = null ){
	global $woocommerce_loop;
	
	$col_lg = ( emarket_options()->getCpanelValue( 'product_colcat_large' ) )  ? emarket_options()->getCpanelValue( 'product_colcat_large' ) : 1;
	$col_md = ( emarket_options()->getCpanelValue( 'product_colcat_medium' ) ) ? emarket_options()->getCpanelValue( 'product_colcat_medium' ) : 1;
	$col_sm = ( emarket_options()->getCpanelValue( 'product_colcat_sm' ) )	   ? emarket_options()->getCpanelValue( 'product_colcat_sm' ) : 1;
	
	
	$column1 = str_replace( '.', '' , floatval( 12 / $col_lg ) );
	$column2 = str_replace( '.', '' , floatval( 12 / $col_md ) );
	$column3 = str_replace( '.', '' , floatval( 12 / $col_sm ) );

	$classes[] = ' col-lg-'.$column1.' col-md-'.$column2.' col-sm-'.$column3.' col-xs-6';
	
	return $classes;
}

/* ==========================================================================================
	** Single Product
   ========================================================================================== */

add_action('sticky_single_product', 'emarket_sticky_content_open', 0 );
add_action('sticky_single_product', 'emarket_single_title', 5 );
add_action('sticky_single_product', 'woocommerce_template_single_rating', 6 );
add_action('sticky_single_product', 'emarket_woocommerce_single_price', 7 );
add_action('sticky_single_product', 'emarket_sticky_content_close', 10 );
	
add_action('vendor_single_product', 'emarket_single_vendor_info', 1);

remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_title', 5 );
remove_action( 'woocommerce_before_single_product_summary', 'woocommerce_show_product_sale_flash', 10 );
add_action( 'woocommerce_single_product_summary', 'emarket_single_title', 5 );
add_action( 'woocommerce_single_product_summary', 'emarket_woocommerce_sharing', 55 );
add_action( 'woocommerce_single_product_summary', 'emarket_get_brand', 7);
remove_action( 'woocommerce_after_single_product_summary', 'woocommerce_upsell_display', 15 );
remove_action( 'woocommerce_after_single_product_summary', 'woocommerce_output_related_products', 20 );
remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_meta', 40 );
remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_sharing', 50 );
remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_price', 10 );

add_action( 'woocommerce_single_product_summary', 'emarket_woocommerce_single_price', 10 );
add_action( 'woocommerce_single_product_summary', 'emarket_woocommerce_meta', 50 );
add_action( 'woocommerce_before_single_product_summary', 'sw_label_sales', 10 );
add_action( 'woocommerce_before_single_product_summary', 'sw_label_stock', 11 );

add_action( 'show_stock', 'sw_label_stock');


if( emarket_options()->getCpanelValue( 'product_single_countdown' ) ){
	add_action( 'woocommerce_single_product_summary', 'emarket_product_deal',25 );
}

function emarket_woocommerce_meta(){
	global $product; ?>

<div class="item-meta">
    <div class="wrap">
        <?php echo wc_get_product_category_list( $product->get_id(), ', ', '<span class="posted_in">' . _n( 'Category:', 'Categories:', count( $product->get_category_ids() ), 'emarket' ) . ' ', '</span>' ); ?>

        <?php echo wc_get_product_tag_list( $product->get_id(), ', ', '<span class="tagged_as">' . _n( 'Tag:', 'Tags:', count( $product->get_tag_ids() ), 'emarket' ) . ' ', '</span>' ); ?>
    </div>
</div>

<?php
}

function emarket_woocommerce_sharing(){
	global $product;
	emarket_get_social();
}

add_shortcode('emarket_social_share', 'emarket_woocommerce_sharing');
remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_excerpt', 20 );
add_action( 'woocommerce_single_product_summary', 'emarket_product_excerpt', 20 );

add_action( 'woocommerce_single_product_summary', 'emarket_product_stock', 40 );
function emarket_woocommerce_single_price(){
	wc_get_template( 'single-product/price.php' );
}

function emarket_product_excerpt(){
	global $post;
	
	if ( ! $post->post_excerpt ) {
		return;
	}
	$html = '';
	$html .= '<div class="description" itemprop="description">';
	$html .= '<h4>'.esc_html__('Product Highlights', 'emarket').'</h4>';
	$html .= apply_filters( 'woocommerce_short_description', $post->post_excerpt );
	$html .= '</div>';
	echo $html;
	
}
function emarket_guide_size(){
	global $post;
	$emarket_product_preview = get_post_meta( $post->ID, 'guide_product_preview', true );
	$attach_url =( wp_get_attachment_url( $emarket_product_preview ) ) ? wp_get_attachment_url( $emarket_product_preview ) : '';
	
	if( $attach_url !='') { ?>
	<div class="guide_size_block ">
		<div class="guide_size" ><i class="fa fa-file" aria-hidden="true"></i><?php echo esc_html__( 'Guide size','emarket' ) ?></div>
		<div class="block-up ">
			<img  src="<?php echo esc_attr( $attach_url ); ?>">
		</div>
	</div>
	<?php } 
}

function emarket_single_title(){
	echo the_title( '<h1 itemprop="name" class="product_title entry-title">', '</h1>' );
}

function emarket_product_stock(){
	global $product;
	$shipping_class_id   = $product->get_shipping_class_id();
	$shipping_class_name = '';
	$shipping_class_term = get_term($shipping_class_id, 'product_shipping_class');
	if( ! is_wp_error($shipping_class_term) && is_a($shipping_class_term, 'WP_Term') ) {
		$shipping_class_name  = $shipping_class_term->name;
	}
	
	if( !emarket_mobile_check() ) :?>
<?php $stock = ( $product->is_in_stock() )? 'in-stock' : 'out-stock' ;?>
<div class="product-info <?php echo esc_attr( $stock ); ?>">
    <div class="product-stock <?php echo esc_attr( $stock ); ?>">
        <span><?php echo ( $product->is_in_stock() )? esc_html__( 'in stock', 'emarket' ) : ''; ?></span>
    </div>
    <?php if( $product->is_in_stock() ) :?>
    <?php if( $shipping_class_name != '' ) : ?><div class="shipping-text"><i
            class="fa fa-truck"></i><?php echo  $shipping_class_name; ?></div> <?php endif; ?>
    <?php else: ?>
    <div class="subscribe-form-out-stock">
        <h4><?php echo esc_html__('Email when stock available','emarket');?></h4>
        <?php echo do_shortcode( '[mc4wp_form]' ); ?>
    </div>
    <?php endif; ?>
</div>
<?php endif;
}

function emarket_product_stock_hompage(){
	global $product;
	$shipping_class_id   = $product->get_shipping_class_id();
	$shipping_class_name = '';
	$shipping_class_term = get_term($shipping_class_id, 'product_shipping_class');
	if( ! is_wp_error($shipping_class_term) && is_a($shipping_class_term, 'WP_Term') ) {
		$shipping_class_name  = $shipping_class_term->name;
	}
	
	if( !emarket_mobile_check() ) :?>
<?php $stock = ( $product->is_in_stock() )? 'in-stock' : 'out-stock' ;?>
<div class="product-info <?php echo esc_attr( $stock ); ?>">
    <div class="product-stock <?php echo esc_attr( $stock ); ?>">
        <span><?php echo ( $product->is_in_stock() )? '<span class="in stock">'.esc_html__( 'in stock', 'emarket' ).'</span>' : '<span class="out stock">'.esc_html__( 'Out stock', 'emarket' ).'</span>'; ?></span>
    </div>
</div>
<?php endif;
}

/**
* Get brand on the product single
**/

function emarket_get_brand(){
	global $post, $product;
	$terms = wp_get_object_terms( $post->ID, 'product_brand' );
	?>
<?php	
	if( !isset( $terms->errors ) && $terms ){
?>
<div class="item-brand <?php echo emarket_options()->getCpanelValue( 'product_brand' ) ? 'brand-image' : '';?>">
    <?php 
				foreach( $terms as $key => $term ){
					$thumbnail_id = absint( get_term_meta( $term->term_id, 'thumbnail_bid', true ) );
					if( $thumbnail_id && emarket_options()->getCpanelValue( 'product_brand' ) ){
			?>
    <a href="<?php echo get_term_link( $term->term_id, 'product_brand' ); ?>"><img
            src="<?php echo wp_get_attachment_url( $thumbnail_id, 'full' ); ?>" alt="image"
            title="<?php echo esc_attr( $term->name ); ?>" /></a>
    <?php 
					}else{
			?>
    <a href="<?php echo get_term_link( $term->term_id, 'product_brand' ); ?>"><?php echo $term->name; ?></a>
    <?php echo( ( $key + 1 ) === count( $terms ) ) ? '' : ', '; ?>
    <?php 
					}					
				}
			?>
</div>
<?php 
	}
}
add_shortcode('emarket_brand', 'emarket_get_brand');


function emarket_sticky_content_open(){
?>
	<div class="item-center">
<?php 
}

function emarket_sticky_content_close(){
?>
	</div>
<?php 
}
/*
** Get info user vendor
*/

function emarket_single_vendor_info(){
	global $product, $post;
	$html = '';
	$vendor_id = get_post_field( 'post_author', get_the_id() );
	$vendor = new WP_User($vendor_id);
		if ( class_exists( 'WC_Vendors' ) && in_array( 'vendor', (array) $vendor->roles ) ): 

			$count = count_user_posts($vendor_id, 'product');
			$time_ago = strtotime($vendor->user_registered);
			$cur_time   = time();
			$time_elapsed   = $cur_time - $time_ago;
			$joined = round($time_elapsed/2600640);
			$day = round($time_elapsed/86400);
			
			$html .= '<div class="single-vendor-info-top">';
			$html .= '<div class="wrap-content">';
			$html .= '<div class="item-top clearfix"><div class="item-image"><a href="'.esc_url( WCV_Vendors::get_vendor_shop_page( $vendor->ID ) ).'">'.get_avatar($vendor->ID, 80).'</a></div>';
			$html .= '<div class="item-name"><a href="'.esc_url( WCV_Vendors::get_vendor_shop_page( $vendor->ID ) ).'">'.$vendor->user_nicename.'</a></div></div>';
			$html .= '<div class="joined"><span>'.esc_html__('Joined: ','emarket').'</span>'. ( $joined > 0 ? $joined : $day ) . ( $joined > 0 ? esc_html__(' months','emarket') : esc_html__(' days','emarket') ).'</div>';
			$html .= '<div class="product"><span>'.esc_html__('Products: ','emarket').'</span>'. $count . esc_html__(' products','emarket') .'</div>';
			$html .= '<a href="'.esc_url( WCV_Vendors::get_vendor_shop_page( $vendor->ID ) ).'">'. esc_html__('Visit Store','emarket') .'</a>';
			$html .= '</div></div>';
			if( get_user_meta( $vendor->ID, 'pv_shop_description', true ) ):
				$html .= '<div class="single-vendor-info-bottom">';
				$html .= '<div class="wrap-content">';
				$html .= get_user_meta( $vendor->ID, 'pv_shop_description', true );
				$html .= '</div></div>';
			endif;
		elseif( class_exists( 'WeDevs_Dokan' ) && in_array( 'seller', (array) $vendor->roles ) ) :
			$count = count_user_posts($vendor_id, 'product');
			$time_ago = strtotime($vendor->user_registered);
			$cur_time   = time();
			$time_elapsed   = $cur_time - $time_ago;
			$joined = round($time_elapsed/2600640);
			$day = round($time_elapsed/86400);
			
			$store_info = dokan_get_store_info( $vendor_id );
			$count = count_user_posts( $vendor_id, 'product');
			$store_name = isset( $store_info['store_name'] ) ? esc_html( $store_info['store_name'] ) : __( 'N/A', 'sw_vendor_slider' );
			$store_url  = dokan_get_store_url( $vendor_id );
			
			$html .= '<div class="single-vendor-info-top">';
			$html .= '<div class="wrap-content">';
			$html .= '<div class="item-top clearfix"><div class="item-image"><a href="'.$store_url.'">'.get_avatar($vendor->ID, 80).'</a></div>';
			$html .= '<div class="item-name"><a href="'.$store_url.'">'.$vendor->user_nicename.'</a></div></div>';
			$html .= '<div class="joined"><span>'.esc_html__('Joined: ','emarket').'</span>'. ( $joined > 0 ? $joined : $day ) . ( $joined > 0 ? esc_html__(' months','emarket') : esc_html__(' days','emarket') ).'</div>';
			$html .= '<div class="product"><span>'.esc_html__('Products: ','emarket').'</span>'. $count . esc_html__(' products','emarket') .'</div>';
			$html .= '<a href="'.$store_url.'">'. esc_html__('Visit Store','emarket') .'</a>';
			$html .= '</div></div>';
			
		elseif( class_exists('WCFMmp') && in_array( 'wcfm_vendor', (array) $vendor->roles ) ) :
			$count = count_user_posts($vendor_id, 'product');
			
			$time_ago = strtotime($vendor->user_registered);
			$cur_time   = time();
			$time_elapsed   = $cur_time - $time_ago;
			$joined = round($time_elapsed/2600640);
			$day = round($time_elapsed/86400);
			
			global $WCFMmp;
			$store_user  = wcfmmp_get_store( $vendor_id );
			$store_info  = $store_user->get_shop_info();
			
			$gravatar        = $store_user->get_avatar();
			$store_url       = wcfmmp_get_store_url( $vendor_id );
			$store_name      = wcfm_get_vendor_store_name( $vendor_id );	
			$wcfm_shop_description = apply_filters( 'woocommerce_short_description', $store_user->get_shop_description() );			
			
			$html .= '<div class="single-vendor-info-top">';
			$html .= '<div class="wrap-content">';
			$html .= '<div class="item-top clearfix"><div class="item-image"><a href="'.$store_url.'"><img width="80" src='. $gravatar. ' /></a></div>';
			$html .= '<div class="item-name"><a href="'.$store_url.'">'.$store_name .'</a></div></div>';
			$html .= '<div class="joined"><span>'.esc_html__('Joined: ','emarket').'</span>'. ( $joined > 0 ? $joined : $day ) . ( $joined > 0 ? esc_html__(' months','emarket') : esc_html__(' days','emarket') ).'</div>';
			$html .= '<div class="product"><span>'.esc_html__('Products: ','emarket').'</span>'. $count . esc_html__(' products','emarket') .'</div>';
			$html .= '<a href="'.$store_url.'">'. esc_html__('Visit Store','emarket') .'</a>';
			$html .= '</div></div>';
			if( $wcfm_shop_description ):
				$html .= '<div class="single-vendor-info-bottom">';
				$html .= '<div class="wrap-content">';
				$html .= $wcfm_shop_description;
				$html .= '</div></div>';
			endif;
		
		elseif( class_exists('WCMp') && in_array( 'dc_vendor', (array) $vendor->roles ) ) :
			$count = count_user_posts($vendor_id, 'product');
			
			$time_ago = strtotime($vendor->user_registered);
			$cur_time   = time();
			$time_elapsed   = $cur_time - $time_ago;
			$joined = round($time_elapsed/2600640);
			$day = round($time_elapsed/86400);
			
			$store_icon_src = wp_get_attachment_image_src( get_post_meta( $vendor_id, '_vendor_image', true ), array( 80, 80 ) );
			
			global $WCMp;
			$vendor = get_wcmp_vendor($vendor_id);
			$description = $vendor->description;			
			
			$html .= '<div class="single-vendor-info-top">';
			$html .= '<div class="wrap-content">';
			$html .= '<div class="item-top clearfix"><div class="item-image"><a href="'.$vendor->get_permalink().'"><img alt="avatar" src='. esc_url( $store_icon_src ) .' /></a></div>';
			$html .= '<div class="item-name"><a href="'.$vendor->get_permalink().'">'.$vendor->page_title .'</a></div></div>';
			$html .= '<div class="joined"><span>'.esc_html__('Joined: ','emarket').'</span>'. ( $joined > 0 ? $joined : $day ) . ( $joined > 0 ? esc_html__(' months','emarket') : esc_html__(' days','emarket') ).'</div>';
			$html .= '<div class="product"><span>'.esc_html__('Products: ','emarket').'</span>'. $count . esc_html__(' products','emarket') .'</div>';
			$html .= '<a href="'.$vendor->get_permalink().'">'. esc_html__('Visit Store','emarket') .'</a>';
			$html .= '</div></div>';
			if( $description ):
				$html .= '<div class="single-vendor-info-bottom">';
				$html .= '<div class="wrap-content">';
				$html .= $description;
				$html .= '</div></div>';
			endif;
		endif;
	echo $html;
}
add_shortcode('emarket_product_vendor', 'emarket_single_vendor_info');

add_action( 'woocommerce_before_add_to_cart_button', 'emarket_single_addcart_wrapper_start', 10 );
add_action( 'woocommerce_after_add_to_cart_button', 'emarket_single_addcart_wrapper_end', 20 );
add_action( 'woocommerce_after_add_to_cart_button', 'emarket_single_addcart', 10 );

if( emarket_options()->getCpanelValue( 'product_single_style' ) == 'style9' ){
	remove_action( 'woocommerce_after_add_to_cart_button', 'emarket_single_addcart', 10 );
	remove_action( 'woocommerce_single_product_summary', 'emarket_product_stock', 40 );
}
remove_action( 'woocommerce_after_single_product_summary', 'woocommerce_output_related_products', 20 );

function emarket_single_addcart_wrapper_start(){
	global $product;
	$class = ( emarket_options()->getCpanelValue( 'product_single_buynow' ) && !in_array( $product->get_type(), array( 'grouped', 'external' ) ) ) ? 'single-buynow' : '';
	echo '<div class="addcart-wrapper '. esc_attr( $class ) .' clearfix">';
}

function emarket_single_addcart_wrapper_end(){
	echo "</div>";
}

function emarket_single_addcart(){
	/* compare & wishlist */
	global $product, $post;
	$html = '';
	$product_id = $product->get_id();
	$availability = $product->get_availability();

	if( emarket_options()->getCpanelValue( 'product_single_buynow' ) && $availability['class'] == 'in-stock' && !in_array( $product->get_type(), array( 'grouped', 'external' ) ) ){
		$args = array(
			'add-to-cart' => $product_id,
		);
		if( $product->get_type() == 'variable' ){
			$args['variation_id'] = '';
		}
		$html .= '<a class="button-buynow" href="'. add_query_arg( $args, get_permalink( get_option( 'woocommerce_checkout_page_id' ) ) ) .'" data-url="'. add_query_arg( $args, get_permalink( get_option( 'woocommerce_checkout_page_id' ) ) ) .'">'. esc_html__( 'Buy Now', 'emarket' ) .'</a>';
		$html .= '<div class="clear"></div>';
	}
	$product_type = ( sw_woocommerce_version_check( '3.0' ) ) ? $product->get_type() : $product->product_type;
	/* compare & wishlist */
	if( class_exists( 'YITH_WCWL' ) || class_exists( 'YITH_WOOCOMPARE' ) ){
		$html .= '';	
		if( class_exists( 'YITH_WCWL' ) ){
			$html .= do_shortcode( "[yith_wcwl_add_to_wishlist]" );
		}
		if( !emarket_mobile_check() && class_exists( 'YITH_WOOCOMPARE' )  ){
		
			$html .= '<a href="javascript:void(0)" class="compare button" data-product_id="'. $product_id .'" rel="nofollow">'. esc_html__( 'Compare', 'emarket' ) .'</a>';	
		}
	}
	echo $html;
	/* Working not shutdown*/
}

add_shortcode( 'shortcode_sticky_detail','emarket_sticky_detail' );
add_action( 'wp_footer', 'emarket_sticky_detail' );
function emarket_sticky_detail(){
	if( emarket_options()->getCpanelValue( 'sticky_single_product' ) != '' && is_singular( 'product' ) ) : 
?>
<div class="sticky-detail">
    <div class="container">
        <?php do_action( 'woocommerce_before_shop_loop_item_title' );?>
        <div class="item-content">
            <?php do_action('sticky_single_product'); ?>
        </div>
    </div>
</div>
<?php endif; ?>
<?php
}

/* 
** Add Product Tag To Tabs 
*/
add_filter( 'woocommerce_product_tabs', 'emarket_tab_tag' );
function emarket_tab_tag($tabs){
	global $post;
	$tag_count = get_the_terms( $post->ID, 'product_tag' );
	if (  $tag_count ) {
		$tabs['product_tag'] = array(
			'title'    => esc_html__( 'Tags', 'emarket' ),
			'priority' => 11,
			'callback' => 'emarket_single_product_tab_tag'
		);
	}
	return $tabs;
}

function emarket_single_product_tab_tag(){
	global $product;
	echo wc_get_product_tag_list( $product->get_id(), ', ', '<span class="tagged_as">' . _n( 'Tag:', 'Tags:', count( $product->get_tag_ids() ), 'emarket' ) . ' ', '</span>' );
}

/*
**Hook into review for rick snippet
*/
add_action( 'woocommerce_review_before_comment_meta', 'emarket_title_ricksnippet', 10 ) ;
function emarket_title_ricksnippet(){
	global $post;
	echo '<span class="hidden" itemprop="itemReviewed" itemscope itemtype="http://schema.org/Thing">
    <span itemprop="name">'. $post->post_title .'</span>
  </span>';
}

/*
** Cart cross sell
*/
add_action('woocommerce_cart_collaterals', 'emarket_cart_collaterals_start', 1 );
add_action('woocommerce_cart_collaterals', 'emarket_cart_collaterals_end', 11 );
function emarket_cart_collaterals_start(){
	echo '<div class="products-wrapper">';
}

function emarket_cart_collaterals_end(){
	echo '</div>';
}

/*
** Set default value for compare and wishlist 
*/
function emarket_cpwl_init(){
	if( class_exists( 'YITH_WCWL' ) ){
		update_option( 'yith_wcwl_button_position', 'shortcode' );
		update_option( 'yith_wcwl_after_add_to_wishlist_behaviour', 'add' );
	}
	if( class_exists( 'YITH_WOOCOMPARE' ) ){
		update_option( 'yith_woocompare_compare_button_in_product_page', 'no' );
		update_option( 'yith_woocompare_compare_button_in_products_list', 'no' );
	}
}
add_action('admin_init','emarket_cpwl_init');

/*
** Add thumbnail to group product type
*/
add_action( 'woocommerce_grouped_product_list_before_price','woocommerce_grouped_product_thumbnail');

function woocommerce_grouped_product_thumbnail( $product ) {
$image_size = array( 100, 100 );  // array( width, height ) image size in pixel
$attachment_id = get_post_meta( $product->get_id(), '_thumbnail_id', true );
$link = get_the_permalink($product->get_id());
?>
<td class="label">
    <a href="<?php echo $link; ?>"> <?php echo 
wp_get_attachment_image($attachment_id, $image_size ); ?> </a>
</td>
<?php
}

/*
** Add quantity text in product
*/

//add_action( 'woocommerce_before_add_to_cart_quantity', 'emarket_qty_front_add_cart' );
add_action( 'woocommerce_after_add_to_cart_button', 'emarket_guide_size' );
function emarket_qty_front_add_cart() {
 echo '<div class="qty">'.esc_html__( 'Quantity:','emarket' ).'</div>'; 
}

/*
** Quickview product
*/
add_action( 'wc_ajax_emarket_quickviewproduct', 'emarket_quickviewproduct' );
add_action( 'wc_ajax_nopriv_emarket_quickviewproduct', 'emarket_quickviewproduct' );
function emarket_quickviewproduct(){
	
	$productid = ( isset( $_REQUEST["product_id"] ) && $_REQUEST["product_id"] > 0 ) ? $_REQUEST["product_id"] : 0;
	$query_args = array(
		'post_type'	=> 'product',
		'p'	=> $productid
	);
	$outputraw = $output = '';
	$r = new WP_Query( $query_args );
	
	if($r->have_posts()){ 
		while ( $r->have_posts() ){ $r->the_post(); setup_postdata( $r->post );
			global $product;
			ob_start();
			wc_get_template_part( 'content', 'quickview-product' );
			$outputraw = ob_get_contents();
			ob_end_clean();
		}
	}
	$output = preg_replace( array('/\s{2,}/', '/[\t\n]/'), ' ', $outputraw );
	echo sprintf( '%s', $output );
	exit();
}
/*
** Custom Login ajax
*/
add_action('wp_ajax_emarket_custom_login_user', 'emarket_custom_login_user_callback' );
add_action('wp_ajax_nopriv_emarket_custom_login_user', 'emarket_custom_login_user_callback' );
function emarket_custom_login_user_callback(){
	// First check the nonce, if it fails the function will break

	// Nonce is checked, get the POST data and sign user on
	$info = array();
	$info['user_login'] = $_POST['username'];
	$info['user_password'] = $_POST['password'];
	$info['remember'] = true;

	$user_signon = wp_signon( $info, false );
	if ( is_wp_error($user_signon) ){
		echo json_encode(array('loggedin'=>false, 'message'=> $user_signon->get_error_message()));
	} else {
		$redirect_url = get_permalink( get_option( 'woocommerce_myaccount_page_id' ) );
		$user_by 	  = ( is_email( $info['user_login'] ) ) ? 'email' : 'login';
		$user 		  = get_user_by( $user_by, $info['user_login'] );
		wp_set_current_user( $user->ID, $info['user_login'] );         
		wp_set_auth_cookie( $user->ID, TRUE );
		$user_role 	  = ( is_array( $user->roles ) ) ? $user->roles : array() ;
		if( in_array( 'vendor', $user_role ) ){
			$vendor_option = get_option( 'wc_prd_vendor_options' );
			$vendor_page   = ( array_key_exists( 'vendor_dashboard_page', $vendor_option ) ) ? $vendor_option['vendor_dashboard_page'] : get_option( 'woocommerce_myaccount_page_id' );
			$redirect_url = get_permalink( $vendor_page );
		}
		elseif( in_array( 'seller', $user_role ) ){
			$vendor_option = get_option( 'dokan_pages' );
			$vendor_page   = ( array_key_exists( 'dashboard', $vendor_option ) ) ? $vendor_option['dashboard'] : get_option( 'woocommerce_myaccount_page_id' );
			$redirect_url = get_permalink( $vendor_page );
		}
		elseif( in_array( 'dc_vendor', $user_role ) ){
			$vendor_option = get_option( 'wcmp_vendor_general_settings_name' );
			$vendor_page   = ( array_key_exists( 'wcmp_vendor', $vendor_option ) ) ? $vendor_option['wcmp_vendor'] : get_option( 'woocommerce_myaccount_page_id' );
			$redirect_url = get_permalink( $vendor_page );
		}
		elseif( in_array( 'wcfm_vendor', $user_role ) ){
			$vendor_option = get_option( 'wcfm_page_options' );
			$vendor_page   = ( array_key_exists( 'wc_frontend_manager_page_id', $vendor_option ) ) ? $vendor_option['wc_frontend_manager_page_id'] : get_option( 'woocommerce_myaccount_page_id' );
			$redirect_url = get_permalink( $vendor_page );
		}
		echo json_encode(array('loggedin'=>true, 'message'=>esc_html__('Login Successful, redirecting...', 'emarket'), 'redirect' => esc_url( $redirect_url ) ));
	}

	die();
}


/*
** Add Label New and SoldOut
*/
if( !function_exists( 'sw_label_new' ) ){
	function sw_label_new(){
		global $product;
		$availability = $product->get_availability();
		
		$html = '';
		$soldout = ( emarket_options()->getCpanelValue( 'product_soldout' ) ) ? emarket_options()->getCpanelValue( 'product_soldout' ) : 0;
		$newtime = ( get_post_meta( $product->get_id(), 'newproduct', true ) != '' && get_post_meta( $product->get_id(), 'newproduct', true ) ) ? get_post_meta( $product->get_id(), 'newproduct', true ) : emarket_options()->getCpanelValue( 'newproduct_time' );
		$product_date = get_the_date( 'Y-m-d', $product->get_id() );
		$newdate = strtotime( $product_date ) + intval( $newtime ) * 24 * 3600;
		if( ( isset( $availability['class'] ) && $availability['class'] != 'in-stock' ) && $soldout ) :
			$html .= '<span class="sw-outstock">'. esc_html__( 'Out Stock', 'emarket' ) .'</span>';		
		else:
			if( $newtime != '' && $newdate > time() ) :
				$html .= '<span class="sw-newlabel">'. esc_html__( 'New', 'emarket' ) .'</span>';			
			endif;
		endif;
		echo apply_filters( 'sw_label_new', $html );
	}
}


/*
** Custom color for product category
*/
add_action( 'product_cat_add_form_fields', 'emarket_product_cat_field_add', 10 );
add_action( 'product_cat_edit_form_fields', 'emarket_product_cat_field_edit', 10, 2 );
add_action( 'created_term', 'emarket_product_cat_field_save', 10, 2 );	
add_action( 'edit_terms', 'emarket_product_cat_field_save', 10, 2 );

/* Enqueue Admin js */
add_action( 'admin_enqueue_scripts', 'emarket_cat_color_script' );	

function emarket_product_cat_field_add() {
	?>
<div class="form-field custom-picker">
    <label for="ets_cat_color"><?php _e( 'Category color', 'emarket' ); ?></label>
    <input name="ets_cat_color" id="ets_cat_color" type="text" value="" size="40" class="category-colorpicker" />
</div>
<?php
	add_action('admin_enqueue_scripts', 'emarket_cat_color_script');
}

function emarket_product_cat_field_edit( $term ) {

	$ets_cat_color = get_term_meta( $term->term_id, 'ets_cat_color', true );

	?>
<tr class="form-field custom-picker custom-picker-edit">
    <th scope="row" valign="top"><label for="ets_cat_color"><?php _e( 'Category color', 'emarket' ); ?></label></th>
    <td>
        <input name="ets_cat_color" id="ets_cat_color" type="text" value="<?php echo esc_attr( $ets_cat_color ) ?>"
            size="40" class="category-colorpicker" />
    </td>
</tr>
<?php
}

/*
** Custom color for blog category
*/
add_action( 'category_add_form_fields', 'emarket_blog_cat_field_add', 10 );
add_action( 'category_edit_form_fields', 'emarket_blog_cat_field_edit', 10, 2 );
add_action( 'created_term', 'emarket_blog_cat_field_save', 10, 2 );	
add_action( 'edit_terms', 'emarket_blog_cat_field_save', 10, 2 );

/* Enqueue Admin js */
add_action( 'admin_enqueue_scripts', 'emarket_cat_color_script' );	

function emarket_blog_cat_field_add() {
	?>
<div class="form-field custom-picker">
    <label for="ets_cat_color2"><?php _e( 'Category color', 'emarket' ); ?></label>
    <input name="ets_cat_color2" id="ets_cat_color2" type="text" value="" size="40" class="category-colorpicker" />
</div>
<?php
	add_action('admin_enqueue_scripts', 'emarket_cat_color_script');
}

function emarket_blog_cat_field_edit( $term ) {

	$ets_cat_color2 = get_term_meta( $term->term_id, 'ets_cat_color2', true );

	?>
<tr class="form-field custom-picker custom-picker-edit">
    <th scope="row" valign="top"><label for="ets_cat_color2"><?php _e( 'Category color', 'emarket' ); ?></label></th>
    <td>
        <input name="ets_cat_color2" id="ets_cat_color2" type="text" value="<?php echo esc_attr( $ets_cat_color2 ) ?>"
            size="40" class="category-colorpicker" />
    </td>
</tr>
<?php
}

/** Save Custom Field Of Category Form */
function emarket_blog_cat_field_save( $term_id, $tt_id = '', $taxonomy = '', $prev_value = '' ) {
	if ( isset( $_POST['ets_cat_color2'] ) ) {			
		$term_value = esc_attr( $_POST['ets_cat_color2'] );
		update_term_meta( $term_id, 'ets_cat_color2', $term_value, $prev_value );
	}
}

/** Save Custom Field Of Category Form */
function emarket_product_cat_field_save( $term_id, $tt_id = '', $taxonomy = '', $prev_value = '' ) {
	if ( isset( $_POST['ets_cat_color'] ) ) {			
		$term_value = esc_attr( $_POST['ets_cat_color'] );
		update_term_meta( $term_id, 'ets_cat_color', $term_value, $prev_value );
	}
}

function emarket_cat_color_script(){
	wp_enqueue_style( 'wp-color-picker' ); 
	wp_enqueue_script('category_color_picker_js', get_template_directory_uri() . '/js/admin/category_color_picker.js', array( 'wp-color-picker' ), false, true);
}

add_action( 'woocommerce_account_content', 'emarket_mydashboard_mobile', 9 );
function emarket_mydashboard_mobile(){
	$current_user = get_user_by( 'id', get_current_user_id() );
	if( emarket_mobile_check() ) : ?>
<p class="avatar-user">
    <?php
			 echo get_avatar( $current_user->ID, 155 );
		?>
</p>
<?php endif;
}

/**
 * Add custom sorting options (best saler)
 */
 
function bestsaler_get_catalog_ordering_args( $args ) {
  
  $orderby_value = isset( $_GET['orderby'] ) ? wc_clean( $_GET['orderby'] ) : apply_filters( 'woocommerce_default_catalog_orderby', get_option( 'woocommerce_default_catalog_orderby' ) );
    if ( 'best-sale' == $orderby_value ) {
 
      	$args['meta_key'] = 'total_sales';
        $args['orderby'] = 'meta_value_num';
        $args['order'] = 'desc';
 
    }
 
    return $args;
}
 
add_filter( 'woocommerce_get_catalog_ordering_args', 'bestsaler_get_catalog_ordering_args' );

function bestsaler_catalog_orderby( $catalog_orderby_options ) {
	$catalog_orderby_options['best-sale'] = __( 'Best Saler', 'emarket' );
 
	return $catalog_orderby_options;
}

add_filter( 'woocommerce_default_catalog_orderby_options', 'bestsaler_catalog_orderby' );
add_filter( 'woocommerce_catalog_orderby', 'bestsaler_catalog_orderby' );

/**
 * Add custom sorting options (most viewed)
 */
 
function mostviewd_get_catalog_ordering_args( $args ) {
  
  $orderby_value = isset( $_GET['orderby'] ) ? wc_clean( $_GET['orderby'] ) : apply_filters( 'woocommerce_default_catalog_orderby', get_option( 'woocommerce_default_catalog_orderby' ) );
    if ( 'most-viewed' == $orderby_value ) {
 
      	$args['meta_key'] = 'post_views_count';
        $args['orderby'] = 'meta_value_num';
        $args['order'] = 'desc';
 
    }
 
    return $args;
}
 
add_filter( 'woocommerce_get_catalog_ordering_args', 'mostviewd_get_catalog_ordering_args' );

function mostviewd_catalog_orderby( $catalog_orderby_options ) {
	$catalog_orderby_options['most-viewed'] = __( 'Most Viewed', 'emarket' );
 
	return $catalog_orderby_options;
}

add_filter( 'woocommerce_default_catalog_orderby_options', 'mostviewd_catalog_orderby' );
add_filter( 'woocommerce_catalog_orderby', 'mostviewd_catalog_orderby' );

// calculate and save discount amount on product save/edit

add_action('woocommerce_product_quick_edit_save', 'sv_woo_calc_my_discount_quickedit');
//add_action('save_post', 'sv_woo_calc_my_discount_edit', 10, 1);
function sv_woo_calc_my_discount_quickedit( $post ) {

	$_product = wc_get_product( $post );
	
	$regular = (float) $_product->get_regular_price();
	$sale = (float) $_product->get_sale_price();

	$discount = round( 100 - ( ( $sale/$regular ) * 100 ) );
	update_post_meta( $post, '_discount_amount', $discount );
}

function sv_woo_calc_my_discount_edit( $post ) {
	global $product;
	$regular = isset( $_POST['_regular_price'] ) ? (float) $_POST['_regular_price'] : 1;
	$sale = isset( $_POST['_sale_price'] ) ? (float) $_POST['_sale_price'] : 0;
	
	$discount = round( 100 - ( ( $sale/$regular ) * 100 ) );
	
	update_post_meta( $post, '_discount_amount', $discount );
}

/**
 * Add custom sorting options (discount)
 */
 
function discount_get_catalog_ordering_args( $args ) {
  
  $orderby_value = isset( $_GET['orderby'] ) ? wc_clean( $_GET['orderby'] ) : apply_filters( 'woocommerce_default_catalog_orderby', get_option( 'woocommerce_default_catalog_orderby' ) );
  
	if ( '_discount_amount' == $orderby_value ) {
		$args['meta_key'] = '_discount_amount';
		$args['orderby'] = 'meta_value_num';
		$args['meta_value'] = 0;
		$args['meta_compare'] = '>';
		$args['order'] = 'desc';
	}
 
    return $args;
}
 
add_filter( 'woocommerce_get_catalog_ordering_args', 'discount_get_catalog_ordering_args' );

function discount_catalog_orderby( $catalog_orderby_options ) {
	$catalog_orderby_options['_discount_amount'] = __( 'Discount: high to low', 'emarket' );
 
	return $catalog_orderby_options;
}

add_filter( 'woocommerce_default_catalog_orderby_options', 'discount_catalog_orderby' );
add_filter( 'woocommerce_catalog_orderby', 'discount_catalog_orderby' );

add_action( 'wp_ajax_sw_product_ajax_load_more', 'sw_product_ajax_load_more_callback' );
add_action( 'wp_ajax_nopriv_sw_product_ajax_load_more', 'sw_product_ajax_load_more_callback' );
function sw_product_ajax_load_more_callback(){ 
	$args = isset( $_POST['query'] ) ? array_map( 'esc_attr', $_POST['query'] ) : array();
	$args['post_type'] = isset( $args['post_type'] ) ? esc_attr( $args['post_type'] ) : 'product';
	$args['post_status'] = 'publish';
	$args['posts_per_page'] = isset( $_POST['posts_per_page'] ) ? $_POST['posts_per_page'] : 12;
	$args['offset'] = $args['posts_per_page'] * ( $_POST['page'] - 1 );
	$category = isset( $args['product_cat'] ) ? $args['product_cat'] : '';
	if( $category != '' ) :
		$args['tax_query'] = array(
			'taxonomy'	=> 'product_cat',
			'field'     => 'slug',
			'terms'     => $category
		);
	endif; 
	
	$wc_query = new WC_Query();
	$ordering = $wc_query->get_catalog_ordering_args();
	$args['orderby'] = $ordering['orderby'];
	$args['order'] = $ordering['order'];
	
	ob_start();
	
	$loop = new WP_Query( $args );
	if( $loop->have_posts() ): while( $loop->have_posts() ): $loop->the_post();
		if( emarket_mobile_check() ) {
			get_template_part( 'mlayouts/product', 'grid' );
		}else{
			 wc_get_template( 'content-product.php' );
		}	
	endwhile; endif; wp_reset_postdata();
	$data = ob_get_clean();
	wp_send_json_success( $data );
	exit;
}

add_filter( 'single_product_small_thumbnail_size', 'emarket_single_product_small_thumbnail_size', 25, 1 );
function emarket_single_product_small_thumbnail_size( $size ) {
    $size = 'thumbnail';
    return $size;
}

add_filter( 'add_to_cart_text', 'emarket_custom_single_add_to_cart_text' );                // < 2.1
add_filter( 'woocommerce_product_add_to_cart_text', 'emarket_custom_single_add_to_cart_text' );  // 2.1 +
add_filter( 'woocommerce_product_single_add_to_cart_text', 'emarket_custom_single_add_to_cart_text' );  // 2.1 +
  
function emarket_custom_single_add_to_cart_text() {
	$layout_ID = ( get_option( 'page_on_front' ) ) ? get_option( 'page_on_front' ) : 0;
	$home_layout = get_post_meta( $layout_ID, 'page_home_template', true );
	
	if( $home_layout == 'home-style40' ):
	
		return __( 'Order now', 'emarket' );
	else:	
		return __( 'Add to cart', 'emarket' );
	endif;
  
}

if ( defined( 'YITH_WCWL' ) && ! function_exists( 'yith_wcwl_get_items_count' ) ) {
  function yith_wcwl_get_items_count() {
    ob_start();
    ?>
      <a href="<?php echo esc_url( YITH_WCWL()->get_wishlist_url() ); ?>">
        <span class="yith-wcwl-items-count">
          <i class="yith-wcwl-icon icon icon-heart"></i>
			<span class="wishlist-right">
				<span class="count-wishlist"><?php echo esc_html( yith_wcwl_count_all_products() ); ?></span><span><?php echo esc_html__('Wishlist','emarket'); ?></span>
			</span>
        </span>
      </a>
    <?php
    return ob_get_clean();
  }

  add_shortcode( 'yith_wcwl_items_count', 'yith_wcwl_get_items_count' );
}

if ( defined( 'YITH_WCWL' ) && ! function_exists( 'yith_wcwl_ajax_update_count' ) ) {
  function yith_wcwl_ajax_update_count() {
    wp_send_json( array(
      'count' => yith_wcwl_count_all_products()
    ) );
  }

  add_action( 'wp_ajax_yith_wcwl_update_wishlist_count', 'yith_wcwl_ajax_update_count' );
  add_action( 'wp_ajax_nopriv_yith_wcwl_update_wishlist_count', 'yith_wcwl_ajax_update_count' );
}

if ( defined( 'YITH_WCWL' ) && ! function_exists( 'yith_wcwl_enqueue_custom_script' ) ) {
  function yith_wcwl_enqueue_custom_script() {
    wp_add_inline_script(
      'jquery-yith-wcwl',
      "
        jQuery( function( $ ) {
          $( document ).on( 'added_to_wishlist removed_from_wishlist', function() {
            $.get( yith_wcwl_l10n.ajax_url, {
              action: 'yith_wcwl_update_wishlist_count'
            }, function( data ) {
              $('.yith-wcwl-items-count').find('.count-wishlist').html( data.count );
            } );
          } );
        } );
      "
    );
  }

  add_action( 'wp_enqueue_scripts', 'yith_wcwl_enqueue_custom_script', 20 );
}

/**
 * Custom Hook Check Page
 */
 
add_action( 'woocommerce_before_checkout_form', 'woocommerce_before_checkout_form_open', 0 );
add_action( 'woocommerce_before_checkout_form', 'woocommerce_before_checkout_form_close', 10 );

function woocommerce_before_checkout_form_open(){
    echo '<div class="form-top-checkout"><div class="wrap-content">';
}

function woocommerce_before_checkout_form_close(){
    echo '</div></div>';
}

add_action( 'woocommerce_checkout_before_order_review_heading', 'woocommerce_before_checkout_form_open2', 0 );
add_action( 'woocommerce_checkout_after_order_review', 'woocommerce_before_checkout_form_close2', 10 );

function woocommerce_before_checkout_form_open2(){
    echo '<div class="order-right" id="order-right"><div class="wrap-content">';
}

function woocommerce_before_checkout_form_close2(){
    echo '</div></div>';
}

add_action( 'woocommerce_checkout_before_customer_details', 'woocommerce_before_checkout_custom_detail_open', 0 );
add_action( 'woocommerce_checkout_after_order_review', 'woocommerce_before_checkout_custom_detail_close', 20 );

function woocommerce_before_checkout_custom_detail_open(){
    echo '<div class="cart-wrapper">';
}

function woocommerce_before_checkout_custom_detail_close(){
    echo '</div>';
}

?>