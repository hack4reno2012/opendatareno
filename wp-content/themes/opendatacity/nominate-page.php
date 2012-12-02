<?php
/*
Template Name: Nomination Page
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

			<div class="post" id="post-<?php the_ID(); ?>">
			
				<h1><a href="<?php the_permalink() ?>" rel="bookmark" title="<?php the_title_attribute(); ?>"><?php the_title(); ?></a><?php if (get_option('bb_no_tweet') == '') { ?><a href="http://twitter.com/share" class="twitter-share-button" data-count="horizontal" data-via="<?php echo get_option('bb_twitter_name') ?>">Tweet</a>
<?php } // Check for Tweet Button Off ?></h1>  

				<div class = "entry">
				<p>Do you want to see data on particular topic? We realize that OpenDataReno does not contain links to every available data resource - often because open data might not be available. Add a nomination for a type of data and vote on other nominations. Together, let's build a list of areas where we'd like to see increased availability of open data.</p> 

<p>This page is for suggesting general areas where you'd like to see more open data. If you have a specific data set to add, visit the <a href = "/submit-your-data">Submit Data</a> page.</p>
				</div>
				
				<div class="entry">

					<?php if(is_user_logged_in()) { 
						     
						the_content();  

						
					 } else {
					 	echo "<p>You must be logged in to nominate a dataset!</p>";
					} ?>   
					
										
				</div>

			</div>

		<?php endwhile; ?>

	<?php endif; ?>  
	                
			<div class="postsingle">  
			
						<?php
    					$recentPosts = new WP_Query();
						$recentPosts->query('category_name=nominations'); 
						  
						global $wpdb;
						global $post;          
					   $query = "SELECT wposts.* FROM $wpdb->posts wposts
							LEFT JOIN $wpdb->term_relationships ON (wposts.ID = $wpdb->term_relationships.object_id)
							LEFT JOIN $wpdb->term_taxonomy ON ($wpdb->term_relationships.term_taxonomy_id = $wpdb->term_taxonomy.term_taxonomy_id)
						WHERE $wpdb->term_taxonomy.taxonomy = 'category' AND $wpdb->term_taxonomy.term_id IN(23)";

						$pageposts = ShowPostByVotes($query);
						
						if ($pageposts):
							foreach ($pageposts as $post):
								setup_postdata($post);
						   ?>						
						
                                   
						<div style = "clear: both" class = "nomination">
							<div style = "float: left" class = "nominationVotes">
								<?php DisplayVotes(get_the_ID()); ?>
							</div>
                            <div style = "float: left" class = "nominationDetail">
								<?php the_content(); ?>
							<small>Nominated by <strong><?php the_author(); ?></strong></small> 
							</div>  
							<div style = "clear: both"></div>
						 </div> 

						<?php
						  
							endforeach;
						endif;
						
						?>
                      
						</div>
	</div>



<?php get_sidebar('standard'); ?>
<?php get_footer(); ?>
