<?php get_header(); ?>

<div class="emarket_breadcrumbs">
	<div class="container">
		<div class="listing-title">			
			<h1><span><?php emarket_title(); ?></span></h1>				
		</div>
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
		<?php if ( is_active_sidebar('left-blog') && emarket_sidebar_template() == 'left' ):
			$emarket_left_span_class = 'col-lg-'.emarket_options()->getCpanelValue('sidebar_left_expand');
			$emarket_left_span_class .= ' col-md-'.emarket_options()->getCpanelValue('sidebar_left_expand_md');
			$emarket_left_span_class .= ' col-sm-'.emarket_options()->getCpanelValue('sidebar_left_expand_sm');
		?>
		<aside id="left" class="sidebar <?php echo esc_attr($emarket_left_span_class); ?>">
			<?php dynamic_sidebar('left-blog'); ?>
		</aside>
		<?php endif; ?>

	
		<div class="single main <?php emarket_content_blog(); ?>" >
			<?php while (have_posts()) : the_post(); ?>
			<?php $related_post_column = emarket_options()->getCpanelValue('sidebar_blog'); ?>
			<div <?php post_class(); ?>>
				<?php $pfm = get_post_format();?>
				<div class="entry-wrap">
					<?php if( $pfm == '' || $pfm == 'image' ){?>
						<?php if( has_post_thumbnail() ){ ?>
							<div class="entry-thumb single-thumb">
								<?php the_post_thumbnail('full'); ?>
							</div>
						<?php }?>
					<?php } ?>
					<div class="entry-content clearfix">
						<div class="entry-meta clearfix">
							<span class="month-time"><a href="<?php echo get_permalink($post->ID)?>"><i class="fa fa-clock-o"></i><?php echo get_the_date( '', $post->ID );?></a></span>
							<span class="entry-author">
								<i class="fa fa-user"></i><?php esc_html_e('Post By:', 'emarket'); ?> <?php the_author_posts_link(); ?>
							</span>
							<div class="entry-comment">
								<a href="<?php comments_link(); ?>">
									<i class="fa fa-comments-o"></i>
									<?php echo $post->comment_count . ( ( $post->comment_count > 1 ) ? esc_html__(' Comments ', 'emarket') : esc_html__(' Comment ', 'emarket') ); ?>
								</a>
							</div>
							<?php if( ! has_post_thumbnail() ){ ?>
								<span class="entry-date">
									<i class="fa fa-clock-o"></i><?php echo ( get_the_title() ) ? date_i18n( 'M j, Y',strtotime($post->post_date)) : '<a href="'.get_the_permalink().'">'.date_i18n( 'l, F j, Y',strtotime($post->post_date)).'</a>'; ?>
								</span>
							<?php } ?>
							<?php if(get_the_tag_list()) { ?>
								<div class="entry-tag single-tag pull-left">
									<?php echo get_the_tag_list('<i class="fa fa-tags"></i><span class="custom-font title-tag"></span>',' , ','');  ?>
								</div>
							<?php } ?>
						</div>
						<h1 class="entry-title clearfix"><?php the_title(); ?></h1>
						<div class="entry-summary single-content ">
							<?php the_content(); ?>
							
							<div class="clear"></div>
							<!-- link page -->
							<?php wp_link_pages( array( 'before' => '<div class="page-links"><span class="page-links-title">' . esc_html__( 'Pages:', 'emarket' ).'</span>', 'after' => '</div>' , 'link_before' => '<span>', 'link_after'  => '</span>' ) ); ?>	
						</div>
						
						<div class="clear"></div>
					</div>
				</div>
				

					
					<div class="clearfix"></div>
					<!-- Comment Form -->
					<?php comments_template('/comments.php'); ?>
			</div>
			<?php endwhile; ?>
		</div>

		<?php if ( is_active_sidebar('right-blog') && emarket_sidebar_template() == 'right' ):
			$emarket_right_span_class = 'col-lg-'.emarket_options()->getCpanelValue('sidebar_right_expand');
			$emarket_right_span_class .= ' col-md-'.emarket_options()->getCpanelValue('sidebar_right_expand_md');
			$emarket_right_span_class .= ' col-sm-'.emarket_options()->getCpanelValue('sidebar_right_expand_sm');
		?>
		<aside id="right" class="sidebar <?php echo esc_attr( $emarket_right_span_class ); ?>">
			<?php dynamic_sidebar('right-blog'); ?>
		</aside>
		<?php endif; ?>
	</div>	
</div>
<?php get_footer(); ?>
