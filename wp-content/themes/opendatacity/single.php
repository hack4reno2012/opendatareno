<?php get_header(); ?>

	<div id="content">

	<?php if (have_posts()) : ?>

		<?php while (have_posts()) : the_post(); ?>

			<div class="post" id="post-<?php the_ID(); ?>">
							
				<h1><a href="<?php the_permalink() ?>" rel="bookmark" title="Permanent Link to <?php the_title_attribute(); ?>"><?php the_title(); ?></a></h1>  

				<div id = "data_wrapper"> 
			    
					<div id = "data_info">
						<div id = "data_image">
							<?php the_post_thumbnail('thumbnail'); ?> 
						</div>
						<h2>Information</h2>
						<p><strong>Organization </strong><br /><?php get_custom_field('organization', TRUE); ?></p>
						<p><strong>Categories </strong><br />
						<?php    
						
						$categories= get_categories();
						$i = 0;
						$size = sizeof($categories);
						 
 						 foreach ($categories as $category) {
							if ($category->category_nicename != "nominations" && $category->category_nicename != "apps") {
	                        	$option = '<a href = "/category/' . $category->category_nicename.'">';
							  	$option .= $category->cat_name;
								$option .= '</a>';       
							    if($i+1 != $size)
									$option .= ', ';
								echo $option;
								$i++; 
							} 
							else
							{
								$size = $size - 1;
							}
						  }
						?>
						</p>
						<p><strong>Description</strong><br /><?php the_content(); ?></p> 
						<p><strong>Update Frequency </strong><br /><?php get_custom_field('update_frequency', TRUE); ?></p>
						<p><strong>Valid Time Period </strong><br /><?php get_custom_field('timeperiod', TRUE); ?></p>
						<p><strong>Release Date </strong><br /><?php get_custom_field('release_date', TRUE); ?></p> 
						      
					</div>
				
					<div id = "data_detail">
						<h2>Data</h2>
						<p><a href = "<?php get_custom_field('website_url', TRUE); ?>" target = "_blank"><?php the_title(); ?></a></p>

					</div>   
				
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
