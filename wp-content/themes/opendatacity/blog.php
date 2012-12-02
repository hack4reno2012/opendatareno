<?php
/*
Template Name: Blog
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
							
				<h1><a href="<?php the_permalink() ?>" rel="bookmark" title="Permanent Link to <?php the_title_attribute(); ?>"><?php the_title(); ?></a><?php if (get_option('bb_no_tweet') == '') { ?><a href="http://twitter.com/share" class="twitter-share-button" data-count="horizontal" data-via="<?php echo get_option('bb_twitter_name') ?>">Tweet</a>
<?php } // Check for Tweet Button Off ?></h1>
				
				<p class="postmetadata"><?php if (get_option('bb_last_updated') ) { ?><?php $u_time = get_the_time('U'); $u_modified_time = get_the_modified_time('U'); if ($u_modified_time != $u_time) { echo __("(Updated: "); the_modified_time('j.m.y'); echo ") | "; } ?><?php } // End check for Last Updated ?><?php _e('by', 'BlueBubble') ?> <?php the_author_posts_link(); ?> <?php _e('on', 'BlueBubble') ?> <?php the_time('l, j F Y') ?> | <img src="<?php echo get_bloginfo('template_directory'); ?>/images/comments.png" alt="comments"> <?php comments_popup_link (__('No Comments', 'BlueBubble'), __('1 Comment', 'BlueBubble'), __('% Comments', 'BlueBubble')); ?> | <?php the_tags(); ?> </p>

				
				<div class="entry">
					<?php the_content (__('Read the rest of this entry &raquo;', 'BlueBubble')); ?>
				</div> 
				
				
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


<?php get_sidebar('standard'); ?>
<?php get_footer(); ?>

