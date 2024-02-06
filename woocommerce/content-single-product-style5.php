<?php
/**
 * The template for displaying product content in the single-product.php template
 *
 * Override this template by copying it to yourtheme/woocommerce/content-single-product.php
 *
 * @author 		WooThemes
 * @package 	WooCommerce/Templates
 * @version    3.0.0
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
?>

<?php
	/**
	 * woocommerce_before_single_product hook
	 *
	 * @hooked woocommerce_show_messages - 10
	 */
	 do_action( 'woocommerce_before_single_product' );
	global $product;
	if ( post_password_required() ) {
		echo get_the_password_form();
		return;
	}
?>
<div class="container">
	<div class="row sidebar-row">
		<div id="contents-detail" class="col-lg-12" role="main">
			<?php
				/**
				 * woocommerce_before_main_content hook
				 *
				 * @hooked woocommerce_output_content_wrapper - 10 (outputs opening divs for the content)
				 * @hooked woocommerce_breadcrumb - 20
				 */
				do_action('woocommerce_before_main_content');
			?>
			<div class="single-product clearfix">
			
				<?php while ( have_posts() ) : the_post(); ?>
						<?php do_action( 'woocommerce_before_single_product' ); ?>
						<div id="product-<?php the_ID(); ?>" <?php post_class(); ?>>
							<div class="product_detail row">
								<div id="single-left" class="single-left col-lg-6 col-md-6 col-sm-12 col-xs-12 clear_xs">							
									<div class="woocommerce-product-gallery woocommerce-product-gallery--with-images images product-images">
										<figure class="woocommerce-product-gallery__wrapper">
										<?php
											$attachments 	     	 =	 array();
											$post_thumbnail_id	 	 = get_post_thumbnail_id( $post->ID );
											$attachments = ( sw_woocommerce_version_check( '3.0' ) ) ? $product->get_gallery_image_ids() : $product->get_gallery_attachment_ids();
											if( has_post_thumbnail() ){ 				
												$image_id 	 = get_post_thumbnail_id();
												array_unshift( $attachments, $image_id );
												$attachments = array_unique( $attachments );
											}
											if( count( $attachments ) ){
												foreach ( $attachments as $key => $attachment ) { 
												if( $key > 6 ){
													break;
												}
												$full_size_image  = wp_get_attachment_image_src( $attachment, 'full' );
												$thumbnail_post   = get_post( $attachment );

												$attributes = array(
													'class' => 'wp-post-image',
													'title'                   => get_post_field( 'post_title', $post_thumbnail_id ),
													'data-caption'            => get_post_field( 'post_excerpt', $post_thumbnail_id ),
													'data-src'                => $full_size_image[0],
													'data-large_image'        => $full_size_image[0],
													'data-large_image_width'  => $full_size_image[1],
													'data-large_image_height' => $full_size_image[2],
												);
											?>
											<div data-thumb="<?php echo wp_get_attachment_image_url( $attachment, 'shop_thumbnail' ) ?>" class="woocommerce-product-gallery__image style-gall">	
												<a href="<?php echo wp_get_attachment_url( $attachment ) ?>"><?php echo wp_get_attachment_image( $attachment, 'shop_single', false, $attributes ); ?></a>
											</div>
											<?php }
											}
										?>
										</figure>
									</div>						
								</div>
								<div id="single-right" class="single-right col-lg-6 col-md-6 col-sm-12 col-xs-12 clear_xs">
									<div class="content_product_detail">
										<!-- woocommerce_template_single_title - 5 -->
										<!-- woocommerce_template_single_rating - 10 -->
										<!-- woocommerce_template_single_price - 20 -->
										<!-- woocommerce_template_single_excerpt - 30 -->
										<!-- woocommerce_template_single_add_to_cart 40 -->
										<?php
											/**
											 * woocommerce_single_product_summary hook
											 *
											 * @hooked woocommerce_template_single_title - 5
											 * @hooked woocommerce_template_single_price - 10
											 * @hooked woocommerce_template_single_excerpt - 20
											 * @hooked woocommerce_template_single_add_to_cart - 30
											 * @hooked woocommerce_template_single_meta - 40
											 * @hooked woocommerce_template_single_sharing - 50
											 */
											do_action( 'woocommerce_single_product_summary' );
										?>				
									</div>
								</div>
							</div>
						</div>	
						
						<div class="tabs clearfix">
							<?php
								/**
								 * woocommerce_after_single_product_summary hook
								 *
								 * @hooked woocommerce_output_product_data_tabs - 10
								 * @hooked woocommerce_output_related_products - 20
								 */
								do_action( 'woocommerce_after_single_product_summary' );
							?>
						</div>
					<?php if (is_active_sidebar('bottom-detail-product')) { ?>
						<div class="bottom-single-product theme-clearfix">
							<?php dynamic_sidebar('bottom-detail-product'); ?>
						</div>
					<?php } ?>
						
					<?php do_action( 'woocommerce_after_single_product' ); ?>
				<?php endwhile; // end of the loop. ?>
			
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
	</div>
</div>