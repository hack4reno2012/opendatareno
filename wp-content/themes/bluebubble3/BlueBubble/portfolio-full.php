<?php
/*
Template Name: Portfolio-Full
*/
?>

<?php get_header(); ?>

<?php
	if ( get_option('bb_portfolio_num') ) {  $portfolio_num = get_option('bb_portfolio_num'); 
    }else{ $portfolio_num = '6'; } //sets number of portfolio items per page
?>
				
<?php
//allows the theme to get info from the theme options page
global $options;
foreach ($options as $value) {
    if (get_settings( $value['id'] ) === FALSE) { $$value['id'] = $value['std']; } else { $$value['id'] = get_settings( $value['id'] ); }
}
?>

       <div id="content-full">
         
	<?php if (have_posts()) : ?>
    
	 <?php
	 /* Mali Studio dummy fix */
	if (is_front_page()){
		$bb_portfolio_page_param = 'page';
		}	 	
	else{
		$bb_portfolio_page_param = 'paged';
		}
		
	 $paged = (get_query_var($bb_portfolio_page_param)) ? get_query_var($bb_portfolio_page_param) : 1; 
	 /* END Mali Studio dummy fix */
	 ?>  
     
    <?php  query_posts("paged=$paged&category_name=$bb_portfolio_cat&posts_per_page=$portfolio_num"); ?>
        <?php while (have_posts()) : the_post(); ?>


            <div class="item-full" id="post-<?php the_ID(); ?>">

				
				<div class="box">
				<?php if ( get_option('bb_no_colorbox') ) { ?>
                <a href="<?php the_permalink() ?>" rel="bookmark" title="Permanent Link to <?php the_title_attribute(); ?>"> 
                <?php }else{ ?>
                <?php $thumbID = get_post_thumbnail_id($post->ID); ?>
                <a href="<?php echo wp_get_attachment_url($thumbID); ?>" rel="project"><?php } // End check for No Colorbox ?>
				<?php the_post_thumbnail('portfolio-thumb') ?>
				</a>
				
				</div>
			
				<h2><a href="<?php the_permalink() ?>" rel="bookmark" title="Permanent Link to <?php the_title_attribute(); ?>"><?php short_title('**','**',true, '41'); ?></a></h2>
				
				<div class="entry">
					<p><?php echo substr(strip_tags($post->post_content), 0, 120); ?>... <a href="<?php the_permalink(); ?>">
					<?php _e('Details', 'BlueBubble') ?></a></p>
				</div>

			</div>

		<?php endwhile; ?>

		<!-- if you set portfolio.php as the homepage via wp-admin the pagintaion doesnt't work. -->
		<div class="navigation">
			<div class="alignleft"><?php next_posts_link (__('&larr; Older Entries', 'BlueBubble')) ?></div>
			<div class="alignright"><?php previous_posts_link (__('Newer Entries &rarr;', 'BlueBubble')) ?></div>
		</div>

	<?php else : ?>

		<h2 class="center"><?php _e('Not Found', 'BlueBubble') ?></h2>
		<p class="center"><?php _e('Sorry, but you are looking for something that is not here.', 'BlueBubble') ?></p>
		

	<?php endif; ?>
	<?php wp_reset_query(); ?>
	</div>


<?php get_footer(); ?>
