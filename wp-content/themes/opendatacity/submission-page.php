post<?php
/*
Template Name: Submission Page
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

		<?php while (have_posts()) : the_post(); ?>

			<div class="postsingle" id="post-<?php the_ID(); ?>">
			
				<h1><a href="<?php the_permalink() ?>" rel="bookmark" title="<?php the_title_attribute(); ?>"><?php the_title(); ?></a><?php if (get_option('bb_no_tweet') == '') { ?><a href="http://twitter.com/share" class="twitter-share-button" data-count="horizontal" data-via="<?php echo get_option('bb_twitter_name') ?>">Tweet</a>
<?php } // Check for Tweet Button Off ?></h1>
				
				<div class="entry">
					
					<?php if(is_user_logged_in()) { 
						          
						?> 
						
						<p>We realize that we haven't listed every Reno-based open data set in the OpenDataReno catalog. If have a specific data set that you think should be included, let us know by completing and submitting the form below. We'll review the information and determine whether the data set should be included in OpenDataReno. The data must be specific to Reno, available online, and contain structured data. A full list of review criteria is available on the <a href = "/faq">FAQ</a> page.</p> 

<p>This form is for recommending specific data sets, applications, or APIs. If you'd like to nominate a general area where you think data should be more easily available, visit the <a href = "/nominate-data">Nominate Data</a> page.</p>
						<?php
						
						the_content();
						
					 } else {
					 	echo "<p>You must be logged in to submit a dataset, API or application!</p>";
					} ?>
					
					<a class="homelink" title="<?php echo bloginfo('blog_name'); ?>" href="<?php echo get_option('home'); ?>/"> &larr; <?php _e('Back') ?> </a>
					
				</div>

			</div>

		<?php endwhile; ?>

		

	<?php else : ?>

		<h2 class="center"><?php _e('Not Found', 'BlueBubble') ?></h2>
		<p class="center"><?php _e('Sorry, but you are looking for something that is not here.', 'BlueBubble') ?></p>


	<?php endif; ?>

	</div>



<?php get_sidebar('standard'); ?>
<?php get_footer(); ?>
