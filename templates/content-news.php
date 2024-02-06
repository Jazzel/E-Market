<?php 
	global $instance, $post;
	$format = get_post_format();
	$emarket_bclass = ( has_post_thumbnail() ) ? '' : 'no-thumb ';
	$emarket_bclass .= 'clearfix';
?>
	<div id="post-<?php the_ID();?>" <?php post_class( $emarket_bclass ); ?>>
		<div class="entry clearfix">
				<?php if( $format == '' ){?>
				<div class="entry-thumb">	
					<?php if ( has_post_thumbnail() ){ ?>
					<a class="entry-hover" href="<?php the_permalink(); ?>" title="<?php the_title_attribute(); ?>">
						<?php the_post_thumbnail('full'); ?>			
					</a>
					<span class="entry-comment"><?php echo get_post( $post->ID )->comment_count;?></span>
					<?php 
							$gameplay   = get_post_meta( $post->ID, '_gameplay', true );
							$gamestory   = get_post_meta( $post->ID, '_gamestory', true );
							$graphic   = get_post_meta( $post->ID, '_grapphic', true );
							$performance   = get_post_meta( $post->ID, '_performance', true );
							$point = (( intval($gameplay) + intval($gamestory) + intval($graphic) + intval($performance))/40)*10;
							if( $point > 0 ){
						?>
						
						<span class="reivew-score"><?php echo esc_attr( $point ); ?></span>
						
						<?php } ?>
					<?php } ?>			
				</div>
				<div class="entry-content">
					<div class="entry-cat">
							<?php 
								$categories = wp_get_post_categories( $post->ID );
								$category_color = '';													
								foreach($categories as $category){
									$category_color =  get_term_meta( $category, 'ets_cat_color2', true );
									if( $category_color ):
										echo '<a data-color="'.esc_attr( $category_color ).'"href="' . get_category_link($category) . '">' . get_cat_name($category) . '</a>';
									endif;
								}
							?>
					</div>
					<div class="entry-title">
						<h4><a href="<?php echo get_permalink($post->ID)?>"><?php emarket_trim_words( $post->post_title ); ?></a></h4>
					</div>
					<div class="entry-meta">
						<span class="entry-author"><?php echo get_avatar( $post->post_author , 26 ); ?><?php the_author_posts_link(); ?></span>
						<span class="entry-date"><a href="<?php echo get_permalink($post->ID)?>"><?php echo get_the_date( '', $post->ID );?></a></span>
					</div>
					<div class="entry-summary">
						<?php 												
							if ( preg_match('/<!--more(.*?)?-->/', $post->post_content, $matches) ) {
								echo wp_trim_words($post->post_content, 40, '...');
							} else {
								the_content('...');
							}		
						?>	
					</div>
				</div>
			<?php } else { ?>
			<div class="entry-thumb">	
						<?php if( $format == 'video' || $format == 'audio' ){ ?>	
							<?php echo ( $format == 'video' ) ? '<div class="video-wrapper">'. emarket_get_entry_content_asset($post->ID) . '</div>' : emarket_get_entry_content_asset($post->ID); ?>										
						<?php } ?>
						<?php if( $format == 'image' ){?>
							<div class="entry-thumb-content">
								<a class="entry-hover" href="<?php the_permalink(); ?>" title="<?php the_title_attribute(); ?>">
									<?php the_post_thumbnail('emarket_detail_thumb');?>				
								</a>	
							</div>
						<?php } ?>
						<?php if( $format == 'gallery' ) { 
							if(preg_match_all('/\[gallery(.*?)?\]/', get_post($instance['post_id'])->post_content, $matches)){
								$attrs = array();
								if (count($matches[1])>0){
									foreach ($matches[1] as $m){
										$attrs[] = shortcode_parse_atts($m);
									}
								}
								$ids = '';
								if (count($attrs)> 0){
									foreach ($attrs as $attr){
										if (is_array($attr) && array_key_exists('ids', $attr)){
											$ids = $attr['ids'];
											break;
										}
									}
								}
							?>
								<div id="gallery_slider_<?php echo $post->ID; ?>" class="carousel slide gallery-slider" data-interval="0">	
									<div class="carousel-inner">
										<?php
											$ids = explode(',', $ids);						
											foreach ( $ids as $i => $id ){ ?>
												<div class="item<?php echo ( $i== 0 ) ? ' active' : '';  ?>">			
														<?php echo wp_get_attachment_image($id, 'full'); ?>
												</div>
											<?php }	?>
									</div>
									<a href="#gallery_slider_<?php echo $post->ID; ?>" class="left carousel-control" data-slide="prev"><?php esc_html_e( 'Prev', 'emarket' ) ?></a>
									<a href="#gallery_slider_<?php echo $post->ID; ?>" class="right carousel-control" data-slide="next"><?php esc_html_e( 'Next', 'emarket' ) ?></a>
								</div>
							<?php }	?>							
						<?php } ?>
				</div>
				<div class="entry-content style2">
					<div class="entry-cat">
							<?php 
								$categories = wp_get_post_categories( $post->ID );
								$category_color = '';													
								foreach($categories as $category){
									$category_color =  get_term_meta( $category, 'ets_cat_color2', true );
									if( $category_color ):
										echo '<a data-color="'.esc_attr( $category_color ).'"href="' . get_category_link($category) . '">' . get_cat_name($category) . '</a>';
									endif;
								}
							?>
					</div>
					<div class="entry-title">
						<h4><a href="<?php echo get_permalink($post->ID)?>"><?php emarket_trim_words( $post->post_title ); ?></a></h4>
					</div>
					<div class="entry-meta">
						<span class="entry-author"><?php echo get_avatar( $post->post_author , 26 ); ?><?php the_author_posts_link(); ?></span>
						<span class="entry-date"><a href="<?php echo get_permalink($post->ID)?>"><?php echo get_the_date( '', $post->ID );?></a></span>
					</div>
					<div class="entry-summary">
						<?php the_content( '...' ); ?>
					</div>
				</div>
			<?php } ?>
		</div>
	</div>