<?php
/*
Template Name: All Datasets Page
*/
?>
<?php get_header(); ?>

<?php
//allows the theme to get info from the theme options page
global $options;
foreach ($options as $value) {
    if (get_settings( $value['id'] ) === FALSE) { $$value['id'] = $value['std']; } else { $$value['id'] = get_settings( $value['id'] ); }
}
?>

	<div id="content">

 	  <h1 class = "pagetitle">All Datasets</h1>

			<?php 
				$args=array('posts_per_page' => '-1', 'orderby' => 'title', 'order' => 'asc' );
				$allposts = new WP_Query($args); ?>
			<?php if ( $allposts->have_posts() ) : ?>
				<?php while( $allposts->have_posts() ) : $allposts->the_post(); ?>
					<div class="datasetsingle" id="post-<?php the_ID(); ?>"> 
			        
						<div class = "thumb">
							<a href="<?php the_permalink() ?>" rel="bookmark" title="View detail"><?php the_post_thumbnail('thumbnail'); ?></a> 
						</div>
								
						<h2><a href="<?php the_permalink() ?>" rel="bookmark" title="View detail"><?php the_title(); ?></a><?php if (get_option('bb_no_tweet') == '') { ?><a href="http://twitter.com/share" class="twitter-share-button" data-count="horizontal" data-via="<?php echo get_option('bb_twitter_name') ?>">Tweet</a>
	<?php } ?></h2>
					
					<div style = "clear: both"></div>
 
					</div>


				<?php endwhile; ?>
				<?php wp_reset_postdata(); ?>
			<?php endif; ?>

	</div>



<?php get_sidebar('standard'); ?>
<?php get_footer(); ?>
