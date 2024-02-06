<?php
/**
 * Loop Rating
 *
 * @author 		WooThemes
 * @package 	WooCommerce/Templates
 * @version     3.6.0
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

global $product;

if ( get_option( 'woocommerce_enable_review_rating' ) === 'no' ) {
	return;
}

if ( ! wc_review_ratings_enabled() ) {
	return;
}

	$rating_count = $product->get_rating_count();
	$review_count = $product->get_review_count();
	$average      = $product->get_average_rating();
?>

<div class="reviews-content">
	<div class="star">
		<?php echo sprintf( '<span style="width: %dpx"></span>', $average*16 ); ?>		
	</div>
</div>
