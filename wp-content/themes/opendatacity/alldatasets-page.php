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


	<?php if (have_posts()) : ?>
	 <?php $paged = (get_query_var('paged')) ? get_query_var('paged') : 1; ?>

        <?php while (have_posts()) : the_post(); ?>

			<div class="postsingle" id="post-<?php the_ID(); ?>"> 
			        
				<div class = "thumb">
					<a href="<?php the_permalink() ?>" rel="bookmark" title="View detail"><?php the_post_thumbnail('thumbnail'); ?></a> 
				</div>
							
				<h2><a href="<?php the_permalink() ?>" rel="bookmark" title="View detail"><?php the_title(); ?></a><?php if (get_option('bb_no_tweet') == '') { ?><a href="http://twitter.com/share" class="twitter-share-button" data-count="horizontal" data-via="<?php echo get_option('bb_twitter_name') ?>">Tweet</a>
<?php } ?></h2>
				
				<div class="entry">
					<?php the_excerpt(); ?> 
					
					<a class = "viewDetail" href="<?php the_permalink() ?>" rel="bookmark" title="Permanent Link to <?php the_title_attribute(); ?>">View Detail</a>
				</div> 
				
				<div style = "clear: both"></div>
 
			</div>

		<?php endwhile; ?>

		<div class="navigation">
			<div class="alignleft"><?php next_posts_link (_e('&larr; Older Entries', 'BlueBubble')); ?></div>
			<div class="alignright"><?php previous_posts_link (_e('Newer Entries &rarr;', 'BlueBubble')); ?></div>
		</div> 

	<?php else : ?>

		<h2 class="center"><?php _e('Not Found', 'BlueBubble') ?></h2>
		<p class="center"><?php _e('Sorry, but you are looking for something that is not here.', 'BlueBubble') ?></p>
		

	<?php endif; ?>

	<?php wp_reset_query(); ?>
	</div> 

	</div>



<?php get_sidebar('standard'); ?>
<?php get_footer(); ?>
