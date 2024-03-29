<?php
/**
 * Single Product tabs
 *
 * @author 		WooThemes
 * @package 	WooCommerce/Templates
 * @version     3.8.0
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

/**
 * Filter tabs and allow third parties to add their own
 *
 * Each tab is an array containing title, callback and priority.
 * @see woocommerce_default_product_tabs()
 */
$tabs = apply_filters( 'woocommerce_product_tabs', array() );	
if ( ! empty( $tabs ) ) : 
	global $post;
	$i = 0;
	$j = 0;
	if( in_array( emarket_options()->getCpanelValue( 'product_single_style' ), array( 'style2', 'style3' ) ) ){
?>	
	<div class="panel-group single-accordion" id="<?php echo esc_attr( 'accordion' . $post->ID ); ?>">
		<?php foreach ( $tabs as $key => $tab ) : ?>
			<div class="panel">
				<div class="panel-heading">
					<h4>
						<a class="accordion-toggle <?php echo esc_attr( ( $j == 0 ) ? '' : 'collapsed' ); ?>" data-toggle="collapse" data-parent="#<?php echo esc_attr( 'accordion' . $post->ID ); ?>" href="#tab-<?php echo $key ?>">
							<?php echo apply_filters( 'woocommerce_product_' . $key . '_tab_title', $tab['title'], $key ) ?>
						</a>
					</h4>
				</div>
				<div id="tab-<?php echo $key ?>" class="panel-collapse collapse <?php echo esc_attr( ( $j == 0 ) ? 'in' : '' ); ?>">
					<div class="content-body">
						<?php 
							if( isset( $tab['callback'] ) ){
								call_user_func( $tab['callback'], $key, $tab ); 
							}
						?>
					</div>
				</div>
			</div>
			<?php $j++; ?>
		<?php endforeach; ?>
	</div>
	<?php }else{ ?>		
		<div class="tabbable">
			<ul class="nav nav-tabs">
				<?php foreach ( $tabs as $key => $tab ) : ?>

					<li class="<?php echo $key ?>_tab <?php if( $i == 0 ){ echo 'active'; } ?>">
						<a href="#tab-<?php echo $key ?>" data-toggle="tab"><?php echo apply_filters( 'woocommerce_product_' . $key . '_tab_title', $tab['title'], $key ) ?></a>
					</li>
				<?php $i++; ?>
				<?php endforeach; ?>
			</ul>
			<div class="clear"></div>
			<div class=" tab-content">
				<?php echo emarket_options()->getCpanelValue( 'product_single_style' ) == 'style8' ? '<div class="close-tab"></div>' : ''; ?>
				<?php foreach ( $tabs as $key => $tab ) : ?>

					<div class="tab-pane <?php if( $j == 0 ){ echo 'active'; } ?>" id="tab-<?php echo $key ?>">
					<?php 
						if( isset( $tab['callback'] ) ){
							call_user_func( $tab['callback'], $key, $tab ); 
						}
					?>
					</div>
				<?php $j++; ?>
				<?php endforeach; ?>
			</div>
		</div>
	<?php } ?>
	<?php do_action( 'woocommerce_product_after_tabs' ); ?>
<?php endif; ?>

