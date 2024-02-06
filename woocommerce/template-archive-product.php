<?php 
/**
 * The Template for displaying product archives, including the main shop page which is a post type archive.
 *
 * Override this template by copying it to yourtheme/woocommerce/archive-product.php
 *
 * @author 		WooThemes
 * @package 	WooCommerce/Templates
 * @version     2.0.0
 */
 
	if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
	$shop_categories  = emarket_options()->getCpanelValue( 'product_categories' );
	$bg_shop_full  = emarket_options()->getCpanelValue( 'shop_modern' );

	$bg_attr = ( emarket_options()->getCpanelValue( 'bg_shop_bread' ) != '' ) ? 'style="background: url( '. esc_url( emarket_options()->getCpanelValue( 'bg_shop_bread' ) ) .' )"' : '#333';
	$class = '';
	$bg = '';
	if( $shop_categories && emarket_options() -> getCpanelValue('sidebar_product') == 'full' ){
		$class = 'style1';
		$bg = $bg_attr;
	}elseif( $bg_shop_full && emarket_options() -> getCpanelValue('sidebar_product') == 'full' ){
		$class = 'style2';
	}else{
		$class = '';
	}
?>
<?php get_header( $shop_categories); ?>


<?php if ( apply_filters( 'woocommerce_show_page_title', true ) ) : ?>
	<div class="emarket_breadcrumbs <?php echo $class;  ?>" <?php echo $bg; ?>>
		<div class="container">
			<?php if( ( $shop_categories && emarket_options() -> getCpanelValue('sidebar_product') == 'full' ) || ( $bg_shop_full && emarket_options() -> getCpanelValue('sidebar_product') == 'full' ) ){ ?>
			<h1 class="page-title"><?php woocommerce_page_title(); ?></h1>
			<?php } ?>
			<?php
				if (!is_front_page() ) {
					if (function_exists('emarket_breadcrumb')){
						emarket_breadcrumb('<div class="breadcrumbs theme-clearfix">', '</div>');
					} 
				} 
			?>
		</div>
		<?php if( $shop_categories && emarket_options() -> getCpanelValue('sidebar_product') == 'full' ){
			 if ( is_active_sidebar('shop-categories') ) {
			?>
			<div class="shop-categories-above">
				<div class="wrap-content">            
					<?php dynamic_sidebar('shop-categories'); ?>
				</div>
			</div>
			<?php
		} }?>
	</div>
<?php endif; ?>
<div class="container">
	<div class="row sidebar-row">
	<?php 	
		if ( is_active_sidebar('left-product') && emarket_sidebar_product() != 'right' && emarket_sidebar_product() != 'full' ):
			$emarket_left_span_class = 'col-lg-'.emarket_options()->getCpanelValue('sidebar_left_expand');
			$emarket_left_span_class .= ' col-md-'.emarket_options()->getCpanelValue('sidebar_left_expand_md');
			$emarket_left_span_class .= ' col-sm-'.emarket_options()->getCpanelValue('sidebar_left_expand_sm');
	?>
	<aside id="left" class="sidebar <?php echo esc_attr($emarket_left_span_class); ?>">
		<?php dynamic_sidebar('left-product'); ?>
	</aside>
	
	<?php endif; ?>
	
	<div id="contents" <?php emarket_content_product(); ?> role="main">
		<?php
			/**
			 * woocommerce_before_main_content hook
			 *
			 * @hooked woocommerce_output_content_wrapper - 10 (outputs opening divs for the content)
			 * @hooked woocommerce_breadcrumb - 20
			 */
			 global $post;
			do_action( 'woocommerce_before_main_content' );
		?>
		
		<!--  Shop Title -->
		<?php if( ( !$shop_categories && emarket_sidebar_product() != 'full' ) || ( !$bg_shop_full && emarket_sidebar_product() != 'full' ) ){ ?><h1 class="page-title"><?php woocommerce_page_title(); ?></h1><?php } ?>
		
		<!-- Description --> 
		<?php do_action( 'woocommerce_archive_description' ); ?>
		<div class="products-wrapper">	
					
			<?php if ( have_posts() ) : ?>
				<?php do_action('woocommerce_message'); ?>
				<ul class="product-categories-listing">
				<?php									
					if( sw_woocommerce_version_check( '3.3' ) ){
						echo apply_filters( 'emarket_custom_category', $html = '' );
					}else{
						woocommerce_product_subcategories(); 
					}
				?>
				</ul>
				<?php
					/**
					 * woocommerce_before_shop_loop hook
					 *
					 * @hooked woocommerce_result_count - 20
					 * @hooked woocommerce_catalog_ordering - 30
					 */
					do_action( 'woocommerce_before_shop_loop' );
				?>
				<?php woocommerce_product_loop_start(); ?>				
										
					<?php while ( have_posts() ) : the_post(); ?>
		
					<?php wc_get_template_part( 'content', 'product' ); ?>

					<?php endwhile; // end of the loop. ?>

				<?php woocommerce_product_loop_end(); ?>
				<div class="clear"></div>			
				<?php
					/**
					 * woocommerce_after_shop_loop hook
					 *
					 * @hooked woocommerce_pagination - 10
					 */
					do_action( 'woocommerce_after_shop_loop' );
				?>
			<?php elseif ( ! woocommerce_product_subcategories( array( 'before' => woocommerce_product_loop_start( false ), 'after' => woocommerce_product_loop_end( false ) ) ) ) : ?>

				<?php wc_get_template( 'loop/no-products-found.php' ); ?>

			<?php endif; ?>
		</div>
		<?php
			/**
			 * woocommerce_after_main_content hook
			 *
			 * @hooked woocommerce_output_content_wrapper_end - 10 (outputs closing divs for the content)
			 */
			do_action('woocommerce_after_main_content');
		?>
	</div>

	<?php if ( is_active_sidebar('right-product') && emarket_sidebar_product() != 'left' && emarket_sidebar_product() != 'full' ):
		$emarket_right_span_class = 'col-lg-'.emarket_options()->getCpanelValue('sidebar_right_expand');
		$emarket_right_span_class .= ' col-md-'.emarket_options()->getCpanelValue('sidebar_right_expand_md');
		$emarket_right_span_class .= ' col-sm-'.emarket_options()->getCpanelValue('sidebar_right_expand_sm');
	?>
	<aside id="right" class="sidebar <?php echo esc_attr($emarket_right_span_class); ?>">
		<?php dynamic_sidebar('right-product'); ?>
	</aside>
	<?php endif; ?>

	</div>
</div>
<?php get_footer(); ?>
