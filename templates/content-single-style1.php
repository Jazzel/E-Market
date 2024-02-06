<?php get_header(); ?>
<?php

	$previous = get_previous_post();
	$next = get_next_post();
	$emarket_light_layout 		= emarket_options()->getCpanelValue( 'layout_light' );
?>
<div class="emarket_breadcrumbs">
	<div class="container">
		<?php
			if (!is_front_page() ) {
				if (function_exists('emarket_breadcrumb')){
					emarket_breadcrumb('<div class="breadcrumbs theme-clearfix">', '</div>');
				} 
			} 
		?>
	</div>
</div>

<div class="container">
	<div class="row sidebar-row">	
	
		<div class="single main single-news-style1 clearfix" >
			<?php while (have_posts()) : the_post();
				global $post;
				
				$pros   = get_post_meta( $post->ID, '_review_pros', true );
				$cons   = get_post_meta( $post->ID, '_review_cons', true );
				$gameplay   = get_post_meta( $post->ID, '_gameplay', true );
				$gamestory   = get_post_meta( $post->ID, '_gamestory', true );
				$graphic   = get_post_meta( $post->ID, '_grapphic', true );
				$performance   = get_post_meta( $post->ID, '_performance', true );
				$point = (( intval($gameplay) + intval($gamestory) + intval($graphic) + intval($performance))/40)*10;
				
				$name_game   = get_post_meta( $post->ID, '_name_game', true );
				$deverloper   = get_post_meta( $post->ID, '_deverloper', true );
				$publisher   = get_post_meta( $post->ID, '_publisher', true );
				$release   = get_post_meta( $post->ID, '_release', true );
				$platforms   = get_post_meta( $post->ID, '_platforms', true );
			?>
				
			<div <?php post_class(); ?>>
				<?php $pfm = get_post_format();?>
				<div class="entry-wrap">
					<div class="entry-top">
						<div class="entry-cat">
						<?php
							$categories = wp_get_post_categories( $post->ID );
							$category_color = '';													
							foreach($categories as $category){
								$category_color =  get_term_meta( $category, 'ets_cat_color2', true );
							  echo '<a data-color="'.esc_attr( $category_color ).'"href="' . get_category_link($category) . '">' . get_cat_name($category) . '</a>';
							}
						?>
						</div>
						<h1 class="entry-title clearfix"><?php the_title(); ?></h1>
						
						<?php if( $pfm == '' || $pfm == 'image' ){?>
							<?php if( has_post_thumbnail() ){ ?>
								<div class="entry-thumb single-thumb">
									<?php the_post_thumbnail('emarket_detail_thumb2');; ?>
								</div>
							<?php }?>
						<?php } ?>
						
						<div class="entry-meta clearfix">
							<div class="meta-left pull-left">
								<?php echo get_avatar( $post->post_author , 40 ); ?>
								<div class="wrap">
									<span class="entry-author"><?php esc_html_e('by ', 'emarket'); ?><?php the_author_posts_link(); ?></span>
									<span class="entry-time"><a href="<?php echo get_permalink($post->ID)?>"><?php echo get_the_date( '', $post->ID );?></a></span>
								</div>
							</div>
							<div class="meta-right pull-right">
								<div class="entry-comment">
									<a href="<?php comments_link(); ?>">
										<i class="fa fa-comment-o"></i>
										<?php echo $post->comment_count; ?>
									</a>
								</div>
							</div>
						</div>
					</div>
					<div class="entry-bottom">
						<div class="wrap-content">
							<div class="entry-content clearfix">
								<div class="entry-summary single-content">
									<?php the_content(); ?>
									
									<?php if( $pros !='' || $cons!='' ): ?>
									<div class="pros-cons-content">
										<div class="wrapper">
											<div class="item">
												<h4><?php esc_html_e('Pros', 'emarket'); ?></h4>
												<?php echo $pros; ?>
											</div>
											<div class="item">
												<h4><?php esc_html_e('Cons', 'emarket'); ?></h4>
												<?php echo $cons; ?>
											</div>
										</div>
									</div>
									<?php endif; ?>
									
									<?php if( $gameplay !='' || $gamestory!='' || $graphic!='' || $performance!='' ): ?>
									<div class="item-review-point clearfix">
										<div class="item-review-left">
											<div class="item-wraps">
												<?php if( $gameplay !='' ): ?>
												<div class="item">
													<div class="item-label">
														<span class="item-left"><?php echo esc_html__('Gameplay','emarket');?></span>
														<span class="item-right"><?php echo esc_attr( $gameplay );?></span>
													</div>
													<div class="item-content">
														<span class="percent" style="width:<?php echo esc_attr( $gameplay*10 );?>%;"></span>
													</div>
												</div>
												<?php endif; ?>
												
												<?php if( $gamestory !='' ): ?>
												<div class="item">
													<div class="item-label">
														<span class="item-left"><?php echo esc_html__('Gamestory','emarket');?></span>
														<span class="item-right"><?php echo esc_attr( $gamestory );?></span>
													</div>
													<div class="item-content">
														<span class="percent" style="width:<?php echo esc_attr( $gamestory*10 );?>%;"></span>
													</div>
												</div>
												<?php endif; ?>
												
												<?php if( $graphic !='' ): ?>
												<div class="item">
													<div class="item-label">
														<span class="item-left"><?php echo esc_html__('Graphic','emarket');?></span>
														<span class="item-right"><?php echo esc_attr( $graphic );?></span>
													</div>
													<div class="item-content">
														<span class="percent" style="width:<?php echo esc_attr( $graphic*10 );?>%;"></span>
													</div>
												</div>
												<?php endif; ?>
												
												<?php if( $performance !='' ): ?>
												<div class="item">
													<div class="item-label">
														<span class="item-left"><?php echo esc_html__('Performance','emarket');?></span>
														<span class="item-right"><?php echo esc_attr( $performance )?></span>
													</div>
													<div class="item-content">
														<span class="percent" style="width:<?php echo esc_attr( $performance*10 );?>%;"></span>
													</div>
												</div>
												<?php endif; ?>
												
											</div>
										</div>
										<div class="item-review-right">
											<div class="item-wraps">
												<canvas id="myCanvas" width="340" height="200" data-point="<?php echo esc_attr( $point );?>" data-light="<?php echo ( ( $emarket_light_layout ) ? $emarket_light_layout:''); ?>"></canvas>
											</div>
										</div>
									</div>
									
									<?php endif; ?>
									
									<?php if( $deverloper !='' || $publisher!='' || $release!='' || $platforms!='' ): ?>
									<h3 class="title-article"><?php echo esc_html__('In This Article','emarket'); ?></h3>
									<div class="in-article">
										<div class="wrap-article">
											<?php
												$image_game   = get_post_meta( $post->ID, 'second_featured_image', true);
												if ( $image_game ) {
													$image = wp_get_attachment_image_url( $image_game, array(100,150) );
												} else {
													$image = wc_placeholder_img_src();
												}
												if ( $image_game ) {
											?>
												<div class="item-image">
													<?php //echo $image_game;?>
													 <img src="<?php echo esc_url( $image ); ?>" alt="Featured Second">
												</div>
												<?php } ?>
											<div class="item-content">
												<h2><?php echo esc_attr( $name_game )?></h2>
												<?php if( $deverloper != '' ){ ?><h4><span><?php echo esc_html__('Developers','emarket');?></span><?php echo esc_attr( $deverloper )?></h4><?php } ?>
												<?php if( $publisher != '' ){ ?><h4><span><?php echo esc_html__('Publishers','emarket');?></span><?php echo esc_attr( $publisher )?></h4><?php } ?>
												<?php if( $release != '' ){ ?><h4><span><?php echo esc_html__('Release Date','emarket');?></span><?php echo esc_attr( $release )?></h4><?php } ?>
												<?php if( $platforms != '' ){ ?><h4><span><?php echo esc_html__('Platforms','emarket');?></span><?php echo esc_attr( $platforms )?></h4><?php } ?>
											</div>
										</div>
									</div>
									
									<?php endif; ?>
									
									<?php if(get_the_tag_list()) { ?>
										<div class="entry-tag single-tag pull-left">
										<span class="custom-font title-tag"><?php echo esc_html__('Tags:','emarket');?></span>
											<?php echo get_the_tag_list('','','');  ?>
										</div>
									<?php } ?>
									
									<div class="clear"></div>
									<!-- link page -->
									<?php wp_link_pages( array( 'before' => '<div class="page-links"><span class="page-links-title">' . esc_html__( 'Pages:', 'emarket' ).'</span>', 'after' => '</div>' , 'link_before' => '<span>', 'link_after'  => '</span>' ) ); ?>	
								</div>
								
								<div class="clear"></div>
								<div class="single-content-bottom clearfix">						
									<!-- Social -->
									<?php emarket_get_social() ?>
								</div>
								<nav>
									<ul class="pager">
										<li class="previous"><?php echo esc_html__( 'Previous Post', 'emarket' );?><a rel="prev" href="<?php echo get_permalink($previous) ?>"><?php echo get_the_title($previous) ?></a></li>
										<li class="next"><?php echo esc_html__( 'Next Post', 'emarket' ); ?><a rel="next" href="<?php echo get_permalink($next) ?>"><?php echo get_the_title($next) ?></a></li>
									</ul>
								</nav> 

								<div class="clearfix"></div>
									<!-- Comment Form -->
								<div class="comment-style">
									<!-- Comment Form -->
									<?php comments_template('/comments.php'); ?>
								</div>
							</div>
							<div class="sidebar-single-detail clearfix">
								<div class="wrap-sidebar">
									<?php echo do_shortcode( '[elementor-template id="4673"]' ); ?>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
			
			<?php endwhile; ?>
		</div>
	</div>	
