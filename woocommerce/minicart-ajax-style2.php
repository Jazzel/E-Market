<?php 
if ( !class_exists( 'WooCommerce' ) ) { 
	return false;
}

$layout_style = ( isset( $layout_style ) ? $layout_style : get_option('layout_style') ); 
global $woocommerce; ?>
<div class="top-form top-form-minicart emarket-minicart2 <?php echo( (string)get_option('layout_style') ) ; ?> pull-right">
	<div class="minicart-title pull-right">
		<h3><?php esc_html_e('My Cart','emarket');?></h3>
		<p><?php echo '<span class="minicart-numbers">'.$woocommerce->cart->cart_contents_count.'</span>'; esc_html_e(' item(s)', 'emarket');?></p>
	</div>
	<div class="top-minicart-icon pull-right"></div>
	<div class="wrapp-minicart">
		<div class="minicart-padding">
			<div class="number-item"><?php echo esc_html__('There are ','emarket').'<span class="item">' .$woocommerce->cart->cart_contents_count.' item(s)</span>'.esc_html__(' in your cart','emarket');?></div>
			<ul class="minicart-content">
				<?php 
					foreach($woocommerce->cart->cart_contents as $cart_item_key => $cart_item): 
					$_product  = apply_filters( 'woocommerce_cart_item_product', $cart_item['data'], $cart_item, $cart_item_key );
					$product_name = ( sw_woocommerce_version_check( '3.0' ) ) ? $_product->get_name() : $_product->get_title();
				?>
					<li>
						<a href="<?php echo get_permalink($cart_item['product_id']); ?>" class="product-image">
							<?php echo ( $_product->get_image( 'thumbnail' ) ); ?>
						</a>
						<?php 	global $product, $post, $wpdb, $average; ?>
						<div class="detail-item">
							<div class="product-details"> 
								<h4><a class="title-item" href="<?php echo get_permalink($cart_item['product_id']); ?>"><?php echo esc_html( $product_name ); ?></a></h4>	  		
								<div class="product-price">
									<span class="price"><?php echo ($woocommerce->cart->get_product_subtotal($cart_item['data'], 1) ); ?></span>	      
									<div class="qty">
										<?php echo '<span class="qty-number">'.esc_html( $cart_item['quantity'] ).'</span>'; ?>
									</div>
								</div>
								<div class="product-action clearfix">
									<?php 
									echo apply_filters( 'woocommerce_cart_item_remove_link', sprintf( '<a href="%s" class="btn-remove" title="%s"><span class="fa fa-trash-o"></span>%s</a>', esc_url( wc_get_cart_remove_url( $cart_item_key ) ), esc_attr__( 'Remove this item', 'emarket' ), esc_html__( 'Remove', 'emarket' ) ), $cart_item_key ); ?>           
									<a class="btn-edit" href="<?php echo wc_get_cart_url(); ?>" title="<?php esc_attr_e( 'View your shopping cart', 'emarket'); ?>"><i class="fa fa-pencil"></i><span></span></a>    
								</div>
							</div>	
						</div>
					</li>
				<?php endforeach; ?>
			</ul>
			<div class="cart-checkout">
			    <div class="price-total">
				   <span class="label-price-total"><?php esc_html_e('Subtotal:', 'emarket'); ?></span>
				   <span class="price-total-w"><span class="price"><?php echo ( $woocommerce->cart->get_cart_subtotal() ); ?></span></span>			
				</div>
				<div class="cart-links clearfix">
					<div class="cart-link"><a href="<?php echo get_permalink(get_option('woocommerce_cart_page_id')); ?>" title="<?php esc_attr_e( 'Cart', 'emarket' ) ?>"><?php esc_html_e('View cart', 'emarket'); ?></a></div>
					<div class="checkout-link"><a href="<?php echo get_permalink(get_option('woocommerce_checkout_page_id')); ?>" title="<?php esc_attr_e( 'Check Out', 'emarket' ) ?>"><?php esc_html_e('Checkout', 'emarket'); ?></a></div>
				</div>
			</div>
		</div>
	</div>
</div>
<script>
(function($) {
	"use strict";	
	$(document).ready(function(){
		/*
		** Cart Canvas Click
		*/
		$('.top-form-minicart.cart_click').on('click', function(e){
			$(this).addClass('open');
			$('body').addClass('cart-open-mark');
		});
	});
	$(document).click(function(e) {			
		if( $('.top-form-minicart').hasClass( 'cart_click' ) ){
			var container = $( ".top-form-minicart.cart_click" );
			if ( typeof container != "undefined" && !container.is(e.target) && container.has(e.target).length === 0 && container.html().length > 0 ){
				$( ".top-form-minicart.cart_click" ).removeClass("open");
				$("body").removeClass("cart-open-mark");
			}
		}
	});
}(jQuery));
</script>