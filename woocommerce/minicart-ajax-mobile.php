<?php 
if ( !class_exists( 'WooCommerce' ) ) { 
	return false;
}
global $woocommerce; ?>
<div class="emarket-minicart-mobile">
	<a href="<?php echo get_permalink(get_option('woocommerce_cart_page_id')); ?>" title="<?php esc_attr_e( 'Cart', 'emarket' ) ?>">
		<span class="icon-menu"></span>
		<?php echo '<span class="minicart-number">'.$woocommerce->cart->cart_contents_count.'</span>'; ?>
		<span class="menu-text"><?php echo esc_html__( 'Cart', 'emarket' ); ?></span>
	</a>
</div>