</div>
<?php get_footer(); ?>
<script>
(function($) {
	"use strict";
	$(window).on( 'load',function(){
		var canvas = document.getElementById('myCanvas');
		if( canvas  != null ){
			var context = canvas.getContext('2d');
			var al=0.00;
			var start=4.72;
			var cw=context.canvas.width/2;
			var ch=context.canvas.height/2;
			var diff;
			var data = $('#myCanvas').data( 'point' );
			var light = $('#myCanvas').data( 'light' );
			function progressBar(){
				diff=(al/10.6)*Math.PI*2;
				context.clearRect(0,0,400,200);
				context.beginPath();
				context.arc(cw,ch,80,0,2*Math.PI,false);
				context.fillStyle='transparent';
				context.fill();
				if( light == 'light' ){
					context.strokeStyle='#f5f5f5';
				}else{
					context.strokeStyle='#222';
				}
				context.stroke();
				context.fillStyle='#eb0036';
				context.strokeStyle='#eb0036';
				context.textAlign='center';
				context.lineWidth=16;
				context.font = '700 40px DM Sans';
				context.beginPath();
				context.arc(cw,ch,80,start,diff+start,false);
				context.stroke();
				context.fillText(data,cw+2,ch+14);
				context.textBaseline = "alphabetic";
				if(al >= data){
					clearTimeout(bar);
				}
				 
				al++;
			}
			 
			var bar=setInterval(progressBar,50);
		}
	});
})(jQuery);
</script>
