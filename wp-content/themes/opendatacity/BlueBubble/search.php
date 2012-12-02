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
		<h2 class="pagetitle"><?php _e('Archive for the', 'BlueBubble') ?> &#8216;<?php single_cat_title(); ?>&#8217; <?php _e('Category', 'BlueBubble') ?></h2>
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
    
    <?php $posts=query_posts($query_string . '&posts_per_page=-1'); ?>
	<?php if (have_posts()) : ?>

<h2 class="pagetitle"><?php echo _e('Search Results for', 'BlueBubble') ?> <?php /* Search Count */ $allsearch = &new WP_Query("s=$s&showposts=-1"); $key = wp_specialchars($s, 1); $count = $allsearch->post_count; ''; '<span class="search-terms">'; echo $key; '</span>'; _e(': '); echo $count . ' '; _e('articles'); wp_reset_query(); ?></h2>

		<?php while (have_posts()) : the_post(); ?>

			<div class="postsingle" id="post-<?php the_ID(); ?>">

<?php $title = get_the_title(); $keys= explode(" ",$s); $title = preg_replace('/('.implode('|', $keys) .')/iu', '<strong class="search-excerpt">\0</strong>', $title); ?>							
<h1><a href="<?php the_permalink() ?>" rel="bookmark" title="Permanent Link to <?php the_title_attribute(); ?>"><?php echo $title; ?></a></h1>
				
				<p class="postmetadata">by <?php the_author(); ?> on <?php the_time('l, j F Y') ?>  | <?php comments_popup_link (__('No Comments', 'BlueBubble'), __('1 Comment', 'BlueBubble'), __('% Comments', 'BlueBubble')); ?> | <?php the_tags(); ?> </p>

				
				<div class="entry">
					<?php the_content (__('Read the rest of this entry &raquo;', 'BlueBubble')); ?>
				</div>
				
				
			</div>
			
<?php comments_template(); ?>
			
			
			

		<?php endwhile; ?>

		

	<?php else : ?>

		<h2 class="center"><?php _e('Not Found', 'BlueBubble') ?></h2>
		<p class="center"><?php _e('Sorry, but you are looking for something that is not here.', 'BlueBubble') ?></p>
		

	<?php endif; ?>

	</div>



<?php get_sidebar('standard'); ?>
<?php get_footer(); ?>
