<?php
/*
Template Name: Nomination Page
*/
?>

<?php get_header(); ?>

<div id="content">

	<?php if (have_posts()) : ?>

		<?php while (have_posts()) : the_post(); ?>

			<div class="post" id="post-<?php the_ID(); ?>">
			
				<h1><a href="<?php the_permalink() ?>" rel="bookmark" title="<?php the_title_attribute(); ?>"><?php the_title(); ?></a></h1>

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
						global $wpdb;
						global $post;          
					   $query = "SELECT wposts.* FROM $wpdb->posts wposts
						WHERE wposts.post_type = 'nomination' AND wposts.post_status = 'publish'";

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
