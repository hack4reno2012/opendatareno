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

 	  <h2 class = "pagetitle">All Datasets</h2>

			<?php 
				$args=array('posts_per_page' => '-1', 'orderby' => 'post_title', 'order' => 'asc' );
				$allposts = new WP_Query($args); ?>
			<?php if ( $allposts->have_posts() ) : ?>
			<ul>
				<?php while( $allposts->have_posts() ) : $allposts->the_post(); ?>
					<li><a href="<?php the_permalink(); ?>" title=""><?php the_title(); ?></a></li>
				<?php endwhile; ?>
				<?php wp_reset_postdata(); ?>
			</ul>
			<?php endif; ?>

	</div>



<?php get_sidebar('standard'); ?>
<?php get_footer(); ?>
