<?php get_header(); ?>

<div id="content">
	            	
	<div id = "hero" > 
	Access to open data allows for transparency, utility and innovation. Submit and use data on OpenDataReno to help make Reno a smarter, more engaged city.
	</div>       

 		<div id = "mainsearch">
			<form method="get" id="searchform" action="<?php bloginfo('home'); ?>/">
				<div><input type="text" size="18" name="s" id="s" placeholder = "Search for data" />
				<input type="submit" id="searchsubmit" value="Search" class="btn" />
				</div>
			</form>
		</div>
		
		<div id = "slider">
			<a href = "http://hack4reno.com" target = "_blank"><img src = "http://opendatareno.org/wp-content/uploads/2011/10/hack4reno.png" /></a>
		</div>
		
		<div id = "columns"> 
			<div id = "leftHome">      
			<center>
				<script src="http://widgets.twimg.com/j/2/widget.js"></script>
<script>
new TWTR.Widget({
  version: 2,
  type: 'profile',
  rpp: 5,
  interval: 30000,
  width: 250,
  height: 300,
  theme: {
    shell: {
      background: '#333333',
      color: '#ffffff'
    },
    tweets: {
      background: '#000000',
      color: '#ffffff',
      links: '#0aceff'
    }
  },
  features: {
    scrollbar: false,
    loop: false,
    live: false,
    hashtags: true,
    timestamp: true,
    avatars: true,
    behavior: 'all'
  }
}).render().setUser('opendatareno').start();
</script>
</center>
			</div> 
			<div id = "rightHome">
				<div style = "margin-bottom: 20px">
					<a href = "/submit-your-data" class = "submissionButton">Submit data</a>
				</div>
				
				<div>
					<h2>Recent Additions</h2>
					<?php $recent = new WP_Query( 'posts_per_page=7' ); ?>
					<?php if ( $recent->have_posts() ) : ?>
					<ul>
						<?php while( $recent->have_posts() ) : $recent->the_post(); ?>
							<li><a href="<?php the_permalink(); ?>" title=""><?php the_title(); ?></a></li>
						<?php endwhile; ?>
						<?php wp_reset_postdata(); ?>
					</ul>
					<?php endif; ?>
				</div>
			</div>
			<div style = "clear: both"></div>
		</div>
	
	</div> 


<?php get_sidebar('standard'); ?>
<?php get_footer(); ?>

