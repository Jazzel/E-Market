<?php get_header(); ?>

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
		<?php if ( is_active_sidebar('left-blog') && emarket_sidebar_template() == 'left' ):
			$emarket_left_span_class = 'col-lg-'.emarket_options()->getCpanelValue('sidebar_left_expand');
			$emarket_left_span_class .= ' col-md-'.emarket_options()->getCpanelValue('sidebar_left_expand_md');
			$emarket_left_span_class .= ' col-sm-'.emarket_options()->getCpanelValue('sidebar_left_expand_sm');
		?>
		<aside id="left" class="sidebar <?php echo esc_attr($emarket_left_span_class); ?>">
			<?php dynamic_sidebar('left-blog'); ?>
		</aside>
		<?php endif; ?>

	
		<div class="single single-style3 main <?php emarket_content_blog(); ?>" >
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
					<div class="entry-meta clearfix">
						<span class="entry-date-style"><a href="<?php echo get_permalink($post->ID)?>"><?php echo get_the_date( '', $post->ID );?></a></span>
						<span class="entry-author">
							<?php esc_html_e('Post by:', 'emarket'); ?> <?php the_author_posts_link(); ?>
						</span>
					</div>
					<h1 class="entry-title clearfix"><?php the_title(); ?></h1>
					<div class="entry-content clearfix">
						<div class="entry-summary single-content ">
							<?php the_content(); ?>
							
							<div class="clear"></div>
							<!-- link page -->
							<?php wp_link_pages( array( 'before' => '<div class="page-links"><span class="page-links-title">' . esc_html__( 'Pages:', 'emarket' ).'</span>', 'after' => '</div>' , 'link_before' => '<span>', 'link_after'  => '</span>' ) ); ?>	
						</div>
						
						<div class="clear"></div>
						<div class="single-content-bottom clearfix">						
							<!-- Social -->
							<?php emarket_get_social() ?>
						</div>
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
