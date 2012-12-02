<?php get_header(); ?>

<?php
//allows the theme to get info from the theme options page
global $options;
foreach ($options as $value) {
    if (get_settings( $value['id'] ) === FALSE) { $$value['id'] = $value['std']; } else { $$value['id'] = get_settings( $value['id'] ); }
}
?>

	<div id="content">

<?php $post = $posts[0]; // Hack. Set $post so that the_date() works. ?>
 	  <?php /* If this is a category archive */ if (is_category()) { ?>
		<h1 class="pagetitle"><?php single_cat_title(); ?></h1>
 	  <?php /* If this is a tag archive */ } elseif( is_tag() ) { ?>
		<h2 class="pagetitle"><?php _e('Posts Tagged', 'BlueBubble') ?> &#8216;<?php single_tag_title(); ?>&#8217;</h2>
 	  <?php /* If this is a daily archive */ } elseif (is_day()) { ?>
		<h2 class="pagetitle"><?php _e('Archive for', 'BlueBubble') ?> <?php the_time('F jS, Y'); ?></h2>
 	  <?php /* If this is a monthly archive */ } elseif (is_month()) { ?>
		<h2 class="pagetitle"><?php _e('Archive for', 'BlueBubble') ?> <?php the_time('F, Y'); ?></h2>
 	  <?php /* If this is a yearly archive */ } elseif (is_year()) { ?>
		<h2 class="pagetitle"><?php _e('Archive for', 'BlueBubble') ?> <?php the_time('Y'); ?></h2>
	  <?php /* If this is an author archive */ } elseif (is_author()) { ?>
		<h2 class="pagetitle"><?php _e('Author Archive', 'BlueBubble') ?></h2>
 	  <?php /* If this is a paged archive */ } elseif (isset($_GET['paged']) && !empty($_GET['paged'])) { ?>
		<h2 class="pagetitle"><?php _e('Blog Archives', 'BlueBubble') ?></h2>
 	  <?php } ?>

	<?php if (have_posts()) : ?>

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
			
<?php comments_template(); ?>
			
			
			

		<?php endwhile; ?>

		

	<?php else : ?>

		<h3 class="center"><?php _e('Open Data Reno does not yet have data in this category!', 'BlueBubble') ?></h3>
		<p><a href = "/submit-your-data" class = "submissionButton">Submit data</a></p>  
		<p style = "margin-top: 20px">Do you have any data to submit? You can <a href = "/nominate-data">nominate data</a> or <a href = "/submit-your-data">submit data</a> to help the project!</p>

		

	<?php endif; ?>

	</div>



<?php get_sidebar('standard'); ?>
<?php get_footer(); ?>
