<?php 
$emarket_header_style = emarket_options()->getCpanelValue('header_style');
?>
<?php do_action( 'before' ); ?>
<?php if ( class_exists( 'WooCommerce' ) ) { ?>
<?php global $woocommerce; ?>
<div class="top-login8">
	<?php if ( ! is_user_logged_in() ) {  ?>
	<ul class="login">
		<li>
			<?php echo '<a class="login" href="javascript:void(0);" data-toggle="modal" data-target="#login_form"><span>'.__('Sign in', 'emarket').'</span></a>'; ?>	
			<a href="<?php echo get_permalink( get_option('woocommerce_myaccount_page_id') ); ?>" title="Register" class="btn-reg-popup"><span><?php _e( 'Register', 'emarket' ); ?></span></a>				
		</li>
	</ul>
	<?php } else{ ?>
	<div class="div-logined">
		<ul>
			<li>
				<?php 
				$user_id = get_current_user_id();
				$user_info = get_userdata( $user_id );	
				?>
				<a class="login" href="<?php echo wp_logout_url( home_url('/') ); ?>" title="<?php esc_attr_e( 'Sign out', 'emarket' ) ?>"><span><?php esc_html_e('Sign out', 'emarket'); ?></span></a>
				<ul class="dropdown-logined">
					<li><a class="user-info" href="<?php echo get_permalink( get_option( 'woocommerce_myaccount_page_id' ) ); ?>"><?php echo sprintf( esc_html__( '%s', 'emarket' ), $user_info->user_nicename ); ?></a></li>
					<li><a class="" href="<?php echo wp_logout_url( home_url('/') ); ?>" title="<?php esc_attr_e( 'Sign out', 'emarket' ) ?>"><span><?php esc_html_e('Sign out', 'emarket'); ?></span></a></li>
				</ul>
			</li>
		</ul>
	</div>
	<?php } ?>
</div>
<?php }