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
global $post, $product;
?>
<li <?php post_class( 'item' ); ?>>
	<div class="products-entry item-wrap clearfix">
		<div class="item-detail">
			<div class="item-img products-thumb">
				<a href="<?php the_permalink(); ?>" title="<?php the_title_attribute(); ?>">
					<div class="product-thumb-hover">
						<?php sw_label_sales(); ?>
						<?php the_post_thumbnail( 'shop_catalog' ); ?>
					</div>
				</a>
			</div>
			<div class="item-content products-content">
					<h4><a href="<?php echo get_permalink($post->ID) ?>" title="<?php the_title_attribute();?>"><?php sw_trim_words( get_the_title(),  emarket_options()->getCpanelValue( 'title_length' ) ); ?></a></h4>		
					<!-- rating -->
					<?php wc_get_template_part( 'loop/rating' ); ?>
					<!-- Price -->
					<?php if ( $price_html = $product->get_price_html() ) : ?>
						<span class="item-price"><?php echo $price_html; ?></span>
					<?php endif; ?>	
					<!-- Description -->
					<div class="item-description">
						<?php echo wp_trim_words( $post->post_excerpt, 15); ?>
					</div>
					<?php if( emarket_options()->getCpanelValue( 'mobile_addcart' ) ) : ?>
						<?php do_action( 'woocommerce_after_shop_loop_item' ); ?>
					<?php endif; ?>
			</div>
		</div>
	</div>
</li>