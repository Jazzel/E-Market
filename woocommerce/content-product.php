<?php
/**
 * The template for displaying product content within loops.
 *
 * Override this template by copying it to yourtheme/woocommerce/content-product.php
 *
 * @author 		WooThemes
 * @package 	WooCommerce/Templates
 * @version    3.6.0
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

global $product, $post;
// Ensure visibility
if ( ! $product || ! $product->is_visible() ) {
	return;
}
$shop_button_style = emarket_options()->getCpanelValue('shop_button_style');
$class = '';
if ( $shop_button_style == "style28"){
	$class = 'item-wrap28';
}else if ( $shop_button_style == "style29"){
	$class = 'item-wrap29';
}else if ( $shop_button_style == "style30"){
	$class = 'item-wrap30';
}else if ( $shop_button_style == "style31"){
	$class = 'item-wrap31';
}else{
	$class = 'item-wrap';
}

$terms_id = get_the_terms( $post->ID, 'product_cat' );
$term_str = '';

foreach( $terms_id as $key => $value ) :
	$term_str .= '<a href="'. get_term_link( $value->term_id, 'product_cat' ) .'">'. esc_html( $value->name ) .'</a>';
endforeach;
?>
<li <?php post_class( emarket_product_attribute() ); ?> >
	<div class="products-entry <?php echo ( $shop_button_style && $shop_button_style != 'default' ) ? $class : 'item-wrap'; ?> clearfix">
		<?php if ( $shop_button_style == "style28") : ?>
			<div class="item-detail">										
				<div class="item-img products-thumb">
					<a href="<?php the_permalink(); ?>" title="<?php the_title_attribute(); ?>">
						<?php 
						$id = get_the_ID();
						if ( has_post_thumbnail() ){
							echo get_the_post_thumbnail( $post->ID, 'shop_catalog', array( 'alt' => $post->post_title ) ) ? get_the_post_thumbnail( $post->ID, 'shop_catalog', array( 'alt' => $post->post_title ) ): '<img src="'.get_template_directory_uri().'/assets/img/placeholder/'.'large'.'.png" alt="No thumb">';		
						}else{
							echo '<img src="'.get_template_directory_uri().'/assets/img/placeholder/'.'large'.'.png" alt="No thumb">';
						}
						?>
					</a>
					<div class="item-button">
						<?php
						if ( class_exists( 'YITH_WCWL' ) ){
						echo do_shortcode( "[yith_wcwl_add_to_wishlist]" );
						} ?>
						<?php if ( class_exists( 'YITH_WOOCOMPARE' ) ){ 
						?>
						<a href="javascript:void(0)" class="compare button"  title="<?php esc_html_e( 'Add to Compare', 'sw_woocommerce' ) ?>" data-product_id="<?php echo esc_attr($post->ID); ?>" rel="nofollow"> <?php esc_html('compare','sw-woocomerce'); ?></a>
						<?php } ?>
						<?php echo emarket_quickview(); ?>
					</div>
					<?php do_action( 'sw_woocommerce_custom_action' ); ?>
				</div>									
				<div class="item-content">	
					<?php do_action( 'woocommerce_shop_loop_item_title' ); ?>		
					<div class="item-description"><?php echo wp_trim_words( $post->post_excerpt, 33 ); ?></div>
						<!-- price -->
						<?php if ( $price_html = $product->get_price_html() ){?>
						<div class="item-price">
							<span>
								<?php echo $price_html; ?>
							</span>
						</div>
						<?php } ?>	
						<?php woocommerce_template_loop_add_to_cart(); ?>
				</div>								
			</div>
		<?php elseif ( $shop_button_style == "style29") : ?>
				<div class="item-detail">										
					<div class="item-img products-thumb">			
						<?php do_action( 'woocommerce_before_shop_loop_item_title' ); ?>
					</div>										
					<div class="item-content">		
						<div class="item-button">
							<?php woocommerce_template_loop_add_to_cart(); ?>
							<?php
							if ( class_exists( 'YITH_WCWL' ) ){
							echo do_shortcode( "[yith_wcwl_add_to_wishlist]" );
							} ?>
							<?php if ( class_exists( 'YITH_WOOCOMPARE' ) ){ 
							?>
							<a href="javascript:void(0)" class="compare button"  title="<?php esc_html_e( 'Add to Compare', 'sw_woocommerce' ) ?>" data-product_id="<?php echo esc_attr($post->ID); ?>" rel="nofollow"> <?php esc_html('compare','sw-woocomerce'); ?></a>
							<?php } ?>
							<?php echo emarket_quickview(); ?>
						</div>
						<?php do_action( 'woocommerce_shop_loop_item_title' ); ?>	
						
						<!-- rating  -->
						<?php 
						$rating_count = $product->get_rating_count();
						$review_count = $product->get_review_count();
						$average      = $product->get_average_rating();
						?>
						<?php if (  wc_review_ratings_enabled() ) { ?>
						<div class="reviews-content">
							<div class="star"><?php echo ( $average > 0 ) ?'<span style="width:'. ( $average*17 ).'px"></span>' : ''; ?></div>
						</div>	
						<?php } ?>
						<!-- end rating  -->
						<div class="item-description"><?php echo wp_trim_words( $post->post_excerpt, 15 ); ?></div>
						<!-- price -->
						<?php if ( $price_html = $product->get_price_html() ){?>
						<div class="item-price">
							<span>
								<?php echo $price_html; ?>
							</span>
						</div>
						<?php } ?>
					</div>								
				</div>
			<?php elseif ( $shop_button_style == "style30") : ?>
					<div class="item-detail">										
						<div class="item-img products-thumb">
							<?php do_action( 'woocommerce_before_shop_loop_item_title' ); ?>
							<div class="item-button">
								<?php
								if ( class_exists( 'YITH_WCWL' ) ){
								echo do_shortcode( "[yith_wcwl_add_to_wishlist]" );
								} ?>
								<?php if ( class_exists( 'YITH_WOOCOMPARE' ) ){ 
								?>
								<a href="javascript:void(0)" class="compare button"  title="<?php esc_html_e( 'Add to Compare', 'sw_woocommerce' ) ?>" data-product_id="<?php echo esc_attr($post->ID); ?>" rel="nofollow"> <?php esc_html('compare','sw-woocomerce'); ?></a>
								<?php } ?>
								<?php echo emarket_quickview(); ?>
							</div>
						</div>									
						<div class="item-content">	
							<div class="item-categories">
								<?php echo  $term_str; ?>
							</div>
							<h4><a href="<?php the_permalink() ?>" title="<?php the_title_attribute(); ?>"><?php emarket_trim_words( get_the_title() ); ?></a></h4>						
							<!-- rating  -->
							<?php 
							$rating_count = $product->get_rating_count();
							$review_count = $product->get_review_count();
							$average      = $product->get_average_rating();
							?>
							<?php if (  wc_review_ratings_enabled() ) { ?>
							<div class="reviews-content">
								<div class="star"><?php echo ( $average > 0 ) ?'<span style="width:'. ( $average*16 ).'px"></span>' : ''; ?></div>
							</div>	
							<?php } ?>
							<!-- end rating  -->	
								<!-- price -->
								<?php if ( $price_html = $product->get_price_html() ){?>
								<div class="item-price">
									<span>
										<?php echo $price_html; ?>
									</span>
								</div>
								<?php } ?>	
								<?php do_action( 'sw_woocommerce_custom_action' ); ?>
								<div class="item-description"><?php echo wp_trim_words( $post->post_excerpt, 30 ); ?></div>
								<?php woocommerce_template_loop_add_to_cart(); ?>
						</div>
					</div>
			<?php elseif ( $shop_button_style == "style31") : ?>
						<div class="item-detail">										
							<div class="item-img products-thumb">
								<?php do_action( 'woocommerce_before_shop_loop_item_title' ); ?>
								<div class="item-button">
									<?php woocommerce_template_loop_add_to_cart(); ?>
									<?php
									if ( class_exists( 'YITH_WCWL' ) ){
										echo do_shortcode( "[yith_wcwl_add_to_wishlist]" );
									} ?>
									<?php if ( class_exists( 'YITH_WOOCOMPARE' ) ){ 
									?>
									<a href="javascript:void(0)" class="compare button"  title="<?php esc_html_e( 'Add to Compare', 'sw_woocommerce' ) ?>" data-product_id="<?php echo esc_attr($post->ID); ?>" rel="nofollow"> <?php esc_html('compare','sw-woocomerce'); ?></a>
									<?php } ?>
									<?php echo emarket_quickview(); ?>
								</div>
								<?php do_action( 'sw_woocommerce_custom_action' ); ?>
							</div>									
							<div class="item-content">	
								<h4><a href="<?php the_permalink() ?>" title="<?php the_title_attribute(); ?>"><?php emarket_trim_words( get_the_title() ); ?></a></h4>						
								<div class="item-description"><?php echo wp_trim_words( $post->post_excerpt, 30 ); ?></div>		
									<!-- price -->
									<?php if ( $price_html = $product->get_price_html() ){?>
									<div class="item-price">
										<span>
											<?php echo $price_html; ?>
										</span>
									</div>
								<?php } ?>	
							</div>
						</div>
		<?php else :?>
			<div class="item-detail">
				<div class="item-img products-thumb">
					<?php
						/**
						 * woocommerce_before_shop_loop_item_title hook
						 *
						 * @hooked woocommerce_show_product_loop_sale_flash - 10
						 * @hooked woocommerce_template_loop_product_thumbnail - 10
						 */
						do_action( 'woocommerce_before_shop_loop_item_title' );					
					?>
					<?php
						/**
						 * woocommerce_after_shop_loop_item hook
						 *
						 * @hooked woocommerce_template_loop_add_to_cart - 10
						 */
						do_action( 'woocommerce_after_shop_loop_item' );
					?>
				</div>
				<div class="item-content products-content">
					<?php
					/**
					 * woocommerce_shop_loop_item_title hook
					 *
					 * @hooked woocommerce_template_loop_product_title - 10
					 */
					do_action( 'woocommerce_shop_loop_item_title' );

					/**
					 * woocommerce_after_shop_loop_item_title hook
					 *
					 * 
					 * @hooked woocommerce_template_loop_price - 10
					 * @hooked woocommerce_template_loop_rating - 15
					 */
					do_action( 'woocommerce_after_shop_loop_item_title' );
					
					/**
					 * woocommerce_after_shop_loop_item hook
					 *
					 * @hooked woocommerce_template_loop_add_to_cart - 10
					 */
					do_action( 'woocommerce_after_shop_loop_item' );
					
					do_action( 'sw_woocommerce_custom_action' );
						
					?>
				</div>
			</div>

		<?php endif; ?>
	</div>
</li>